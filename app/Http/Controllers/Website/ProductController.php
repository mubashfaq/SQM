<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Slider;
use App\Models\CategorySlider;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public $website_title = 'Sydney Quality Meat';

    public function shop(Request $request)
    {
        $meta_details = [
            'title'       => 'Shop | My Website',
            'keywords'    => 'tools, genuine, hardware, makita, australia',
            'description' => 'Wholesale Hardware selling website',
            'author'      => 'Sydney Quality Meat',
        ];
    
        $sortOrder   = $request->input('sort', 'default');
        $categoryId  = $request->input('category_id', null);
        $searchTerm  = $request->input('search');
        $minPrice    = $request->input('min_price');
        $maxPrice    = $request->input('max_price');
    
        // Base Query
        $featuredproductsQuery = Product::query()
            ->with([
                'productMeta' => function ($q) {
                    $q->whereIn('option', ['_price', '_weight','_is_weight', '_stock_status']);
                },
                'discount'
            ])
            ->leftJoin('products_meta AS pm_price', function ($join) {
                $join->on('products.id', '=', 'pm_price.product_id')
                    ->where('pm_price.option', '_price');
            })
            ->where('products.active', 1)
            ->select('products.*'); // No alias here
    
        // Category Filter
        if ($categoryId) {
            $featuredproductsQuery->where('products.category_id', $categoryId);
        }
    
        // Multi-column Search
        if ($searchTerm) {
            $featuredproductsQuery->where(function ($q) use ($searchTerm) {
                $q->where('products.title', 'like', '%' . $searchTerm . '%');
              
            });
        }
    
        // Price Range Filter: cast pm_price.value in the WHERE
        if (!is_null($minPrice) && !is_null($maxPrice)) {
            $featuredproductsQuery->whereRaw(
                'CAST(pm_price.value AS DECIMAL(10,2)) BETWEEN ? AND ?',
                [(float) $minPrice, (float) $maxPrice]
            );
        }
    
        // Sorting by numeric price or title
        $featuredproductsQuery
            ->when($sortOrder === 'price-low', function ($q) {
                // Low to High
                $q->orderByRaw('CAST(pm_price.value AS DECIMAL(10,2)) ASC');
            })
            ->when($sortOrder === 'price-high', function ($q) {
                // High to Low
                $q->orderByRaw('CAST(pm_price.value AS DECIMAL(10,2)) DESC');
            })
            ->when($sortOrder === 'name-az', function ($q) {
                $q->orderBy('title', 'ASC');
            })
            ->when($sortOrder === 'name-za', function ($q) {
                $q->orderBy('title', 'DESC');
            });
    
        // Paginate
        $featuredproducts = $featuredproductsQuery->orderBy('id', 'desc')->paginate(9);

    
        // Apply discount calculation to each product
        $featuredproducts->getCollection()->transform(function ($product) {
            $discountData = $product->getDiscountDataAttribute();
            if ($discountData) {
                $product->discount_data = $discountData;
            }
            return $product;
        });
    
        // Check if no products found
        $noProductsFound = $featuredproducts->isEmpty();
    
        // Categories
        $categories = Category::orderBy('created_at', 'desc')
            ->with([
                'products' => function ($q) {
                    $q->where('active', 1)
                    ->with([
                        'productMeta' => function ($q2) {
                            $q2->whereIn('option', ['_price', '_weight', '_stock','_is_weight']);
                        },
                        'discount'
                    ]);
                }
            ])
            ->get();
// dd($featuredproducts);
        return view('Website.GeneralPages.shop', compact(
            'meta_details',
            'featuredproducts',
            'sortOrder',
            'categories',
            'noProductsFound'
        ));
    }

    

    public function product_detail($permalink){
        $meta_details = [
            'title'       => 'Shop | ' . $this->website_title,
            'keywords'    => 'tools, genuine, hardware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author'      => 'Sydney Quality Meat',
        ];

        $product=Product::with('productMeta','category')->where('permalink',$permalink)->first();
        if($product){
            return view('Website.GeneralPages.product-detail',compact('meta_details','product'));
        } else {
            return response()->view('Admin.Errors.404', [], 404);
        }
    }
} 
 

