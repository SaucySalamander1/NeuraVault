<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display the weather dashboard
     */
    public function index()
    {
        $defaultLocation = 'London';
        $weatherData = $this->weatherService->getCurrentWeather($defaultLocation);
        $forecast = $this->weatherService->getForecast($defaultLocation);

        return view('weather.dashboard', [
            'currentWeather' => $weatherData,
            'forecast' => $forecast,
            'location' => $defaultLocation,
        ]);
    }

    /**
     * Get current weather for a location (API endpoint)
     */
    public function getCurrentWeather(Request $request, $location)
    {
        try {
            $weatherData = $this->weatherService->getCurrentWeather($location);
            return response()->json($weatherData);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch weather data',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get weather forecast for a location (API endpoint)
     */
    public function getForecast(Request $request, $location)
    {
        try {
            $forecast = $this->weatherService->getForecast($location);
            return response()->json($forecast);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch forecast data',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Search for locations
     */
    public function searchLocations(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'error' => 'Query must be at least 2 characters',
            ], 400);
        }

        try {
            $results = $this->weatherService->searchLocations($query);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to search locations',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
