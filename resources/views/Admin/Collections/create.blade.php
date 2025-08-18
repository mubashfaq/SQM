@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<div class="page-content">
    <div class="container-fluid">
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
        <div class="card shadow-sm mb-4">

            <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <h4 class="fs-4 mb-1">{{ __('Collection Create') }}</h4>
                
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                    <i class="fa-solid fa-house me-1"></i> Dashboard
                                </a>
                            </li> 
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}/admin/collections" class="link-primary text-decoration-none">
                                     Collecition
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Collection Create
                            </li>
                        </ol>
                    </nav>
                </div>            
                @if (Session::has('permissions') &&
                        (in_array('Create a Collection', Session::get('permissions')) ||
                            in_array('Collection Trash List', Session::get('permissions'))))
                    <div class="d-flex gap-2">
                        @if (Session::has('permissions') && in_array('Create a Collection', Session::get('permissions')))
                            <a href="{{ url('/') }}/admin/collections" class="btn btn-primary">
                                <i class="fa-solid fa-list me-1"></i>Colleciton List
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
        
        {{-- <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h4 class="mb-1">{{ __('Collection Create') }}</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}/admin">Dashboard</a></li>
                    <li class="breadcrumb-item active">Collection Create</li>
                </ol>
            </div>
                <div class="d-flex gap-2">
                    @if (Session::has('permissions') && in_array('Collection List', Session::get('permissions')))
                        <a href="{{ url('/') }}/admin/collections" class="btn btn-primary">
                            <i class="bi bi-list me-1"></i> Collection List
                        </a>
                    @endif
                    @if (Session::has('permissions') && in_array('Collection Trash List', Session::get('permissions')))
                        <a href="{{ url('/') }}/admin/collections/list/trash" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Trash List
                        </a>
                    @endif
                </div>
        </div> --}}

        <form id="" method="POST" action="{{ route('Admin.collection.store') }}"
            enctype="multipart/form-data"
            autocomplete="off" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="created_by" value="{{ $logged_in_user->id }}">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <!-- Brand Details -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title rounded-circle bg-light text-primary fs-20">
                                        <i class="fa-solid fa-tags"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">Collection Details</h5>
                                    <p class="text-muted mb-0">Enter essential collection information below.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label" for="brand-title-input">Collection Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control addTitle" name="title"
                                    id="brand-title-input" placeholder="e.g. HITACHI" value="{{ old('title') }}"
                                    required>
                                <div class="invalid-feedback">Please enter a collection title.</div>
                            </div>

                            <!-- Permalink -->
                            <div class="form-group">
                                <label class="form-control-label active">Permalink: <span
                                        class="tx-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend custom-permalink">
                                        <span
                                            class="input-group-text input-group-text">{{ url('/') }}/collections/</span>
                                    </div>
                                    <input class="form-control form-control restrict-input-alphabets permalink"
                                        id="permalink" type="text" name="permalink" value="{{ old('permalink') }}"
                                        placeholder="hitachi" required>
                                </div>
                                <div class="text-danger permalink_error"></div>
                                <input type="hidden" class="permalink_status" name="permalink_status"
                                    value="{{ old('permalink_status') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">SEO Information <small class="text-muted">(Optional)</small>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">SEO Title</label>
                                <input type="text" name="_seo_title" class="form-control"
                                    value="{{ old('_seo_title') }}" placeholder="SEO Title...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SEO Tags</label>
                                <input type="text" name="_seo_tags" class="form-control"
                                    value="{{ old('_seo_tags') }}" placeholder="SEO Tags...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SEO Description</label>
                                <textarea name="_seo_description" class="form-control" rows="3" placeholder="SEO Description...">{{ old('_seo_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-xl-3 col-lg-4">
                    <!-- Status -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Status</h5>
                        </div>
                        <div class="card-body">
                            <label for="brand-status" class="form-label">Active Status</label>
                            <select name="active" class="form-select" id="colleciton-status">
                                <option value="0" @if (old('active') == 0) selected @endif>Draft</option>
                                <option value="1" @if (old('active') == 1) selected @endif>Public</option>
                                <option value="2" @if (old('active') == 2) selected @endif>Private</option>
                                <option value="3" @if (old('active') == 3) selected @endif>Trash</option>
                            </select>
                        </div>
                        
                        <div class="card-body">
                            <label for="brand-status" class="form-label">Select Discount (Optional):</label>
                            <select name="discount_id" class="form-select" id="brand-status">
                                <option value="" selected disabled>Select Discount On Collection</option>
                                @foreach ($discounts as $discount)
                                        <option value="{{ $discount->id }}"
                                            @if (old('discount_id') == $discount->id) selected @endif>{{ $discount->title }}
                                        </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-success w-100">Create Collection</button>
                    </div>
                </div>
            </div>
        </form>

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

        // Show/hide the "No matching records found" message based on the search results
        if (matchingRecords === 0 && searchTerm !== '') {
            $('.search-message').show();
        } else {
            $('.search-message').hide();
        }
    });
</script>

