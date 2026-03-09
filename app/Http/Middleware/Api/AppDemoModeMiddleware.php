<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppDemoModeMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {

        if (strtoupper(env('APP_MODE')) !== 'LIVE') {
            $allowedRoutes = [
                'api.client-register', 'api.client-login', 'api.client-forget-password', 'api.client-reset-password', 'api.client-logout', 'api.client-logoutAllApp', 'api.lawyer-register', 'api.lawyer-login', 'api.lawyer-forget-password', 'api.lawyer-reset-password', 'api.lawyer-logout', 'api.lawyer-logoutAllApp',
            ];

            if (request()->routeIs(...$allowedRoutes) || request()->method() == 'GET') {
                return $next($request);
            } else {
                return response()->json(['status' => 'failed', 'message' => __('In Demo Mode You Can Not Perform This Action')], 403);
            }
        }

        return $next($request);
    }
}
