<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JudgeType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_ru',
        'title_kk',
        'title_en',
        'value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
