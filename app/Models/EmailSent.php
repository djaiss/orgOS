<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Database\Factories\EmailSentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $uuid
 * @property string $email_type
 * @property string $email_address
 * @property string|null $subject
 * @property string|null $body
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $bounced_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 *
 * @method static EmailSentFactory factory($count = null, $state = [])
 * @method static Builder<static>|EmailSent newModelQuery()
 * @method static Builder<static>|EmailSent newQuery()
 * @method static Builder<static>|EmailSent query()
 *
 * @mixin Model
 */
class EmailSent extends Model
{
    /** @use HasFactory<EmailSentFactory> */
    use HasFactory;

    protected $table = 'emails_sent';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'email_type',
        'email_address',
        'subject',
        'body',
        'sent_at',
        'delivered_at',
        'bounced_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'bounced_at' => 'datetime',
    ];

    /**
     * Get the user associated with the email.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
