<?php

namespace MUST\RRLogger\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MUST\RRLogger\Models\RRLogger;

class RRLoggerFactory extends Factory
{
    protected $model = RRLogger::class;

    public function definition()
    {
        $startTime = microtime(true) * 1000;
        return [
            'endpoint' => fake()->slug(),
            'uri' => fake()->slug(),
            'method' => fake()->randomElement(['post', 'get']),
            'milliseconds' => microtime(true) * 1000 - $startTime,
            'status' => 200,
            'success' => true
        ];
    }

}
