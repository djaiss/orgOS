<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Auth;

use App\Actions\VerifyTwoFactorCode;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Jobs\CheckLastLogin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFAChallengeController extends Controller
{
    public function create(): View
    {
        if (! session('2fa:user:id')) {
            return view('app.auth.2fa', [
                'error' => __('Session expired. Please login again.'),
            ]);
        }

        $quotes = config('quotes');
        $randomQuote = $quotes[array_rand($quotes)];

        return view('app.auth.2fa', [
            'quote' => $randomQuote,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255'],
        ]);

        $userId = session('2fa:user:id');
        $user = User::query()->find($userId);

        if (! new VerifyTwoFactorCode(
            user: $user,
            code: TextSanitizer::plainText((string) $request->input('code')),
        )->execute()) {
            return back()->withErrors(['code' => 'Invalid code']);
        }

        Auth::login($user);
        session()->forget('2fa:user:id');
        $request->session()->regenerate();

        CheckLastLogin::dispatch(user: $request->user(), ip: $request->ip())->onQueue('low');

        return redirect()->intended(route('organization.index', absolute: false));
    }
}
