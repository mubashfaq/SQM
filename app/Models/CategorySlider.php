<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slider_image',
        'created_by',
        'category_id', // Corrected typo here
        'slide_number',
        'active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

