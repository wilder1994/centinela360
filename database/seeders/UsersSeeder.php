<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first() ?? Company::factory()->create(['name' => 'Empresa Demo']);

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $adminEmpresaRole = Role::where('name', 'Admin Empresa')->first();
        $supervisorRole = Role::where('name', 'Supervisor')->first();
        $guardiaRole = Role::where('name', 'Guardia')->first();

        // Usuario Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@centinela.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'active' => true,
                'company_id' => $company->id,
            ]
        );
        $admin->roles()->sync([$superAdminRole?->id]);

        // Usuario de empresa
        $empresa = User::firstOrCreate(
            ['email' => 'empresa@centinela.test'],
            [
                'name' => 'Empresa Demo',
                'password' => Hash::make('password'),
                'active' => true,
                'company_id' => $company->id,
            ]
        );
        $empresa->roles()->sync([$adminEmpresaRole?->id]);

        // Opcional: supervisor demo
        if ($supervisorRole) {
            $supervisor = User::firstOrCreate(
                ['email' => 'supervisor@centinela.test'],
                [
                    'name' => 'Supervisor Demo',
                    'password' => Hash::make('password'),
                    'active' => true,
                    'company_id' => $company->id,
                ]
            );
            $supervisor->roles()->sync([$supervisorRole->id]);
        }

        // Opcional: guardia demo
        if ($guardiaRole) {
            $guardia = User::firstOrCreate(
                ['email' => 'guardia@centinela.test'],
                [
                    'name' => 'Guardia Demo',
                    'password' => Hash::make('password'),
                    'active' => true,
                    'company_id' => $company->id,
                ]
            );
            $guardia->roles()->sync([$guardiaRole->id]);
        }
    }
}
