<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserModuleSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_routes_exist(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']));
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/users');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'meta',
                'links',
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Users retrieved successfully',
            ]);
    }
}
