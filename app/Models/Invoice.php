<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    // use HasFactory;
    const STATUS_PAID = 1;
    const STATUS_UNPAID = 2;
    const STATUS_PARTIAL = 3;

    use SoftDeletes;

    // حدد اسم الجدول بالجمع اللي في الـ database
    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'user_id',
        'product_id',
        'section_id',
        'amount_collection',
        'amount_commission',
        'discount',
        'value_vat',
        'rate_vat',
        'total',
        'status',
        'value_status',
        'note',
        'payment_date',
        'total_with_value_vat',
        'remaining_amount',
        'amount_paid',
    ];


    protected static function booted()
    {
        static::created(function ($invoice) {
            $invoice->user->increment('invoices_count');      // كل ميحصل اضافة تزود واحد
        });
        static::deleted(function ($invoice) {
            $invoice->user->decrement('invoices_count');     // كل ميحصل حذف تنقص واحد
        });
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    public function invoiceAttachments()
    {
        return $this->hasMany(InvoiceAttachment::class, 'invoice_id');
    }
}
