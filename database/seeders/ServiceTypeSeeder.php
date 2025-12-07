<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            return;
        }

        $defaults = ['Ronda', 'Porteria', 'Control de acceso', 'Supervisión', 'Ocasional'];

        foreach ($defaults as $name) {
            ServiceType::firstOrCreate([
                'company_id' => $company->id,
                'name' => $name,
            ]);
        }
    }
}
