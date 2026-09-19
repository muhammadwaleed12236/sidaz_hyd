<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyInvoiceItem extends Model
{
    protected $fillable = [
        'dummy_invoice_id',
        'product_id',
        'product_name',
        'pack',
        'batch_no',
        'mfg_date',
        'exp_date',
        'qty',
        'mrp',
        'tp',
        'dp',
        'total_amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(DummyInvoice::class, 'dummy_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
