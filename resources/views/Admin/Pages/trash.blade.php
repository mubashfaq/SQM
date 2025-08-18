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
</style>
<!-- Page Inner Start -->
<!--================================-->
<div class="page-inner">
    <!--================================-->
    <!-- Breadcrumb Start -->
    <!--================================-->
    <div class="pageheader pd-t-25 pd-b-35">
        <div class="d-flex justify-content-between">
            <div class="clearfix">
                <div class="pd-t-5 pd-b-5">
                    <h1 class="pd-0 mg-0 tx-20 tx-dark">{{ __('Trash Pages List') }}</h1>
                </div>
                <div class="breadcrumb pd-0 mg-0">
                    <a class="breadcrumb-item" href="{{ url('/') }}/admin">Dashboard</a>
                    <span class="breadcrumb-item active">Trash Pages List</span>
                </div>
            </div>
             @if (Session::has('permissions') && (in_array('Creae a Page', Session::get('permissions'))  ||in_array('Page Trash List', Session::get('permissions'))))
            <div class="d-flex align-items-center">
                @if (Session::has('permissions') && in_array('Creae a Page', Session::get('permissions')))
                <button type="button" onclick="window.location = '{{ url('/') }}/admin/pages/create'"
                    class="btn btn-default mb-2 mb-md-0 d-none d-lg-block pd-t-6-force pd-b-5-force">
                    <i data-feather="layout" class="wd-16 mr-2"></i>Create Page</button>
               @endif
               @if (Session::has('permissions') && in_array('Page Trash List', Session::get('permissions')))
                <button type="button" onclick="window.location = '{{ url('/') }}/admin/pages/list/trash'"
                    class="btn btn-default mb-2 mb-md-0 d-none d-lg-block pd-t-6-force pd-b-5-force">
                    <i data-feather="layout" class="wd-16 mr-2"></i>Trash List</button>
           @endif
            </div>
        @endif
        </div>
    </div>
    <!--/ Breadcrumb End -->
    <!--================================-->
    <!--================================-->
    <div class="col-md-12 col-lg-12 px-0">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}</a>
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
                        <h6 class="card-header-title tx-13 mb-0">{{ __('Trash Pages List') }}</h6>
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
                                <th>Status</th>
                                @if (Session::has('permissions') && (in_array('Edit Page', Session::get('permissions')) ||in_array('Delete Page', Session::get('permissions'))  ||in_array('Page Status', Session::get('permissions'))))<th width="100px">Actions</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($pages as $page)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <div class="product-detail-cell">
                                            <a class="product-title" target="_blank">{{ $page->title }}</a>
                                        </div>
                                    </td>
                                    <td>{{ $page->permalink }}</td>
                                    <td>{{ $page->hero_heading }}</td>
                                    <td>{{ $page->seo_tags }}</td>
                                    <td>{{ $page->seo_title }}</td>
                                    <td class="active-status">{{ ProductClass::check_active($page->active) }}</td>
                                    @if (Session::has('permissions') && (in_array('Edit Page', Session::get('permissions')) ||in_array('Delete Page', Session::get('permissions'))  ||in_array('Page Status', Session::get('permissions'))))
                                    <td>
                                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                                @if (Session::has('permissions') && in_array('Edit Page', Session::get('permissions')))
                                                <button type="button" class="btn btn-warning"
                                                    onclick="window.location='{{ route('pages.edit', $page->id) }}'">Edit</button>
                                            @endif
                                            @if (Session::has('permissions') && in_array('Delete Page', Session::get('permissions')))
                                                <button class="btn btn-danger" type="submit"
                                                    onclick="return confirm('Are you sure? Page will permanantly deleted, suggestion is to make Page status Draft or Private!')">Delete</button>
                                            @endif
                                            @if (Session::has('permissions') && in_array('Page Status', Session::get('permissions')))
                                                <div class="btn-group" role="group">
                                                    <button id="btnGroupDrop1" type="button"
                                                        class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Change Status
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        <a class="dropdown-item"
                                                            onclick="PageUpdateStatus({{ $page->id }}, 0, this)">Draft</a>
                                                        <a class="dropdown-item"
                                                            onclick="PageUpdateStatus({{ $page->id }}, 1, this)">Public</a>
                                                        <a class="dropdown-item"
                                                            onclick="PageUpdateStatus({{ $page->id }}, 2, this)">Private</a>
                                                        <a class="dropdown-item"
                                                            onclick="PageUpdateStatus({{ $page->id }}, 3, this)">Trash</a>
                                                    </div>
                                                </div>
                                            @endif
                                            </div>
                                        </form>
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
                                <th>Status</th>
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

@include('Admin.Includes.footer')
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
</script>
