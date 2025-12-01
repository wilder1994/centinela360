<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $birthDate = $this->faker->dateTimeBetween('-55 years', '-20 years');
        $startDate = $this->faker->dateTimeBetween('-5 years', 'now');
        $badgeExpiresAt = $this->faker->optional(0.7)->dateTimeBetween($startDate, '+2 years');

        return [
            'company_id' => Company::factory(),
            'client_id' => null,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'position' => $this->faker->jobTitle(),
            'document_type' => $this->faker->randomElement(['CC', 'CE', 'TI', 'PAS']),
            'document_number' => $this->faker->unique()->numerify('##########'),
            'rh' => $this->faker->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
            'address' => $this->faker->address(),
            'birth_date' => $birthDate,
            'start_date' => $startDate,
            'badge_expires_at' => $badgeExpiresAt,
            'service_type' => $this->faker->randomElement(['Vigilancia', 'Escolta', 'Monitoreo', 'Administrativo']),
            'status' => $this->faker->randomElement(['Activo', 'Inactivo']),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->optional()->sentence(),
            'photo_path' => null,
        ];
    }

    public function withClient(Client|int|null $client = null): static
    {
        return $this->state(function (array $attributes) use ($client) {
            $companyId = $attributes['company_id'] instanceof Company
                ? $attributes['company_id']->id
                : ($attributes['company_id'] ?? null);
            $clientFactory = $client ? null : Client::factory();

            return [
                'company_id' => $companyId ?? ($client ? $client->company_id : null),
                'client_id' => $client
                    ? ($client instanceof Client ? $client->id : $client)
                    : $clientFactory?->state(fn () => ['company_id' => $companyId]),
            ];
        });
    }
}
