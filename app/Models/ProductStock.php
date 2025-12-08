<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $primaryKey = 'product_id';

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'qty_in',
        'qty_out',
        'current_stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
