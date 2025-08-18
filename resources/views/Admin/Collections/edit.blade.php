@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
    use App\General\CollectionClass;
    use App\General\ProductCollectionClass;
@endphp

<style>
    .back_image_icon {
        background-image: url("http://127.0.0.1:8000/products/1684133865.jpeg");
        background-size: 27px;
        background-position: center;
        background-repeat: no-repeat;
        height: 40px;
        width: 40px;
    }

    .product_image_icon {
        background-size: 27px;
        background-position: center;
        background-repeat: no-repeat;
        height: 40px;
        width: 40px;
    }

    .img_border {
        height: 40px;
        width: 40px;
        border: 2px solid gainsboro;
        border-radius: 8px;
    }

    .show_data {
        display: flex;
    }

    .title-truncate {
        display: inline-block;
        max-width: 150px;
        /* Adjust this width as needed */
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .products-list {
        max-height: 400px;
        overflow: auto;
    }
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="page-inner">
            <!--================================-->
            <!-- Breadcrumb Start -->
            <!--================================-->
            <div class="card shadow-sm mb-4">

                <div class="pageheader pd-t-25 pd-b-35">
                    <div class="  p-3 mb-3 d-flex justify-content-between">
                        <div class="clearfix">
                            <div class="pd-t-5 pd-b-5">
                                <h4 class="mb-1">{{ __('Edit Collection') }}</h4>
                            </div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent p-0 m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('Admin.Dashboard') }}"
                                            class="link-primary text-decoration-none">
                                            <i class="fa-solid fa-home me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url('/admin/collections') }}"
                                            class="link-primary text-decoration-none">
                                            Collections
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        @if (Session::has('permissions') &&
                                (in_array('Create a Collection', Session::get('permissions')) ||
                                    in_array('Collection List', Session::get('permissions')) ||
                                    in_array('Collection Trash List', Session::get('permissions'))))
                            <div class="d-flex align-items-center gap-2">
                                @if (Session::has('permissions') && in_array('Create a Collection', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/collections/create" class="btn btn-success">
                                        <i class="fa-solid fa-circle-plus me-1"></i> Create Collection
                                    </a>
                                @endif
                                @if (Session::has('permissions') && in_array('Collection List', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/collections" class="btn btn-primary">
                                        <i class="fa-solid fa-list-ul me-1"></i> Collection List
                                    </a>
                                @endif
                                @if (Session::has('permissions') && in_array('Collection Trash List', Session::get('permissions')))
                                    <a href="{{ url('/') }}/admin/collections/list/trash" class="btn btn-danger">
                                        <i class="fa-solid fa-trash me-1"></i> Trash List
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--/ Breadcrumb End -->
            <!--================================-->
            <!--================================-->
            <div class="col-md-12 col-lg-12 px-0 mt-3">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="Brandr-create-form" method="POST" action="{{ route('collections.update', $collection->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('Collection Details (Compulsory)') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Title: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control form-control-lg  addTitle" type="text"
                                            name="title" placeholder="HITACHI"
                                            value="{{ old('title', $collection->title) }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Permalink: <span
                                                class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span
                                                    class="input-group-text input-group-text">{{ url('/') }}/collections/</span>
                                            </div>
                                            <input class="form-control form-control restrict-input-alphabets permalink"
                                                id="permalink" type="text" name="permalink"
                                                value="{{ old('title', $collection->permalink) }}"
                                                placeholder="hitachi" required>
                                        </div>
                                        <div class="text-danger permalink_error"></div>
                                        <input type="hidden" class="permalink_status" name="permalink_status"
                                            value="{{ old('permalink_status') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">Description:</label>
                                        <textarea class="form-control form-control-lg  adddescription" name="description" placeholder="HITACHI"
                                            value="{{ old('description', $collection->description) }}">{{ old('description', $collection->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">{{ __('Product') }}</h6>
                                        </div>
                                        @if (Session::has('permissions') && in_array('Add Product In Collection', Session::get('permissions')))
                                            <div class="input-group-append pl-2">
                                                <button class="btn btn-success rounded-pill px-4 shadow-sm"
                                                    type="button" data-bs-toggle="modal"
                                                    data-bs-target="#editproductModel">
                                                    <i class="fas fa-plus me-2"></i>Add Product
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                    </div>
                                    <div class="mt-2">
                                        <div class="search-message"
                                            style="display: none; color: #979797; text-align: center; margin-top: 2px;">
                                            No matching records found
                                        </div>
                                        <table id="data_Table" class="table hover responsive display nowrap"
                                            style=" width: 100% !important;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th width="100px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($collectionproducts as $collectionproduct)
                                                    <tr data-category="{{ $collectionproduct->category_id }}">
                                                        <td>{{ $i }}</td>
                                                        <td>
                                                            <div class="product-detail-cell">
                                                                <div class="back_image_icon mr-2"
                                                                    style="background:url({{ url('/') . '/' . $collectionproduct->featured_image }});background-size:cover;background-position:center">
                                                                </div>
                                                                <a
                                                                    class="product-title"target="_blank">{{ $collectionproduct->title }}</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($collectionproduct->id)
                                                                <div class="btn-group btn-group-sm" role="group"
                                                                    aria-label="...">
                                                                    @if (Session::has('permissions') && in_array('Delete Collection Product', Session::get('permissions')))
                                                                        <button class="btn btn-danger" type="button"
                                                                            onclick="deleteProduct({{ $collectionproduct->id }})">Delete</button>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php $i++; @endphp
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th width="100px">Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('SEO Details (Optional)') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">SEO Title: </label>
                                        <input type="text" class="form-control" name="_seo_title"
                                            value="{{ old('_seo_title', CollectionClass::getOptionValue('_seo_title', $collection->brandMeta)) }}"
                                            placeholder="SEO Title...">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">SEO Tags: </label>
                                        <input type="text" name="_seo_tags"
                                            value="{{ old('_seo_tags', CollectionClass::getOptionValue('_seo_tags', $collection->brandMeta)) }}"
                                            class="form-control rounded-0" id="seo_tags">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label active">SEO Description: </label>
                                        <textarea class="form-control" name="_seo_description" placeholder="SEO Description...">{{ old('_seo_description', CollectionClass::getOptionValue('_seo_description', $collection->brandMeta)) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">{{ __('Collection Actions') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p>Created By: @php print(CollectionClass::CreateBy($collection->created_by)); @endphp</p>
                                    <p>Created Date: {{ $collection->created_at ?? '' }}</p>
                                    <p>Updated Date: {{ $collection->updated_at ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mg-b-30">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-header-title tx-13 mb-0">
                                                {{ __('More Details (Required)') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Active Status: </label>
                                        <select class="form-control" name="active">
                                            <option value="0" @if (old('active', $collection->active) == 0) selected @endif>
                                                Draft
                                            </option>
                                            <option value="1" @if (old('active', $collection->active) == 1) selected @endif>
                                                Public
                                            </option>
                                            <option value="2" @if (old('active', $collection->active) == 2) selected @endif>
                                                Private
                                            </option>
                                            <option value="3" @if (old('active', $collection->active) == 3) selected @endif>
                                                Trash
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-control-label active">Select Discount (Optional): </label>
                                        <select class="form-control" name="discount_id">
                                            <option value="0" selected disabled>select Discount On Collection
                                            </option>
                                            @foreach ($discounts as $discount)
                                                <option value="{{ $discount->id }}"
                                                    @if ($collection->discount_id == $discount->id) selected @endif>
                                                    {{ $discount->title }}
                                                </option>
                                            @endforeach
                                            <option value="0" @if ($collection->discount_id == 0) selected @endif>
                                                General
                                                Collection</option>
                                        </select>
                                    </div>
                                    <input class="btn btn-success float-right" type="submit" value="Update">
                                    <input class="btn btn-secondary float-right mx-1" type="button"
                                        onclick="window.location = '{{ url('/') }}/admin/collections'"
                                        value="Cancel">
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>

        <!-- Product Model Start -->
        <div class="modal fade" id="editproductModel" tabindex="-1" aria-labelledby="productModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold" id="productModalLabel">
                            <i class="fas fa-box me-2"></i>All Products
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="unchecked"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="collection_product_form" method="POST">
                            @csrf
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" id="searchProducts"
                                        class="form-control border-start-0" placeholder="Search Products">
                                </div>
                            </div>

                            <div id="saveform_errList" class="alert alert-danger" style="display: none;"></div>
                            <div id="success_message" class="alert alert-success" style="display: none;"></div>

                            <div class="products-list card p-2" style="max-height: 400px; overflow-y: auto;">
                                @if ($products->isEmpty())
                                    <p class="text-center py-4 text-muted">No records found.</p>
                                @else
                                    <input type="hidden" name="collection_discount_id" id="collection_discount_id"
                                        value="{{ $collection->discount_id }}">
                                    <input type="hidden" name="collection_id" id="collection_id"
                                        value="{{ $collection->id }}">

                                    @foreach ($products as $product)
                                        <input type="hidden" name="discount_id" id="discount_id"
                                            value="{{ $product->discount_id }}">
                                        <div class="product-item border-bottom p-2">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check">
                                                    <input type="checkbox" name="product_id" class="form-check-input"
                                                        value="{{ $product->id }}"
                                                        id="product-{{ $product->id }}">
                                                </div>
                                                <div class="product-image ms-3 me-3 rounded product-clickable"
                                                    style="background-image: url('{{ url('/') . '/' . $product->featured_image }}'); 
                                                        width: 50px; height: 50px; background-size: cover; background-position: center;">
                                                </div>
                                                <div>
                                                    <label class="title-truncate product-clickable mb-0"
                                                        for="product-{{ $product->id }}">{{ $product->title }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="no-records-message text-center text-muted py-4" style="display: none;">
                                <i class="fas fa-search fa-2x mb-3"></i>
                                <p>Record not found.</p>
                            </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <span class="text-muted small">Click on product row to select/deselect</span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    id="unchecked_product">
                                    <i class="fas fa-times me-1"></i>Close
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Add
                                </button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.addTitle').on('input', function() {
        var title = $(this).val();
        var sku = title.replace(/\s+/g, '-').toLowerCase();
        $('.permalink').val(sku);
        validate_permalink();
    });
    $('.permalink').on('input', function() {
        validate_permalink();
    });
    // $('#seo_tags').tagsinput({
    //     tagClass: 'badge badge-dark'
    // });
</script>

<script>
    $(document).ready(function() {
        $("#unchecked_product, #unchecked").on("click", function() {
            $("input[type='checkbox']").prop("checked", false);
        });
    });

    $(document).ready(function() {
        $(".product-clickable").on("click", function() {
            var checkbox = $(this).closest(".d-flex").find("input[type='checkbox']");
            checkbox.prop("checked", !checkbox.prop("checked"));
        });
    });

    $(document).ready(function() {
        // Store original products HTML
        var originalProductsHtml = $('.products-list').html();

        // Variable to store collection ID
        var collectionId = $('#collection_id').val();

        $('#searchProducts').on('input', function() {
            var searchTerm = $(this).val().trim();
            console.log(searchTerm);
            var resultsContainer = $('.products-list');

            if (searchTerm === "") {
                resultsContainer.html(originalProductsHtml);
                $('.no-records-message').hide();
            } else {
                $.ajax({
                    url: '/search-products',
                    method: 'GET',
                    data: {
                        search: searchTerm
                    },
                    success: function(response) {
                        resultsContainer.empty();
                        if (response.searchResults.length > 0) {
                            var collectionDiscountId = response.collection.discount_id;
                            collectionId = response.collection.id; // Update collectionId
                            console.log(collectionId, collectionDiscountId);

                            // Add hidden inputs first before adding products
                            resultsContainer.append($('<input>', {
                                'type': 'hidden',
                                'name': 'collection_discount_id',
                                'id': 'collection_discount_id',
                                'value': collectionDiscountId
                            }));

                            resultsContainer.append($('<input>', {
                                'type': 'hidden',
                                'name': 'collection_id',
                                'id': 'collection_id',
                                'value': collectionId
                            }));

                            $.each(response.searchResults, function(index, product) {
                                var productItem = $('<div>', {
                                    'class': 'product-item border-bottom p-2'
                                });

                                var productInfo = $('<div>', {
                                    'class': 'd-flex align-items-center'
                                });

                                var checkboxElement = $('<div>', {
                                    'class': 'form-check',
                                    'html': '<input type="checkbox" name="product_id" value="' +
                                        product.id +
                                        '" class="form-check-input" id="product-' +
                                        product.id + '">'
                                });

                                var imageElement = $('<div>', {
                                    'class': 'product-image ms-3 me-3 rounded product-clickable',
                                    'style': 'background-image: url("' +
                                        '{{ url('/') }}' + '/' +
                                        product.featured_image +
                                        '"); width: 50px; height: 50px; background-size: cover; background-position: center;'
                                });

                                var titleElement = $('<div>', {
                                    'class': 'flex-grow-1'
                                }).append($('<label>', {
                                    'class': 'title-truncate product-clickable mb-0 fw-medium',
                                    'for': 'product-' + product.id,
                                    'text': product.title
                                }));

                                productInfo.append(checkboxElement, imageElement,
                                    titleElement);
                                productItem.append(productInfo);
                                resultsContainer.append(productItem);
                            });

                            $('.no-records-message').hide();
                        } else {
                            $('.no-records-message').show();
                        }
                    },
                    error: function(error) {
                        console.error('Error searching products:', error);
                    }
                });
            }
        });

        // Make product items clickable to toggle checkbox
        $(document).on('click', '.product-clickable', function() {
            var checkboxItem = $(this).closest('.product-item').find('input[type="checkbox"]');
            checkboxItem.prop('checked', !checkboxItem.prop('checked'));
        });

        // Reset checkboxes when modal is closed
        $('#unchecked, #unchecked_product').on('click', function() {
            $('.products-list input[type="checkbox"]').prop('checked', false);
        });
    });
</script>

<script>
    $('#collection_product_form').submit(function(e) {
        e.preventDefault();
        const checkedIds = [];
        $('input[type="checkbox"]:checked').each(function() {
            checkedIds.push($(this).val());
        });

        if (checkedIds.length === 0) {
            $('#saveform_errList').html("");
            $('#saveform_errList').addClass('alert alert-danger m-2');
            $('#saveform_errList').text('Please select at least one product').show();
            return; // Stop form submission
        }

        const discountId = $('#collection_discount_id').val();

        // Prepare the form data
        const formData = new FormData(this);
        formData.delete('checked_ids[]');
        for (const id of checkedIds) {
            formData.append('checked_ids[]', id);
        }
        formData.append('discount_id', discountId);

        $.ajax({
            url: '/store-collection-product',
            method: "POST",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(res) {
                console.log(res);
                $('#saveform_errList').html("").removeClass('alert alert-danger m-2').hide();
                $('#success_message').html("").removeClass('alert alert-success m-2').hide();

                if (res.products_with_discounts && res.products_with_discounts.length > 0) {
                    var discountProductListHTML = '<ul>';
                    res.products_with_discounts.forEach(function(product) {
                        discountProductListHTML += '<li>Product SKU: ' + product.sku +
                            ' - Already has a discount applied</li>';
                    });
                    discountProductListHTML += '</ul>';
                    $('#saveform_errList').append(
                        '<div class="alert alert-warning m-2" id="autoDismissAlert1"><strong>Products with Discount:</strong>' +
                        discountProductListHTML + '</div>').show();
                    setTimeout(function() {
                        $('#autoDismissAlert1').alert('close');
                    }, 5000);
                }

                if (res.already_existing_products && res.already_existing_products.length > 0) {
                    var existingProductListHTML = '<ul>';
                    res.already_existing_products.forEach(function(product) {
                        existingProductListHTML += '<li>Product SKU: ' + product.sku +
                            ' - Already in other collection</li>';
                    });
                    existingProductListHTML += '</ul>';
                    $('#saveform_errList').append(
                        '<div class="alert alert-warning m-2" id="autoDismissAlert2"><strong>Products Already in Other Collections:</strong>' +
                        existingProductListHTML + '</div>').show();
                    setTimeout(function() {
                        $('#autoDismissAlert2').alert('close');
                    }, 5000);
                }

                if (res.message === 'products_added') {
                    $('input[type="checkbox"]:checked').prop('checked', false);
                    $('#collection_discount_id').val('');
                    $('#success_message').addClass('alert alert-success m-2');
                    $('#success_message').text('All products added successfully').show();
                    setTimeout(function() {
                        $('#success_message').removeClass('alert alert-success m-2').hide();
                    }, 3000);
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                } else if (!res.products_with_discounts && !res.already_existing_products) {
                    $('#saveform_errList').addClass('alert alert-danger m-2');
                    $('#saveform_errList').text('An unexpected error occurred.').show();
                }
            },
            error: function(err) {
                $('#saveform_errList').addClass('alert alert-danger m-2');
                $('#saveform_errList').text(
                    'An error occurred while processing your request. Please try again.').show();
            }
        });
    });






    function CollectionProductUpdateStatus(productId, status, elementObj) {
        var active_status = '';
        switch (status) {
            case 0:
                active_status = 'Inactive';
                break;
            case 1:
                active_status = 'Active';
                break;
        }
        $.ajax({
            url: '/admin/collections/' + productId + '/update-collectproduct-Status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showToast('success', 'status updated successfully.');
                console.log('Active Status: ', active_status);
                location.reload();
                $(elementObj).parent().parent().parent().parent().parent().siblings('.active-status').text(
                    active_status);
                $(elementObj).closest('.status-cell').text(active_status);

            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message :
                    'Error occurred.';
                showToast('error', errorMessage);
            }
        });
    }
    $(document).ready(function() {
        $("#categorySelect").on("change", function() {
            var selectedCategory = $(this).val();
            $("tr[data-category]").hide();

            if (selectedCategory === "") {
                $("tr[data-category]").show();
            } else {
                $("tr[data-category='" + selectedCategory + "']").show();
            }
        });
    });
</script>

<script>
    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: '/destroy-product/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Product deleted successfully.');
                    location.reload(); // Refresh the page after deletion
                },
                error: function(xhr, status, error) {
                    alert('Error occurred while deleting the product: ' + xhr.responseText);
                }
            });
        }
    }
</script>
