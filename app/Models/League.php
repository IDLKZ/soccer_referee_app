<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_url',
        'country_id',
        'title_ru',
        'title_kk',
        'title_en',
        'description_ru',
        'description_kk',
        'description_en',
        'value',
        'level',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with Country
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
