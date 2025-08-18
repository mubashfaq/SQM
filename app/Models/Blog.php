<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
      protected $fillable = [
        'title',
        'permalink',
        'description',
        'content',
        'cover_image',
        'featured_images',
        '_seo_title',
        '_seo_tags',
        '_seo_description',
        '_canonical_url',
        '_schema_markup',
        'active',
        'created_by',
        'updated_by',
        'fimage_alt_text',
        '_page_index',
    ];

    protected $casts = [
        'active' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    // Accessor for featured images array
    public function getFeaturedImagesArrayAttribute()
    {
        if (empty($this->featured_images)) {
            return [];
        }
        return array_filter(explode(',', $this->featured_images));
    }

    // Mutator for featured images
    public function setFeaturedImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['featured_images'] = implode(',', array_filter($value));
        } else {
            $this->attributes['featured_images'] = $value;
        }
    }

    // Many-to-many relationships for categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_categories', 'blog_id', 'category_id')
            ->withTimestamps();
    }

    public function childCategories()
    {
        return $this->belongsToMany(ChildCategory::class, 'blog_child_categories', 'blog_id', 'child_category_id')
            ->withTimestamps();
    }

    public function grandChildCategories()
    {
        return $this->belongsToMany(GrandChildCategory::class, 'blog_grand_child_categories', 'blog_id', 'grand_child_category_id')
            ->withTimestamps();
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'blog_brands', 'blog_id', 'brand_id')
            ->withTimestamps();
    }

    // Helper methods to get IDs
    public function getCategoryIdsAttribute()
    {
        return $this->categories->pluck('id')->toArray();
    }

    public function getChildCategoryIdsAttribute()
    {
        return $this->childCategories->pluck('id')->toArray();
    }

    public function getGrandChildCategoryIdsAttribute()
    {
        return $this->grandChildCategories->pluck('id')->toArray();
    }

    public function getBrandIdsAttribute()
    {
        return $this->brands->pluck('id')->toArray();
    }

    public function getBlogwithPermalink($permalink)
    {
        $blog = $this->with(['categories', 'childCategories', 'grandChildCategories', 'brands'])->where('permalink', $permalink)->first();
        if ($blog) {
            return $blog;
        }
        return null; // or handle inactive blog case
    }

    // Helper methods
    public function isGeneral()
    {
        return $this->categories->isEmpty() &&
            $this->childCategories->isEmpty() &&
            $this->grandChildCategories->isEmpty() &&
            $this->brands->isEmpty();
    }

    public function getStatusText()
    {
        $statuses = [
            0 => 'Draft',
            1 => 'Public',
            2 => 'Private',
            3 => 'Trash'
        ];
        return $statuses[$this->active] ?? 'Unknown';
    }

    public function getStatusBadge()
    {
        $badges = [
            0 => '<span class="badge bg-secondary">Draft</span>',
            1 => '<span class="badge bg-success">Public</span>',
            2 => '<span class="badge bg-warning">Private</span>',
            3 => '<span class="badge bg-danger">Trash</span>'
        ];
        return $badges[$this->active] ?? '<span class="badge bg-dark">Unknown</span>';
    }

    // Creator relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
