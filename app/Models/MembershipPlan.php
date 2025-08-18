<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'discount_percentage',
        'duration',
        'price',
        'badge_text',
        'description',
        'membership_type',
        'created_by',
        'active',
        'signup'
    ];


    public function subscriptions()
    {
        return $this->hasMany(MembershipSubscription::class, 'plan_id', 'id');
    }
}
