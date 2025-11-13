<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            return;
        }

        $faker = fake('es_CO');

        foreach (range(1, 8) as $index) {
            Employee::create([
                'company_id' => $company->id,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'position' => $faker->jobTitle(),
            ]);
        }
    }
}
