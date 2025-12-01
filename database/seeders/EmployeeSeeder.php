<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Client;
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

        $clients = Client::factory()
            ->count(3)
            ->create(['company_id' => $company->id]);

        foreach (range(1, 8) as $index) {
            $clientId = $clients->isNotEmpty() ? $clients->random()->id : null;

            Employee::factory()
                ->for($company)
                ->state(['client_id' => $clientId])
                ->create();
        }
    }
}
