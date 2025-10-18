<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransformTokenHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->headers->get('Authorization');

        if (is_string($authorization)) {
            $matches = [];

            if (preg_match('/^Token\s+(.+)$/i', trim($authorization), $matches) === 1) {
                $request->headers->set('Authorization', 'Bearer ' . trim($matches[1]));
            }
        }

        return $next($request);
    }
}
