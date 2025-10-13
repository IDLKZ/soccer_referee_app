<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stadium extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_url',
        'city_id',
        'title_ru',
        'title_kk',
        'title_en',
        'description_ru',
        'description_kk',
        'description_en',
        'address_ru',
        'address_kk',
        'address_en',
        'lat',
        'lon',
        'built_date',
        'phone',
        'website',
        'is_active',
    ];

    protected $casts = [
        'built_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with City
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relationship with Clubs (many-to-many)
     */
    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_stadiums', 'stadium_id', 'club_id')
            ->withTimestamps();
    }
}
