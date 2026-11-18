<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'platform_type',
        'tier',
        'authority_score',
        'base_url',
        'description',
        'is_active',
        'sync_interval_minutes',
        'last_synced_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'authority_score' => 'float',
        'sync_interval_minutes' => 'integer',
        'last_synced_at' => 'datetime',
    ];

    public function endpoints(): HasMany
    {
        return $this->hasMany(SourceEndpoint::class);
    }

    public function syncJobs(): HasMany
    {
        return $this->hasMany(SyncJob::class);
    }
}

