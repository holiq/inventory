<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'description',
        'qty',
        'total_price',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
