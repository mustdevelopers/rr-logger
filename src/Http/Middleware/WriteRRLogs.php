<?php

namespace MUST\RRLogger\Http\Middleware;

use Closure;
use MUST\RRLogger\Models\RRLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WriteRRLogs
{
    public function handle(Request $request, Closure $next)
    {
        if (!defined('LARAVEL_START')) define('LARAVEL_START', microtime(true));

        // Log request start
        Log::info('Handling request', ['uri' => $request->getRequestUri()]);

        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        RRLogger::create([
            'endpoint' => $request->route() ? $request->route()->uri : '',
            'uri' => $request->getRequestUri(),
            'user_type' => $request->user() ? get_class($request->user()) : null,
            'user_id' => auth()?->id(),
            'method' => $request->method(),
            'request_type' => 'Incoming',
            'ip_address' => $request->ip(),
            'request' => json_encode($request->except($this->getHiddenFields())),
            'content' => $request->getContent(),
            'response' => method_exists($response, 'content') ? $response->content() : null,
            'milliseconds' => $this->getTurnAroundTime(),
            'status' => method_exists($response, 'status') ? $response->status() : null,
            'success' => $response->isSuccessful(),
        ]);
    }

    private function getHiddenFields(): array
    {
        return explode(',', config('rrlogger.hidden_fields'));
    }

    private function getTurnAroundTime(): float|int
    {
        return round(round(microtime(true) - LARAVEL_START, 4) * 1000);
    }
}
