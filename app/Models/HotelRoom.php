<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'image_url',
        'title_ru',
        'title_kk',
        'title_en',
        'description_ru',
        'description_kk',
        'description_en',
        'bed_quantity',
        'room_size',
        'air_conditioning',
        'private_bathroom',
        'tv',
        'wifi',
        'smoking_allowed',
    ];

    protected $casts = [
        'image_url' => 'array',
        'bed_quantity' => 'integer',
        'room_size' => 'decimal:2',
        'air_conditioning' => 'boolean',
        'private_bathroom' => 'boolean',
        'tv' => 'boolean',
        'wifi' => 'boolean',
        'smoking_allowed' => 'boolean',
    ];

    /**
     * Relationship with Hotel
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Relationship with Facilities (many-to-many)
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facilities', 'room_id', 'facility_id')
            ->withTimestamps();
    }
}
