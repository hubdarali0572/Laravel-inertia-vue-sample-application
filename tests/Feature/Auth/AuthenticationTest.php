<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->assertSame('/', route('login', absolute: false));
        $this->assertSame('/login', route('login.store', absolute: false));
        $this->get('/')->assertStatus(200);
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $this->fakeSuccessfulRecaptcha();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'test-token',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->fakeSuccessfulRecaptcha();
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'test-token',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    private function fakeSuccessfulRecaptcha(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);
    }
}
