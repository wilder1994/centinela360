<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            RoleSeeder::class,
            RolesSeeder::class,
            PermissionSeeder::class,
            UsersSeeder::class,
            ServiceTypeSeeder::class,
            ClientSeeder::class,
            EmployeeSeeder::class,
            MemorandumsSeeder::class,
        ]);
    }
}
