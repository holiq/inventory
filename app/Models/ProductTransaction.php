<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'qty_in',
        'qty_out',
        'type',
        'price',
        'total_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
