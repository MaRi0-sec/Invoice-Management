<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'description',
        'section_id',
    ];

    // علاقة المنتج بالقسم
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // علاقة المنتج بالفواتير
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
