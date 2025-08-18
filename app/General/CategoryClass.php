<?php

namespace App\General;
use App\Models\Category;
use App\Models\ChildCategory;
use DB;

class CategoryClass
{
    public static function getParentCategory($option)
    {
        $category = Category::where('id', $option)->first();
        if ($category) {
            return $category;
        }
        return '';
    }

    public static function getMidParentCategory($option){
        $childCategory = ChildCategory::where('id', $option)->first();
        if ($childCategory) {
            return $childCategory;
        }
        return '';
    }

    public static function getGrandParentCategory($option){
        $childCategory = ChildCategory::where('id', $option)->first();
        if($childCategory){
            $grandParent = Category::where('id', $childCategory->main_parent_category)->first();
            if($grandParent){
                return $grandParent;
            }
            else{
                return '';
            }
        }
        return '';
    }
}

