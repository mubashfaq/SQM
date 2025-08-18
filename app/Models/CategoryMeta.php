<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMeta extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'option', 'value'];
    public $timestamps = false;

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
