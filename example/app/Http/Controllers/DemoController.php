<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

/**
 * Demo Controller - Showcasing Laravel API Response Builder
 *
 * This controller demonstrates ZERO-CONFIGURATION auto-documentation.
 * No PHP attributes are used - documentation is generated automatically
 * by scanning the code for ApiResponse method calls!
 */
class DemoController extends Controller
{
    /**
     * =============================================
     * SUCCESS RESPONSES
     * =============================================
     */

    /**
     * Basic success response - returns a single user.
     */
    public function success(): JsonResponse
    {
        $data = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'admin'
        ];

        return ApiResponse::success($data, 'User retrieved successfully');
    }

    /**
     * List all users in the system.
     */
    public function users(): JsonResponse
    {
        $users = User::all();

        return ApiResponse::success($users, 'Users retrieved successfully');
    }

    /**
     * List users with pagination - pagination is auto-detected!
     */
    public function usersPaginated(): JsonResponse
    {
        // The package automatically detects paginate() calls
        // and documents this as a PaginatedResponse
        $users = User::paginate(5);

        return ApiResponse::success($users, 'Users retrieved with pagination');
    }

    /**
     * Create a new user in the system.
     */
    public function store(Request $request): JsonResponse
    {
        // Simulating user creation
        $user = [
            'id' => 99,
            'name' => $request->input('name', 'New User'),
            'email' => $request->input('email', 'newuser@example.com'),
            'created_at' => now()->toISOString()
        ];

        // ApiResponse::created() is auto-detected as 201 response
        return ApiResponse::created($user, 'User created successfully');
    }

    /**
     * Delete a user from the system.
     */
    public function destroy(): JsonResponse
    {
        // ApiResponse::noContent() is auto-detected as 204 response
        return ApiResponse::noContent();
    }

    /**
     * =============================================
     * ERROR RESPONSES
     * =============================================
     */

    /**
     * Example of a 400 Bad Request response.
     */
    public function badRequest(): JsonResponse
    {
        // Auto-detected as 400 response
        return ApiResponse::badRequest('Invalid request parameters');
    }

    /**
     * Example of a 401 Unauthorized response.
     */
    public function unauthorized(): JsonResponse
    {
        // Auto-detected as 401 response
        return ApiResponse::unauthorized('Please login to continue');
    }

    /**
     * Example of a 403 Forbidden response.
     */
    public function forbidden(): JsonResponse
    {
        // Auto-detected as 403 response
        return ApiResponse::forbidden('You do not have permission to access this resource');
    }

    /**
     * Example of a 404 Not Found response.
     */
    public function notFound(): JsonResponse
    {
        // Auto-detected as 404 response
        return ApiResponse::notFound('User not found');
    }

    /**
     * Example of validation errors with multiple field errors.
     */
    public function validationError(): JsonResponse
    {
        $errors = [
            'email' => ['The email field is required.', 'The email must be valid.'],
            'password' => ['The password must be at least 8 characters.']
        ];

        // Auto-detected as 422 ValidationErrorResponse
        return ApiResponse::validationError($errors, 'Validation failed');
    }

    /**
     * Example of rate limiting response with Retry-After header.
     */
    public function rateLimited(): JsonResponse
    {
        // Auto-detected as 429 response
        return ApiResponse::tooManyRequests('Too many requests. Please try again later.', 60);
    }

    /**
     * Example of a 500 Internal Server Error response.
     */
    public function serverError(): JsonResponse
    {
        // Auto-detected as 500 response
        return ApiResponse::serverError('Something went wrong on our end');
    }

    /**
     * =============================================
     * ADVANCED FEATURES
     * =============================================
     */

    /**
     * Demonstrates adding extra fields to the response using withData().
     */
    public function customData(): JsonResponse
    {
        $user = [
            'id' => 1,
            'name' => 'John Doe'
        ];

        return ApiResponse::success($user, 'User with extra info')
            ->withData('permissions', ['read', 'write', 'delete'])
            ->withData('last_login', '2025-01-15 10:30:00');
    }

    /**
     * Demonstrates adding custom HTTP headers to the response.
     */
    public function customHeaders(): JsonResponse
    {
        $data = ['status' => 'healthy'];

        return ApiResponse::success($data, 'API is healthy')
            ->withHeader('X-API-Version', '1.0.0')
            ->withHeader('X-Request-ID', uniqid());
    }

    /**
     * Submit a job for async processing with 202 Accepted response.
     */
    public function asyncJob(): JsonResponse
    {
        $job = [
            'job_id' => 'job_' . uniqid(),
            'status' => 'processing',
            'check_status_url' => '/api/jobs/status/abc123'
        ];

        // Auto-detected as 202 response
        return ApiResponse::accepted($job, 'Your request is being processed');
    }

    /**
     * Example of a 409 Conflict response when resource already exists.
     */
    public function conflict(): JsonResponse
    {
        // Auto-detected as 409 response
        return ApiResponse::conflict('This email is already registered');
    }
}
