<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDefaultUsersCommand extends Command
{
    protected $signature = 'users:create-defaults
        {--admin-email= : Admin email (default: admin@crmtracker.com)}
        {--admin-password= : Admin password (default: Admin123!@#)}
        {--client-email= : Client email (default: client@crmtracker.com)}
        {--client-password= : Client password (default: Client123!@#)}
        {--force : Update existing users (including password)}';

    protected $description = 'Create (or update) one admin and one client user with roles';

    public function handle(): int
    {
        $adminEmail = (string) ($this->option('admin-email') ?: 'admin@crmtracker.com');
        $adminPassword = (string) ($this->option('admin-password') ?: 'Admin123!@#');
        $clientEmail = (string) ($this->option('client-email') ?: 'client@crmtracker.com');
        $clientPassword = (string) ($this->option('client-password') ?: 'Client123!@#');
        $force = (bool) $this->option('force');

        $adminApiRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminWebRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $clientWebRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        $adminUser = $this->createOrUpdateUser(
            email: $adminEmail,
            name: 'Admin User',
            password: $adminPassword,
            force: $force,
        );

        if (! $adminUser->roles()->where('name', 'admin')->where('guard_name', 'api')->exists()) {
            $adminUser->assignRole($adminApiRole);
        }
        if (! $adminUser->roles()->where('name', 'admin')->where('guard_name', 'web')->exists()) {
            $adminUser->assignRole($adminWebRole);
        }

        $clientUser = $this->createOrUpdateUser(
            email: $clientEmail,
            name: 'Client User',
            password: $clientPassword,
            force: $force,
        );

        if (! $clientUser->roles()->where('name', 'client')->where('guard_name', 'web')->exists()) {
            $clientUser->assignRole($clientWebRole);
        }

        $this->info('Default users are ready.');
        $this->line("Admin: {$adminEmail} / {$adminPassword}");
        $this->line("Client: {$clientEmail} / {$clientPassword}");

        return Command::SUCCESS;
    }

    protected function createOrUpdateUser(string $email, string $name, string $password, bool $force): User
    {
        $attributes = [
            'name' => $name,
        ];

        if ($force) {
            $attributes['password'] = Hash::make($password);
            $attributes['email_verified_at'] = now();

            return User::updateOrCreate(['email' => $email], $attributes);
        }

        /** @var User $user */
        $user = User::firstOrCreate(
            ['email' => $email],
            $attributes + [
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ],
        );

        return $user;
    }
}
