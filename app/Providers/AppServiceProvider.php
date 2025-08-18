<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Menus;
use App\Models\GeneralSettings;
use App\Models\DynamicPage;
use App\General\GeneralSettingsClass;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (Schema::hasTable('general_settings') && Schema::hasTable('menus') && Schema::hasTable('categories') && Schema::hasTable('dynamic_pages') && Schema::hasTable('collections')) {
            $settings = GeneralSettings::get();
            $pages = DynamicPage::where('active', 1)->get();
            $gst_percentage = GeneralSettingsClass::getOptionValue('_gst_percentage', $settings);
            $headerLogo = GeneralSettingsClass::getOptionValue('_header_logo', $settings);
            $footerLogo = GeneralSettingsClass::getOptionValue('_footer_logo', $settings);
            config(['app.PERCENTAGE' => $gst_percentage]);

            $categories = Category::limit(6)->get();
            $navbarCategories = Category::get();
            $totalCategories = Category::count();
            $collections = Collection::where('active', 1)->with(['products.metas', 'products.discount'])->get();
            $menus = Menus::with('menuMeta')->orderBy('number')->get();
            view()->share([
                'settings' => $settings,
                'pages' => $pages,
                'navbarCategories' => $navbarCategories,
                'categories' => $categories,
                'totalCategories' => $totalCategories,
                'collections' => $collections,
                'menus' => $menus,
                'headerLogo' => $headerLogo,
                'footerLogo' => $footerLogo,
            ]);

            $mail_mailer = GeneralSettings::where('option', '_mail_mailer')->first();
            $mail_host = GeneralSettings::where('option', '_mail_host')->first();
            $mail_port = GeneralSettings::where('option', '_mail_port')->first();
            $mail_username = GeneralSettings::where('option', '_mail_username')->first();
            $mail_password = GeneralSettings::where('option', '_mail_password')->first();
            $mail_encrption = GeneralSettings::where('option', '_mail_encrption')->first();
            $mail_from_name = GeneralSettings::where('option', '_mail_from_name')->first();
            $mail_from_address = GeneralSettings::where('option', '_mail_from_address')->first();

            if($mail_mailer && $mail_host && $mail_port && $mail_username && $mail_password && $mail_encrption && $mail_from_name && $mail_from_address){
                config([
                    'mail.mailer' => $mail_mailer->value,
                    'mail.host' => $mail_host->value,
                    'mail.port' => $mail_port->value,
                    'mail.username' => $mail_username->value,
                    'mail.password' => $mail_password->value,
                    'mail.encryption' => $mail_encrption->value,
                    'mail.from.name' => $mail_from_name->value,
                    'mail.from.address' => $mail_from_address->value,
                ]);
            }

            $defaultPayPalMode = GeneralSettings::where('option', '_active_paypal_environment')->first();

            if ($defaultPayPalMode) {
                config(['services.paypal.settings.mode' => $defaultPayPalMode->value]);
            }
        }
    }
}
