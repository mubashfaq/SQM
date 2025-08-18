<?php

namespace App\General;

use DB;

class ProjectClass
{
    public function all_variations($project_id){
        $P_variations = DB::table('projects_donation_variation')->where('project_id', $project_id)->get();
        $variations = '';

        foreach ($P_variations as $P_variation) {
            $variations .= '
                <div class="project-single-carted">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <img class="variation_image" src="'.url('/').'/'.$P_variation->thumbnail.'" width="100%">
                        </div>
                        <div class="col-lg-5 col-md-4 col-sm-12">
                            <div class="">
                                <div class="h3 project_Var_title mt-2">'.$P_variation->title.'</div>
                                <div class="h1 project_Var_price fw-bold" style="letter-spacing:-1px;">$'.$P_variation->price.'</div>
                                <p class="project_Var_desc">'.$P_variation->description.'To make text bold in Bootstrap, you can use the class ".font-weight-bold"12. In Bootstrap 5, you can also use the class ".fw-bold</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 single-project-align">
                            <div>
                                <button class="btn single-project-addcart-btn">ADD TO DONATION CART</button>
                            </div>
                        </div>
                    </div>
                </div>';
        }
        return $variations;
    }

    public function all_variations_scroll($project_id){
        $P_variations = DB::table('projects_donation_variation')->where('project_id', $project_id)->get();
        $variations = '';
        $i = 0;
        foreach ($P_variations as $P_variation) {
            $variations .= '
                <input type="checkbox" id="option'.$i.'" name="options" value="option'.$i.'">
                <label for="option'.$i.'">
                    <div class="j-package-details ">
                        <p class="j-donatebate-text-para j-mrgn-0">DONATION PACKAGE</p>
                        <p class="j-donatecard-right-texttop j-docard-right texttracate">
                            '.$P_variation->title.'</p>
                    </div>
                    <div class="j-grid-docard-selctdiv">
                        <p class="single-project-amount-inp j-txt-center">$'.$P_variation->price.' x</p><input type="number" name="" id=""
                            value="1" class="j-inp-num">
                    </div>
                </label>';
                $i++;
        }
        return $variations;
    }


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
    public static function CreateBy($user){
        $user_name = DB::table('users')->select('name')->where('id',$user)->first();
        if(!empty($user_name->name)){
            return '<a class="user-name" user-id="'.$user.'">'.$user_name->name.'</a>';
        }
        return '';
    }
}

