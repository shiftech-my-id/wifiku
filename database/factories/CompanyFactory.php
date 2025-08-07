<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Party>
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
        $address = $this->faker->address();
        return [
            'code' => strtoupper('CMP-' . Str::random(5)),
            'name' => $this->faker->company,
            'owner_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'registration_datetime' => Carbon::now()->subDays(rand(1, 30)),
            'activation_datetime' => Carbon::now(),
            'activation_code' => Str::upper(Str::random(8)),
            'active' => true,
        ];
    }
}
