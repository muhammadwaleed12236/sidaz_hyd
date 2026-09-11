<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'department_id',
        'unit_id',
        'type',
        'min_stock',
        'reorder_level',
        'description',
        'status',
    ];

    /**
     * Get the department that owns the raw material.
     */
    public function department()
    {
        return $this->belongsTo(\App\Models\Hr\Department::class, 'department_id');
    }

    /**
     * Get the unit associated with the raw material.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
