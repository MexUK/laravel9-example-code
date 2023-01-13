<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

$redirectToShowAllVehicles = function():RedirectResponse
{
    return redirect('/vehicle/show/all');
};

Route::get('/', $redirectToShowAllVehicles);

Route::controller(VehicleController::class)->group(function() use ($redirectToShowAllVehicles)
{
    Route::get('/vehicle', $redirectToShowAllVehicles);

    Route::post('/vehicle/create', 'createOne');
    Route::post('/vehicle/destroy', 'destroyOne');

    Route::get('/vehicle/show', $redirectToShowAllVehicles);
    Route::get('/vehicle/show/all', 'showAll');
    Route::get('/vehicle/show/{id}', 'showOne');
});

