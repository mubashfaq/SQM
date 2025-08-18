<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerQuery;
use App\Models\Product;
use App\General\GeneralSettingsClass;
use App\Http\Controllers\Website\EmailController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
class CustomerqueryContorller extends Controller
{

    public function index()
    {
        $quries = CustomerQuery::orderBy('created_at', 'desc')->get();
        return view('Admin.CustomerQuery.index', compact('quries'));

    }

    public function destroy($id)
    {
        $customer_q = CustomerQuery::find($id);
        if (!$customer_q) {
            return Redirect::back()->withErrors(['Record not found.']);
        }
        $customer_q->delete();
        return redirect()->route('quries.index')->with('success', 'Record deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        CustomerQuery::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Selected records deleted successfully']);
    }

    public function getCustomerQueryDetail($id)
    {
        $customerQuery = CustomerQuery::with('product')->find($id);
        return response()->json($customerQuery);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'c_email' => 'required|string',
            'c_phone' => 'required|string',
            'c_state' => 'required|string',
            'c_message' => 'required|string',
            'product_id' => 'required|integer',
        ]);

        $customerquer = CustomerQuery::create($validatedData);
        $product = Product::where('id', $validatedData['product_id'])->first();

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

        $product_image = url('/') . '/' . $product->featured_image;

        if ($customerquer) {
            $params = [
                'subject' => 'Expert Query Submitted | ' . config('app.website_name'),
                'to' => $validatedData['c_email'],
                'blade' => 'coustomer-query',
                'data' => [
                    'name' => $validatedData['f_name'] . " " . $validatedData['l_name'],
                    'message_data'=> $validatedData['c_message'],
                    'product_title' => $product->title,
                    'product_image' => $product_image,
                    'url' => url('/'),
                    'logo' => $logo,
                    'instagram_link' => $instagram_link,
                    'facebook_link' => $facebook_link,
                    'twitter_link' => $twitter_link,
                    'youtube_link' => $youtube_link,
                    'admin_email' => $admin_email,
                ],
            ];

            $emailController = new EmailController();
            $emailSentUser = $emailController->SendInstantEmail($params);

            if ($emailSentUser) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Your concern has been submitted, and our expert will contact you by email.',
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error, An error occurred while sending the query email.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Error, An error occurred while saving the query.',
            ]);
        }
    }
}
