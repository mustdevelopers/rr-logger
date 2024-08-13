<?php

namespace MUST\RRLogger\Http\Middleware;

use Closure;
use MUST\RRLogger\Models\RRLogger;
use Illuminate\Http\Request;

class WriteRRLogs
{
    public function handle(Request $request, Closure $next)
    {
        if (!defined('LARAVEL_START')) define('LARAVEL_START', microtime(true));

        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        $maxContentLength = config('rrlogger.max_content_length');

        dd(config('rrlogger.hidden_fields'));

        // Truncate request and response content if it exceeds the maximum length
        $requestContent = $this->truncateContent($request->getContent(), $maxContentLength);
        $responseContent = method_exists($response, 'content') ? $this->truncateContent($response->content(), $maxContentLength) : null;

        RRLogger::create([
            'endpoint' => $request->route() ? $request->route()->uri : '',
            'uri' => $request->getRequestUri(),
            'user_type' => $request->user() ? get_class($request->user()) : null,
            'user_id' => auth()?->id(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'request' => json_encode($request->except($this->getHiddenFields())),
            'content' => $requestContent,
            'response' => $responseContent,
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

    private function truncateContent(string $content, int $maxLength): string
    {
        return strlen($content) > $maxLength ? substr($content, 0, $maxLength) : $content;
    }
}
