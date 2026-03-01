<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Permission;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'user_id' => User::factory(),
            'permission' => Permission::Member,
            'timezone' => fake()->timezone(),
            'birthdate' => null,
            'joined_at' => now(),
        ];
    }
}
