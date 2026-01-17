<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class HPanelRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles/permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Keep names EXACTLY same as users.role enum values
        foreach (['super_admin', 'reseller', 'client'] as $name) {
            Role::findOrCreate($name, 'web');
        }
    }
}
