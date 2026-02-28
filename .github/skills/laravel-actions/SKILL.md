---
name: laravel-actions
description: Actions are what the user does within an application. Use when working with actions.
---

# Laravel Actions

## Rules

- Actions should be 100% testable.
- If an action does something for a user, we should always log what the user did.
- Always use Eloquent in an action, if possible.
- Actions must do as fewer DB queries as possible.

## Action Naming Conventions

Actions should represent what a user wants to do, or what the system needs to do. The verb should try to follow when possible, the appropriate RESTful method names, like `CreateXX`, `UpdateXX` or `DestroyXX`.

```php
// ✅ CORRECT
CreateJournal
DestroyUser
```

```php
// ❌ INCORRECT
AccountCreated
```

## Full Example

```php
<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\Member;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Create an organization for a user.
 * The user will be added to the organization as the first user.
 */
class CreateOrganization
{
    private Organization $organization;

    public function __construct(
        public User $user,
        public string $name,
    ) {}

    public function execute(): Organization
    {
        $this->validate();
        $this->create();
        $this->generateSlug();
        $this->addMembership();
        $this->log();

        return $this->organization;
    }

    private function validate(): void
    {
        // make sure the organization name doesn't contain any special characters
        if (in_array(preg_match('/^[a-zA-Z0-9\s\-_]+$/', $this->name), [0, false], true)) {
            throw ValidationException::withMessages([
                'organization_name' => 'Organization name can only contain letters, numbers, spaces, hyphens and underscores',
            ]);
        }
    }

    private function create(): void
    {
        $this->organization = Organization::query()->create([
            'name' => $this->name,
        ]);
    }

    private function generateSlug(): void
    {
        $slug = $this->organization->id . '-' . Str::of($this->name)->slug('-');

        $this->organization->slug = $slug;
        $this->organization->save();
    }

    private function addMembership(): void
    {
        Member::query()->create([
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'joined_at' => now(),
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: $this->organization,
            user: $this->user,
            action: 'organization_creation',
            description: sprintf('Created an organization called %s', $this->name),
        )->onQueue('low');
    }
}
```

## Testing

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\CreateOrganization;
use App\Jobs\LogUserAction;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_an_organization(): void
    {
        Queue::fake();

        $user = $this->createUser();

        $organization = new CreateOrganization(
            user: $user,
            name: 'Dunder Mifflin',
        )->execute();

        $expectedSlug = $organization->id . '-dunder-mifflin';

        $this->assertInstanceOf(Organization::class, $organization);

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'Dunder Mifflin',
            'slug' => $expectedSlug,
        ]);

        $this->assertDatabaseHas('members', [
            'organization_id' => $organization->id,
            'user_id' => $user->id,
        ]);

        Queue::assertPushedOn(
            queue: 'low',
            job: LogUserAction::class,
            callback: fn(LogUserAction $job): bool => (
                $job->action === 'organization_creation'
                && $job->user->id === $user->id
                && $job->organization->id === $organization->id
                && $job->description === 'Created an organization called Dunder Mifflin'
            ),
        );
    }

    #[Test]
    public function it_rejects_organization_names_with_special_characters(): void
    {
        $user = $this->createUser();

        $this->expectException(ValidationException::class);

        new CreateOrganization(
            user: $user,
            name: 'Dunder & Mifflin',
        )->execute();
    }
}
```
