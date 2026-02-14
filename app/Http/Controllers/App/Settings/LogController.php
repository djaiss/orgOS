<?php

declare(strict_types = 1);

namespace App\Http\Controllers\App\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = Log::query()
            ->where('user_id', $request->user()->id)
            ->with('user')
            ->with('organization')
            ->latest()
            ->cursorPaginate(10)
            ->through(fn (Log $log) => (object) [
                'username' => $log->getUserName(),
                'organization_name' => $log->organization?->name,
                'organization_link' => $log->organization ? route('organization.show', $log->organization_id) : null,
                'action' => $log->action,
                'description' => $log->description,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                'created_at_human' => $log->created_at->diffForHumans(),
            ]);

        return view('app.settings.logs.index', [
            'logs' => $logs,
        ]);
    }
}
