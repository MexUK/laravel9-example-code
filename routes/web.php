<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function():RedirectResponse
{
    return redirect('/vehicle');
});

Route::fallback(function()
{
    return response()->view('/error/404', [], 404);
});

Route::controller(VehicleController::class)->group(function()
{
    Route::get('/vehicle', function():RedirectResponse
    {
        return redirect('/vehicle/show');
    });

    Route::post('/vehicle/create', 'createOne');
    Route::post('/vehicle/destroy', 'destroyOne');

    Route::get('/vehicle/show', function():RedirectResponse
    {
        return redirect('/vehicle/show/all');
    });
    Route::get('/vehicle/show/all', 'showAll');
    Route::get('/vehicle/show/ids', 'showIds');
    Route::get('/vehicle/show/{id}', 'showOne');
});

