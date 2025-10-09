<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(
            ['nit' => '900123456-7'],
            [
                'name' => 'Centinela Global',
                'address' => 'Calle 100 # 10-50 Bogotá',
                'phone' => '3101234567',
                'email' => 'info@centinela360.com',
                'representative' => 'Wilder Chiquiza',
                'active' => true,
            ]
        );
    }
}
