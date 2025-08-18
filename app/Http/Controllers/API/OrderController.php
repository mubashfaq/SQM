<?php

namespace App\Http\Controllers\API;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Traits\StripeTrait;


class OrderController extends Controller
{
    use StripeTrait;
    public function index()
    {
        $user = $this->authUser();
        $orders = $user->orders()->latest();
        return $this->withCount($orders);
    }

    public function store(Request $request)
    {
        try {
            $request->merge(['user_id' => $request->input('user_id', 0)]);
            $validator = $this->validateOrderRequest($request);
            if ($validator->fails()) {
                return $this->handleValidationFailure($validator);
            }
            $validatorData = $validator->validated();
            $this->handleSelectedAddress($request, $validatorData);
            if ($request->payment_method === 'card') {
                return $this->processStripePayment($request, $validatorData);
            } elseif ($request->payment_method === 'Paypal') {
               return $this->processPaypalPayment($request, $validatorData);
            } else {
                return $this->error('Unsupported payment method', 422);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error placing order: ' . $e->getMessage());
            return $this->error('An error occurred while placing the order');
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = $this->validateOrderRequest($request);
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $message = reset($errors)[0];
                return $this->error($message, 422);
            }
            $validatorData = $validator->validated();
            $order = \App\Models\order::findOrFail($id);
            $order->update($validatorData);
            $order->orderMeta()->delete();
            $this->createOrderMeta($order, $validatorData['products']);
            DB::commit();
            return $this->message('Order updated successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('An error occurred while updating the order');
        }
    }

    public function show(Request $request)
    {
            $order = $this->findOrder($request->id);
            if (!$order){
                $this->message('Order not found');
            }
            return $this->success($order);
    }

    public function destroy($id)
    {
        $order = \App\Models\order::findOrFail($id);
        if ($order->user_id !== $this->authUser()->id) {
            return $this->error('Unauthorized', 403);
        }
        DB::beginTransaction();
        try {
            $order->orderMeta()->delete();
            $order->delete();
            DB::commit();
            return $this->message('Order deleted successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('An error occurred while deleting the order');
        }
    }
    public function userOrders(\App\Models\order $order,Request $request)
    {
        return $this->userOrderList($order,$request);
    }

    public function thankYouPage($orderId)
{
    $order = \App\Models\order::with('orderMeta.product')->findOrFail($orderId);

    $meta_details = [
        'title' => 'Thank You| Sydney Quality Meats',
        'keywords' => 'Sydney Quality Meats',
        'description' => 'Sydney Quality Meats.',
        'author' => 'Sydney Quality Meats Org',
    ];

    return view('Website.GeneralPages.thankyou', compact('order', 'meta_details'));
}

}
