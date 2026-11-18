<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SentimentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'raw_content_id',
        'sentiment_label',
        'sentiment_score',
        'confidence_score',
        'emotion_label',
        'emotion_scores',
        'framing_category',
        'analyzed_by_model',
    ];

    protected $casts = [
        'sentiment_score' => 'float',
        'confidence_score' => 'float',
        'emotion_scores' => 'array',
    ];

    public function rawContent(): BelongsTo
    {
        return $this->belongsTo(RawContent::class);
    }

    public function aspectSentiments(): HasMany
    {
        return $this->hasMany(AspectSentiment::class);
    }
}

