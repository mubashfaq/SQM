<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_meta extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'product_title',
        'quantity',
        'delivered_weight',
        'price',
        'qp_subtotal',
        'payment_intent_id',
    ];

    public function order()
    {
        return $this->belongsTo(order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
