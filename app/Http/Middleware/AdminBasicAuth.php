<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedUser = (string) env('ADMIN_USER', '');
        $expectedPassword = (string) env('ADMIN_PASSWORD', '');

        if ($expectedUser === '' || $expectedPassword === '') {
            if (app()->environment('local')) {
                return $next($request);
            }

            return response('Forbidden', 403);
        }

        $user = (string) $request->getUser();
        $password = (string) $request->getPassword();

        if (! hash_equals($expectedUser, $user) || ! hash_equals($expectedPassword, $password)) {
            return response('Unauthorized', 401, [
                'WWW-Authenticate' => 'Basic realm="Admin"',
            ]);
        }

        return $next($request);
    }
}
