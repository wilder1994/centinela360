<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'business_name' => $this->faker->company(),
            'nit' => $this->faker->unique()->numerify('9########-#'),
            'address' => $this->faker->streetAddress(),
            'neighborhood' => $this->faker->citySuffix(),
            'city' => $this->faker->city(),
            'service_count' => $this->faker->numberBetween(1, 5),
            'email' => $this->faker->companyEmail(),
            'representative_name' => $this->faker->name(),
            'quadrant' => $this->faker->optional()->randomElement(['Norte', 'Sur', 'Oriente', 'Occidente']),
            'start_date' => $this->faker->dateTimeBetween('-3 years', '-1 month'),
            'end_date' => $this->faker->optional()->dateTimeBetween('-1 month', '+6 months'),
        ];
    }

    public function forCompany(Company|int $company): static
    {
        return $this->state(fn () => [
            'company_id' => $company instanceof Company ? $company->id : $company,
        ]);
    }
}
