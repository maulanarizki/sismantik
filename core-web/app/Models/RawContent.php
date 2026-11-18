<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RawContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_endpoint_id',
        'sync_job_id',
        'external_id',
        'author',
        'title',
        'content_text',
        'clean_text',
        'url',
        'content_hash',
        'minhash_signature',
        'is_duplicate',
        'scope',
        'parent_id',
        'published_at',
        'engagement_metrics',
        'is_analyzed',
        'is_suspected_bot',
        'bot_suspicion_reason',
    ];

    protected $casts = [
        'is_duplicate' => 'boolean',
        'is_analyzed' => 'boolean',
        'is_suspected_bot' => 'boolean',
        'published_at' => 'datetime',
        'engagement_metrics' => 'array',
    ];

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(SourceEndpoint::class, 'source_endpoint_id');
    }

    public function syncJob(): BelongsTo
    {
        return $this->belongsTo(SyncJob::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(RawContent::class, 'parent_id');
    }

    public function duplicates(): HasMany
    {
        return $this->hasMany(RawContent::class, 'parent_id');
    }

    public function sentimentResult(): HasOne
    {
        return $this->hasOne(SentimentResult::class);
    }

    public function geoExtractions(): HasMany
    {
        return $this->hasMany(GeoExtraction::class);
    }

    public function aspectSentiments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(AspectSentiment::class, SentimentResult::class);
    }
}

