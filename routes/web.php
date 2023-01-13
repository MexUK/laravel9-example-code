<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

$redirectToShowAll = function():RedirectResponse
{
    return redirect('/vehicle/show/all');
};

Route::get('/', $redirectToShowAll);

Route::controller(VehicleController::class)->group(function() use ($redirectToShowAll)
{
    Route::get('/vehicle', $redirectToShowAll);

    Route::post('/vehicle/create', 'createOne');
    Route::post('/vehicle/destroy', 'destroyOne');

    Route::get('/vehicle/show', $redirectToShowAll);
    Route::get('/vehicle/show/all', 'showAll');
    Route::get('/vehicle/show/{id}', 'showOne');
});

