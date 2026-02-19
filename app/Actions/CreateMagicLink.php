<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\User;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

/**
 * Create a magic link so the user can log in.
 * This link is valid for 5 minutes.
 */
class CreateMagicLink
{
    private User $user;

    private string $magicLinkUrl;

    public function __construct(
        private readonly string $email,
    ) {}

    public function execute(): string
    {
        $this->validate();
        $this->create();
        $this->log();

        return $this->magicLinkUrl;
    }

    private function validate(): void
    {
        $this->user = User::query()->where('email', $this->email)->firstOrFail();
    }

    private function create(): void
    {
        $action = new LoginAction($this->user);
        $action->response(redirect(route('organization.index', absolute: false)));

        $this->magicLinkUrl = MagicLink::create($action, 5)->url;
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: null,
            user: $this->user,
            action: 'magic_link_created',
            description: 'Sent a magic link',
        )->onQueue('low');
    }
}
