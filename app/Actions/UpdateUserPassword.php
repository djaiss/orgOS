<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UpdateUserPassword
{
    public function __construct(
        private readonly User $user,
        private readonly string $currentPassword,
        private readonly string $newPassword,
    ) {}

    /**
     * Update the user password.
     */
    public function execute(): User
    {
        $this->validate();
        $this->update();
        $this->log();

        return $this->user;
    }

    private function validate(): void
    {
        throw_unless(Hash::check($this->currentPassword, $this->user->password), InvalidArgumentException::class, 'Current password is incorrect');
    }

    private function update(): void
    {
        $this->user->update([
            'password' => Hash::make($this->newPassword),
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: null,
            user: $this->user,
            action: 'update_user_password',
            description: 'Updated their password',
        )->onQueue('low');
    }
}
