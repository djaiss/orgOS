<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\User;

readonly class ToggleAutoDeleteAccount
{
    public function __construct(
        private User $user,
        private bool $autoDeleteAccount,
    ) {}

    /**
     * Toggle the auto delete account setting.
     */
    public function execute(): User
    {
        $this->update();
        $this->log();

        return $this->user;
    }

    private function update(): void
    {
        $this->user->update([
            'auto_delete_account' => $this->autoDeleteAccount,
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: null,
            user: $this->user,
            action: 'auto_delete_account_update',
            description: 'Updated auto delete account setting to '
            . ($this->user->auto_delete_account ? 'enabled' : 'disabled'),
        )->onQueue('low');
    }
}
