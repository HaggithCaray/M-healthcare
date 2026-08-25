<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name' => 'Test Midwife',
            'email' => 'health@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/', [
            'email' => 'health@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('email', 'health@example.com')->first());
    }

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_user_cannot_view_login_page_when_authenticated(): void
    {
        $user = User::where('email', 'health@example.com')->first();
        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/', [
            'email' => 'health@example.com',
            'password' => 'wrong-password',
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'health@example.com')->first();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $user = User::where('email', 'health@example.com')->first();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_statistics(): void
    {
        $user = User::where('email', 'health@example.com')->first();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertViewHas('totalMothers');
        $response->assertViewHas('totalChildren');
        $response->assertViewHas('totalPatients');
    }
}
