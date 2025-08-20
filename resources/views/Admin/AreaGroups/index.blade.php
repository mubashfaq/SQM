@include('Admin.Includes.header')
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --warning-color: #f39c12;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 0.5rem;
        --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .pageheader {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 1.5rem;
    }

    h1.tx-20 {
        font-weight: 600;
        color: var(--secondary-color);
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: var(--secondary-color);
    }

    .area-group h5 {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .btn {
        border-radius: var(--border-radius);
    }

    .search,
    .search_post {
        border-radius: var(--border-radius);
        padding: 0.75rem;
        border: 1px solid #dde2e6;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
    }

    .card {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: none;
    }

    .modal-content {
        border-radius: var(--border-radius);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .form-control,
    .form-select {
        border-radius: var(--border-radius);
        padding: 0.75rem;
        border: 1px solid #dde2e6;
    }

    .group-details {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .btn-group .btn {
        margin-right: 0.25rem;
    }

    .icon {
        margin-right: 0.5rem;
    }

    .alert {
        border-radius: var(--border-radius);
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fs-4 mb-1">{{ __('Shipping Area Groups') }}</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center ">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('products.index') }}" class="link-primary text-decoration-none">
                                        Products
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Shipping Area Group
                                </li>

                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
        <!--/ Breadcrumb End -->
        <div class="row mx-1">
            <div class="col-md-4 p-0">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light border-0">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" class="form-control search border-0"
                                placeholder="Search area groups..." />
                        </div>

                        <div class="group mb-4">
                            <div class="d-flex justify-content-between mb-3">
                                <small class="text-muted fw-bold">Please Select an Item Below</small>
                                <small class="text-primary">{{ $areaGroups->count() }} groups</small>
                            </div>
                            <div class="d-grid gap-2 area-groups-container"
                                style="max-height: 500px; overflow-y: auto;">
                                @foreach ($areaGroups->reverse() as $areaGroup)
                                    <div class="cursor-pointer area-group mb-1 border rounded shadow-sm"
                                        data-id="{{ $areaGroup->id }}" data-title="{{ $areaGroup->title }}"
                                        data-sku="{{ $areaGroup->sku }}" data-country="{{ $areaGroup->country }}">
                                        <h5 class="m-0 py-2 px-3">{{ $areaGroup->title }}</h5>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Button to trigger modal (Bootstrap 5 version) -->
                        @if (Session::has('permissions') && in_array('Area Group Add', Session::get('permissions')))
                            <button class="btn btn-success w-100 d-flex align-items-center justify-content-center"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa fa-plus-circle me-2"></i> Add New Group
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8 p-0 ps-0 ps-md-3 my-md-0 my-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="mb-3 fw-bold">Area Group Details</h3>
                        <div class="row p-3 border rounded bg-light mb-4">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <h5 class="mb-1 fw-bold">Title: <span class="group-title text-primary"></span></h5>
                                </div>
                                <div class="mb-2">
                                    <h5 class="mb-1 fw-bold">SKU: <span class="group-sku text-secondary"></span></h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3 fw-bold">No of Code: <span
                                        class="group-code-count badge bg-primary"></span></h5>
                                <div class="btn_show btn-group flex-wrap area-group-btns">
                                    @if (Session::has('permissions') && in_array('Area Group Edit', Session::get('permissions')))
                                        <button type="button" class="btn btn-info d-flex align-items-center m-0"
                                            data-bs-toggle="modal" data-bs-target="#add_more11"
                                            onclick="populateEditForm()">
                                            <i class="fa fa-edit me-2"></i> Edit
                                        </button>
                                    @endif
                                    @if (Session::has('permissions') && in_array('Area Group Delete', Session::get('permissions')))
                                        <button type="button" class="btn btn-danger btn-delete-group m-0">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-secondary btn-view-code m-0">
                                        <i class="fa fa-eye"></i> View Code
                                    </button>
                                    @if (Session::has('permissions') && in_array('Add More', Session::get('permissions')))
                                        <button type="button" class="btn btn-success d-flex align-items-center m-0"
                                            data-bs-toggle="modal" data-bs-target="#add_more">
                                            <i class="fa fa-plus me-2"></i> Add More
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control search_post border-0" name="searchText"
                                        placeholder="Search codes..." />
                                </div>
                                <div class="search_group mb-3 mt-4">
                                    <div class="table-responsive">
                                        <table id="code-table" class="table table-hover nowrap"
                                            style="width: 100% !important; display: none;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>PostCode</th>
                                                    <th width="180px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="product_type_rows" id="code-table-body">
                                                <tr class="product-row">
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>PostCode</th>
                                                    <th width="180px">Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <!-- Modal Header with gradient background -->
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">
                        <i class="fa fa-map-marker-alt me-2"></i>Add Area Group
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Success Alert (hidden by default) -->
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 d-none" role="alert">
                    Area group added successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="area-group-form" action="{{ route('area-groups.store') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="row g-3">
                            <!-- Title Input -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control restrict-input-alphabets_space addTitle"
                                        id="title_group" type="text" pattern="[A-Za-z_]+" name="title"
                                        required />
                                    <label for="title_group">
                                        <i class="fa fa-tag text-muted me-1"></i>Title
                                    </label>
                                </div>
                            </div>

                            <!-- SKU Input -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control restrict-input-alphabets addSku" id="sku_group"
                                        type="text" pattern="[A-Za-z_]+" name="sku" required />
                                    <label for="sku_group">
                                        <i class="fa fa-barcode text-muted me-1"></i>SKU
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Country Selection -->
                        <div class="form-floating mb-4">
                            <select class="form-select country" id="country_group" name="country">
                                <option>select country</option>
                                <option value="AF">Afghanistan</option>
                                <option value="AX">Aland Islands</option>
                                <option value="AL">Albania</option>
                                <option value="DZ">Algeria</option>
                                <option value="AS">American Samoa</option>
                                <option value="AD">Andorra</option>
                                <option value="AO">Angola</option>
                                <option value="AI">Anguilla</option>
                                <option value="AQ">Antarctica</option>
                                <option value="AG">Antigua and Barbuda</option>
                                <option value="AR">Argentina</option>
                                <option value="AM">Armenia</option>
                                <option value="AW">Aruba</option>
                                <option value="AU" selected>Australia</option>
                                <option value="AT">Austria</option>
                                <option value="AZ">Azerbaijan</option>
                                <!-- Truncated for brevity -->
                                <option value="ZW">Zimbabwe</option>
                            </select>
                            <label for="country_group">
                                <i class="fa fa-globe text-muted me-1"></i>Select Country
                            </label>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fa fa-file-csv text-muted me-1"></i>Select Area Groups CSV
                            </label>
                            <div class="small text-muted mb-2">CSV must have title, postcode columns <span
                                    class="text-danger">Required</span></div>
                            <div class="input-group">
                                <input class="form-control meta_csv" type="file" id="csv_group" name="meta_csv"
                                    accept=".csv" />
                                <span class="input-group-text bg-light"><i class="fa fa-upload"></i></span>
                            </div>
                        </div>
                    </form>

                    <div class="response_section"></div>
                </div>

                <!-- Modal Footer with styled buttons -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Close
                    </button>
                    <button type="button" class="btn btn-success" onclick="add_area_form()">
                        <i class="fa fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Area Group Update -->
    <!-- Edit Area Group Modal -->
    <div class="modal fade" id="add_more11" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <!-- Modal Header with gradient background -->
                <div class="modal-header bg-gradient-warning">
                    <h5 class="modal-title fw-bold" id="editModalLabel">
                        <i class="fa fa-edit me-2"></i>Area Group Update
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Success Alert (hidden by default) -->
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 d-none" role="alert">
                    Area group updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="edit_area_group_form" action="" method="PUT">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_area_id" name="area_group" value="">

                        <div class="row g-3">
                            <!-- Title Input -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control restrict-input-alphabets_space edit_addTitle"
                                        id="edit_title_group" type="text" pattern="[A-Za-z_]+" name="title"
                                        placeholder="Title" required>
                                    <label for="edit_title_group">
                                        <i class="fa fa-tag text-muted me-1"></i>Title
                                    </label>
                                </div>
                            </div>

                            <!-- SKU Input -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control restrict-input-alphabets edit_addSku"
                                        id="edit_sku_group" type="text" pattern="[A-Za-z_]+" name="sku"
                                        placeholder="Sku" required>
                                    <label for="edit_sku_group">
                                        <i class="fa fa-barcode text-muted me-1"></i>SKU
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Country Selection -->
                        <div class="form-floating mb-3">
                            <select class="form-select" id="edit_country_group" name="country">
                                <option selected disabled>Select country</option>
                                <option value="AF">Afghanistan</option>
                                <option value="AX">Aland Islands</option>
                                <option value="AL">Albania</option>
                                <option value="DZ">Algeria</option>
                                <option value="AS">American Samoa</option>
                                <option value="AD">Andorra</option>
                                <option value="AO">Angola</option>
                                <option value="AI">Anguilla</option>
                                <option value="AQ">Antarctica</option>
                                <option value="AG">Antigua and Barbuda</option>
                                <option value="AR">Argentina</option>
                                <option value="AM">Armenia</option>
                                <option value="AW">Aruba</option>
                                <option value="AU">Australia</option>
                                <option value="AT">Austria</option>
                                <option value="AZ">Azerbaijan</option>
                                <!-- Truncated for brevity -->
                                <option value="ZW">Zimbabwe</option>
                            </select>
                            <label for="edit_country_group">
                                <i class="fa fa-globe text-muted me-1"></i>Select Country
                            </label>
                        </div>
                    </form>

                    <div class="response_section"></div>
                </div>

                <!-- Modal Footer with styled buttons -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="editAreaGroup()">
                        <i class="fa fa-save me-1"></i>Update Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add More Area Group Modal -->
    <div class="modal fade" id="add_more" tabindex="-1" aria-labelledby="addMoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <!-- Modal Header with gradient background -->
                <div class="modal-header bg-gradient-success text-white">
                    <h5 class="modal-title fw-bold" id="addMoreModalLabel">
                        <i class="fa fa-plus-circle me-2"></i>Add More Area Group
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Success Alert (hidden by default) -->
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 d-none" role="alert">
                    Additional area groups added successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="area-group-form" action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="csv_group_file" class="form-label fw-bold">
                                <i class="fa fa-file-csv text-muted me-2"></i>Choose CSV File:
                            </label>
                            <div class="small text-muted mb-2">Upload a CSV file with area group data</div>
                            <div class="input-group">
                                <input class="form-control" type="file" id="csv_group_file" name="meta_csv"
                                    accept=".csv">
                                <span class="input-group-text bg-light"><i class="fa fa-upload"></i></span>
                            </div>
                        </div>
                    </form>

                    <div class="response_section"></div>
                </div>

                <!-- Modal Footer with styled buttons -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="add_more_area_group_form()">
                        <i class="fa fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit -->
    <!-- Edit Area Group -->
    <div class="modal fade" id="edit_area_group_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="exampleModalLabel">Area Group Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="alert alert-success d-none" role="alert"></div>
                <div class="modal-body">
                    <form id="edit_area_group_form" action="" method="PUT">
                        @csrf @method('PUT')
                        <div class="row">
                            <input type="hidden" id="edit_area_id" name="area_group" value="" />
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control restrict-input-alphabets_space edit_addTitle"
                                        id="edit_title_group" type="text" pattern="[A-Za-z_]+" name="title"
                                        placeholder="Title" required />
                                    <label for="edit_title_group">Title</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control restrict-input-alphabets edit_addSku"
                                        id="edit_sku_group" type="text" pattern="[A-Za-z_]+" name="sku"
                                        placeholder="Sku" required />
                                    <label for="edit_sku_group">SKU</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="Default select example" id="edit_country_group"
                                name="country">
                                <option>select country</option>
                                <option value="AF">Afghanistan</option>
                                <option value="AX">Aland Islands</option>
                                <option value="AL">Albania</option>
                                <option value="DZ">Algeria</option>
                                <option value="AS">American Samoa</option>
                                <option value="AD">Andorra</option>
                                <option value="AO">Angola</option>
                                <option value="AI">Anguilla</option>
                                <option value="AQ">Antarctica</option>
                                <option value="AG">Antigua and Barbuda</option>
                                <option value="AR">Argentina</option>
                                <option value="AM">Armenia</option>
                                <option value="AW">Aruba</option>
                                <option value="AU" selected>Australia</option>
                                <option value="AT">Austria</option>
                                <option value="AZ">Azerbaijan</option>
                                <option value="ZW">Zimbabwe</option>
                            </select>
                            <label for="edit_country_group">Select Country</label>
                        </div>
                    </form>
                    <div class="response_section"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editAreaGroup()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Area Group Confirmation Modal -->
    <div class="modal fade zoomIn" id="deleteAreaGroupModal" tabindex="-1"
        aria-labelledby="deleteAreaGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-md-5">
                    <div class="text-center">
                        <div class="text-danger">
                            <i class="fa-solid fa-trash fs-1"></i>
                        </div>
                        <div class="mt-4 fs-15">
                            <h4 class="mb-1">Are you sure?</h4>
                            <p class="text-muted mx-3 fs-16 mb-0">
                                Are you sure you want to delete <strong class="group-title-text"></strong>?<br>
                                This action cannot be undone.
                            </p>

                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light btn-hover"
                            data-bs-dismiss="modal">Cancel</button>
                        <form class="delete-group-form" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-sm btn-danger btn-hover">Yes, Delete It!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Code Confirmation Modal -->
    <div class="modal fade zoomIn" id="deleteCodeModal" tabindex="-1" aria-labelledby="deleteCodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-md-5">
                    <div class="text-center">
                        <div class="text-danger">
                            <i class="fa-solid fa-trash fs-1"></i>
                        </div>
                        <div class="mt-4 fs-15">
                            <h4 class="mb-1">Are you sure?</h4>
                            <p class="text-muted mx-3 fs-16 mb-0">
                                Are you sure you want to delete code <strong class="code-title-text"></strong>?<br>
                                This action cannot be undone.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light btn-hover"
                            data-bs-dismiss="modal">Cancel</button>
                        <form class="delete-code-form" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-sm btn-danger btn-hover">Yes, Delete It!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(".addTitle").on("input", function() {
        var title = $(this).val();
        var sku = title.replace(/\s+/g, "-").toLowerCase();
        $(".addSku").val(sku);
    });

    $(".edit_addTitle").on("input", function() {
        var title = $(this).val();
        var sku = title.replace(/\s+/g, "-").toLowerCase();
        $(".edit_addSku").val(sku);
    });

    function add_area_form() {
        var formData = new FormData();
        formData.append("country", $("#country_group").val());
        formData.append("meta_csv", $("#csv_group").prop("files")[0]);
        formData.append("sku", $("#sku_group").val()); // Add this line to include the 'sku' field
        formData.append("title", $("#title_group").val());
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

        $.ajax({
            url: '{{ route('area-groups.store') }}',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var groupTitle = $("#title_group").val(); // Get the group title from the input field
                $("#groupTitle").text(groupTitle); // Set the group title in the <h3> element

                // Show a Bootstrap success alert
                $(".alert-success")
                    .text("Your Area Group Saved Successfully")
                    .removeClass("d-none");

                // Reload the page to reflect the updated data
                location.reload();
            },
        });
    }

    function add_more_area_group_form() {
        var formData = new FormData();
        formData.append("meta_csv", $("#csv_group_file").prop("files")[0]);
        formData.append("id", $(".area-group.selected").attr("data-id"));
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

        $.ajax({
            url: '{{ route('add-more-group-area') }}',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Show a Bootstrap success alert
                $(".alert-success")
                    .text("PostCode Saved Successfully")
                    .removeClass("d-none")
                    .alert();
                // Reload the page to reflect the updated data
                location.reload();
                // Add the newly added postcode to the UI without reloading the page
                var tableBody = $(".product_type_rows");
                var newPostcode = response.postcode;
                var row = "<tr>" +
                    "<td>" + newPostcode.title + "</td>" +
                    "<td>" + newPostcode.postcode + "</td>" +
                    "<td>" +
                    "<form action='' method='POST'>" +
                    "<button type='button' class='btn btn-warning mt-2 btn-sm btn-edit-group' data-id='" +
                    newPostcode.id + "'>Edit</button> " +
                    "<button type='button' class='btn btn-danger mt-2 btn-sm btn-delete-group' data-id='" +
                    newPostcode.id + "'>Delete</button>" +
                    "</form>" +
                    "</td>" +
                    "</tr>";

                tableBody.append(row);

                // Clear the file input field
                $("#csv_group_file").val("");
            },
        });
    }
