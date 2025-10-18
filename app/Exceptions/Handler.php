<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler
{
    /**
     * Render an exception into a JSON HTTP response compliant with RealWorld spec.
     */
    public function render(Throwable $e): JsonResponse
    {
        $status = $this->statusCode($e);

        if ($e instanceof ValidationException) {
            return response()->json([
                'errors' => $this->formatValidationErrors($e),
            ], $status);
        }

        $message = $e->getMessage();

        if ($message === '' && $status === 404) {
            $message = 'Resource not found.';
        }

        return response()->json([
            'errors' => [[
                'message' => $message ?: (Response::$statusTexts[$status] ?? 'Error'),
                'code' => $status,
            ]],
        ], $status);
    }

    private function statusCode(Throwable $e): int
    {
        return match (true) {
            $e instanceof ValidationException => $e->status,
            $e instanceof AuthenticationException => 401,
            $e instanceof AuthorizationException, $e instanceof UnauthorizedException => 403,
            $e instanceof ModelNotFoundException => 404,
            $e instanceof HttpResponseException => $e->getResponse()->getStatusCode(),
            $e instanceof HttpExceptionInterface => $e->getStatusCode(),
            default => 500,
        };
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function formatValidationErrors(ValidationException $exception): array
    {
        return collect($exception->errors())
            ->map(fn (array $messages, string $field) => [
                'field' => $field,
                'messages' => $messages,
            ])
            ->values()
            ->all();
    }
}
