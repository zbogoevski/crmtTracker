<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RbacAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_is_admin_only(): void
    {
        $client = User::factory()->create();
        $client->assignRole(Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']));

        Sanctum::actingAs($client);

        $this->getJson('/api/v1/roles')->assertStatus(403);
        $this->getJson('/api/v1/permissions')->assertStatus(403);
        $this->getJson('/api/v1/users')->assertStatus(403);

        $admin = User::factory()->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']));

        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/roles')->assertStatus(200);
        $this->getJson('/api/v1/permissions')->assertStatus(200);
        $this->getJson('/api/v1/users')->assertStatus(200);
    }

    public function test_web_roles_and_permissions_are_read_only_for_client(): void
    {
        $client = User::factory()->create();
        $client->assignRole(Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']));

        $this->actingAs($client);

        $this->get('/roles')->assertStatus(200);
        $this->get('/permissions')->assertStatus(200);

        $this->get('/roles/create')->assertStatus(403);
        $this->get('/permissions/create')->assertStatus(403);
    }
}
