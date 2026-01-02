<?php

namespace App\Models;

use App\Casts\Rupiah;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'description',
        'qty',
        'total_price',
    ];

    protected $casts = [
        'total_price' => Rupiah::class,
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
