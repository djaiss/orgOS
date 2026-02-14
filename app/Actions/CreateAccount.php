<?php

declare(strict_types = 1);

namespace App\Actions;

use App\Jobs\LogUserAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAccount
{
    private User $user;

    public function __construct(
        private string $email,
        private string $password,
        private string $firstName,
        private string $lastName,
    ) {}

    public function execute(): User
    {
        $this->create();
        $this->log();

        return $this->user;
    }

    private function create(): void
    {
        $this->user = User::query()->create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'trial_ends_at' => now()->addDays(30),
        ]);
    }

    private function log(): void
    {
        LogUserAction::dispatch(
            organization: null,
            user: $this->user,
            action: 'account_creation',
            description: sprintf('Created an account'),
        )->onQueue('low');
    }
}
