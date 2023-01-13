<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/vehicle/show/', [VehicleController::class, 'showAll']);
Route::get('/vehicle/show/all', [VehicleController::class, 'showAll']);
Route::get('/vehicle/show/{id}', [VehicleController::class, 'showOne']);

Route::post('/vehicle/create/', [VehicleController::class, 'createOne']);
Route::post('/vehicle/destroy/', [VehicleController::class, 'destroyOne']);

