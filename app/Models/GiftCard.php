<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'coupon_code',
        'variant',
        'usage',
        'start_date',
        'end_date',
        'discount',
        'price',
        'total_used',
        'status',
        'is_expirable',
        'expire_after_weeks',
        'created_by',
        'expired_by',
        'deleted_by',
        'expired_at',
        'deleted_at',
        'reason'
    ];

    public function purchased(){
        return $this->hasMany();
    }
}
