<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;


Route::get('/', [MapController::class, 'login'])->name('login');
Route::get('/home', [MapController::class, 'index'])->name('new-map');


Route::get('/region/{regionId}/report', [MapController::class, 'downloadReport'])
    ->name('region.report');

Route::get('/regions/report-all', [MapController::class, 'downloadAllReport'])
    ->name('regions.report.all');
