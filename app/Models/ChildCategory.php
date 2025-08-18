<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_parent_category',
        'child_title',
        'child_sku',
    ];

    protected $casts = [
        'main_parent_category' => 'integer',
    ];

    public function childCategoryMeta()
    {
        return $this->hasMany(ChildCategoryMeta::class);
    }

    public function mainParentCategory()
    {
        return $this->belongsTo(Category::class, 'main_parent_category', 'id');
    }
    


    // Define the relationship with grandchild Category
    public function grandChildCategories()
    {
        return $this->hasMany(GrandChildCategory::class, 'mid_parent_category', 'id');
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'child_category', 'id');
    }

  public function activeProducts()
    {
        return $this->hasMany(Product::class, 'child_category', 'id')->where('active',1)->whereNotNull('featured_image');
    }

    public function productsBySKU($sku)
    {
        $childCategory = $this->where('child_sku', $sku)->first();
        if($childCategory){
            return $childCategory->activeProducts;
        }
    }

}
