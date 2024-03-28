<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">

<div class="container mx-auto py-8">
    <header class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">Weather Forecast</h1>
        <p id="weather-location" class="text-xl text-gray-500">Fetching weather data...</p>
    </header>

    <main class="bg-white rounded-lg shadow-lg p-8 md:p-12">
        <div class="mb-6">
            <h2 id="weather-condition" class="text-4xl font-semibold text-gray-700 mb-2">Loading...</h2>
            <p id="weather-temp" class="text-xl text-gray-600">--°F</p>
            <img id="weather-icon" src="" alt="Weather Icon" class="mx-auto d-block"/>
            <span id="weather-description" class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-2 rounded-full mt-4">--</span>
        </div>
    </main>

    <footer class="mt-12 text-center">
        <p class="text-sm text-gray-500 font-semibold">&copy; 2024 - Company, Inc. All rights reserved.</p>
    </footer>
</div>

@vite('resources/js/app.js')
</body>
</html>