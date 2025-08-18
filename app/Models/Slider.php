<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'brand_id',
        'slider_image',
        'created_by',
        'active',
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
