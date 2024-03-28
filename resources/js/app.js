import './bootstrap';
// Import the core of Chart.js and the controllers, elements, scales, and plugins you need
import {
    Chart,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Filler,
    Legend,
    LineController
  } from 'chart.js';
  
  Chart.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Filler,
    Legend,
    LineController
  );

const data = {
    labels: ['6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM'], // Replace with actual hourly data points
    datasets: [{
        label: 'Temperature (°C)',
        data: [12, 14, 18, 21, 19, 15], // Replace with actual temperature data
        fill: true,
        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light blue fill
        borderColor: 'rgba(75, 192, 192, 1)', // Dark blue line
        tension: 0.3, // Adds some curve between points
        pointRadius: 5,
        pointHoverRadius: 7,
        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
    }]
};
const options = {
    maintainAspectRatio: false, // Set to false to allow custom dimensions

    responsive: true,
    scales: {
      y: {
        beginAtZero: false,
        title: {
          display: true,
          text: 'Temperature (°C)'
        },
        grid: {
          drawBorder: false,
          color: 'rgba(0, 0, 0, 0.1)'
        }
      },
      x: {
        title: {
          display: true,
          text: 'Time of Day'
        },
        grid: {
          drawBorder: false,
          color: 'rgba(0, 0, 0, 0.1)'
        }
      }
    },
    plugins: {
      legend: {
        display: true,
        position: 'top'
      },
      tooltip: {
        mode: 'index',
        intersect: false,
        callbacks: {
          label: function(context) {
            return ` ${context.dataset.label}: ${context.parsed.y}°C`;
          }
        }
      }
    },
    elements: {
      line: {
        borderWidth: 3
      },
      point: {
        borderWidth: 2,
        hoverRadius: 7,
        hoverBorderWidth: 3
      }
    },
    interaction: {
      mode: 'nearest',
      axis: 'x',
      intersect: false
    },
    animation: {
      duration: 1500
    }
  };

