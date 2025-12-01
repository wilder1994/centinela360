<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'nit' => $this->faker->unique()->numerify('9########-#'),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'representative' => $this->faker->name(),
            'logo' => null,
            'color_primary' => $this->faker->safeHexColor(),
            'color_secondary' => $this->faker->safeHexColor(),
            'color_text' => $this->faker->safeHexColor(),
            'active' => true,
            'status' => 'active',
            'subscription_expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'notes' => $this->faker->sentence(),
        ];
    }
}
