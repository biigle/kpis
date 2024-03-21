<?php

namespace Biigle\Modules\Kpis\Http\Middleware;

use Closure;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->header('authorization')) {
            return response('Unauthorized', 401);
        }

        if ($request->header('authorization') !== env('KPI_TOKEN')) {
            return response('Unauthorized.', 403);
        }

        return $next($request);
    }
}
