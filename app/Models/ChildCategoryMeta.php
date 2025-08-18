<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategoryMeta extends Model
{
    use HasFactory;
    protected $fillable = ['child_category_id', 'option', 'value'];
    public $timestamps = false;

    public function ChildCategory()
    {
        return $this->belongsTo(ChildCategory::class);
    }
}
