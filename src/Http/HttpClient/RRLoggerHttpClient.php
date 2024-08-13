<?php
namespace MUST\RRLogger\Http\HttpClient;

use Illuminate\Support\Facades\Http as BaseHttp;
use MUST\RRLogger\Models\RRLogger;
use Illuminate\Http\Client\Response;

class RRLoggerHttpClient extends BaseHttp
{
    public static function post($url, array $data = [], array $options = [])
    {
        return self::logRequestAndResponse(function () use ($url, $data, $options) {
            return parent::post($url, $data, $options);
        });
    }

    public static function put($url, array $data = [], array $options = [])
    {
        return self::logRequestAndResponse(function () use ($url, $data, $options) {
            return parent::put($url, $data, $options);
        });
    }

    public static function patch($url, array $data = [], array $options = [])
    {
        return self::logRequestAndResponse(function () use ($url, $data, $options) {
            return parent::patch($url, $data, $options);
        });
    }

    public static function get($url, array $options = [])
    {
        return self::logRequestAndResponse(function () use ($url, $options) {
            return parent::get($url, $options);
        });
    }

    public static function delete($url, array $options = [])
    {
        return self::logRequestAndResponse(function () use ($url, $options) {
            return parent::delete($url, $options);
        });
    }

    private static function logRequestAndResponse(callable $callback)
    {
        // Start timing
        $start = microtime(true);

        // Capture the request object
        $request = app('request');

        // Execute the HTTP request
        /** @var Response $response */
        $response = $callback();

        // Calculate elapsed time
        $end = microtime(true);
        $milliseconds = round(($end - $start) * 1000);

        RRLogger::create([
            'endpoint' => $request->fullUrl(), 
            'uri' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'request_type' => 'Outgoing',
            'request' => json_encode($request->all()), 
            'response' => $response->body(),
            'status' => $response->status(),
            'success' => $response->successful(),
            'message' => $response->successful() ? null : $response->body(),
            'milliseconds' => $milliseconds,
        ]);

        return $response;
    }
}
