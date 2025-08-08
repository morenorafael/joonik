<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login(): void
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Then
        $response->assertJsonStructure(['token']);
    }
}
