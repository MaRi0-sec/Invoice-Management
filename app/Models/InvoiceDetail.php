<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    // use HasFactory;

    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product',
        'section',
        'amount_paid',
        'total_with_value_vat',
        'remaining_amount',
        'status',
        'value_status',
        'note',
        'user',
        'payment_date',
    ];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withTrashed();
    }
}
