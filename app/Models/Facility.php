<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_ru',
        'title_kk',
        'title_en',
    ];

    /**
     * Relationship with HotelRooms (many-to-many)
     */
    public function rooms()
    {
        return $this->belongsToMany(HotelRoom::class, 'room_facilities', 'facility_id', 'room_id')
            ->withTimestamps();
    }
}
