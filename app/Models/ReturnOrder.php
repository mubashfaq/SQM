<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id', 'user_id', 'gift_card_id', 'user_email', 'return_type', 'return_via', 'grandTotal'
    ];

    public function returnMeta(){
        return $this->hasMany(ReturnOrderMeta::class, 'return_id', 'id');
    }

    public function order(){
        return $this->hasOne(order::class, 'id', 'order_id');
    }
}
