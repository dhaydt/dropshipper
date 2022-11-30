<?php

namespace App\Http\Middleware;

use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use http\Client;

class ActivationCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = Helpers::requestSender();
        if ($data['active']) {
            return $next($request);
        }
        return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
    }
}
