<?php
use Illuminate\Support\Facades\Route;
use MUST\RRLogger\Http\Controllers\TestController;

Route::post('/test-endpoint', [TestController::class, 'handle']);
