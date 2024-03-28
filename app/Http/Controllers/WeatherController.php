<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class WeatherController extends Controller
{
    private $apiKey = '9616e85cb48d4e489b1180957242202'; // Replace with your actual API key
    private $baseUrl = 'http://api.weatherapi.com/v1';

    public function showForecasts(Request $request)
    {

        return view('weather-forecast');
    }
    
    public function getCurrentWeather(Request $request)
    {
        var_dump($request->all());
        die();

        $response = Http::get("{$this->baseUrl}/forecast.json", [
            'key' => $this->apiKey,
            'q' => 'Pezinok', // Replace with dynamic location if needed
            'hours' => 24
        ]);

        $weatherData = $response->json();
        
        // Optionally, log the response or handle errors as needed
        \Log::info($weatherData);

        return response()->json($weatherData);
    }

    public function getWeeklyWeather()
    {
        $response = Http::get("{$this->baseUrl}/forecast.json", [
            'key' => $this->apiKey,
            'q' => 'Pezinok', // Replace with dynamic location if needed
            'days' => 7, // Fetching a 7-day forecast
        ]);

        $forecastData = $response->json();
        
        // Optionally, log the response or handle errors as needed
        \Log::info($forecastData);

        return response()->json($forecastData);
    }

    // Additional methods (if any)...
}