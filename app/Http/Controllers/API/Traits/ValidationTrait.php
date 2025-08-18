<?php
namespace App\Http\Controllers\API\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



trait ValidationTrait
{
    protected function validateOrderRequest(Request $request)
{
    // Format numeric values by removing commas before merging
    $numericFields = [
        'subTotal',
        'totalGst', 
        'total_amount',
        'grand_total',
        'auth_discount_per',
        'auth_discount',
        'discountedAmount',
        'delivery_charges'
    ];

    $formattedData = [];
    foreach ($numericFields as $field) {
        $value = $request->input($field, 0);
        $formattedData[$field] = is_string($value) ? (float) str_replace(',', '', $value) : $value;
    }

    // Merge formatted numeric values
    $request->merge($formattedData);

    // Format product numeric values
    $products = array_map(function ($product) {
        return [
            'id' => $product['id'] ?? 0,
            'quantity' => $product['quantity'] ?? 0,
            'qp_subtotal' => is_string($product['qp_subtotal'] ?? 0) 
                ? (float) str_replace(',', '', $product['qp_subtotal']) 
                : ($product['qp_subtotal'] ?? 0),
        ];
    }, $request->input('products', []));

    $request->merge([
        'payment_status' => $request->input('payment_status', 'pending'),
        'products' => $products
    ]);

    $rules = [
        'user_id' => 'integer|nullable',
        'status' => 'required|string|in:pending,success,default:pending',
        'subTotal' => 'required|numeric|regex:/^\d*\.?\d{0,2}$/',
        'totalGst' => 'required|numeric|regex:/^\d*\.?\d{0,2}$/',
        'total_amount' => 'required|numeric|regex:/^\d*\.?\d{0,2}$/',
        'auth_discount_per' => 'nullable|numeric|regex:/^\d*\.?\d{0,2}$/',
        'auth_discount' => 'nullable|numeric|regex:/^\d*\.?\d{0,2}$/',
        'grand_total' => 'required|numeric|regex:/^\d*\.?\d{0,2}$/',
        'discountedAmount' => 'nullable|numeric|regex:/^\d*\.?\d{0,2}$/',
        'discountPlan' => 'nullable|string|max:255',
        'channel' => 'required|string|max:255',
        'payment_status' => 'nullable|string',
        'order_notes' => 'nullable|string',
        'payment_method' => 'required|string|max:255',
        'delivery_charges' => 'required|numeric|regex:/^\d*\.?\d{0,2}$/',
        'delivery_method' => 'required',
        'admin_delete' => 'required|boolean',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|numeric',
        'products.*.qp_subtotal' => 'required|numeric|min:0|regex:/^\d*\.?\d{0,2}$/',
    ];

    if (!$request->has('selected_address')) {
        $rules = array_merge($rules, [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'post_code' => 'required|string|max:255',
            'suburb' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            's_f_name' => 'nullable|max:255',
            's_l_name' => 'nullable|max:255',
            's_email' => 'nullable|max:255',
            's_company' => 'nullable|string|max:255',
            's_phone' => 'nullable|string|max:255',
            's_street_address' => 'nullable|string|max:255',
            's_post_code' => 'nullable|string|max:255',
            's_suburb' => 'nullable|string|max:255',
            's_state' => 'nullable|string|max:255',
            's_city' => 'nullable|string|max:255',
        ]);
    }

    return Validator::make($request->all(), $rules);
}
    protected function userValidationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+?(\d{1,3})?-?(\d{3})?-?(\d{3})?-?(\d{4})?$/',
            ],
            'role_id' => 'required|integer',
        ]);
    }
    protected  function userProfileValidationRequest(Request $request){

        return Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+?(\d{1,3})?-?(\d{3})?-?(\d{3})?-?(\d{4})?$/',
            ],
            'old_password' => 'required_with:password|string',
//            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

    }
    protected function userAddressValidationRequest(Request $request){
        return Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:255',
            'suburb' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'address_type' => 'required|string|max:255',
        ]);

    }

    protected function userUpdateAddressValidationRequest(Request $request){
        return Validator::make($request->all(), [
            'user_id' => 'nullable|integer',
            'f_name' => 'nullable|string|max:255',
            'l_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address_type' => 'nullable|string|max:255',
        ]);

    }

    protected function newLetterValidationRequest(Request $request)
    {
        return  Validator::make($request->all(), [
            'email' => 'required|email',
            'state' => 'nullable|string|max:255',
            'user_id' => 'nullable|integer',
            'business_type' => 'nullable|string'
        ]);
    }
    protected function emailValidationRequest(Request $request){
        return Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    }
    protected function passwordValidationRequest(Request $request){
        return Validator::make($request->all(), [
            'password' => 'required|string|min:8|',
            'password_confirmation' => 'required|same:password',
            'otp' => 'numeric',
        ]);
    }

    protected function emailOtpValidationRequest(Request $request){
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);
    }

    private function handleValidationFailure($validator)
    {
        $errors = $validator->errors()->toArray();
        $message = reset($errors)[0];
        return $this->error($message, 422);
    }
    protected function contactUsValidationRequest(Request $request){
        return  Validator::make($request->all(), [
            'f_name' =>'nullable|string',
            'l_name' =>'nullable|string',
            'subject' =>'nullable|string',
            'email' =>'required|email',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+?(\d{1,3})?-?(\d{3})?-?(\d{3})?-?(\d{4})?$/',
            ],
            'message' =>'nullable|string',
        ]);
    }
    protected function orderReturnValidationRequest(Request $request){
        return  Validator::make($request->all(), [
            'f_name' =>'nullable|string',
            'l_name' =>'nullable|string',
            'subject' =>'required|string',
            'email' =>'required|email',
            'order_id' =>'required|string',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+?(\d{1,3})?-?(\d{3})?-?(\d{3})?-?(\d{4})?$/',
            ],
            'message' =>'nullable|string',
        ]);
    }
}
