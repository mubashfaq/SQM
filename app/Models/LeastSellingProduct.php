<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeastSellingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'featured_image',
        'permalink',
        'quantity',
        'order_count'
    ];
}
