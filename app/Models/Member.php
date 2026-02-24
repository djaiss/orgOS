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
