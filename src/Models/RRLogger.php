<?php

namespace MUST\RRLogger\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use MUST\RRLogger\Database\Factories\RRLoggerFactory;

class RRLogger extends Model
{
    use HasFactory, Prunable;

    protected $guarded = [];
    protected $table = 'rrloggers';

    protected static function newFactory()
    {
        return RRLoggerFactory::new();
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(config('rrloger.retention_days')));
    }
}