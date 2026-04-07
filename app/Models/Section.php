<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $table = 'sections';

    protected $fillable = [
        'section_name',
        'description',
        'created_by',
        'user_id', // لو عايز تربطه بجدول users
    ];

    // علاقة Section بالـ User اللي أنشأه
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة Section بالمنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
