@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    $logged_in_user = Auth::user();
@endphp
<style>
    /* Style for the drop area */
    .drop-area {
        width: 200px;
        height: 200px;
        border: 2px dashed #ddd;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Style for the icon */
    .icon {
        font-size: 48px;
        color: #aaa;
    }

    /* Style for the text */
    .text {
        margin-top: 10px;
        font-size: 16px;
        color: #333;
    }

    /* Style for when hovering over the drop area */
    .drop-area:hover {
        border-color: #333;
    }

    /* Style for the selected file information */
    .file-info {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
        color: #333;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* #data_Table {
        width: 100%;
        min-width: 800px; 
    } */

</style>
<!-- Page Inner Start -->
<!--================================-->
<div class="page-content">
    <!--================================-->
    <!-- Breadcrumb Start -->
    <!--================================-->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="clearfix">
                    <div class="pd-t-5 pd-b-5">
                        <h4 class="pd-0 mg-0 tx-20 tx-dark">{{ __('Page List') }}</h4>
                    </div>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/admin') }}" class="link-primary text-decoration-none">
                                    <i class="fa-solid fa-house me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Page List
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex gap-2 mt-3 mt-md-0 index-order-btns">
                    @if (in_array('Create Product', Session::get('permissions')))
                       
                        <a href="{{ url('/admin/pages/create') }}" class="btn btn-success" style="">
                            <i class="fas fa-plus-circle me-1"></i> Create Page </a>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!--/ Breadcrumb End -->
    <!--================================-->
    <!--================================-->
    <div class="col-md-12 col-lg-12 px-0">
        @if (session('success'))
            <div class="alert alert-success" id="successAlert">
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
        <div class="card mg-b-30">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header-title tx-13 mb-0">{{ __('Pages List') }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body pd-0">
                <div class="table-responsive">
                    <table id="data_Table" class="table hover responsive display nowrap" style=" width: 100% !important;">
                        <thead>
                            <tr>
                                <th width="25px">#</th>
                                <th width="330px">Page Title</th>
                                <th>Permalink</th>
                                <th>Main Heading</th>
                                <th>Seo Tags</th>
                                <th>Seo Title</th>
                                <!-- <th>Status</th> -->
                                @if (Session::has('permissions') && (in_array('Edit Page', Session::get('permissions')) ||in_array('Delete Page', Session::get('permissions'))  ||in_array('Page Status', Session::get('permissions'))))<th width="100px">Actions</th>@endif
                            </tr>
                        </thead>
                       <tbody>
    @php $i = 1; @endphp
    @foreach ($pages as $page)
        <tr>
            <td>{{ $i }}</td>
            <td>
              
                    {{ $page->title }}
                
            </td>
            <td>{{ $page->permalink }}</td>
            <td>{{ $page->hero_heading }}</td>
            <td>{{ $page->seo_tags }}</td>
            <td>{{ $page->seo_title }}</td>
            @if (Session::has('permissions') && (in_array('Edit Page', Session::get('permissions')) ||in_array('Delete Page', Session::get('permissions'))))
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm text-dark rounded shadow-sm"
                        type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" style="background-color: #c2d6e7;">
                        &#8942;
                    </button>
                    <ul class="dropdown-menu">
                        @if (Session::has('permissions') && in_array('Edit Page', Session::get('permissions')))
                            <li>
                                <a class="dropdown-item" href="{{ route('pages.edit', $page->id) }}">
                                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit
                                </a>
                            </li>
                        @endif
                        @if (Session::has('permissions') && in_array('Delete Page', Session::get('permissions')))
                            <li>
                                <button class="dropdown-item text-danger delete-btn" type="button" 
                                    data-id="{{ $page->id }}" data-title="{{ $page->title }}">
                                    <i class="fa-solid fa-trash me-2"></i> Delete
                                </button>
                            </li>
                        @endif
                    </ul>
                </div>
            </td>
            @endif
        </tr>
        @php $i++; @endphp
    @endforeach
</tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Page Title</th>
                                <th>Permalink</th>
                                <th>Main Heading</th>
                                <th>Seo Tags</th>
                                <th>Seo Title</th>
                                <!-- <th>Status</th> -->
                            @if (Session::has('permissions') && (in_array('Edit Page', Session::get('permissions')) ||in_array('Delete Page', Session::get('permissions'))  ||in_array('Page Status', Session::get('permissions'))))<th width="100px">Actions</th>@endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/  Order Active dataTable End -->
    <!--================================-->
</div>
<!--/ Page Inner End -->

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the page "<span id="pageTitle"></span>"?</p>
        <div class="alert alert-warning">
          <i class="fa-solid fa-triangle-exclamation me-2"></i>Warning: This action cannot be undone. The page will be permanently deleted.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deletePageForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete Page</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    function PageUpdateStatus(pageId, status, elementObj) {
        var active_status = '';
        switch (status) {
            case 0:
                active_status = 'Draft';
                break;
            case 1:
                active_status = 'Public';
                break;
            case 2:
                active_status = 'Private';
                break;
            case 3:
                active_status = 'Trash';
                break;
        }

        $.ajax({
            url: '/admin/pages/' + pageId + '/update-status',
            type: 'PUT',
            data: {
                active: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showToast('success', 'Page status updated successfully.');
                $(elementObj).parent().parent().parent().parent().parent().siblings('.active-status').text(
                    active_status);
                if (status == 3) {
                    window.location = '{{ route('Admin.Pages.Trash') }}';
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message :
                    'Error occurred.';
                showToast('error', errorMessage);
            }
        });
    }

    function displayFileInfo() {
        const fileInput = document.getElementById("fileInput");
        const fileInfo = document.getElementById("fileInfo");

        fileInput.addEventListener("change", function() {
            const selectedFile = fileInput.files[0];

            if (selectedFile) {
                const fileName = selectedFile.name;
                const fileSize = Math.round(selectedFile.size / 1024); // Convert to KB

                fileInfo.innerHTML = `Selected File: ${fileName} (${fileSize} KB)`;
            } else {
                fileInfo.innerHTML = "";
            }
        });
    }

    // Handle delete button clicks
    $(document).on('click', '.delete-btn', function() {
        var pageId = $(this).data('id');
        var pageTitle = $(this).data('title');
        
        // Set the page title in the modal
        $('#pageTitle').text(pageTitle);
        
        // Set the form action URL
        $('#deletePageForm').attr('action', '{{ route("pages.destroy", "") }}/' + pageId);
        
        // Show the modal
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
    });

    $(document).ready(function() {
        // Auto-hide success alert after 5 seconds
        if ($("#successAlert").length > 0) {
            setTimeout(function() {
                $("#successAlert").fadeOut('slow');
            }, 5000); // 5000 milliseconds = 5 seconds
        }
        
        // Initialize Bootstrap 5 dropdowns if using Bootstrap 5
        if (typeof bootstrap !== 'undefined') {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        }
        
        // Alternative initialization for Bootstrap 4
        // This will ensure the dropdown works regardless of Bootstrap version
        $('.dropdown-toggle').on('click', function(e) {
            e.stopPropagation();
            var $this = $(this);
            var $parent = $this.parent();
            
            $('.dropdown-menu').not($parent.find('.dropdown-menu')).removeClass('show');
            $parent.find('.dropdown-menu').toggleClass('show');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    });
</script>
