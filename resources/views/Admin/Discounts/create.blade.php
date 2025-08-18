@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<div class="page-content">
    <div class="card shadow-sm mb-4">

        <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h4 class="fs-4 mb-1">{{ __('Create Discount') }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center ">
                        <li class="breadcrumb-item">
                            <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                <i class="fa-solid fa-house me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('discounts.index') }}" class="link-primary text-decoration-none">
                                Discount
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Create
                        </li>
                    </ol>
                </nav>
            </div>

            @if (Session::has('permissions') &&
                    (in_array('Discount List', Session::get('permissions')) ||
                        in_array('Discount Trash List', Session::get('permissions'))))

                <div class="d-flex gap-2 align-items-center ">
                    <div class="d-flex align-items-center gap-2">
                        @if (Session::has('permissions') && in_array('Discount List', Session::get('permissions')))
                            <a href="{{ url('/') }}/admin/discounts" class="btn btn-primary">
                                <i class="fa-solid fa-list-ul me-1"></i> Discount List
                            </a>
                        @endif
                        @if (Session::has('permissions') && in_array('Discount Trash List', Session::get('permissions')))
                            <a href="{{ url('/') }}/admin/discounts/list/trash" class="btn btn-danger">
                                <i class="fa-solid fa-trash-can me-1"></i> Trash List
                            </a>
                        @endif
                    </div>

                </div>
            @endif
        </div>
    </div>
    <div class="col-md-12 col-lg-12 px-0">

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
        <form method="POST" action="{{ route('discounts.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
            <div class="row">
                <div class="col-md-9">
                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">{{ __('Discount Details (Compulsory)') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-control-label active">Title: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="title" placeholder="Discount Title"
                                    value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label active">Description: <span
                                        class="tx-danger"></span></label>
                                <textarea class="form-control" name="description" placeholder="Discount Description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">Value</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input class="" type="radio" value = "0" name="discount_type" id="percentage"
                                    checked>
                                <label class="form-check-label" for="percentage">Percentage</label>
                            </div>
                            <div class="form-group mb-3">
                                <input class="" type="radio" value = "1" name="discount_type" id="fixed">
                                <label class="form-check-label" for="fixed"> Fixed Amount</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control " type="number" name="discount_value"
                                    Placeholder="Discount value" required>
                            </div>
                        </div>
                    </div>
                    <!-- test-->
                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">Featured Badge Media (required)</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-control-label">Select Badge Color: </label>
                                <input class="form-control" name="badge_color" type="color" value="#ff0000">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-control-label active">Badge Text: <span
                                class="tx-danger">*</span></label>
                                <input class="form-control tagtext" id="tags_text" placeholder="Badge Text" name="badge_text" type="text"
                                    placeholder="">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Multiple Badge Image: </label>
                                <input class="form-control" name="badge_image[]" accept="image/*" type="file"
                                    multiple id="featuredimageInput">
                                <div class="text-secondary"><small>Preferred Dimension: (1920px) x (700px)</small>
                                </div>
                            </div>
                            <div id="featuredimagePreview"></div>
                        </div>
                    </div>
                    <!-- test -->
                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">Activate Date</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Start Date: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control form-control-lg" type="date" name="from_date"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">End Date: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control form-control-lg" type="date" name="end_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="card mg-b-30">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-header-title tx-13 mb-0">{{ __('More Details (Required)') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-control-label active"><i class=""></i>Active Status: </label>
                                <select class="form-select" name="active">
                                    <option value="0" @if (old('active') == 0) selected @endif>Draft
                                    </option>
                                    <option value="1" @if (old('active') == 1) selected @endif>Public
                                    </option>
                                    <option value="2" @if (old('active') == 2) selected @endif>Private
                                    </option>
                                    <option value="3" @if (old('active') == 3) selected @endif>Trash
                                    </option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-control-label active"><i class=""></i> Limited: </label>
                                <select class="form-select" name="limited">
                                    <option value="1" @if (old('limited') == 1) selected @endif>True
                                    </option>
                                    <option value="0" @if (old('limited') == 0) selected @endif>False
                                    </option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-control-label active"><i class=""></i>Discount Type: </label>
                                <select class="form-select" name="discount_for">
                                    <option value="1" @if (old('discount_for') == 1) selected @endif>Product
                                        Discount</option>
                                    <!-- <option value="2" @if (old('discount_for') == 2) selected @endif>
                                        Collection Discount</option> -->
                                </select>

                            </div>
                            <input class="btn btn-success float-right" type="submit" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var imageInput = document.getElementById('featuredimageInput');
    var imagePreview = document.getElementById('featuredimagePreview');
    imageInput.addEventListener('change', function(event) {
        var selectedFiles = event.target.files;

        imagePreview.innerHTML = '';

        for (var i = 0; i < selectedFiles.length; i++) {
            var selectedFile = selectedFiles[i];
            var reader = new FileReader();

            reader.onload = function(event) {
                var imageElement = document.createElement('img');
                imageElement.src = event.target.result;
                imageElement.style.width = '25%';
                imageElement.style.height = 'auto';
                imageElement.style.margin = '2px';
                imagePreview.appendChild(imageElement);
            }

            reader.readAsDataURL(selectedFile);
        }
    });



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

    // JavaScript code for the search function
    $(document).on('input', '#search_products', function() {
        var searchTerm = $(this).val().toLowerCase();
        var matchingRecords = 0;

        $('#product_list li').each(function() {
            var listItemText = $(this).find('span.serch_data').text().toLowerCase();

            if (searchTerm === '' || listItemText.includes(searchTerm)) {
                $(this).css('display', 'flex');
                matchingRecords++;
            } else {
                $(this).css('display', 'none');
            }
        });
        if (matchingRecords === 0 && searchTerm !== '') {
            $('.search-message').show();
        } else {
            $('.search-message').hide();
        }
    });
    // $('#tags_text').tagsinput({
    //     tagClass: 'badge badge-primary'
    // });

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Use Bootstrap's fade out
            alert.classList.remove('show');
            alert.classList.add('fade');
            // Completely remove from DOM after transition
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
