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
        if ($request->bearerToken()) {
            try {
                $this->jwtAuth->setToken($request->bearerToken())->authenticate();
            } catch (JWTException $exception) {
                throw $exception;
            }
        }

        return $next($request);
    }
}
