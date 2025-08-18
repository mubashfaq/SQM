<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $activeStatuses = [0, 1, 2];
        $products = Product::whereIn('active', $activeStatuses)->with('productMeta')->paginate(30);
        return view('Admin.Products.inventory', compact('products'));
    }

    public function update(Request $request)
    {
        // Retrieve existing arrays from the request
        $stocks       = $request->input('_stock', []);
        $prices       = $request->input('_price', []);
        $status       = $request->input('active', []);
        
        // NEW: retrieve stock status array
        $stockStatuses = $request->input('_stock_status', []); // e.g. ['1' => 'in_stock', '2' => 'out_stock', ...]
    
        // Loop through each product ID in $status (or another array if you prefer)
        foreach ($status as $rowId => $row) {
            $product = Product::find($rowId);
            if ($product) {
                // Update product "active" status
                $product->update([
                    'active' => $row,
                ]);
    
                // Retrieve all meta records for this product
                $product_meta = ProductMeta::where('product_id', $product->id)->get();
    
                // Update _price meta
                $meta_price = $product_meta->where('option', '_price')->first();
                if ($meta_price) {
                    $meta_price->value = $prices[$rowId] ?? 0;
                    $meta_price->update();
                }
    
                // Update _stock meta
                // $meta_stock = $product_meta->where('option', '_stock')->first();
                // if ($meta_stock) {
                //     $meta_stock->value = $stocks[$rowId] ?? 0;
                //     $meta_stock->update();
                // }
    
                // Update _stock_status meta (In Stock / Out of Stock)
                $meta_stock_status = $product_meta->where('option', '_stock_status')->first();
                if ($meta_stock_status) {
                    // If already exists, just update its value
                    $meta_stock_status->value = $stockStatuses[$rowId] ?? 'out_stock';
                    $meta_stock_status->update();
                } else {
                    // If it doesn't exist, create a new one
                    ProductMeta::create([
                        'product_id' => $product->id,
                        'option'     => '_stock_status',
                        'value'      => $stockStatuses[$rowId] ?? 'out_stock',
                    ]);
                }
            }
        }
        
        return redirect()->back()->with(['success' => "Product's Inventory is updated Successfully"]);
    }
    

    public function search(Request $request)
    {
        $activeStatuses = [0, 1, 2];
        $query = Product::query()->leftjoin('products_meta', 'products.id', '=', 'products_meta.product_id')->select('products.*');
        if ($request->value) {
            $query->where('products.title', 'LIKE', '%' . $request->value . '%')
                ->orWhere('products.sku', 'LIKE', '%' . $request->value . '%')
                ->orWhere('products_meta.value', 'LIKE', '%' . $request->value . '%');
        }
        $products = $query->whereIn('active', $activeStatuses)->distinct()->with('productMeta')->paginate(30);
        // dd($products);
        if (!$products->isEmpty()) {
            return view('Admin.Products.ajax.filtered_inventory', compact('products'))->render();
        }
    }

    public function import_data(Request $request)
    {
        if ($request->hasFile('file')) {
            $logged_in_user = Auth::user();
            $file = $request->file('file');
            # Read and parse the CSV file
            $csvData = array_map('str_getcsv', file($file));
            # Define the expected columns
            $expectedColumns = [
                "PRODUCT ID", "PRODUCT", "SKU", "STOCK", "PRICE"
            ];
            # Remove the header row
            array_shift($csvData);
            foreach ($csvData as $row) {
                DB::table('products_meta')->where('product_id', $row[0] ?? 0)->where('option', '_stock')->update(['value' => $row[3] ?? null]);
                DB::table('products_meta')->where('product_id', $row[0] ?? 0)->where('option', '_price')->update(['value' => $row[4] ?? null]);
            }


            return redirect()->back()->with('success', 'CSV data imported successfully11.');
        } else {
            return redirect()->back()->with('success', 'CSV File not found');
        }
    }

    public function exportAllDataToCSV()
    {
        $activeStatuses = [0, 1, 2];
        $products = Product::whereIn('active', $activeStatuses)->with('productMeta')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="all_products.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            // Add the headers for each column
            fputcsv($file, ['Product ID', 'Product', 'SKU', 'Stock', 'Price']);

            foreach ($products as $product) {
                $price = $product->productMeta->where('option', '_price')->first()->value ?? '';
                $stock = $product->productMeta->where('option', '_stock_status')->first()->value ?? '';
                
                fputcsv($file, [
                    $product->id,
                    $product->title,
                    $product->sku,
                    $stock,
                    $price,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
