<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateDefaultUsersCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_admin_and_client_users_with_roles(): void
    {
        $this->artisan('users:create-defaults')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => 'admin@crmtracker.com']);
        $this->assertDatabaseHas('users', ['email' => 'client@crmtracker.com']);

        $admin = User::where('email', 'admin@crmtracker.com')->firstOrFail();
        $client = User::where('email', 'client@crmtracker.com')->firstOrFail();

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'api')->firstOrFail();
        $clientRole = Role::where('name', 'client')->where('guard_name', 'api')->firstOrFail();

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $adminRole->id,
            'model_type' => User::class,
            'model_id' => $admin->id,
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $clientRole->id,
            'model_type' => User::class,
            'model_id' => $client->id,
        ]);
    }

    public function test_is_idempotent(): void
    {
        $this->artisan('users:create-defaults')->assertExitCode(0);
        $this->artisan('users:create-defaults')->assertExitCode(0);

        $this->assertSame(1, User::where('email', 'admin@crmtracker.com')->count());
        $this->assertSame(1, User::where('email', 'client@crmtracker.com')->count());
    }
}