document.addEventListener('DOMContentLoaded', function() {

    function showLoadingIndicator(show) {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (show) {
            loadingIndicator.style.display = 'block';
        } else {
            loadingIndicator.style.display = 'none';
        }
    }

    const dailyForecast = document.getElementById('daily-forecast');
    const weeklyForecast = document.getElementById('weekly-forecast');

    // Fetch and display daily forecast initially
    fetchDailyForecast();

    // Button event listeners
    document.getElementById('daily-btn').addEventListener('click', function() {
        fetchDailyForecast();
        weeklyForecast.classList.remove('active-forecast'); // Hide weekly forecast
        dailyForecast.classList.add('active-forecast'); // Show daily forecast
    });    
    document.getElementById('weekly-btn').addEventListener('click', function() {
        fetchWeeklyForecast();
        dailyForecast.classList.remove('active-forecast'); // Hide daily forecast
        weeklyForecast.classList.add('active-forecast'); // Show weekly forecast
    });
    function fetchDailyForecast() {
        showLoadingIndicator(true);

        const dailyForecastElement = document.querySelector('#daily-forecast');
        const weeklyForecastElement = document.querySelector('#weekly-forecast');

        dailyForecast.classList.add('active-forecast');
        weeklyForecast.classList.remove('active-forecast');
        weeklyForecastElement.className = 'hidden';
        dailyForecastElement.className = '';

        if (!dailyForecastElement || !weeklyForecastElement) {
            console.error('One of the forecast elements does not exist in the DOM.');
            showLoadingIndicator(false);
            return; // Exit the function if elements don't exist
        }

        // Assuming '/api/weather/daily' is your endpoint for the daily weather data
        fetch('/api/weather/daily')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const windIcon = document.querySelector('#wind-text i');
            const humidityIcon = document.querySelector('#humidity-text i');
            // Update the content of #daily-forecast with new data
            document.getElementById('location-name').textContent = data.location.name;
            document.getElementById('temperature-text').textContent = `Temperature: ${data.current.temp_c}°C`;
            document.getElementById('condition-text').textContent = `Condition: ${data.current.condition.text}`;
            document.getElementById('weather-icon').src = data.current.condition.icon;
            document.getElementById('wind-text').textContent = `Wind: ${data.current.wind_kph} kph`;
            document.getElementById('humidity-text').textContent = `Humidity: ${data.current.humidity}%`;

            document.getElementById('date-text').textContent = new Date().toLocaleDateString();
            const latLongText = `Lat/Long: ${data.location.lat.toFixed(2)}, ${data.location.lon.toFixed(2)}`;
            document.getElementById('lat-long').textContent = latLongText;

            const hourlyData = data.forecast.forecastday[0].hour;
            console.log(hourlyData);

            const fourHourlyData = hourlyData.filter((hour, index) => index % 4 === 0);
            const chartLabels = fourHourlyData.map(hour => hour.time.substring(11));
            const chartData = fourHourlyData.map(hour => hour.temp_c);

            const chart_data = {
                labels: [data.location.localtime], // Use the time from the API data
                datasets: [{
                    label: 'Temperature (°C)',
                    data: [data.current.temp_c], // Use the actual temperature from the API
                    fill: true,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light blue fill
                    borderColor: 'rgba(75, 192, 192, 1)', // Dark blue line
                    tension: 0.3, // Adds some curve between points
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
                }]
            };
            const options = {
                maintainAspectRatio: false, // Set to false to allow custom dimensions

                responsive: true,
                scales: {
                y: {
                    beginAtZero: false,
                    title: {
                    display: true,
                    text: 'Temperature (°C)'
                    },
                    grid: {
                    drawBorder: false,
                    color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    title: {
                    display: true,
                    text: 'Time of Day'
                    },
                    grid: {
                    drawBorder: false,
                    color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
                },
                plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                    label: function(context) {
                        return ` ${context.dataset.label}: ${context.parsed.y}°C`;
                    }
                    }
                }
                },
                elements: {
                line: {
                    borderWidth: 3
                },
                point: {
                    borderWidth: 2,
                    hoverRadius: 7,
                    hoverBorderWidth: 3
                }
                },
                interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
                },
                animation: {
                duration: 1500
                }
            };

            const ctx = document.getElementById('weatherChart').getContext('2d');
            const weatherChart = new Chart(ctx, {
                type: 'line',
                data: chart_data,
                options: options
            });

            weatherChart.data.labels = chartLabels;
            weatherChart.data.datasets[0].data = chartData;
            
            // You may want to update the dataset label if you wish
            weatherChart.data.datasets[0].label = 'Temperature Every 4 Hours (°C)';
            
            // Finally, update the chart to reflect the changes
            weatherChart.update();
        })
        .catch(error => {
            console.error('Error fetching daily weather data:', error);
        }).finally(() => {
            showLoadingIndicator(false);
        });
    }

    function fetchWeeklyForecast() {
        weeklyForecast.classList.add('active-forecast');
        dailyForecast.classList.remove('active-forecast');
        showLoadingIndicator(true);
        fetch('/api/weather/weekly')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const forecastBody = document.getElementById('forecast-body');
            forecastBody.innerHTML = ''; // Clear previous content

            const rainInForecast = data.forecast.forecastday.slice(0, 3).some(day => day.day.condition.text.includes('rain'));
        
            if (rainInForecast) {
                // Show the popup
                document.getElementById('rain-popup').classList.remove('hidden');
            }

            // Check if the data includes at least some forecast days
            if (data.forecast.forecastday.length > 0) {
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

                // If less data is received than expected, display a notice
                // if (data.forecast.forecastday.length < 7) {
                //     const notice = document.createElement('div');
                //     notice.textContent = `Note: Only ${data.forecast.forecastday.length} days of data available.`;
                //     notice.style.color = 'red';
                //     forecastBody.appendChild(notice);
                // }
            } else {
                // If no data is received, display a different notice
                forecastBody.textContent = 'No forecast data available.';
            }

            weeklyForecast.classList.remove('hidden');
            dailyForecast.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error fetching weekly weather data:', error);
            forecastBody.textContent = 'Error fetching forecast data.';
        })
        .finally(() => {
            showLoadingIndicator(false);
        });
    }
    document.getElementById('close-btn').addEventListener('click', function() {
        document.getElementById('rain-popup').classList.add('hidden');
    });
});