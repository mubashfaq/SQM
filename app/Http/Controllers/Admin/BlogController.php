<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ChildCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class BlogController extends Controller
{
     public function index(Request $request)
    {
        $query = Blog::with(['categories', 'childCategories', 'grandChildCategories', 'brands'])
        ->where('active', '!=', 3);;

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status);
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Apply brand filter
        if ($request->filled('brand')) {
            $query->whereHas('brands', function ($q) use ($request) {
                $q->where('brands.id', $request->brand);
            });
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply date range filter
        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            $now = Carbon::now();

            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        $now->startOfWeek()->toDateTimeString(),
                        $now->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', $now->month)
                        ->whereYear('created_at', $now->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', $now->year);
                    break;
            }
        }

        $blogs = $query->orderBy('created_at', 'desc')->get();

        // Get all categories and brands for filter dropdowns
        $allCategories = Blog::with('categories')
            ->get()
            ->pluck('categories')
            ->flatten()
            ->unique('id')
            ->sortBy('title');

        $allBrands = Blog::with('brands')
            ->get()
            ->pluck('brands')
            ->flatten()
            ->unique('id')
            ->sortBy('title');

        return view('Admin.Blog.index', compact('blogs', 'allCategories', 'allBrands'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        // Don't load all child categories initially - they'll be loaded via AJAX
        return view('Admin.Blog.create')->with([
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    // Add these new methods for AJAX requests
    public function getChildCategories($categoryId)
    {
        $childCategories = ChildCategory::where('main_parent_category', $categoryId)->get();
        return response()->json($childCategories);
    }

    public function getGrandChildCategories($childCategoryId)
    {
        $grandChildCategories = GrandChildCategory::where('mid_parent_category', $childCategoryId)->get();
        return response()->json($grandChildCategories);
    }


    public function store(Request $request)
    {
        // CRITICAL FIX: Filter out empty string values from Select2 arrays BEFORE validation
        $arrayFields = ['category_ids', 'brand_ids', 'child_category_ids', 'grand_child_category_ids'];

        foreach ($arrayFields as $field) {
            if ($request->has($field) && is_array($request->input($field))) {
                // Filter out empty strings and null values
                $filtered = array_filter($request->input($field), function ($value) {
                    return $value !== '' && $value !== null && $value !== 'null';
                });

                // Reset array keys and convert to null if empty
                $filtered = array_values($filtered);
                $request->merge([$field => empty($filtered) ? null : $filtered]);
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'permalink' => 'required|string|unique:blogs,permalink',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'brand_ids' => 'nullable|array',
            'brand_ids.*' => 'integer|exists:brands,id',
            'child_category_ids' => 'nullable|array',
            'child_category_ids.*' => 'integer|exists:child_categories,id',
            'grand_child_category_ids' => 'nullable|array',
            'grand_child_category_ids.*' => 'integer|exists:grand_child_categories,id',
            'active' => 'required|integer|in:0,1,2,3',
            'fimage_alt_text' => 'nullable|string',
            '_seo_title' => 'nullable|string|max:255',
            '_seo_tags' => 'nullable|string',
            '_seo_description' => 'nullable|string',
            '_canonical_url' => 'nullable|url',
            '_schema_markup' => 'nullable|string',
            '_page_index' => 'nullable|integer',

        ]);

        $data = $request->except(['cover_image', 'featured_images', 'category_ids', 'brand_ids', 'child_category_ids', 'grand_child_category_ids']);
        $data['created_by'] = Auth::id();

        // Define paths inside public folder
        $coverDir = public_path('blogs/covers');
        $featuredDir = public_path('blogs/featured');

        // Make sure directories exist, if not create them
        if (!File::exists($coverDir)) {
            File::makeDirectory($coverDir, 0755, true);
        }
        if (!File::exists($featuredDir)) {
            File::makeDirectory($featuredDir, 0755, true);
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = 'blog_cover_' . time() . '_' . uniqid() . '.' . $coverImage->getClientOriginalExtension();
            $coverImage->move($coverDir, $coverImageName);
            $data['cover_image'] = 'blogs/covers/' . $coverImageName;
        }

        // Handle featured images upload
        $featuredImagePaths = [];
        if ($request->hasFile('featured_images')) {
            foreach ($request->file('featured_images') as $index => $image) {
                $imageName = 'blog_featured_' . time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($featuredDir, $imageName);
                $featuredImagePaths[] = 'blogs/featured/' . $imageName;
            }
        }
        $data['featured_images'] = implode(',', $featuredImagePaths);

        // Create the blog
        $blog = Blog::create($data);

        // Attach categories, brands, etc. with improved filtering
        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $validCategoryIds = array_filter($request->category_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
            if (!empty($validCategoryIds)) {
                $blog->categories()->attach($validCategoryIds);
            }
        }

        if ($request->has('brand_ids') && is_array($request->brand_ids)) {
            $validBrandIds = array_filter($request->brand_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
            if (!empty($validBrandIds)) {
                $blog->brands()->attach($validBrandIds);
            }
        }

        if ($request->has('child_category_ids') && is_array($request->child_category_ids)) {
            $validChildCategoryIds = array_filter($request->child_category_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
            if (!empty($validChildCategoryIds)) {
                $blog->childCategories()->attach($validChildCategoryIds);
            }
        }

        if ($request->has('grand_child_category_ids') && is_array($request->grand_child_category_ids)) {
            $validGrandChildCategoryIds = array_filter($request->grand_child_category_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
            if (!empty($validGrandChildCategoryIds)) {
                $blog->grandChildCategories()->attach($validGrandChildCategoryIds);
            }
        }

        $message = 'Blog created successfully.';

        if ($request->has('stay_on_page')) {
            return redirect()->route('blogs.create')->with('success', $message);
        }

        return redirect()->route('blogs.index')->with('success', $message);
    }

    public function show(Blog $blog)
    {
        $blog->load(['categories', 'childCategories', 'grandChildCategories', 'brands']);
        return view('Admin.Blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $blog->load(['categories', 'childCategories', 'grandChildCategories', 'brands']);

        $categories = Category::all();
        $brands = Brand::all();

        return view('Admin.Blog.edit', compact('blog', 'categories', 'brands'));
    }
    public function update(Request $request, Blog $blog)
    {
        // CRITICAL FIX: Filter out empty string values from Select2 arrays BEFORE validation
        $arrayFields = ['category_ids', 'brand_ids', 'child_category_ids', 'grand_child_category_ids'];

        foreach ($arrayFields as $field) {
            if ($request->has($field) && is_array($request->input($field))) {
                // Filter out empty strings and null values
                $filtered = array_filter($request->input($field), function ($value) {
                    return $value !== '' && $value !== null && $value !== 'null';
                });

                // Reset array keys and convert to null if empty
                $filtered = array_values($filtered);
                $request->merge([$field => empty($filtered) ? null : $filtered]);
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'permalink' => 'required|string|unique:blogs,permalink,' . $blog->id,
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'brand_ids' => 'nullable|array',
            'brand_ids.*' => 'integer|exists:brands,id',
            'child_category_ids' => 'nullable|array',
            'child_category_ids.*' => 'integer|exists:child_categories,id',
            'grand_child_category_ids' => 'nullable|array',
            'grand_child_category_ids.*' => 'integer|exists:grand_child_categories,id',
            'fimage_alt_text' => 'nullable|string',
            'active' => 'required|integer|in:0,1,2,3',
            '_seo_title' => 'nullable|string|max:255',
            '_seo_tags' => 'nullable|string',
            '_seo_description' => 'nullable|string',
            '_canonical_url' => 'nullable|url',
            '_schema_markup' => 'nullable|string',
            '_page_index' => 'nullable|integer',
        ]);

        $data = $request->except(['cover_image', 'featured_images', 'category_ids', 'brand_ids', 'child_category_ids', 'grand_child_category_ids']);
        $data['updated_by'] = Auth::id();

        // Define directories inside public
        $coverDir = public_path('blogs/covers');
        $featuredDir = public_path('blogs/featured');

        // Create directories if not exist
        if (!File::exists($coverDir)) {
            File::makeDirectory($coverDir, 0755, true);
        }
        if (!File::exists($featuredDir)) {
            File::makeDirectory($featuredDir, 0755, true);
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($blog->cover_image && file_exists(public_path($blog->cover_image))) {
                unlink(public_path($blog->cover_image));
            }

            $coverImage = $request->file('cover_image');
            $coverImageName = 'blog_cover_' . time() . '_' . uniqid() . '.' . $coverImage->getClientOriginalExtension();
            $coverImage->move($coverDir, $coverImageName);
            $data['cover_image'] = 'blogs/covers/' . $coverImageName;
        }

        // Handle featured images upload
        if ($request->hasFile('featured_images')) {
            // Delete old featured images
            $oldFeaturedImages = $blog->featured_images_array ?? [];
            foreach ($oldFeaturedImages as $oldImage) {
                if (file_exists(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }
            }

            $featuredImagePaths = [];
            foreach ($request->file('featured_images') as $index => $image) {
                $imageName = 'blog_featured_' . time() . '_' . $index . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($featuredDir, $imageName);
                $featuredImagePaths[] = 'blogs/featured/' . $imageName;
            }
            $data['featured_images'] = implode(',', $featuredImagePaths);
        }

        // Update the blog
        $blog->update($data);

        // Sync categories, brands, etc. with improved filtering
        // Filter and sync categories
        $categoryIds = [];
        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $categoryIds = array_filter($request->category_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
        }
        $blog->categories()->sync($categoryIds);

        // Filter and sync brands
        $brandIds = [];
        if ($request->has('brand_ids') && is_array($request->brand_ids)) {
            $brandIds = array_filter($request->brand_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
        }
        $blog->brands()->sync($brandIds);

        // Filter and sync child categories
        $childCategoryIds = [];
        if ($request->has('child_category_ids') && is_array($request->child_category_ids)) {
            $childCategoryIds = array_filter($request->child_category_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
        }
        $blog->childCategories()->sync($childCategoryIds);

        // Filter and sync grand child categories
        $grandChildCategoryIds = [];
        if ($request->has('grand_child_category_ids') && is_array($request->grand_child_category_ids)) {
            $grandChildCategoryIds = array_filter($request->grand_child_category_ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
        }
        $blog->grandChildCategories()->sync($grandChildCategoryIds);

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        // Delete associated images
        if ($blog->cover_image && file_exists(public_path($blog->cover_image))) {
            unlink(public_path($blog->cover_image));
        }

        $featuredImages = $blog->featured_images_array;
        foreach ($featuredImages as $image) {
            if (file_exists(public_path($image))) {
                unlink(public_path($image));
            }
        }

        // Delete pivot table relationships (will be handled by cascade)
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }

    public function updateStatus(Request $request, Blog $blog)
    {
        $request->validate([
            'active' => 'required|integer|in:0,1,2,3'
        ]);

        $blog->update([
            'active' => $request->active,
            'updated_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog status updated successfully.'
        ]);
    }

    public function trash()
    {
        $blogs = Blog::with(['categories', 'childCategories', 'grandChildCategories', 'brands'])
            ->where('active', 3)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Admin.Blog.trash', compact('blogs'));
    }

    public function restore($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->update([
            'active' => 0, // Restore as draft
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('blogs.trash')->with('success', 'Blog restored successfully.');
    }

    public function checkPermalink(Request $request)
    {
        $permalink = $request->permalink;
        $id = $request->id ?? null;

        $query = Blog::where('permalink', $permalink);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        $exists = $query->exists();

        if ($exists || empty($permalink)) {
            return response()->json(['error' => 'Permalink already exists or is empty'], 422);
        }

        return response()->json(['success' => 'Permalink is available']);
    }

    // Additional helper methods for filtering
    public function filterByCategory($categoryId)
    {
        $blogs = Blog::byCategory($categoryId)
            ->with(['categories', 'childCategories', 'grandChildCategories', 'brands'])
            ->where('active', '!=', 3)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Admin.Blog.index', compact('blogs'));
    }

    public function filterByBrand($brandId)
    {
        $blogs = Blog::byBrand($brandId)
            ->with(['categories', 'childCategories', 'grandChildCategories', 'brands'])
            ->where('active', '!=', 3)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Admin.Blog.index', compact('blogs'));
    }
    public $website_title = 'Sydney Quality Meat';
    public function allBlogs(Blog $blog, Request $request)
    {
        
         $meta_details = [
            'title' => 'Blogs | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $blogs = Blog::with(['categories', 'childCategories', 'grandChildCategories', 'brands'])->where('active',1)
                        ->latest()
                        ->paginate(10);
        return view('Website.GeneralPages.blogs', compact('blogs','meta_details'));
    }
    public function getBlogbyPermalink(Blog $blog,$permalink)
    {
        
        $mainBlog = $blog->getBlogwithPermalink($permalink);
        if (!$mainBlog) {
            return $this->error('Blog not found', 404);
        }
        $title=$mainBlog->title;
        $relatedBlogs = $this->getRelatedBlogs($mainBlog);
        $meta_details = [
            'title' => $title.' | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
       
        return view('Website.GeneralPages.blog-detail',compact('mainBlog','relatedBlogs','meta_details'));
    }


    private function getRelatedBlogs(Blog $mainBlog, $limit = 10)
    {
        $relatedBlogIds = collect();
        // Get blogs related by brands
        if ($mainBlog->brands && $mainBlog->brands->count() > 0) {
            $brandIds = $mainBlog->brands->pluck('id')->toArray();
            $brandRelatedBlogs = Blog::whereHas('brands', function ($query) use ($brandIds) {
                $query->whereIn('brand_id', $brandIds);
            })
                ->where('id', '!=', $mainBlog->id)
                ->where('active', 1) 
                ->pluck('id');
            $relatedBlogIds = $relatedBlogIds->merge($brandRelatedBlogs);
        }
        // Get blogs related by main categories
        if ($mainBlog->categories && $mainBlog->categories->count() > 0) {
            $categoryIds = $mainBlog->categories->pluck('id')->toArray();
            $categoryRelatedBlogs = Blog::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
                ->where('id', '!=', $mainBlog->id)
                ->where('active', 1)
                ->pluck('id');
            $relatedBlogIds = $relatedBlogIds->merge($categoryRelatedBlogs);
        }
        // Get blogs related by child categories
        if ($mainBlog->childCategories && $mainBlog->childCategories->count() > 0) {
            $childCategoryIds = $mainBlog->childCategories->pluck('id')->toArray();
            $childCategoryRelatedBlogs = Blog::whereHas('childCategories', function ($query) use ($childCategoryIds) {
                $query->whereIn('child_category_id', $childCategoryIds);
            })
                ->where('id', '!=', $mainBlog->id)
                ->where('active', 1)
                ->pluck('id');
            $relatedBlogIds = $relatedBlogIds->merge($childCategoryRelatedBlogs);
        }
        // Get blogs related by grand child categories
        if ($mainBlog->grandChildCategories && $mainBlog->grandChildCategories->count() > 0) {
            $grandChildCategoryIds = $mainBlog->grandChildCategories->pluck('id')->toArray();
            $grandChildCategoryRelatedBlogs = Blog::whereHas('grandChildCategories', function ($query) use ($grandChildCategoryIds) {
                $query->whereIn('grand_child_category_id', $grandChildCategoryIds);
            })
                ->where('id', '!=', $mainBlog->id)
                ->where('active', 1)
                ->pluck('id');
            $relatedBlogIds = $relatedBlogIds->merge($grandChildCategoryRelatedBlogs);
        }
        // Remove duplicates and get unique blog IDs
        $uniqueRelatedBlogIds = $relatedBlogIds->unique()->values();
        // If we have related blog IDs, fetch them with priority scoring
        if ($uniqueRelatedBlogIds->count() > 0) {
            return Blog::whereIn('id', $uniqueRelatedBlogIds->toArray())
                ->where('active', 1)
                ->with(['brands', 'categories', 'childCategories', 'grandChildCategories'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }
        // // If no related blogs found, return recent blogs from same general area
        // return Blog::where('id', '!=', $mainBlog->id)
        //     ->where('active', 1)
        //     ->with(['brands', 'categories', 'childCategories', 'grandChildCategories'])
        //     ->orderBy('created_at', 'desc')
        //     ->limit($limit)
        //     ->get();
    }
}
