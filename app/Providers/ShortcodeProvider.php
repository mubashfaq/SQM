<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use DB;

class ShortcodeProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
Blade::directive('ProjectVariations', function ($project_id) {
    $P_variations = DB::table('projects_donation_variation')->where('project_id', $project_id)->get();
    $variations = '';

    foreach ($P_variations as $P_variation) {
        $variations .= '
            <div class="row d-flex justify-content-center single-project-pt-100">
                <div class="col-lg-10 col-md-11 col-sm-12 ">
                    <div class="project-single-carted">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <div class="addcart-donate-img" style="background-image: url('.url('/').'/'.$P_variation->thumbnail.');background-size:cover;background-position:center">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-5 col-sm-12">
                                <div class="">
                                    <div class="h3 project-single-addcart-title">'.$P_variation->title.'</div>
                                    <div class="h2 project-single-addcart-title">'.$P_variation->price.'</div>
                                    <p class="project-single-addcart-desc sinfle-project-line">'.$P_variation->description.'</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 single-project-align">
                                <div>
                                    <button class="btn single-project-addcart-btn">ADD TO DONATION CART</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }

    return $variations;
});

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
