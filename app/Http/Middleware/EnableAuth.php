<?php

namespace App\Http\Middleware;

use Closure;

class EnableAuth
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
        if (config('app.enable_auth') && auth()->guest()) {
            \Log::debug("Auth enabled and not logged in, redirecting...");
            return redirect()->route('login');
        }

        return $next($request);
    }
}
