# Laravel API Response Builder

A clean and consistent API response builder for Laravel applications. This package provides a simple and elegant way to build standardized JSON responses for your APIs.

## Features

- Consistent API response structure
- Fluent interface for building responses
- Built-in methods for common HTTP status codes
- Automatic pagination metadata
- Exception handler for consistent error responses
- Facade and Trait support
- Fully customizable via config

## Installation

You can install the package via composer:

```bash
composer require stackmasteraliza/laravel-api-response
```

The package will automatically register itself.

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=api-response-config
```

## Usage

### Using the Facade

```php
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

// Success response
return ApiResponse::success($data, 'Data retrieved successfully');

// Error response
return ApiResponse::error('Something went wrong', 400);

// Created response (201)
return ApiResponse::created($user, 'User created successfully');

// Not found response (404)
return ApiResponse::notFound('User not found');

// Validation error response (422)
return ApiResponse::validationError([
    'email' => ['The email field is required.'],
    'name' => ['The name field is required.'],
]);
```

### Using the Trait

```php
use Stackmasteraliza\ApiResponse\Traits\HasApiResponse;

class UserController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        $users = User::paginate(15);

        return $this->success($users, 'Users retrieved successfully');
    }

    public function store(Request $request)
    {
        $user = User::create($request->validated());

        return $this->created($user, 'User created successfully');
    }

    public function show(User $user)
    {
        return $this->success($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $this->noContent();
    }
}
```

### Available Methods

#### Success Responses

| Method | Status Code | Description |
|--------|-------------|-------------|
| `success($data, $message, $statusCode)` | 200 | General success response |
| `created($data, $message)` | 201 | Resource created |
| `accepted($data, $message)` | 202 | Request accepted for processing |
| `noContent()` | 204 | No content to return |

#### Error Responses

| Method | Status Code | Description |
|--------|-------------|-------------|
| `error($message, $statusCode, $errors)` | Variable | General error response |
| `badRequest($message, $errors)` | 400 | Bad request |
| `unauthorized($message)` | 401 | Unauthorized |
| `forbidden($message)` | 403 | Forbidden |
| `notFound($message)` | 404 | Resource not found |
| `methodNotAllowed($message)` | 405 | Method not allowed |
| `conflict($message, $errors)` | 409 | Conflict |
| `unprocessable($message, $errors)` | 422 | Unprocessable entity |
| `validationError($errors, $message)` | 422 | Validation failed |
| `tooManyRequests($message, $retryAfter)` | 429 | Too many requests |
| `serverError($message)` | 500 | Internal server error |
| `serviceUnavailable($message)` | 503 | Service unavailable |

### Response Structure

#### Success Response

```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

#### Paginated Response

```json
{
    "success": true,
    "message": "Users retrieved successfully",
    "data": [
        {"id": 1, "name": "John Doe"},
        {"id": 2, "name": "Jane Doe"}
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 50,
        "last_page": 4,
        "from": 1,
        "to": 15,
        "path": "http://example.com/api/users",
        "links": {
            "first": "http://example.com/api/users?page=1",
            "last": "http://example.com/api/users?page=4",
            "prev": null,
            "next": "http://example.com/api/users?page=2"
        }
    }
}
```

#### Error Response

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "name": ["The name field is required."]
    }
}
```

### Exception Handling

To use the built-in exception handler, update your `bootstrap/app.php` (Laravel 11+):

```php
use Stackmasteraliza\ApiResponse\Exceptions\ApiExceptionHandler;

->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (Throwable $e, Request $request) {
        $handler = new ApiExceptionHandler();
        return $handler->handle($e, $request);
    });
})
```

Or for Laravel 10, update your `app/Exceptions/Handler.php`:

```php
use Stackmasteraliza\ApiResponse\Exceptions\ApiExceptionHandler;

public function render($request, Throwable $exception)
{
    $handler = new ApiExceptionHandler();
    $response = $handler->handle($exception, $request);

    if ($response) {
        return $response;
    }

    return parent::render($request, $exception);
}
```

### Middleware

Use the `ForceJsonResponse` middleware to ensure all API responses are JSON:

```php
// In your route file or middleware group
use Stackmasteraliza\ApiResponse\Http\Middleware\ForceJsonResponse;

Route::middleware([ForceJsonResponse::class])->group(function () {
    // Your API routes
});
```

### Adding Custom Data

```php
return ApiResponse::success($user)
    ->withData('token', $token)
    ->withHeader('X-Custom-Header', 'value');
```

## Configuration

```php
return [
    // Default messages
    'default_success_message' => 'Success',
    'default_error_message' => 'Error',

    // Include debug info in error responses (when APP_DEBUG=true)
    'include_debug_info' => env('API_RESPONSE_DEBUG', false),

    // Customize response keys
    'keys' => [
        'success' => 'success',
        'message' => 'message',
        'data' => 'data',
        'errors' => 'errors',
        'meta' => 'meta',
    ],
];
```

## Testing

```bash
composer test
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
