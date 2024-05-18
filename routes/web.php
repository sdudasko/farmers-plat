<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [WeatherController::class, 'showForecasts']);
Route::get('/weather', [WeatherController::class, 'showForecasts']);
Route::get('/abctest', [WeatherController::class, 'abctest']);
Route::get('/checkIfExtremeWeather', [WeatherController::class, 'checkIfExtremeWeather'])->name('checkIfExtremeWeather');

Route::get('/weather-alert', [WeatherController::class, 'getLatestWeatherAlert'])->name('weather-alert');
Route::post('/mark-alert-shown', [WeatherController::class, 'markAlertAsShown']);



require __DIR__.'/auth.php';
