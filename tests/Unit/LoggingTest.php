<?php

namespace MUST\RRLogger\Tests\Unit;

use MUST\RRLogger\Tests\TestCase;
use Illuminate\Http\Request;
use MUST\RRLogger\Http\Middleware\WriteRRLogs;
use MUST\RRLogger\Models\RRLogger;
use MUST\RRLogger\Http\HttpClient\RRLoggerHttpClient;

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

        dd(config('rrlogger.max_content_length'));
    }
    public function test_create_rrlogger_for_incoming_requests()
    {
        $response = $this->postJson('/api/test-endpoint', [
            'some_field' => 'some_value',
        ]);

        $response->assertStatus(200);

        // Assert that the RRLogger record was created
        $this->assertDatabaseHas('rrloggers', [
            'endpoint' => 'api/test-endpoint',
            'method' => 'POST',
            'status' => 200,
            'success' => 1,
            'request_type' => 'Incoming',
        ]);
    }

    public function test_http_client_logs_requests_and_responses()
    {
        RRLogger::truncate();

        $url = 'https://example.com/api/test-endpoint';
        $data = ['key' => 'value'];

        // Mock the HTTP request
        RRLoggerHttpClient::fake([
            $url => RRLoggerHttpClient::response(['response_key' => 'response_value'], 200),
        ]);

        $response = RRLoggerHttpClient::post($url, $data);

        // Check if the RRLogger record was created
        $this->assertDatabaseHas('rrloggers', [
            'status' => 200,
            'success' => 1,
            'request_type' => "Outgoing",
            'response' => $response->body(),
        ]);
    }
}
