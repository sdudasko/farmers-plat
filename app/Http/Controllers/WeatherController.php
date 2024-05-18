<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\WeatherService;

use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    private $apiKey = '9616e85cb48d4e489b1180957242202';
    private $baseUrl = 'http://api.weatherapi.com/v1';

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function showForecasts(Request $request)
    {
        $user = null;
        if (auth()->check()) {
            $user = auth()->user();
        }
        return view('weather-forecast', [
            'user' => $user
        ]);
    }
    
    public function getCurrentWeather(Request $request)
    {
        $cityName = $request->query('city');
        
        $response = Http::get("{$this->baseUrl}/forecast.json", [
            'key' => $this->apiKey,
            'q' => $cityName,
            'hours' => 24
        ]);

        $weatherData = $response->json();
        
        // \Log::info($weatherData);

        return response()->json($weatherData);

        // \Log::info($weatherData);

        return response()->json($weatherData);
    }

    public function getTemperature(Request $request) {

        // $city = $request->input('city', 'Bratislava');
        // $temperatureThreshold = 8.0; // Assuming a specific threshold for demonstration
        \Log::info("Tttemperature threshold: ", $request->all());

        $cityWithParams = $request->input('city');
        $parts = explode('?', $cityWithParams, 2);
        $city = $parts[0];  // This will give you 'Lendak'
    
        // Initialize $temperature_threshold with a default value or null
        $temperature_threshold = null;
    
        // Check if there are any parameters
        if (isset($parts[1])) {
            // Parse the query string to get parameters
            parse_str($parts[1], $params);
            $temperature_threshold = $params['temperature_threshold'] ?? null;
        }
    
        // Log the parsed data for verification
        \Log::info("Parsed City: ", ['city' => $city]);
        \Log::info("Parsed Temperature Threshold: ", ['temperature_threshold' => $temperature_threshold]);
    
        $weatherData = $this->fetchWeatherData($city);

        if (isset($weatherData['error'])) {
            return response()->json(['error' => $weatherData['error']], $weatherData['status']);
        }
    
        $averageTempAboveThreshold = $weatherData->contains(function ($day) use ($temperature_threshold) {
            return $day['average_temp'] > $temperature_threshold;
        });

        \Log::info("Answertemperature: " . $averageTempAboveThreshold ? "OK" : "high");

        $this->weatherService->temperatureDataObtained([
            'city' => $city,
            'temperature_threshold' => $temperature_threshold,
            'temperature' => $averageTempAboveThreshold ? "OK" : "high",
        ]);
        
        return response()->json(['temperature' => $averageTempAboveThreshold ? "OK" : "high"]);
    }
    
    public function getHumidity(Request $request) {

        $humidityThreshold = 90; // Assuming a specific threshold for demonstration

        $city = $request->input('city', 'Bratislava');

        $cityWithParams = $request->input('city');

        // Separate the city name and the parameters using explode on '?'
        $parts = explode('?', $cityWithParams, 2);
        $city = $parts[0];  // This will give you 'Lendak'
    
        // Initialize $temperature_threshold with a default value or null
        $temperature_threshold = null;
    
        // Check if there are any parameters
        if (isset($parts[1])) {
            // Parse the query string to get parameters
            parse_str($parts[1], $params);
            $temperature_threshold = $params['temperature_threshold'] ?? null;
        }
    
        // Log the parsed data for verification
        \Log::info("111Parsed City: ", ['city' => $city]);
        \Log::info("222Parsed Temperature Threshold: ", ['temperature_threshold' => $temperature_threshold]);
        
        $weatherData = $this->fetchWeatherData($city);
    
        if (isset($weatherData['error'])) {
            return response()->json(['error' => $weatherData['error']], $weatherData['status']);
        }
    
        $humidityAboveThreshold = $weatherData->contains(function ($day) use ($humidityThreshold) {
            return $day['avg_humidity'] < $humidityThreshold;
        });
    
        \Log::info("AnswerHumidity: " . ($humidityAboveThreshold ? "OK" : "high"));

        return response()->json(['humidity' => $humidityAboveThreshold ? "OK" : "high"]);
    }

    public function getWeeklyWeather(Request $request)
    {
        $cityName = $request->query('city');

        $response = Http::get("{$this->baseUrl}/forecast.json", [
            'key' => $this->apiKey,
            'q' => $cityName,
            'days' => 7,
        ]);

        $forecastData = $response->json();
        
        return response()->json($forecastData);
    }
    

    public function checkIfExtremeWeather(Request $request)
    {
        $camundaBaseUrl = 'https://5jvttxe.localto.net/engine-rest';

        $processDefinitionKey = 'humidity_temperature_check'; // Replace with your actual process definition key
        

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$camundaBaseUrl}/process-definition/key/{$processDefinitionKey}/start", [
            "variables" => [
                "Variables" => [
                    "Name" => [

                    ]
                ]
            ],
            "businessKey" => "111111"
        ]);

        $weatherData = [
            // Your weather data here...
        ];

        // Send the weather update to Kafka
        // $this->weatherService->sendWeatherUpdate($weatherData);

        return back();
        
        // $weatherData = $response->json();
    }
    
    private function fetchWeatherData($city) {
        $url = "http://api.weatherapi.com/v1/forecast.json?key={$this->apiKey}&q={$city}&days=3&aqi=no&alerts=no";
    
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $data = $response->json();
                return collect($data['forecast']['forecastday'])->map(function ($day) {
                    return [
                        'date' => $day['date'],
                        'average_temp' => $day['day']['avgtemp_c'],
                        'avg_humidity' => $day['day']['avghumidity']
                    ];
                });
            } else {
                return ['error' => 'Unable to retrieve weather data', 'status' => $response->status()];
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage(), 'status' => 500];
        }
    }

    public function getLatestWeatherAlert()
    {
        $alert = \DB::table('weather_alerts')
                    ->where('created_at', '>', now()->subMinutes(10))
                    ->where('is_shown', false)
                    ->latest()
                    ->first();
    
        if ($alert) {
            // Parse city and temperature_threshold from the city field
            if (strpos($alert->city, '?') !== false) {
                list($city, $params) = explode('?', $alert->city, 2);
                parse_str($params, $output);
                $alert->city = $city;
                $alert->temperature_threshold = $output['temperature_threshold'] ?? null;
            }
        }
    
        return response()->json($alert);
    }

    public function markAlertAsShown()
    {
        \DB::table('weather_alerts')
            // ->where('id', $id)
            ->update(['is_shown' => true]);

        return response()->json(['status' => 'success', 'message' => 'Alert marked as shown']);
    }
}