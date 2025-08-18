<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\General\GeneralSettingsClass;
use App\Models\NewsLetterSubscription;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = GeneralSettings::get();
        $minimumWeightGrams = GeneralSettingsClass::getOptionValue('_minimum_weight', $settings);
        $minimum_items = GeneralSettingsClass::getOptionValue('_minimum_items', $settings);
        $minimumWeightKg = $minimumWeightGrams ;
        $headerLogoDark = GeneralSettingsClass::getOptionValue('_header_logo_dark', $settings);
        $headerLogo = GeneralSettingsClass::getOptionValue('_header_logo', $settings);
        $favIcon = GeneralSettingsClass::getOptionValue('_fav_icon', $settings);
        $footerLogo = GeneralSettingsClass::getOptionValue('_footer_logo', $settings);

        return view('Admin.Settings.index', compact('settings', 'minimumWeightGrams', 'minimumWeightKg','minimum_items', 'headerLogoDark', 'headerLogo', 'favIcon'));
    }

    public function storeSetting(Request $request)
    {
        $metaDescriptions = $request->only([
            '_tagline',
            '_header_logo',
            '_footer_logo',
            '_header_logo_dark',
            '_fav_icon', // <-- included here
            '_gst_percentage',
            '_minimum_items',
            '_delivery_charges',
            '_minimum_weight',
            '_paypal_live_key',
            '_paypal_live_secrete_key',
            '_paypal_live_environment',
            '_paypal_sandbox_key',

            '_paypal_sandbox_secrete_key',
            '_paypal_sandbox_environment',
            '_active_paypal_environment',
            '_stripe_publish_key',
            '_stripe_live_environment',
            '_stripe_secrit_key',
            '_stripe_sandbox_environment',
            '_active_stripe_environment',
            '_mail_mailer',
            '_mail_host',
            '_mail_port',
            '_mail_username',
            '_mail_encrption',
            '_mail_password',
            '_mail_from_name',
            '_mail_from_address',
            '_b_name',
            '_b_email',
            '_b_number',
            '_b_area_address',
            '_b_street_address',
            '_pos_invoice_note',
            '_store_order_note',
            '_store_return_order_note',
            '_footer_note',
            '_layout_color',
            '_button_color',
            '_youtube_link',
            '_instagram_link',
            '_facebook_link',
            '_linke_din',
            '_twitter_link',
            '_delivery_api_key',
            '_delivery_active',
            '_delivery_from_postcode',
            '_measurement_active',
            '_analytics_measurement_id',
            '_abn',
            '_bank_details',
            '_bsb',
            '_acc',
            '_bank_name',

        ]);

        $settings = GeneralSettings::get();

        // Save header logo
        if ($request->hasFile('_header_logo')) {
            $image = $request->file('_header_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('logos'), $imageName);
            $metaDescriptions['_header_logo'] = 'logos/' . $imageName;
        } else {
            $headerLogo = GeneralSettingsClass::getOptionValue('_header_logo', $settings);
            if ($headerLogo) {
                $metaDescriptions['_header_logo'] = $headerLogo;
            }
        }

        // Save dark header logo
        if ($request->hasFile('_header_logo_dark')) {
            $image = $request->file('_header_logo_dark');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('logos'), $imageName);
            $metaDescriptions['_header_logo_dark'] = 'logos/' . $imageName;
        } else {
            $headerLogoDark = GeneralSettingsClass::getOptionValue('_header_logo_dark', $settings);
            if ($headerLogoDark) {
                $metaDescriptions['_header_logo_dark'] = $headerLogoDark;
            }
        }

        if ($request->hasFile('_footer_logo')) {
            $image = $request->file('_footer_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('logos'), $imageName);
            $metaDescriptions['_footer_logo'] = 'logos/' . $imageName;
        } else {
            $footerLogo = GeneralSettingsClass::getOptionValue('_footer_logo', $settings);
            if ($footerLogo) {
                $metaDescriptions['_footer_logo'] = $footerLogo;
            }
        }

        // Save fav icon
        if ($request->hasFile('_fav_icon')) {
            $image = $request->file('_fav_icon');
            $imageName = 'favicon_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('logos'), $imageName);
            $metaDescriptions['_fav_icon'] = 'logos/' . $imageName;
        } else {
            $existingFavIcon = GeneralSettingsClass::getOptionValue('_fav_icon', $settings);
            if ($existingFavIcon) {
                $metaDescriptions['_fav_icon'] = $existingFavIcon;
            }
        }

        // Save settings
        $settingsMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $settingsMeta[] = [
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }

        DB::table('general_settings')->truncate();
        DB::table('general_settings')->insert($settingsMeta);

        // Update mail config
        config([
            'mail.mailer' => $request->_mail_mailer,
            'mail.host' => $request->_mail_host,
            'mail.port' => $request->_mail_port,
            'mail.username' => $request->_mail_username,
            'mail.password' => $request->_mail_password,
            'mail.encryption' => $request->_mail_encrption,
            'mail.from.name' => $request->_mail_from_name,
            'mail.from.address' => $request->_mail_from_address,
        ]);

        return redirect()->back()->with('success', 'General Settings Updated Successfully.');
    }



    public function newsletter_subscriptions()
    {
        $subscriptions = NewsLetterSubscription::latest()->get();
        return view('Admin.NewsLetters.index', compact('subscriptions'));
    }

    public function subscription_destroy($id)
    {
        $subscription = NewsLetterSubscription::find($id);
        if (!$subscription) {
            return Redirect::back()->withErrors(['Record not found.']);
        }
        $subscription->delete();
        return redirect()->route('Admin.Subscription.index')->with('success', 'Record deleted successfully.');
    }

    public function subcription_bulk_delete(Request $request)
    {
        $ids = $request->input('ids');
        NewsLetterSubscription::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Selected records deleted successfully']);
    }
}
