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
        $request = new Request();

        $response = (new WriteRRLogs())->handle($request, function () {
            return (object) [
                'status' => 200,
                'success' => true,
            ];
        });

        $this->assertEquals(200, $response->status);
        $this->assertTrue($response->success);
    }
    public function test_create_rrlogger_for_incoming_requests()
    {
        $response = $this->postJson('/api/test-endpoint', [
            'some_field' => 'some_value',
            'password' => '1234',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
        ]);

        $this->assertDatabaseHas('rrloggers', [
            "id" => 1,  
            "user_id" => null,
            "endpoint" => "/api/test-endpoint",
            "method" => "POST",
            "ip_address" => "127.0.0.1",
            "request" => '{"some_field":"some_value"}',
            "request_type" => "Incoming",
            "response" => '{"status":"success"}',
            "status" => 200,
            "success" => 1,
            "message" => null

        ]);
    }

    public function test_http_client_logs_requests_and_responses()
    {
        RRLogger::truncate();

        $url = 'https://example.com/api/test-endpoint';
        $data = ['key' => 'value'];

        RRLoggerHttpClient::fake([
            $url => RRLoggerHttpClient::response(['response_key' => 'response_value'], 200),
        ]);

        $response = RRLoggerHttpClient::post($url, $data);

        $this->assertDatabaseHas('rrloggers', [
            'status' => 200,
            'success' => 1,
            'request_type' => "Outgoing",
            'response' => json_encode(['response_key' => 'response_value']), // Ensure the response is correctly logged
        ]);
    }
}
