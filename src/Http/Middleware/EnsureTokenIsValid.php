<?php
 
namespace Biigle\Modules\Kpis\Http\Middleware;
 
use Closure;
use Illuminate\Support\Facades\Log;
 
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
        if ($request->header('authorization') !== env('KPI_TOKEN')) {
            return response('Unauthorized.', 401);
        }
 
        return $next($request);
    }
}