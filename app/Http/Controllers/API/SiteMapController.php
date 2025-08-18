<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use Illuminate\Http\Request;


class SiteMapController extends Controller
{

  

    public function generateSitemapApi($returnType = 'json')
    {
        $data = array();
        // Generate Product URLs
        $productUrls = Product::where('active', 1)
            ->whereNotNull('featured_image')
            ->get()
            ->map(function ($product) {
                return [
                    'url' => urldecode($product->permalink),
                    'lastModified' => optional($product->updated_at)->toIso8601String() ?? now()->toIso8601String(),
                    'priority' => 0.8
                ];
            });

        // Generate Category URLs with SKUs
        $categoryUrls = Category::get()
            ->map(function ($category) {
                return [
                    'url' => 'shop?category=' . $category->sku,
                    'lastModified' => optional($category->updated_at)->toIso8601String() ?? now()->toIso8601String(),
                    'priority' => 0.7
                ];
            });

        // Generate Child Category URLs
        $childCategoryUrls = ChildCategory::get()
            ->map(function ($childCategory) {
                return [
                    'url' => 'shop?child_category=' . $childCategory->child_sku,
                    'lastModified' => optional($childCategory->updated_at)->toIso8601String() ?? now()->toIso8601String(),
                    'priority' => 0.6
                ];
            });

        // Generate Brand URLs
        $brandUrls = Brand::where('active', 1)
            ->get()
            ->map(function ($brand) {
                return [
                    'url' => 'brand?brand=' . $brand->permalink,
                    'lastModified' => optional($brand->updated_at)->toIso8601String() ?? now()->toIso8601String(),
                    'priority' => 0.5
                ];
            });



        $staticPagesUrls = collect(config('sitemap.static_pages'))
            ->map(function ($pageConfig, $url) {
                return [
                    'url' => $url,
                    'lastModified' => now()->toIso8601String(),
                    'priority' => $pageConfig['priority']
                ];
            })->values();

        $data = [
            'Product' => $productUrls,
            'Category' => $categoryUrls,
            'Child Categories' => $childCategoryUrls,
            'Brand' => $brandUrls,
            'Other Pages' => $staticPagesUrls
        ];

        return $data;
    }
}
