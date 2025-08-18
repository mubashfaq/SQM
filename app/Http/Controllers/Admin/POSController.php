<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\order;
use App\Models\order_meta;
use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use App\Models\GeneralSettings;
use App\Http\Controllers\Controller;
use App\General\GeneralSettingsClass;
use Illuminate\Contracts\Session\Session;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\API\Traits\StripeTrait;
use App\Http\Controllers\Payment\Traits\RefundPaymentTrait;

class POSController extends Controller
{
    use StripeTrait;
    use RefundPaymentTrait;
    public function index(Request $request)
    {
        // Base query without date filtering by default
        $originalQuery = Order::where('admin_delete', 0)
            ->where('status', '!=', 'refund')
            ->orderBy('id', 'desc')
            ->with('orderMeta', 'user');
        // Get the days parameter with default value of null
        // $defaultDays = $request->filled('days') ? (int) $request->get('days') : null;

        // Apply date range filter if `days` is provided
        if ($request->filled('days')) {
            $days = (int) $request->get('days');
            $startDate = Carbon::now()->subDays($days)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $originalQuery->whereBetween('created_at', [$startDate, $endDate]);
            // dd($originalQuery);
        }



        // Apply other filters
        if ($request->filled('search_order')) {
            $originalQuery->where('invoice_number', 'like', '%' . $request->search_order . '%');
        }

        if ($request->filled('search_customer')) {
            $searchCustomer = trim($request->get('search_customer'));
            $originalQuery->where(function ($query) use ($searchCustomer) {
                $query->where('f_name', 'like', '%' . $searchCustomer . '%')
                    ->orWhere('l_name', 'like', '%' . $searchCustomer . '%')
                    ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchCustomer . '%']);
            });
        }

        if ($request->filled('search_status')) {
            $originalQuery->where('status', $request->get('search_status'));
        }


        // Pagination
        $orders = $originalQuery->paginate(15)->appends(request()->query());

        $countQuery = clone $originalQuery;
        $totalOrders = $countQuery->count();
        $completedOrders = Order::where('status', 'completed')->where('admin_delete', 0)->count();
        $pendingOrders = Order::where('status', 'pending')->where('admin_delete', 0)->count();
        $inProcessOrders = Order::where('status', 'inProcess')->where('admin_delete', 0)->count();
        $cancelledOrders = Order::where('status', 'cancelled')->where('admin_delete', 0)->count();




        return view('Admin.Orders.index', compact(
            'orders',
            'completedOrders',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'cancelledOrders',
            'inProcessOrders',
        ));
    }

    public function filter_order(Request $request)
    {
        if ($request->ajax()) {
            $now = Carbon::now();
            $ajaxFilterDays = $request->filterDays;
            $page = $request->page;
            $pageType = $request->pageType;

            $ajaxDaysAgo = $now->copy()->subDays($ajaxFilterDays);
            if ($pageType == 'Returned') {
                if ($ajaxFilterDays == null) {
                    $originalQuery = order::where('status', 'refund')->where('admin_delete', 0)->orderBy('id', 'desc')->with('orderMeta', 'user', 'returnedOrder');
                } else {
                    $originalQuery = order::where('status', 'refund')->whereBetween('created_at', [$ajaxDaysAgo, $now])->where('admin_delete', 0)->orderBy('id', 'desc')->with('orderMeta', 'user');
                }
            } else {
                if ($ajaxFilterDays == null) {
                    $originalQuery = order::where('admin_delete', 0)->where('status', '!=', 'refund')->orderBy('id', 'desc')->with('orderMeta', 'user');
                } else {
                    $originalQuery = order::whereBetween('created_at', [$ajaxDaysAgo, $now])->where('admin_delete', 0)->where('status', '!=', 'refund')->orderBy('id', 'desc')->with('orderMeta', 'user');
                }
            }
            $perPage = 15;
            $orders = $originalQuery->paginate($perPage);
            $paginationHtml = $orders->links('pagination::bootstrap-4')->toHtml();

            $saveFilterDays = $ajaxFilterDays;

            if (session()->get('defaultFilterDays') != $ajaxFilterDays) {
                $completedQuery = clone $originalQuery;
                $pendingQuery = clone $originalQuery;
                $inProcessQuery = clone $originalQuery;
                $cancelledQuery = clone $originalQuery;

                $totalOrders = $originalQuery->count();
                $completedOrders = $completedQuery->where('status', 'completed')->count();
                $pendingOrders = $pendingQuery->where('status', 'pending')->count();
                $inProcessOrders = $inProcessQuery->where('status', 'inProcess')->count();
                $cancelledOrders = $cancelledQuery->where('status', 'cancelled')->count();

                session([
                    'defaultFilterDays' => $ajaxFilterDays,
                    'totalOrders' => $totalOrders,
                    'completedOrders' => $completedOrders,
                    'pendingOrders' => $pendingOrders,
                    'inProcessOrders' => $inProcessOrders,
                    'cancelledOrders' => $cancelledOrders,
                ]);
            } else {
                $totalOrders = session('totalOrders', null);
                $completedOrders = session('completedOrders', null);
                $pendingOrders = session('pendingOrders', null);
                $inProcessOrders = session('inProcessOrders', null);
                $cancelledOrders = session('cancelledOrders', null);
            }

            if ($pageType == 'Returned') {
                $orders = view('Admin.Ajax.returnedFilteredOrders', compact('orders'))->render();
                $counts = view('Admin.Ajax.returnedFilteredOrderCount', compact('ajaxFilterDays', 'totalOrders', 'pendingOrders', 'completedOrders', 'inProcessOrders', 'cancelledOrders'))->render();
            } else {
                $orders = view('Admin.Ajax.filteredOrders', compact('orders'))->render();
                $counts = view('Admin.Ajax.filteredOrderCount', compact('ajaxFilterDays', 'totalOrders', 'pendingOrders', 'completedOrders', 'inProcessOrders', 'cancelledOrders'))->render();
            }
            return response()->json([
                'orders' => $orders,
                'counts' => $counts,
                'pagination' => $paginationHtml,
            ]);
        }
    }
    public function filter(Request $request)
    {
        // 1. Start a new query
        $query = Order::query();

        // 2. Filter by "Days" if provided
        if ($request->has('filterDays') && !empty($request->filterDays)) {
            $days = (int) $request->filterDays;
            $query->where('created_at', '>=', now()->subDays($days));
        }

        // 3. Handle the search term
        if ($request->has('searchTerm') && !empty($request->searchTerm)) {
            $searchTerm = $request->searchTerm;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('invoice_number', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('f_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('l_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('payment_method', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('delivery_method', 'LIKE', "%{$searchTerm}%");
                // ... Add any other fields you want to be searchable.
            });
        }

        // 4. Order by recent
        $query->orderBy('created_at', 'desc');

        // 5. Paginate (10 items per page, for example)
        $orders = $query->paginate(10);

        // 6. [Optional] If you need to recalc some counts (Pending, Completed, etc.)
        //    You can do that by removing the pagination constraint or using separate queries
        $totalOrders = Order::where('admin_delete', 0)->count();
        $pendingOrders = Order::where('status', 'pending')->where('admin_delete', 0)->count();
        $inProcessOrders = Order::where('status', 'inProcess')->where('admin_delete', 0)->count();
        $completedOrders = Order::where('status', 'completed')->where('admin_delete', 0)->count();
        $cancelledOrders = Order::where('status', 'cancelled')->where('admin_delete', 0)->count();

        // 7. Render the partial that has only the table + pagination
        $renderedTable = view('Admin.orders.partials.orders-table', [
            'orders' => $orders
        ])->render();

        // 8. If you have a separate partial for the counts
        //    e.g. Admin.orders.partials.orders-counts
        //    you can do:
        $renderedCounts = view('Admin.orders.partials.orders-counts', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'inProcessOrders' => $inProcessOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders
        ])->render();

        // 9. Return JSON with the partial HTML
        return response()->json([
            'orders' => $renderedTable,
            'counts' => $renderedCounts,
            // Optionally pass raw pagination if needed
            'pagination' => (string) $orders->links()
        ]);
    }

    public function change_order_status(Request $request)
    {
        $orderId = $request->input('orderId');
        $status = $request->input('status');
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Order not found']);
        }

        if ($status === 'completed') {
            return response()->json([
                'status' => true,
                'redirect' => route('order.update', ['id' => $order->id])
            ]);

        }

        $order->status = $status;
        $order->payment_status = $status;
        $order->save();
        // Status Update Email
        if (in_array($status, ['cancelled', 'inProcess'])) {
            $capitalizedStatus = ucwords($status);
            $this->orderStatusUpdateEmail($order, $capitalizedStatus);
        }

        return response()->json(['status' => true, 'message' => 'Order Status Updated Successfully.']);
    }

    public function update_order_view($id)
    {
        $order = Order::find($id);
        $settings = GeneralSettings::get();
        $minimumWeightGrams = GeneralSettingsClass::getOptionValue('_minimum_weight', $settings);
        $minimumWeightKg = $minimumWeightGrams ? $minimumWeightGrams / 1000 : 0.5;

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }
        return view('Admin.Orders.update', compact('order', 'minimumWeightKg','minimumWeightGrams'));
    }
    public function update(Request $request, Order $order)
    {
        // Validate request
        $request->validate([
            'weights' => 'required|array',
            'prices' => 'required|array',
        ]);
        $status = 'completed';
        $finalAmountCents = $request->grand_total * 100;
        if (!$finalAmountCents) {
            return response()->json(['status' => false, 'message' => 'No final amount provided']);
        }

        $paymentMethodId = $order->stripe_payment_method_id;
        $customer_id = $order->stripe_customer_id;

        if (!$paymentMethodId) {
            return response()->json(['status' => false, 'message' => 'No payment method ID stored']);
        }

        $this->initializeStripe();

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $finalAmountCents,
            'currency' => 'usd',
            'customer' => $customer_id,
            'payment_method' => $paymentMethodId,
            'off_session' => true,
            'confirm' => true,
        ]);

        if ($paymentIntent->status === 'succeeded') {
            $orderMetaItems = $order->orderMeta;
            foreach ($orderMetaItems as $metaItem) {
                $metaItem->payment_intent_id = $paymentIntent->id;
                $metaItem->save();
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Payment not succeeded. Status: ' . $paymentIntent->status]);
        }

        $order->status = $status;
        $order->payment_status = $status;
        $order->save();


        $totalSubtotal = 0;

        // Loop through each order meta and update the quantity and subtotal
        foreach ($order->orderMeta as $meta) {
            if (isset($request->weights[$meta->id])) {
                $quantity = (float) $request->weights[$meta->id];
                $qpSubtotal = (float) $request->prices[$meta->id];
                // Update OrderMeta
                $meta->update([
                    // 'quantity' => $quantity,
                    'qp_subtotal' => $qpSubtotal,
                    'delivered_weight' => $quantity,
                ]);

                // Add to total subtotal
                $totalSubtotal += $qpSubtotal;
            }
        }

        // Update Order total
        $grandTotal = $totalSubtotal + $request->total_gst + $order->delivery_charges;

        $order->update([
            // 'grand_total' => $grandTotal,
            'total_amount' => $grandTotal,
            'subTotal' => $totalSubtotal,
            'delivered_price' => $grandTotal,
            'totalGst' => $request->total_gst,
        ]);
        // Status Update Email
        if (in_array($status, ['completed', 'cancelled', 'inProcess'])) {
            $capitalizedStatus = ucwords($status);
            $this->orderCompletedEmail($order, $capitalizedStatus);
        }
        return redirect()->route('admin.orders')->with(['status' => true, 'message' => 'Order Status Updated Successfully.']);
    }

    public function detail(Request $request)
    {

        $order = order::where('id', $request->id)->with('orderMetas.product')->first();
        $user = null;
        $orderCount = 0;
        if ($order->user_id) {
            $user = User::where('id', $order->user_id)->orWhere('email', $order->email)->with('userMeta')->first();
            $orderCount = order::where('user_id', $user->id)->orWhere('email', $order->email)->count();
        }
        return view('Admin.Orders.details', compact('order', 'user', 'orderCount'))->render();
    }
    public function delete_order(Request $request)
    {
        $id = $request->id;
        $order = order::find($id);
        if ($order) {
            $order->admin_delete = 1;
            $order->update();
            return redirect()->back()->with('success', 'Selected records deleted successfully');
        } else {
            return redirect()->back()->with('success', 'Order not found');
        }
    }

    public function bulk_delete(Request $request)
    {
        $ids = $request->input('ids');
        $idsArray = explode(',', $ids);
        if (empty($idsArray)) {
            return redirect()->back()->with('error', 'No records selected for deletion');
        }
        $deleted = Order::whereIn('id', $idsArray)->update([
            'admin_delete' => 1
        ]);
        if ($deleted) {
            return redirect()->back()->with('success', 'Selected records deleted successfully');
        }
        return redirect()->back()->with('error', 'Failed to delete selected records');
    }

    public function cancelled_orders_list(Request $request)
    {
        // Base query for cancelled orders
        $originalQuery = Order::where('status', 'cancelled')->where('admin_delete', 0)
            ->orderBy('id', 'desc')
            ->with('orderMeta', 'user');

        // Get the days parameter with default value of null
        $defaultDays = $request->filled('days') ? (int) $request->get('days') : null;

        // Apply date filter if days parameter is provided
        if ($defaultDays) {
            $now = Carbon::now();
            $startDate = $now->copy()->subDays($defaultDays);
            $originalQuery->where('created_at', '>=', $startDate);
        }

        // Apply other filters
        if ($request->filled('search_order')) {
            $originalQuery->where('invoice_number', 'like', '%' . $request->search_order . '%');
        }

        if ($request->filled('search_customer')) {
            $searchCustomer = trim($request->get('search_customer'));
            $originalQuery->where(function ($query) use ($searchCustomer) {
                $query->where('f_name', 'like', '%' . $searchCustomer . '%')
                    ->orWhere('l_name', 'like', '%' . $searchCustomer . '%')
                    ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchCustomer . '%']);
            });
        }

        // Note: search_status filter is not needed since we're already filtering for 'cancelled' status
        // But if you want to allow filtering by other statuses too, you can uncomment below:
        // if ($request->filled('search_status')) {
        //     $originalQuery->where('status', $request->get('search_status'));
        // }

        $orders = $originalQuery->paginate(30);

        return view('Admin.Orders.cancelled', compact('orders'));
    }

    public function return_order_list()
    {
        session()->forget([
            'defaultFilterDays',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'inProcessOrders',
            'cancelledOrders',
        ]);
        $now = Carbon::now();
        $sevenDaysAgo = $now->copy()->subDays(7);
        $originalQuery = order::where('status', 'refund')->whereBetween('created_at', [$sevenDaysAgo, $now])->where('admin_delete', 0)->orderBy('id', 'desc')->with('orderMeta', 'user', 'returnedOrder');
        $perPage = 15;
        $orders = $originalQuery->paginate($perPage);

        $completedQuery = clone $originalQuery;
        $pendingQuery = clone $originalQuery;
        $inProcessQuery = clone $originalQuery;
        $cancelledQuery = clone $originalQuery;

        $totalOrders = $originalQuery->count();
        $completedOrders = $completedQuery->where('status', 'completed')->count();
        $pendingOrders = $pendingQuery->where('status', 'pending')->count();
        $inProcessOrders = $inProcessQuery->where('status', 'inProcess')->count();
        $cancelledOrders = $cancelledQuery->where('status', 'cancelled')->count();

        session([
            'defaultFilterDays' => 7,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'inProcessOrders' => $inProcessOrders,
            'cancelledOrders' => $cancelledOrders,
        ]);

        return view('Admin.OrderReturn.returned', compact('orders', 'totalOrders', 'completedOrders', 'pendingOrders', 'cancelledOrders', 'inProcessOrders'));
    }

    public function return_detail(Request $request)
    {
        $order = ReturnOrder::where('id', $request->id)->with('returnMeta.product', 'order')->first();
        $user = null;
        $orderCount = 0;
        if ($order->user_id) {
            $user = User::where('id', $order->user_id)->orwhere('email', $order->user_email)->with('userMeta')->first();
            $orderCount = ReturnOrder::where('user_id', $user->id)->orwhere('user_email', $order->user_email)->count();
        }
        return view('Admin.OrderReturn.return-detail', compact('order', 'user', 'orderCount'))->render();
    }

    public function delete_returned_order(Request $request)
    {
        // Find the return order by its ID
        $orderReturn = ReturnOrder::where('order_id', $request->id)->first();
        $order = order::where('id', $request->id)->first();
        if (!$orderReturn && !$order) {
            return response()->json(['status' => 'error', 'message' => 'Order return record not found.']);
        }
        $orderReturn->admin_delete = 1;
        $orderReturn->save();
        $order->admin_delete = 1;
        $order->save();
        return response()->json(['status' => 'success', 'message' => 'Order has been deleted and status updated.']);
    }

    public function bulk_delete_returned_order(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No records selected'], );
        }
        Order::whereIn('id', $ids)->update(['admin_delete' => 1]);
        ReturnOrder::whereIn('order_id', $ids)->update(['admin_delete' => 1]);
        return response()->json(['message' => 'Selected records deleted successfully']);
    }

    public function sendStatusChangeEmail($capitalizedStatus, $orderdetail, $orderMeta, $status)
    {
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

        $EMAIL = null;

        if ($orderdetail && $orderdetail->orderAddressDetail && $orderdetail->orderAddressDetail->email) {

            $EMAIL = $orderdetail->orderAddressDetail->email;

        } elseif ($orderdetail && $orderdetail->email) {
            $EMAIL = $orderdetail->email;
        }

        $params = [
            'subject' => 'Order ' . $capitalizedStatus . ' | ' . config('app.website_name'),
            'to' => $EMAIL,
            'blade' => 'order',
            'data' => [
                'name' => $orderdetail->f_name . " " . $orderdetail->l_name,
                'status' => $status,
                'orderdetail' => $orderdetail,
                'orderMeta' => $orderMeta,
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
        $emailSent = $emailController->SendInstantEmail($params);
    }
}
