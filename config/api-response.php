<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Success Message
    |--------------------------------------------------------------------------
    |
    | This value is the default success message that will be used when no
    | message is provided to the success response methods.
    |
    */

    'default_success_message' => 'Success',

    /*
    |--------------------------------------------------------------------------
    | Default Error Message
    |--------------------------------------------------------------------------
    |
    | This value is the default error message that will be used when no
    | message is provided to the error response methods.
    |
    */

    'default_error_message' => 'Error',

    /*
    |--------------------------------------------------------------------------
    | Include Debug Information
    |--------------------------------------------------------------------------
    |
    | When set to true and the application is in debug mode, additional
    | debug information will be included in error responses, such as
    | exception traces and file locations.
    |
    */

    'include_debug_info' => env('API_RESPONSE_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Response Keys
    |--------------------------------------------------------------------------
    |
    | Customize the keys used in the API response structure. This allows
    | you to match your existing API conventions or standards.
    |
    */

    'keys' => [
        'success' => 'success',
        'message' => 'message',
        'data' => 'data',
        'errors' => 'errors',
        'meta' => 'meta',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Meta Keys
    |--------------------------------------------------------------------------
    |
    | Customize the keys used in the pagination metadata. This allows you
    | to standardize your paginated responses across all endpoints.
    |
    */

    'pagination_keys' => [
        'current_page' => 'current_page',
        'per_page' => 'per_page',
        'total' => 'total',
        'last_page' => 'last_page',
        'from' => 'from',
        'to' => 'to',
        'path' => 'path',
        'links' => 'links',
    ],

];
