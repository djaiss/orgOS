<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\EmailType;
use App\Jobs\LogUserAction;
use App\Jobs\SendEmail;
use App\Mail\ApiKeyDestroyed;
use App\Models\User;

class DestroyApiKey
{
    private string $label;

    public function __construct(
        private readonly User $user,
        private readonly int $tokenId,
    ) {}

    /**
     * Destroy an API key.
     */
    public function execute(): void
    {
        $token = $this->user
            ->tokens()
            ->where('id', $this->tokenId)
            ->first();
        $this->label = $token->name;
        $token->delete();

        $this->log();
        $this->sendEmailToUser();
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: null,
            user: $this->user,
            action: 'api_key_deletion',
            description: 'Deleted an API key',
        )->onQueue('low');
    }

    private function sendEmailToUser(): void
    {
        SendEmail::dispatch(
            mailable: new ApiKeyDestroyed(
                label: $this->label,
            ),
            user: $this->user,
            emailType: EmailType::ApiDestroyed,
        )->onQueue('high');
    }
}
