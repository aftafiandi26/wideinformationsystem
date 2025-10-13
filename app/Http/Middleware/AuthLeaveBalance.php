<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Lang;

class AuthLeaveBalance
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
        if (auth()->user()->dept_category_id === 6) {
            if (auth()->user()->koor === 1) {
                return $next($request);
            }
            if (auth()->user()->pm === 1) {
                return $next($request);
            }
            if (auth()->user()->producer === 1) {
                return $next($request);
            }
        } else {
            return $next($request);
        }
        return redirect()->route('index')->with('getError', Lang::get('messages.no_access'));
    }
}