</script>

<script>
    function populateEditForm() {
        var areaGroup = getSelectedAreaGroup();

        $("#edit_title_group").val(areaGroup.title);
        $("#edit_area_id").val(areaGroup.edite);
        $("#edit_sku_group").val(areaGroup.sku);
        $("#edit_country_group").val(areaGroup.country);

    }

    function getSelectedAreaGroup() {
        var selectedAreaGroup = $(".area-group.selected");
        var id = selectedAreaGroup.data("id");
        var title = selectedAreaGroup.data("title");
        var sku = selectedAreaGroup.data("sku");
        var country = selectedAreaGroup.data("country");

        var areaGroup = {
            id: id,
            title: title,
            sku: sku,
            country: country,
        };
        return areaGroup;
    }
</script>

<script>
    $(".edit_addTitle").on("input", function() {
        var title = $(this).val();
        var sku = title.replace(/\s+/g, "-").toLowerCase();
        $(".edit_addSku").val(sku);
    });

    var editid = "";
    $(".area-group").on("click", function() {

        var areaGroupId = $(".area-group.selected").data("id");
        $("#code-table-body").empty();

        var title = $(this).data("title");
        var sku = $(this).data("sku");

        // Update the area group details
        $(".group-title").text(title);
        $(".group-sku").text(sku);
        $(".group-editid").text(editid);
        $("#edit_area_id").text($(".area-group").attr("data-id"));

        // Show the area group details
        $(".area-group-details").show();
    });

    function editAreaGroup() {
        var form = $("#edit_area_group_form");
        var formData = new FormData(form[0]);

        // Get the area group ID from the hidden input field
        var areaGroupId = $(".area-group.selected").attr("data-id");

        // Set the area group ID in the formData
        formData.set("area_group", areaGroupId);

        $.ajax({
            url: "{{ url('/admin/area-groups') }}/" + areaGroupId,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Update the preview HTML with the updated data
                $(".group-title").text(response.title);
                $(".group-sku").text(response.sku);

                // Display a success message
                $(".alert-success")
                    .text("Data updated successfully!")
                    .removeClass("d-none")
                    .alert();

                // Reload the page to reflect the updated data
                location.reload();
            },
            error: function(xhr, status, error) {
                // Display an error message or perform any error handling
                console.log(error);
            },
        });
    }
