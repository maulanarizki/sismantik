<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AspectSentiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sentiment_result_id',
        'agency_id',
        'aspect_keyword',
        'aspect_sentiment_label',
        'aspect_score',
    ];

    protected $casts = [
        'aspect_score' => 'float',
    ];

    public function sentimentResult(): BelongsTo
    {
        return $this->belongsTo(SentimentResult::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(TopicOrAgency::class, 'agency_id');
    }
}

