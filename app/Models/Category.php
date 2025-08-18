<?php

namespace App\Models;
use App\Models\ChildCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'sku',
        'thumbnail_image',
        'cover_image',
    ];


    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class, 'main_parent_category', 'id');
    }

    public function categoryMeta()
    {
        return $this->hasMany(CategoryMeta::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
      public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('active', 1)->whereNotNull('featured_image');
    }

    public function productsBySKU($sku)
    {
        $category = $this->where('sku', $sku)->first();
        if ($category) {
            return $category->activeProducts;
        }
    }

    public function grandChildCategories()
    {
        return $this->hasManyThrough(
            GrandChildCategory::class,
            ChildCategory::class,
            'main_parent_category',
            'mid_parent_category',
            'id',
            'id'
        );
    }
}
