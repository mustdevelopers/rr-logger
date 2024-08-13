<?php

namespace MUST\RRLogger\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use MUST\RRLogger\Http\HttpClient\RRLoggerHttpClient;
use MUST\RRLogger\Http\Middleware\WriteRRLogs;

class RRLoggerServiceProvider extends ServiceProvider
{
    public function boot(Kernel $kernel)
    {
        if ($this->app->runningInConsole()) {
            if (!class_exists('CreateRRLoggersTable')) {
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_rrloggers_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_rrloggers_table.php'),
                ], 'migrations');
            }

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('rrlogger.php'),
            ], 'config');
        }

        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');

        // Register the custom HTTP client
        $this->app->singleton('http', function ($app) {
            return RRLoggerHttpClient::class;
        });

        $kernel->appendMiddlewareToGroup('api', WriteRRLogs::class); // Add it after all other middlewares
    }
}
