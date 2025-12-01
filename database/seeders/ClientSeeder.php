<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            return;
        }

        Client::factory()->count(5)->create([
            'company_id' => $company->id,
        ]);
    }
}
