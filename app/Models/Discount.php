<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'discount_type',
        'discount_value',
        'limited',
        'from_date',
        'end_date',
        'badge',
        'badge_text',
        'badge_color',
        'badge_image',
        'active',
        'created_by',
        'discount_for',
    ];
}
