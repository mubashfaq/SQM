<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrandChildCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'mid_parent_category',
        'grand_title',
        'grand_sku',
    ];

    // Define the relationship with the mid parent Category
    public function midParentCategory()
    {
        return $this->belongsTo(ChildCategory::class, 'mid_parent_Category', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'grand_child_category', 'id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'grand_child_category', 'id')->where('active',1);
    }
    public function productsBySKU($sku)
    {
        $grandChildCategory = $this->where('grand_sku', $sku)->first();
        if($grandChildCategory){
            return $grandChildCategory->activeProducts;
        }
    }

}
