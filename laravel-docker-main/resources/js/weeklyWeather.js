fetch('/api/weather/weekly')
  .then(response => response.json())
  .then(data => {
    // Example: Update the DOM with weekly weather data
    // You'll need to implement this based on your specific HTML structure
    console.log('Weekly forecast data:', data);
  })
  .catch(error => {
    console.error('Error fetching weekly weather data:', error);
  });