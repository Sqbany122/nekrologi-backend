<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();

        User::factory()->create([
            'name' => 'Test user',
            'email' => 'test@example.org',
            'password' => Hash::make('secret')
        ]);
    }

    public function test_show_validation_error_when_both_fields_empty()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_show_validation_error_on_email_when_credential_donot_match()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' => 'test@test.org',
            'password' => 'abcdabcd'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status_code' => 422,
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_return_user_and_access_token_after_successful_login()
    {
        $response = $this->json('POST', route('auth.login'), [
            'email' =>'test@example.org',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'access_token'
            ]);
    }

    public function test_non_authenticated_user_cannot_get_user_details()
    {
        $response = $this->json('GET', route('auth.user'));

        $response->assertStatus(401)
            ->assertSee('Unauthenticated');
    }

    public function test_authenticated_user_can_get_user_details()
    {
        Sanctum::actingAs(
            User::first(),
        );

        $response = $this->json('GET', route('auth.user'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email'
            ]);
    }
}
