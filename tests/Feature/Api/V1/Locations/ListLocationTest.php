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
            $user = User::factory()->create(),
            ['view-locations']
        );

        $locations = Location::factory(3)->create();

        // Then
        $response = $this->actingAs($user)->getJson(route('api.v1.locations'));

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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['view-locations']
        );

        Location::factory(3)->create();
        $location = Location::factory()->create([
            'code' => 'SEDE-50',
        ]);

        // Then
        $response = $this->actingAs($user)->getJson(route('api.v1.locations', [
            'filter' => [
                'code' => '50',
            ],
        ]));

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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['view-locations']
        );

        Location::factory(3)->create();
        $location = Location::factory()->create([
            'name' => 'Name location',
        ]);

        // Then
        $response = $this->actingAs($user)->getJson(route('api.v1.locations', [
            'filter' => [
                'name' => 'name location',
            ],
        ]));

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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['view-locations']
        );

        Location::factory(3)->create();

        // Then
        $response = $this->actingAs($user)->getJson(route('api.v1.locations', [
            'filter' => [
                'unknown' => 'filter',
            ],
        ]));

        // When
        $response->assertStatus(400);
    }

    public function test_a_guest_user_cannot_fetch_locations(): void
    {
        // Given
        // Then
        $response = $this->getJson(route('api.v1.locations'));

        // When
        $response->assertUnauthorized();
    }

    public function test_a_user_without_scope_cannot_fetch_locations(): void
    {
        // Given
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['other-scope']
        );

        // Then
        $response = $this->actingAs($user)->getJson(route('api.v1.locations'));

        // When
        $response->assertForbidden();
    }
}
