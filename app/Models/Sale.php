<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'description',
        'qty',
        'total_price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
