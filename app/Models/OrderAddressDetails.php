<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddressDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'default_billing_id',
        'default_shiping_id',
        'f_name',
        'l_name',
        'email',
        'company',
        'phone',
        'street_address',
        'post_code',
        'suburb',
        'state',
        'city',
        'address_type',
    ];

    public function order()
    {
        return $this->belongsTo(order::class);
    }
}
