<?php

namespace Tests\Feature\Api\V1\Locations;

use App\Models\Location;
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
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ], [
            'x-api-key' => config('app.api_key'),
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

    public function test_code_is_required(): void
    {
        // Given
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            // 'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ], [
            'x-api-key' => config('app.api_key'),
        ]);

        // Then
        $response->assertJson([
            'error' => [
                'message' => 'The code is required',
                'code' => 'E_INVALID_PARAM',
            ],
        ])->assertStatus(422);
    }

    public function test_code_is_must_be_unique(): void
    {
        // Given
        Location::factory()->create([
            'code' => 'SEDE-01',
        ]);

        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ], [
            'x-api-key' => config('app.api_key'),
        ]);

        // Then
        $response->assertJson([
            'error' => [
                'message' => 'The code has already been taken.',
                'code' => 'E_INVALID_PARAM',
            ],
        ])->assertStatus(422);
    }

    public function test_name_is_required(): void
    {
        // Given
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            // 'name' => 'Location name',
            'image' => 'http://image-url',
        ], [
            'x-api-key' => config('app.api_key'),
        ]);

        // Then
        $response->assertJson([
            'error' => [
                'message' => 'The name is required',
                'code' => 'E_INVALID_PARAM',
            ],
        ])->assertStatus(422);
    }

    public function test_name_must_be_a_string(): void
    {
        // Given
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 3,
            'image' => 'http://image-url',
        ], [
            'x-api-key' => config('app.api_key'),
        ]);

        // Then
        $response->assertJson([
            'error' => [
                'message' => 'The name must be a string',
                'code' => 'E_INVALID_PARAM',
            ],
        ])->assertStatus(422);
    }

    public function test_image_is_required(): void
    {
        // Given
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            // 'image' => 'http://image-url',
        ], [
            'x-api-key' => config('app.api_key'),
        ]);

        // Then
        $response->assertJson([
            'error' => [
                'message' => 'The image is required',
                'code' => 'E_INVALID_PARAM',
            ],
        ])->assertStatus(422);
    }

    public function test_image_must_be_an_url(): void
    {
        // Given
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'string',
        ], [
            'x-api-key' => config('app.api_key'),
        ]);

        // Then
        $response->assertJson([
            'error' => [
                'message' => 'The image must be an url',
                'code' => 'E_INVALID_PARAM',
            ],
        ])->assertStatus(422);
    }

    public function test_a_guest_user_cannot_create_locations(): void
    {
        // Given
        // When
        $response = $this->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ]);

        // Then
        $response->assertUnauthorized();
    }
}
