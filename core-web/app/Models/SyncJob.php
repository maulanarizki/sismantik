<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyncJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_uuid',
        'source_id',
        'endpoint_id',
        'status',
        'triggered_by',
        'start_date',
        'end_date',
        'max_items_limit',
        'items_scraped',
        'items_processed',
        'log_messages',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'max_items_limit' => 'integer',
        'items_scraped' => 'integer',
        'items_processed' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(SourceEndpoint::class, 'endpoint_id');
    }

    public function rawContents(): HasMany
    {
        return $this->hasMany(RawContent::class);
    }
}

