<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'user_id',
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
        'status',
        'subTotal',
        'totalGst',
        'total_amount',
        'auth_discount_per',
        'auth_discount',
        'amount_paid_by_coupon',
        'coupon_id',
        'grand_total',
        'discountedAmount',
        'discountPlan',
        'channel',
        'payment_status',
        'payment_method',
        'delivery_charges',
        'order_notes',
        'delivered_price',
        'admin_delete',
        's_f_name',
        's_l_name',
        's_email',
        's_company',
        's_phone',
        's_street_address',
        's_post_code',
        's_suburb',
        's_state',
        's_city',
        'stripe_payment_method_id',
        'stripe_customer_id',
        'delivery_method',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            # Find the last invoice if it exists
            $lastOrder = self::orderBy('id', 'desc')->first();

            if ($lastOrder) {
                # Increment the invoice number
                $invoice->invoice_number = 'INV-OS-0' . ($lastOrder->id + 1);
            } else {
                # If no previous invoices, start with 'IN-OS-01'
                $invoice->invoice_number = 'INV-OS-01';
            }
        });
    }

    protected $with = ['orderMeta'];
    public function orderMeta()
    {
        return $this->hasMany(order_meta::class, 'order_id');
    }

    function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function orderMetas()
    {
        return $this->hasMany(order_meta::class);
    }

    public function orderAddressDetail()
    {
        return $this->hasOne(OrderAddressDetails::class, 'order_id', 'id');
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'address_id', 'id');
    }

    public function returnedOrder(){
        return $this->hasOne(ReturnOrder::class, 'order_id', 'id')->where('admin_delete', 0);
    }
}
