<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(), // otomatis buat company jika belum ada
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'google_id' => null, // bisa diganti dengan random jika dibutuhkan
            'password' => Hash::make('password'), // default password
            'active' => true,
            'last_login_datetime' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'last_activity_description' => $this->faker->sentence(),
            'last_activity_datetime' => $this->faker->optional()->dateTimeBetween('-10 days', 'now'),
            'remember_token' => Str::random(10),
        ];
    }
}
