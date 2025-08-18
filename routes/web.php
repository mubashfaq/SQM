<?php

use App\Http\Middleware\EndUser;
use App\Http\Middleware\SuperUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\HeaderController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Website\PagesController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\GiftCardController;
use App\Http\Controllers\Payment\PayPalController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Admin\AreaGroupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Website\ProductController;
use App\Http\Controllers\Admin\CollectionController;

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Payment\AfterpayController;
use App\Http\Controllers\Admin\ContactlistController;

use App\Http\Controllers\Admin\DynamicPageController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Website\ContactUsController;

use App\Http\Controllers\Admin\InvoiceReturnController;
use App\Http\Controllers\Payment\ReturnOrderController;
use App\Http\Controllers\Website\UserAddressController;
use App\Http\Controllers\Admin\CategorysliderController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\GeneralSettingsController;

use App\Http\Controllers\Website\CustomerqueryContorller;
use App\Http\Controllers\Website\AuthController as WebsiteAuthController;
use App\Http\Controllers\Website\UserController as WebsiteUserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


#Error Pages
Route::view('/404', 'Admin.Errors.404')->name('error.404');
Route::view('/500', 'Admin.Errors.500')->name('error.500');
Route::get('/generate-sitemap', function() {
    Artisan::call('sitemap:generate');
    return 'Sitemap generated!';
});

Route::get('datafeed', [\App\Http\Controllers\API\ProductController::class, 'getLatestFileUrl']);
Route::get('datafeed/gm', [\App\Http\Controllers\API\ProductController::class, 'getGmLatestFileUrl']);



#Non-Authenticated Routes
Route::post('/admin/login', [AuthController::class, 'login'])->name('Admin.Login');
Route::post('/admin/forget-password', [AuthController::class, 'sendResetLinkEmail'])->name('Admin.ForgotPassword');
Route::get('password/otp', [AuthController::class, 'showOtpForm'])->name('password.otp');
Route::post('/password/match-otp', [AuthController::class, 'matchOtp'])->name('password.match-otp');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('password.resend-otp');

Route::get('/admin/reset-password', function () {
    return view('Admin.Auth.reset_password');
})->name('Admin.Auth.ResetPassword');
Route::post('/admin/update-password/submit', [AuthController::class, 'updatePassword'])->name('Admin.PasswordUpdate.Submit');


