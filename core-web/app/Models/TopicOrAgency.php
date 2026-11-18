<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopicOrAgency extends Model
{
    use HasFactory;

    protected $table = 'topics_or_agencies';

    protected $fillable = [
        'name',
        'code',
        'category',
        'scope',
        'keywords',
        'aliases',
        'is_active',
    ];

    protected $casts = [
        'keywords' => 'array',
        'aliases' => 'array',
        'is_active' => 'boolean',
    ];

    public function aspectSentiments(): HasMany
    {
        return $this->hasMany(AspectSentiment::class, 'agency_id');
    }

    public function alertRules(): HasMany
    {
        return $this->hasMany(AlertRule::class, 'topic_id');
    }
}

