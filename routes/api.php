<?php

use App\Http\Controllers\MDestinationsController;
use App\Http\Controllers\MServicesController;
use App\Http\Controllers\MServiceTypesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->group(function () {
    Route::get("/", function (Request $request) {
        return response()->json("API V1 ESB SIMPLE APP");
    });
    Route::prefix("/masters")->group(function () {
        Route::resource('service_types', MServiceTypesController::class);
        Route::resource('services', MServicesController::class);
        Route::resource('destinations', MDestinationsController::class);
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
