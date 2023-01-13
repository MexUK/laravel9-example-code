<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

$showAll = [VehicleController::class, 'showAll'];
Route::get('/', $showAll);
Route::get('/vehicle/', $showAll);
Route::get('/vehicle/show/', $showAll);
Route::get('/vehicle/show/all', $showAll);
Route::get('/vehicle/show/{id}', [VehicleController::class, 'showOne']);

Route::post('/vehicle/create/', [VehicleController::class, 'createOne']);
Route::post('/vehicle/destroy/', [VehicleController::class, 'destroyOne']);

