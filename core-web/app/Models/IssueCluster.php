<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueCluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'cluster_uuid',
        'title',
        'framing_category',
        'lifecycle_stage',
        'total_mentions',
        'net_sentiment_score',
        'confidence_lower',
        'confidence_upper',
        'is_high_alert',
        'first_detected_at',
        'last_active_at',
    ];

    protected $casts = [
        'net_sentiment_score' => 'float',
        'confidence_lower' => 'float',
        'confidence_upper' => 'float',
        'is_high_alert' => 'boolean',
        'first_detected_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];
}

