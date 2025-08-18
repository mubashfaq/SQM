<?php

namespace App\Http\Controllers\API;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ChildCategory;
use App\Models\CategorySlider;
use App\Models\GrandChildCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB as FacadesDB;

class CategoryController extends Controller
{
    public function index(Category $category, Request $request)
    {

        return $this->getLatestData($category, $request, ['childCategories.grandChildCategories']);
    }

    public function getProductsByCategory( Category $category, Request $request,$sku)
    {
        return $this->getProductsBySku($category, $request,$sku);
    }

    public function childCategoryProducts(ChildCategory $childCategory , Request $request,$sku)
    {
        return $this->getProductsBySku($childCategory, $request,$sku);
    }

    public function grandchildCategoryProducts(GrandChildCategory $grandChildCategory , Request $request,$sku)
    {
        return $this->getProductsBySku($grandChildCategory, $request,$sku);
    }
    public function getCategorySliders(CategorySlider $category, Request $request)
    {
        return $this->getLatestData($category, $request);
    }

}
