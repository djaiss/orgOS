<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function addOrganization(User $user, string $name = 'Dunder Mifflin'): Organization
    {
        $organization = Organization::factory()->create([
            'name' => $name,
        ]);
        $user->organizations()->attach($organization, ['joined_at' => now()]);

        return $organization;
    }
}
