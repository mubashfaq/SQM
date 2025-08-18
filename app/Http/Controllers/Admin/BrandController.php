<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Storage;
use File;

class BrandController extends Controller
{
    public function create()
    {
        return view('Admin.Brands.create');
    }

    public function index()
    {
        $activeStatuses = [0, 1, 2];

        $brands = Brand::whereIn('active', $activeStatuses)
            ->latest() // sorts by created_at DESC
            ->get();

        return view('Admin.Brands.index', compact('brands'));
    }


    public function edit(Brand $brand)
    {
        return view('Admin.Brands.edit', compact('brand'));
    }
    public function store(Request $request)
    {
        // Transform permalink slug
        $request->merge([
            'permalink' => $this->transformSKU($request->input('permalink')),
        ]);

        // Validate request input
        $validated_data = $request->validate([
            'title' => 'required|string',
            'permalink' => 'required|string|unique:brands,permalink',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'required|integer',
            'files' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for editor files
        ]);

        $logged_in_user = Auth::user();
        $validated_data['created_by'] = $logged_in_user->id;

        // Make sure the 'brands' directory exists inside public
        if (!File::exists(public_path('brands'))) {
            File::makeDirectory(public_path('brands'), 0755, true);
        }

        // Make sure the 'brands/editor' directory exists inside public
        if (!File::exists(public_path('brands/editor'))) {
            File::makeDirectory(public_path('brands/editor'), 0755, true);
        }

        // Handle Logo Image - Save in original size
        if ($request->hasFile('logo_image')) {
            $image = $request->file('logo_image');
            $imageName = time() . '1.' . $image->getClientOriginalExtension();
            $savePath = public_path('brands/' . $imageName);

            // Move directly to final destination without resizing
            $image->move(public_path('brands'), $imageName);

            $validated_data['logo_image'] = 'brands/' . $imageName;
        }

        // Handle Cover Image - Save in original size
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $savePath = public_path('brands/' . $imageName);

            // Move directly to final destination without resizing
            $image->move(public_path('brands'), $imageName);

            $validated_data['cover_image'] = 'brands/' . $imageName;
        }

        // Handle Editor Images (from Summernote) - Save in original size
        $editorImagePath = null;
        if ($request->hasFile('files')) {
            $editorImage = $request->file('files');
            $editorImageName = time() . '_editor.' . $editorImage->getClientOriginalExtension();
            $editorSavePath = public_path('brands/editor/' . $editorImageName);

            if (!File::exists(public_path('brands/editor'))) {
                File::makeDirectory(public_path('brands/editor'), 0755, true);
            }

            // Move directly to final destination without resizing
            $editorImage->move(public_path('brands/editor'), $editorImageName);

            $editorImagePath = 'brands/editor/' . $editorImageName;
        }

        // Save Brand record
        $brand = Brand::create($validated_data);

        // SEO & Brand Meta fields
        $metaDescriptions = $request->only([
            '_seo_title',
            '_seo_tags',
            '_seo_description',
            '_canonical_url',
            '_schema_markup',
            '_alt',
            '_description',
            '_page_index'
        ]);

