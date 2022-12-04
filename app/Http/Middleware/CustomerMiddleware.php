<?php

namespace App\Http\Middleware;

use function App\CPU\translate;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('customer')->check() && auth('customer')->user()->is_active) {
            return $next($request);
        } elseif (Auth::guard('customer')->check()) {
            auth()->guard('customer')->logout();
        }

        if (Auth::guard('seller')->check()) {
            return $next($request);
        }
        Toastr::info(translate('login_first_for_next_steps'));

        return redirect()->route('customer.auth.login');
    }
}
