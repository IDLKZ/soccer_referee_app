<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'category_id',
        'title_ru',
        'title_kk',
        'title_en',
        'description_ru',
        'description_kk',
        'description_en',
        'value',
        'responsible_roles',
        'is_first',
        'is_last',
        'can_reject',
        'is_active',
        'result',
        'previous_id',
        'next_id',
        'on_reject_id',
    ];

    protected $casts = [
        'responsible_roles' => 'array',
        'is_first' => 'boolean',
        'is_last' => 'boolean',
        'can_reject' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with CategoryOperation
     */
    public function category()
    {
        return $this->belongsTo(CategoryOperation::class, 'category_id');
    }

    /**
     * Relationship with previous Operation
     */
    public function previousOperation()
    {
        return $this->belongsTo(Operation::class, 'previous_id');
    }

    /**
     * Relationship with next Operation
     */
    public function nextOperation()
    {
        return $this->belongsTo(Operation::class, 'next_id');
    }

    /**
     * Relationship with on_reject Operation
     */
    public function onRejectOperation()
    {
        return $this->belongsTo(Operation::class, 'on_reject_id');
    }
}
