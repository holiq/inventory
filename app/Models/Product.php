<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function productStock()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function productTransactions()
    {
        return $this->hasMany(ProductTransaction::class);
    }
}
