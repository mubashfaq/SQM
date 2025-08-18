<?php

namespace App\Http\Controllers\Website;

use DB;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Brand;
use App\Models\order;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Project;
use App\Models\Category;
use App\Models\Discount;
use App\Models\GiftCard;
use App\Models\Invoices;
use App\Models\UserMeta;
use App\General\PostClass;
use App\Models\Collection;
use App\General\BrandClass;
use App\Models\DynamicPage;
use App\Models\ProductMeta;
use App\Models\ReturnOrder;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\General\ProductClass;
use App\General\ProjectClass;
use App\Models\ChildCategory;
use App\Models\CategorySlider;
use App\Models\GeneralSettings;
use App\General\CollectionClass;
use App\Models\CollectionProduct;
use PayPal\Api\Order as ApiOrder;
use App\Models\GrandChildCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\General\GeneralSettingsClass;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Stripe\Climate\Order as ClimateOrder;
use App\Http\Controllers\Admin\Traits\InvoiceTrait;

class PagesController extends Controller
{
    use InvoiceTrait;
    public $website_title = 'Sydney Quality Meat';
    public function home()
    {
        $meta_details = [
            'title' => 'Home | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
    
        // Cache only images from brand sliders
        $brandsliders_images = Cache::remember('home_brandsliders_images', now()->addHours(2), function () {
            return Slider::where('active', 1)
                ->with('brand:id,title,logo_image,cover_image') // Use correct column names
                ->select('id', 'brand_id', 'slider_image', 'title', 'active')
                ->get()
                ->map(function ($slider) {
                    return [
                        'id' => $slider->id,
                        'slider_image' => $slider->slider_image,
                        'title' => $slider->title,
                        'brand_logo' => $slider->brand->logo_image ?? null,
                        'brand_cover' => $slider->brand->cover_image ?? null,
                        'brand_name' => $slider->brand->title ?? null,
                    ];
                });
        });
    
        // Cache only images from category sliders
        $categorysliders_images = Cache::remember('home_categorysliders_images', now()->addHours(2), function () {
            return CategorySlider::where('active', 1)
                ->with('category:id,title,thumbnail_image,cover_image') // Use correct column names
                ->select('id', 'category_id', 'title', 'slide_number', 'active') // Check if CategorySlider has image column
                ->orderBy('slide_number', 'asc')
                ->get()
                ->map(function ($slider) {
                    return [
                        'id' => $slider->id,
                        'title' => $slider->title,
                        'slide_number' => $slider->slide_number,
                        'category_thumbnail' => $slider->category->thumbnail_image ?? null,
                        'category_cover' => $slider->category->cover_image ?? null,
                        'category_title' => $slider->category->title ?? null,
                    ];
                });
        });
    
        // Cache only brand logos and cover images
        $brands_images = Cache::remember('home_brands_images', now()->addHours(4), function () {
            return Brand::where('active', 1)
                ->select('id', 'title', 'logo_image', 'cover_image') // Use correct column names
                ->get()
                ->map(function ($brand) {
                    return [
                        'id' => $brand->id,
                        'name' => $brand->title,
                        'logo_image' => $brand->logo_image,
                        'cover_image' => $brand->cover_image,
                    ];
                });
        });
    
        // Cache only product images
        $products_images = Cache::remember('home_products_images', now()->addMinutes(30), function () {
            return Product::where('active', 1)
                ->select('id', 'title', 'permalink', 'featured_image', 'gallery_media', 'created_at')
                ->orderBy('created_at', 'desc')
                ->take(30)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'permalink' => $product->permalink,
                        'featured_image' => $product->featured_image,
                        'gallery_media' => $product->gallery_media ? json_decode($product->gallery_media) : null,
                    ];
                });
        });
    
        // Cache only collection images
        $collectionproducts_images = Cache::remember('home_collectionproducts_images', now()->addHours(1), function () {
            return Collection::select('id', 'title', 'permalink', 'created_at')
                ->where('active', 1)
                ->orderBy('created_at', 'desc')
                ->take(30)
                ->get()
                ->map(function ($collection) {
                    return [
                        'id' => $collection->id,
                        'title' => $collection->title,
                        'permalink' => $collection->permalink,
                        // Note: collections table doesn't seem to have an image column
                        // You might need to get images from related products or add an image column
                    ];
                });
        });
    
        // Cache only featured product images
        $featuredproducts_images = Cache::remember('home_featuredproducts_images', now()->addMinutes(45), function () {
            return Product::where('active', 1)
                ->whereHas('productMeta', function ($query) {
                    $query->where('option', '_virtual')
                        ->where('value', 1);
                })
                ->select('id', 'title', 'permalink', 'featured_image', 'gallery_media', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'permalink' => $product->permalink,
                        'featured_image' => $product->featured_image,
                        'gallery_media' => $product->gallery_media ? json_decode($product->gallery_media) : null,
                    ];
                });
        });
    
        // Cache only category images
        $categories_images = Cache::remember('home_categories_images', now()->addHours(2), function () {
            return Category::select('id', 'title', 'sku', 'thumbnail_image', 'cover_image', 'created_at')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->title,
                        'sku' => $category->sku,
                        'thumbnail_image' => $category->thumbnail_image,
                        'cover_image' => $category->cover_image,
                    ];
                });
        });
    
        // Now fetch the complete data (without caching)
        $brandsliders = Slider::where('active', 1)
            ->with('brand')
            ->get();
    
        $categorysliders = CategorySlider::where('active', 1)
            ->with('category')
            ->orderBy('slide_number', 'asc')
            ->get();
    
        $brands = Brand::where('active', 1)->get();
    
        $products = Product::where('active', 1)
            ->with('discount')
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();
    
        $collectionproducts = Collection::with(['products.metas', 'products.discount'])
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();
    
        $featuredproducts = Product::where('active', 1)
            ->whereHas('productMeta', function ($query) {
                $query->where('option', '_virtual')
                    ->where('value', 1);
            })
            ->with([
                'productMeta' => function ($query) {
                    $query->whereIn('option', ['_price', '_gst', '_stock_status', '_weight','_is_weight']);
                },
                'discount'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $categories = Category::orderBy('created_at', 'desc')
            ->with([
                'products' => function ($query) {
                    $query->where('active', 1)
                        ->with([
                            'productMeta' => function ($query) {
                                $query->whereIn('option', ['_price', '_weight', '_is_weight','_stock_status']);
                            },
                            'discount'
                        ]);
                }
            ])
            ->take(8)
            ->get();
    
        // Apply discount calculation to featured products
        $featuredproducts->transform(function ($product) {
            $discountData = $product->getDiscountDataAttribute();
            if ($discountData) {
                $product->discount_data = $discountData;
            }
            return $product;
        });
    
        // Pass both cached images and complete data to view
        $pageData = compact(
            'meta_details',
            'categories',
            'brandsliders',
            'categorysliders', 
            'brands',
            'products',
            'featuredproducts',
            'collectionproducts',
            // Cached image data
            'brandsliders_images',
            'categorysliders_images',
            'brands_images',
            'products_images',
            'featuredproducts_images',
            'collectionproducts_images',
            'categories_images'
        );
    
        return response()->view('Website.GeneralPages.home', $pageData);
    }
    
    // Method to clear only image caches
    public function clearHomeImageCache()
    {
        $imageCacheKeys = [
            'home_brandsliders_images',
            'home_categorysliders_images', 
            'home_brands_images',
            'home_products_images',
            'home_collectionproducts_images',
            'home_featuredproducts_images',
            'home_categories_images'
        ];
        
        foreach ($imageCacheKeys as $key) {
            Cache::forget($key);
        }
        
        return response()->json(['message' => 'Home page image cache cleared successfully']);
    }


    public function filter_products(Request $request)
    {
        $meta_details = [
            'title' => 'Search | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $perPage = 30;
        $searchTerm = $request->input('search');
        $categoryId = $request->input('select_category') ?? 0;
        $products = Product::where('active', 1)
            ->where(function ($query) use ($searchTerm, $categoryId) {
                if ($searchTerm && $categoryId) {
                    $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                        ->where('category_id', $categoryId);
                } else {
                    if ($searchTerm) {
                        $query->where('title', 'LIKE', '%' . $searchTerm . '%');
                    }
                    if ($categoryId) {
                        $query->where('category_id', $categoryId);
                    }
                }
            })
            ->paginate($perPage);
        if ($products->isNotEmpty()) {
            $category = 0;
            if (isset($products) && !empty($products)) {
                $category = Category::where('id', $products[0]->category_id ?? 0)->first();
            }
            return view('Website.GeneralPages.filtered-products', compact('meta_details', 'perPage', 'products', 'category'));
        } else {
            $meta_details = [
                'title' => 'Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];

            return view('Website.GeneralPages.NotFoundPage.product', compact('meta_details'));
        }
    }

    public function all_categories()
    {
        $meta_details = [
            'title' => 'all category | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $perPage = 30;
        $categories = Category::paginate($perPage);
        $paginationLinks = $categories->onEachSide(1)->links('pagination::bootstrap-4');
        return view('Website.GeneralPages.all-categories', compact('meta_details', 'perPage', 'paginationLinks', 'categories'));
    }

    public function brand_details($brand)
    {
        $perPage = 30;
        $brand = Brand::where('permalink', $brand)->first();
        $products = Product::where('brand_id', $brand->id)->with('discount', 'productMeta')->paginate($perPage);
        $paginationLinks = $products->onEachSide(1)->links('pagination::bootstrap-4');

        if ($brand) {
            $brand_Title = $brand->title;
            if (!empty($seo_title = BrandClass::getOptionValue('_seo_title', $brand->BrandMeta))) {
                $product_Title = $seo_title;
            }


            $meta_details = [
                'title' => $brand_Title . ' | ' . $this->website_title,
                'keywords' => BrandClass::getOptionValue('_seo_tags', $brand->BrandMeta),
                'description' => BrandClass::getOptionValue('_seo_description', $brand->BrandMeta),
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.brand-view', compact('meta_details', 'products', 'brand', 'paginationLinks', 'perPage'));
        } else {
            $meta_details = [
                'title' => 'Product Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];

            return view('Website.GeneralPages.brand-view', compact('meta_details'));
        }
    }
    public function cart()
    {

        $meta_details = [
            'title' => 'Cart | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];

        return view('Website.GeneralPages.cart', compact('meta_details'));
    }
    public function checkout()
    {
        $meta_details = [
            'title' => 'Checkout | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $cartData = session('cartData', []);
        $totalAmount = session('totalAmount', 0);
        $subTotal = 0;

        if (!empty($cartData)) {
            foreach ($cartData as $product) {
                $amount = $product['amount'];
                $quantity = $product['quantity'];
                $subtotal = floatval($amount) * floatval($quantity);
                $subTotal += $subtotal;
            }

        }
        $totalIncludingGST = $totalAmount;
        $percentage = config('app.PERCENTAGE');
        if (auth()->check()) {
            $user_address = UserAddress::where('user_id', auth()->user()->id)->first();
            return view('Website.GeneralPages.checkout', compact('user_address', 'cartData', 'subTotal', 'totalIncludingGST', 'meta_details'))->render();
        }
        return view('Website.GeneralPages.checkout', compact('cartData', 'subTotal', 'totalIncludingGST', 'meta_details'))->render();


    }
    public function shop()
    {
        $meta_details = [
            'title' => 'Shop | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $products = Product::where('active', 1)->get();
        return view('Website.GeneralPages.shop', compact('meta_details', 'products'));
    }
    public function privary()
    {
        $meta_details = [
            'title' => 'Privacy | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $products = Product::where('active', 1)->get();
        return view('Website.GeneralPages.privacy-policy', compact('meta_details', 'products'));
    }
    public function terms()
    {
        $meta_details = [
            'title' => 'Terms | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $products = Product::where('active', 1)->get();
        return view('Website.GeneralPages.terms-conditions', compact('meta_details', 'products'));
    }
    public function product($permalink)
    {
        $product = Product::where('permalink', $permalink)->first();

        if ($product) {
            $product_Title = $product->product_title;
            if (!empty($seo_title = ProductClass::getOptionValue('_seo_title', $product->productMeta))) {
                $product_Title = $seo_title;
            }
            $meta_details = [
                'title' => $product_Title . ' | ' . $this->website_title,
                'keywords' => ProductClass::getOptionValue('_seo_tags', $product->productMeta),
                'description' => ProductClass::getOptionValue('_seo_description', $product->productMeta),
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.product', compact('product', 'meta_details'));
        } else {
            $meta_details = [
                'title' => 'Product Not Found! | Sydney Quality Meat',
                'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
                'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.NotFoundPage.product', compact('meta_details'));
        }
    }

    public function profile()
    {
        $user = User::where('id', Auth::id())->with('userMeta')->first();
        $shipping = UserAddress::where('id', 1)->with('user')->first();
        $addresses = UserAddress::where('user_id', Auth::id())->get();
        $orders = order::where('user_id', $user->id)->orWhere('email', $user->email)->where('status', '!=', 'refund')
            ->with('orderMetas.product')->orderBy('id', 'desc')
            ->paginate(10);
        $returns = ReturnOrder::where('user_id', $user->id)->orWhere('User_email', $user->email)->orderBy('id', 'desc')->with('returnMeta', 'order')
            ->paginate(10);
        $invoices = Invoices::where('is_deleted', 0)->where('customer', Auth::id())->where('active', 1)->orderBy('id', 'DESC')->paginate(10);
        $meta_details = [
            'title' => 'Profile | Sydney Quality Meat',
            'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat. Together, we can make a difference in the lives of those in need.',
            'author' => 'Sydney Quality Meat Org',
        ];
        return view('Website.GeneralPages.profile', compact('meta_details', 'user', 'shipping', 'addresses', 'orders', 'invoices', 'returns'));
    }
    public function generatePdf_pos($id)
    {
        $Invoice_review = $this->ReviewInvoice($id);
        return response()->json(['message' => 'Invoice updated successfully', 'invoice_review' => $Invoice_review], 200);
    }
    public function login()
    {
        $meta_details = [
            'title' => 'SignIn | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        return view('Website.GeneralPages.login', compact('meta_details'));
    }

    public function orderHistory(Request $request)
    {
        $meta_details = [
            'title' => 'Order History | ' . $this->website_title,
            'keywords' => 'tools, genuine, hardware, makita, australia',
            'description' => 'Wholesale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];

        // Start a query that gets orders for the current user
        $query = Order::where(function ($q) {
            $q->where('user_id', auth()->user()->id)
                ->orWhere('email', auth()->user()->email);
        });

        if ($search = $request->input('search')) {

            $query->where(function ($q) use ($search) {
                // If the search input contains '/', assume it might be a date in dd/mm/yyyy format
                if (strpos($search, '/') !== false) {
                    $convertedDate = Carbon::createFromFormat('d/m/Y', $search)->format('Y-m-d');
                    $q->orWhereDate('created_at', $convertedDate);
                }

                $q->orWhere('grand_total', 'LIKE', "%{$search}%")
                    ->orWhere('payment_status', 'LIKE', "%{$search}%")
                    ->orWhere('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhere('delivered_price', 'LIKE', "%{$search}%")
                    ->orWhere('created_at', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('delivery_method', 'LIKE', "%{$search}%")
                    ->orWhere('payment_method', 'LIKE', "%{$search}%");
            });
        }

        // Order and paginate the results
        $orders = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('Website.GeneralPages.order-history', compact('orders', 'meta_details'));
    }


    public function updateAddress()
    {
        $meta_details = [
            'title' => 'Update Address | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $address = UserAddress::where('user_id', auth()->user()->id)->first();
        return view('Website.GeneralPages.update-address', compact('address', 'meta_details'));
    }
    public function addNewAddress()
    {
        $meta_details = [
            'title' => 'Add Address | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        return view('Website.GeneralPages.add-new-address', compact('meta_details'));
    }

    public function StoreAddress(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'suburb' => 'required|string|max:255',
            'post_code' => 'required|string',
            'street_address' => 'required|string|max:255',
            'address_type' => 'required|string|max:255',

        ]);

        $identificationAttributes = [
            'user_id' => auth()->user()->id,
        ];

        $additionalData = [
            'f_name' => $request->first_name,
            'l_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'suburb' => $request->suburb,
            'post_code' => $request->post_code,
            'street_address' => $request->street_address,
            'address_type' => $request->address_type,
            'company' => $request->company,
        ];

        UserAddress::updateOrCreate($identificationAttributes, $additionalData);

        return redirect()->route('Website.GeneralPages.my-account')->with('success', 'Address saved successfully!');
    }


    public function edit_profile()
    {
        $meta_details = [
            'title' => 'Update Profile | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];

        $user = auth()->user();
        $user_id = $user->id;
        $user_address = UserAddress::where('user_id', $user_id)->first();
        $user_meta = UserMeta::where('user_id', $user_id)->get();

        $business = [];
        foreach ($user_meta as $meta) {
            $business[$meta->option] = $meta->value;
        }

        return view('Website.GeneralPages.update-profile', compact('user', 'business', 'meta_details'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|numeric',
        ];

        $request->validate($validationRules);
        // Update the user details if provided
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->phone = $request->input('phone', $user->phone);

        // Update password if it's not empty
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Save the user details

        // Update business details in user_meta
        $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id, 'option' => 'business_name'],
            ['value' => $request->input('business_name')]
        );
        $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id, 'option' => 'business_email'],
            ['value' => $request->input('business_email')]
        );
        $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id, 'option' => 'business_phone'],
            ['value' => $request->input('business_phone')]
        );
        $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id, 'option' => 'business_street_address'],
            ['value' => $request->input('business_street_address')]
        );
        $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id, 'option' => 'area_address'],
            ['value' => $request->input('area_address')]
        );

        return redirect()->route('Website.GeneralPages.my-account')->with('success', 'Profile updated successfully!');
    }


    public function orderDetail($id)
    {
        $meta_details = [
            'title' => 'Order Detail | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $order = Order::with(['orderMeta'])->where('id', $id)->first();
        return view('Website.GeneralPages.order-detail', compact('order', 'meta_details'));
    }
    public function myAccount()
    {

        $user = auth()->user();
        $user_id = $user->id;
        $user_address = UserAddress::where('user_id', $user_id)->first();
        $user_meta = UserMeta::where('user_id', $user_id)->get();
        $business_details = [];
        foreach ($user_meta as $meta) {
            $business_details[$meta->option] = $meta->value;
        }
        $meta_details = [
            'title' => 'Account | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];

        return view('Website.GeneralPages.my-account', compact('user', 'meta_details', 'user_address', 'user_meta', 'business_details'));
    }
    public function register()
    {
        $meta_details = [
            'title' => 'SignUp | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $categories = Category::get();
        return view('Website.GeneralPages.register', compact('meta_details', 'categories'));
    }
    public function forgetPassword()
    {
        $meta_details = [
            'title' => 'Forget Password | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        return view('Website.GeneralPages.forgetPassword', compact('meta_details'));
    }
    public function reset_password(Request $request)
    {
        $token = $request->query('token');
        $meta_details = [
            'title' => 'Reset Password | Sydney Quality Meat',
            'keywords' => 'Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat.',
            'author' => 'Sydney Quality Meat Org',
        ];
        return view('Website.GeneralPages.reset_password', compact('meta_details', 'token'));
    }

    public function project($permalink)
    {
        $project = Project::where('permalink', $permalink)->first();
        if ($project) {
            $project_Title = $project->project_title . ' | Sydney Quality Meat';
            if (!empty($seo_title = ProjectClass::getOptionValue('_seo_title', $project->projectMeta))) {
                $project_Title = $seo_title;
            }
            $meta_details = [
                'title' => $project_Title,
                'keywords' => ProjectClass::getOptionValue('_seo_tags', $project->projectMeta),
                'description' => ProjectClass::getOptionValue('_seo_description', $project->projectMeta),
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.project', compact('project', 'meta_details'));
        } else {
            $meta_details = [
                'title' => 'Project Not Found! | Sydney Quality Meat',
                'keywords' => 'Sydney Quality Meat, organization',
                'description' => 'Support our cause by making a donation to Sydney Quality Meat.',
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.NotFoundPage.project', compact('meta_details'));
        }
    }
    public function volunteer()
    {
        $meta_details = [
            'title' => 'Volunteer | Sydney Quality Meat',
            'keywords' => 'Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat.',
            'author' => 'Sydney Quality Meat Org',
        ];
        return view('Website.GeneralPages.volunteer', compact('meta_details'));
    }

    public function posts()
    {
        $meta_details = [
            'title' => 'Blog - Posts | Sydney Quality Meat',
            'keywords' => 'Sydney Quality Meat, organization',
            'description' => 'Support our cause by making a donation to Sydney Quality Meat.',
            'author' => 'Sydney Quality Meat Org',
        ];

        $posts = Post::where('active', 1)->get();
        return view('Website.GeneralPages.Posts', compact('meta_details', 'posts'));
    }

    public function thankyouPage()
    {
        $meta_details = [
            'title' => 'Thanks, Order Placed | Sydney Quality Meat',
            'keywords' => ' Sydney Quality Meat',
            'description' => ' Sydney Quality Meat.',
            'author' => 'Sydney Quality Meat Org',
        ];
        $settings = GeneralSettings::get();
        $store_invoice_note = GeneralSettingsClass::getOptionValue('_store_order_note', $settings);
        return view('Website.GeneralPages.thankyou', compact('meta_details', 'store_invoice_note'));
    }

    public function post($permalink)
    {
        $post = Post::where('permalink', $permalink)->first();

        if ($post) {
            $post_Title = $post->post_title . ' | Sydney Quality Meat';
            if (!empty($seo_title = PostClass::getOptionValue('_seo_title', $post->postMeta))) {
                $post_Title = $seo_title;
            }
            $meta_details = [
                'title' => $post_Title,
                'keywords' => PostClass::getOptionValue('_seo_tags', $post->postMeta),
                'description' => PostClass::getOptionValue('_seo_description', $post->postMeta),
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.post', compact('post', 'meta_details'));
        } else {
            $meta_details = [
                'title' => 'Post Not Found! | Sydney Quality Meat',
                'keywords' => 'donate, donation, charity, Sydney Quality Meat, organization',
                'description' => 'Support our cause by making a donation to Sydney Quality Meat.',
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.NotFoundPage.post', compact('meta_details'));
        }
    }

    public function checkPostcode(Request $request)
    {
        $postCode = $request->input('post_code');
        $productId = $request->input('product_id');

        $product = Product::find($productId);

        if ($product->area_group_id == "0") {
            return response()->json(['area_group_exit' => true]);
        }

        if ($product && $product->area_group_id) {
            $areaGroupIds = explode(',', $product->area_group_id);

            if (in_array($postCode, $areaGroupIds)) {
                return response()->json(['area_group_exit' => true]);
            }
        }

        return response()->json(['area_group_exit' => false]);
    }

    // public function updateCart(Request $request)
    // {
    //     // Get the percentage value from the environment configuration
    //     // $percentage = env('PERCENTAGE', 0);
    //     $percentage = config('app.PERCENTAGE');
    //     $updatedCartData = session('cartData', []);
    //     $pId = $request->input('pId');
    //     $title = $request->input('title');
    //     $amount = $request->input('amount');
    //     $featuredImage = $request->input('featuredImage');
    //     $gst = $request->input('gst');
    //     $pIdToRemove = $request->input('pIdToRemove');
    //     $quantity = $request->input('quantity');
    //     $productGst = $request->input('productGst');
    //     $increaseQuantity = $request->input('increaseQuantity');
    //     $decreaseQuantity = $request->input('decreaseQuantity');

    //     $productStock = ProductMeta::where('product_id', $pId == null ? $pIdToRemove : $pId)
    //         ->where('option', '_stock')
    //         ->first();
    //     $productPrice = ProductMeta::where('product_id', $pId == null ? $pIdToRemove : $pId)
    //         ->where('option', '_price')
    //         ->first();
    //     if ($productStock->value >= $quantity) {
    //         $totalGst = 0;
    //         $subTotal = 0;
    //         if ($pIdToRemove !== null) {
    //             $updatedCartData = array_values(array_filter($updatedCartData, function ($product) use ($pIdToRemove) {
    //                 return $product['pId'] !== $pIdToRemove;
    //             }));
    //         }
    //         $existingProductIndex = -1;
    //         if (isset($updatedCartData) && !empty($updatedCartData)) {
    //             foreach ($updatedCartData as $index => $product) {
    //                 if ($product['pId'] === $pId) {
    //                     $existingProductIndex = $index;
    //                     break;
    //                 }
    //             }
    //         }
    //         if ($existingProductIndex !== -1) {
    //             if ($productStock->value > $updatedCartData[$existingProductIndex]['quantity']) {
    //                 if ($increaseQuantity !== null) {
    //                     $updatedCartData[$existingProductIndex]['quantity'] = intval($increaseQuantity);
    //                 } elseif ($decreaseQuantity !== null) {
    //                     $updatedCartData[$existingProductIndex]['quantity'] = intval($decreaseQuantity);
    //                 } else {
    //                     $updatedCartData[$existingProductIndex]['quantity'] += intval($quantity);
    //                 }
    //             } else {
    //                 return response()->json(['message' => 'Sorry We do not have more quantity of this product', 'status' => false]);
    //             }
    //         } else {
    //             $cartData = [
    //                 'pId' => $pId,
    //                 'title' => $title,
    //                 'amount' => $amount,
    //                 'featuredImage' => $featuredImage,
    //                 'quantity' => $quantity,
    //                 'productGst' => $productGst,
    //             ];
    //             if ($gst == 1) {
    //                 $cartData['gst'] = $gst;
    //                 if ($cartData['productGst'] == 0) {
    //                     $productGst = (intval($amount) * intval($percentage) / 100);
    //                     $cartData['productGst'] = intval($productGst);
    //                 }
    //             } else {
    //                 $cartData['productGst'] = 0;
    //             }
    //             if ($cartData['pId'] !== null && $cartData['title'] !== null) {
    //                 $updatedCartData[] = $cartData;
    //             }
    //         }
    //         session(['cartData' => $updatedCartData]);

    //         $amounts = [];
    //         $totalAmount = 0;
    //         $totalGst = 0;
    //         $totalProducts = count($updatedCartData);

    //         foreach ($updatedCartData as $product) {
    //             $amount = $product['amount'];
    //             $gst = isset($product['gst']) ? $product['gst'] : null;

    //             if ($gst == 1) {
    //                 $amount += (intval($amount) * intval($percentage) / 100);
    //             }
    //             $totalGst += intval($product['productGst']) * intval($product['quantity']);
    //             $subTotal += intval($amount) * intval($product['quantity']);
    //         }
    //         $totalAmount = $subTotal;
    //         $amounts = ['totalAmount' => $totalAmount, 'subTotal' => $subTotal, 'gst' => $totalGst];

    //         session(['totalAmount' => $totalAmount]);
    //         session(['totalProducts' => $totalProducts]);
    //         session(['totalGst' => $totalGst]);
    //         session(['subTotal' => $subTotal]);

    //         return response()->json(['message' => 'Session data updated.', 'data' => $updatedCartData, 'amounts' => $amounts, 'status' => true]);
    //     } else {
    //         return response()->json(['message' => 'Sorry We do not have your required quantity of this product.', 'status' => false]);
    //     }
    // }
    public function storeCart(Request $request)
    {
        // Fetch GST percentage from general settings
        $gstPercentageSetting = GeneralSettings::where('option', '_gst_percentage')->first();
        $gstPercentage = $gstPercentageSetting ? (int) $gstPercentageSetting->value : 0;

        // Fetch Delivery Charges from general settings
        $deliveryChargeSetting = GeneralSettings::where('option', '_delivery_charges')->first();
        $deliveryCharge = $deliveryChargeSetting ? (int) $deliveryChargeSetting->value : 0;

        $updatedCartData = session('cartData', []);

        $pId = $request->input('pId');
        $title = $request->input('title');
        $amount = $request->input('amount');
        $featuredImage = $request->input('featuredImage');
        $pIdToRemove = $request->input('pIdToRemove');
        $quantity = $request->input('quantity', 1);
        $original_weight = $request->input('original_weight', 0);
        $original_price = $request->input('original_price', 0);
        $weight = $request->input('weight', 0); // Get product weight
        $is_weight = $request->input('is_weight',0); 

        // Fetch product stock and price from meta
        $productStock = ProductMeta::where('product_id', $pId ?? $pIdToRemove)
            ->where('option', '_stock_status')
            ->first();

        $productPrice = ProductMeta::where('product_id', $pId ?? $pIdToRemove)
            ->where('option', '_price')
            ->first();

        // Fetch product GST status
        $productGstStatus = ProductMeta::where('product_id', $pId)
            ->where('option', '_gst')
            ->first();
        $isGstApplicable = $productGstStatus && $productGstStatus->value == 1;

        if (!$productStock || !$productPrice) {
            return redirect()->back()->with('info', 'Missing product meta for stock or price.');
        }

        if ($productStock->value < $quantity) {
            return redirect()->back()->with('info', 'Sorry, not enough stock available!');
        }

        // If pIdToRemove is set, remove that product from the cart.
        if ($pIdToRemove !== null) {
            $updatedCartData = array_values(array_filter($updatedCartData, function ($product) use ($pIdToRemove) {
                return $product['pId'] !== $pIdToRemove;
            }));
        }

        // Check if the product already exists in cart.
        foreach ($updatedCartData as $cartProduct) {
            if ($cartProduct['pId'] === $pId) {
                // If the product is already in the cart, do not add it again.
                return redirect()->back()->with('error', 'Item already in cart.');
            }
        }

        // Add new product to cart
        $cartData = [
            'pId' => $pId,
            'title' => $title,
            'original_ammount' => $amount,
            'amount' => $amount,
            'featuredImage' => $featuredImage,
            'quantity' => (int) $quantity,
            'weight' => (float) $weight * (int) $quantity, // Calculate total weight
            'is_weight' => $is_weight, // Calculate total weight
            'original_weight' => $original_weight, // Calculate total weight
            'original_price' => $original_price, // Calculate total weight
            'productGst' => 0,
        ];

        // Calculate GST if applicable
        if ($isGstApplicable) {
            $cartData['gst'] = 1;
            $cartData['productGst'] = ((float) $amount * $gstPercentage) / 100;
        } else {
            $cartData['gst'] = 0;
            $cartData['productGst'] = 0;
        }

        if ($cartData['pId'] && $cartData['title']) {
            $updatedCartData[] = $cartData;
        }

        // Update cart session
        session(['cartData' => $updatedCartData]);

        // Calculate totals
        $totalAmount = 0;
        $totalGst = 0;
        $subTotal = 0;
        $totalWeight = 0;
        $totalProducts = count($updatedCartData);

        foreach ($updatedCartData as $item) {
            $itemAmount = (float) $item['amount'];
            $itemQty = (int) $item['quantity'];
            $itemWeight = (float) $item['weight'];

            // Add the plain price to subTotal
            $subTotal += $itemAmount * $itemQty;

            // If this product has GST, add the productGst to totalGst
            if (!empty($item['gst']) && $item['gst'] == 1) {
                $totalGst += ((float) $item['productGst']) * $itemQty;
            }

            // Calculate total weight
            $totalWeight += $itemWeight;
        }

        // Final total amount
        $totalAmount = $subTotal + $totalGst;

        // Store totals in session
        session([
            'totalAmount' => $totalAmount,
            'totalProducts' => $totalProducts,
            'totalGst' => $totalGst,
            'subTotal' => $subTotal,
            'deliveryCharge' => $deliveryCharge,
            'totalWeight' => $totalWeight,
        ]);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }





    public function updateQuantity(Request $request)
    {
        // Retrieve existing cart data from session
        $cartData = session('cartData', []);

        $pId = $request->input('pId');
        $increase = $request->input('increaseQuantity');
        $decrease = $request->input('decreaseQuantity');

        // Fetch GST percentage from general settings
        $gstPercentageSetting = GeneralSettings::where('option', '_gst_percentage')->first();
        $gstPercentage = $gstPercentageSetting ? (int) $gstPercentageSetting->value : 0;

        // Fetch Delivery Charges from general settings
        $deliveryChargeSetting = GeneralSettings::where('option', '_delivery_charges')->first();
        $deliveryCharge = $deliveryChargeSetting ? (int) $deliveryChargeSetting->value : 0;

        // Loop through the cart to update the quantity and weight
        foreach ($cartData as &$item) {
            if ($item['pId'] == $pId) {
                if ($increase) {
                    $item['quantity'] += 1;
                } elseif ($decrease && $item['quantity'] > 1) {
                    $item['quantity'] -= 1;
                }

                // Update the weight based on new quantity
                if (isset($item['weight'])) {
                    $originalWeightPerItem = $item['weight'] / max($item['quantity'], 1); // Prevent division by zero
                    $item['weight'] = $originalWeightPerItem * $item['quantity'];
                }
            }
        }
        unset($item); // Break the reference

        // Recalculate totals
        $subTotal = 0;
        $totalQuantity = 0;  // Sum of all quantities
        $totalGst = 0;  // Sum of all GST across items
        $totalWeight = 0;  // Sum of total weight

        foreach ($cartData as &$item) {
            // Fetch product GST status from meta
            $productGstStatus = ProductMeta::where('product_id', $item['pId'])
                ->where('option', '_gst')
                ->first();
            $isGstApplicable = $productGstStatus && $productGstStatus->value == 1;

            // Calculate per-item subtotal (price * quantity)
            $lineAmount = $item['amount'] * $item['quantity'];
            $subTotal += $lineAmount;
            $totalQuantity += $item['quantity'];

            // Recalculate GST based on updated quantity
            if ($isGstApplicable) {
                $item['gst'] = 1;
                $item['productGst'] = ((int) $item['amount'] * $gstPercentage) / 100;
            } else {
                $item['gst'] = 0;
                $item['productGst'] = 0;
            }

            // For total GST, multiply the per-item GST by its quantity
            $totalGst += $item['productGst'] * $item['quantity'];

            // Calculate total weight
            if (isset($item['weight'])) {
                $totalWeight += $item['weight'];
            }
        }
        unset($item); // Break reference again

        // totalProducts is just how many distinct products are in the cart
        $totalProducts = count($cartData);

        // totalAmount = subTotal + totalGst
        $totalAmount = $subTotal + $totalGst;

        // Update session data with new values, including totalWeight
        session([
            'cartData' => $cartData,
            'subTotal' => $subTotal,
            'totalProducts' => $totalProducts,  // Distinct products
            'totalQuantity' => $totalQuantity,  // Sum of all item quantities
            'totalGst' => $totalGst,
            'totalAmount' => $totalAmount,
            'totalWeight' => $totalWeight,    // Store total weight in session
        ]);

        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Quantity updated!',
                'cartData' => $cartData,
                'subTotal' => $subTotal,
                'totalAmount' => $totalAmount,
                'totalGst' => $totalGst,
                'totalProducts' => $totalProducts,
                'totalQuantity' => $totalQuantity,
                'totalWeight' => $totalWeight,  // Include updated total weight
            ]);
        }

        // Fallback for non-AJAX requests
        return redirect()->back();
    }






    public function clearCart(Request $request)
    {
        // Clear the cart from the session
        session()->forget('cartData');

        // Reset all cart-related session values
        session([
            'totalAmount' => 0,
            'totalProducts' => 0,
            'totalGst' => 0,
            'subTotal' => 0,
            'totalWeight' => 0,
        ]);

        return response()->json([
            'status' => true,
            'totalProducts' => 0,
        ]);
    }

    public function getCartItemNumbers()
    {
        $cartData = session('cartData', []);
        $total_items = count($cartData);
        session(['total_items' => $total_items]);
        return response()->json($total_items);
    }

    public function showCartdata()
    {
        $meta_details = [
            'title' => 'Cart | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'Sydney Quality Meat',
        ];
        $cartData = session('cartData', []);
        $totalAmount = session('totalAmount', 0);
        $subTotal = 0;

        if (!empty($cartData)) {
            foreach ($cartData as $product) {
                $amount = $product['amount'];
                $quantity = $product['quantity'];
                $subtotal = intval($amount) * intval($quantity);
                $subTotal += intval($subtotal);
            }
        }
        $totalIncludingGST = $totalAmount;
        $percentage = config('app.PERCENTAGE');
        $settings = GeneralSettings::get();
        $minimumWeightGrams = GeneralSettingsClass::getOptionValue('_minimum_weight', $settings);
        $minimumWeightKg = $minimumWeightGrams ? $minimumWeightGrams / 1000 : 0.5;

        return view('Website.GeneralPages.cart', compact('cartData', 'meta_details', 'minimumWeightKg', 'minimumWeightGrams'))->render();
    }


    public function single_product(Request $request, $permalink)
    {
        $categories = Category::all();
        $childcats = ChildCategory::all();
        $grandcats = GrandChildCategory::all();
        $product = Product::where('permalink', $permalink)->where('active', 1)->with('type')->firstOrFail();

        $categoryproducts = Product::with([
            'category',
            'productMeta' => function ($query) {
                $query->whereIn('option', ['_price', '_gst', '_stock']);
            }
        ])
            ->where('active', 1)
            ->where('category_id', $product->category_id)
            ->get();

        $brandlogo = DB::table('brands')->where('id', $product->brand_id)->select('logo_image', 'title')->first();

        $disocuntonproduct = DB::table('discounts')->where('id', $product->discount_id)->select('title', 'active', 'badge_text', 'badge_color', 'badge_image', 'discount_value', 'limited', 'from_date', 'end_date', 'discount_type')->first();
        $currentDate = time();
        $fromDate = strtotime($disocuntonproduct->from_date ?? 0);
        $toDate = strtotime($disocuntonproduct->end_date ?? 0);
        if ($currentDate <= $fromDate || $currentDate >= $toDate) {
            $disocuntonproduct = null;
        }

        $collectionDiscount = null;

        if (!$disocuntonproduct) {
            $collectionproducts = CollectionProduct::where('product_id', $product->id)->with('collection')->first();
            $collectionDiscount = Discount::where('id', $collectionproducts->collection->discount_id ?? 0)->first();
        }

        $featuredproducts = Product::where('active', 1)
            ->whereHas('productMeta', function ($query) {
                $query->where('option', '_virtual')
                    ->where('value', 1);
            })
            ->with([
                'productMeta' => function ($query) {
                    $query->whereIn('option', ['_price', '_gst', '_stock']);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        if ($product) {
            $product_Title = $product->title;
            if (!empty($seo_title = ProductClass::getOptionValue('_seo_title', $product->productMeta))) {
                $product_Title = $seo_title;
            }
            $meta_details = [
                'title' => $product_Title . ' | ' . $this->website_title,
                'keywords' => ProductClass::getOptionValue('_seo_tags', $product->productMeta),
                'description' => ProductClass::getOptionValue('_seo_description', $product->productMeta),
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.product', compact('meta_details', 'product', 'categories', 'childcats', 'grandcats', 'featuredproducts', 'categoryproducts', 'brandlogo', 'disocuntonproduct', 'collectionDiscount'));
        } else {
            $meta_details = [
                'title' => 'Product Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];
        }
    }

    public function kit_product()
    {
        $meta_details = [
            'title' => 'Kit Product | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'ToolTrad',
        ];
        return view('Website.GeneralPages.kit', compact('meta_details'));
    }

    public function all_featured_products()
    {
        $meta_details = [
            'title' => 'Featured Products | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'ToolTrad',
        ];
        $perPage = 30;
        $featuredproducts = Product::where('active', 1)
            ->whereHas('productMeta', function ($query) {
                $query->where('option', '_virtual')
                    ->where('value', 1);
            })
            ->with([
                'productMeta' => function ($query) {
                    $query->whereIn('option', ['_price', '_gst', '_stock']); // Load _gst and _price productMeta
                }
            ])->with('discount')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        $paginationLinks = $featuredproducts->onEachSide(1)->links('pagination::bootstrap-4');

        return view('Website.GeneralPages.product-featured-view', compact('meta_details', 'featuredproducts', 'paginationLinks', 'perPage'));
    }

    public function all_products()
    {
        $meta_details = [
            'title' => 'All Products | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'ToolTrad',
        ];
        $perPage = 30;
        $featuredproducts = Product::where('active', 1)->with('productMeta')->paginate($perPage);
        $paginationLinks = $featuredproducts->onEachSide(1)->links('pagination::bootstrap-4');
        return view('Website.GeneralPages.all-product-view', compact('meta_details', 'featuredproducts', 'paginationLinks', 'perPage'));
    }

    public function brands()
    {
        $meta_details = [
            'title' => 'Brands | ' . $this->website_title,
            'keywords' => 'tools, genuine, harware, makita, australia',
            'description' => 'Whole sale Hardware selling website',
            'author' => 'ToolTrad',
        ];
        $activeStatuses = [0, 1, 2];
        $brands = Brand::whereIn('active', $activeStatuses)->get();
        return view('Website.GeneralPages.brands', compact('meta_details', 'brands'));
    }

    public function category_product($category_sku)
    {
        $category = Category::where('sku', $category_sku)->first();

        if ($category) {
            $perPage = 30;
            $products = Product::where('category_id', $category->id)->with('discount', 'productMeta')->paginate($perPage);
            $paginationLinks = $products->onEachSide(1)->links('pagination::bootstrap-4');
            $meta_details = [
                'title' => $category->title . ' | ' . $this->website_title,
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website for ' . $category->title,
                'author' => 'ToolTrad',
            ];

            return view('Website.GeneralPages.category-view', compact('products', 'paginationLinks', 'perPage', 'meta_details', 'category'));
        } else {
            $meta_details = [
                'title' => 'Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];
            $heading_word = 'Category';

            return view('Website.GeneralPages.NotFoundPage.category', compact('meta_details', 'heading_word'));
        }
    }

    public function childcategory_product($childcategory_sku)
    {
        $childcategory = ChildCategory::where('child_sku', $childcategory_sku)->first();
        if ($childcategory) {
            $perPage = 30;
            $products = Product::where('child_category', $childcategory->id)->with('discount')->paginate($perPage);
            $paginationLinks = $products->onEachSide(1)->links('pagination::bootstrap-4');
            $meta_details = [
                'title' => $childcategory->child_title . ' | ' . $this->website_title,
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website for ' . $childcategory->child_title,
                'author' => 'ToolTrad',
            ];

            return view('Website.GeneralPages.childcategory-view', compact('products', 'paginationLinks', 'perPage', 'meta_details', 'childcategory'));
        } else {
            $meta_details = [
                'title' => 'Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];
            $heading_word = 'Child Category';

            return view('Website.GeneralPages.NotFoundPage.category', compact('meta_details', 'heading_word'));
        }
    }

    public function grand_childcategory_product($grandchildcategory_sku)
    {
        $grandchildcategory = GrandChildCategory::where('grand_sku', $grandchildcategory_sku)->first();
        if ($grandchildcategory) {
            $perPage = 30;
            $products = Product::where('grand_child_category', $grandchildcategory->id)->with('discount')->paginate($perPage);
            $paginationLinks = $products->onEachSide(1)->links('pagination::bootstrap-4');
            $meta_details = [
                'title' => $grandchildcategory->grand_title . ' | ' . $this->website_title,
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website for ' . $grandchildcategory->grand_title,
                'author' => 'ToolTrad',
            ];
            return view('Website.GeneralPages.grand-childcategory-view', compact('products', 'paginationLinks', 'perPage', 'meta_details', 'grandchildcategory'));
        } else {
            $meta_details = [
                'title' => 'Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];
            $heading_word = 'Grand Child Category';

            return view('Website.GeneralPages.NotFoundPage.category', compact('meta_details', 'heading_word'));
        }
    }

    public function dynamic_page($permalink)
    {
        $page = DynamicPage::where('permalink', $permalink)->first();
        $brands = Brand::where('active', 1)->get();
        if ($page) {
            $meta_details = [
                'title' => $page->title . ' | ' . $this->website_title ?? "Page",
                'keywords' => $page->seo_tags ?? "Tags",
                'description' => $page->seo_description ?? "Description",
                'author' => 'Sydney Quality Meat',
            ];
            return view('Website.GeneralPages.dynamic-content-page', compact('page', 'brands', 'meta_details'));
        } else {

            return response()->view('Admin.Errors.404', [], 404);
        }
    }

    public function gift_cards()
    {
        $giftCards = GiftCard::where('variant', 'gift_card')->where('status', 'active')->get();
        if ($giftCards->isNotEmpty()) {
            $meta_details = [
                'title' => 'Gift Cards | ' . $this->website_title,
                'keywords' => 'tools, genuine, harware, makita, australia',
                'description' => 'Gift Cards can be a very usefull thing to gift someone.',
                'author' => 'Sydney Quality Meat',
            ];
            return view('Website.GeneralPages.gift-cards', compact('meta_details', 'giftCards'));
        } else {
            $meta_details = [
                'title' => 'Not Found | ' . $this->website_title,
                'keywords' => 'tools, genuine, harware, makita, australia',
                'description' => 'Gift Cards can be a very usefull thing to gift someone.',
                'author' => 'Sydney Quality Meat',
            ];
            return view('Website.GeneralPages.NotFoundPage.gift-card', compact('meta_details'));
        }
    }
    public function gift_card_checkout_page($id)
    {
        $gift_card = GiftCard::where('id', base64_decode($id))->first();
        if ($gift_card->status == 'active') {
            $meta_details = [
                'title' => 'Gift Cards Checkout | ' . $this->website_title,
                'keywords' => 'tools, genuine, harware, makita, australia',
                'description' => 'Gift Cards can be a very usefull thing to gift someone.',
                'author' => 'Sydney Quality Meat',
            ];
            return view('Website.GeneralPages.gift-cards-checkout', compact('meta_details', 'gift_card'));
        }
    }

    public function getCollectionProducts(Request $request)
    {
        $selectedCollectionId = $request->input('collection_id');
        $collectionproducts = Collection::with(['products.metas', 'products.discount'])
            ->where('id', $selectedCollectionId)
            ->where('active', 1)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        $html = '';

        if (isset($collectionproducts) && !empty($collectionproducts)) {
            foreach ($collectionproducts as $collectionproduct) {
                if ($collectionproduct->products->isNotEmpty()) {
                    foreach ($collectionproduct->products as $c_product) {
                        $discountedPrice = 0;
                        $fromDate = 0;
                        $currentDate = 0;
                        $toDate = 0;
                        if (isset($c_product) && $c_product->discount !== null) {
                            if ($c_product->active == 1) {
                                $currentDate = time();
                                $fromDate = strtotime($c_product->discount->from_date);
                                $toDate = strtotime($c_product->discount->end_date);
                                $discountedPrice = 0;
                                if ($c_product->discount->limited == 0) {
                                    $discountValue = $c_product->discount->discount_value;
                                    $actualPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                                    $discountAmount = ($discountValue / 100) * $actualPrice;
                                    $discountedPrice = $actualPrice - $discountAmount;
                                } elseif ($c_product->discount->limited == 1) {
                                    if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                        $fromDate = strtotime($c_product->discount->from_date);
                                        $toDate = strtotime($c_product->discount->end_date);
                                        $discountValue = $c_product->discount->discount_value;
                                        $actualPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                                        $discountAmount = ($discountValue / 100) * $actualPrice;
                                        $discountedPrice = $actualPrice - $discountAmount;
                                    } else {
                                        $discountedPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                                    }
                                }
                            } else {
                                $discountedPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                            }
                        }

                        if (isset($c_product)) {
                            $html .= '<div class="col-lg-2 col-md-4 col-sm-12 col-border-top-r">
                        <div class="product-detail-section">
                            <div class="card product-card">
                                <div class="card-body">
                                    <div class="product-back-img">
                                        <div class="image-container" style="background-image: url(\'' . url('/') . '/' . $c_product->featured_image . '\');"></div>';
                            $collection_products = DB::table('collection_products')
                                ->join('collections', 'collections.id', '=', 'collection_products.collection_id')
                                ->join('discounts', 'discounts.id', '=', 'collections.discount_id')
                                ->where('product_id', $c_product->id)
                                ->first();
                            if ($c_product->discount_id == 0) {
                                if ($collection_products) {
                                    if ($collection_products->limited == 1) {
                                        $currentDate = time();
                                        $fromDate = strtotime($collection_products->from_date);
                                        $toDate = strtotime($collection_products->end_date);
                                        if ($collection_products->active == 1) {
                                            if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                                if ($collection_products->discount_type == 1) {
                                                    $html .= '<p class="product-discount">Save ' . $collection_products->discount_value . '$ </p>';
                                                } else {
                                                    $html .= '<p class="product-discount">Save ' . $collection_products->discount_value . ' %</p>';
                                                }
                                            } else {
                                                $html .= '<p class="product-discount-dynamic">Save 0</p>';
                                            }
                                        } else {
                                            $html .= '<p class="product-discount-dynamic">Save 0</p>';
                                        }
                                    } else {
                                        if ($collection_products->active == 1) {
                                            if ($collection_products->discount_type == 1) {
                                                $html .= '<p class="product-discount">Save ' . $collection_products->discount_value . '$ </p>';
                                            } else {
                                                $html .= '<p class="product-discount">Save ' . $collection_products->discount_value . ' %</p>';
                                            }
                                        } else {
                                            $html .= '<p class="product-discount-dynamic">Save 0</p>';
                                        }
                                    }
                                } else {
                                    $html .= '<p class="product-discount-dynamic">Save 0</p>';
                                }
                            } else {
                                if ($c_product->discount != null) {
                                    $currentDate = time();
                                    $fromDate = strtotime($c_product->discount->from_date);
                                    $toDate = strtotime($c_product->discount->end_date);
                                    if ($c_product->discount->active == 1) {
                                        if ($c_product->limited == 1 && $currentDate >= $fromDate && $currentDate <= $toDate) {
                                            if ($c_product->discount->discount_type == 1) {
                                                $html .= '<p class="product-discount">Save ' . $c_product->discount->discount_value . ' $</p>';
                                            } else {
                                                $html .= '<p class="product-discount">Save ' . $c_product->discount->discount_value . '%</p>';
                                            }
                                        } else {
                                            if ($c_product->discount->discount_type == 1) {
                                                $html .= '<p class="product-discount">Save ' . $c_product->discount->discount_value . ' $</p>';
                                            } else {
                                                $html .= '<p class="product-discount">Save ' . $c_product->discount->discount_value . '%</p>';
                                            }
                                        }
                                    } else {
                                        $html .= '<p class="product-discount-dynamic">Save 0</p>';
                                    }
                                } else {
                                    $html .= '<p class="product-discount-dynamic">Save 0</p>';
                                }
                            }
                            $html .= '</div>
                                    <div class="product-details">
                                        <a class="product-detail-desc texttracate" href="' . url('/') . '/product/' . $c_product->permalink . '">' . $c_product->title . '</a>
                                        <p><span class="product-detail-amount">$';

                            $discountedPriceHtml = '';

                            if ($c_product->discount_id == 0) {
                                if ($collection_products) {
                                    if ($collection_products->active == 1) {
                                        if ($collection_products->limited == 1) {
                                            $currentDate = time();
                                            $fromDate = strtotime($collection_products->from_date);
                                            $toDate = strtotime($collection_products->end_date);
                                            if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                                                if ($collection_products->discount_type == 1) {
                                                    $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($collection_products->discount_value ?? 0));
                                                } else {
                                                    $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($collection_products->discount_value ?? 0) / 100), 2);
                                                }
                                            } else {
                                                $discountedPriceHtml .= ProductClass::getOptionValue('_price', $c_product->metas);
                                            }
                                        } else {
                                            if ($collection_products->discount_type == 1) {
                                                $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($collection_products->discount_value ?? 0));
                                            } else {
                                                $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($collection_products->discount_value ?? 0) / 100), 2);
                                            }
                                        }
                                    } else {
                                        $discountedPriceHtml .= ProductClass::getOptionValue('_price', $c_product->metas);
                                    }
                                } else {
                                    $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($collection_products->discount_value ?? 0));
                                }
                            } else {
                                if ($c_product->discount != null) {
                                    $currentDate = time();
                                    $fromDate = strtotime($c_product->discount->from_date);
                                    $toDate = strtotime($c_product->discount->end_date);
                                    if ($c_product->discount->active == 1) {
                                        if ($c_product->limited == 1 && $currentDate >= $fromDate && $currentDate <= $toDate) {
                                            if ($c_product->discount->discount_type == 1) {
                                                $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($c_product->discount->discount_value ?? 0));
                                            } else {
                                                $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($c_product->discount->discount_value ?? 0) / 100), 2);
                                            }
                                        } else {
                                            $discountedPriceHtml .= ProductClass::getOptionValue('_price', $c_product->metas);
                                        }
                                    } else {
                                        $discountedPriceHtml .= ProductClass::getOptionValue('_price', $c_product->metas);
                                    }
                                } else {
                                    $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($c_product->discount->discount_value ?? 0) / 100), 2);
                                }
                            }

                            if ($discountedPriceHtml == ProductClass::getOptionValue('_price', $c_product->metas)) {
                                $html .= $discountedPriceHtml;
                            } else {
                                $html .= $discountedPriceHtml . '</span><small class="product-detail-discount"><del>$' . ProductClass::getOptionValue('_price', $c_product->metas) . '</del></small></p>';
                            }
                            $btn_disabled = '';
                            $btn_text = 'Add to Cart';
                            $product_stock = ProductClass::getOptionValue('_stock', $c_product->metas);
                            if ($product_stock == 0) {
                                $btn_disabled = 'disabled';
                                $btn_text = 'Out of Stock';
                            }
                            $html .= '</div>
                                    <div class="product-detail-addtocart-btn">
                                        <a class="TextDecor">
                                            <button class="btn futured-product-add-tocart-btn"' . $btn_disabled . '
                                                    data-gst="' . ProductClass::getOptionValue('_gst', $c_product->metas) . '"
                                                    data-title="' . $c_product->title . '"
                                                    data-amount="' . $discountedPriceHtml . '"
                                                    data-p_id="' . $c_product->id . '"
                                                    data-featured_image="' . url('/') . '/' . $c_product->featured_image . '">
                                                ' . $btn_text . '
                                            </button>
                                        </a>
                                    </div>
                                    <div class="product-detail-tags">';
                            if ($c_product->discount_id == 0) {
                                $badgeText = $collection_products->badge_text ?? '';
                                $badgeColor = $collection_products->badge_color ?? '';
                                $imagePaths = explode(',', ($collection_products->badge_image ?? ''));

                                foreach ($imagePaths as $imagePath) {
                                    if (!empty($imagePath)) {
                                        $html .= '<img src="' . asset($imagePath) . '" class="m-1" alt="">';
                                    }
                                }

                                $textPaths = explode(',', $badgeText);
                                foreach ($textPaths as $textPath) {
                                    if (!empty($textPath) && $textPath !== "") {
                                        $html .= '<a style="border: 2px solid ' . $badgeColor . '; color: ' . $badgeColor . ';">' . $textPath . '</a>';
                                    } else {
                                        $badgeColor = isset($c_product->discount) ? $c_product->discount->badge_color : '';
                                        $html .= '';
                                    }
                                }
                            } else {
                                $badge_text = isset($collection_products) ? $collection_products->badge_text : '';
                                $textPaths = explode(',', $badge_text);
                                $image_badge = isset($c_product->discount) ? $c_product->discount->badge_image : '';
                                $imagePaths = explode(',', $image_badge);
                                foreach ($textPaths as $textPath) {
                                    if (!empty($textPaths) && $textPath !== "") {
                                        $badgeColor = isset($c_product->discount) ? $c_product->discount->badge_color : '';
                                        $html .= '<a style="border: 2px solid ' . $badgeColor . '; color: ' . $badgeColor . ';">' . implode(', ', $textPaths) . '</a>';
                                    } else {
                                        $badgeColor = isset($c_product->discount) ? $c_product->discount->badge_color : '';
                                        $html .= '';
                                    }
                                }
                                foreach ($imagePaths as $imagePath) {
                                    if (!empty($imagePath)) {
                                        $html .= '<img src="' . asset($imagePath) . '" class="m-1" alt="">';
                                    }
                                }
                            }
                            $html .= '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
                        }
                    }
                } else {
                    $html .= '<div class="text-center"><p class="text-danger" style="font-size: 16px; font-weight: bold;">Sorry No Product Found in this collection.</p></div>';
                }
            }
        } else {
            $html .= '<div class="col-lg-2 col-md-4 col-sm-12 col-border-top-r">
                        <div class="product-detail-section">
                        <p><b>No product found in this collection. You can explore more collection using the dropdown above.</b></p>';
        }

        if ($request->ajax()) {
            return response()->json(['html' => $html]);
        }
    }

    public function collection_product(Request $request)
    {
        $perPage = 1;
        $collectionfirst = Collection::with(['products.metas', 'products.discount'])->orderBy('created_at', 'desc')->first();
        $collectionproducts = Collection::with(['products.metas', 'products.discount'])->where('active', 1)->where('permalink', $request->permalink)->orderBy('created_at', 'desc')->take(30)
            ->get();
        $allCollections = Collection::where('active', 1)->with(['products.metas', 'products.discount'])->get();
        $selectedCollection = Collection::where('permalink', $request->permalink)->first();

        if ($collectionproducts->isNotEmpty()) {
            $collection_Title = $collectionfirst->title;
            if (!empty($seo_title = CollectionClass::getOptionValue('_seo_title', $collectionfirst->CollectionMeta))) {
                $product_Title = $seo_title;
            }
            $meta_details = [
                'title' => $collection_Title . ' | ' . $this->website_title,
                'keywords' => CollectionClass::getOptionValue('_seo_tags', $collectionfirst->CollectionMeta),
                'description' => CollectionClass::getOptionValue('_seo_description', $collectionfirst->CollectionMeta),
                'author' => 'Sydney Quality Meat Org',
            ];
            return view('Website.GeneralPages.collection-product', compact('meta_details', 'collectionproducts', 'selectedCollection', 'allCollections'));
        } else {
            $meta_details = [
                'title' => 'Collection Not Found! | Sydney Quality Meat',
                'keywords' => 'tools, genuine, hardware, makita, australia',
                'description' => 'Wholesale Hardware selling website.',
                'author' => 'Sydney Quality Meat Org',
            ];

            return view('Website.GeneralPages.NotFoundPage.collection', compact('meta_details'));
        }
    }

    public function CollectionProducts(Request $request)
    {
        $selectedCollectionId = $request->input('collection_id');
        $collectionproducts = Collection::with(['products.metas', 'products.discount'])
            ->where('id', $selectedCollectionId)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        $html = '';

        foreach ($collectionproducts as $collectionproduct) {
            if ($collectionproduct->products->isNotEmpty()) {
                foreach ($collectionproduct->products as $c_product) {
                    $discountedPrice = 0;
                    $fromDate = 0;
                    $currentDate = 0;
                    $toDate = 0;
                    if (isset($c_product) && $c_product->discount !== null) {
                        $currentDate = time();
                        $discountedPrice = 0;
                        if ($currentDate >= $fromDate && $currentDate <= $toDate) {
                            if ($c_product->discount->limited == 0) {
                                // Apply the discount without checking date range
                                $discountValue = $c_product->discount->discount_value;
                                $actualPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                                $discountAmount = ($discountValue / 100) * $actualPrice;
                                $discountedPrice = $actualPrice - $discountAmount;
                            } elseif ($c_product->discount->limited == 1) {
                                // Apply the discount only if within the valid date range
                                $fromDate = strtotime($c_product->discount->from_date);
                                $toDate = strtotime($c_product->discount->end_date);
                                $discountValue = $c_product->discount->discount_value;
                                $actualPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                                $discountAmount = ($discountValue / 100) * $actualPrice;
                                $discountedPrice = $actualPrice - $discountAmount;
                            }
                        } else {
                            // Discount is not applicable, use original price
                            $discountedPrice = ProductClass::getOptionValue('_price', $c_product->metas);
                        }
                    }

                    if (isset($c_product) && $currentDate >= $fromDate && $currentDate <= $toDate) {
                        $html .= '<div class="col-lg-2 col-md-4 col-sm-12 col-border-top-r">
                        <div class="product-detail-section">
                            <div class="card product-card">
                                <div class="card-body">
                                    <div class="product-back-img">
                                        <div class="image-container" style="background-image: url(\'' . url('/') . '/' . $c_product->featured_image . '\');"></div>';
                        $collection_products = DB::table('collection_products')
                            ->join('collections', 'collections.id', '=', 'collection_products.collection_id')
                            ->join('discounts', 'discounts.id', '=', 'collections.discount_id')
                            ->where('product_id', $c_product->id)
                            ->first();

                        if ($c_product->discount_id == 0) {
                            if ($collection_products) {
                                if ($collection_products->discount_type == 1) {
                                    $html .= '<p class="product-discount">Save ' . $collection_products->discount_value . '$ </p>';
                                } else {
                                    $html .= '<p class="product-discount">Save ' . $collection_products->discount_value . ' %</p>';
                                }
                            } else {
                                $html .= '<p class="product-discount-dynamic">Save 0</p>';
                            }
                        } else {
                            if ($c_product->discount != null) {
                                if ($c_product->discount->discount_type == 1) {
                                    $html .= '<p class="product-discount">Save ' . $c_product->discount->discount_value . ' $</p>';
                                } else {
                                    $html .= '<p class="product-discount">Save ' . $c_product->discount->discount_value . '%</p>';
                                }
                            } else {
                                $html .= '<p class="product-discount-dynamic">Save 0</p>';
                            }
                        }
                        $html .= '</div>
                                    <div class="product-details">
                                        <a class="product-detail-desc texttracate" href="' . url('/') . '/product/' . $c_product->permalink . '">' . $c_product->title . '</a>
                                        <p><span class="product-detail-amount">$';

                        $discountedPriceHtml = '';

                        if ($c_product->discount_id == 0) {

                            if ($collection_products) {

                                if ($collection_products->discount_type == 1) {
                                    $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($collection_products->discount_value ?? 0));
                                } else {
                                    $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($collection_products->discount_value ?? 0) / 100), 2);
                                }
                            } else {
                                $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($collection_products->discount_value ?? 0));
                            }
                        } else {
                            if ($c_product->discount != null) {
                                if ($c_product->discount->discount_type == 1) {
                                    $discountedPriceHtml .= intval(ProductClass::getOptionValue('_price', $c_product->metas) - ($c_product->discount->discount_value ?? 0));
                                } else {
                                    $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($c_product->discount->discount_value ?? 0) / 100), 2);
                                }
                            } else {
                                $discountedPriceHtml .= number_format(ProductClass::getOptionValue('_price', $c_product->metas) - (ProductClass::getOptionValue('_price', $c_product->metas) * ($c_product->discount->discount_value ?? 0) / 100), 2);
                            }
                        }
                        $html .= $discountedPriceHtml . '</span><small class="product-detail-discount"><del>$' . ProductClass::getOptionValue('_price', $c_product->metas) . '</del></small></p>
                                    </div>
                                    <div class="product-detail-addtocart-btn">
                                        <a class="TextDecor">
                                            <button class="btn futured-product-add-tocart-btn"
                                                    data-gst="' . ProductClass::getOptionValue('_gst', $c_product->metas) . '"
                                                    data-title="' . $c_product->title . '"
                                                    data-amount="' . $discountedPriceHtml . '"
                                                    data-p_id="' . $c_product->id . '"
                                                    data-featured_image="' . url('/') . '/' . $c_product->featured_image . '">
                                                Add to Cart
                                            </button>
                                        </a>
                                    </div>
                                    <div class="product-detail-tags">';
                        if ($c_product->discount_id == 0) {
                            $badgeText = $collection_products->badge_text ?? '';
                            $badgeColor = $collection_products->badge_color ?? '';
                            $imagePaths = explode(',', ($collection_products->badge_image ?? ''));

                            foreach ($imagePaths as $imagePath) {
                                if (!empty($imagePath)) {
                                    $html .= '<img src="' . asset($imagePath) . '" class="m-1" alt="">';
                                }
                            }

                            $textPaths = explode(',', $badgeText);
                            foreach ($textPaths as $textPath) {
                                if (!empty($textPath) && $textPath !== "") {
                                    $html .= '<a style="border: 2px solid ' . $badgeColor . '; color: ' . $badgeColor . ';">' . $textPath . '</a>';
                                } else {
                                    $badgeColor = isset($c_product->discount) ? $c_product->discount->badge_color : '';
                                    $html .= '';
                                }
                            }
                        } else {
                            $badge_text = isset($collection_products) ? $collection_products->badge_text : '';
                            $textPaths = explode(',', $badge_text);
                            $image_badge = isset($c_product->discount) ? $c_product->discount->badge_image : '';
                            $imagePaths = explode(',', $image_badge);
                            foreach ($textPaths as $textPath) {
                                if (!empty($textPaths) && $textPath !== "") {
                                    $badgeColor = isset($c_product->discount) ? $c_product->discount->badge_color : '';
                                    $html .= '<a style="border: 2px solid ' . $badgeColor . '; color: ' . $badgeColor . ';">' . implode(', ', $textPaths) . '</a>';
                                } else {
                                    $badgeColor = isset($c_product->discount) ? $c_product->discount->badge_color : '';
                                    $html .= '';
                                }
                            }
                            foreach ($imagePaths as $imagePath) {
                                if (!empty($imagePath)) {
                                    $html .= '<img src="' . asset($imagePath) . '" class="m-1" alt="">';
                                }
                            }
                        }
                        $html .= '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                }
            } else {
                $html .= '<div class="text-center"><p class="text-danger" style="font-size: 16px; font-weight: bold;">Sorry No Product Found in this collection.</p></div>';
            }
        }

        if ($request->ajax()) {
            return response()->json(['html' => $html]);
        }
    }
}
