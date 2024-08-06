<?php

namespace MUST\RRLogger\Tests\Unit;

use MUST\RRLogger\Tests\TestCase;
use Illuminate\Http\Request;
use MUST\RRLogger\Http\Middleware\WriteRRLogs;

class LoggingTest extends TestCase
{
    public function test_that_middleware_runs()
    {
        // Given we have a request
        $request = new Request();

        (new WriteRRLogs())->handle($request, function () {
            return (object) [
                'status' => 200,
                'success' => true,
            ];
        });
    }
    public function test_create_rrlogger()
    {
        // Make a request to the test endpoint
        $response = $this->postJson('/api/test-endpoint', [
            'some_field' => 'some_value',
        ]);

        // Assert that the response is successful
        $response->assertStatus(200);

        //Assert that the RRLogger record was created
        $this->assertDatabaseHas('rrloggers', [
            'endpoint' => '/api/test-endpoint',
            'method' => 'POST',
            'status' => 200,
            'success' => true,
            'request_type' => 'Incoming'
        ]);
    }
}