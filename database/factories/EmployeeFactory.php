<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use App\Enums\EmploymentType;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private const FIRST_NAMES = [
        'Wanjiku', 'Kamau', 'Achieng', 'Otieno', 'Njeri', 'Mwangi', 'Akinyi', 'Kiprono',
        'Chebet', 'Mutua', 'Wambui', 'Omondi', 'Nyambura', 'Kiplagat', 'Atieno', 'Mutiso',
        'Grace', 'Brian', 'Faith', 'Kevin', 'Mercy', 'Dennis', 'Joy', 'Collins',
    ];

    private const LAST_NAMES = [
        'Kamau', 'Odhiambo', 'Wanjiru', 'Kipchoge', 'Mwangi', 'Ochieng', 'Njoroge', 'Kibet',
        'Mutua', 'Onyango', 'Karanja', 'Rotich', 'Nyaga', 'Wekesa', 'Maina', 'Cheruiyot',
    ];
    public function definition(): array
    {
        $firstName = fake()->randomElement(self::FIRST_NAMES);
        $lastName = fake()->randomElement(self::LAST_NAMES);

        return [
            'company_id' => Company::factory(),
            'department_id' => null,
            'job_title_id' => null,
            'manager_id' => null,
            'user_id' => null,
            'staff_number' => 'EMP-'.fake()->unique()->numerify('######'),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => strtolower("{$firstName}.{$lastName}.").fake()->unique()->numberBetween(1000,999999).'@example.com',
            'phone' => '+2547'.fake()->numerify('########'),
            'national_id' => fake()->unique()->numerify('########'),
            'kra_pin' => 'A'.fake()->unique()->numerify('#########').strtoupper(fake()->randomLetter()),
            'hire_date' => fake()->dateTimeBetween('-10 years', '-1 month'),
            'termination_date' => null,
            'employment_type' => fake()->randomElement(EmploymentType::cases()),
            'status' => EmployeeStatus::Active,
        ];
    }

    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EmployeeStatus::Terminated,
            'termination_date' => fake()->dateTimeBetween($attributes['hire_date'], 'now'),
        ]);
    }
}
