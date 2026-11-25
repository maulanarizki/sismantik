<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stopword extends Model
{
    use HasFactory;

    protected $table = 'stopwords';

    protected $fillable = [
        'word',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public const CATEGORIES = [
        'daerah' => 'Daerah & Wilayah',
        'waktu' => 'Waktu & Kalender',
        'angka' => 'Angka & Nominal',
        'sapaan_gelar' => 'Sapaan & Gelar',
        'aksi_umum' => 'Aksi & Kerja Umum',
        'umum' => 'Stopword Umum / Partikel',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}

