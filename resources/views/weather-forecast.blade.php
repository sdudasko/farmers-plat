<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    @vite(['resources/css/app.css'])
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWecG8VcP/OD1sIzS7bZ9FqPNiF9i5MRvUUjBzBJvRtGFgjaVZvdzJ5ZwDvV" crossorigin="anonymous"> --}}

    <style>
        .forecast-icon {
            width: 50px;
            height: auto;
            display: inline;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('components.weather-nav')

        <div id="alertModal" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm mx-auto text-center">
                <button class="close-button absolute top-2 right-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                    <span>&times;</span>
                </button>
                <h2 class="text-xl font-semibold">Weather Alert!</h2>
                <p id="alertMessage" class="mt-4">Temperature is extreme in your area!</p>
                <button class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none shadow close-button">
                    Close
                </button>
            </div>
        </div>

        <main class="flex-grow">
            <section class="text-center py-8">
                <div id="daily-forecast" class="weather-info bg-white p-6 shadow rounded-lg my-4">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col items-center">
                            <img id="weather-icon" src="/img/weather-icon.svg" alt="Weather Icon" class="h-12 w-12">
                            <h3 id="location-name" class="font-semibold text-lg">
                                @auth
                                    {{ $user->city }}    
                                @endauth
                                @guest
                                    Bratislava
                                @endguest
                        
                        </h3>
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
                        <table class="forecast-table  bg-white">
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