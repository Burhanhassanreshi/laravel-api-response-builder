<?php

namespace Stackmasteraliza\ApiResponse\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

class ApiExceptionHandler
{
    /**
     * Handle the exception and return a JSON response.
     */
    public function handle(Throwable $exception, Request $request): ?JsonResponse
    {
        if (! $request->expectsJson() && ! $request->is('api/*')) {
            return null;
        }

        return match (true) {
            $exception instanceof ValidationException => $this->handleValidationException($exception),
            $exception instanceof AuthenticationException => $this->handleAuthenticationException($exception),
            $exception instanceof AuthorizationException => $this->handleAuthorizationException($exception),
            $exception instanceof ModelNotFoundException => $this->handleModelNotFoundException($exception),
            $exception instanceof NotFoundHttpException => $this->handleNotFoundHttpException($exception),
            $exception instanceof MethodNotAllowedHttpException => $this->handleMethodNotAllowedHttpException($exception),
            $exception instanceof TooManyRequestsHttpException => $this->handleTooManyRequestsHttpException($exception),
            $exception instanceof HttpException => $this->handleHttpException($exception),
            default => $this->handleGenericException($exception),
        };
    }

    /**
     * Handle validation exception.
     */
    protected function handleValidationException(ValidationException $exception): JsonResponse
    {
        return ApiResponse::validationError(
            $exception->errors(),
            $exception->getMessage()
        );
    }

    /**
     * Handle authentication exception.
     */
    protected function handleAuthenticationException(AuthenticationException $exception): JsonResponse
    {
        return ApiResponse::unauthorized(
            $exception->getMessage() ?: 'Unauthenticated'
        );
    }

    /**
     * Handle authorization exception.
     */
    protected function handleAuthorizationException(AuthorizationException $exception): JsonResponse
    {
        return ApiResponse::forbidden(
            $exception->getMessage() ?: 'Forbidden'
        );
    }

    /**
     * Handle model not found exception.
     */
    protected function handleModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        $modelName = class_basename($exception->getModel());

        return ApiResponse::notFound(
            "{$modelName} not found"
        );
    }

    /**
     * Handle not found HTTP exception.
     */
    protected function handleNotFoundHttpException(NotFoundHttpException $exception): JsonResponse
    {
        return ApiResponse::notFound(
            $exception->getMessage() ?: 'Resource not found'
        );
    }

    /**
     * Handle method not allowed HTTP exception.
     */
    protected function handleMethodNotAllowedHttpException(MethodNotAllowedHttpException $exception): JsonResponse
    {
        return ApiResponse::methodNotAllowed(
            $exception->getMessage() ?: 'Method not allowed'
        );
    }

    /**
     * Handle too many requests HTTP exception.
     */
    protected function handleTooManyRequestsHttpException(TooManyRequestsHttpException $exception): JsonResponse
    {
        $retryAfter = $exception->getHeaders()['Retry-After'] ?? null;

        return ApiResponse::tooManyRequests(
            $exception->getMessage() ?: 'Too many requests',
            $retryAfter ? (int) $retryAfter : null
        );
    }

    /**
     * Handle generic HTTP exception.
     */
    protected function handleHttpException(HttpException $exception): JsonResponse
    {
        return ApiResponse::error(
            $exception->getMessage() ?: 'An error occurred',
            $exception->getStatusCode()
        );
    }

    /**
     * Handle generic exception.
     */
    protected function handleGenericException(Throwable $exception): JsonResponse
    {
        $message = config('app.debug')
            ? $exception->getMessage()
            : 'Internal server error';

        $response = ApiResponse::serverError($message);

        if (config('app.debug') && config('api-response.include_debug_info', false)) {
            $response->setData(array_merge(
                json_decode($response->getContent(), true),
                [
                    'debug' => [
                        'exception' => get_class($exception),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => collect($exception->getTrace())->take(5)->toArray(),
                    ],
                ]
            ));
        }

        return $response;
    }
}
