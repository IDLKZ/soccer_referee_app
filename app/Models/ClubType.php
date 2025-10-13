<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClubType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_url',
        'title_ru',
        'title_kk',
        'title_en',
        'value',
        'level',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
    ];
}
