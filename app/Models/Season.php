<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_ru',
        'title_kk',
        'title_en',
        'value',
        'start_at',
        'end_at',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'is_active' => 'boolean',
    ];
}
