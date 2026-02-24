<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $nickname
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $last_activity_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property array|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $last_used_ip
 * @property Carbon|null $trial_ends_at
 * @property string $locale
 * @property bool $time_format_24h
 * @property bool $auto_delete_account
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'nickname',
        'email',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
        'trial_ends_at',
        'last_used_ip',
        'last_activity_at',
        'locale',
        'time_format_24h',
        'auto_delete_account',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
            'time_format_24h' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
            'auto_delete_account' => 'boolean',
        ];
    }

    /**
     * Get the emailsSent associated with the user.
     *
     * @return HasMany<EmailSent, $this>
     */
    public function emailsSent(): HasMany
    {
        return $this->hasMany(EmailSent::class);
    }

    /**
     * Get the memberships associated with the user.
     *
     * @return HasMany<Member, $this>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the organizations associated with the user.
     *
     * @return HasManyThrough<Organization, Member, $this>
     */
    public function organizations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Organization::class,
            Member::class,
            'user_id',          // Foreign key on members table...
            'id',               // Foreign key on organizations table...
            'id',               // Local key on users table...
            'organization_id',  // Local key on members table...
        );
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->first_name . ' ' . $this->last_name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Get the user's full name by combining first and last name.
     */
    public function getFullName(): string
    {
        $firstName = $this->first_name;
        $lastName = $this->last_name;
        $separator = $firstName && $lastName ? ' ' : '';

        return $firstName . $separator . $lastName;
    }

    /**
     * Check if the user is part of a specific organization.
     */
    public function isPartOfOrganization(Organization $organization): bool
    {
        return $this->memberships()->where('organization_id', $organization->id)->exists();
    }

    /**
     * Return the member object for the user in the given organization.
     */
    public function memberOf(Organization $organization): ?Member
    {
        return $this->memberships()->where('organization_id', $organization->id)->first();
    }
}
