<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActorNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'username',
        'display_name',
        'account_type',
        'degree_centrality',
        'betweenness_centrality',
        'total_interactions',
        'bot_probability',
    ];

    protected $casts = [
        'degree_centrality' => 'float',
        'betweenness_centrality' => 'float',
        'bot_probability' => 'float',
        'total_interactions' => 'integer',
    ];
}

