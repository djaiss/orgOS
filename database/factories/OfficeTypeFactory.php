<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OfficeType;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfficeType>
 */
class OfficeTypeFactory extends Factory
{
    protected $model = OfficeType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->word(),
            'position' => 0,
        ];
    }
}
