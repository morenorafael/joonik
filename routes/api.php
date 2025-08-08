<?php

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('api/v1/locations', function () {

    $locations = Location::all()->map(function(Location $location) {
        return [
            'id' => $location->id,
            'code' => $location->code,
            'name' => $location->name,
            'image' => $location->image,
        ];
    });

    return response()->json(['data' => $locations]);
    
})->name('api.v1.locations');