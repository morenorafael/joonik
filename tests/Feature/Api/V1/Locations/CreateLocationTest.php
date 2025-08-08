<?php

namespace Tests\Feature\Api\V1\Locations;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_location(): void
    {
        // Given
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['create-locations']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ]);

        // Then
        $this->assertDatabaseHas('locations', [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ]);

        $response->assertCreated()->assertJson([
            'data' => [
                'code' => 'SEDE-01',
                'name' => 'Location name',
                'image' => 'http://image-url',
            ],
        ]);
    }
}
