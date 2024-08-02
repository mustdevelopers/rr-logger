<?php
namespace MUST\RRLogger\Http\Middleware;

use Closure;
use MUST\RRLogger\Models\RRLogger;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $end = microtime(true);
        
        $log = new RRLogger();
        $log->user_type = auth()->check() ? auth()->user()->type : null;
        $log->user_id = auth()->check() ? auth()->id() : null;
        $log->endpoint = $request->route() ? $request->route()->getName() : null;
        $log->uri = $request->getRequestUri();
        $log->method = $request->method();
        $log->ip_address = $request->ip();
        $log->content = $request->getContent();
        $log->request = json_encode($request->all());
        $log->response = json_encode($response->getContent());
        $log->milliseconds = (int)(($end - $start) * 1000);
        $log->status = $response->getStatusCode();
        $log->success = $response->isSuccessful();
        $log->message = $response->getContent();
        $log->save();
        
        return $response;
    }
}
