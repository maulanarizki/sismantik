<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeoExtraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'raw_content_id',
        'district_name',
        'subdistrict_name',
        'latitude',
        'longitude',
        'match_confidence',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'match_confidence' => 'float',
    ];

    public function rawContent(): BelongsTo
    {
        return $this->belongsTo(RawContent::class);
    }
}