        $brandMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if (!empty($value)) {
                $brandMeta[] = [
                    'brand_id' => $brand->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }

        // Add editor image path to brand meta if it exists
        if ($editorImagePath) {
            $brandMeta[] = [
                'brand_id' => $brand->id,
                'option' => '_editor_image',
                'value' => $editorImagePath,
            ];
        }

        if (!empty($brandMeta)) {
            DB::table('brands_meta')->insert($brandMeta);
        }

        $brandPageIndex = $request->input('_page_index');
        if ($brandPageIndex != 3 && $brandPageIndex !== null) {
            // Get all active products for this category
            $products = $brand->activeProducts()->get();

            if ($products->count() > 0) {
                $productIds = $products->pluck('id')->toArray();

                // Log the product IDs for confirmation
                \Log::info('Updating page_index for products in category ' . $brand->id . ':', [
                    'category_page_index' => $brandPageIndex,
                    'product_ids' => $productIds,
                    'total_products' => count($productIds)
                ]);

                // Update or create page_index meta for each product
                foreach ($products as $product) {
                    \App\Models\ProductMeta::updateOrCreate(
                        ['product_id' => $product->id, 'option' => '_page_index'],
                        ['value' => $brandPageIndex],
                    );
                }

                \Log::info('Successfully updated page_index for ' . count($productIds) . ' products to value: ' . $brandPageIndex);
            } else {
                \Log::info('No active products found for category ' . $brand->id . ' to update page_index');
            }
        }

        if ($request->has('stay_on_page')) {
            return redirect()->back()->with('success', 'Brand saved. You stayed on the page.');
        }

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'permalink' => $this->transformSKU($request->input('permalink')),
        ]);

        // Validate request input
        $validatedData = $request->validate([
            'title' => 'required|string',
            'permalink' => 'required|string|unique:brands,permalink,' . $id,
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'required|integer',
            'files' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand = Brand::findOrFail($id);

        // Directories
        if (!File::exists(public_path('brands'))) {
            File::makeDirectory(public_path('brands'), 0755, true);
        }
        if (!File::exists(public_path('brands/editor'))) {
            File::makeDirectory(public_path('brands/editor'), 0755, true);
        }

        // Logo Image - Save in original size
        if ($request->hasFile('logo_image')) {
            $image = $request->file('logo_image');
            $imageName = time() . '1.' . $image->getClientOriginalExtension();

            // Move directly to final destination without resizing
            $image->move(public_path('brands'), $imageName);

            $validatedData['logo_image'] = 'brands/' . $imageName;
        }

        // Cover Image - Save in original size
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Move directly to final destination without resizing
            $image->move(public_path('brands'), $imageName);

            $validatedData['cover_image'] = 'brands/' . $imageName;
        }

        // Editor Image - Save in original size
        $editorImagePath = null;
        if ($request->hasFile('files')) {
            $editorImage = $request->file('files');
            $editorImageName = time() . '_editor.' . $editorImage->getClientOriginalExtension();

            // Move directly to final destination without resizing
            $editorImage->move(public_path('brands/editor'), $editorImageName);

            $editorImagePath = 'brands/editor/' . $editorImageName;
        }

        // Update brand
        $brand->update($validatedData);

        // Meta Fields
        $metaDescriptions = $request->only([
            '_seo_title',
            '_seo_tags',
            '_seo_description',
            '_canonical_url',
            '_schema_markup',
            '_alt',
            '_description',
            '_page_index',
        ]);

        $brandMeta = [];
        foreach ($metaDescriptions as $option => $value) {
            if (!empty($value)) {
                $brandMeta[] = [
                    'brand_id' => $brand->id,
                    'option' => $option,
                    'value' => $value,
                ];
            }
        }

        if ($editorImagePath) {
            $brandMeta[] = [
                'brand_id' => $brand->id,
                'option' => '_editor_image',
                'value' => $editorImagePath,
            ];
        }

        DB::table('brands_meta')->where('brand_id', $brand->id)->delete();
        if (!empty($brandMeta)) {
            DB::table('brands_meta')->insert($brandMeta);
        }

        $brand->save();
        $brandPageIndex = $request->input('_page_index');
        if ($brandPageIndex != 3 && $brandPageIndex !== null) {
            // Get all active products for this category
            $products = $brand->activeProducts()->get();
            if ($products->count() > 0) {
                $productIds = $products->pluck('id')->toArray();

                // Log the product IDs for confirmation
                \Log::info('Updating page_index for products in category ' . $brand->id . ':', [
                    'category_page_index' => $brandPageIndex,
                    'product_ids' => $productIds,
                    'total_products' => count($productIds)
                ]);

                // Update or create page_index meta for each product
                foreach ($products as $product) {
                    \App\Models\ProductMeta::updateOrCreate(
                        ['product_id' => $product->id, 'option' => '_page_index'],
                        ['value' => $brandPageIndex],
                    );
                }

                \Log::info('Successfully updated page_index for ' . count($productIds) . ' products to value: ' . $brandPageIndex);
            } else {
                \Log::info('No active products found for category ' . $brand->id . ' to update page_index');
            }
        }
        if ($request->has('stay_on_page')) {
            return redirect()->back()->with('success', 'Brand saved. You stayed on the page.');
        }
        return redirect()->route('brands.index', ['brand' => $brand])->with('success', 'Brand updated successfully.');
    }


    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return Redirect::back()->withErrors(['Brand not found.']);
        }
        $brand->delete();
        return redirect()->back()->with('success', 'Brand deleted successfully.');
    }

    public function updateStatus(Request $request, Brand $brand)
    {
        $request->validate([
            'active' => 'required',
        ]);

        $brand->active = $request->active;
        $brand->save();

        return response()->json([
            'status' => 200,
            'message' => 'Brand status updated successfully.',
        ]);
    }

    public function trash()
    {
        $brands = Brand::where('active', 3)->get();
        return view('Admin.Brands.trash', compact('brands'));
    }
    public function check_permalink(Request $request)
    {
        $request->validate([
            'permalink' => 'required|unique:brands,permalink',
        ]);
        return response()->json([
            'status' => true,
        ]);
    }
}
