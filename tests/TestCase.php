<?php

namespace MUST\RRLogger\Tests;

use CreateRRLoggersTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MUST\RRLogger\Http\Kernel;
use MUST\RRLogger\Providers\RRLoggerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        $this->loadPackageRoutes();
    }

    protected function getPackageProviders($app): array
    {
        return [
            RRLoggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_rrloggers_table.php';

        // run the up() method of that migration class
        (new CreateRRLoggersTable())->up();
    }

    protected function loadPackageRoutes()
    {
        $this->app['router']->group([
            'namespace' => 'MUST\RRLogger\Http\Controllers',
            'prefix' => 'api',
        ], function () {
            require __DIR__.'/../routes/api.php';
        });
    }
}