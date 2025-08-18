<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductType;
use Illuminate\Support\Facades\Session;

class ProductTypeController extends Controller
{
    public function index(){
        $products = ProductType::latest()->get();
        return view('Admin.ProductType.index', compact('products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'sku' => 'required',
        ]);
    
        if ($request->has('update_id') && !empty($request->update_id)) {
            // Update existing product type
            $producttype = ProductType::findOrFail($request->update_id);
            $producttype->title = $request->title;
            $producttype->sku = $request->sku;
            $producttype->save();
            
            // Prepare HTML for updated row
            // $html = view('admin.products.product_type_row', ['producttype' => $producttype])->render();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product Type updated successfully.',
                // 'updated_row' => $html
            ]);
        } else {
            // Create new product type
            $producttype = new ProductType();
            $producttype->title = $request->title;
            $producttype->sku = $request->sku;
            $producttype->save();
            
            // Prepare HTML for new row
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product Type added successfully.',
            ]);
        }
    }
    public function destroy(Request $request, ProductType $product_type)
    {

        $product_type->delete();

        return redirect()->route('product-type.index')->with(['success' => 'Product Type Delete Successfully ']);
    }

    public function show(ProductType $product_type)
    {
        return response()->json([
            'status' => true,
            'product_details' => $product_type,
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'sku' => 'required',
        ]);
        $producttype = ProductType::findOrFail($id);
        $producttype->title = $request->title;
        $producttype->sku = $request->sku;
        $producttype->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Product Type updated successfully.',
        ]);
    }

}
