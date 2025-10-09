<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Full system access'],
            ['name' => 'Admin Empresa', 'description' => 'Company-level administrator'],
            ['name' => 'Supervisor', 'description' => 'Field operations supervisor'],
            ['name' => 'Guardia', 'description' => 'Security staff user'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
