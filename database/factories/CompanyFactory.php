<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'kra_pin' => 'P'.fake()->unique()->numerify('#########').strtoupper(fake()->randomLetter()),
            'email' => 'hr@'.Str::slug($name).'.co.ke',
            'phone' => '+2547'.fake()->numerify('########'),
            'is_active' => true
        ];
    }
}
