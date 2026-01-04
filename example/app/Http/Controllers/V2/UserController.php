<?php

namespace App\Http\Controllers\V2;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::paginate(15);

        return ApiResponse::success($users, 'Users retrieved successfully')
            ->header('X-API-Version', 'v2');
    }

    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        $userData = $user->toArray();
        $userData['profile_complete'] = ! empty($user->name) && ! empty($user->email);
        $userData['account_age_days'] = $user->created_at?->diffInDays(now()) ?? 0;

        return ApiResponse::success($userData, 'User retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'sometimes|string|in:admin,user,moderator',
            'metadata' => 'sometimes|array',
        ]);

        $user = [
            'id' => rand(100, 999),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? 'user',
            'metadata' => $validated['metadata'] ?? [],
            'created_at' => now()->toISOString(),
        ];

        return ApiResponse::created($user, 'User created successfully');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'role' => 'sometimes|string|in:admin,user,moderator',
        ]);

        return ApiResponse::success(
            array_merge($user->toArray(), $validated),
            'User updated successfully'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::noContent();
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'users' => 'required|array|min:1|max:100',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email',
        ]);

        $createdUsers = [];
        foreach ($validated['users'] as $userData) {
            $createdUsers[] = [
                'id' => rand(100, 999),
                'name' => $userData['name'],
                'email' => $userData['email'],
                'created_at' => now()->toISOString(),
            ];
        }

        return ApiResponse::created([
            'count' => count($createdUsers),
            'users' => $createdUsers,
        ], count($createdUsers) . ' users created successfully');
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|integer',
        ]);

        $deletedCount = count($validated['ids']);

        return ApiResponse::success([
            'deleted_count' => $deletedCount,
            'deleted_ids' => $validated['ids'],
        ], $deletedCount . ' users deleted successfully');
    }
}
