<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EmailSentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
