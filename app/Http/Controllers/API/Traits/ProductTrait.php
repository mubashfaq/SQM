<?php

namespace App\Http\Controllers\API\Traits;

use App\Models\order_meta;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ProductTrait
{
    use JsonResponseTrait, ProductFilterTrait;

    public function getProductBySku($model, $permalink)
    {
        $product = $model->where("permalink", $permalink)->first();
        return $this->success($product);
    }

    public function getLatestData($model, Request $request, $relations = [])
    {
        $latestProducts = $model instanceof \App\Models\Product || $model instanceof \App\Models\Brand
            ? $model->where('active', 1)->latest()
            : $model->latest();
        if (!empty($relations)) {
            foreach ($relations as $relation) {
                $latestProducts = $latestProducts->with($relation);
            }
        }
        if (!empty($request->all())) {
            $latestProducts = $this->getFiltered($latestProducts, $request );
            return $this->success($latestProducts);
        }
        return $this->withCount($latestProducts);
    }

    public function getFeatureProducts(Request $request)
    {
        $featureProducts = Product::where('active', 1)->
        whereHas('productMeta', function ($query) {
            $query->where('option', '_virtual')->where('value', 1);
        })->latest();
        if (!empty($request->all())) {
            $filteredProducts = $this->getFiltered($featureProducts, $request);
            return $this->success($filteredProducts);
        }
        return $this->withCount($featureProducts);
    }

    public function getDiscountProducts(Request $request)
    {
        $discountProduct = Product::where('active', 1)->whereNotNull('discount_id')->latest();
        if (!empty($request->all())) {
            $filteredProducts = $this->getFiltered($discountProduct, $request);
            return $this->success($filteredProducts);
        }
        return $this->withCount($discountProduct);
    }

    public function getProductsBySku($model, Request $request, $sku)
    {
        $products = $model->productsBySKU($sku);
        if (!empty($request->all())) {
            $filteredProducts = $this->getFiltered($products, $request);
            return $this->success($filteredProducts);
        }
        return $this->withCount($products);
    }

    public function getBestSellerProducts(Request $request)
    {
        $repetitionNumber = $request->input('repetition_number', 3);
        $bestSellerProductIds = order_meta::select('product_id')
            ->groupBy('product_id')
            ->having(\DB::raw('count(product_id)'), '>=', $repetitionNumber)
            ->pluck('product_id');
        if ($bestSellerProductIds->isEmpty()) {
            return $this->error('No best-selling products found',404);
        }
        $products = Product::whereIn('id', $bestSellerProductIds);
        if (empty($products)) {
            return $this->error('No best-selling products found',404);
        }
        return $this->withCount($products);
    }

    function transformSKU($sku) {
        $replacements = [
            '&' => 'and',
            '/' => 'forwardslash',
            '\\' => 'backslash',
            // Add more replacements as needed
        ];
        foreach ($replacements as $search => $replace) {
            $sku = str_replace($search, $replace, $sku);
        }
        $sku = preg_replace('/[^A-Za-z0-9\-]/', '', $sku);
        return $sku;
    }

//  





}
