<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Auth\LoginController;



// Route::get('/', [MapController::class, 'login'])->name('login');
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/region/{regionId}/report', [MapController::class, 'downloadReport'])
        ->name('region.report');

    Route::get('/regions/report-all', [MapController::class, 'downloadAllReport'])
        ->name('regions.report.all');
    });

Route::middleware(['auth', 'map.access'])->group(function () {

    Route::get('/home', [MapController::class, 'index'])->name('new-map');

    });





Route::get('/', [LoginController::class,'showLoginForm'])->name('login');

Route::post('/home', [LoginController::class,'login'])->name('login.submit');

Route::post('/logout', [LoginController::class,'logout'])->middleware('auth')->name('logout');

// صفحة الخريطة للمستخدمين المسجلين فقط
Route::view('/map', 'map')
    ->middleware('auth')
    ->name('map');
