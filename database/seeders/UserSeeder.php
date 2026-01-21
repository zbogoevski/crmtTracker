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
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);

        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'api']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@crmtracker.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin123!@#'),
                'email_verified_at' => now(),
            ]
        );

        if (! $adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        $clientUser = User::firstOrCreate(
            ['email' => 'client@crmtracker.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('Client123!@#'),
                'email_verified_at' => now(),
            ]
        );

        if (! $clientUser->hasRole('client')) {
            $clientUser->assignRole($clientRole);
        }

        if ($this->command) {
            $this->command->info('Admin and Client users created successfully!');
            $this->command->info('Admin: admin@crmtracker.com / Admin123!@#');
            $this->command->info('Client: client@crmtracker.com / Client123!@#');
        }
    }
}
