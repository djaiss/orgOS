<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->country(),
            'iso2' => mb_strtoupper(fake()->unique()->regexify('\d[A-Z]')),
            'iso3' => mb_strtoupper(fake()->unique()->regexify('[0-9][A-Z]{2}')),
            'phone_code' => '+' . fake()->numberBetween(1, 999),
            'currency_code' => mb_strtoupper(fake()->lexify('???')),
            'timezone_default' => null,
            'is_active' => true,
        ];
    }
}
