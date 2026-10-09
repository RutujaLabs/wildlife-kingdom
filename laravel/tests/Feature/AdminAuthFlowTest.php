<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminAuthSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_the_login_form_and_is_redirected_from_dashboard(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Wildlife Kingdom Admin')
            ->assertSee('name="_token"', false);

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_open_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make('secret-password'),
        ]);

        $this->post(route('admin.authenticate'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Animals')
            ->assertSee('Total Habitats');
    }

    public function test_admin_seeder_uses_explicit_credentials(): void
    {
        config([
            'auth.admin_setup.email' => 'seeded-admin@example.test',
            'auth.admin_setup.password' => 'configured-password',
        ]);

        (new AdminAuthSeeder())->run();

        $user = User::where('email', 'seeded-admin@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('configured-password', $user->password));
    }

    public function test_logout_invalidates_authentication_and_protects_dashboard(): void
    {
        $this->actingAs(User::factory()->create(), 'web')
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest('web');
        $this->get(route('admin.login'))->assertOk();
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }
}
