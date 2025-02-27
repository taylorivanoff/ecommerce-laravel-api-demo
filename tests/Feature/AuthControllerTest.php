<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $this->actingAs($user)->post('/api/logout')
            ->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);
    }
}
