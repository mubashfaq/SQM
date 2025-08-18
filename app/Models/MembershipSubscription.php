<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'customer_id',
        'starting_date',
        'ending_date',
        'subscription_id',
        'expired',
        'created_by',
        ];

        public function plan()
        {
            return $this->belongsTo(MembershipPlan::class, 'plan_id', 'id');
        }

        public function user()
        {
            return $this->belongsTo(User::class, 'customer_id', 'id'); // Linking customer_id to the users table
        }
}
