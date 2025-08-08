<?php

use App\Http\Controllers\Api\V1\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'api/v1',
    'as' => 'api.v1.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::apiResource('api/v1/locations', LocationController::class)->only(['index', 'store']);
});
