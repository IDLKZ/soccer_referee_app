<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryOperation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_ru',
        'title_kk',
        'title_en',
        'value',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with Operations
     */
    public function operations()
    {
        return $this->hasMany(Operation::class, 'category_id');
    }
}
