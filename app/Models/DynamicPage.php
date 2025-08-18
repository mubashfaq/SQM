<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'permalink',
        'permalink_status',
        'hero_heading',
        'content',
        'active',
        'seo_title',
        'seo_tags',
        'seo_description'
    ];
}
