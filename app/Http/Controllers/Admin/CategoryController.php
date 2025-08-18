<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\ChildCategoryMeta;
use App\Models\GrandChildCategory;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('childCategories');

        // Keyword filter
        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        // Date filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case '7days':
                    $query->where('created_at', '>=', Carbon::now()->subDays(7));
                    break;
                case '30days':
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                    break;
            }
        }

        $categories = $query->latest()->paginate(21)->withQueryString(); // keep filters on pagination
        return view('Admin.Category.index', compact('categories'));
    }

    public function get_child_category(Request $request)
    {
        $query = \DB::table('child_categories')
            ->join('categories', 'child_categories.main_parent_category', '=', 'categories.id')
            ->select('child_categories.*', 'categories.title as parent_title');

        // Keyword filter
        if ($request->filled('keyword')) {
            $query->where('child_categories.child_title', 'like', '%' . $request->keyword . '%');
        }

        // Date filters
        if ($request->filled('status')) {
            $today = now();
            switch ($request->status) {
                case 'today':
                    $query->whereDate('child_categories.created_at', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('child_categories.created_at', $today->subDay());
                    break;
                case '7days':
                    $query->whereDate('child_categories.created_at', '>=', now()->subDays(7));
                    break;
                case '30days':
                    $query->whereDate('child_categories.created_at', '>=', now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereMonth('child_categories.created_at', now()->month)
                        ->whereYear('child_categories.created_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('child_categories.created_at', now()->subMonth()->month)
                        ->whereYear('child_categories.created_at', now()->subMonth()->year);
                    break;
            }
        }

        // Parent category filter
        if ($request->filled('parent_category_id')) {
            $query->where('child_categories.main_parent_category', $request->parent_category_id);
        }

        $childCategories = $query->latest('child_categories.created_at')->get();
        $categories = Category::all();

        return view('Admin.ChildCategory.index', compact('childCategories', 'categories'));
    }


    public function get_category()
    {
        $categories = Category::orderByDesc('id')->get();
        return response()->json(['data' => $categories, 'status' => 'success']);
    }

   public function store(Request $request)
    {
        // Transform SKU before validation
        $request->merge([
            'sku' => $this->transformSKU($request->input('sku')),
        ]);
        
        $isUpdate = $request->filled('category_id');
        $categoryId = $request->input('category_id');
        
        $rules = [
            'title' => 'required|string',
            'sku' => [
                'required',
                'string',
                $isUpdate
                    ? Rule::unique('categories', 'sku')->ignore($categoryId)
                    : Rule::unique('categories', 'sku')
            ],
            // Make images required only for create, optional for update
            'thumbnail_image' => $isUpdate 
                ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                : 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        
        $validated_data = $request->validate($rules);
        
        // Ensure directories exist
        Storage::makeDirectory('public/categories');
        $tempDir = public_path('temp');
        $saveDir = public_path('categories');
        
        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }
        if (!File::isDirectory($saveDir)) {
            File::makeDirectory($saveDir, 0755, true);
        }
        
        $manager = new ImageManager(new GdDriver());
        
        // Process thumbnail image only if uploaded
        if ($request->hasFile('thumbnail_image')) {
            $image = $request->file('thumbnail_image');
            $imageName = time() . '_thumb.' . $image->getClientOriginalExtension();
            $tempPath = $tempDir . '/' . $imageName;
            $savePath = $saveDir . '/' . $imageName;

            $image->move($tempDir, $imageName);
            $imageProcessor = $manager->read($tempPath);
            $imageProcessor->scale(800, 500)->toJpeg()->save($savePath);
            unlink($tempPath);

            $validated_data['thumbnail_image'] = 'categories/' . $imageName;
        } else {
            // Remove thumbnail_image from validated_data if not uploaded during update
            if ($isUpdate) {
                unset($validated_data['thumbnail_image']);
            }
        }
        
        // Process cover image only if uploaded
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_cover.' . $image->getClientOriginalExtension();
            $tempPath = $tempDir . '/' . $imageName;
            $savePath = $saveDir . '/' . $imageName;
            
            $image->move($tempDir, $imageName);
            $imageProcessor = $manager->read($tempPath);
            $imageProcessor->scale(1920, 700)->toJpeg()->save($savePath);
            unlink($tempPath);
            
            $validated_data['cover_image'] = 'categories/' . $imageName;
        } else {
            // Remove cover_image from validated_data if not uploaded
            unset($validated_data['cover_image']);
        }
        
        $editorDir = public_path('categories/editor');
        if (!is_dir($editorDir)) {
            mkdir($editorDir, 0755, true);
        }
        
        // Process Summernote base64 images inside description
        $contentField = '_description';
        $htmlContent = $request->input($contentField, '');
        $result = $this->saveBase64ImagesFromContent($htmlContent, $editorDir);
        $updatedContent = $result['content'];
        $firstEditorImage = $result['first_image_path']; // null if none found
        
        // Save or update category
        if ($isUpdate) {
            $category = Category::findOrFail($categoryId);
            $category->update($validated_data);
            $message = 'Category updated successfully.';
        } else {
            $category = Category::create($validated_data);
            $message = 'Category created successfully.';
        }
        
        // Prepare SEO meta options including the updated content and first editor image
        $seoOptions = [
            '_seo_title' => $request->input('_seo_title'),
            '_seo_tags' => $request->input('_seo_tags'),
            '_seo_description' => $request->input('_seo_description'),
            '_canonical_url' => $request->input('_canonical_url'),
            '_schema_markup' => $request->input('_schema_markup'),
            '_description' => $updatedContent,
            'editor_image' => $firstEditorImage,
            '_page_index' => $request->input('_page_index'),
        ];
        
        if ($request->input('_all_product_index') != '3') {
            $seoOptions['_all_product_index'] = $request->input('_all_product_index');
        }
        
        // Save all metas to category_metas table
        foreach ($seoOptions as $option => $value) {
            if ($value !== null) {
                \App\Models\CategoryMeta::updateOrCreate(
                    ['category_id' => $category->id, 'option' => $option],
                    ['value' => $value]
                );
            }
        }
        
        $categoryPageIndex = $request->input('_all_product_index');
        if ($categoryPageIndex != 3 && $categoryPageIndex !== null) {
            // Get all active products for this category
            $products = $category->activeProducts()->get();
            $category->load(['childCategories.grandChildCategories']);
            
            // Update products page_index
            if ($products->count() > 0) {
                $productIds = $products->pluck('id')->toArray();
                // Log the product IDs for confirmation
                \Log::info('Updating page_index for products in category ' . $category->id . ':', [
                    'category_page_index' => $categoryPageIndex,
                    'product_ids' => $productIds,
                    'total_products' => count($productIds)
                ]);
                
                // Update or create page_index meta for each product
                foreach ($products as $product) {
                    \App\Models\ProductMeta::updateOrCreate(
                        ['product_id' => $product->id, 'option' => '_page_index'],
                        ['value' => $categoryPageIndex],
                    );
                }
                
                \Log::info('Successfully updated page_index for ' . count($productIds) . ' products to value: ' . $categoryPageIndex);
            } else {
                \Log::info('No active products found for category ' . $category->id . ' to update page_index');
            }
            
            // Update child categories page_index
            if ($category->childCategories->count() > 0) {
                $childCategoryIds = $category->childCategories->pluck('id')->toArray();
                \Log::info('Updating page_index for child categories in category ' . $category->id . ':', [
                    'category_page_index' => $categoryPageIndex,
                    'child_category_ids' => $childCategoryIds,
                    'total_child_categories' => count($childCategoryIds)
                ]);
                
                // Update or create page_index meta for each child category
                foreach ($category->childCategories as $childCategory) {
                    \App\Models\ChildCategoryMeta::updateOrCreate(
                        ['child_category_id' => $childCategory->id, 'option' => '_page_index'],
                        ['value' => $categoryPageIndex]
                    );

                    \App\Models\ChildCategoryMeta::updateOrCreate(
                        ['child_category_id' => $childCategory->id, 'option' => '_all_product_index'],
                        ['value' => $categoryPageIndex]
                    );
                }

                \Log::info('Successfully updated page_index for ' . count($childCategoryIds) . ' child categories to value: ' . $categoryPageIndex);
            } else {
                \Log::info('No child categories found for category ' . $category->id . ' to update page_index');
            }
            
            // Update grandchild categories page_index
            $allGrandChildCategories = $category->childCategories->pluck('grandChildCategories')->flatten();
            if ($allGrandChildCategories->count() > 0) {
                $grandChildCategoryIds = $allGrandChildCategories->pluck('id')->toArray();
                \Log::info('Updating page_index for grandchild categories in category ' . $category->id . ':', [
                    'category_page_index' => $categoryPageIndex,
                    'grandchild_category_ids' => $grandChildCategoryIds,
                    'total_grandchild_categories' => count($grandChildCategoryIds)
                ]);
                
                // Update or create page_index meta for each grandchild category
                foreach ($allGrandChildCategories as $grandChildCategory) {
                    \App\Models\GrandChildCategoryMeta::updateOrCreate(
                        ['grand_child_category_id' => $grandChildCategory->id, 'option' => '_page_index'],
                        ['value' => $categoryPageIndex],
                    );
                }
                
                \Log::info('Successfully updated page_index for ' . count($grandChildCategoryIds) . ' grandchild categories to value: ' . $categoryPageIndex);
            } else {
                \Log::info('No grandchild categories found for category ' . $category->id . ' to update page_index');
            }
        }
        
        return redirect()->route('category.index')->with('success', $message);
    }

    private function saveBase64ImagesFromContent($html, $saveDir)
    {
        if (empty($html)) {
            return ['content' => $html, 'first_image_path' => null];
        }
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();
        $images = $dom->getElementsByTagName('img');
        $firstImagePath = null;
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                $imageType = $type[1];
                $base64Data = substr($src, strpos($src, ',') + 1);
                $base64Data = str_replace(' ', '+', $base64Data);
                $imageData = base64_decode($base64Data);

                if ($imageData === false) {
                    continue;
                }
                $fileName = 'editor_' . \Illuminate\Support\Str::random(10) . '.' . $imageType;
                $filePath = $saveDir . DIRECTORY_SEPARATOR . $fileName;
                file_put_contents($filePath, $imageData);
                $imageUrl = 'categories/editor/' . $fileName;
                $img->setAttribute('src', $imageUrl);
                // Save first image path only once
                if ($firstImagePath === null) {
                    $firstImagePath = $imageUrl;
                }
            }
        }
        $images = $dom->getElementsByTagName('img');
        // Because getElementsByTagName returns a live NodeList,
        // and we want to remove nodes safely, iterate backward:
        for ($i = $images->length - 1; $i >= 0; $i--) {
            $img = $images->item($i);
            $img->parentNode->removeChild($img);
        }
        // Now get the body content without any <img> tags
        $body = $dom->getElementsByTagName('body')->item(0);
        $newContent = '';
        foreach ($body->childNodes as $child) {
            $newContent .= $dom->saveHTML($child);
        }
        return ['content' => $newContent, 'first_image_path' => $firstImagePath];
    }


    public function delete(Request $request)
    {
        $id = $request->id;
        $category = Category::find($id);
        if ($category) {
            // Delete thumbnail image if it exists
            if (!empty($category->thumbnail_image) && File::exists(public_path($category->thumbnail_image))) {
                File::delete(public_path($category->thumbnail_image));
            }

            // Delete cover image if it exists
            if (!empty($category->cover_image) && File::exists(public_path($category->cover_image))) {
                File::delete(public_path($category->cover_image));
            }

            // Delete all related category metas
            \App\Models\CategoryMeta::where('category_id', $category->id)->delete();

            // Delete the category
            $category->delete();

            return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
        }

        return redirect()->route('category.index')->with('error', 'Something went wrong.');
    }

    public function edit_category(Request $request)
    {
        $id = $request->id;
        $cat = Category::with('categoryMeta')->find($id);

        return response()->json($cat, 200, [], JSON_UNESCAPED_SLASHES);
    }
    public function show_category_detail(Request $request)
    {
        $category = Category::with('childCategories')->find($request->id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json([
            'id' => $category->id,
            'title' => $category->title,
            'thumbnail_image' => $category->thumbnail_image,
            'cover_image' => $category->cover_image,
            'child_categories' => $category->childCategories
        ]);
    }
    public function update_category(Request $request, $id)
    {
        $request->merge([
            'sku' => $this->transformSKU($request->input('sku')),
        ]);
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'sku' => 'required|string|unique:categories,sku,' . $id,
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }
        // Find the category to update
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found.'], 404);
        }
        // Initialize an empty array to hold the image data
        $validated_data = [];
        // Process and update thumbnail image
        if ($request->hasFile('thumbnail_image')) {
            $thumbnailImage = $request->file('thumbnail_image');
            $thumbnailImageName = time() . '.' . $thumbnailImage->getClientOriginalExtension();
            $thumbnailImage->move(public_path('categories'), $thumbnailImageName);
            $validated_data['thumbnail_image'] = 'categories/' . $thumbnailImageName;
        }
        // Process and update cover image
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '.' . $coverImage->getClientOriginalExtension();
            $coverImage->move(public_path('categories'), $coverImageName);
            $validated_data['cover_image'] = 'categories/' . $coverImageName;
        }
        // If neither thumbnail_image nor cover_image was uploaded, do not include it in the update
        if (!$request->hasFile('thumbnail_image') && !$request->hasFile('cover_image')) {
            $category->update($validator->validated());
        } else {
            // Update the category with validated data (including images)
            $category->update(array_merge($validator->validated(), $validated_data));
        }
        return response()->json(['status' => 'success', 'message' => 'Category updated successfully.']);
    }

    // child category functions
    public function store_child_category(Request $request)
    {
        $request->merge([
            'child_sku' => $this->transformSKU($request->input('child_sku')),
        ]);
        $isUpdate = $request->filled('child_category_id');
        // Validate input data
        $validatedData = $request->validate([
            'main_parent_category' => 'required|integer',
            'child_sku' => [
                'required',
                'string',
                $isUpdate
                    ? Rule::unique('child_categories', 'child_sku')->ignore($request->child_category_id)
                    : 'unique:child_categories,child_sku'
            ],
            'child_title' => 'required|string',
            '_seo_title' => 'nullable|string|max:255',
            '_seo_tags' => 'nullable|string',
            '_seo_description' => 'nullable|string',
            '_canonical_url' => 'nullable|url',
            '_schema_markup' => 'nullable|string',
            '_description' => 'nullable|string',
            'files' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Create or update the main child category
        if ($isUpdate) {
            $childCategory = ChildCategory::find($request->child_category_id);
            if (!$childCategory) {
                return redirect()->route('child.category.index')->with('error', 'Child category not found.');
            }
            $childCategory->update([
                'main_parent_category' => $validatedData['main_parent_category'],
                'child_sku' => $validatedData['child_sku'],
                'child_title' => $validatedData['child_title'],
            ]);
            $message = 'Child Category Updated Successfully.';
        } else {
            $childCategory = ChildCategory::create([
                'main_parent_category' => $validatedData['main_parent_category'],
                'child_sku' => $validatedData['child_sku'],
                'child_title' => $validatedData['child_title'],
            ]);
            $message = 'Child Category Added Successfully.';
        }
        // Prepare directory for saving extracted base64 images
        $editorDir = public_path('categories/editor');
        if (!is_dir($editorDir)) {
            mkdir($editorDir, 0755, true);
        }
        // Extract images from content and get updated content + first image path
        $contentField = '_description';
        $htmlContent = $request->input($contentField, '');
        $result = $this->saveBase64ImagesFromContent($htmlContent, $editorDir);
        $updatedContent = $result['content'];          // updated HTML with images saved
        $firstEditorImage = $result['first_image_path']; // path or null if none
        // Map request SEO fields to meta options keys
        $seoOptions = [
            '_seo_title' => $request->input('_seo_title'),
            '_seo_tags' => $request->input('_seo_tags'),
            '_seo_description' => $request->input('_seo_description'),
            '_canonical_url' => $request->input('_canonical_url'),
            '_schema_markup' => $request->input('_schema_markup'),
            '_description' => $updatedContent,
            'editor_image' => $firstEditorImage,
            '_page_index' => $request->input('_page_index'),

        ];
        if ($request->input('_all_product_index') != '3') {
            $seoOptions['_all_product_index'] = $request->input('_all_product_index');
        }
        // Save all SEO and content meta to child_category_metas table
        foreach ($seoOptions as $option => $value) {
            if ($value !== null) {
                \App\Models\ChildCategoryMeta::updateOrCreate(
                    [
                        'child_category_id' => $childCategory->id,
                        'option' => $option,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
        $categoryPageIndex = $request->input('_all_product_index');
        if ($categoryPageIndex != 3 && $categoryPageIndex !== null) {
            $products = $childCategory->activeProducts()->get();
            if ($products->count() > 0) {
                $productIds = $products->pluck('id')->toArray();
                // Log the product IDs for confirmation
                \Log::info('Updating page_index for products in category ' . $childCategory->id . ':', [
                    'category_page_index' => $categoryPageIndex,
                    'product_ids' => $productIds,
                    'total_products' => count($productIds)
                ]);
                // Update or create page_index meta for each product
                foreach ($products as $product) {
                    \App\Models\ProductMeta::updateOrCreate(
                        ['product_id' => $product->id, 'option' => '_page_index'],
                        ['value' => $categoryPageIndex],
                    );
                }
                \Log::info('Successfully updated page_index for ' . count($productIds) . ' products to value: ' . $categoryPageIndex);
            } else {
                \Log::info('No active products found for category ' . $childCategory->id . ' to update page_index');
            }

            if ($childCategory->grandChildCategories->count() > 0) {
                $grandChildCategoryIds = $childCategory->grandChildCategories->pluck('id')->toArray();
                \Log::info('Updating page_index for grandchild categories in child category ' . $childCategory->id . ':', [
                    'category_page_index' => $categoryPageIndex,
                    'grandchild_category_ids' => $grandChildCategoryIds,
                    'total_grandchild_categories' => count($grandChildCategoryIds)
                ]);
                // Update or create page_index meta for each grandchild category
                foreach ($childCategory->grandChildCategories as $grandChildCategory) {
                    \App\Models\GrandChildCategoryMeta::updateOrCreate(
                        ['grand_child_category_id' => $grandChildCategory->id, 'option' => '_page_index'],
                        ['value' => $categoryPageIndex],
                    );
                }
                \Log::info('Successfully updated page_index for ' . count($grandChildCategoryIds) . ' grandchild categories to value: ' . $categoryPageIndex);
            } else {
                \Log::info('No grandchild categories found for child category ' . $childCategory->id . ' to update page_index');
            }
        }
        return redirect()->route('child.category.index')->with('success', $message);
    }


    public function delete_child_catgory(Request $request)
    {
        $category = ChildCategory::find($request->child_category);
        if ($category) {
            // Delete related metas first
            \App\Models\ChildCategoryMeta::where('child_category_id', $category->id)->delete();
            $category->delete();
            return redirect()->route('child.category.index')->with('success', 'Child Category deleted successfully.');
        }
        return redirect()->route('child.category.index')->with('error', 'Something went wrong.');
    }

    public function edit_child_category(Request $request)
    {
        $id = $request->get('id');
        $childCategory = ChildCategory::with('childCategoryMeta')
            ->join('categories', 'categories.id', '=', 'child_categories.main_parent_category')
            ->select(
                'categories.title as parent_title',
                'child_categories.id',
                'child_categories.child_title',
                'child_categories.child_sku',
                'child_categories.main_parent_category',
                'child_categories.created_at',
                'child_categories.updated_at'
            )
            ->where('child_categories.id', $id)
            ->first();
        return response()->json($childCategory, 200, [], JSON_UNESCAPED_SLASHES);
    }


    public function update_child_category(Request $request, $id)
    {
        $request->merge([
            'child_sku' => $this->transformSKU($request->input('child_sku')),
        ]);
        $validatedData = $request->validate([
            'main_parent_category' => 'required|integer',
            'child_title' => 'required|string',
            'child_sku' => 'required|string|unique:child_categories,child_sku,' . $id,
        ]);
        $childCategory = ChildCategory::findOrFail($id);
        $childCategory->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Child Category Updated Successfully.']);
    }
    public function show_child_detail(Request $request)
    {
        $id = $request->id;
        $child = Category::join('child_categories', 'categories.id', '=', 'child_categories.main_parent_category')
            ->select('categories.title', 'child_categories.*')
            ->where('child_categories.id', $id)
            ->get();
        return response()->json($child);
    }

    // Grand child category functions

    public function store_grandchild_category(Request $request)
    {
        $request->merge([
            'grand_sku' => $this->transformSKU($request->input('grand_sku')),
        ]);

        $isUpdate = $request->filled('grand_child_category_id');

        // Add SEO & description validation if needed, or adjust as required
        $validatedData = $request->validate([
            'mid_parent_category' => 'required|integer',
            'grand_sku' => [
                'required',
                'string',
                $isUpdate
                    ? Rule::unique('grand_child_categories', 'grand_sku')->ignore($request->grand_child_category_id)
                    : 'unique:grand_child_categories,grand_sku'
            ],
            'grand_title' => 'required|string',

            // Optional SEO fields (add if you want to save them too)
            '_seo_title' => 'nullable|string|max:255',
            '_seo_tags' => 'nullable|string',
            '_seo_description' => 'nullable|string',
            '_canonical_url' => 'nullable|url',
            '_schema_markup' => 'nullable|string',
            '_description' => 'nullable|string',
            'files' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create or update the main grand child category
        if ($isUpdate) {
            $grandChildCategory = GrandChildCategory::find($request->grand_child_category_id);
            if (!$grandChildCategory) {
                return redirect()->route('grandchild.category.index')->with('error', 'Grand Child category not found.');
            }
            $grandChildCategory->update([
                'mid_parent_category' => $validatedData['mid_parent_category'],
                'grand_sku' => $validatedData['grand_sku'],
                'grand_title' => $validatedData['grand_title'],
            ]);
            $message = 'Grand Child Category Updated Successfully.';
        } else {
            $grandChildCategory = GrandChildCategory::create([
                'mid_parent_category' => $validatedData['mid_parent_category'],
                'grand_sku' => $validatedData['grand_sku'],
                'grand_title' => $validatedData['grand_title'],
            ]);
            $message = 'Grand Child Category Added Successfully.';
        }

        // Prepare directory for saving extracted base64 images
        $editorDir = public_path('categories/editor');
        if (!is_dir($editorDir)) {
            mkdir($editorDir, 0755, true);
        }

        // Extract images from content and get updated content + first image path
        $contentField = '_description';
        $htmlContent = $request->input($contentField, '');

        $result = $this->saveBase64ImagesFromContent($htmlContent, $editorDir);

        $updatedContent = $result['content'];          // updated HTML with images saved
        $firstEditorImage = $result['first_image_path']; // path or null if none

        // Map request SEO fields to meta options keys
        $seoOptions = [
            '_seo_title' => $request->input('_seo_title'),
            '_seo_tags' => $request->input('_seo_tags'),
            '_seo_description' => $request->input('_seo_description'),
            '_canonical_url' => $request->input('_canonical_url'),
            '_schema_markup' => $request->input('_schema_markup'),
            '_description' => $updatedContent,
            'editor_image' => $firstEditorImage,
            '_page_index' => $request->input('_page_index'),
        ];
        if ($request->input('_all_product_index') != '3') {
            $seoOptions['_all_product_index'] = $request->input('_all_product_index');
        }

        // Save all SEO and content meta to grand_child_category_metas table
        foreach ($seoOptions as $option => $value) {
            if ($value !== null) {
                \App\Models\GrandChildCategoryMeta::updateOrCreate(
                    [
                        'grand_child_category_id' => $grandChildCategory->id,
                        'option' => $option,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }

        $categoryPageIndex = $request->input('_all_product_index');
        if ($categoryPageIndex != 3 && $categoryPageIndex !== null) {
            // Get all active products for this category
            $products = $grandChildCategory->activeProducts()->get();

            if ($products->count() > 0) {
                $productIds = $products->pluck('id')->toArray();

                // Log the product IDs for confirmation
                \Log::info('Updating page_index for products in category ' . $grandChildCategory->id . ':', [
                    'category_page_index' => $categoryPageIndex,
                    'product_ids' => $productIds,
                    'total_products' => count($productIds)
                ]);

                // Update or create page_index meta for each product
                foreach ($products as $product) {
                    \App\Models\ProductMeta::updateOrCreate(
                        ['product_id' => $product->id, 'option' => '_page_index'],
                        ['value' => $categoryPageIndex],
                    );
                }

                \Log::info('Successfully updated page_index for ' . count($productIds) . ' products to value: ' . $categoryPageIndex);
            } else {
                \Log::info('No active products found for category ' . $grandChildCategory->id . ' to update page_index');
            }
        }

        return redirect()->route('grandchild.category.index')->with('success', $message);
    }


    public function get_grandchild_category(Request $request)
    {
        $query = \DB::table('grand_child_categories')
            ->join('child_categories', 'grand_child_categories.mid_parent_category', '=', 'child_categories.id')
            ->select(
                'child_categories.child_title as parent_child_title',
                'grand_child_categories.id',
                'grand_child_categories.grand_title',
                'grand_child_categories.grand_sku',
                'grand_child_categories.mid_parent_category',
                'grand_child_categories.created_at',
                'grand_child_categories.updated_at'
            );

        // Keyword filter
        if ($request->filled('keyword')) {
            $query->where('grand_child_categories.grand_title', 'like', '%' . $request->keyword . '%');
        }

        // Parent category filter
        if ($request->filled('parent_category_id')) {
            $query->where('grand_child_categories.mid_parent_category', $request->parent_category_id);
        }

        // Date filters
        if ($request->filled('status')) {
            $now = \Carbon\Carbon::now();
            switch ($request->status) {
                case 'today':
                    $query->whereDate('grand_child_categories.created_at', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('grand_child_categories.created_at', $now->subDay()->toDateString());
                    break;
                case '7days':
                    $query->where('grand_child_categories.created_at', '>=', $now->subDays(7));
                    break;
                case '30days':
                    $query->where('grand_child_categories.created_at', '>=', $now->subDays(30));
                    break;
                case 'this_month':
                    $query->whereMonth('grand_child_categories.created_at', $now->month)
                        ->whereYear('grand_child_categories.created_at', $now->year);
                    break;
                case 'last_month':
                    $lastMonth = $now->subMonth();
                    $query->whereMonth('grand_child_categories.created_at', $lastMonth->month)
                        ->whereYear('grand_child_categories.created_at', $lastMonth->year);
                    break;
            }
        }

        $categories = $query->orderByDesc('grand_child_categories.id')->get();
        $childCategories = ChildCategory::all();

        return view('Admin.GrandChildCategory.index', compact('categories', 'childCategories'));
    }

    public function delete_grandchild_catgory(Request $request)
    {
        $id = $request->grand_child_category;
        $category = GrandChildCategory::find($id);
        if ($category) {
            $category->delete(); // Delete the record
            return redirect()->route('grandchild.category.index')->with('success', 'Grand Child Category deleted successfully.');
        } else {
            return redirect()->route('grandchild.category.index')->with('error', 'Something when Wrong.');
        }
    }
    public function edit_grandchild_category(Request $request)
    {
        $id = $request->id;
        $chil_category_data = GrandChildCategory::with('grandChildCategoryMeta')->join('child_categories', 'child_categories.id', '=', 'grand_child_categories.mid_parent_category')
            ->select('child_categories.child_title', 'grand_child_categories.*')
            ->where('grand_child_categories.id', $id)
            ->get();
        return response()->json($chil_category_data, 200, [], JSON_UNESCAPED_SLASHES);
    }
    public function update_grandchild_category(Request $request, $id)
    {
        $request->merge([
            'grand_sku' => $this->transformSKU($request->input('grand_sku')),
        ]);
        $validatedData = $request->validate([
            'mid_parent_category' => 'required|integer',
            'grand_title' => 'required|string',
            'grand_sku' => 'required|string|unique:child_categories,child_sku,' . $id,
        ]);
        $childCategory = GrandChildCategory::findOrFail($id);
        $childCategory->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Child Category Updated Successfully.']);
    }
    public function show_grandchild_detail(Request $request)
    {
        $id = $request->id;
        $grandchild = GrandChildCategory::join('child_categories', 'child_categories.id', '=', 'grand_child_categories.mid_parent_category')
            ->select('child_categories.child_title', 'grand_child_categories.*')
            ->where('grand_child_categories.id', $id)
            ->get();
        return response()->json($grandchild);
    }
}
