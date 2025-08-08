<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LocationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('ability:view-locations', only: ['index']),
            new Middleware('ability:create-locations', only: ['store']),
        ];
    }

    public function index()
    {
        $locations = Location::query()
            ->allowedFilters(['code', 'name'])
            ->paginate();

        return LocationResource::collection($locations);
    }

    public function store(StoreLocationRequest $request)
    {
        $location = Location::create($request->validated());

        return LocationResource::make($location);
    }
}
