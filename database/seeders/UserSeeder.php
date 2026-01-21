<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminApiRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminWebRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $clientWebRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@crmtracker.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin123!@#'),
                'email_verified_at' => now(),
            ]
        );

        if (! $adminUser->roles()->where('name', 'admin')->where('guard_name', 'api')->exists()) {
            $adminUser->assignRole($adminApiRole);
        }
        if (! $adminUser->roles()->where('name', 'admin')->where('guard_name', 'web')->exists()) {
            $adminUser->assignRole($adminWebRole);
        }

        $clientUser = User::firstOrCreate(
            ['email' => 'client@crmtracker.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('Client123!@#'),
                'email_verified_at' => now(),
            ]
        );

        if (! $clientUser->roles()->where('name', 'client')->where('guard_name', 'web')->exists()) {
            $clientUser->assignRole($clientWebRole);
        }

        if ($this->command) {
            $this->command->info('Admin and Client users created successfully!');
            $this->command->info('Admin: admin@crmtracker.com / Admin123!@#');
            $this->command->info('Client: client@crmtracker.com / Client123!@#');
        }
    }
}
