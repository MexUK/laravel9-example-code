<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

$redirectToShowAll = function():RedirectResponse
{
    return redirect('/vehicle/show/all');
};

Route::get('/', $redirectToShowAll);
Route::get('/vehicle/', $redirectToShowAll);
Route::get('/vehicle/show/', $redirectToShowAll);

Route::get('/vehicle/show/all', [VehicleController::class, 'showAll']);
Route::get('/vehicle/show/{id}', [VehicleController::class, 'showOne']);

Route::post('/vehicle/create/', [VehicleController::class, 'createOne']);
Route::post('/vehicle/destroy/', [VehicleController::class, 'destroyOne']);

