<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        abort_unless($request->user() !== null, 401);

        $member = $request->attributes->get('member');

        abort_unless(in_array($member->permission->value, $permissions), 403, 'Unauthorized action.');

        return $next($request);
    }
}
