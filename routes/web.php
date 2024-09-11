<?php

use App\Http\Controllers\RainfallController;
use App\Http\Controllers\WaterDataController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RainfallController::class, 'index']);
Route::post('/', [RainfallController::class, 'generateExcel']);
Route::get('/waterdata', [WaterDataController::class, 'index'])->name('qcdebit');
Route::get('/data', [DataController::class, 'index'])->name('data.index');
Route::get('/data/form', [DataController::class, 'showForm'])->name('data.form');
Route::post('/data/process', [DataController::class, 'processInput'])->name('data.process');
