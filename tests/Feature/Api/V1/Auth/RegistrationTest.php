<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register(): void
    {
        // Given

        // When
        $response = $this->postJson(route('api.v1.register'), [
            'name' => 'User name',
            'email' => 'user@mail.com',
            'password' => 'password',
        ]);

        // Then
        $this->assertDatabaseHas('users', [
            'name' => 'User name',
            'email' => 'user@mail.com',
        ]);

        $user = User::first();

        $this->assertTrue(Hash::check('password', $user->password));

        $response->assertJsonStructure(['token']);
    }
}
