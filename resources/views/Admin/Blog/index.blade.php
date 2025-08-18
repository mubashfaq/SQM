@include('Admin.Includes.header')

@php
    use Illuminate\Support\Facades\Auth;
    use App\General\BlogClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">

        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h4 class="mb-1">{{ __('Blog List') }}</h4>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Blog List
                                </li>
                            </ol>
                        </nav>
                    </div>

                    {{-- @if (Session::has('permissions') && (in_array('Blog Create', Session::get('permissions')) || in_array('Blog Trash List', Session::get('permissions')))) --}}
                    <div class="d-flex gap-2">
                        {{-- @if (in_array('Blog Create', Session::get('permissions'))) --}}
                        <a href="{{ url('/') }}/admin/blogs/create" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Create Blog
                        </a>
                        {{-- @endif --}}

                        {{-- @if (in_array('Blog Trash List', Session::get('permissions'))) --}}
                        <a href="{{ url('/') }}/admin/blogs/list/trash" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Trash List
                        </a>
                        {{-- @endif --}}
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>

        <!-- Alerts -->
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

        <!-- Advanced Filters Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>Advanced Filters
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div class="collapse {{ request()->hasAny(['status', 'category', 'brand', 'date_range', 'search']) ? 'show' : '' }}"
                id="filterCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('blogs.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter" name="status">
                                <option value="">All Status</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Draft</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Published
                                </option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Private
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="category_filter" class="form-label">Category</label>
                            <select class="form-select" id="category_filter" name="category">
                                <option value="">All Categories</option>
                                @foreach ($allCategories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="brand_filter" class="form-label">Brand</label>
                            <select class="form-select" id="brand_filter" name="brand">
                                <option value="">All Brands</option>
                                @foreach ($allBrands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="date_range" class="form-label">Date Range</label>
                            <select class="form-select" id="date_range" name="date_range">
                                <option value="">All Time</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today
                                </option>
                                <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This
                                    Week</option>
                                <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This
                                    Month</option>
                                <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This
                                    Year</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="search_filter" class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="search_filter" name="search"
                                    value="{{ request('search') }}" placeholder="Search by title, description...">
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-1"></i>Apply Filters
                                </button>
                                <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-height-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                    <i class="bi bi-journal-text"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Blogs</p>
                                <h4 class="mb-0" id="total_blogs">{{ $blogs->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-height-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                    <i class="bi bi-check-circle"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Published</p>
                                <h4 class="mb-0" id="published_blogs">{{ $blogs->where('active', 1)->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-height-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                    <i class="bi bi-pencil-square"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Draft</p>
                                <h4 class="mb-0" id="draft_blogs">{{ $blogs->where('active', 0)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-height-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                    <i class="bi bi-lock"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Private</p>
                                <h4 class="mb-0" id="private_blogs">{{ $blogs->where('active', 2)->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>Blogs Table
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="entries_per_page" style="width: auto;">
                        <option value="10">10 entries</option>
                        <option value="25">25 entries</option>
                        <option value="50">50 entries</option>
                        <option value="100">100 entries</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data_Table" class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="sort" data-sort="id">#</th>
                                <th class="sort" data-sort="title">Title</th>
                                <th class="sort" data-sort="categories">Categories</th>
                                <th class="sort" data-sort="brands">Brands</th>
                                <th class="sort" data-sort="brands">Child Category</th>
                                <th class="sort" data-sort="created_by">Created By</th>
                                <th class="sort" data-sort="status">Status</th>
                                <th class="sort" data-sort="created_at">Created Date</th>
                                @if (Session::has('permissions') &&
                                        (in_array('Blog Edit', Session::get('permissions')) ||
                                            in_array('Blog Delete', Session::get('permissions')) ||
                                            in_array('Change Status', Session::get('permissions'))))
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="blog_table_body">
                            @php $i = 1; @endphp
                            @foreach ($blogs as $blog)
                                <tr>
                                    <td><span class="fw-semibold">{{ $i++ }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($blog->cover_image)
                                                <img src="{{ url('/') . '/' . $blog->cover_image }}" alt="Cover"
                                                    class="rounded border me-3"
                                                    style="width: 60px; height: 45px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded border me-3 d-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 45px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($blog->title, 40) }}</h6>
                                                @if ($blog->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit(strip_tags($blog->description), 60) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($blog->categories->count() > 0)
                                            @foreach ($blog->categories->take(2) as $category)
                                                <span class="badge bg-primary-subtle text-primary me-1 mb-1">
                                                    {{ $category->title }}
                                                </span>
                                            @endforeach
                                            @if ($blog->categories->count() > 2)
                                                <span
                                                    class="badge bg-light text-muted">+{{ $blog->categories->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No category</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($blog->brands->count() > 0)
                                            @foreach ($blog->brands->take(2) as $brand)
                                                <span class="badge bg-info-subtle text-info me-1 mb-1">
                                                    {{ $brand->title }}
                                                </span>
                                            @endforeach
                                            @if ($blog->brands->count() > 2)
                                                <span
                                                    class="badge bg-light text-muted">+{{ $blog->brands->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No brand</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($blog->childCategories->count() > 0)
                                            @foreach ($blog->childCategories->take(2) as $category)
                                                <span class="badge bg-primary-subtle text-primary me-1 mb-1">
                                                    {{ $category->child_title }}
                                                </span>
                                            @endforeach
                                            @if ($blog->childCategories->count() > 2)
                                                <span
                                                    class="badge bg-light text-muted">+{{ $blog->childCategories->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No Child Categories</span>
                                        @endif
                                    </td>

                                  
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <div class="avatar-title rounded-circle bg-primary text-white">
                                                  
                                                    {{ strtoupper(substr($blog->creator->name ?? 'A', 0, 1)) }}
                                                </div>
                                            </div>
                                            <span>{{ $blog->creator->name ?? 'Admin' }}</span>
                                        </div>
                                    </td>
                                    <td class="active-status">
                                        @php
                                            $statusClass = [
                                                0 => 'bg-warning-subtle text-warning',
                                                1 => 'bg-success-subtle text-success',
                                                2 => 'bg-secondary-subtle text-secondary',
                                                3 => 'bg-danger-subtle text-danger',
                                            ];
                                            $statusText = [
                                                0 => 'Draft',
                                                1 => 'Published',
                                                2 => 'Private',
                                                3 => 'Trash',
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $statusClass[$blog->active] ?? 'bg-light text-muted' }}">
                                            {{ $statusText[$blog->active] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <span
                                                class="fw-medium">{{ \Carbon\Carbon::parse($blog->created_at)->timezone('Australia/Sydney')->format('M d, Y') }}</span>
                                            <br>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($blog->created_at)->timezone('Australia/Sydney')->format('h:i A') }}</small>
                                        </div>

                                    </td>

                                    @if (Session::has('permissions') &&
                                            (in_array('Blog Edit', Session::get('permissions')) ||
                                                in_array('Blog Delete', Session::get('permissions')) ||
                                                in_array('Change Status', Session::get('permissions'))))
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="background-color: #c2d6e7;">
                                                    &#8942;
                                                </button>
                                                <ul class="dropdown-menu">
                                                    {{-- @if (in_array('Blog Edit', Session::get('permissions'))) --}}
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('blogs.edit', ['blog' => $blog->id]) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit</a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item" href="{{url('/blog/')  }}/{{ $blog->permalink }}" target="_blank">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    {{-- @endif --}}


                                                    {{-- @if (in_array('Blog Delete', Session::get('permissions'))) --}}
                                                    <li><button class="dropdown-item text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteConfirmModal{{ $blog->id }}">
                                                            <i class="bi bi-trash me-2"></i>Delete</button>
                                                    </li>
                                                    {{-- @endif --}}


                                                    @if (in_array('Change Status', Session::get('permissions')))
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $blog->active == 0 ? 'active' : '' }} cursor-pointer"
                                                                onclick="BlogUpdateStatus({{ $blog->id }}, 0, this)">
                                                                <i class="bi bi-pencil me-2"></i> Draft
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $blog->active == 1 ? 'active' : '' }} cursor-pointer"
                                                                onclick="BlogUpdateStatus({{ $blog->id }}, 1, this)">
                                                                <i class="bi bi-globe me-2"></i> Published
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $blog->active == 2 ? 'active' : '' }} cursor-pointer"
                                                                onclick="BlogUpdateStatus({{ $blog->id }}, 2, this)">
                                                                <i class="bi bi-lock me-2"></i> Private
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning {{ $blog->active == 3 ? 'active' : '' }} cursor-pointer"
                                                                onclick="BlogUpdateStatus({{ $blog->id }}, 3, this)">
                                                                <i class="bi bi-trash me-2"></i> Trash
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    @endif
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade zoomIn" id="deleteConfirmModal{{ $blog->id }}"
                                    tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $blog->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-md-5">
                                                <div class="text-center">
                                                    <div class="text-danger">
                                                        <i class="bi bi-trash display-4"></i>
                                                    </div>
                                                    <div class="mt-4 fs-15">
                                                        <h4 class="mb-1">Are you sure?</h4>
                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                            Are you sure you want to delete
                                                            <strong>{{ $blog->title }}</strong>?<br>
                                                            This action cannot be undone.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                    <button type="button" class="btn w-sm btn-light btn-hover"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('blogs.destroy', ['blog' => $blog->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn w-sm btn-danger btn-hover">Yes, Delete
                                                            It!</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- No results message -->
                <div id="no_results" class="text-center py-5" style="display: none;">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-search display-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No blogs found</h5>
                        <p class="text-muted">Try adjusting your search criteria</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Blog Status Update Function
    function BlogUpdateStatus(blogId, status, elementObj) {
        const statusLabels = ['Draft', 'Published', 'Private', 'Trash'];
        const newStatusText = statusLabels[status] || 'Unknown';

        const statusClasses = {
            0: 'bg-warning-subtle text-warning',
            1: 'bg-success-subtle text-success',
            2: 'bg-secondary-subtle text-secondary',
            3: 'bg-danger-subtle text-danger'
        };

        $.ajax({
            url: '/admin/blogs/' + blogId + '/update-status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update status badge in the table
                const $statusCell = $(elementObj).closest('tr').find('.active-status .badge');
                $statusCell.removeClass().addClass('badge ' + statusClasses[status]).text(newStatusText);

                // Show success alert
                const alertHTML = `
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    Status updated to <strong>${newStatusText}</strong> successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

                $('.page-content .container-fluid').prepend(alertHTML);

                // Update stats cards
                updateStatsCards();

                // Auto-hide alert after 5 seconds
                setTimeout(() => {
                    $('.alert-success').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);

                // If moved to Trash, redirect
                if (status === 3) {
                    window.location = '{{ route('Admin.Blog.Trash') }}';
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Something went wrong.';
                const alertHTML = `
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;

                $('.page-content .container-fluid').prepend(alertHTML);

                setTimeout(() => {
                    $('.alert-danger').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    }

    // Update stats cards after status change
    function updateStatsCards() {
        const rows = $('#blog_table_body tr:visible');
        let total = 0,
            published = 0,
            draft = 0,
            private = 0;

        rows.each(function() {
            const statusText = $(this).find('.active-status .badge').text().trim();
            total++;
            if (statusText === 'Published') published++;
            else if (statusText === 'Draft') draft++;
            else if (statusText === 'Private') private++;
        });

        $('#total_blogs').text(total);
        $('#published_blogs').text(published);
        $('#draft_blogs').text(draft);
        $('#private_blogs').text(private);
    }

    // Filter Functions


    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Initialize DataTable-like functionality
    $(document).ready(function() {
        // Search functionality
        $('#search_filter').on('keyup', function() {
            if ($(this).val() === '') {
                applyFilters();
            }
        });

        // Real-time filter application
        $('#status_filter, #category_filter, #brand_filter, #date_range').on('change', function() {
            applyFilters();
        });
    });
</script>

<style>
    .avatar-xs {
        height: 2rem;
        width: 2rem;
    }

    .avatar-sm {
        height: 3rem;
        width: 3rem;
    }

    .avatar-title {
        align-items: center;
        color: #fff;
        display: flex;
        font-weight: 500;
        height: 100%;
        justify-content: center;
        width: 100%;
    }

    .card-height-100 {
        height: calc(100% - 0px);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .table th.sort {
        cursor: pointer;
        user-select: none;
    }

    .table th.sort:hover {
        background-color: var(--bs-gray-100);
    }

    .badge {
        font-size: 0.75em;
    }

    .dropdown-item.active {
        /* background-color: var(--bs-primary); */
        color: black;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem 0.5rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
