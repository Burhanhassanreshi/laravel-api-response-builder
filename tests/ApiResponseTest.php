<?php

namespace Stackmasteraliza\ApiResponse\Tests;

use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\Attributes\Test;
use Stackmasteraliza\ApiResponse\Facades\ApiResponse;

class ApiResponseTest extends TestCase
{
    #[Test]
    public function it_returns_success_response(): void
    {
        $response = ApiResponse::success(['id' => 1, 'name' => 'Test'], 'Success message');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertSame('Success message', $data['message']);
        $this->assertSame(['id' => 1, 'name' => 'Test'], $data['data']);
    }

    #[Test]
    public function it_returns_error_response(): void
    {
        $response = ApiResponse::error('Error message', 400);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Error message', $data['message']);
    }

    #[Test]
    public function it_returns_created_response(): void
    {
        $response = ApiResponse::created(['id' => 1], 'Resource created');

        $this->assertSame(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertSame('Resource created', $data['message']);
    }

    #[Test]
    public function it_returns_no_content_response(): void
    {
        $response = ApiResponse::noContent();

        $this->assertSame(204, $response->getStatusCode());
    }

    #[Test]
    public function it_returns_unauthorized_response(): void
    {
        $response = ApiResponse::unauthorized('Not authenticated');

        $this->assertSame(401, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Not authenticated', $data['message']);
    }

    #[Test]
    public function it_returns_forbidden_response(): void
    {
        $response = ApiResponse::forbidden('Access denied');

        $this->assertSame(403, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Access denied', $data['message']);
    }

    #[Test]
    public function it_returns_not_found_response(): void
    {
        $response = ApiResponse::notFound('User not found');

        $this->assertSame(404, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('User not found', $data['message']);
    }

    #[Test]
    public function it_returns_validation_error_response(): void
    {
        $errors = [
            'email' => ['The email field is required.'],
            'name' => ['The name field is required.'],
        ];

        $response = ApiResponse::validationError($errors, 'Validation failed');

        $this->assertSame(422, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Validation failed', $data['message']);
        $this->assertSame($errors, $data['errors']);
    }

    #[Test]
    public function it_returns_server_error_response(): void
    {
        $response = ApiResponse::serverError('Something went wrong');

        $this->assertSame(500, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Something went wrong', $data['message']);
    }

    #[Test]
    public function it_returns_too_many_requests_response(): void
    {
        $response = ApiResponse::tooManyRequests('Rate limit exceeded', 60);

        $this->assertSame(429, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Rate limit exceeded', $data['message']);
    }

    #[Test]
    public function it_returns_accepted_response(): void
    {
        $response = ApiResponse::accepted(['job_id' => 123], 'Request accepted for processing');

        $this->assertSame(202, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertSame('Request accepted for processing', $data['message']);
        $this->assertSame(['job_id' => 123], $data['data']);
    }

    #[Test]
    public function it_returns_conflict_response(): void
    {
        $response = ApiResponse::conflict('Resource already exists');

        $this->assertSame(409, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Resource already exists', $data['message']);
    }

    #[Test]
    public function it_returns_bad_request_response(): void
    {
        $response = ApiResponse::badRequest('Invalid input');

        $this->assertSame(400, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Invalid input', $data['message']);
    }

    #[Test]
    public function it_returns_service_unavailable_response(): void
    {
        $response = ApiResponse::serviceUnavailable('Service temporarily unavailable');

        $this->assertSame(503, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertSame('Service temporarily unavailable', $data['message']);
    }

    #[Test]
    public function it_handles_null_data(): void
    {
        $response = ApiResponse::success(null, 'No data');

        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
        $this->assertNull($data['data']);
    }

    #[Test]
    public function it_handles_array_data(): void
    {
        $response = ApiResponse::success([
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ]);

        $this->assertSame(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(2, $data['data']);
    }
}
