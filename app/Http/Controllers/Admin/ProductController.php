<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\AreaGroup;
use App\Models\GroupsMeta;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\GrandChildCategory;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Discount;
use DB;
use Intervention\Image\ImageManager;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ProductController extends Controller
{
    public function index()
    {
       $categories = Category::all();
       $activeStatuses = [0, 1, 2];
       $products = Product::whereIn('active', $activeStatuses)->orderBy('id','desc')->paginate(15);
       $producttypes = ProductType::all();
       return view('Admin.Products.index', compact('products','categories','producttypes'));
    }

    public function create()
    {
        $categories = Category::all();
        $childcats = ChildCategory::all();
        $grandcats = GrandChildCategory::all();
        $producttypes = ProductType::all();
        $areagroups = AreaGroup::all();
        $groupmetas = GroupsMeta::all();
        $brands = Brand::where('active', 1)->orderBy('created_at', 'desc')->get();
        $discounts = Discount::where('active', 1)->where('discount_for',1)->orderBy('created_at', 'desc')->get();
       return view('Admin.Products.create', compact('categories','childcats','grandcats','producttypes','areagroups','groupmetas','brands','discounts'));
    }

    public function edit(Product $product)
    {

        $categories = Category::all();
        $childcats = ChildCategory::all();
        $grandcats = GrandChildCategory::all();
        $producttypes = ProductType::all();
        $areagroups = AreaGroup::all();
        $groupmetas = GroupsMeta::all();
        $brands = Brand::where('active', 1)->orderBy('created_at', 'desc')->get();
        $discounts = Discount::where('active', 1)->where('discount_for',1)->orderBy('created_at', 'desc')->get();
       return view('Admin.Products.edit', compact('product','categories','childcats','grandcats','producttypes','areagroups','groupmetas','brands','discounts'));
    }

    public function check_permalink(Request $request)
    {
        $request->validate([
            'permalink' => 'required|unique:products,permalink',
        ]);
        return response()->json([
           'status' => true,
        ]);
    }

    public function store(Request $request)
    {
        $logged_in_user = Auth::user();
        DB::table('product_temp_variation')->where('created_by', $logged_in_user->id)->delete();

        $validatedData = $request->validate([
            'title' => 'required|string',
            'permalink' => 'required|string|unique:products,permalink',
            'sku' => 'required|string|unique:products,sku',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'product_type' => 'nullable|integer',
            'active' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'child_category' => 'nullable|integer',
            'grand_child_category' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'brand_id' => 'nullable|string',
            'discount_id' => 'nullable|string',
            'area_group_id' => 'nullable|array',
            'area_group_id.*' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_media.*' => 'nullable|image|max:2048',
            'variant' => 'nullable|string',
        ]);

        $logged_in_user = Auth::user();
        $validated_data['created_by'] = $logged_in_user->id;

        if ($request->filled('_price')) {
            $validatedData['categories'] = implode(',', $request->input('categories', []));
        }

        $validatedData['area_group_id'] = implode(',', $request->input('area_group_id', []));
        # Store the featured image
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
        
            // Generate a unique image name
            $imageName = time() . '.' . $image->getClientOriginalExtension();
        
            // Move the image to a temporary location
            $image->move(public_path('temp'), $imageName);
        
            // Optimize the image using Intervention Image v3
            $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $optimizedImage = $manager->read(public_path('temp') . '/' . $imageName)
                ->resize(720, 800, function ($constraint) {
                    $constraint->aspectRatio(); // Maintain aspect ratio
                    $constraint->upsize();      // Prevent upsizing
                })
                ->toJpeg();
        
            // Store the optimized image in the products folder
            $optimizedImage->save(public_path('products') . '/' . $imageName);
        
            // Remove the temporary image
            unlink(public_path('temp') . '/' . $imageName);
        
            // Set the image path in the validated data
            $validatedData['featured_image'] = 'products/' . $imageName;
        } else {
            // If no file is uploaded, use the default image path
            $validatedData['featured_image'] = 'default/default.jpg';
        }
        

        if ($request->hasFile('gallery_media')) {
            $images = $request->file('gallery_media');
            $galleryMedia = [];

            foreach ($images as $image) {
                # Generate a unique image name
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                # Move the image to a temporary location
                $image->move(public_path('temp'), $imageName);

                # Optimize the image using Intervention Image v3
                $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $optimizedImage = $manager->read(public_path('temp') . '/' . $imageName)
                    ->resize(720, 800, function ($constraint) {
                        $constraint->aspectRatio(); // Maintain aspect ratio
                        $constraint->upsize(); // Prevent upsizing
                    })
                    ->toJpeg();

                # Store the optimized image in the products folder
                $optimizedImage->save(public_path('products') . '/' . $imageName);

                # Remove the temporary image
                unlink(public_path('temp') . '/' . $imageName);

                # Add the image path to the gallery media array
                $galleryMedia[] = 'products/' . $imageName;
            }

            $validatedData['gallery_media'] = implode(',', $galleryMedia);
            Session::put('gallery_media', $validatedData['gallery_media']);
        }

        # Store the product using the validated data
        $product = Product::create($validatedData);

        $metaDescriptions = $request->only([
            '_price',
            '_sale',
            '_sale_price',
            '_seo_title',
            '_seo_tags',
            '_seo_description',
            '_stock_status',
            '_alt',
            '_virtual',
            '_upc',
            '_weight',
            '_is_weight',
            '_gst',
            '_buy_price',
        ]);

        $productMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $productMeta[] = [
                    'product_id' => $product->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }

        # Insert product meta descriptions in bulk
        DB::table('products_meta')->insert($productMeta);

        # Return a success response or perform additional actions
        return redirect()->route('products.create')->with(['success' => 'Product created successfully!']);
    }


    public function addProductVariation(Request $request)
    {
        $validatedData = $request->validate([
            'gallery_media.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'featuredImage' => 'required|integer',
        ]);

        $logged_in_user = Auth::user();
        $validatedData['created_by'] = $logged_in_user->id;

        if ($request->hasFile('gallery_media')) {
            $images = $request->file('gallery_media');
            $galleryMedia = [];

            foreach ($images as $image) {
                # Generate a unique image name
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                # Move the image to a temporary location
                $image->move(public_path('temp'), $imageName);

                # Optimize the image using Intervention Image
                $manager = new ImageManager();
                $optimizedImage = $manager->make(public_path('temp') . '/' . $imageName)
                    ->fit(720, 800) // Adjust the dimensions as needed
                    ->encode();

                # Store the optimized image in the products folder
                $optimizedImage->save(public_path('products') . '/' . $imageName);

                # Remove the temporary image
                unlink(public_path('temp') . '/' . $imageName);

                # Add the image path to the gallery media array
                $galleryMedia[] = 'products/' . $imageName;
            }

            $validatedData['gallery_media'] = implode(',', $galleryMedia);
        }

        DB::table('product_temp_variation')->insert($validatedData);

        // Return a JSON response indicating success
        return response()->json(['message' => 'featured Images created successfully!'], 200);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $logged_in_user = Auth::user();
        DB::table('product_temp_variation')->where('created_by', $logged_in_user->id)->delete();

        $validatedData = $request->validate([
            'title' => 'required|string',
            'permalink' => 'required|string|unique:products,permalink,' . $id,
            'sku' => 'required|string|unique:products,sku,' . $id,
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'product_type' => 'nullable|integer',
            'active' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'child_category' => 'nullable|integer',
            'grand_child_category' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'area_group_id' => 'array',
            'brand_id' => 'nullable|string',
            'discount_id' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_media.*' => 'nullable|image|max:2048',
            'variant' => 'nullable|string',
        ]);

        if ($request->filled('_price')) {
            $validatedData['categories'] = implode(',', $request->input('categories', []));
        }

        $validatedData['area_group_id'] = implode(',', $request->input('area_group_id', []));

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('temp'), $imageName);

            // Optimize the image using Intervention Image v3
            $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $optimizedImage = $manager->read(public_path('temp') . '/' . $imageName)
                ->resize(720, 800, function ($constraint) {
                    $constraint->aspectRatio(); 
                    $constraint->upsize(); 
                })
                ->toJpeg();

            $optimizedImage->save(public_path('products') . '/' . $imageName);

            unlink(public_path('temp') . '/' . $imageName);

            $validatedData['featured_image'] = 'products/' . $imageName;
        }

        $product = Product::findOrFail($id);

        if ($request->hasFile('gallery_media')) {
            $images = $request->file('gallery_media');
            $galleryMedia = [];
        
            foreach ($images as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        
                // Move the image to a temporary location
                $image->move(public_path('temp'), $imageName);
        
                // Optimize the image using Intervention Image v3
                $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $optimizedImage = $manager->read(public_path('temp') . '/' . $imageName)
                    ->resize(720, 800, function ($constraint) {
                        $constraint->aspectRatio(); // Maintain aspect ratio
                        $constraint->upsize(); // Prevent upsizing
                    })
                    ->toJpeg();
        
                // Save the optimized image in the products folder
                $optimizedImage->save(public_path('products') . '/' . $imageName);
        
                // Remove the temporary image
                unlink(public_path('temp') . '/' . $imageName);
        
                // Add the new image path to the gallery media array
                $galleryMedia[] = 'products/' . $imageName;
            }
        
            // If there are already images in the product's gallery_media, append the new ones.
            if ($product->gallery_media) {
                $validatedData['gallery_media'] = $product->gallery_media . ',' . implode(',', $galleryMedia);
            } else {
                $validatedData['gallery_media'] = implode(',', $galleryMedia);
            }
        }
        
        // Update the product using the validated data (including merged gallery_media)
        $product->update($validatedData);

        $metaDescriptions = $request->only([
            '_price',
            '_sale',
            '_sale_price',
            '_seo_title',
            '_seo_tags',
            '_seo_description',
            '_stock',
            '_stock_status',
            '_alt',
            '_virtual',
            '_upc',
            '_weight',
            '_is_weight',
            '_gst',
            '_buy_price',
        ]);

        // dd($metaDescriptions);
        $productMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if ($value !== null) {
                $productMeta[] = [
                    'product_id' => $product->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }

        # Delete previous product meta descriptions
        DB::table('products_meta')->where('product_id', $product->id)->delete();

        # Insert updated product meta descriptions in bulk
        DB::table('products_meta')->insert($productMeta);

        # Return a success response or perform additional actions
        return redirect()->route('products.edit', $product->id)->with(['success' => 'Product updated successfully!']);
    }
    

    public function destroy(Product $product)
    {
        if(DB::table('order_metas')->where('product_id', $product->id)->count()){
    return redirect()->back()->withErrors(['error' => 'The product cannot be deleted as it has already been ordered']);
}


        $imagePath = public_path($product->featured_image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        DB::table('products_meta')->where('product_id', $product->id)->delete();
        DB::table('selling_products')->where('product_id', $product->id)->delete();
        $product->delete();
        return redirect()->route('products.index')->with(['success' => 'Product deleted successfully.']);
    }

    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'active' => 'required',
        ]);

        $product->active = $request->active;
        $product->save();

        return response()->json([
            'status' => 200,
            'message' => 'Product status updated successfully.',
        ]);
    }

    public function trash()
    {
       $categories = Category::all();
       $products = Product::where('active',3)->paginate(5);
       $producttypes = ProductType::all();
       return view('Admin.Products.trash', compact('products','categories','producttypes'));
    }

    public function import(Request $request)
    {
        # Validate the request
        $request->validate([
          'file' => 'required|file',
        ]);
        $logged_in_user = Auth::user();
        $permalinks = Product::pluck('permalink')->toArray();
        # Get the uploaded CSV file
        $file = $request->file('file');

        # Read and parse the CSV file
        $csvData = array_map('str_getcsv', file($file));
        # Define the expected columns
        $expectedColumns = [
            "title","permalink","sku","short_description","long_description","product_type","active","category_id","child_category","grand_child_category","brand_id","featured_image","gallery_media","_stock_status","_buy_price","_price","_seo_title","_seo_tags","_seo_description","_alt","_virtual","_upc","_weight","_gst"
        ];

       # Check if the CSV contains the expected columns
        $missingColumns = array_diff($expectedColumns, $csvData[0]);
        if (count($missingColumns) > 0) {
            $missingColumnsList = implode(', ', $missingColumns);
            dd($missingColumnsList);
            return redirect()->back()->with('error', 'The CSV file is missing the following required columns: ' . $missingColumnsList);
        }
   


        # Remove the header row
        array_shift($csvData);
        $previous_id = 0;
        $i = 1;
        # Loop through the CSV data and insert into the database
        foreach ($csvData as $row) {
            # Prepare product data with handling empty fields
            $productData = [
                'title' => $row[0] ?? null,
                'permalink' => $this->generatePermalink($row[0], $permalinks),
                'sku' => $row[2] ?? null,
                'short_description' => $row[3] ?? null,
                'long_description' => $row[4] ?? null,
                'product_type' => $row[5] ?? null,
                'active' => $row[6] ?? 0,
                'category_id' => $row[7] ?? null,
                'child_category' => $row[8] ?? null,
                'grand_child_category' => $row[9] ?? null,
                'created_by' => $logged_in_user->id,
                'brand_id' => $row[10] ?? null,
                'featured_image' => $row[11] ?? null,
                'gallery_media' => $row[12] ?? null,
            ];
            # Insert into the 'products' table
            $product = Product::create($productData);
            if(!$product){
                 return redirect()->back()->with('error', 'Product Not Added, Last Product Id:'.$previous_id.' | Error Counted Row: '.$i);
            }
            $i++;
            $previous_id = $product->id;
            # Insert product meta data if available in the CSV
            $productMetaData = [
                [
                    'product_id' => $product->id,
                    'option' => '_stock_status',
                    'value' => $row[13],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_buy_price',
                    'value' => $row[14],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_price',
                    'value' => $row[15],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_seo_title',
                    'value' => $row[16],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_seo_tags',
                    'value' => $row[17],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_seo_description',
                    'value' => $row[18],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_alt',
                    'value' => $row[19],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_virtual',
                    'value' => $row[20],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_upc',
                    'value' => $row[21],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_weight',
                    'value' => $row[22],
                ],
                [
                    'product_id' => $product->id,
                    'option' => '_gst',
                    'value' => $row[23] ?? '0',
                ],
                # Add other meta data as needed
            ];
            DB::table('products_meta')->insert($productMetaData);
        }

        return redirect()->back()->with('success', 'CSV data imported successfully.');
    }
    private function generatePermalink($title, $existingPermalinks)
    {
        // Replace any special characters with hyphens
        $permalink = preg_replace('/[^a-zA-Z0-9\s]/', '-', $title);
        $permalink = preg_replace('/\s+/', '-', $permalink);
        $permalink = trim($permalink, '-');

        // Check if the permalink already exists, and append a unique identifier if needed
        $counter = 1;
        while (in_array($permalink, $existingPermalinks)) {
            $permalink = $permalink . '-' . $counter;
            $counter++;
        }

        // Add the new permalink to the list of existing permalinks
        $existingPermalinks[] = $permalink;

        return $permalink;
    }

    public function productSearch(Request $request)
    {
        // Gather user inputs
        $title       = $request->product_name;
        $sku         = $request->sku;
        $productType = $request->product_type;
        $stockStatus = $request->stock_status;
        $category    = $request->category;
    
        $activeStatuses = [0, 1, 2];
        
        // Base query
        $query = Product::whereIn('active', $activeStatuses)
                        ->orderBy('id','desc');
        
        // Search by title
        if (!empty($title)) {
            $query->where('title', 'LIKE', "%{$title}%");
        }
        
        // Search by SKU
        if (!empty($sku)) {
            $query->where('sku', 'LIKE', "%{$sku}%");
        }
    
        // Search by product type (via relationship)
        // Assuming your Product model has something like:
        //    public function productType() {
        //        return $this->belongsTo(ProductType::class);
        //    }
        if (!empty($productType)) {
            $query->whereHas('productType', function ($q) use ($productType) {
                // Adjust column name as appropriate. If "title" is the column in
                // your product_types table, you can match by it:
                $q->where('id', 'LIKE', "%{$productType}%");
            });
        }
    
        // Filter by category (assuming a "category" relationship)
        if (!empty($category)) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('title', 'LIKE', "%{$category}%");
            });
        }
    
        // Filter by stock status (assuming it is in productMeta)
        if (!empty($stockStatus)) {
            $query->whereHas('productMeta', function ($q) use ($stockStatus) {
                $q->where('option', '_stock_status')
                  ->where('value', $stockStatus);
            });
        }
    
        // Save these in session if needed
        session([
            'product_search_params' => [
                'title'         => $title,
                'sku'           => $sku,
                'product_type'  => $productType,
            ],
            'product_stock_status' => $stockStatus,
            'product_category'     => $category
        ]);
    
        // Paginate & append queries
        $products     = $query->paginate(15)->appends(request()->query());
        $producttypes = ProductType::all();
        $categories   = Category::all();
    
        return view('Admin.Products.index', compact(
            'products',
            'categories',
            'producttypes',
            'title',
            'sku',
            'productType',
            'stockStatus'
        ));
    }

    public function trash_productSearch(Request $request)
    {
        // Gather user inputs
        $title       = $request->product_name;
        $sku         = $request->sku;
        $productType = $request->product_type;
        $stockStatus = $request->stock_status;
        $category    = $request->category;
    
        $activeStatuses = [3];
        
        // Base query
        $query = Product::whereIn('active', $activeStatuses)
                        ->orderBy('id','desc');
        
        // Search by title
        if (!empty($title)) {
            $query->where('title', 'LIKE', "%{$title}%");
        }
        
        // Search by SKU
        if (!empty($sku)) {
            $query->where('sku', 'LIKE', "%{$sku}%");
        }
    
        // Search by product type (via relationship)
        // Assuming your Product model has something like:
        //    public function productType() {
        //        return $this->belongsTo(ProductType::class);
        //    }
        if (!empty($productType)) {
            $query->whereHas('productType', function ($q) use ($productType) {
                // Adjust column name as appropriate. If "title" is the column in
                // your product_types table, you can match by it:
                $q->where('id', 'LIKE', "%{$productType}%");
            });
        }
    
        // Filter by category (assuming a "category" relationship)
        if (!empty($category)) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('title', 'LIKE', "%{$category}%");
            });
        }
    
        // Filter by stock status (assuming it is in productMeta)
        if (!empty($stockStatus)) {
            $query->whereHas('productMeta', function ($q) use ($stockStatus) {
                $q->where('option', '_stock_status')
                  ->where('value', $stockStatus);
            });
        }
    
        // Save these in session if needed
        session([
            'product_search_params' => [
                'title'         => $title,
                'sku'           => $sku,
                'product_type'  => $productType,
            ],
            'product_stock_status' => $stockStatus,
            'product_category'     => $category
        ]);
    
        // Paginate & append queries
        $products     = $query->paginate(15)->appends(request()->query());
        $producttypes = ProductType::all();
        $categories   = Category::all();
    
        return view('Admin.Products.trash', compact(
            'products',
            'categories',
            'producttypes',
            'title',
            'sku',
            'productType',
            'stockStatus'
        ));
    }
    
    public function deleteGalleryImage(Request $request)
    {
        // Get image path and product id from request
        $imagePath = $request->input('image');
        $productId = $request->input('product_id');
    
        // Validate input
        if (!$imagePath || !$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing image path or product id.'
            ]);
        }
    
        // Find the product record
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }
    
        // Convert the comma-separated gallery_media field to an array
        $galleryMedia = $product->gallery_media ? explode(',', $product->gallery_media) : [];
        // Check if the image exists in the gallery array
        if (!in_array($imagePath, $galleryMedia)) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found in product gallery.'
            ]);
        }
    
        // Remove the image from the array
        $galleryMedia = array_filter($galleryMedia, function($img) use ($imagePath) {
            return $img !== $imagePath;
        });
    
        // Update the product record with the new gallery media list
        $product->gallery_media = implode(',', $galleryMedia);
        $product->save();
    
        // Optionally, delete the image file from the server if it exists
        $filePath = public_path($imagePath);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }
    

}
