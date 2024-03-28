<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWecG8VcP/OD1sIzS7bZ9FqPNiF9i5MRvUUjBzBJvRtGFgjaVZvdzJ5ZwDvV" crossorigin="anonymous">

    <style>
        .forecast-table {
            width: 100%;
            text-align: center;
        }
        .forecast-table th, .forecast-table td {
            padding: 10px;
        }
        .forecast-icon {
            width: 50px;
            height: auto;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="rain-popup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Rainy Days Ahead</h3>
            <div class="mt-2 px-7 py-3">
              <p class="text-sm text-gray-500">It may be raining in the next 3 days. Don't forget your umbrella!</p>
            </div>
            <div class="items-center px-4 py-3">
              <button id="close-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    <div id="loading-indicator" class="hidden">Loading...</div>

    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-md z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo and Primary Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <!-- Logo area -->
                        <div class="flex-shrink-0 flex items-center">
                            <img class="block lg:hidden h-8 w-auto" src="/img/weather-icon.svg" alt="Logo">
                            <img class="hidden lg:block h-10 w-auto" src="/img/weather-icon.svg" alt="Logo">
                        </div>
                
                        <!-- Primary Nav Links -->
                        <div class="flex space-x-1">
                        <a href="/my-farm" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            My Farm
                        </a>
                        <a href="/planned-actions" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            Planned Actions
                        </a>
                        <a href="/community" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                            Community
                        </a>
                        </div>
                    </div>
            
                    <!-- Forecast Toggles -->
                    <div class="flex items-center space-x-4">
                        <button id="daily-btn" class="text-sm px-4 py-2 leading-5 text-blue-700 transition-colors duration-150 border border-blue-700 rounded-lg focus:shadow-outline hover:bg-blue-700 hover:text-white">
                        Daily Forecast
                        </button>
                        <button id="weekly-btn" class="text-sm px-4 py-2 leading-5 text-blue-700 transition-colors duration-150 border border-blue-700 rounded-lg focus:shadow-outline hover:bg-blue-700 hover:text-white">
                        Weekly Forecast
                        </button>
                    </div>
            
                    <!-- Log In -->
                    <div class="flex items-center">
                        <a href="/login" class="text-sm px-3 py-2 rounded-md text-gray-700 hover:text-blue-500 hover:bg-gray-100">
                        Log In
                        </a>
                    </div>
                </div>
            </div>
        </nav>


        <main class="flex-grow">
            <section class="text-center py-8">
                <div id="daily-forecast" class="weather-info bg-white p-6 shadow rounded-lg my-4">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col items-center">
                            <img id="weather-icon" src="/img/weather-icon.svg" alt="Weather Icon" class="h-12 w-12">
                            <h3 id="location-name" class="font-semibold text-lg">Bratislava</h3>
                            <p id="date-text" class="text-sm text-gray-500">Date placeholder</p>
                        </div>
                        <div>
                            <p id="temperature-text" class="text-gray-700 text-xl">Temperature: 4°C</p>
                            <p id="condition-text" class="text-gray-500">Condition: Clear</p>
                            <p id="lat-long" class="text-gray-700">Lat/Long: --, --</p>
                        </div>
                    </div>
                    <div class="flex justify-around items-center my-4">
                        <p id="wind-text" class="text-gray-500"><i class="fas fa-wind"></i> Wind: 9 kph</p>
                        <p id="humidity-text" class="text-gray-500"><i class="fas fa-tint"></i> Humidity: 75%</p>
                    </div>
                    <div id="weatherChartContainer" class="mt-4">
                        <canvas id="weatherChart"></canvas>
                    </div>
                </div>

                <!-- Weekly Forecast Section -->
                <div id="weekly-forecast" class="bg-white shadow-lg rounded-lg p-6">
                    <div class="overflow-x-auto mt-6">
                        <table class="forecast-table min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Condition</th>
                                    <th>High/Low</th>
                                    <th>Humidity</th>
                                    <th>Wind</th>
                                </tr>
                            </thead>
                            <tbody id="forecast-body">
                                <!-- Forecast rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-white py-4 mt-8">
            <div class="container mx-auto px-4">
                <p class="text-center text-gray-500 text-sm">&copy; 2024 - Company, Inc. All rights reserved.</p>
            </div>
        </footer>
    
    </div>

@vite('resources/js/app.js')
<script>
    

</script>
</body>
</html>