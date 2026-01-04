<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::paginate(10);

        return ApiResponse::success($users, 'Users retrieved');
    }

    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::success($user);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $user = [
            'id' => rand(100, 999),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'created_at' => now()->toISOString(),
        ];

        return ApiResponse::created($user, 'User created');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::success($user, 'User updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return ApiResponse::notFound('User not found');
        }

        return ApiResponse::noContent();
    }
}
