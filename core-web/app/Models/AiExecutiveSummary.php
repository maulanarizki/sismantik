<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiExecutiveSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_date',
        'key_highlights',
        'root_causes',
        'recommended_actions',
        'raw_summary_text',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'key_highlights' => 'array',
        'root_causes' => 'array',
        'recommended_actions' => 'array',
    ];
}

