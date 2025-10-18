<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class OptionalJwtAuthenticate
{
    public function __construct(private readonly JWTAuth $jwtAuth)
    {
    }

    /**
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            $request->setUserResolver(static fn () => null);

            return $next($request);
        }

        try {
            $user = $this->jwtAuth->setToken($token)->authenticate();
        } catch (JWTException $exception) {
            throw $exception;
        }

        $request->setUserResolver(static fn () => $user);
        auth()->setUser($user);

        return $next($request);
    }
}
