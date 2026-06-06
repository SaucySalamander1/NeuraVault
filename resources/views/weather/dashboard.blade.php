<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard - NeuraVault</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 to-slate-800 text-gray-100 min-h-screen">
    <div id="app" x-data="weatherDashboard()" x-init="init()" class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 mb-2">
                    🌤️ Weather Intelligence Platform
                </h1>
                <p class="text-gray-400">Real-time weather analytics and forecasting powered by Open-Meteo</p>
            </div>

            <!-- Search Bar -->
            <div class="bg-slate-800 rounded-lg shadow-lg p-4 mb-8 border border-slate-700">
                <div class="flex gap-2 flex-col sm:flex-row">
                    <div class="flex-1 relative">
                        <input 
                            type="text" 
                            x-model="searchQuery" 
                            @input="searchLocations()"
                            @keydown.enter="fetchWeather()"
                            placeholder="Search for a city or location..."
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                        >
                        
                        <!-- Search Results Dropdown -->
                        <div x-show="searchResults.length > 0 && searchQuery.length > 0" 
                             @click.away="searchResults = []"
                             class="absolute top-full left-0 right-0 mt-2 bg-slate-700 border border-slate-600 rounded-lg shadow-lg z-10 max-h-64 overflow-y-auto">
                            <template x-for="result in searchResults" :key="result.name">
                                <button 
                                    @click="selectLocation(result)"
                                    class="w-full text-left px-4 py-2 hover:bg-slate-600 border-b border-slate-600 last:border-b-0 transition-colors"
                                >
                                    <div class="font-semibold" x-text="result.name"></div>
                                    <div class="text-sm text-gray-400" x-text="result.country"></div>
                                </button>
                            </template>
                        </div>
                    </div>
                    <button 
                        @click="fetchWeather()"
                        :disabled="loading"
                        class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-lg font-semibold hover:from-cyan-600 hover:to-blue-600 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!loading">Search</span>
                        <span x-show="loading" class="inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Current Weather Card -->
            <template x-if="currentWeather">
                <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-xl shadow-2xl p-8 mb-8 border border-slate-600">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Weather Info -->
                        <div class="md:col-span-2">
                            <div class="flex items-start justify-between mb-6">
                                <div>
                                    <h2 class="text-3xl font-bold mb-2">
                                        <span x-text="currentWeather.location"></span>, <span x-text="currentWeather.country" class="text-gray-400"></span>
                                    </h2>
                                    <p class="text-gray-400" x-text="new Date(currentWeather.time).toLocaleString()"></p>
                                </div>
                                <div class="text-6xl" x-text="currentWeather.icon"></div>
                            </div>

                            <div class="mb-6">
                                <div class="flex items-baseline">
                                    <span class="text-6xl font-bold" x-text="Math.round(currentWeather.temperature) + '°C'"></span>
                                    <span class="text-2xl text-gray-400 ml-2" x-text="currentWeather.condition"></span>
                                </div>
                                <p class="text-gray-400 mt-2">Feels like <span x-text="Math.round(currentWeather.apparent_temperature) + '°C'"></span></p>
                            </div>

                            <!-- Weather Details Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                    <p class="text-gray-400 text-sm mb-1">Humidity</p>
                                    <p class="text-2xl font-bold" x-text="currentWeather.humidity + '%'"></p>
                                </div>
                                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                    <p class="text-gray-400 text-sm mb-1">Wind Speed</p>
                                    <p class="text-2xl font-bold" x-text="Math.round(currentWeather.wind_speed) + ' km/h'"></p>
                                </div>
                                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                    <p class="text-gray-400 text-sm mb-1">Precipitation</p>
                                    <p class="text-2xl font-bold" x-text="currentWeather.precipitation + ' mm'"></p>
                                </div>
                                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                    <p class="text-gray-400 text-sm mb-1">UV Index</p>
                                    <p class="text-2xl font-bold" x-text="currentWeather.uv_index.toFixed(1)"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Info -->
                        <div class="space-y-4">
                            <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                <p class="text-gray-400 text-sm mb-2">Coordinates</p>
                                <p class="text-sm font-mono" x-text="`${currentWeather.coordinates.latitude.toFixed(2)}°, ${currentWeather.coordinates.longitude.toFixed(2)}°`"></p>
                            </div>
                            <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                <p class="text-gray-400 text-sm mb-2">Timezone</p>
                                <p class="text-sm" x-text="currentWeather.timezone"></p>
                            </div>
                            <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                <p class="text-gray-400 text-sm mb-2">Wind Direction</p>
                                <p class="text-sm" x-text="currentWeather.wind_direction + '°'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 7-Day Forecast -->
            <template x-if="forecast.length > 0">
                <div>
                    <h3 class="text-2xl font-bold mb-6">7-Day Forecast</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
                        <template x-for="day in forecast" :key="day.date">
                            <div class="bg-gradient-to-br from-slate-800 to-slate-700 rounded-lg shadow-lg p-4 border border-slate-600 hover:border-cyan-400 transition-colors">
                                <p class="text-gray-400 text-sm mb-3" x-text="new Date(day.date).toLocaleDateString('en-US', {weekday: 'short', month: 'short', day: 'numeric'})"></p>
                                <div class="text-3xl mb-3" x-text="day.icon"></div>
                                <p class="text-xs text-gray-400 mb-3" x-text="day.condition"></p>
                                
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">High</span>
                                        <span class="font-semibold" x-text="Math.round(day.max_temperature) + '°'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Low</span>
                                        <span class="font-semibold text-blue-300" x-text="Math.round(day.min_temperature) + '°'"></span>
                                    </div>
                                    <div class="flex justify-between border-t border-slate-600 pt-2">
                                        <span class="text-gray-400">Rain</span>
                                        <span class="font-semibold" x-text="day.precipitation + ' mm'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Loading State -->
            <template x-if="loading && !currentWeather">
                <div class="flex items-center justify-center py-16">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-cyan-400 mx-auto mb-4"></div>
                        <p class="text-gray-400">Loading weather data...</p>
                    </div>
                </div>
            </template>

            <!-- Error State -->
            <template x-if="error">
                <div class="bg-red-900/20 border border-red-400 text-red-200 px-6 py-4 rounded-lg">
                    <p class="font-semibold mb-2">⚠️ Error</p>
                    <p x-text="error"></p>
                </div>
            </template>

            <!-- Info Footer -->
            <div class="mt-12 text-center text-gray-400 text-sm">
                <p>Weather data powered by <a href="https://open-meteo.com" target="_blank" class="text-cyan-400 hover:text-cyan-300">Open-Meteo</a> - Free weather API with no API key required</p>
            </div>
        </div>
    </div>

    <script>
        function weatherDashboard() {
            return {
                currentLocation: 'London',
                currentWeather: null,
                forecast: [],
                searchQuery: '',
                searchResults: [],
                loading: false,
                error: null,

                async fetchWeather() {
                    if (!this.currentLocation) {
                        this.error = 'Please enter a location';
                        return;
                    }

                    this.loading = true;
                    this.error = null;

                    try {
                        const [weatherResponse, forecastResponse] = await Promise.all([
                            fetch(`/weather/api/current/${encodeURIComponent(this.currentLocation)}`),
                            fetch(`/weather/api/forecast/${encodeURIComponent(this.currentLocation)}`)
                        ]);

                        if (!weatherResponse.ok || !forecastResponse.ok) {
                            throw new Error('Location not found or API error. Please try again.');
                        }

                        this.currentWeather = await weatherResponse.json();
                        this.forecast = await forecastResponse.json();
                    } catch (err) {
                        this.error = err.message || 'An error occurred while fetching weather data';
                        this.currentWeather = null;
                        this.forecast = [];
                    } finally {
                        this.loading = false;
                    }
                },

                async searchLocations() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/weather/api/search?q=${encodeURIComponent(this.searchQuery)}`);
                        if (!response.ok) throw new Error('Search failed');
                        this.searchResults = await response.json();
                    } catch (err) {
                        this.error = 'Search failed: ' + err.message;
                        this.searchResults = [];
                    }
                },

                selectLocation(location) {
                    this.currentLocation = location.name;
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.fetchWeather();
                },

                init() {
                    this.fetchWeather();
                }
            }
        }
    </script>
</body>
</html>
