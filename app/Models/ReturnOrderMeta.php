<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrderMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id', 'product_id', 'quantity', 'price', 'subTotal'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
