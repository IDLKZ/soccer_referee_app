<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_ru',
        'title_kk',
        'title_en',
        'description_ru',
        'description_kk',
        'description_en',
        'value',
        'is_administrative',
        'is_active',
        'can_register',
        'is_system',
    ];

    protected $casts = [
        'is_administrative' => 'boolean',
        'is_active' => 'boolean',
        'can_register' => 'boolean',
        'is_system' => 'boolean',
    ];
}
