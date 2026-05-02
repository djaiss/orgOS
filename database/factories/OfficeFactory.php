<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Office>
 */
class OfficeFactory extends Factory
{
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'country_id' => Country::factory(),
            'name' => fake()->company(),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => null,
            'city' => fake()->city(),
            'state_province' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'timezone' => fake()->timezone(),
            'office_type_id' => OfficeType::factory(),
        ];
    }
}
