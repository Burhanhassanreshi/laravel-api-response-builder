<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\V1\UserController as UserControllerV1;
use App\Http\Controllers\V2\UserController as UserControllerV2;
use Illuminate\Support\Facades\Route;

// Versioned API Routes
Route::prefix('v1')->group(function () {
    Route::get('/users', [UserControllerV1::class, 'index']);
    Route::get('/users/{id}', [UserControllerV1::class, 'show']);
    Route::post('/users', [UserControllerV1::class, 'store']);
    Route::put('/users/{id}', [UserControllerV1::class, 'update']);
    Route::delete('/users/{id}', [UserControllerV1::class, 'destroy']);
});

Route::prefix('v2')->group(function () {
    Route::get('/users', [UserControllerV2::class, 'index']);
    Route::get('/users/{id}', [UserControllerV2::class, 'show']);
    Route::post('/users', [UserControllerV2::class, 'store']);
    Route::put('/users/{id}', [UserControllerV2::class, 'update']);
    Route::delete('/users/{id}', [UserControllerV2::class, 'destroy']);
    Route::post('/users/bulk', [UserControllerV2::class, 'bulkStore']);
    Route::delete('/users/bulk', [UserControllerV2::class, 'bulkDestroy']);
});

// User Resource
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Demo Endpoints
Route::get('/demo/success', [DemoController::class, 'success']);
Route::get('/demo/users', [DemoController::class, 'users']);
Route::get('/demo/users-paginated', [DemoController::class, 'usersPaginated']);
Route::post('/demo/users', [DemoController::class, 'store']);
Route::delete('/demo/users/{id}', [DemoController::class, 'destroy']);
Route::get('/demo/bad-request', [DemoController::class, 'badRequest']);
Route::get('/demo/unauthorized', [DemoController::class, 'unauthorized']);
Route::get('/demo/forbidden', [DemoController::class, 'forbidden']);
Route::get('/demo/not-found', [DemoController::class, 'notFound']);
Route::post('/demo/validate', [DemoController::class, 'validationError']);
Route::get('/demo/rate-limited', [DemoController::class, 'rateLimited']);
Route::get('/demo/server-error', [DemoController::class, 'serverError']);
Route::get('/demo/custom-data', [DemoController::class, 'customData']);
Route::get('/demo/custom-headers', [DemoController::class, 'customHeaders']);
Route::post('/demo/async-job', [DemoController::class, 'asyncJob']);
Route::post('/demo/conflict', [DemoController::class, 'conflict']);
