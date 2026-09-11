<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagingMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'packaging_type',
        'variant',
        'unit_id',
        'department_id',
        'capacity',
        'capacity_unit_id',
        'min_stock',
        'description',
        'status',
    ];

    /**
     * Get the unit associated with the packaging material (stock keeping unit).
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get the department that owns the packaging material.
     */
    public function department()
    {
        return $this->belongsTo(\App\Models\Hr\Department::class, 'department_id');
    }

    /**
     * Get the unit associated with the capacity of the packaging material.
     */
    public function capacityUnit()
    {
        return $this->belongsTo(Unit::class, 'capacity_unit_id');
    }
}
