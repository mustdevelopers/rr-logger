<?php

namespace MUST\RRLogger\Tests\Unit;

use MUST\RRLogger\Tests\TestCase;
use Illuminate\Http\Request;
use MUST\RRLogger\Http\Middleware\WriteRRLogs;
use MUST\RRLogger\Models\RRLogger;
use Illuminate\Support\Facades\Http;


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

    public function test_http_client_logs_requests_and_responses()
    {
        // Make sure the table is empty before the test
        RRLogger::truncate();

        // Define the URL and payload
        $url = 'https://example.com/api/test-endpoint';
        $data = ['key' => 'value'];

        // Mock the HTTP request
        Http::fake([
            $url => Http::response(['response_key' => 'response_value'], 200),
        ]);

        // Perform an HTTP request using the custom client
        $response = Http::post($url, $data);

        // Check if the RRLogger record was created
        $this->assertDatabaseHas('rrloggers', [
            'endpoint' => $url,
            'method' => 'POST',
            'status' => 200,
            'success' => true,
            'request' => json_encode($data),
            'response' => $response->body(),
        ]);

        // Optionally, you can assert more details
        $logEntry = RRLogger::latest()->first();
        $this->assertNotNull($logEntry);
        $this->assertEquals($url, $logEntry->endpoint);
        $this->assertEquals('POST', $logEntry->method);
        $this->assertEquals(200, $logEntry->status);
        $this->assertTrue($logEntry->success);
        $this->assertEquals(json_encode($data), $logEntry->request);
        $this->assertEquals($response->body(), $logEntry->response);
    }
}
