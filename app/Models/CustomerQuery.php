<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerQuery extends Model
{
    use HasFactory;
    protected $fillable = [
        'f_name',
        'l_name',
        'c_email',
        'c_phone',
        'c_state',
        'c_message',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
