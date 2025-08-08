<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;

class LocationController extends Controller
{
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
