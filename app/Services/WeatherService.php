<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected const GEOCODING_API = 'https://geocoding-api.open-meteo.com/v1/search';
    protected const WEATHER_API = 'https://api.open-meteo.com/v1/forecast';
    protected const CACHE_DURATION = 600; // 10 minutes

    /**
     * Get current weather for a location
     */
    public function getCurrentWeather(string $location): array
    {
        $cacheKey = "weather:current:{$location}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location) {
            $coordinates = $this->getCoordinates($location);

            if (!$coordinates) {
                throw new \Exception("Location '{$location}' not found");
            }

            $response = Http::timeout(10)
                ->get(self::WEATHER_API, [
                    'latitude' => $coordinates['latitude'],
                    'longitude' => $coordinates['longitude'],
                    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m,wind_direction_10m,uv_index',
                    'timezone' => $coordinates['timezone'] ?? 'auto',
                ])
                ->throw();

            return $this->formatCurrentWeather($response->json(), $coordinates, $location);
        });
    }

    /**
     * Get weather forecast for a location
     */
    public function getForecast(string $location, int $days = 7): array
    {
        $cacheKey = "weather:forecast:{$location}:{$days}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $days) {
            $coordinates = $this->getCoordinates($location);

            if (!$coordinates) {
                throw new \Exception("Location '{$location}' not found");
            }

            $response = Http::timeout(10)
                ->get(self::WEATHER_API, [
                    'latitude' => $coordinates['latitude'],
                    'longitude' => $coordinates['longitude'],
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,wind_speed_10m_max',
                    'forecast_days' => $days,
                    'timezone' => $coordinates['timezone'] ?? 'auto',
                ])
                ->throw();

            return $this->formatForecast($response->json());
        });
    }

    /**
     * Search for locations
     */
    public function searchLocations(string $query): array
    {
        $cacheKey = "weather:search:{$query}";

        return Cache::remember($cacheKey, self::CACHE_DURATION * 6, function () use ($query) {
            $response = Http::timeout(10)
                ->get(self::GEOCODING_API, [
                    'name' => $query,
                    'count' => 10,
                    'language' => 'en',
                    'format' => 'json',
                ])
                ->throw();

            $data = $response->json();

            if (!isset($data['results']) || empty($data['results'])) {
                return [];
            }

            return array_map(function ($result) {
                return [
                    'name' => $result['name'] ?? '',
                    'country' => $result['country'] ?? '',
                    'latitude' => $result['latitude'] ?? null,
                    'longitude' => $result['longitude'] ?? null,
                    'admin1' => $result['admin1'] ?? '',
                    'timezone' => $result['timezone'] ?? 'UTC',
                    'display_name' => $this->formatLocationName($result),
                ];
            }, $data['results']);
        });
    }

    /**
     * Get coordinates for a location
     */
    protected function getCoordinates(string $location): ?array
    {
        $cacheKey = "weather:coordinates:{$location}";

        return Cache::remember($cacheKey, self::CACHE_DURATION * 144, function () use ($location) {
            $response = Http::timeout(10)
                ->get(self::GEOCODING_API, [
                    'name' => $location,
                    'count' => 1,
                    'language' => 'en',
                    'format' => 'json',
                ])
                ->throw();

            $data = $response->json();

            if (!isset($data['results']) || empty($data['results'])) {
                return null;
            }

            $result = $data['results'][0];

            return [
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
                'timezone' => $result['timezone'] ?? 'UTC',
                'country' => $result['country'] ?? '',
                'admin1' => $result['admin1'] ?? '',
            ];
        });
    }

    /**
     * Format current weather data
     */
    protected function formatCurrentWeather(array $data, array $coordinates, string $location): array
    {
        $current = $data['current'] ?? [];
        $weatherCode = $current['weather_code'] ?? 0;

        return [
            'location' => $location,
            'country' => $coordinates['country'] ?? '',
            'coordinates' => [
                'latitude' => $coordinates['latitude'],
                'longitude' => $coordinates['longitude'],
            ],
            'temperature' => $current['temperature_2m'] ?? 0,
            'apparent_temperature' => $current['apparent_temperature'] ?? 0,
            'humidity' => $current['relative_humidity_2m'] ?? 0,
            'precipitation' => $current['precipitation'] ?? 0,
            'wind_speed' => $current['wind_speed_10m'] ?? 0,
            'wind_direction' => $current['wind_direction_10m'] ?? 0,
            'uv_index' => $current['uv_index'] ?? 0,
            'weather_code' => $weatherCode,
            'condition' => $this->getWeatherCondition($weatherCode),
            'icon' => $this->getWeatherIcon($weatherCode),
            'time' => $current['time'] ?? now()->toIso8601String(),
            'timezone' => $data['timezone'] ?? 'UTC',
        ];
    }

    /**
     * Format forecast data
     */
    protected function formatForecast(array $data): array
    {
        $daily = $data['daily'] ?? [];
        $forecast = [];

        if (isset($daily['time']) && is_array($daily['time'])) {
            foreach ($daily['time'] as $index => $date) {
                $weatherCode = $daily['weather_code'][$index] ?? 0;

                $forecast[] = [
                    'date' => $date,
                    'max_temperature' => $daily['temperature_2m_max'][$index] ?? 0,
                    'min_temperature' => $daily['temperature_2m_min'][$index] ?? 0,
                    'precipitation' => $daily['precipitation_sum'][$index] ?? 0,
                    'wind_speed' => $daily['wind_speed_10m_max'][$index] ?? 0,
                    'weather_code' => $weatherCode,
                    'condition' => $this->getWeatherCondition($weatherCode),
                    'icon' => $this->getWeatherIcon($weatherCode),
                ];
            }
        }

        return $forecast;
    }

    /**
     * Get weather condition string from WMO weather code
     */
    protected function getWeatherCondition(int $code): string
    {
        $conditions = [
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Foggy',
            48 => 'Depositing rime fog',
            51 => 'Light drizzle',
            53 => 'Moderate drizzle',
            55 => 'Dense drizzle',
            61 => 'Slight rain',
            63 => 'Moderate rain',
            65 => 'Heavy rain',
            71 => 'Slight snow',
            73 => 'Moderate snow',
            75 => 'Heavy snow',
            80 => 'Slight rain showers',
            81 => 'Moderate rain showers',
            82 => 'Violent rain showers',
            85 => 'Slight snow showers',
            86 => 'Heavy snow showers',
            95 => 'Thunderstorm',
            96 => 'Thunderstorm with slight hail',
            99 => 'Thunderstorm with heavy hail',
        ];

        return $conditions[$code] ?? 'Unknown';
    }

    /**
     * Get emoji icon for weather condition
     */
    protected function getWeatherIcon(int $code): string
    {
        $icons = [
            0 => '☀️',
            1 => '🌤️',
            2 => '⛅',
            3 => '☁️',
            45 => '🌫️',
            48 => '🌫️',
            51 => '🌧️',
            53 => '🌧️',
            55 => '🌧️',
            61 => '🌧️',
            63 => '⛈️',
            65 => '⛈️',
            71 => '🌨️',
            73 => '🌨️',
            75 => '🌨️',
            80 => '🌦️',
            81 => '⛈️',
            82 => '⛈️',
            85 => '🌨️',
            86 => '🌨️',
            95 => '⛈️',
            96 => '⛈️',
            99 => '⛈️',
        ];

        return $icons[$code] ?? '🌡️';
    }

    /**
     * Format location name for display
     */
    protected function formatLocationName(array $location): string
    {
        $parts = [];

        if (!empty($location['name'])) {
            $parts[] = $location['name'];
        }

        if (!empty($location['admin1']) && $location['admin1'] !== $location['name']) {
            $parts[] = $location['admin1'];
        }

        if (!empty($location['country'])) {
            $parts[] = $location['country'];
        }

        return implode(', ', $parts);
    }
}
