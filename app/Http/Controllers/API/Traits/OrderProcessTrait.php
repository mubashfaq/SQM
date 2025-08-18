<?php
namespace App\Http\Controllers\API\Traits;
use App\Models\order;
use App\Models\Product;
use App\Models\Contactu;


use App\Models\order_meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Website\Traits\EmailTrait;

trait OrderProcessTrait
{
    use EmailTrait;
    protected function createOrder(array $validatedData)
    {
      
        // $validatedData['totalGst']=session('totalGst');
        // $validatedData['delivery_charges']=session('deliveryCharge');
        return order::create($validatedData);
    }

    protected function createOrderMeta(Order $order, array $products)
    {
       
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            if ($product) {
                $priceMeta = $product->productMeta->firstWhere('option', '_price');
                $productPrice = $priceMeta->value;
                order_meta::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'quantity' => $productData['quantity'],
                    'payment_intent_id' => $productData['payment_intent_id'] ?? null,
                    'qp_subtotal' => $productData['qp_subtotal'],
                    'price' => $productPrice,
                ]);
            }
        }
        return true;
    }
    
    protected function userOrderList($model, $request){
        $pendingOrderList = $model->where('user_id', $this->authUser()->id)
            ->where($request->column, $request->value)
            ->latest();
        return $this->withCount($pendingOrderList);
    }

    protected function orderConformationEmail($order)
    {
        $order->load(['orderMeta','orderMeta.product']);
        $params = [
            'subject' => 'Order Confirmation',
            'to' => $order->email,
            'msg' => view('Admin.Emails.order-conformation', ['order' => $order])->render()
        ];
        $this->SendInstantEmail($params);
    }
    

    protected function orderStatusUpdateEmail($order ,$status)
    {
        
        $order->load(['orderMeta','orderMeta.product']);
       
        $params = [
            'subject' => 'Order confirmation' . ' | ' . env("APP_NAME"),
            'to' => $order->email,
            'template' => 'Admin.Emails.order-conformation',
            'data' => [
                'order' => $order,
                'status' => $order->status,
                'image_url'=>$order,
            ]
        ];



        $emailSent = $this->sendEmail($params);
    }

    protected function orderCompletedEmail($order ,$status)
    {
        
        $order->load(['orderMeta','orderMeta.product']);
        // $params = [
        //     'subject' => 'Order confirmation'. ' | ' . env("APP_NAME"),
        //     'to' => $order->email,
        //     'msg' => view('Admin.Emails.order-conformation', [
        //         'order' => $order,
        //         'status'=>$order->status,
        //     ])->render()
        // ];
        $params = [
            'subject' => 'Order confirmation' . ' | ' . env("APP_NAME"),
            'to' => $order->email,
            'template' => 'Admin.Emails.order-complete',
            'data' => [
                'order' => $order,
                'status' => $order->status,
                '$image_url'=>$order,
            ]
        ];


        $emailSent = $this->SendEmail($params);
    }

    protected function order_place_email($order ,$status)
    {
        $order->load(['orderMeta','orderMeta.product']);

        $params = [
            'subject' => 'Order Status '.$status,
            'to' => $order->email,
            'msg' => view('Admin.Emails.order-conformation', ['order' => $order , 'status'=>$status])->render()
        ];
        $emailsent= $this->SendInstantEmail($params);
        // dd($emailsent);
    }
    
    protected function findOrder($id){
        return order::with(['orderMeta'])->find($id);
    }

    public function orderReturnForm(Request $request)
    {
        $validator = $this->orderReturnValidationRequest($request);
        if ($validator->fails()) {
            return $this->handleValidationFailure($validator);
        }
        $orderExists = Order::where('id', $request->order_id)->exists();
        if (!$orderExists) {
            return $this->error('Order not found ');
        }
        $data = $this->returnOrderFile($request);
        $contact = Contactu::create($data);
        if (!$contact) {
            return $this->message('Something went wrong');
        }
        return $this->message('Message sent successfully');
    }

    protected function returnOrderFile(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $filename, 'public');

            $data['file_path'] = $filePath;
        }
        return $data;
    }

}
