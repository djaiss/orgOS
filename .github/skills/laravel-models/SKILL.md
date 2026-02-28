---
name: laravel-models
description: Eloquent model patterns and database layer. Use when working with models, database entities, Eloquent ORM, or when user mentions models, eloquent, relationships, casts, observers, database entities.
---

# Laravel Models

Models represent database tables and domain entities.

**Related guides:**
- [Actions](../laravel-actions/SKILL.md) - Actions contain business logic

## Philosophy

Models should:
- Define relationships
- Define casts
- Contain simple accessors/mutators
- **NOT contain business logic** (that belongs in Actions)

## Rules

- Model names are always in singular.
- Models are always tested.
- Models always have a factory.
- Never use model observers.
- Always document model attributes at the top of the file as docblocks.
- Always comment methods in a model.

## Basic Model Structure

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Member
 *
 * @property int $id
 * @property int $organization_id
 * @property int|null $user_id
 * @property string|null $timezone
 * @property Carbon|null $birthdate
 * @property Carbon|null $joined_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'timezone',
        'birthdate',
        'joined_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'joined_at' => 'datetime',
    ];

    /**
     * Get the user record associated with the member.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Organization record associated with the member.
     *
     * @return BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
```

## Model Methods

**Simple helper methods** are acceptable:

```php
class Order extends Model
{
    public function isPending(): bool
    {
        return $this->status === OrderStatus::Pending;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::Completed;
    }

    public function canBeCancelled(): bool
    {
        return $this->isPending() || $this->status === OrderStatus::Processing;
    }
}
```

**But NOT business logic:**

## Testing Models

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_user(): void
    {
        $member = Member::factory()->create();

        $this->assertTrue($member->user()->exists());
    }

    #[Test]
    public function it_belongs_to_an_organization(): void
    {
        $member = Member::factory()->create();

        $this->assertTrue($member->organization()->exists());
    }
}
```
