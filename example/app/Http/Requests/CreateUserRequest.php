<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for creating a new user.
 *
 * This FormRequest demonstrates automatic schema generation.
 * The validation rules below are automatically converted to OpenAPI schema!
 */
class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * These rules are automatically extracted and converted to OpenAPI schema:
     * - 'required' -> marks field as required
     * - 'string', 'integer', 'boolean' -> sets correct type
     * - 'email' -> adds email format
     * - 'min:X', 'max:X' -> adds constraints
     * - 'in:a,b,c' -> creates enum
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role' => 'string|in:admin,user,moderator',
            'age' => 'integer|min:18|max:120',
            'is_active' => 'boolean',
            'bio' => 'nullable|string|max:500',
        ];
    }
}
