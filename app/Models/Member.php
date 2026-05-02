<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Permission;
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
 * @property int|null $member_type_id
 * @property Permission $permission
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
        'member_type_id',
        'permission',
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
        'permission' => Permission::class,
    ];

    /**
     * Get the member type associated with the member.
     *
     * @return BelongsTo<MemberType, $this>
     */
    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    /**
     * Get the user record associated with the member.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Organization record associated with the member.
     *
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Check if the member has admin permissions.
     */
    public function isAdministrator(): bool
    {
        return $this->permission === Permission::Admin;
    }

    /**
     * Check if the member has owner permissions.
     */
    public function isOwner(): bool
    {
        return $this->permission === Permission::Owner;
    }
}
