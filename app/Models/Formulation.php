<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Hr\Department::class, 'department_id');
    }

    public function batchUnit()
    {
        return $this->belongsTo(Unit::class, 'batch_unit_id');
    }

    public function rawMaterials()
    {
        return $this->hasMany(FormulationRawMaterial::class);
    }

    public function packagingMaterials()
    {
        return $this->hasMany(FormulationPackagingMaterial::class);
    }
}
