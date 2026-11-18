<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntelligenceBrief extends Model
{
    use HasFactory;

    protected $fillable = [
        'brief_date',
        'threat_level',
        'executive_summary',
        'key_actors',
        'actionable_recommendations',
        'draft_press_release',
        'generated_by',
    ];

    protected $casts = [
        'brief_date' => 'date',
        'key_actors' => 'array',
        'actionable_recommendations' => 'array',
    ];
}

