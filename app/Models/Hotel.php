<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
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
        'star',
        'email',
        'address_ru',
        'address_kk',
        'address_en',
        'website_ru',
        'website_kk',
        'website_en',
        'lat',
        'lon',
        'is_active',
        'is_partner',
    ];

    protected $casts = [
        'star' => 'integer',
        'is_active' => 'boolean',
        'is_partner' => 'boolean',
    ];

    /**
     * Relationship with City
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relationship with HotelRooms
     */
    public function rooms()
    {
        return $this->hasMany(HotelRoom::class);
    }
}
