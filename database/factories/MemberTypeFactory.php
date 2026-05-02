<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MemberType;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberType>
 */
class MemberTypeFactory extends Factory
{
    protected $model = MemberType::class;

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
