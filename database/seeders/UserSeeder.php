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
        // Create or get admin role
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'admin', 'guard_name' => 'api']
        );

        // Create or get client role
        $clientRole = Role::firstOrCreate(
            ['name' => 'client', 'guard_name' => 'api'],
            ['name' => 'client', 'guard_name' => 'api']
        );

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@crmtracker.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@crmtracker.com',
                'password' => Hash::make('Admin123!@#'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role if not already assigned
        if (! $adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        // Create client user
        $clientUser = User::firstOrCreate(
            ['email' => 'client@crmtracker.com'],
            [
                'name' => 'Client User',
                'email' => 'client@crmtracker.com',
                'password' => Hash::make('Client123!@#'),
                'email_verified_at' => now(),
            ]
        );

        // Assign client role if not already assigned
        if (! $clientUser->hasRole('client')) {
            $clientUser->assignRole($clientRole);
        }

        $this->command->info('Admin and Client users created successfully!');
        $this->command->info('Admin: admin@crmtracker.com / Admin123!@#');
        $this->command->info('Client: client@crmtracker.com / Client123!@#');
    }
}
