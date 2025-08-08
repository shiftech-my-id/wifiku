<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostCategoryFactory extends Factory
{
    protected $model = CostCategory::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(), // Bisa null juga jika diperlukan
            'name' => $this->faker->words(2, true), // contoh: 'Operational Cost'
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
