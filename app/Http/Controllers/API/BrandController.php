<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Slider;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Brand $brand , Request $request)
    {
        return $this->getLatestData($brand , $request);
    }

    public function getProductsByBrand(Brand $brand , Request $request,$sku)
    {
        return $this->getProductsBySku($brand, $request,$sku);
    }
    public function getBrandSliders(Slider $slider, Request $request)
    {
        return $this->getLatestData($slider, $request);
    }

}
