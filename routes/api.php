<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HeaderController;
use App\Http\Controllers\API\AuthController;
// use App\Http\Controllers\API\EbayController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SiteMapController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/





Route::post('/admin/store/Menu',[HeaderController::class,'storeMenu']);
Route::post('/admin/store/MenuMeta/{menu_id}',[HeaderController::class,'storeMenuMeta']);
Route::delete('/menus/{menu_id}',[ HeaderController::class,'deleteMenu']);
Route::delete('/submenus/{id}',[ HeaderController::class,'deleteMenuMeta']);
Route::get('/sitemap-map', [SiteMapController::class, 'generateSitemapApi']);




Route::prefix('categories')->group(function () {
    Route::get('/', [\App\Http\Controllers\API\CategoryController::class, 'index']);
    Route::get('/{sku}/products', [\App\Http\Controllers\API\CategoryController::class, 'getProductsByCategory']);
    Route::get('/child_categories/{sku}/products', [\App\Http\Controllers\API\CategoryController::class, 'childCategoryProducts']);
    Route::get('/grandchild_categories/{sku}/products', [\App\Http\Controllers\API\CategoryController::class, 'grandchildCategoryProducts']);
});
Route::get('/collections', [\App\Http\Controllers\API\CollectionController::class,'index']);
Route::get('/collections/{id}', [\App\Http\Controllers\API\CollectionController::class, 'getCollectionByPermalink']);

Route::get('/latest-products', [\App\Http\Controllers\API\ProductController::class, 'index']);
Route::get('/virtual-products', [\App\Http\Controllers\API\ProductController::class, 'getFeatureProducts']);
Route::get('/discount-products', [\App\Http\Controllers\API\ProductController::class, 'getDiscountProducts']);
Route::get('/best-seller-products', [\App\Http\Controllers\API\ProductController::class, 'getBestSellerProducts']);
Route::get('/products/{permalink}', [\App\Http\Controllers\API\ProductController::class, 'getProductById'])->where('permalink', '.*');
Route::get('/gift-cards', [\App\Http\Controllers\API\GitCardController::class, 'getGiftCard']);
Route::get('/brands', [\App\Http\Controllers\API\BrandController::class, 'index']);
Route::get('/brands/{sku}/products', [\App\Http\Controllers\API\BrandController::class, 'getProductsByBrand']);
Route::get('/brand-sliders', [\App\Http\Controllers\API\BrandController::class, 'getBrandSliders']);
Route::get('/category-sliders', [\App\Http\Controllers\API\CategoryController::class, 'getcategorySliders']);

//Route::post('/update-prices', [\App\Http\Controllers\API\ProductController::class, 'updateProductPrices']);


// Auth routes
Route::post('/registered', [\App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/password-reset-email', [\App\Http\Controllers\Admin\AuthController::class, 'sendResetLinkEmail']);
Route::post('/password-update', [\App\Http\Controllers\Admin\AuthController::class, 'updatePassword']);
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/subscribe', [\App\Http\Controllers\API\NewLetterSubscriptionController::class, 'store']);
Route::post('/contact-us', [\App\Http\Controllers\API\NewLetterSubscriptionController::class, 'contactUs']);
Route::post('/order-return', [\App\Http\Controllers\API\OrderController::class, 'orderReturnForm']);
Route::post('/place-order', [\App\Http\Controllers\API\OrderController::class, 'store']);
Route::put('/orders/{id}', [\App\Http\Controllers\API\OrderController::class, 'update']);
Route::post('/check-email', [\App\Http\Controllers\API\AuthController::class, 'checkUserEmail']);
Route::post('/update-password', [\App\Http\Controllers\API\AuthController::class, 'updatePassword']);
Route::post('/confirm-otp', [\App\Http\Controllers\API\AuthController::class, 'confirmOtp']);
Route::get('/shop', [\App\Http\Controllers\API\ProductController::class, 'shop']);
Route::get('/search-product', [\App\Http\Controllers\API\ProductController::class, 'searchProduct']);
Route::get('orders-detail', [\App\Http\Controllers\API\OrderController::class, 'show']);
Route::get('/encrypted-paypal-keys', [\App\Http\Controllers\API\OrderController::class, 'getEncryptedPayPalKeys']);

// In routes/api.php (or routes/web.php for web routes)
Route::post('guest/track-order/{order}', [\App\Http\Controllers\API\OrderController::class, 'guestTrackOrder']);

Route::get('/user', [AuthController::class, 'getUser']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/profile', [\App\Http\Controllers\API\ProfileController::class, 'index']);
    Route::post('/profile/update', [\App\Http\Controllers\API\ProfileController::class, 'update']);

    Route::get('/orders', [\App\Http\Controllers\API\OrderController::class, 'index']);
    Route::get('/user-order', [\App\Http\Controllers\API\OrderController::class, 'userOrders']);
    Route::delete('/orders/{id}', [\App\Http\Controllers\API\OrderController::class, 'destroy']);

    Route::prefix('user-addresses')->group(function () {
        Route::get('/', [\App\Http\Controllers\API\UserAddressController::class, 'index']);
        Route::post('/store', [\App\Http\Controllers\API\UserAddressController::class, 'store']);
        Route::put('/{userAddress}', [\App\Http\Controllers\API\UserAddressController::class, 'update']);
        Route::delete('/{userAddress}', [\App\Http\Controllers\API\UserAddressController::class, 'destroy']);
    });
});

// Route::get('/ebay/auth', [EbayController::class, 'initiateAuth']);
// Route::get('/ebay/list',[EbayController::class, 'listAllProducts']);
// Route::get('/ebay/callback', [EbayController::class, 'handleEbayCallback']);
// Route::get('/ebay/check-missing-products', [EbayController::class, 'checkMissingProductsOnEbay']);
Route::get('/social-links', [\App\Http\Controllers\API\UserAddressController::class, 'socialMediaLinks']);



// Step 2: Handle eBay callback and exchange code for token
Route::fallback(function () {
    return response()->json(['message' => ' not found'], 404);
});



Route::get('products-export', [ProductController::class, 'exportCsv']);
Route::get('/export-products/gm', [ProductController::class, 'exportGm']);




Route::get('/process-product-images', [ProductController::class, 'processProductImages']);

Route::get('/generate-product-descriptions', [ProductController::class, 'generateDescriptions']);



