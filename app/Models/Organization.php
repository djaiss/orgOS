<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\GenerateOrganizationAvatar;
use Carbon\Carbon;
use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Organization
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $invitation_code
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Organization extends Model
{
    /** @use HasFactory<OrganizationFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'invitation_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Get the members of the organization.
     *
     * @return HasMany<Member, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the offices of the organization.
     *
     * @return HasMany<Office, $this>
     */
    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    /**
     * Get the office types of the organization.
     *
     * @return HasMany<OfficeType, $this>
     */
    public function officeTypes(): HasMany
    {
        return $this->hasMany(OfficeType::class);
    }

    /**
     * Get the employee types of the organization.
     *
     * @return HasMany<MemberType, $this>
     */
    public function memberTypes(): HasMany
    {
        return $this->hasMany(MemberType::class);
    }

    /**
     * Get the departments of the organization.
     *
     * @return HasMany<Department, $this>
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Gets the avatar of the organization.
     */
    public function getAvatar(): string
    {
        return new GenerateOrganizationAvatar($this->id . '-' . $this->name)->execute();
    }
}
