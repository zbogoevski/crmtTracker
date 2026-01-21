<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Modules\Permission\Infrastructure\Models\Permission;
use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPermissionsPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_permissions_index_defaults_to_20_per_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']));

        Permission::factory()->count(25)->create(['guard_name' => 'web']);

        $response = $this->actingAs($admin)->get('/permissions');

        $response->assertStatus(200);

        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator */
        $paginator = $response->viewData('permissions');
        $this->assertSame(20, $paginator->perPage());
        $this->assertCount(20, $paginator->items());

        // Explicit override still works
        $overrideResponse = $this->actingAs($admin)->get('/permissions?per_page=5');
        $overrideResponse->assertStatus(200);
        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $overridePaginator */
        $overridePaginator = $overrideResponse->viewData('permissions');
        $this->assertSame(5, $overridePaginator->perPage());
    }
}
