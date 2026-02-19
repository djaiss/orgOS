<?php

declare(strict_types=1);

use App\Jobs\DeleteInactiveAccounts;
use Illuminate\Support\Facades\Schedule;

Schedule::job(
    new DeleteInactiveAccounts,
    'low',
)->dailyAt('00:30');
