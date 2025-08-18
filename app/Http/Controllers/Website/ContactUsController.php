<?php

namespace App\Http\Controllers\Website;

use App\Models\User;
use App\Models\Contactu;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GeneralSettings;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\General\GeneralSettingsClass;
use App\Models\NewsLetterSubscription;
use App\Http\Controllers\Website\EmailController;
use App\Http\Controllers\Website\Traits\EmailTrait;

class ContactUsController extends Controller
{
    use EmailTrait;
    public $website_title = 'Sydney Quality Meat';
    public function index()
    {
        $meta_details = [
            'title' => 'Contact Us | ' . $this->website_title,
            'keywords' => 'contact tooltrede, tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        return view('Website.GeneralPages.contact-us', compact('meta_details'));
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'f_name' => 'nullable|string',
            'email' => 'required|string',
            'message' => 'nullable|string'
        ]);
        $contactuser = Contactu::create($validated_data);
        $data = GeneralSettings::where('option', '_header_logo')->orWhere('option', '_b_email')->orWhere('option', '_instagram_link')->orWhere('option', '_facebook_link')->orWhere('option', '_twitter_link')->orWhere('option', '_youtube_link')->get()->toArray();

        $admin_email_index = array_search('_b_email', array_column($data, 'option'));
        $header_logo_index = array_search('_header_logo', array_column($data, 'option'));
        $insta_link_index = array_search('_instagram_link', array_column($data, 'option'));
        $fb_link_index = array_search('_facebook_link', array_column($data, 'option'));
        $twitter_link_index = array_search('_twitter_link', array_column($data, 'option'));
        $youtube_link_index = array_search('_youtube_link', array_column($data, 'option'));

        $admin_email = $data[$admin_email_index]['value'];
        $logo = $data[$header_logo_index]['value'];
        $instagram_link = $data[$insta_link_index]['value'];
        $facebook_link = $data[$fb_link_index]['value'];
        $twitter_link = $data[$twitter_link_index]['value'];
        $youtube_link = $data[$youtube_link_index]['value'];

        if ($contactuser) {
            // $params = [
            //     'subject' => 'Contact Us | ' . config('app.website_name'),
            //     'to' => $validated_data['email'],
            //     'blade' => 'contactus-user',
            //     'data' => [
            //         'name' => $validated_data['f_name'] ?? '',
            //         'url' => url('/'),
            //         'logo' => $logo,
            //         'instagram_link' => $instagram_link,
            //         'facebook_link' => $facebook_link,
            //         'twitter_link' => $twitter_link,
            //         'youtube_link' => $youtube_link,
            //         'admin_email' => $admin_email,
            //     ],
            // ];

            $params = [
                'subject' => 'Contact Us | ' . config('app.website_name'),
                'to' => $validated_data['email'],
                'template' => 'Website.Emails.contactus-user',
                'data' => [
                    'name' => $validated_data['f_name'] ?? '',
                    'url' => url('/'),
                    'logo' => $logo,
                    'instagram_link' => $instagram_link,
                    'facebook_link' => $facebook_link,
                    'twitter_link' => $twitter_link,
                    'youtube_link' => $youtube_link,
                    'admin_email' => $admin_email,
                ]
            ];
            // env('BUSINESS_EMAIL')

            $emailController = new EmailController();
            $paramsAdmin = [
                'subject' => 'New Contact Form Submission',
                'to' => $admin_email,
                'template' => 'Website.Emails.contact-us-admin',
                'data' => [
                    'name' => $validated_data['f_name'] ?? '',
                    'email' => $validated_data['email'],
                    'contact_message' => $validated_data['message'] ?? '',
                    'url' => url('/'),
                    'logo' => $logo,
                ]
            ];
            $emailSentAdmin = $this->SendEmail($paramsAdmin);
            $emailSentUser = $this->SendEmail($params);
            if ($emailSentUser && $emailSentAdmin) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Message sent! We will get back to you soon.',
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error, An Error Occurred while sending the email.',
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Message sent! We will get back to you soon.',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'An Error Occurred, During creating user account.',
            ]);
        }
    }

    public function sendmail(Request $request)
    {
        $validated_data = $request->validate([
           
            'email' => 'required|string',
         
        ]);
        $contactuser = NewsLetterSubscription::create($validated_data);
        $data = GeneralSettings::where('option', '_header_logo')->orWhere('option', '_b_email')->orWhere('option', '_instagram_link')->orWhere('option', '_facebook_link')->orWhere('option', '_twitter_link')->orWhere('option', '_youtube_link')->get()->toArray();

        $admin_email_index = array_search('_b_email', array_column($data, 'option'));
        $header_logo_index = array_search('_header_logo', array_column($data, 'option'));
        $insta_link_index = array_search('_instagram_link', array_column($data, 'option'));
        $fb_link_index = array_search('_facebook_link', array_column($data, 'option'));
        $twitter_link_index = array_search('_twitter_link', array_column($data, 'option'));
        $youtube_link_index = array_search('_youtube_link', array_column($data, 'option'));

        $admin_email = $data[$admin_email_index]['value'];
        $logo = $data[$header_logo_index]['value'];
        $instagram_link = $data[$insta_link_index]['value'];
        $facebook_link = $data[$fb_link_index]['value'];
        $twitter_link = $data[$twitter_link_index]['value'];
        $youtube_link = $data[$youtube_link_index]['value'];

        if ($contactuser) {
            $params = [
                'subject' => 'Contact Us | ' . config('app.website_name'),
                'to' => $validated_data['email'],
                'template' => 'Website.Emails.news-letter-subscription',
                'data' => [
                    'name' => $validated_data['f_name'] ?? '',
                    'url' => url('/'),
                    'logo' => $logo,
                    'instagram_link' => $instagram_link,
                    'facebook_link' => $facebook_link,
                    'twitter_link' => $twitter_link,
                    'youtube_link' => $youtube_link,
                    'admin_email' => $admin_email,
                ],
            ];
            // env('BUSINESS_EMAIL')

            $emailController = new EmailController();
            $paramsAdmin = [
                'subject' => 'New Contact Form Submission',
                'to' => $admin_email,
                'template' => 'Website.Emails.contact-us-admin',
                'data' => [
                    'name' => $validated_data['f_name'] ?? '',
                    'email' => $validated_data['email'],
                    'contact_message' => $validated_data['message'] ?? '',
                    'url' => url('/'),
                    'logo' => $logo,
                ],
            ];
            $emailSentAdmin = $this->SendEmail($paramsAdmin);
            $emailSentUser = $this->SendEmail($params);
            if ($emailSentUser && $emailSentAdmin) {
                return redirect()->back()->with('success', 'Thank You for Subscribing.');
            } else {
                return redirect()->back()->with('error', 'Error, An Error Occurred while sending the email.');
            }
            return redirect()->back()->with('success', 'Thank You for Subscribing.');
        } else {
            return redirect()->back()->with('error', 'An Error Occurred, During creating user account.');
        }
    }
    
}
