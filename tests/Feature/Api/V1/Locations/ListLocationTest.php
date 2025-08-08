<?php

namespace Tests\Feature\Api\V1\Locations;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_location(): void
    {
        // Given
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-locations']
        );

        $locations = Location::factory(3)->create();

        // Then
        $response = $this->getJson(route('api.v1.locations'));

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
}
