<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizationAPI
{
    /**
     * Check if the user has access to the organization.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route()->parameter('id');

        try {
            $organization = Organization::query()->findOrFail($id);

            abort_unless($request->user()->memberOf($organization) !== null, 403);

            $request->attributes->add(['organization' => $organization]);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
