<?php
namespace MUST\RRLogger\Http\HttpClient;

use Illuminate\Support\Facades\Http as BaseHttp;
use MUST\RRLogger\Models\RRLogger;

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

        // Execute the HTTP request
        $response = $callback();

        // Calculate elapsed time
        $end = microtime(true);
        $milliseconds = round(($end - $start) * 1000);

        // Log request and response details
        RRLogger::create([
            'endpoint' => $response->effectiveUri(),
            'uri' => $response->effectiveUri(),
            'method' => $response->request()->method(),
            'ip_address' => request()->ip(),
            'request_type' => 'Outgoing',
            'request' => json_encode($response->request()->data()),
            'response' => $response->body(),
            'status' => $response->status(),
            'success' => $response->successful(),
            'message' => $response->successful() ? null : $response->body(),
            'milliseconds' => $milliseconds,
        ]);

        return $response;
    }
}
