<?php

use App\Http\Controllers\MDestinationsController;
use App\Http\Controllers\MServicesController;
use App\Http\Controllers\MServiceTypesController;
use App\Http\Controllers\TBillingInvoicesController;
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
        Route::apiResource('service_types', MServiceTypesController::class);
        Route::apiResource('services', MServicesController::class);
        Route::apiResource('destinations', MDestinationsController::class);
    });

    Route::prefix("/finance")->group(function () {
        Route::get('invoice_issued/{id}', [TBillingInvoicesController::class, 'issued']);
        Route::get('invoice/{flag}/{id}', [TBillingInvoicesController::class, 'flagPaid']);
        Route::apiResource('invoices', TBillingInvoicesController::class);
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
