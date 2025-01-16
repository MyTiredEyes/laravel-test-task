<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\CityController;

//сброс города из session
Route::get('/reset', function () {
    session()->forget('city');
    return redirect()->route('index');
})->name('reset');

//маршруты для случая, когда хотим, чтобы about and news не или работали (в посреднике смотри) без сущ сессии города
Route::prefix(\App\Helpers\CitySlug::getSlug())->middleware('city')->group(function () {
    
    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/about', [MainController::class, 'about'])->name('about');
    Route::get('/news', [MainController::class, 'news'])->name('news');
});

//маршруты для случая, когда хотим, чтобы about and news не работали без сущ сессии города
// Route::prefix('{city?}')->group(function () {
//     Route::get('/', [MainController::class, 'index'])->name('index');
//     Route::get('/about', [MainController::class, 'about'])->name('about');
//     Route::get('/news', [MainController::class, 'news'])->name('news');
// });

//маршруты под раб с api
Route::get('/get-countries-capital', [CityController::class, 'getCountriesCapital']);
Route::get('/get-cities', [CityController::class, 'getCities']);
