<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLetterSubscription extends Model
{
    protected $fillable = [
        'user_id', 'email', 'state', 'business_type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($subscription) {
            if (!isset($subscription->user_id)) {
                $subscription->user_id = 0;
            }
        });
    }
}
