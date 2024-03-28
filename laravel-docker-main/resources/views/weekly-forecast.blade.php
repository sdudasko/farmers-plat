<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Weather Forecast</title>
    @vite(['resources/css/app.css'])
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
    </style>
</head>
<body class="bg-gray-100">

<div class="container mx-auto py-8">
    <header class="mb-12 text-center">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">Weekly Weather Forecast</h1>
        <p class="text-xl text-gray-500">Your 7-day weather outlook.</p>
    </header>

    <main class="bg-white rounded-lg shadow-lg p-8 md:p-12">
        <table class="forecast-table">
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
    </main>

    <footer class="mt-12 text-center">
        <p class="text-sm text-gray-500 font-semibold">&copy; 2024 - Company, Inc. All rights reserved.</p>
    </footer>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script>
fetch('/api/weather/weekly')
  .then(response => response.json())
  .then(data => {
    const forecastBody = document.getElementById('forecast-body');
    data.forecast.forecastday.forEach(day => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${day.date}</td>
        <td>
            <img src="${day.day.condition.icon}" alt="Weather Icon" class="forecast-icon"/>
            ${day.day.condition.text}
        </td>
        <td>${day.day.maxtemp_c}°C / ${day.day.mintemp_c}°C</td>
        <td>${day.day.avghumidity}%</td>
        <td>${day.day.maxwind_kph} kph</td>
      `;
      forecastBody.appendChild(row);
    });
  })
  .catch(error => {
    console.error('Error fetching weekly weather data:', error);
  });
</script>
</body>
</html>