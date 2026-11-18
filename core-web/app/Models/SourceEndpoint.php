<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SourceEndpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'name',
        'endpoint_url',
        'parser_type',
        'scope',
        'selectors_config',
        'default_max_items',
        'is_active',
    ];

    protected $casts = [
        'selectors_config' => 'array',
        'default_max_items' => 'integer',
        'is_active' => 'boolean',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function rawContents(): HasMany
    {
        return $this->hasMany(RawContent::class);
    }

    public function syncJobs(): HasMany
    {
        return $this->hasMany(SyncJob::class, 'endpoint_id');
    }
}

