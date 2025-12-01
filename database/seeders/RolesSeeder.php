<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
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
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
