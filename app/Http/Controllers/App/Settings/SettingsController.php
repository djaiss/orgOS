<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Settings;

use App\Actions\UpdateUserInformation;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        // $logs = Log::query()
        //     ->where('user_id', $request->user()->id)
        //     ->with('user')
        //     ->with('journal')
        //     ->latest()
        //     ->limit(6)
        //     ->get();

        // $emails = EmailSent::query()
        //     ->where('user_id', $request->user()->id)
        //     ->with('user')
        //     ->latest('sent_at')
        //     ->limit(6)
        //     ->get();

        $user = (object) $request
            ->user()
            ->only([
                'first_name',
                'last_name',
                'nickname',
                'email',
                'locale',
                'time_format_24h',
            ]);

        return view('app.settings.index', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore(Auth::user()->id),
                'disposable_email',
            ],
            'locale' => ['required', 'string', 'max:3', Rule::in(['en', 'fr'])],
            'time_format_24h' => ['required', Rule::in(['true', 'false'])],
        ]);

        new UpdateUserInformation(
            user: Auth::user(),
            email: mb_strtolower(TextSanitizer::plainText($validated['email'])),
            firstName: TextSanitizer::plainText($validated['first_name']),
            lastName: TextSanitizer::plainText($validated['last_name']),
            nickname: TextSanitizer::nullablePlainText($validated['nickname']),
            locale: TextSanitizer::plainText($validated['locale']),
            timeFormat24h: $validated['time_format_24h'] === 'true' ? true : false,
        )->execute();

        return to_route('settings.index')
            ->with('status', __('Changes saved'));
    }
}
