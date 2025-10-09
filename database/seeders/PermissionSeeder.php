<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['code' => 'view_dashboard', 'name' => 'View Dashboard'],
            ['code' => 'manage_users', 'name' => 'Manage Users'],
            ['code' => 'manage_roles', 'name' => 'Manage Roles'],
            ['code' => 'manage_permissions', 'name' => 'Manage Permissions'],
            ['code' => 'manage_companies', 'name' => 'Manage Companies'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['code' => $perm['code']], $perm);
        }
    }
}
