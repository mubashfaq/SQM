<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardPurchases extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id', 'coupon_code', 'customer_type', 'customer_name', 'customer_email', 'customer_phone', 'business_name', 'street_address', 'suburb', 'city', 'state', 'post_code', 'payment_status' , 'payment_method', 'payment_intent', 'total_amount', 'purchasing_date', 'remaining_amount', 'expiry_date', 'is_used', 'is_expired', 'used_times'
    ];

    public function card(){
        return $this->hasOne(GiftCard::class, 'id', 'card_id');
    }
}
