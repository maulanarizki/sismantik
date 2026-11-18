<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'topic_id',
        'metric_type',
        'threshold_value',
        'window_hours',
        'notification_target',
        'is_active',
    ];

    protected $casts = [
        'threshold_value' => 'float',
        'window_hours' => 'integer',
        'is_active' => 'boolean',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(TopicOrAgency::class, 'topic_id');
    }
}

