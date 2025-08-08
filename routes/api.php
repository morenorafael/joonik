<?php

use App\Http\Controllers\Api\V1\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('api/v1/locations', [LocationController::class, 'index'])->name('api.v1.locations.index')->middleware('ability:view-locations');
    Route::post('api/v1/locations', [LocationController::class, 'store'])->name('api.v1.locations.store')->middleware('ability:create-locations');
});
