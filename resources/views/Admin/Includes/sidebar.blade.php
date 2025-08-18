@php
    $setting = \App\Models\GeneralSettings::all();
    use App\General\GeneralSettingsClass;

@endphp
<div class="app-menu navbar-menu position-fixed">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('Admin.Dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ url('/') . '/' . GeneralSettingsClass::getRequiredValue('_fav_icon') }}" alt=""
                    height="22">
            </span>
            <span class="logo-lg">
                <img class="w-100 h-100"
                    src="{{ url('/') . '/' . GeneralSettingsClass::getRequiredValue('_header_logo') }}" alt="">
            </span>
        </a>

        <a href="{{ route('Admin.Dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <!-- Use the same favicon for dark mode -->
                <img src="{{ url('/') . '/' . GeneralSettingsClass::getRequiredValue('_fav_icon') }}" alt=""
                    height="22">
            </span>
            <span class="logo-lg">
                <img class="w-100 h-100"
                    src="{{ url('/') . '/' . GeneralSettingsClass::getRequiredValue('_header_logo_dark') }}"
                    alt="">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar" data-simplebar class="h-100">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item @php if(request()->is('admin')){echo 'open active';} @endphp">
                    <a href="{{ url('/') }}/admin" class="nav-link menu-link"> <i class="fa fa-tachometer-alt"></i>
                        <span data-key="t-dashboard">Dashboard</span> </a>
                </li>

                {{-- ///// Brands ////  --}}
                @if (Session::has('permissions') &&
                        (in_array('Brand Create', Session::get('permissions')) ||
                            in_array('Brand List', Session::get('permissions')) ||
                            in_array('Brand Trash List', Session::get('permissions'))))
                    @php
                        $isBrandActive =
                            request()->is('admin/brands') ||
                            request()->is('admin/brands/create') ||
                            request()->is('admin/brands/list/trash');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarBrands" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isBrandActive ? 'true' : 'false' }}" aria-controls="sidebarBrands">
                            <i class="fa fa-store"></i>
                            <span data-key="t-products">Brands</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isBrandActive ? 'show' : '' }}" id="sidebarBrands"
                            data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                @if (in_array('Brand Create', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/brands/create') }}"
                                            class="nav-link {{ request()->is('admin/brands/create') ? 'active' : '' }}"
                                            data-key="t-list-view">
                                            Create Brand
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Brand List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/brands') }}"
                                            class="nav-link {{ request()->is('admin/brands') ? 'active' : '' }}"
                                            data-key="t-grid-view">
                                            Brands List
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Brand Trash List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/brands/list/trash') }}"
                                            class="nav-link {{ request()->is('admin/brands/list/trash') ? 'active' : '' }}"
                                            data-key="t-overview">
                                            Trash List
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>

                @endif
                        {{-- ///// Blogs ////  --}}
                       {{-- @if (Session::has('permissions') &&
                            (in_array('Blog Create', Session::get('permissions')) ||
                            in_array('Blog List', Session::get('permissions')) ||
                            in_array('Blog Trash List', Session::get('permissions')))) --}}
                            @php
                                $isBlogActive = 
                                    request()->is('admin/blogs') || 
                                    request()->is('admin/blogs/create') || 
                                    request()->is('admin/blogs/list/trash') || 
                                    request()->is('admin/blogs/*/edit') || 
                                    request()->is('admin/blogs/*');             
                            @endphp

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="#sidebarBlogs" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ $isBlogActive ? 'true' : 'false' }}" aria-controls="sidebarBlogs">
                                    <i class="fa-solid fa-blog"></i> {{-- Better blog icon --}}
                                    <span data-key="t-blogs">Blogs</span>
                                </a>

                                <div class="collapse menu-dropdown {{ $isBlogActive ? 'show' : '' }}" id="sidebarBlogs" data-bs-parent="#navbar-nav">
                                    <ul class="nav nav-sm flex-column">

                                        {{-- @if (in_array('Blog Create', Session::get('permissions'))) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('blogs.create') }}" 
                                                class="nav-link {{ request()->is('admin/blogs/create') ? 'active' : '' }}" 
                                                data-key="t-create-blog">
                                                 </i>Create Blog
                                                </a>
                                            </li>
                                        {{-- @endif --}}

                                        {{-- @if (in_array('Blog List', Session::get('permissions'))) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('blogs.index') }}" 
                                                class="nav-link {{ (request()->is('admin/blogs') && !request()->is('admin/blogs/create') && !request()->is('admin/blogs/list/trash')) || request()->is('admin/blogs/*/edit') || (request()->is('admin/blogs/*') && !request()->is('admin/blogs/create') && !request()->is('admin/blogs/list/trash')) ? 'active' : '' }}" 
                                                data-key="t-blog-list">
                                                    Blog List
                                                </a>
                                            </li>
                                        {{-- @endif --}}

                                        {{-- @if (in_array('Blog Trash List', Session::get('permissions'))) --}}
                                            <li class="nav-item">
                                                <a href="{{ route('Admin.Blog.Trash') }}" 
                                                class="nav-link {{ request()->is('admin/blogs/list/trash') ? 'active' : '' }}" 
                                                data-key="t-trash-list">
                                                    Trash List
                                                </a>
                                            </li>
                                        {{-- @endif --}}

                                    </ul>
                                </div>
                            </li>
                        {{-- @endif --}}

                <!-- Dynamic sidebar navigation with reorganized menu structure -->


                @php
                    // Define the conditions for menu items to be active
                    $isProductActive =
                        request()->is('admin/products/create') ||
                        request()->is('admin/products') ||
                        request()->is('admin/products/list/trash') ||
                        request()->is('admin/products/manage/inventory') ||
                        request()->is('admin/product-type');

                    $isCategoryActive =
                        request()->is('admin/category') ||
                        request()->is('admin/get_child_category') ||
                        request()->is('admin/get_grandchild_category');

                    $isShippingActive = request()->is('admin/area-groups');
                @endphp

                <!-- Product Management Section -->
                @if (Session::has('permissions') &&
                        (in_array('Product Type', Session::get('permissions')) ||
                            in_array('Create Product', Session::get('permissions')) ||
                            in_array('Product List', Session::get('permissions')) ||
                            in_array('Product Trash List', Session::get('permissions')) ||
                            in_array('Manage Inventory', Session::get('permissions'))))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isProductActive ? 'true' : 'false' }}" aria-controls="sidebarProducts">
                            <i class="fa fa-box"></i>
                            <span data-key="t-products">Products</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isProductActive ? 'show' : '' }}" id="sidebarProducts"
                            data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">
                                @if (in_array('Product Type', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/product-type') }}"
                                            class="nav-link {{ request()->is('admin/product-type') ? 'active' : '' }}"
                                            data-key="t-product-type">
                                            Product Type
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Create Product', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/products/create') }}"
                                            class="nav-link {{ request()->is('admin/products/create') ? 'active' : '' }}"
                                            data-key="t-create-product">
                                            Product Create
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Product List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/products') }}"
                                            class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}"
                                            data-key="t-product-list">
                                            Product List
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Product Trash List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/products/list/trash') }}"
                                            class="nav-link {{ request()->is('admin/products/list/trash') ? 'active' : '' }}"
                                            data-key="t-trash-list">
                                            Trash List
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Manage Inventory', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/products/manage/inventory') }}"
                                            class="nav-link {{ request()->is('admin/products/manage/inventory') ? 'active' : '' }}"
                                            data-key="t-manage-inventory">
                                            Manage Inventory
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- Category Management Section -->
                @if (Session::has('permissions') && in_array('Product Category', Session::get('permissions')))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarCategories" data-bs-toggle="collapse"
                            role="button" aria-expanded="{{ $isCategoryActive ? 'true' : 'false' }}"
                            aria-controls="sidebarCategories">
                            <i class="fa fa-tags"></i>
                            <span data-key="t-categories">Categories</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isCategoryActive ? 'show' : '' }}"
                            id="sidebarCategories" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('/admin/category') }}"
                                        class="nav-link {{ request()->is('admin/category') ? 'active' : '' }}"
                                        data-key="t-main-category">
                                        Main Category
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/admin/get_child_category') }}"
                                        class="nav-link {{ request()->is('admin/get_child_category') ? 'active' : '' }}"
                                        data-key="t-child-category">
                                        Child Category
                                    </a>
                                </li>
                                    <!-- <li class="nav-item">
                                        <a href="{{ url('/admin/get_grandchild_category') }}"
                                            class="nav-link {{ request()->is('admin/get_grandchild_category') ? 'active' : '' }}"
                                            data-key="t-grandchild-category">
                                            Grand Child Category
                                        </a>
                                    </li> -->
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- Shipping Section -->
                @if (Session::has('permissions') && in_array('Area Group', Session::get('permissions')))
                    <li class="nav-item">
                        <a href="{{ url('/admin/area-groups') }}"
                            class="nav-link menu-link {{ $isShippingActive ? 'active' : '' }}">
                            <i class="fa fa-truck"></i>
                            <span data-key="t-shipping">Shipping Area Group</span>
                        </a>
                    </li>
                @endif




                @if (Session::has('permissions') &&
                        (in_array('Create a Discount', Session::get('permissions')) ||
                            in_array('Discount List', Session::get('permissions')) ||
                            in_array('Discount Trash List', Session::get('permissions'))))

                    @php
                        $isDiscountActive =
                            request()->is('admin/discounts') ||
                            request()->is('admin/discounts/create') ||
                            request()->is('admin/discounts/list/trash');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isDiscountActive ? '' : 'collapsed' }}"
                            href="#sidebarDiscounts" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isDiscountActive ? 'true' : 'false' }}"
                            aria-controls="sidebarDiscounts">
                            <i class="fa-solid fa-sack-dollar"></i>
                            <span data-key="t-discounts">Discounts</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isDiscountActive ? 'show' : '' }}"
                            id="sidebarDiscounts" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                @if (in_array('Create a Discount', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/discounts/create') }}"
                                            class="nav-link {{ request()->is('admin/discounts/create') ? 'active' : '' }}"
                                            data-key="t-create-discount">
                                            Create  Discount
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Discount List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/discounts') }}"
                                            class="nav-link {{ request()->is('admin/discounts') ? 'active' : '' }}"
                                            data-key="t-discount-list">
                                            Discount List
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Discount Trash List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/discounts/list/trash') }}"
                                            class="nav-link {{ request()->is('admin/discounts/list/trash') ? 'active' : '' }}"
                                            data-key="t-discount-trash">
                                            Trash List
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif

                @if (Session::has('permissions') &&
                        (in_array('Create a Collaction', Session::get('permissions')) ||
                            in_array('Collection List', Session::get('permissions')) ||
                            in_array('Collection Trash List', Session::get('permissions'))))

                    @php
                        $isCollectionActive =
                            request()->is('admin/collections') ||
                            request()->is('admin/collections/create') ||
                            request()->is('admin/collections/list/trash');
                    @endphp

                    <!-- Section Title -->
                    {{-- <li class="nav-item text-uppercase fw-bold small text-muted px-3 mt-3">
                    Collection and Discounts
                </li> --}}

                    <!-- Menu Item -->
                    <!-- <li class="nav-item">
                        <a class="nav-link menu-link {{ $isCollectionActive ? '' : 'collapsed' }}"
                            href="#sidebarCollections" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isCollectionActive ? 'true' : 'false' }}"
                            aria-controls="sidebarCollections">
                            <i class="fa fa-tags"></i>
                            <span data-key="t-collections">Collections</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isCollectionActive ? 'show' : '' }}"
                            id="sidebarCollections" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                {{-- @if (in_array('Create a Collaction', Session::get('permissions'))) --}}
                                <li class="nav-item">
                                    <a href="{{ url('/admin/collections/create') }}"
                                        class="nav-link {{ request()->is('admin/collections/create') ? 'active' : '' }}"
                                        data-key="t-create-collection">
                                        Create a Collection
                                    </a>
                                </li>
                                {{-- @endif --}}

                                @if (in_array('Collection List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/collections') }}"
                                            class="nav-link {{ request()->is('admin/collections') ? 'active' : '' }}"
                                            data-key="t-collection-list">
                                            Collection List
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Collection Trash List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/collections/list/trash') }}"
                                            class="nav-link {{ request()->is('admin/collections/list/trash') ? 'active' : '' }}"
                                            data-key="t-collection-trash">
                                            Trash List
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif

                @if (Session::has('permissions') &&
                        (in_array('Create a Plan', Session::get('permissions')) || in_array('Plans List', Session::get('permissions'))))

                    @php
                        $isMembershipActive =
                            request()->is('admin/memberships') || request()->is('admin/memberships/create');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isMembershipActive ? '' : 'collapsed' }}"
                            href="#sidebarMemberships" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isMembershipActive ? 'true' : 'false' }}"
                            aria-controls="sidebarMemberships">
                            <i class="fa fa-user-check"></i>
                            <span data-key="t-memberships">Memberships</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isMembershipActive ? 'show' : '' }}"
                            id="sidebarMemberships" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                @if (in_array('Create a Plan', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/memberships/create') }}"
                                            class="nav-link {{ request()->is('admin/memberships/create') ? 'active' : '' }}"
                                            data-key="t-create-plan">
                                            Create a Plan
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Plans List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/memberships') }}"
                                            class="nav-link {{ request()->is('admin/memberships') ? 'active' : '' }}"
                                            data-key="t-plans-list">
                                            Plans List
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif-->

               

                @if (Session::has('permissions') &&
                        (in_array('Create Invoice', Session::get('permissions')) ||
                            in_array('Invoice List', Session::get('permissions')) ||
                            in_array('Create Return', Session::get('permissions')) ||
                            in_array('Returns List', Session::get('permissions')) ||
                            in_array('Orders List', Session::get('permissions'))))

                    <!-- Section Header -->


                    {{-- Point of Sale --}}
                    @php
                        $isPOSActive =
                            request()->is('admin/invoices') ||
                            request()->is('admin/invoices/create') ||
                            request()->is('admin/invoices_return') ||
                            request()->is('admin/invoices_return/create') ||
                            request()->is('admin/invoices/returns') ||
                            request()->is('admin/invoices/returns/create');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isPOSActive ? '' : 'collapsed' }}" href="#sidebarPOS"
                            data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isPOSActive ? 'true' : 'false' }}" aria-controls="sidebarPOS">
                            <i class="fa fa-print"></i>
                            <span data-key="t-pos">Point of Sale</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isPOSActive ? 'show' : '' }}" id="sidebarPOS"
                            data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">
                                @if (in_array('Create Invoice', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/invoices/create') }}"
                                            class="nav-link {{ request()->is('admin/invoices/create') ? 'active' : '' }}"
                                            data-key="t-create-invoice">
                                            Create Invoice
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Invoice List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/invoices') }}"
                                            class="nav-link {{ request()->is('admin/invoices') ? 'active' : '' }}"
                                            data-key="t-invoice-list">
                                            Invoices List
                                        </a>
                                    </li>
                                @endif

                                <!-- @if (in_array('Create Return', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/invoices_return/create') }}"
                                            class="nav-link {{ request()->is('admin/invoices_return/create') ? 'active' : '' }}"
                                            data-key="t-create-return">
                                            Create Return
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Returns List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/invoices_return') }}"
                                            class="nav-link {{ request()->is('admin/invoices_return') ? 'active' : '' }}"
                                            data-key="t-return-list">
                                            Returns List
                                        </a>
                                    </li>
                                @endif -->
                            </ul>
                        </div>
                    </li>

                    {{-- Orders --}}
                    @if (in_array('Orders List', Session::get('permissions')))
                        @php
                            $isOrdersActive =
                                request()->is('admin/orders') ||
                                request()->is('admin/orders/cancelled') ||
                                request()->is('admin/orders/return') ||
                                request()->is('admin/orders/return/create');
                        @endphp

                        <li class="nav-item">
                            <a class="nav-link menu-link {{ $isOrdersActive ? '' : 'collapsed' }}"
                                href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ $isOrdersActive ? 'true' : 'false' }}"
                                aria-controls="sidebarOrders">
                                <i class="fa fa-shopping-cart"></i>
                                <span data-key="t-orders">Orders</span>
                            </a>

                            <div class="collapse menu-dropdown {{ $isOrdersActive ? 'show' : '' }}"
                                id="sidebarOrders" data-bs-parent="#navbar-nav">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item">
                                        <a href="{{ url('/admin/orders') }}"
                                            class="nav-link {{ request()->is('admin/orders') ? 'active' : '' }}"
                                            data-key="t-orders-list">
                                            Orders List
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ url('/admin/orders/cancelled') }}"
                                            class="nav-link {{ request()->is('admin/orders/cancelled') ? 'active' : '' }}"
                                            data-key="t-cancelled-orders">
                                            Cancel Orders List
                                        </a>
                                    </li>

                                    <!-- <li class="nav-item">
                                        <a href="{{ url('/admin/orders/return') }}"
                                            class="nav-link {{ request()->is('admin/orders/return') ? 'active' : '' }}"
                                            data-key="t-return-orders">
                                            Return Orders List
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ url('/admin/orders/return/create') }}"
                                            class="nav-link {{ request()->is('admin/orders/return/create') ? 'active' : '' }}"
                                            data-key="t-create-return-order">
                                            Create Return Orders
                                        </a>
                                    </li> -->

                                </ul>
                            </div>
                        </li>
                    @endif
                @endif

                @if (Session::has('permissions') &&
                        (in_array('Create User', Session::get('permissions')) ||
                            in_array('User List', Session::get('permissions')) ||
                            in_array('Customer List', Session::get('permissions'))))

                    @php
                        $isUserMenuActive =
                            request()->is('admin/users') ||
                            request()->is('admin/users/create') ||
                            request()->is('admin/users/list/customer');
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isUserMenuActive ? '' : 'collapsed' }}" href="#sidebarUsers"
                            data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isUserMenuActive ? 'true' : 'false' }}" aria-controls="sidebarUsers">
                            <i class="fa fa-user-shield"></i>
                            <span data-key="t-users">Users</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isUserMenuActive ? 'show' : '' }}" id="sidebarUsers"
                            data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                @if (in_array('Create User', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/users/create') }}"
                                            class="nav-link {{ request()->is('admin/users/create') ? 'active' : '' }}"
                                            data-key="t-create-user">
                                            Create User
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('User List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/users') }}"
                                            class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}"
                                            data-key="t-users-list">
                                            User List
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Customer List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/users/list/customer') }}"
                                            class="nav-link {{ request()->is('admin/users/list/customer') ? 'active' : '' }}"
                                            data-key="t-customer-list">
                                            Customer List
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif

                @if (Session::has('permissions') &&
                        (in_array('Contact List', Session::get('permissions')) ||
                            in_array('Expert Query', Session::get('permissions'))))

                    @php
                        $isContactsActive = request()->is('admin/contacts') || request()->is('admin/quries');
                    @endphp

                    <li class="nav-item">
                        <!-- CHANGE: Removed the incorrect collapsed class logic -->
                        <!-- The Bootstrap collapse functionality handles this automatically -->
                        <a class="nav-link menu-link" href="#sidebarContacts" data-bs-toggle="collapse"
                            role="button" aria-expanded="{{ $isContactsActive ? 'true' : 'false' }}"
                            aria-controls="sidebarContacts">
                            <i class="fa fa-paper-plane"></i>
                            <span data-key="t-contacts">Contacts & Query</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isContactsActive ? 'show' : '' }}"
                            id="sidebarContacts" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                @if (in_array('Contact List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/contacts') }}"
                                            class="nav-link {{ request()->is('admin/contacts') ? 'active' : '' }}"
                                            data-key="t-contact-list">
                                            Contact List
                                        </a>
                                    </li>
                                @endif

                                <!-- @if (in_array('Expert Query', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/quries') }}"
                                            class="nav-link {{ request()->is('admin/quries') ? 'active' : '' }}"
                                            data-key="t-expert-query">
                                            Expert Query
                                        </a>
                                    </li>
                                @endif -->

                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Newsletter Subscriptions --}}
                @if (Session::has('permissions') && in_array('Newsletter Subscriptions', Session::get('permissions')))
                    <li class="nav-item">
                        <a href="{{ route('Admin.Subscription.index') }}"
                            class="nav-link menu-link {{ request()->is('admin/newsletter/subcriptions*') ? 'active' : '' }}">
                            <i class="fa fa-envelope-open-text"></i>
                            <span data-key="t-newsletter">Newsletter Subscriptions</span>
                        </a>
                    </li>
                @endif

                {{-- Settings --}}
                    @php
                    $isSettingsActive = request()->is('admin/header') || request()->is('admin/general-settings');
                @endphp

                <!-- Replace the separate Settings and Web Header menu items with a dropdown -->
                @if (Session::has('permissions') &&
                        (in_array('Website Header', Session::get('permissions')) ||
                            in_array('General Settings', Session::get('permissions'))))
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}" aria-controls="sidebarSettings">
                            <i class="fa fa-gear"></i>
                            <span data-key="t-settings">Settings</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isSettingsActive ? 'show' : '' }}" id="sidebarSettings"
                            data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">
                                @if (in_array('General Settings', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/general-settings') }}"
                                            class="nav-link {{ request()->is('admin/general-settings*') ? 'active' : '' }}"
                                            data-key="t-general-settings">
                                            General Settings
                                        </a>
                                    </li>
                                @endif
                                
                                @if (in_array('Website Header', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/header') }}"
                                            class="nav-link {{ request()->is('admin/header*') ? 'active' : '' }}"
                                            data-key="t-website-header">
                                            Website Header
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif
                 @if (Session::has('permissions') && (in_array('Creae a Page', Session::get('permissions')) || in_array('Page List', Session::get('permissions')) || in_array('Page Trash List', Session::get('permissions'))))
                    @php
                        $isWebPagesActive = request()->is('admin/pages') || request()->is('admin/pages*');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarWebPages" data-bs-toggle="collapse"
                            role="button" aria-expanded="{{ $isWebPagesActive ? 'true' : 'false' }}"
                            aria-controls="sidebarWebPages">
                            <i class="fa fa-file"></i>
                            <span data-key="t-pages">Website Pages</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isWebPagesActive ? 'show' : '' }}"
                            id="sidebarWebPages" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                @if (in_array('Creae a Page', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/pages/create') }}"
                                            class="nav-link {{ request()->is('admin/pages/create') ? 'active' : '' }}"
                                            data-key="t-create-page">
                                            Page Create
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('Page List', Session::get('permissions')))
                                    <li class="nav-item">
                                        <a href="{{ url('/admin/pages') }}"
                                            class="nav-link {{ request()->is('admin/pages') ? 'active' : '' }}"
                                            data-key="t-page-list">
                                            Page List
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif
                @if (Session::has('permissions') && in_array('Role', Session::get('permissions')))
                    @php
                        $isRoleActive = request()->is('admin/role/create');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isRoleActive ? '' : 'collapsed' }}"
                            href="#sidebarPermissions" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $isRoleActive ? 'true' : 'false' }}"
                            aria-controls="sidebarPermissions">
                            <i class="fa fa-lock"></i>
                            <span data-key="t-permissions">Permission</span>
                        </a>

                        <div class="collapse menu-dropdown {{ $isRoleActive ? 'show' : '' }}"
                            id="sidebarPermissions" data-bs-parent="#navbar-nav">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{ url('/admin/role/create') }}"
                                        class="nav-link {{ $isRoleActive ? 'active' : '' }}" data-key="t-role">
                                        Role
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
