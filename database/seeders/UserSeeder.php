<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $companyAdminRole = Role::where('name', 'Admin Empresa')->first();

        $user = User::firstOrCreate(
            ['email' => 'admin@centinela360.com'],
            [
                'name' => 'Wilder Rivera',
                'password' => Hash::make('12345678'),
                'company_id' => $company->id ?? null,
                'active' => true,
            ]
        );

        $roleIds = collect([$superAdminRole?->id, $companyAdminRole?->id])
            ->filter()
            ->unique()
            ->all();

        if (!empty($roleIds)) {
            $user->roles()->syncWithoutDetaching($roleIds);
        }
    }
}
