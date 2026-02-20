<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Settings;

use App\Actions\CreateApiKey;
use App\Actions\DestroyApiKey;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function create(): View
    {
        return view('app.settings.security._api-create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'min:3', 'max:255'],
        ]);

        $apiKey = new CreateApiKey(
            user: $request->user(),
            label: TextSanitizer::plainText($validated['label']),
        )->execute();

        return to_route('settings.security.index')
            ->with('apiKey', $apiKey)
            ->with('status', trans('API key created'));
    }

    public function destroy(Request $request, int $apiKeyId): RedirectResponse
    {
        $apiKey = $request->user()
            ->tokens()
            ->where('id', $apiKeyId)
            ->first();

        new DestroyApiKey(
            user: $request->user(),
            tokenId: $apiKey->id,
        )->execute();

        return to_route('settings.security.index')
            ->with('status', trans('API key deleted'));
    }
}
