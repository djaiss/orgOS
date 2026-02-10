<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Auth;

use App\Actions\CreateMagicLink;
use App\Enums\EmailType;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Mail\MagicLinkCreated;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SendMagicLinkController extends Controller
{
    public function create(): View
    {
        $quotes = config('quotes');
        $randomQuote = $quotes[array_rand($quotes)];

        return view('app.auth.request-magic-link', [
            'quote' => $randomQuote,
        ]);
    }

    public function store(Request $request): View
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $email = mb_strtolower((string) $request->input('email'));

        try {
            $link = new CreateMagicLink(
                email: $email,
            )->execute();

            SendEmail::dispatch(
                mailable: new MagicLinkCreated(
                    link: $link,
                ),
                user: User::query()->where('email', $email)->firstOrFail(),
                emailType: EmailType::MAGIC_LINK_CREATED,
            )->onQueue('high');
        } catch (ModelNotFoundException) {
        }

        $quotes = config('quotes');
        $randomQuote = $quotes[array_rand($quotes)];

        return view('app.auth.magic-link-sent', [
            'quote' => $randomQuote,
        ]);
    }
}
