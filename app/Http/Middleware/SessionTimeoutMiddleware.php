<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @codeCoverageIgnore
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $maxIdleTime = 120;

        $lastActivity = session('last_activity');

        if ($lastActivity && time() - $lastActivity > $maxIdleTime) {
            auth()->logout(); // <-- já é StatefulGuard
            session()->flush();

            return redirect()->route('welcome-horizon');
        }

        session(['last_activity' => time()]);

        return $next($request);
    }
}