</script>

<script>
    $(document).ready(function() {
        $(".search").on("input", function() {
            var searchText = $(this).val().toLowerCase();
            $(".area-group").each(function() {
                var title = $(this).data("title").toLowerCase();
                if (title.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });


    $(document).ready(function() {
        $(".btn_show").hide();
        $(".area-group").on("click", function() {

            var title = $(this).data("title");
            var sku = $(this).data("sku");

            // Update the area group details
            $(".group-title").text(title);
            $(".group-sku").text(sku);

            // Show the area group details
            $(".area-group-details").show();

            // Show the buttons
            $(".btn_show").show();
        });
    });


    /////view the No Of Codes////
    function viewCode() {
        var areaGroupId = $(".area-group.selected").data("id");

        $.ajax({
            url: "/admin/area-groups/" + areaGroupId + "/codes/count",
            type: "GET",
            success: function(response) {
                $(".group-code-count").text(response.count);
                console.log(success);
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.log(error);
            },
        });
    }

    $(".area-group").on("click", function() {
        var areaGroupId = $(this).data("id");
        var title = $(this).data("title");
        var sku = $(this).data("sku");

        // Update the area group details
        $(".group-title").text(title);
        $(".group-sku").text(sku);

        // Make an AJAX request to fetch the count of codes
        $.ajax({
            url: "/admin/area-groups/" + areaGroupId + "/codes/count",
            type: "GET",
            success: function(response) {
                $(".group-code-count").text(response.count);
            },
            error: function(xhr, status, error) {
                // Handle the error if needed
                console.log(error);
            },
        });

        // Show the area group details
        $(".area-group-details").show();
    });

    /////view code////
    // $(document).ready(function () {
    //     $(".btn-view-code").on("click", function () {
    //         $("#code-table").toggle();
    //     });
    // });



    /////view code show details///
    // Update the code to handle the "View Code" button click event
    // Area Group Delete Confirmation Modal Implementation
    $(document).ready(function() {
        // Area group click handler
        $(".area-group").on("click", function() {
            $(".area-group").removeClass("selected");
            $(this).addClass("selected");

            var groupId = $(this).data("id");
            var groupTitle = $(this).data("title");

            $(".group-title").text(groupTitle);
        });

        // Replace the existing delete handler with modal implementation
        $(".btn-delete-group").on("click", function() {
            var areaGroup = $(".area-group.selected");

            if (areaGroup.length === 0) {
                // No group selected
                return;
            }

            var groupId = areaGroup.data("id");
            var groupTitle = areaGroup.data("title");

            // Show the delete confirmation modal
            $("#deleteAreaGroupModal").find(".group-title-text").text(groupTitle);
            $("#deleteAreaGroupModal").find(".delete-group-form").attr("action", "/admin/area-groups/" +
                groupId);
            $("#deleteAreaGroupModal").modal("show");
        });

        // View Code button handler
        $(".btn-view-code").on("click", function() {
            var areaGroupId = $(".area-group.selected").data("id");
            $("#code-table-body").empty(); // Clear existing rows

            // Make an AJAX request to fetch the data
            $.ajax({
                url: "/admin/area-groups/" + areaGroupId + "/codes",
                type: "GET",
                success: function(response) {
                    if (response.data.length > 0) {
                        // Iterate through the data and create table rows
                        $.each(response.data, function(index, item) {
                            var row =
                                "<tr>" +
                                "<td class='numbering-td'>" + (index + 1) +
                                "</td>" +
                                "<td class='title-td'>" + item.title + "</td>" +
                                "<td class='postcode-td'>" + item.postcode +
                                "</td>" +
                                "<td>" +
                                '<div class="btn-group btn-group-sm">' +
                                @if (Session::has('permissions') && in_array('Post Code Edit', Session::get('permissions')))
                                    '<button type="button" class="btn btn-primary mt-2 btn-sm btn-edit-group" data-id="' +
                                    item.id + '">Edit</button> ' +
                                @endif
                            @if (Session::has('permissions') && in_array('Post Code Delete', Session::get('permissions')))
                                '<button type="button" class="btn btn-danger mt-2 btn-sm btn-delete-code btn-sm" data-id="' +
                                item.id + '" data-title="' + item.title +
                                    '">Delete</button>' +
                            @endif
                            '</div>' +
                            "</td>" +
                            "</tr>";
                            $(".product_type_rows").append(row);
                        });

                        // Add click event listener to delete buttons
                        $(".btn-delete-code").on("click", function() {
                            var deleteButton = $(this);
                            var id = deleteButton.data("id");
                            var codeTitle = deleteButton.data("title");
                            var areaGroupId = $(".area-group.selected").data("id");

                            // Show the delete confirmation modal
                            $("#deleteCodeModal").find(".code-title-text").text(
                                codeTitle);
                            $("#deleteCodeModal").find(".delete-code-form").attr(
                                "action", "/admin/area-groups/" + areaGroupId +
                                "/codes/" + id);
                            $("#deleteCodeModal").modal("show");
                        });

                        // Add click event listener to edit buttons (Better State-Based Approach)
                        var csrfToken = $('meta[name="csrf-token"]').attr('content');

                        $('.btn-edit-group').on('click', function() {
                            var editButton = $(this);
                            var isEditMode = editButton.text() === "Edit";
                            var id = editButton.data('id');
                            var titleTd = editButton.closest('tr').find(
                            '.title-td');
                            var postcodeTd = editButton.closest('tr').find(
                                '.postcode-td');

                            if (isEditMode) {
                                // Switch to Edit Mode
                                var currentTitle = titleTd.text();
                                var currentPostcode = postcodeTd.text();

                                // Store original values in data attributes
                                editButton.data('original-title', currentTitle);
                                editButton.data('original-postcode',
                                    currentPostcode);

                                // Create input fields
                                var titleInput = $("<input>").attr("type", "text")
                                    .val(currentTitle);
                                var postcodeInput = $("<input>").attr("type",
                                    "text").val(currentPostcode);

                                // Replace the text with input fields
                                titleTd.html(titleInput);
                                postcodeTd.html(postcodeInput);

                                // Focus on the title input field
                                titleInput.focus();

                                // Update button text to "Save"
                                editButton.text("Save");

                            } else {
                                // Switch to Save Mode
                                var updatedTitle = titleTd.find('input').val();
                                var updatedPostcode = postcodeTd.find('input')
                            .val();

                                // Make AJAX request to update
                                $.ajax({
                                    url: "/admin/area-groups/" +
                                        areaGroupId + "/codes/" + id,
                                    type: "PUT",
                                    headers: {
                                        "X-CSRF-TOKEN": csrfToken,
                                    },
                                    data: {
                                        title: updatedTitle,
                                        postcode: updatedPostcode,
                                    },
                                    success: function(response) {
                                        // Replace input fields with updated text
                                        titleTd.text(updatedTitle);
                                        postcodeTd.text(
                                        updatedPostcode);

                                        // Update button text back to "Edit"
                                        editButton.text("Edit");
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error - restore original values
                                        console.log(error);

                                        var originalTitle = editButton
                                            .data('original-title');
                                        var originalPostcode =
                                            editButton.data(
                                                'original-postcode');

                                        titleTd.text(originalTitle);
                                        postcodeTd.text(
                                            originalPostcode);
                                        editButton.text("Edit");
                                    },
                                });
                            }
                        });

                    } else {
                        // No data found
                        var emptyRow =
                            '<tr><td colspan="4" class="text-center">No codes found</td></tr>';
                        $(".product_type_rows").append(emptyRow);
                    }

                    // Show the table
                    $("#code-table").show();
                },
                error: function(xhr, status, error) {
                    // Handle the error if needed
                    console.log(error);
                },
            });
        });

        // Handle delete confirmation form submission for area group
        $("#deleteAreaGroupModal .delete-group-form").on("submit", function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr("action");

            $.ajax({
                url: url,
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(response) {
                    // Hide the area group details
                    $(".area-group-details").hide();

                    // Hide modal
                    $("#deleteAreaGroupModal").modal("hide");

                    // Show a success message
                    $(".alert-success")
                        .text("Group deleted successfully!")
                        .removeClass("d-none")
                        .alert();

                    // Remove the alert after 5 seconds
                    setTimeout(function() {
                        $(".alert-success").fadeOut(500, function() {
                            $(this).addClass("d-none");
                        });
                    }, 5000);

                    // Reload the page
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Hide modal
                    $("#deleteAreaGroupModal").modal("hide");

                    // Show an error message
                    $(".alert-danger")
                        .text("Failed to delete group! Error: " + error)
                        .removeClass("d-none")
                        .alert();

                    // Remove the alert after 5 seconds
                    setTimeout(function() {
                        $(".alert-danger").fadeOut(500, function() {
                            $(this).addClass("d-none");
                        });
                    }, 5000);
                },
            });
        });

        // Handle delete confirmation form submission for code
        $("#deleteCodeModal .delete-code-form").on("submit", function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr("action");

            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(response) {
                    // Hide modal
                    $("#deleteCodeModal").modal("hide");

                    // Find and remove the row
                    var codeId = url.split('/').pop();
                    $(".btn-delete-code[data-id='" + codeId + "']").closest("tr").remove();

                    // Update the code count
                    var codeCount = parseInt($(".group-code-count").text());
                    $(".group-code-count").text(codeCount - 1);

                    // Show success message with Bootstrap alert
                    $(".alert-success")
                        .text("The code has been deleted.")
                        .removeClass("d-none")
                        .alert();

                    // Remove the alert after 5 seconds
                    setTimeout(function() {
                        $(".alert-success").fadeOut(500, function() {
                            $(this).addClass("d-none");
                        });
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    // Hide modal
                    $("#deleteCodeModal").modal("hide");

                    // Show error message with Bootstrap alert
                    $(".alert-danger")
                        .text("Error: Failed to delete the code.")
                        .removeClass("d-none")
                        .alert();

                    // Remove the alert after 5 seconds
                    setTimeout(function() {
                        $(".alert-danger").fadeOut(500, function() {
                            $(this).addClass("d-none");
                        });
                    }, 5000);

                    // Handle the error if needed
                    console.log(error);
                },
            });
        });

    });

    // Add an event listener to the search input for filtering table rows
    $(".search_post").on("keyup", function() {
        var searchText = $(this).val().toLowerCase();

        // Iterate through table rows and hide those that do not match the search
        $("#code-table-body tr").each(function() {
            var row = $(this);
            var title = row.find(".title-td").text().toLowerCase();
            var postcode = row.find(".postcode-td").text().toLowerCase();

            if (title.includes(searchText) || postcode.includes(searchText)) {
                row.show();
            } else {
                row.hide();
            }
        });
    });
</script>
