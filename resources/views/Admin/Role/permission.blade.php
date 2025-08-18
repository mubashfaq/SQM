@include('Admin.Includes.header')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb Start -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="pageheader pd-t-25 pd-b-35">
                    <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div class="clearfix">
                            <div class="pd-t-5 pd-b-5">
                                <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Permission') }}</h1>
                            </div>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('Admin.Dashboard') }}"
                                            class="link-primary text-decoration-none">
                                            <i class="fa-solid fa-house me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Permission
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Permission Settings</h6>
            </div>
            <div class="card-body">
                <form id="form_role" action="/store-permission" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ request()->route('id') }}">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="all checked"
                                    name="permission[]" id="all_checked" <?php if (in_array('all checked', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="all_checked">All Checked</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="font-weight-bold mb-3">Dashboard</h5>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="View Dashboard" id="View Dashboard" <?php if (in_array('View Dashboard', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="View Dashboard">Dashboard</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input brand_checklist" id="brand_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="brand_checklist">Brand</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="permission-group">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Create" id="Brand Create" <?php if (in_array('Brand Create', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Create">Create Brand</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand List" id="Brand List" <?php if (in_array('Brand List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand List">Brand List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Trash List" id="Brand Trash List" <?php if (in_array('Brand Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Trash List">Brand Trash List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Edit" id="Brand Edit" <?php if (in_array('Brand Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Edit">Edit Brand</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Delete" id="Brand Delete" <?php if (in_array('Brand Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Delete">Delete Brand</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Change Status" id="Change Status" <?php if (in_array('Change Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Change Status">Change Status</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input product_checklist"
                                id="product_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="product_checklist">Product
                                Type</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="product-type-list">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Type" id="Product Type" <?php if (in_array('Product Type', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Type">Product Type</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Type Add" id="Product Type Add" <?php if (in_array('Product Type Add', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Type Add">Add Product Type</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Type Edit" id="Product Type Edit" <?php if (in_array('Product Type Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Type Edit">Edit Product Type</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Type Delete" id="Product Type Delete" <?php if (in_array('Product Type Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Type Delete">Delete Product
                                    Type</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input category_checklist"
                                id="category_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="category_checklist">Product
                                Category</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="category_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Category" id="Product Category" <?php if (in_array('Product Category', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Category">Product Category</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Category Add" id="Product Category Add" <?php if (in_array('Product Category Add', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Category Add">Add Product
                                    Category</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Category Edit" id="Product Category Edit" <?php if (in_array('Product Category Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Category Edit">Edit Product
                                    Category</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Category Delete" id="Product Category Delete" <?php if (in_array('Product Category Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Category Delete">Delete Product
                                    Category</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input groups_checklist"
                                id="groups_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="groups_checklist">Area
                                Groups</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="groups_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Area Group" id="Area Group" <?php if (in_array('Area Group', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Area Group">Area Group</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Area Group Add" id="Area Group Add" <?php if (in_array('Area Group Add', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Area Group Add">Add Area Group</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Area Group Edit" id="Area Group Edit" <?php if (in_array('Area Group Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Area Group Edit">Edit Area Group</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Area Group Delete" id="Area Group Delete" <?php if (in_array('Area Group Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Area Group Delete">Delete Area Group</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Add More" id="Add More" <?php if (in_array('Add More', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Add More">Add More</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Post Code Edit" id="Post Code Edit" <?php if (in_array('Post Code Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Post Code Edit">Edit Post Code</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Post Code Delete" id="Post Code Delete" <?php if (in_array('Post Code Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Post Code Delete">Delete Post Code</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input Productchecklist"
                                id="Productchecklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="Productchecklist">Product</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="Productchecklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create Product" id="Create Product" <?php if (in_array('Create Product', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create Product">Create Product</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product List" id="Product List" <?php if (in_array('Product List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product List">Product List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Trash List" id="Product Trash List" <?php if (in_array('Product Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Trash List">Product Trash
                                    List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Edit" id="Product Edit" <?php if (in_array('Product Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Edit">Edit Product</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Delete" id="Product Delete" <?php if (in_array('Product Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Delete">Delete Product</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Product Change Status" id="Product Change Status" <?php if (in_array('Product Change Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Product Change Status">Product Change
                                    Status</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Import Products" id="Import Products" <?php if (in_array('Import Products', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Import Products">Import Products</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input inventory_checklist"
                                id="inventory_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="inventory_checklist">Manage
                                Inventory</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="inventory_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Manage Inventory" id="Manage Inventory" <?php if (in_array('Manage Inventory', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Manage Inventory">Manage Inventory</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Add Inventory" id="Add Inventory" <?php if (in_array('Add Inventory', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Add Inventory">Add Inventory</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="CSV Export" id="CSV Export" <?php if (in_array('CSV Export', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="CSV Export">CSV Export</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="CSV Import" id="CSV Import" <?php if (in_array('CSV Import', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="CSV Import">CSV Import</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Inventory Status" id="Inventory Status" <?php if (in_array('Inventory Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Inventory Status">Inventory Status</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input collections_checklist"
                                id="collections_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="collections_checklist">Collections</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="collections_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create a Collection" id="Create a Collection"
                                    @if (in_array('Create a Collection', $permissions)) checked @endif>
                                <label class="custom-control-label" for="Create a Collection">Create a
                                    Collection</label>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Collection List" id="Collection List" <?php if (in_array('Collection List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Collection List">Collection List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Collection Trash List" id="Collection Trash List" <?php if (in_array('Collection Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Collection Trash List">Trash List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Collection Edit" id="Collection Edit" <?php if (in_array('Collection Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Collection Edit">Edit Collection</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Add Product In Collection" id="Add Product In Collection"
                                    <?php if (in_array('Add Product In Collection', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Add Product In Collection">Add
                                    Product</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Collection Product" id="Delete Collection Product"
                                    <?php if (in_array('Delete Collection Product', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Collection Product">Delete Collection
                                    Product</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Collection Delete" id="Collection Delete" <?php if (in_array('Collection Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Collection Delete">Delete Collection</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Collection Change Status" id="Collection Change Status"
                                    <?php if (in_array('Collection Change Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Collection Change Status">Collection Change
                                    Status</label>
                            </div>
                        </div>
                    </div>

                    <!-- Continue with all remaining sections following the same pattern -->
                    <!-- Discounts Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input discounts_checklist"
                                id="discounts_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="discounts_checklist">Discounts</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="discounts_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create a Discount" id="Create a Discount" <?php if (in_array('Create a Discount', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create a Discount">Create a Discount</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Discount List" id="Discount List" <?php if (in_array('Discount List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Discount List">Discount List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Discount Trash List" id="Discount Trash List" <?php if (in_array('Discount Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Discount Trash List">Trash List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Discount Edit" id="Discount Edit" <?php if (in_array('Discount Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Discount Edit">Edit Discount</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Discount Delete" id="Discount Delete" <?php if (in_array('Discount Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Discount Delete">Delete Discount</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Discount Change Status" id="Discount Change Status" <?php if (in_array('Discount Change Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Discount Change Status">Discount Change
                                    Status</label>
                            </div>
                        </div>
                    </div>

                    <!-- Memberships Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input memberships_checklist"
                                id="memberships_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="memberships_checklist">Memberships</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="memberships_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create a Plan" id="Create a Plan" <?php if (in_array('Create a Plan', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create a Plan">Create a Plan</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Plans List" id="Plans List" <?php if (in_array('Plans List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Plans List">Plans List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Plan Edit" id="Plan Edit" <?php if (in_array('Plan Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Plan Edit">Edit Plan</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Plan Delete" id="Plan Delete" <?php if (in_array('Plan Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Plan Delete">Delete Plan</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Subscribers" id="Subscribers" <?php if (in_array('Subscribers', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Subscribers">Subscribers</label>
                            </div>
                        </div>
                    </div>

                    <!-- Point of Sale Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input pos_checklist" id="pos_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="pos_checklist">Point of
                                Sale</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="pos_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create Invoice" id="Create Invoice" <?php if (in_array('Create Invoice', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create Invoice">Create Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Save Invoice" id="Save Invoice" <?php if (in_array('Save Invoice', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Save Invoice">Save Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Cancel Invoice" id="Cancel Invoice" <?php if (in_array('Cancel Invoice', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Cancel Invoice">Cancel Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Add New Customer" id="Add New Customer" <?php if (in_array('Add New Customer', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Add New Customer">Add New Customer</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Print Invoice" id="Print Invoice" <?php if (in_array('Print Invoice', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Print Invoice">Print Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Invoice List" id="Invoice List" <?php if (in_array('Invoice List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Invoice List">Invoice List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Invoice Edit" id="Invoice Edit" <?php if (in_array('Invoice Edit', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Invoice Edit">Edit Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Invoice Delete" id="Invoice Delete" <?php if (in_array('Invoice Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Invoice Delete">Delete Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create Return" id="Create Return" <?php if (in_array('Create Return', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create Return">Create Return</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Save Return" id="Save Return" <?php if (in_array('Save Return', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Save Return">Save Return</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Print Return" id="Print Return" <?php if (in_array('Print Return', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Print Return">Print Return</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Returns List" id="Returns List" <?php if (in_array('Returns List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Returns List">Returns List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Edit Return" id="Edit Return" <?php if (in_array('Edit Return', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Edit Return">Edit Return</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Return" id="Delete Return" <?php if (in_array('Delete Return', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Return">Delete Return</label>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input orders_checklist"
                                id="orders_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="orders_checklist">Orders</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="orders_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Orders List" id="Orders List" <?php if (in_array('Orders List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Orders List">Orders List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Print Order Invoice" id="Print Order Invoice" <?php if (in_array('Print Order Invoice', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Print Order Invoice">Print Order
                                    Invoice</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Fulfillment Status" id="Fulfillment Status" <?php if (in_array('Fulfillment Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Fulfillment Status">Fulfillment
                                    Status</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Orders Detail" id="Orders Detail" <?php if (in_array('Orders Detail', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Orders Detail">Orders Detail</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Orders Delete" id="Orders Delete" <?php if (in_array('Orders Delete', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Orders Delete">Delete Orders</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete All" id="Delete All" <?php if (in_array('Delete All', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete All">Delete All</label>
                            </div>
                        </div>
                    </div>

                    <!-- Users Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input users_checklist" id="users_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="users_checklist">Users</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="users_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create User" id="Create User" <?php if (in_array('Create User', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create User">Create User</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="User List" id="User List" <?php if (in_array('User List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="User List">User List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Edit User" id="Edit User" <?php if (in_array('Edit User', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Edit User">Edit User</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete User" id="Delete User" <?php if (in_array('Delete User', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete User">Delete User</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="User Change Status" id="User Change Status" <?php if (in_array('User Change Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="User Change Status">Change Status</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Customer List" id="Customer List" <?php if (in_array('Customer List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Customer List">Customer List</label>
                            </div>
                        </div>
                    </div>

                    <!-- Category Slider Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input cat_checklist" id="cat_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="cat_checklist">Category
                                Slider</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="cat_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create a Slider" id="Create a Slider" <?php if (in_array('Create a Slider', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create a Slider">Create a Slider</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Slider List" id="Slider List" <?php if (in_array('Slider List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Slider List">Slider List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Slider Trash List" id="Slider Trash List" <?php if (in_array('Slider Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Slider Trash List">Trash List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Edit Slider" id="Edit Slider" <?php if (in_array('Edit Slider', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Edit Slider">Edit Slider</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Slider" id="Delete Slider" <?php if (in_array('Delete Slider', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Slider">Delete Slider</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Category Slider Status" id="Category Slider Status" <?php if (in_array('Category Slider Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Category Slider Status">Change Status</label>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Slider Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input brandslider_checklist"
                                id="brandslider_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="brandslider_checklist">Brand
                                Slider</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="brandslider_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Create Brand Slider" id="Create Brand Slider" <?php if (in_array('Create Brand Slider', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Create Brand Slider">Create a Slider</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Slider List" id="Brand Slider List" <?php if (in_array('Brand Slider List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Slider List">Slider List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Slider Trash List" id="Brand Slider Trash List" <?php if (in_array('Brand Slider Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Slider Trash List">Trash List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Edit Slider" id="Brand Edit Slider" <?php if (in_array('Brand Edit Slider', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Edit Slider">Edit Slider</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Delete Slider" id="Brand Delete Slider" <?php if (in_array('Brand Delete Slider', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Delete Slider">Delete Slider</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Brand Slider Status" id="Brand Slider Status" <?php if (in_array('Brand Slider Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Brand Slider Status">Change Status</label>
                            </div>
                        </div>
                    </div>

                    <!-- Contacts List Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input contact_checklist"
                                id="contact_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="contact_checklist">Contacts
                                List</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="contact_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Contact List" id="Contact List" <?php if (in_array('Contact List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Contact List">Contact List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Contact Detail" id="Contact Detail" <?php if (in_array('Contact Detail', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Contact Detail">View Detail</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Contact Delete All" id="Contact Delete All" <?php if (in_array('Contact Delete All', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Contact Delete All">Delete All
                                    Contact</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Contact" id="Delete Contact" <?php if (in_array('Delete Contact', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Contact">Delete Contact</label>
                            </div>
                        </div>
                    </div>

                    <!-- Expert Query Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input query_checklist"
                                id="query_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="query_checklist">Expert
                                Query</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="query_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Expert Query" id="Expert Query" <?php if (in_array('Expert Query', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Expert Query">Expert Query</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Query Detail" id="Query Detail" <?php if (in_array('Query Detail', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Query Detail">View Detail</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Query Delete All" id="Query Delete All" <?php if (in_array('Query Delete All', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Query Delete All">Delete All Query</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Query" id="Delete Query" <?php if (in_array('Delete Query', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Query">Delete Query</label>
                            </div>
                        </div>
                    </div>

                    <!-- Newsletter Subscriptions Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input news_checklist" id="news_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="news_checklist">Newsletter
                                Subscriptions</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="news_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Newsletter Subscriptions" id="Newsletter Subscriptions"
                                    <?php if (in_array('Newsletter Subscriptions', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Newsletter Subscriptions">Newsletter
                                    Subscriptions</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete All Newsletter" id="Delete All Newsletter" <?php if (in_array('Delete All Newsletter', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete All Newsletter">Delete All
                                    Newsletter</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Newsletter" id="Delete Newsletter" <?php if (in_array('Delete Newsletter', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Newsletter">Delete
                                    Newsletter</label>
                            </div>
                        </div>
                    </div>

                    <!-- Website Header Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input web_checklist" id="web_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="web_checklist">Website
                                Header</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="web_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Website Header" id="Website Header" <?php if (in_array('Website Header', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Website Header">Website Header</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Add New Menu" id="Add New Menu" <?php if (in_array('Add New Menu', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Add New Menu">Add New Menu</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Add Item" id="Add Item" <?php if (in_array('Add Item', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Add Item">Add Item</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Item" id="Delete Item" <?php if (in_array('Delete Item', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Item">Delete Item</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Menu" id="Delete Menu" <?php if (in_array('Delete Menu', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Menu">Delete Menu</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Save Header" id="Save Header" <?php if (in_array('Save Header', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Save Header">Save Change</label>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input set_checklist" id="set_checklist"
                                name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="set_checklist">Settings</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="set_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="General Settings" id="General Settings" <?php if (in_array('General Settings', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="General Settings">General Settings</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Save General Settings" id="Save General Settings" <?php if (in_array('Save General Settings', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Save General Settings">Save Change</label>
                            </div>
                        </div>
                    </div>

                    <!-- Website Page Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input webpage_checklist"
                                id="webpage_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5" for="webpage_checklist">Website
                                Page</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="webpage_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Creae a Page" id="Creae a Page" <?php if (in_array('Creae a Page', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Creae a Page">Creae a Page</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Page List" id="Page List" <?php if (in_array('Page List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Page List">Page List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Page Trash List" id="Page Trash List" <?php if (in_array('Page Trash List', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Page Trash List">Trash List</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Edit Page" id="Edit Page" <?php if (in_array('Edit Page', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Edit Page">Edit Page</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Page" id="Delete Page" <?php if (in_array('Delete Page', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Page">Delete Page</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Page Status" id="Page Status" <?php if (in_array('Page Status', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Page Status">Change Status</label>
                            </div>
                        </div>
                    </div>

                    <!-- Permission Section -->
                    <hr>
                    <div class="mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input Permission_checklist"
                                id="Permission_checklist" name="permission[]">
                            <label class="custom-control-label font-weight-bold h5"
                                for="Permission_checklist">Permission</label>
                        </div>
                    </div>
                    <div class="row mb-4" id="Permission_checklistbox">
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Role" id="Role" <?php if (in_array('Role', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Role">Role</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Edit Role" id="Edit Role" <?php if (in_array('Edit Role', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Edit Role">Edit Role</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Delete Role" id="Delete Role" <?php if (in_array('Delete Role', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Delete Role">Delete Role</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="permission[]"
                                    value="Role Permissions" id="Role Permissions" <?php if (in_array('Role Permissions', $permissions)) {
                                        echo 'checked';
                                    } ?>>
                                <label class="custom-control-label" for="Role Permissions">Permissions</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ url('/') }}/admin/role/create"
                                class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Use Bootstrap's fade out
            alert.classList.remove('show');
            alert.classList.add('fade');
            // Completely remove from DOM after transition
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000)
</script>

<script>
    // document.getElementById('all_checked').addEventListener('change', function() {
    //     var permissionCheckboxes = document.querySelectorAll('input[name="permission[]"]');
    //     permissionCheckboxes.forEach(function(checkbox) {
    //         checkbox.checked = this.checked;
    //     }, this);
    // });
    var allCheckedCheckbox = document.getElementById('all_checked');
    var permissionCheckboxes = document.querySelectorAll('input[name="permission[]"]');
    allCheckedCheckbox.addEventListener('change', function() {
        permissionCheckboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });
    permissionCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var allChecked = true;
            permissionCheckboxes.forEach(function(checkbox) {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            allCheckedCheckbox.checked = allChecked;
        });
    });
</script>
<script>
    $(document).ready(function() {
        function updateMasterCheckbox() {
            const anyUnchecked = $('#permission-group input[type="checkbox"]:not(:checked)').length > 0;
            $('#brand_checklist').prop('checked', !anyUnchecked);
        }
        $('#brand_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#permission-group input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#permission-group input[type="checkbox"]').change(function() {
            updateMasterCheckbox();
        });
        updateMasterCheckbox();
    });
    $(document).ready(function() {
        function updateMasterproCheckbox() {
            const anyUnchecked = $('#product-type-list input[type="checkbox"]:not(:checked)').length > 0;
            $('#product_checklist').prop('checked', !anyUnchecked);
        }
        $('#product_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#product-type-list input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#product-type-list input[type="checkbox"]').change(function() {
            updateMasterproCheckbox();
        });
        updateMasterproCheckbox();
    });
    $(document).ready(function() {
        function updateMastercatCheckbox() {
            const anyUnchecked = $('#category_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#category_checklist').prop('checked', !anyUnchecked);
        }
        $('#category_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#category_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#category_checklistbox input[type="checkbox"]').change(function() {
            updateMastercatCheckbox();
        });
        updateMastercatCheckbox();
    });
    $(document).ready(function() {
        function updateMasterareaCheckbox() {
            const anyUnchecked = $('#groups_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#groups_checklist').prop('checked', !anyUnchecked);
        }
        $('#groups_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#groups_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#groups_checklistbox input[type="checkbox"]').change(function() {
            updateMasterareaCheckbox();
        });
        updateMasterareaCheckbox();
    });
    $(document).ready(function() {
        function updateproductCheckbox() {
            const anyUnchecked = $('#Productchecklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#Productchecklist').prop('checked', !anyUnchecked);
        }
        $('#Productchecklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#Productchecklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#Productchecklistbox input[type="checkbox"]').change(function() {
            updateproductCheckbox();
        });
        updateproductCheckbox();
    });
    $(document).ready(function() {
        function updatemanageCheckbox() {
            const anyUnchecked = $('#inventory_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#inventory_checklist').prop('checked', !anyUnchecked);
        }
        $('#inventory_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#inventory_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#inventory_checklistbox input[type="checkbox"]').change(function() {
            updatemanageCheckbox();
        });
        updatemanageCheckbox();
    });
    $(document).ready(function() {
        function updatecollectCheckbox() {
            const anyUnchecked = $('#collections_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#collections_checklist').prop('checked', !anyUnchecked);
        }
        $('#collections_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#collections_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#collections_checklistbox input[type="checkbox"]').change(function() {
            updatecollectCheckbox();
        });
        updatecollectCheckbox();
    });
    $(document).ready(function() {
        function updatediscountCheckbox() {
            const anyUnchecked = $('#discounts_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#discounts_checklist').prop('checked', !anyUnchecked);
        }
        $('#discounts_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#discounts_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#discounts_checklistbox input[type="checkbox"]').change(function() {
            updatediscountCheckbox();
        });
        updatediscountCheckbox();
    });
    $(document).ready(function() {
        function updatememeberCheckbox() {
            const anyUnchecked = $('#memberships_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#memberships_checklist').prop('checked', !anyUnchecked);
        }
        $('#memberships_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#memberships_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#memberships_checklistbox input[type="checkbox"]').change(function() {
            updatememeberCheckbox();
        });
        updatememeberCheckbox();
    });
    $(document).ready(function() {
        function updateposCheckbox() {
            const anyUnchecked = $('#pos_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#pos_checklist').prop('checked', !anyUnchecked);
        }
        $('#pos_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#pos_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#pos_checklistbox input[type="checkbox"]').change(function() {
            updateposCheckbox();
        });
        updateposCheckbox();
    });
    $(document).ready(function() {
        function updateorderCheckbox() {
            const anyUnchecked = $('#orders_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#orders_checklist').prop('checked', !anyUnchecked);
        }
        $('#orders_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#orders_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#orders_checklistbox input[type="checkbox"]').change(function() {
            updateorderCheckbox();
        });
        updateorderCheckbox();
    });
    $(document).ready(function() {
        function updateuserCheckbox() {
            const anyUnchecked = $('#users_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#users_checklist').prop('checked', !anyUnchecked);
        }
        $('#users_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#users_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#users_checklistbox input[type="checkbox"]').change(function() {
            updateuserCheckbox();
        });
        updateuserCheckbox();
    });
    $(document).ready(function() {
        function updatecatsliderCheckbox() {
            const anyUnchecked = $('#cat_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#cat_checklist').prop('checked', !anyUnchecked);
        }
        $('#cat_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#cat_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#cat_checklistbox input[type="checkbox"]').change(function() {
            updatecatsliderCheckbox();
        });
        updatecatsliderCheckbox();
    });
    $(document).ready(function() {
        function updatebrandingCheckbox() {
            const anyUnchecked = $('#brandslider_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#brandslider_checklist').prop('checked', !anyUnchecked);
        }
        $('#brandslider_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#brandslider_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#brandslider_checklistbox input[type="checkbox"]').change(function() {
            updatebrandingCheckbox();
        });
        updatebrandingCheckbox();
    });
    $(document).ready(function() {
        function updatecontactlist() {
            const anyUnchecked = $('#contact_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#contact_checklist').prop('checked', !anyUnchecked);
        }
        $('#contact_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#contact_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#contact_checklistbox input[type="checkbox"]').change(function() {
            updatecontactlist();
        });
        updatecontactlist();
    });
    $(document).ready(function() {
        function updatepertquery() {
            const anyUnchecked = $('#query_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#query_checklist').prop('checked', !anyUnchecked);
        }
        $('#query_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#query_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#query_checklistbox input[type="checkbox"]').change(function() {
            updatepertquery();
        });
        updatepertquery();
    });
    $(document).ready(function() {
        function updatesubscription() {
            const anyUnchecked = $('#news_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#news_checklist').prop('checked', !anyUnchecked);
        }
        $('#news_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#news_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#news_checklistbox input[type="checkbox"]').change(function() {
            updatesubscription();
        });
        updatesubscription();
    });
    $(document).ready(function() {
        function updateweb() {
            const anyUnchecked = $('#web_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#web_checklist').prop('checked', !anyUnchecked);
        }
        $('#web_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#web_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#web_checklistbox input[type="checkbox"]').change(function() {
            updateweb();
        });
        updateweb();
    });
    $(document).ready(function() {
        function updatewebsetting() {
            const anyUnchecked = $('#set_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#set_checklist').prop('checked', !anyUnchecked);
        }
        $('#set_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#set_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#set_checklistbox input[type="checkbox"]').change(function() {
            updatewebsetting();
        });
        updatewebsetting();
    });
    $(document).ready(function() {
        function updatewebpages() {
            const anyUnchecked = $('#webpage_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#webpage_checklist').prop('checked', !anyUnchecked);
        }
        $('#webpage_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#webpage_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#webpage_checklistbox input[type="checkbox"]').change(function() {
            updatewebpages();
        });
        updatewebpages();
    });
    $(document).ready(function() {
        function updatepermission() {
            const anyUnchecked = $('#Permission_checklistbox input[type="checkbox"]:not(:checked)').length > 0;
            $('#Permission_checklist').prop('checked', !anyUnchecked);
        }
        $('#Permission_checklist').change(function() {
            const isChecked = $(this).is(':checked');
            $('#Permission_checklistbox input[type="checkbox"]').prop('checked', isChecked);
        });
        $('#Permission_checklistbox input[type="checkbox"]').change(function() {
            updatepermission();
        });
        updatepermission();
    });
</script>
