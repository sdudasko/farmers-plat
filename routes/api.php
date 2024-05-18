<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/weather/getTemperature', [WeatherController::class, 'getTemperature']);
Route::get('/weather/getHumidity', [WeatherController::class, 'getHumidity']);

Route::get('/weather/daily', [WeatherController::class, 'getCurrentWeather']);
Route::get('/weather/weekly', [WeatherController::class, 'getWeeklyWeather']);
Route::get('/weather/checkIfExtremeWeather', [WeatherController::class, 'checkIfExtremeWeather']);