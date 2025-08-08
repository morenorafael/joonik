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

    public function test_code_is_required(): void
    {
        // Given
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['create-locations']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            // 'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['create-locations']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            // 'name' => 'Location name',
            'image' => 'http://image-url',
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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['create-locations']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 3,
            'image' => 'http://image-url',
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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['create-locations']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            // 'image' => 'http://image-url',
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
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['create-locations']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'string',
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

    public function test_a_user_without_scope_cannot_create_locations(): void
    {
        // Given
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['other-scope']
        );

        // When
        $response = $this->actingAs($user)->postJson(route('api.v1.locations.store'), [
            'code' => 'SEDE-01',
            'name' => 'Location name',
            'image' => 'http://image-url',
        ]);

        // Then
        $response->assertForbidden();
    }
}
