<?php

namespace App\Http\Controllers\Admin;

use App\Models\Collection;
use App\Models\Product;
use App\Models\CollectionProduct;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use File;;

class CollectionController extends Controller
{
    public function create()
    {
        $discounts = Discount::where('active', 1)
            ->where('discount_for', 2)
            ->orderBy('created_at', 'desc')->get();
        return view('Admin.Collections.create', compact('discounts'));
    }
    public function index()
    {
        $activeStatuses = [0, 1, 2];
        $collections = Collection::whereIn('active', $activeStatuses)->latest()->get();
        return view('Admin.Collections.index', compact('collections'));
    }
    public function edit(Collection $collection)
    {
        session(['collection_id' => $collection->id]);
        $products = Product::where('active', 1)
            ->whereNotIn('id', function ($query) use ($collection) {
                $query->select('product_id')
                    ->from('collection_products')
                    ->where('collection_id', $collection->id);
            })
            ->take(50)
            ->get();
        $discounts = Discount::where('active', 1)
            ->where('discount_for', 2)
            ->orderBy('created_at', 'desc')->get();
        $collectionproducts = DB::table('collection_products')
            ->join('products', 'collection_products.product_id', '=', 'products.id')->where('collection_products.collection_id', $collection->id)
            ->select('products.title', 'products.featured_image', 'products.category_id', 'collection_products.active', 'collection_products.id')
            ->get();
        $categories = Category::all();
        return view('Admin.Collections.edit', compact('collection', 'products', 'collectionproducts', 'categories', 'discounts'));
    }
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'title' => 'required|string',
            'permalink' => 'required|string|unique:collections,permalink',
            'description' => 'nullable|string',
            'discount_id' => 'nullable',
            'active' => 'required|integer',
        ]);
        $logged_in_user = Auth::user();
        $validated_data['created_by'] = $logged_in_user->id;

        $collection = Collection::create($validated_data);
        $metaDescriptions = $request->only([
            '_seo_title',
            '_seo_tags',
            '_seo_description',
            '_alt',
        ]);
        $brandMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $brandMeta[] = [
                    'collection_id' => $collection->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }
        DB::table('collections_meta')->insert($brandMeta);

        return redirect()->route('collections.index')->with('success', 'Collection created successfully.');
    }
    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'title' => 'required|string',
            'permalink' => 'required|string|unique:collections,permalink,' . $id,
            'description' => 'nullable|string',
            'discount_id' => 'nullable',
            'active' => 'required|integer',
        ]);
        $collection = Collection::find($id);
        if (!$collection) {
            return  redirect()->back()->withErrors(['Collection not found.']);
        }
        $collection->update($validatedData);
        $metaDescriptions = $request->only([
            '_seo_title',
            '_seo_tags',
            '_seo_description',
            '_alt',
        ]);
        $brandMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $brandMeta[] = [
                    'collection_id' => $collection->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }
        DB::table('collections_meta')->where('collection_id', $collection->id)->delete();
        DB::table('collections_meta')->insert($brandMeta);
        $collection->save();
        return redirect()->route('collections.index')->with('success', 'collection upated successfully.');
    }
    public function destroy($id)
    {
        $collection = Collection::find($id);
        if (!$collection) {
            return Redirect::back()->withErrors(['Collection not found.']);
        }

        // Delete all records in collection_products where collection_id matches $id
        CollectionProduct::where('collection_id', $collection->id)->delete();

        $collection->delete();
        return redirect()->route('collections.index')->with('success', 'Collection deleted successfully.');
    }

    public function updateStatus(Request $request, Collection $collection)
    {
        $request->validate([
            'active' => 'required',
        ]);

        $collection->active = $request->active;
        $collection->save();

        return response()->json([
            'status' => 200,
            'message' => 'status updated successfully.',
        ]);
    }

    public function trash()
    {
        $collections = Collection::where('active', 3)->get();
        return view('Admin.Collections.trash', compact('collections'));
    }

    public function store_collectionproduct(Request $request)
    {
        $validated_data = $request->validate([
            'collection_id' => 'required|integer',
            'checked_ids' => 'required|array',
            'checked_ids.*' => 'integer',
            'discount_id' => 'integer|nullable',
        ]);
        $addedCount = 0;
        $alreadyAddedProducts = [];
        $productsWithDiscount = [];
        $collectionId = $validated_data['collection_id'];
        $isGeneralCollection = $validated_data['discount_id'] == 0;
        foreach ($validated_data['checked_ids'] as $productId) {
            $existingEntry = CollectionProduct::where('product_id', $productId)
                ->where('collection_id', $collectionId)
                ->first();
            if ($existingEntry) {
                $alreadyAddedProducts[] = $productId;
                continue;
            }
            if ($isGeneralCollection) {
                $existingGeneralEntry = CollectionProduct::where('product_id', $productId)
                    ->whereHas('collection', function ($query) {
                        $query->where('discount_id', 0);
                    })
                    ->first();
                if ($existingGeneralEntry) {
                    $alreadyAddedProducts[] = $productId;
                    continue;
                }
            } else {
                $product = Product::find($productId);
                if ($product->discount_id != 0) {
                    $productsWithDiscount[] = $productId;
                    continue;
                }
                $existingDiscountedEntry = CollectionProduct::where('product_id', $productId)
                    ->whereHas('collection', function ($query) {
                        $query->where('discount_id', '!=', 0);
                    })
                    ->first();
                if ($existingDiscountedEntry) {
                    $alreadyAddedProducts[] = $productId;
                    continue;
                }
            }
            CollectionProduct::create([
                'collection_id' => $collectionId,
                'product_id' => $productId,
            ]);
            $addedCount++;
        }
        $productDetails = [];
        foreach ($alreadyAddedProducts as $productId) {
            $product = Product::find($productId);
            $collection = CollectionProduct::where('product_id', $productId)->first()->collection;
            $productDetails[] = ['sku' => $product->sku, 'collection_title' => $collection->title];
        }
        $discountedProductDetails = [];
        foreach ($productsWithDiscount as $productId) {
            $product = Product::find($productId);
            $discountedProductDetails[] = ['sku' => $product->sku];
        }
        $response = [];
        if ($addedCount > 0) {
            $response['message'] = 'products_added';
            $response['added_count'] = $addedCount;
        }
        if (!empty($productDetails)) {
            $response['already_existing_products'] = $productDetails;
        }
        if (!empty($discountedProductDetails)) {
            $response['products_with_discounts'] = $discountedProductDetails;
        }
        if (empty($response)) {
            $response['message'] = 'no_products_added';
        }
        return response()->json($response);
    }



    public function destroy_prodcut($id)
    {
        try {
            $collectprodut = CollectionProduct::findOrFail($id);
            $collectprodut->delete();

            return response()->json(['message' => 'Collection Product deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the product.'], 500);
        }
    }

    public function update_collectproduct_Status(Request $request, $id)
    {
        $request->validate([
            'active' => 'required|in:0,1',
        ]);

        $collectionProduct = CollectionProduct::findOrFail($id);

        $collectionProduct->active = $request->input('active');
        $collectionProduct->save();

        return response()->json(['message' => 'Status updated successfully.']);
    }

    public function searchProducts(Request $request)
    {
        $collectionId = session('collection_id');
        $collection = Collection::find($collectionId);
        if (!$collection) {
            return response()->json(['error' => 'Collection not found'], 404);
        }
        $searchTerm = $request->input('search');
        $searchResults = Product::where('active', 1)
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->whereNotIn('id', function ($query) use ($collection) {
                $query->select('product_id')
                    ->from('collection_products')
                    ->where('collection_id', $collection->id);
            })->take(15)->get();
        return response()->json([
            'searchResults' => $searchResults,
            'collection' => $collection
        ]);
    }

    public function fetch_product(Request $request, Collection $collection)
    {
        $perPage = 30;
        $getproducts = Product::where('active', 1)
            ->whereNotIn('id', function ($query) use ($collection) {
                $query->select('product_id')
                    ->from('collection_products')
                    ->where('collection_id', $collection->id);
            })->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'products' => $getproducts->items(),
                'pagination' => [
                    'current_page' => $getproducts->currentPage(),
                    'last_page' => $getproducts->lastPage(),
                    'per_page' => $getproducts->perPage(),
                ],
            ]);
        }
    }
}
