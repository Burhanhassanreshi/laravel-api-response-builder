<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

/**
 * User Controller - Demonstrates FormRequest Auto-Documentation
 *
 * This controller shows how the package automatically extracts
 * validation rules from FormRequest classes and converts them
 * to OpenAPI request body schemas - no attributes needed!
 */
class UserController extends Controller
{
    /**
     * List all users with pagination.
     */
    public function index(): JsonResponse
    {
        // Pagination is auto-detected from paginate() call
        $users = User::paginate(15);

        return ApiResponse::success($users, 'Users retrieved successfully');
    }

    /**
     * Get a single user by ID.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::success($user, 'User retrieved successfully');
    }

    /**
     * Create a new user.
     *
     * The CreateUserRequest FormRequest is used here.
     * Its validation rules are automatically extracted and
     * converted to an OpenAPI request body schema!
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        // In a real app, you'd create the user here
        $user = [
            'id' => rand(100, 999),
            'name' => $request->validated()['name'],
            'email' => $request->validated()['email'],
            'role' => $request->validated()['role'] ?? 'user',
            'created_at' => now()->toISOString(),
        ];

        return ApiResponse::created($user, 'User created successfully');
    }

    /**
     * Update an existing user.
     */
    public function update(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        // Simulating update
        return ApiResponse::success($user, 'User updated successfully');
    }

    /**
     * Delete a user.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::noContent();
    }
}
