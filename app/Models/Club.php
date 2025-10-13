<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Club extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_url',
        'parent_id',
        'city_id',
        'type_id',
        'short_name_ru',
        'short_name_kk',
        'short_name_en',
        'full_name_ru',
        'full_name_kk',
        'full_name_en',
        'description_ru',
        'description_kk',
        'description_en',
        'bin',
        'foundation_date',
        'address_ru',
        'address_kk',
        'address_en',
        'phone',
        'website',
        'is_active',
    ];

    protected $casts = [
        'phone' => 'array',
        'foundation_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with parent club
     */
    public function parent()
    {
        return $this->belongsTo(Club::class, 'parent_id');
    }

    /**
     * Relationship with child clubs
     */
    public function children()
    {
        return $this->hasMany(Club::class, 'parent_id');
    }

    /**
     * Relationship with City
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relationship with ClubType
     */
    public function type()
    {
        return $this->belongsTo(ClubType::class, 'type_id');
    }

    /**
     * Relationship with Stadiums (many-to-many)
     */
    public function stadiums()
    {
        return $this->belongsToMany(Stadium::class, 'club_stadiums', 'club_id', 'stadium_id')
            ->withTimestamps();
    }
}
