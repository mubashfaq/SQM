<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'f_name', 'l_name', 'email', 'company', 'phone', 'street_address', 'city', 'post_code', 'suburb', 'state', 'address_type'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }
}
