<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
