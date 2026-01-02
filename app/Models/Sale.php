<?php

namespace App\Models;

use App\Casts\Rupiah;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'description',
        'qty',
        'total_price',
    ];

    protected $casts = [
        'total_price' => Rupiah::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
