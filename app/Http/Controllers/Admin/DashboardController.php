<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\order;
use App\Models\Product;
use App\Models\customer;
use App\Models\Invoices;
use App\Models\AreaGroup;
use App\Models\Collection;
use App\Models\order_meta;
use App\Models\productMeta;
use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use App\Models\SellingProduct;
use Illuminate\Support\Facades\DB;
use App\Models\LeastSellingProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index()
    {
        $total_users = User::count();
        $discount = Discount::count();
        $total_products = Product::where('active', 1)->count();
        $total_brands = Brand::where('active', 1)->count();
        $total_areagroups = AreaGroup::count();
        $total_collections = Collection::where('active', 1)->count();
        $comp_orders = order::where('status', 'completed')->where('admin_delete', '!=', 1)->count();
        $pend_orders = order::where('status', 'pending')->where('admin_delete', '!=', 1)->count();
        $canc_orders = order::where('status', 'cancelled')->where('admin_delete', '!=', 1)->count();
        $inpro_orders = order::where('status', 'inProcess')->where('admin_delete', '!=', 1)->count();
        $refundOrder = order::where('status', 'refund')->where('admin_delete', '!=', 1)->count();
        $online_orders = order::where('admin_delete', '!=', 1)->count();
        $invoice_orders = Invoices::where('active', 1)->count();

        $all_orders = $online_orders + $invoice_orders;

        $customers = User::where('role_id', 5)
            ->where('active', 1)
            ->with('shippingAddresses')
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();


        $orders = order::where('admin_delete', 0)->latest()->take(10)->get();
        // return ($orders);
        $topSellingProducts = Product::select(
            'products.id',
            'products.title',
            'products.featured_image',
            'products.permalink',
            \DB::raw('COUNT(order_metas.id) as order_count'),
            \DB::raw('SUM(order_metas.quantity) as total_quantity')
        )
            ->leftJoin('order_metas', 'products.id', '=', 'order_metas.product_id')
            ->where('active',1)
            ->groupBy('products.id', 'products.title', 'products.featured_image', 'products.permalink') 
            ->orderBy('order_count', 'DESC')
            ->take(7)
            ->get();
        // return $topSellingProducts;
        $leastSellingProducts = Product::select(
            'products.id',
            'products.title',
            'products.featured_image',
            'products.permalink',
            \DB::raw('COUNT(order_metas.id) as order_count'),
            \DB::raw('SUM(CASE WHEN order_metas.quantity IS NULL THEN 0 ELSE order_metas.quantity END) as total_quantity')
        )
            ->leftJoin('order_metas', 'products.id', '=', 'order_metas.product_id')
            ->where('active',1) 
            ->groupBy('products.id', 'products.title', 'products.featured_image', 'products.permalink')
            ->orderBy('order_count', 'ASC')
            ->take(7)
            ->get();

        $sellingProducts = SellingProduct::with('product.productMeta')->get();
        $lessSellingProducts = LeastSellingProduct::get();

        $currentYear = date('Y');

        // Correctly format order data
        $orderData = Order::whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total_sales')
            ->groupBy('month')
            ->get();

        // Correctly format invoice data
        $invoiceData = Invoices::whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total_sales')
            ->groupBy('month')
            ->get();

        // Initialize arrays for all months
        $orderSales = array_fill(0, 12, 0);
        $posSales = array_fill(0, 12, 0);

        // Fill in available data
        foreach ($orderData as $data) {
            $orderSales[$data->month - 1] = floatval($data->total_sales);
        }

        foreach ($invoiceData as $data) {
            $posSales[$data->month - 1] = floatval($data->total_sales);
        }

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'orderSales' => $orderSales,
            'posSales' => $posSales,
        ];
        // Get sales by location (city and state)
        $salesByLocation = Order::select(
            'city',
            'state',
            \DB::raw('COUNT(*) as order_count'),
            \DB::raw('SUM(grand_total) as total_sales')
        )
            ->whereNotNull('city')
            ->whereNotNull('state')
            ->groupBy('city', 'state')
            ->orderBy('total_sales', 'DESC')

            ->get();

        // Prepare data for view
        $locationData = [];
        $totalLocationSales = 0;

        foreach ($salesByLocation as $location) {
            $locationName = $location->city . ', ' . $location->state;
            $totalLocationSales += $location->total_sales;

            // Get country code based on state (using US states since your data shows South Carolina)
            $countryCode = 'us';

            $locationData[] = [
                'location' => $locationName,
                'city' => $location->city,
                'state' => $location->state,
                'code' => $countryCode,
                'sales' => $location->total_sales,
                'orders' => $location->order_count
            ];
        }

        // New code for the consolidated dashboard chart
        $dashboardMetrics = [
            ['name' => 'All Orders', 'value' => $all_orders, 'color' => '#6c757d'],
            ['name' => 'Complete Orders', 'value' => $comp_orders, 'color' => '#0dcaf0'],
            ['name' => 'Inprogress Orders', 'value' => $inpro_orders, 'color' => '#ffc107'],
            ['name' => 'Pending Orders', 'value' => $pend_orders, 'color' => '#0d6efd'],
            ['name' => 'Cancelled Orders', 'value' => $canc_orders, 'color' => '#dc3545'],
            ['name' => 'Users', 'value' => $total_users, 'color' => '#0dcaf0'],
            ['name' => 'Discounts', 'value' => $discount, 'color' => '#0c7f1b'],
            ['name' => 'Product Brands', 'value' => $total_brands, 'color' => '#ffc107'],
            ['name' => 'Area Groups', 'value' => $total_areagroups, 'color' => '#0dcaf0'],
            ['name' => 'POS Orders', 'value' => $invoice_orders, 'color' => '#0d6efd'],
            ['name' => 'Online Store Orders', 'value' => $online_orders, 'color' => '#212529'],
        ];

        return view('Admin.dashboard', compact(
            'total_users',
            'total_products',
            'total_brands',
            'total_areagroups',
            'total_collections',
            'all_orders',
            'comp_orders',
            'pend_orders',
            'canc_orders',
            'inpro_orders',
            'online_orders',
            'customers',
            'invoice_orders',
            'orders',
            'topSellingProducts',
            'sellingProducts',
            'leastSellingProducts',
            'lessSellingProducts',
            'currentYear',
            'chartData',
            'salesByLocation',
            'totalLocationSales',
            'locationData',
            'dashboardMetrics',
            'refundOrder'
        ));
    }

    /**
     * Get filtered metrics data for dashboard chart
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilteredDashboardMetrics(Request $request)
    {
        $period = $request->period ?? 'today';

        $now = Carbon::now();
        $startDate = null;
        $endDate = $now;
        $applyDateFilter = true;

        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                break;
            case 'last_week':
                $startDate = $now->copy()->subWeek()->startOfWeek();
                $endDate = $now->copy()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                break;
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'last_6_months':
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                break;
            case 'last_year':
                $startDate = $now->copy()->subYear()->startOfYear();
                $endDate = $now->copy()->subYear()->endOfYear();
                break;
            case 'all':
                $applyDateFilter = false;
                break;
            default:
                $startDate = $now->copy()->startOfDay();
        }

        // Helper to apply conditional date filter
        $dateFilter = function ($query, $column = 'created_at') use ($applyDateFilter, $startDate, $endDate) {
            return $applyDateFilter
                ? $query->whereBetween($column, [$startDate, $endDate])
                : $query;
        };

        $total_users = $dateFilter(User::query())->count();
        $discount = $dateFilter(Discount::query())->count();
        $total_brands = $dateFilter(Brand::where('active', 1))->count();
        $total_areagroups = $dateFilter(AreaGroup::query())->count();
        $total_collections = $dateFilter(Collection::where('active', 1))->count();

        $comp_orders = $dateFilter(Order::where('status', 'completed'))->count();
        $pend_orders = $dateFilter(Order::where('status', 'pending'))->count();
        $canc_orders = $dateFilter(Order::where('status', 'cancelled'))->count();
        $inpro_orders = $dateFilter(Order::where('status', 'inProcess'))->count();
        $online_orders = $dateFilter(Order::where('admin_delete', '!=', 1))->count();
        $invoice_orders = $dateFilter(Invoices::where('active', 1))->count();

        // Sum of both
        $all_orders = $online_orders + $invoice_orders;


        $dashboardMetrics = [
            ['name' => 'All Orders', 'value' => $all_orders, 'color' => '#6c757d'],
            ['name' => 'Complete Orders', 'value' => $comp_orders, 'color' => '#0dcaf0'],
            ['name' => 'Inprogress Orders', 'value' => $inpro_orders, 'color' => '#ffc107'],
            ['name' => 'Pending Orders', 'value' => $pend_orders, 'color' => '#0d6efd'],
            ['name' => 'Cancelled Orders', 'value' => $canc_orders, 'color' => '#dc3545'],
            ['name' => 'Users', 'value' => $total_users, 'color' => '#0dcaf0'],
            ['name' => 'Discount', 'value' => $discount, 'color' => '#0c7f1b'],
            ['name' => 'Product Brands', 'value' => $total_brands, 'color' => '#ffc107'],
            ['name' => 'Area Groups', 'value' => $total_areagroups, 'color' => '#0dcaf0'],
            ['name' => 'POS Orders', 'value' => $invoice_orders, 'color' => '#0d6efd'],
            ['name' => 'Online Store Orders', 'value' => $online_orders, 'color' => '#212529'],
        ];

        return response()->json([
            'success' => true,
            'data' => $dashboardMetrics,
            'period' => $period,
            'date_range' => $applyDateFilter ? [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ] : null
        ]);
    }


    // Add this new function for CSV export (optional, as we're using JS for export)
    public function exportReport(Request $request)
    {
        $reportType = $request->type ?? 'sales';
        $filename = $reportType . '_report_' . date('Y-m-d') . '.csv';

        // Generate and return the CSV file
        return response()->streamDownload(function () use ($reportType) {
            $output = fopen('php://output', 'w');

            // Based on report type, add appropriate headers and data
            if ($reportType == 'sales') {
                // Sales report content
                fputcsv($output, ['Sales Summary']);
                // Add your data here
            } elseif ($reportType == 'products') {
                // Product report content
                fputcsv($output, ['Product Report']);
                // Add your data here
            } elseif ($reportType == 'customers') {
                // Customer report content
                fputcsv($output, ['Customer Report']);
                // Add your data here
            }

            fclose($output);
        }, $filename);
    }


    public function updateTopSellingProducts()
    {
        $topSellingProducts = Product::select(
            'products.id',
            \DB::raw('COUNT(order_metas.id) as order_count'),
            \DB::raw('SUM(order_metas.quantity) as total_quantity')
        )
            ->leftJoin('order_metas', 'products.id', '=', 'order_metas.product_id')
            ->groupBy('products.id')
            ->orderBy('order_count', 'DESC')
            ->take(7)
            ->get();

        SellingProduct::truncate();
        foreach ($topSellingProducts as $product) {
            SellingProduct::create([
                'product_id' => $product->id,
                'count' => $product->order_count,
                'total_quantity' => $product->total_quantity,
            ]);
        }

        return 'Top selling products updated successfully.';
    }

    public function updateLeastSellingProducts()
    {
        $leastSellingProducts = Product::select('products.id', 'products.title', 'products.featured_image', 'products.permalink', \DB::raw('COUNT(order_metas.id) as order_count'))
            ->leftJoin('order_metas', 'products.id', '=', 'order_metas.product_id')
            ->groupBy('products.id', 'products.title', 'products.featured_image', 'products.permalink')
            ->orderBy('order_count', 'ASC')
            ->take(7)
            ->get();
        LeastSellingProduct::truncate();
        foreach ($leastSellingProducts as $product) {
            LeastSellingProduct::create([
                'product_id' => $product->id,
                'title' => $product->title,
                'featured_image' => $product->featured_image,
                'permalink' => $product->permalink,
                'quantity' => $product->total_quantity,
                'order_count' => $product->order_count
            ]);
        }

        return 'Least selling products updated successfully.';
    }

    public function admin_profile()
    {
        $user = auth()->user();
        $roles = DB::table('roles')->get();
        return view('Admin.profile.profile', compact('user', 'roles'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation 
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['nullable', 'string', 'max:20'],
                'profile_image' => ['nullable', 'image', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            // Instead of custom response, let the exception handler manage this
            return back()->withErrors($e->errors())->withInput();
        }

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists and not the default image
            if ($user->profile_image && !str_contains($user->profile_image, 'default-profile.png')) {
                $oldImagePath = public_path($user->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Store new image in public folder
            $file = $request->file('profile_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('profile_images');

            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Move file to public directory
            $file->move($destinationPath, $filename);

            // Store relative path in database
            $user->profile_image = 'profile_images/' . $filename;
        }

        $user->save();
        return redirect()->back()->with(['success' => 'Profile updated successfully']);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => [
                    'required',
                    'string',
                    'min:8',  // Adding password strength requirements
                    'confirmed',
                ],
            ]);
        } catch (ValidationException $e) {
            // Using withErrors for better integration with blade template
            return back()->withErrors($e->errors())->withInput();
        }

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->back()->with(['success' => 'Password updated successfully']);
    }
}
