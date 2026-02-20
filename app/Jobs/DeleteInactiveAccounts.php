<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\AccountAutomaticallyDestroyed;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class DeleteInactiveAccounts implements ShouldQueue
{
    use Queueable;

    /**
     * Delete all accounts which have been inactive for the last 6 months.
     */
    public function handle(): void
    {
        $users = User::query()->where('auto_delete_account', true)->get();

        foreach ($users as $user) {
            $this->delete($user);
        }
    }

    private function delete(User $user): void
    {
        if ($user->last_activity_at === null) {
            return;
        }

        // Check if the user has been inactive for 6 months
        if ($user->last_activity_at->diffInMonths(now()) >= 6) {
            $user->delete();

            Mail::to(config('app.account_deletion_notification_email'))
                ->queue(new AccountAutomaticallyDestroyed(
                    age: $user->created_at->diffInMonths(now()) . ' months',
                ));
        }
    }
}
