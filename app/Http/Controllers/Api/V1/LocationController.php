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
        $locations = Location::query();

        $allowedFilters = ['code', 'name'];

        foreach (request('filter', []) as $filter => $value) {
            abort_unless(in_array($filter, $allowedFilters), 400);

            if ($locations->hasNamedScope($filter)) {
                $locations->{$filter}($value);
            } else {
                $locations->whereLike($filter, "%{$value}%");
            }
        }

        return LocationResource::collection($locations->paginate());
    }

    public function store(StoreLocationRequest $request)
    {
        $location = Location::create($request->validated());

        return LocationResource::make($location);
    }
}
