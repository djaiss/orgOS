<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class CheckOrganization
{
    /**
     * Check if the user is a member of the organization.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route()->parameter('slug');
        $id = (int) Str::before($slug, '-');

        try {
            $organization = Organization::query()->findOrFail($id);

            $member = $request->user()->memberOf($organization);
            abort_unless($member !== null, 403);

            $request->attributes->add(['organization' => $organization]);
            $request->attributes->add(['member' => $member]);

            View::share('organization', $organization);
            View::share('member', $member);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
