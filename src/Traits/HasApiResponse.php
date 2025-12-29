<?php

namespace Stackmasteraliza\ApiResponse\Traits;

use Illuminate\Http\JsonResponse;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

trait HasApiResponse
{
    /**
     * Return a success response.
     */
    protected function success(mixed $data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return ApiResponse::success($data, $message, $statusCode);
    }

    /**
     * Return an error response.
     */
    protected function error(string $message = 'Error', int $statusCode = 400, mixed $errors = null): JsonResponse
    {
        return ApiResponse::error($message, $statusCode, $errors);
    }

    /**
     * Return a created response (201).
     */
    protected function created(mixed $data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return ApiResponse::created($data, $message);
    }

    /**
     * Return a no content response (204).
     */
    protected function noContent(): JsonResponse
    {
        return ApiResponse::noContent();
    }

    /**
     * Return an accepted response (202).
     */
    protected function accepted(mixed $data = null, string $message = 'Request accepted'): JsonResponse
    {
        return ApiResponse::accepted($data, $message);
    }

    /**
     * Return a bad request response (400).
     */
    protected function badRequest(string $message = 'Bad request', mixed $errors = null): JsonResponse
    {
        return ApiResponse::badRequest($message, $errors);
    }

    /**
     * Return an unauthorized response (401).
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return ApiResponse::unauthorized($message);
    }

    /**
     * Return a forbidden response (403).
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return ApiResponse::forbidden($message);
    }

    /**
     * Return a not found response (404).
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return ApiResponse::notFound($message);
    }

    /**
     * Return a validation error response (422).
     */
    protected function validationError(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return ApiResponse::validationError($errors, $message);
    }

    /**
     * Return a server error response (500).
     */
    protected function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return ApiResponse::serverError($message);
    }
}
