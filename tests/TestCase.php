<?php

namespace Stackmasteraliza\ApiResponse\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Stackmasteraliza\ApiResponse\ApiResponseServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ApiResponseServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'ApiResponse' => \Stackmasteraliza\ApiResponse\Facades\ApiResponse::class,
        ];
    }
}
