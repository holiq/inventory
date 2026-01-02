<?php

namespace App\Models;

use App\Casts\Rupiah;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'qty',
        'price',
        'total_price',
    ];

    protected $casts = [
        'price' => Rupiah::class,
        'total_price' => Rupiah::class,
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
