<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyInvoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'order_date',
        'customer_name',
        'customer_address',
        'gate_pass_no',
        'licence_no',
        'licence_expiry',
        'total_amount',
        'warranty_text',
        'signatory_name',
        'signatory_title',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(DummyInvoiceItem::class, 'dummy_invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNextInvoiceNo()
    {
        $dateStr = date('mY'); // e.g. 092026
        $prefix = 'SP01507' . $dateStr;
        
        $latest = self::where('invoice_no', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $numStr = substr($latest->invoice_no, strlen($prefix));
            $nextNum = intval($numStr) + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}
