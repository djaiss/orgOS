<?php

declare(strict_types=1);

namespace Tests;

use App\Enums\Permission;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function addOrganization(User $user, string $name = 'Dunder Mifflin', Permission $permission = Permission::Owner): Organization
    {
        $organization = Organization::factory()->create([
            'name' => $name,
        ]);
        Member::factory()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'permission' => $permission,
        ]);

        return $organization;
    }
}
