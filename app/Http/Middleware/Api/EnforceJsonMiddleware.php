<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class EnforceJsonMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        // Check if the Content-Type is set to application/json
        if (!$request->isJson() && $request->header('Accept') !== 'application/json') {
            abort(404);
        }

        return $next($request);
    }
}
