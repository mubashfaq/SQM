<?php

namespace App\General;

use DB;

class ProductClass
{
    public static function getOptionValue($option, $items)
    {
        foreach ($items as $item) {
            if ($item->option === $option) {
                return $item->value;
            }
        }

        return ''; // Return null if option is not found
    }
   




    public static function getCategoryTitles($categories, $string)
    {
        $categoryIds = explode(',', $string);
        $categoryTitles = [];

        foreach ($categories as $category) {
            if (in_array($category['id'], $categoryIds)) {
                $categoryTitles[] = $category['title'];
            }
        }

        return implode(', ', $categoryTitles);
    }
    public static function getCategoryTitlesinfo($categories, $string)
    {
        $categoryIds = explode(',', $string);
        $categoryTitles = [];
    
        foreach ($categories as $category) {
            if (in_array($category['id'], $categoryIds)) {
                $categoryTitles[] = [
                    'title' => $category['title'],
                    'sku' => $category['sku'] // Assuming you have an 'sku' key in the category array
                ];
            }
        }
    
        return $categoryTitles;
    }
    
    


    public static function getproducttype($producttypes, $string)
    {
        $producttypeIds = explode(',', $string);
        $producttypeTitles = [];

        foreach ($producttypes as $producttype) {
            if (in_array($producttype['id'], $producttypeIds)) {
                $producttypeTitles[] = $producttype['title'];
            }
        }

        return implode(', ', $producttypeTitles);
    }
    public static function getChildCategoryTitles($childcats, $string)
    {
        $childcategoryIds = explode(',', $string);
        $childcategoryTitles = [];

        foreach ($childcats as $childcat) {
            if (in_array($childcat['id'], $childcategoryIds)) {
                $childcategoryTitles[] =  [
                    'child_title' => $childcat['child_title'],
                    'child_sku' => $childcat['child_sku'] // Assuming you have an 'sku' key in the category array
                ];
            }
        }

        // return implode(', ', $childcategoryTitles);
        return $childcategoryTitles;
    }

    public static function getgrandCategoryTitles($grandcats, $string)
    {
        $grandcategoryIds = explode(',', $string);
        $grandcategoryTitles = [];

        foreach ($grandcats as $grandcat) {
            if (in_array($grandcat['id'], $grandcategoryIds)) {
                $grandcategoryTitles[] = [
                    'grand_title' => $grandcat['grand_title'],
                    'grand_sku' => $grandcat['grand_sku'] // Assuming you have an 'sku' key in the category array
                ];
            }
        }

        // return implode(', ', $grandcategoryTitles);
        return $grandcategoryTitles;
    }

    public static function check_active($active_status){
        switch($active_status){
            case 0:
                return 'Draft';
                break;
            case 1:
                return 'Public';
                break;
            case 2:
                return 'Private';
                break;
            case 3:
                return 'Trash';
                break;
        }
    }
    public static function check_type($active_status){
        switch($active_status){
            case 1:
                return 'General';
                break;
            case 2:
                return 'Featured';
                break;
            case 3:
                return 'On Sale';
                break;
            case 4:
                return 'Featured + On Sale';
                break;
        }
    }
    public static function CreateBy($user){
        $user_name = DB::table('users')->select('name')->where('id',$user)->first();
        if(!empty($user_name->name)){
            return '<a class="user-name" user-id="'.$user.'">'.$user_name->name.'</a>';
        }
        return '';
    }
    public static function shop_item_price($product){
        $productType = $product->product_type;

        if ($productType == 1 || $productType == 2) {
            return '$'.self::getOptionValue('_price', $product->productMeta);
        } elseif ($productType == 3 || $productType == 4) {
            return '<span class="on_sale_general_featured">$'.self::getOptionValue('_price', $product->productMeta) . '</span> / ' . '$'.self::getOptionValue('_sale_price', $product->productMeta);
        }
    }
}

