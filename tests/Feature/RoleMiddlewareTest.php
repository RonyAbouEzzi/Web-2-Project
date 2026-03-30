<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['web', 'role:admin'])->get('/__role-test/admin-only', function () {
            return response('admin-ok', 200);
        })->name('role-test.admin-only');

        Route::middleware(['web', 'role:office_user'])->get('/__role-test/office-only', function () {
            return response('office-ok', 200);
        })->name('role-test.office-only');

        Route::middleware(['web', 'role:citizen'])->get('/__role-test/citizen-only', function () {
            return response('citizen-ok', 200);
        })->name('role-test.citizen-only');
    }

    public function test_guest_is_redirected_to_login_and_intended_url_is_saved(): void
    {
        $response = $this->get('/__role-test/admin-only');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('url.intended', url('/__role-test/admin-only'));
    }

    public function test_admin_can_access_admin_only_route(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/__role-test/admin-only')
            ->assertOk()
            ->assertSee('admin-ok');
    }

    public function test_citizen_is_redirected_when_trying_to_access_admin_route(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'is_active' => true,
        ]);

        $response = $this->actingAs($citizen)->get('/__role-test/admin-only');

        $response->assertRedirect(route('citizen.dashboard'));
        $response->assertSessionHas('error', 'You do not have access to that area.');
    }

    public function test_citizen_is_redirected_when_trying_to_access_office_route(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'is_active' => true,
        ]);

        $response = $this->actingAs($citizen)->get('/__role-test/office-only');

        $response->assertRedirect(route('citizen.dashboard'));
        $response->assertSessionHas('error', 'You do not have access to that area.');
    }

    public function test_office_user_is_redirected_when_trying_to_access_citizen_route(): void
    {
        $officeUser = User::factory()->create([
            'role' => 'office_user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($officeUser)->get('/__role-test/citizen-only');

        $response->assertRedirect(route('office.dashboard'));
        $response->assertSessionHas('error', 'You do not have access to that area.');
    }

    public function test_inactive_user_is_logged_out_and_redirected_to_login(): void
    {
        $inactiveCitizen = User::factory()->create([
            'role' => 'citizen',
            'is_active' => false,
        ]);

        $response = $this->actingAs($inactiveCitizen)->get('/__role-test/citizen-only');

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Your account has been deactivated. Please contact support.',
        ]);
        $this->assertGuest();
    }
}
