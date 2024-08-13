<?php
use Illuminate\Support\Facades\Route;
use MUST\RRLogger\Http\Controllers\TestController;
use MUST\RRLogger\Http\Middleware\WriteRRLogs;

Route::post('/test-endpoint', [TestController::class, 'handle'])->middleware(WriteRRLogs::class);
