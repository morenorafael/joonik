<?php

namespace Tests\Feature\Api\V1\Locations;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_location(): void
    {
        // Given
        $locations = Location::factory(3)->create();

        // Then
        $response = $this->getJson(route('api.v1.locations.index'), [
            'x-api-key' => config('app.api_key'),
        ]);

        // When
        $response->assertJson([
            'data' => [
                [
                    'id' => $locations[0]->id,
                    'code' => $locations[0]->code,
                    'name' => $locations[0]->name,
                    'image' => $locations[0]->image,
                ],
                [
                    'id' => $locations[1]->id,
                    'code' => $locations[1]->code,
                    'name' => $locations[1]->name,
                    'image' => $locations[1]->image,
                ],
                [
                    'id' => $locations[2]->id,
                    'code' => $locations[2]->code,
                    'name' => $locations[2]->name,
                    'image' => $locations[2]->image,
                ],
            ],
        ]);
    }

    public function test_can_filter_locations_by_code(): void
    {
        // Given
        Location::factory(3)->create();
        $location = Location::factory()->create([
            'code' => 'SEDE-50',
        ]);

        // Then
        $response = $this->getJson(route('api.v1.locations.index', [
            'filter' => [
                'code' => '50',
            ],
        ]), [
            'x-api-key' => config('app.api_key'),
        ]);

        // When
        $response->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $location->id,
                        'code' => 'SEDE-50',
                        'name' => $location->name,
                        'image' => $location->image,
                    ],
                ],
            ]);
    }

    public function test_can_filter_locations_by_name(): void
    {
        // Given
        Location::factory(3)->create();
        $location = Location::factory()->create([
            'name' => 'Name location',
        ]);

        // Then
        $response = $this->getJson(route('api.v1.locations.index', [
            'filter' => [
                'name' => 'name location',
            ],
        ]), [
            'x-api-key' => config('app.api_key'),
        ]);

        // When
        $response->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $location->id,
                        'code' => $location->code,
                        'name' => 'Name location',
                        'image' => $location->image,
                    ],
                ],
            ]);
    }

    public function test_cannot_filter_by_unknown_filters(): void
    {
        // Given
        Location::factory(3)->create();

        // Then
        $response = $this->getJson(route('api.v1.locations.index', [
            'filter' => [
                'unknown' => 'filter',
            ],
        ]), [
            'x-api-key' => config('app.api_key'),
        ]);

        // When
        $response->assertStatus(400);
    }

    public function test_a_guest_user_cannot_fetch_locations(): void
    {
        // Given
        // Then
        $response = $this->getJson(route('api.v1.locations.index'));

        // When
        $response->assertUnauthorized();
    }
}
