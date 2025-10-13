<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgeCity extends Model
{
    protected $fillable = [
        'user_id',
        'city_id',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with City
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