Route::get('/admin/login', function () {
    $user = Auth::user();
    if (!$user) {
        return view('Admin.Auth.login');
    }
    return redirect('/admin');
});
Route::middleware(['super_user'])->group(function () {
    Route::post('admin/setting/store', [GeneralSettingsController::class, 'storeSetting'])->name('store-general-setting');
     Route::group(['prefix' => 'admin'], function () {
            Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
            Route::get('/blogs/create', [App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blogs.create');
            Route::post('/blogs', [App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blogs.store');
            Route::get('/blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'show'])->name('blogs.show');
            Route::get('/blogs/{blog}/edit', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blogs.edit');
            Route::put('/blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blogs.update');
            Route::delete('/blogs/{blog}', [App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blogs.destroy');
            Route::put('/blogs/{blog}/update-status', [App\Http\Controllers\Admin\BlogController::class, 'updateStatus']);
            Route::get('/blogs/list/trash', [App\Http\Controllers\Admin\BlogController::class, 'trash'])->name('Admin.Blog.Trash');
            Route::put('/blogs/restore/{id}', [App\Http\Controllers\Admin\BlogController::class, 'restore'])->name('blogs.restore');
            Route::post('/blogs/check-permalink', [App\Http\Controllers\Admin\BlogController::class, 'checkPermalink'])->name('blogs.check.permalink');
    });
    Route::get('/admin/blog/child-categories/{category}', [BlogController::class, 'getChildCategories'])->name('admin.blog.child-categories');
    Route::get('/admin/blog/grand-child-categories/{childCategory}', [BlogController::class, 'getGrandChildCategories'])->name('admin.blog.grand-child-categories');
    Route::get('/admin/blog/grand-child-categories-by-category/{category}', [BlogController::class, 'getGrandChildCategoriesByCategory'])->name('admin.blog.grand-child-categories-by-category');

    #General
    Route::get('/admin', [DashboardController::class, 'index'])->name('Admin.Dashboard');
    Route::get('/admin/profile', [DashboardController::class, 'admin_profile'])->name('Admin.profile');
    Route::post('/admin/profile/update', [DashboardController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/admin/profile/change-password', [DashboardController::class, 'changePassword'])->name('admin.profile.change-password');

    Route::get('/admin-logout', [AuthController::class, 'logout'])->name('Admin.Logout');
    
    Route::post('/admin/update-password', [UserController::class, 'updatePassword'])->name('Admin.LoggedUpdatePassword');
    Route::post('/admin/update-profile-image', [UserController::class, 'updateProfileImage'])->name('Admin.ProfileImage');

    #Users
    Route::resource('/admin/users', UserController::class);
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/profile', [UserController::class, 'showUserProfile'])->name('user.profile');

    Route::get('/admin/users/list/customer', [UserController::class, 'customers'])->name('Customer.List');
    Route::put('/admin/users/{user}/update-status', [UserController::class, 'updateStatus'])->name('Admin.User.UpdateStatus');

    #Sliders
    Route::resource('/admin/sliders', SliderController::class);
    Route::put('/admin/sliders/{slider}/update-status', [SliderController::class, 'updateStatus'])->name('Admin.Slider.UpdateStatus');
    Route::get('/admin/sliders/list/trash', [SliderController::class, 'trash'])->name('Admin.Slider.Trash');

    #Product Brands
    Route::resource('/admin/brands', BrandController::class);
    Route::put('/admin/brands/{brand}/update-status', [BrandController::class, 'updateStatus'])->name('Admin.Brand.UpdateStatus');
    Route::get('/admin/brands/list/trash', [BrandController::class, 'trash'])->name('Admin.Brand.Trash');
    Route::post('/admin/brands/check-permalink', [BrandController::class, 'check_permalink'])->name('Admin.Brand.CheckPermalink');

    #Product Type
    Route::resource('/admin/product-type', ProductTypeController::class);

    #Product Categories
    #- Category
    Route::get('admin/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/fetch-all', [CategoryController::class, 'get_category'])->name('category');
    Route::get('/edit_category', [CategoryController::class, 'edit_category'])->name('category.edit');
    Route::post('/update_category/{id}', [CategoryController::class, 'update_category'])->name('category.update');
    Route::post('/delete_category', [CategoryController::class, 'delete'])->name('category.delete');
    Route::get('/show_category_detail', [CategoryController::class, 'show_category_detail'])->name('category.show_category_detail');


    #- child category
    Route::post('/store_child_category', [CategoryController::class, 'store_child_category'])->name('child.category.strore');
    Route::get('/admin/get_child_category', [CategoryController::class, 'get_child_category'])->name('child.category.index');
    Route::get('/edit_child_category', [CategoryController::class, 'edit_child_category'])->name('child.category.edit');
    Route::post('/update_child_category/{id}', [CategoryController::class, 'update_child_category'])->name('child.category.update');
    Route::post('/delete_child_catgory', [CategoryController::class, 'delete_child_catgory'])->name('child.category.delete');
    Route::get('/show_child_detail', [CategoryController::class, 'show_child_detail'])->name('child.category.show_detail');
    #- grandchild category
    Route::post('/store_grandchild_category', [CategoryController::class, 'store_grandchild_category'])->name('grandchild.category.store');
    Route::get('/admin/get_grandchild_category', [CategoryController::class, 'get_grandchild_category'])->name('grandchild.category.index');
    Route::get('/edit_grandchild_category', [CategoryController::class, 'edit_grandchild_category'])->name('grandchild.category.edit');
    Route::post('/update_grandchild_category/{id}', [CategoryController::class, 'update_grandchild_category'])->name('grandchild.category.update');
    Route::post('/delete_grandchild_catgory', [CategoryController::class, 'delete_grandchild_catgory'])->name('grandchild.category.delete');
    Route::get('/show_grandchild_detail', [CategoryController::class, 'show_grandchild_detail'])->name('grandchild.category.show');


    #Area Group
    Route::resource('/admin/area-groups', AreaGroupController::class);
    Route::post('/admin/area-groups/add-more-group-area', [AreaGroupController::class, 'add_more_group_areas'])->name('add-more-group-area');
    Route::post('/admin/area-groups/{area_group}', [AreaGroupController::class, 'update'])->name('area-groups.update');
    Route::delete('/admin/area-groups/{AreaGroup}', [AreaGroupController::class, 'destroy'])->name('area-groups.destroy');
    Route::get('/admin/area-groups/{areaGroupId}/codes/count', [AreaGroupController::class, 'getCodeCount'])->name('area-groups.codes.count');
    Route::get('/admin/area-groups/{area_group}/codes', [AreaGroupController::class, 'viewCode'])->name('area-groups.viewCode');
    Route::delete('/admin/area-groups/{areaGroupId}/codes/{id}', [AreaGroupController::class, 'deleteCode']);
    Route::put('/admin/area-groups/{areaGroupId}/codes/{id}', [AreaGroupController::class, 'updateCode']);

    #- Collections
    Route::resource('/admin/collections', CollectionController::class);
    Route::post('/admin/collections/store', [CollectionController::class, 'store'])->name('Admin.collection.store');
    // Route::post('/admin/collections/update/{id}', [CollectionController::class, 'update'])->name('Admin.collection.update');
    
    Route::put('/admin/collections/{collection}/update-status', [CollectionController::class, 'updateStatus'])->name('Admin.collection.UpdateStatus');
    Route::get('/admin/collections/list/trash', [CollectionController::class, 'trash'])->name('Admin.collection.Trash');
    Route::get('/admin/collections/collection-product', [CollectionController::class, 'collection_product'])->name('Admin.collection.CollectionProcut');
    Route::post('/store-collection-product', [CollectionController::class, 'store_collectionproduct']);
    // Route::get('/fetch-all-collectionproduct', [ProductCollection::class, 'get_collection_product']);
    Route::delete('/destroy-product/{id}', [CollectionController::class, 'destroy_prodcut']);
    Route::put('/admin/collections/{id}/update-collectproduct-Status', [CollectionController::class, 'update_collectproduct_Status'])->name('Admin.collection.updatecollectproductStatus');
    Route::get('/search-products', [CollectionController::class, 'searchProducts']);

    #- Products
    Route::resource('/admin/products', App\Http\Controllers\Admin\ProductController::class)->except('show');
    Route::post('/admin/products/product_search', [App\Http\Controllers\Admin\ProductController::class, 'productSearch']);
    Route::post('/admin/products/trash_search', [App\Http\Controllers\Admin\ProductController::class, 'trash_productSearch']);
    Route::post('/delete-gallery-image', [App\Http\Controllers\Admin\ProductController::class, 'deleteGalleryImage']);

    // to get paginated results
    Route::get('/admin/products/product_search', [App\Http\Controllers\Admin\ProductController::class, 'productSearch']);
        Route::get('export-product', [App\Http\Controllers\Admin\ProductController::class, 'exportProduct'])->name('export.product');

    Route::put('/admin/products/{product}/update-status', [App\Http\Controllers\Admin\ProductController::class, 'updateStatus'])->name('Admin.Product.UpdateStatus');
    Route::post('/admin/products/add_product_variation', [App\Http\Controllers\Admin\ProductController::class, 'addProductVariation'])->name('Admin.Product.addproductvariation');
    Route::get('/admin/products/list/trash', [App\Http\Controllers\Admin\ProductController::class, 'trash'])->name('Admin.Product.Trash');
    Route::post('/admin/products/check-permalink', [App\Http\Controllers\Admin\ProductController::class, 'check_permalink'])->name('Admin.Product.CheckPermalink');

    Route::post('/admin/products/import/csv', [App\Http\Controllers\Admin\ProductController::class, 'import'])->name('Admin.Product.Import');

    #- Permission
    Route::resource('/admin/role', PermissionController::class);
    Route::get('/admin/permission/{id}', [PermissionController::class, 'permission']);
    Route::post('/store-permission', [PermissionController::class, 'storepermission']);
    

    #Inventory
    Route::get('/admin/products/manage/inventory', [InventoryController::class, 'index'])->name('Admin.Product.InventoryList');
    Route::get('/admin/products/inventory/search', [InventoryController::class, 'search'])->name('Admin.Product.Inventory.Search');
    Route::post('/admin/products/update/inventory', [InventoryController::class, 'update'])->name('Admin.Product.Inventory.Update');
    Route::post('/admin/inventory/import/csv', [InventoryController::class, 'import_data'])->name('Admin.Inventory.Import');
    Route::get('/admin/inventory/export-csv', [InventoryController::class, 'exportAllDataToCSV'])->name('admin.inventory.export-csv');


    #- Discounts
    Route::resource('/admin/discounts', DiscountController::class);
    Route::put('/admin/discounts/{discount}/update-status', [DiscountController::class, 'updateStatus'])->name('Admin.discount.UpdateStatus');
    Route::get('/admin/discounts/list/trash', [DiscountController::class, 'trash'])->name('Admin.discount.Trash');

    #- Category Slider
    Route::resource('/admin/catsliders', CategorysliderController::class);
    Route::put('/admin/catsliders/{catslider}/update-status', [CategorysliderController::class, 'updateStatus'])->name('Admin.catslider.UpdateStatus');
    Route::get('/admin/catsliders/list/trash', [CategorysliderController::class, 'trash'])->name('Admin.catslider.Trash');

    #-Brand Sliders
    Route::resource('/admin/sliders', SliderController::class);
    Route::put('/admin/sliders/{slider}/update-status', [SliderController::class, 'updateStatus'])->name('Admin.Slider.UpdateStatus');
    Route::get('/admin/sliders/list/trash', [SliderController::class, 'trash'])->name('Admin.Slider.Trash');

    #POS
    Route::resource('/admin/invoices', InvoiceController::class);
    Route::get('/admin/invoices/filter/search', [InvoiceController::class, 'index_x'])->name('invoices.search');
    Route::get('/admin/invoices/delete/{id}', [InvoiceController::class, 'destroy_new'])->name('invoices.destroyJson');


    Route::get('/admin/invoices/products/search', [InvoiceController::class, 'searchProducts'])->name('search.products');
    Route::get('/admin/invoices/products/select', [InvoiceController::class, 'selectProduct'])->name('search.signle-products');
    Route::post('/admin/invoices/is_delete/{id}', [InvoiceController::class, 'is_delete'])->name('invoice.is_delete');
    Route::get('/admin/invoices/download/invoice/{id}', [InvoiceController::class, 'generatePdf'])->name('invoice.generatePdf');

    #Online Store Orders
    Route::get('/admin/orders', [POSController::class, 'index'])->name('admin.orders');
    Route::get('/admin/customers/{id}', [POSController::class, 'CustomerDetails'])->name('admin.customers.details');

    Route::post('/change-order-status', [POSController::class, 'change_order_status']);
    Route::get('/admin/filter/orders', [POSController::class, 'filter_order']);
    Route::delete('/admin/delete/order/{id}', [POSController::class, 'delete_order'])->name('admin.order.delete');
    Route::post('/admin/orders/update/{order}', [POSController::class, 'update'])->name('admin.orders.update');
    Route::get('/order/update/{id}', [POSController::class, 'update_order_view'])->name('order.update');
    Route::get('/admin/order/detail', [POSController::class, 'detail'])->name('admin.order.detail');
    Route::post('/admin/orders/bulk-delete', [POSController::class, 'bulk_delete'])->name('Admin.orders.bulk-delete');
    Route::get('/admin/orders/cancelled', [POSController::class, 'cancelled_orders_list'])->name('Admin.orders.cancelled');
    Route::get('/print-guest-order-invoice/{id}', [UserAddressController::class, 'print_os_invoice'])->name('Website.print_guest_order_invoice');

    #Online Store Return Orders
    Route::get('/admin/orders/return', [POSController::class, 'return_order_list'])->name('Admin.Return.Orders');
    Route::get('/admin/return/order/detail', [POSController::class, 'return_detail'])->name('admin.return.order.detail');
    Route::post('/admin/return/order/delete', [POSController::class, 'delete_returned_order'])->name('admin.return.order.delete');
    Route::post('/admin/return/orders/bulk-delete', [POSController::class, 'bulk_delete_returned_order'])->name('Admin.return.orders.bulk-delete');

    # Return Order
    Route::get('/admin/orders/return/create', [ReturnOrderController::class, 'return_order_page'])->name('admin.Return.Order');
    Route::post('/admin/orders/return/create', [ReturnOrderController::class, 'return_order_page'])->name('Website.Return.Order.Details');
    Route::post('/update-return-array', [ReturnOrderController::class, 'update_return_array'])->name('Website.Return.Order.Update');
    Route::post('/paypal-refund', [ReturnOrderController::class, 'initiatePayPalRefund'])->name('Paypal_Refund');
    Route::post('/stripe-refund', [ReturnOrderController::class, 'initiateStripeRefund'])->name('Stripe_Refund');
    Route::post('/gift-card-refund', [ReturnOrderController::class, 'initialGiftCardRefund'])->name('Gift_Card_Refund');
    Route::get('/print-return-order-invoice/{id}', [UserAddressController::class, 'print_return_invoice'])->name('Website.Print_Return_Invoice');

    #POS Invoice Return
    Route::resource('/admin/invoices_return', InvoiceReturnController::class);
    Route::post('/admin/invoices_return/is_delete/{id}', [InvoiceReturnController::class, 'is_delete'])->name('invoice_return.is_delete');
    Route::get('/admin/return_invoice/download/{id}', [InvoiceReturnController::class, 'generatePdf'])->name('invoice_return.generatePdf');
    Route::get('/admin/search/invoices/select', [InvoiceReturnController::class, 'selectInvoice']);
    Route::get('/admin/search/invoices', [InvoiceReturnController::class, 'searchInvoices']);

    #Memberships
    Route::resource('/admin/memberships', MembershipPlanController::class);
    Route::get('/admin/memberships/customers/search', [MembershipPlanController::class, 'search_customer'])->name('search.customer');
    Route::get('/admin/memberships/plans/subscriber_list', [MembershipPlanController::class, 'search_subscribers'])->name('plan.subscribers');
    Route::get('/admin/memberships/plans/add_subscriber', [MembershipPlanController::class, 'add_subscribers'])->name('plan.add_subscribers');
    Route::get('/admin/memberships/plans/change_default_signup/{plan_id}', [MembershipPlanController::class, 'setDefaultSignUpPlan'])->name('plan.signup_plan');
    Route::get('/admin/memberships/plans/remove_subscriber', [MembershipPlanController::class, 'removeSubscriber'])->name('memberships.removeSubscriber');

    Route::get('/admin/memberships/plans/subscriber', [MembershipPlanController::class, 'subscriber_plan'])->name('plan.subscriber_plan');

    #Gift Cards
    Route::resource('/admin/gift-cards', GiftCardController::class);
    Route::put('/admin/gift-cards/{id}/update-status', [GiftCardController::class, 'updateStatus'])->name('gift_card.update_status');
    Route::get('/admin/gift-cards/expired/list', [GiftCardController::class, 'expired_list'])->name('Admin.gift_card.expired');
    Route::get('/admin/gift-cards/deleted/list', [GiftCardController::class, 'deleted_list'])->name('Admin.gift_card.deleted');
    Route::get('/admin/gift-cards/purchased/list/{card_id}', [GiftCardController::class, 'purchased_list'])->name('Admin.gift_card.purchased');

    #-Conctact and Queries
    Route::resource('/admin/contacts', ContactlistController::class);
    Route::post('/contacts/bulk-delete', [ContactlistController::class, 'bulkDelete'])->name('contacts.bulk-delete');
    Route::get('/contact_details/{id}', [ContactlistController::class, 'getcontactdetails']);
    Route::resource('/admin/quries', CustomerqueryContorller::class);
    Route::post('/query/bulk-delete', [CustomerqueryContorller::class, 'bulkDelete'])->name('query.bulk-delete');
    Route::get('/customer_querie_details/{id}', [CustomerqueryContorller::class, 'getCustomerQueryDetail']);

    Route::get('/admin/newsletter/subscriptions', [GeneralSettingsController::class, 'newsletter_subscriptions'])->name('Admin.Subscription.index');
    Route::post('/admin/subscription/destroy/{id}', [GeneralSettingsController::class, 'subscription_destroy'])->name('Admin.Subscription.destroy');
    Route::post('/admin/subscription/bulk-delete', [GeneralSettingsController::class, 'subcription_bulk_delete'])->name('Admin.subscription.bulk-delete');


    Route::get('/admin/general-settings', [GeneralSettingsController::class, 'index']);
    

    Route::get('/admin/header', [HeaderController::class, 'index']);
    Route::post('/admin/header/save', [HeaderController::class, 'save'])->name('Admin.Header.Save');

    #-- Dynamic Pages Crud
    // Route::resource('/admin/pages', DynamicPageController::class);
    Route::get('/admin/pages', [DynamicPageController::class, 'index'])->name('pages.index');
    Route::get('/admin/pages/create', [DynamicPageController::class, 'create'])->name('pages.create');
    Route::post('/admin/pages/store', [DynamicPageController::class, 'store'])->name('pages.store');
    Route::get('/admin/pages/{page}/edit', [DynamicPageController::class, 'edit'])->name('pages.edit');
    Route::put('/admin/pages/update/{id}', [DynamicPageController::class, 'update'])->name('pages.update');
    Route::delete('/admin/pages/{page}', [DynamicPageController::class, 'destroy'])->name('pages.destroy');
    Route::put('/admin/pages/{page}/update-status', [DynamicPageController::class, 'updateStatus'])->name('Admin.Page.UpdateStatus');
    Route::get('/admin/pages/list/trash', [DynamicPageController::class, 'trash'])->name('Admin.Pages.Trash');
    Route::post('/admin/pages/check_permalink', [DynamicPageController::class, 'check_permalink'])->name('Admin.Pages.permalink_check');
});

        ///blogs
        Route::get('/blog', [BlogController::class, 'allBlogs'])->name('blogs');
        Route::get('/blog/{permalink}', [BlogController::class, 'getBlogbyPermalink'])->name('blog.permalink');
    

        //Prduct Detail
        Route::get('/product-detail/{permalink}', [App\Http\Controllers\Website\ProductController::class,'product_detail']);
        Route::pattern('home', '^(|home)$');
        Route::get('/{home}', [PagesController::class, 'home'])->name('Website.GeneralPages.Home');
        Route::get('/privary', [PagesController::class, 'privary']);
        Route::get('/terms', [PagesController::class, 'terms']);
    
    
    
        Route::get('/filtered-products', [PagesController::class, 'filter_products'])->name('Website.Filter_Products');
        Route::get('/all-categories', [PagesController::class, 'all_categories'])->name('Website.GeneralPages.All_Categories');
    
    
    
        Route::get('/single-product', [PagesController::class, 'single_product'])->name('Website.GeneralPages.Single_Product');
        Route::get('/kit-product', [PagesController::class, 'kit_product'])->name('Website.GeneralPages.Kit_Product');
        Route::get('/tool-brands', [PagesController::class, 'brands'])->name('Website.GeneralPages.Brands');
        Route::get('/tool-brands/{brand}', [PagesController::class, 'brand_details'])->name('Website.GeneralPages.Brands_View');
    
        // Route::get('/cart', [PagesController::class, 'cart'])->name('Website.GeneralPages.Cart');
        Route::get('/checkout', [PagesController::class, 'checkout'])->name('Website.GeneralPages.Checkout');
    
        Route::get('/sign-in', [PagesController::class, 'login'])->name('Website.GeneralPages.Login');
        Route::get('/sign-up', [PagesController::class, 'register'])->name('Website.GeneralPages.register');
        Route::get('/forget-password', [PagesController::class, 'forgetPassword'])->name('Website.ForgetPassword');
    
        Route::post('/sign-in', [WebsiteAuthController::class, 'login'])->name('Website.Login');
        Route::post('/sign-up', [WebsiteUserController::class, 'register'])->name('Website.RegisterUser');
        Route::post('/user-forget-password', [WebsiteAuthController::class, 'sendResetLinkEmail'])->name('Website.ForgotPasswordRoute');
        Route::get('/reset-password', [PagesController::class, 'reset_password'])->name('Website.ResetPassword');
    
        Route::post('/calculate-price', [PagesController::class, 'calculatePrice'])->name('calculate-price');
        Route::get('/check-postcode', [PagesController::class, 'checkPostcode']);
        // Route::post('/update-cart', [PagesController::class, 'updateCart'])->name('update.cart');
        Route::post('/cart/store', [PagesController::class, 'storeCart'])->name('cart.store');
        Route::post('/update-quantity', [PagesController::class, 'updateQuantity'])->name('update.quantity');
        Route::get('/cart', [PagesController::class, 'showCartdata'])->name('show-Cart-data');
        Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::post('/update-weight', [CartController::class, 'updateWeight']);
        Route::post('/clear-cart', [PagesController::class, 'clearCart'])->name('clear.cart');
        Route::get('/total-cart-items', [PagesController::class, 'getCartItemNumbers'])->name('total-cart-items');
    
        Route::get('/all-featured-products', [PagesController::class, 'all_featured_products'])->name('Website.GeneralPages.featured_product_view');
        Route::get('/all-products', [PagesController::class, 'all_products'])->name('Website.GeneralPages.product_view');
        Route::get('/product/{permalink}', [PagesController::class, 'single_product'])->name('Website.GeneralPages.permalink');
    
        Route::get('/category/{category_sku}', [PagesController::class, 'category_product'])->name('Website.GeneralPages.Category');
        Route::get('/child-category/{childcategory_sku}', [PagesController::class, 'childcategory_product'])->name('Website.GeneralPages.Child_Category');
        Route::get('/grand-child-category/{grandchildcategory_sku}', [PagesController::class, 'grand_childcategory_product'])->name('Website.GeneralPages.Grand_Child_Category');
        Route::post('/user-update-password', [WebsiteAuthController::class, 'updatePassword'])->name('Website.UserPasswordUpdate');
        Route::post('/place-order', [\App\Http\Controllers\API\OrderController::class, 'store']);
        Route::get('/thankyou/{order}', [\App\Http\Controllers\API\OrderController::class, 'thankYouPage'])->name('thankyou');
    
        Route::get('/get-collection-products', [PagesController::class, 'getCollectionProducts'])->name('get.collection.products');
        Route::get('/collection-products', [PagesController::class, 'CollectionProducts'])->name('collection.products');
        Route::get('/category/{category_sku}', [PagesController::class, 'category_product'])->name('Website.GeneralPages.Category');
        Route::get('/collection-product/{permalink}', [PagesController::class, 'collection_product'])->name('Website.GeneralPages.collection_product');
    
        Route::get('/tool-brands/{brand}', [PagesController::class, 'brand_details'])->name('Website.GeneralPages.Brands_View');
        #-Conctact and Queries
        Route::get('/contact-us', [ContactUsController::class, 'index']);
        Route::post('/contact_us_data', [ContactUsController::class, 'store']);
        Route::post('/send-mail', [ContactUsController::class, 'sendmail']);
        Route::post('/store-customer', [CustomerqueryContorller::class, 'store'])->name('store.customer');
    
        Route::post('newsletterSubscription', [WebsiteUserController::class, 'subscribe_newsletter'])->name('Website.Subscribe-Newsletter');
        Route::get('/page/{page_permalink}', [PagesController::class, 'dynamic_page'])->name('Website.GeneralPages.dynamic_page');
    
        #Gift Cards
        Route::get('/gift-cards', [PagesController::class, 'gift_cards'])->name('Website.GeneralPages.gift-cards');
        Route::get('/gift-card-checkout/{id}', [PagesController::class, 'gift_card_checkout_page'])->name('Website.GeneralPages.gift-card-checkout');
        Route::get('/apply-coupon-code', [CartController::class, 'apply_coupon'])->name('Website.GeneralPages.apply_coupon');
        # Gift Card Checkout
        Route::post('/create-gift-card-order', [PayPalController::class, 'gift_card_order'])->name('Website.GiftCard.Order');
        Route::get('/paypal/gift-card-success', [PayPalController::class, 'handle_giftCard_payment_response'])->name('GiftCard.Payment.Success');
        Route::get('/paypal/gift-card-cancel', [PayPalController::class, 'gift_pay_cancel'])->name('GiftCard.Payment.Cancel');
        Route::post('/stripe/gift-card-payment', [StripeController::class, 'gift_card_payment'])->name('gift-card-payment');
        Route::get('/restore-grand-total', [CartController::class, 'restore_grand_total']);
    
        // Order History Butcher
    
        Route::get('/order-history', [PagesController::class, 'orderHistory'])->name('Website.GeneralPages.order-history');
        Route::get('/order-detail/{id}', [PagesController::class, 'orderDetail'])->name('Website.GeneralPages.order-detail');
        Route::get('/my-account', [PagesController::class, 'myAccount'])->name('Website.GeneralPages.my-account');
        Route::get('/update-address', [PagesController::class, 'updateAddress'])->name('Website.GeneralPages.update-address');
        Route::get('/add-new-address', [PagesController::class, 'addNewAddress'])->name('Website.GeneralPages.add-new-address');
        Route::post('/add-address', [PagesController::class, 'StoreAddress'])->name('Website.GeneralPages.store-address');
        Route::post('/delete-address', [PagesController::class, 'StoreAddress'])->name('Website.GeneralPages.delete-address');
        Route::get('/update-profile', [PagesController::class, 'edit_profile'])->name('Website.GeneralPages.update-profile');
        Route::post('/update-profile', [PagesController::class, 'updateProfile'])->name('profile.update');
    
    
        #Guest checkout
        # Order Routes starts
        Route::get('/check-out', [CartController::class, 'checkout_page'])->name('Website.CheckOut');
        Route::get('/change-shipping-address', [UserAddressController::class, 'change_shipping_address']);
        Route::post('/create-paypal-order', [PayPalController::class, 'create_order'])->name('Website.Paypal_Order');
        Route::get('/paypal/success', [PayPalController::class, 'handlePaymentResponse'])->name('paypal.success');
        Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
        Route::get('/thank-you', [PagesController::class, 'thankyouPage'])->name('Website.ThankYou_Page');
        Route::post('/stripe-payment', [StripeController::class, 'stripe_payment_method'])->name('stripe-payment');
        Route::post('/calculate-postage', [StripeController::class, 'calculatePostage']);
    
        Route::post('/initiate-payment', [AfterpayController::class, 'initiatePayment']);
        #-Crons
        Route::get('/top-selling-product', [DashboardController::class, 'updateTopSellingProducts']);
        Route::get('/least-selling-product', [DashboardController::class, 'updateLeastSellingProducts']);
        Route::get('/admin/dashboard/metrics', [DashboardController::class, 'getFilteredDashboardMetrics'])->name('admin.dashboard.metrics');
        Route::get('/update-membership-status', [MembershipPlanController::class, 'updateExpiryStatus']);
        Route::get('/shop', [App\Http\Controllers\Website\ProductController::class, 'shop'])->name('shop');
    
        Route::get('/track-my-order', [UserAddressController::class, 'guest_order_details'])->name('Website.guest_order_details');
        Route::post('/track-my-order', [UserAddressController::class, 'guest_order_details'])->name('Website.guest_order_details.post');
    
        Route::middleware(['end_user'])->group(function () {
            Route::get('/profile', [PagesController::class, 'profile'])->name('Website.GeneralPages.Profile');
            Route::post('/user-logout', [WebsiteAuthController::class, 'logout'])->name('Website.Logout');
            Route::post('/update-password', [WebsiteUserController::class, 'updatePassword'])->name('Website.LoggedUpdatePassword');
            Route::post('/update-profile-details', [WebsiteUserController::class, 'updatePersonalDetails'])->name('Website.ProfileImage');
    
            Route::get('/shipping-address/update/{id}', [UserAddressController::class, 'update'])->name('Website.GeneralPages.Update_Address');
            Route::post('/shipping-address', [UserAddressController::class, 'store'])->name('Website.Shipping_Address');
            Route::get('/destroy-address/{id}', [UserAddressController::class, 'delete_address']);
            Route::get('fetch-shipping-address/{id}', [CartController::class, 'fetch_shipping_address']);
    
            Route::get('/all-orders', [PagesController::class, 'profile'])->name('Website.GeneralPages.Orders');
            Route::get('/order-details/{id}', [UserAddressController::class, 'order_details'])->name('Website.order_details');
            Route::get('/return-order-details/{id}', [UserAddressController::class, 'return_order_details'])->name('Website.return_order_details');
            Route::get('/business-details', [PagesController::class, 'profile'])->name('Website.GeneralPages.Business-Details');
            Route::post('/update/business-details', [WebsiteUserController::class, 'update_business_details'])->name('Website.GeneralPages.Update-Business-Details');
            Route::get('/shipping-addresses', [PagesController::class, 'profile'])->name('Website.GeneralPages.Shipping_Address');
            Route::get('/return-orders', [PagesController::class, 'profile'])->name('Website.GeneralPages.Return_Orders');
    
            #afaq
            Route::get('/pos-orders', [PagesController::class, 'profile'])->name('Website.GeneralPages.pos-orders');
            Route::get('/user-order-details/{id}', [UserAddressController::class, 'user_order_details'])->name('Website.user_order_details');
            Route::get('/pos-order-detail/{id}', [UserAddressController::class, 'pos_order_detail'])->name('Website.pos_order_detail');
            Route::get('/invoicedetail{id}', [PagesController::class, 'generatePdf_pos'])->name('invoice.generatePdf_pos');
        });
    
    
        Route::get('/download-images', [ProductController::class, 'downloadImages']);

Route::fallback(function () {
    return view('Admin.Errors.404'); 
});