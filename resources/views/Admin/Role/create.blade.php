@include('Admin.Includes.header')

<div class="page-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">

            <div class="pageheader pd-t-25 pd-b-35">
                <div class="  p-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Role') }}</h1>
                        </div>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-home me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Role
                                </li>
                            </ol>
                        </nav>
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

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Roles Table</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive table-borderless rounded  p-3  ">
                    <table id="data_Table" class="table table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roledatas as $roledata)
                                <tr>
                                    <td>{{ $roledata->id }}</td>
                                    <td>{{ $roledata->title }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                style="background-color: #c2d6e7;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#viewModal-{{ $roledata->id }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </button>
                                                </li>

                                                @if ($roledata->title === 'Customer')
                                                    <li>
                                                        <button class="dropdown-item text-danger" type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal-{{ $roledata->id }}">
                                                            <i class="fas fa-trash-alt me-2"></i>Delete
                                                        </button>
                                                    </li>
                                                @endif

                                                <li>
                                                    <a href="{{ url('/admin/permission/' . $roledata->id) }}"
                                                        class="dropdown-item">
                                                        <i class="fas fa-user-shield"></i> Permissions
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- DELETE-MODEL --}}
                                <div class="modal fade" id="deleteModal-{{ $roledata->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel-{{ $roledata->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-md-5">
                                                <div class="text-center">
                                                    <div class="text-danger">
                                                        <i class="fa-solid fa-trash fs-1"></i>
                                                    </div>
                                                    <div class="mt-4">
                                                        <h4 class="mb-1">Are you sure?</h4>
                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                            Role <strong> {{ $roledata->title }}</strong>?<br>
                                                            will permanently be deleted.
                                                        </p>
                                                        <p class="text-muted mt-2 mb-0">
                                                            <small>Consider archiving instead if you're unsure.</small>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                    <button type="button" class="btn btn-light btn-hover w-sm"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('role.destroy', $roledata->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-hover w-sm">Yes, Delete
                                                            It!</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="viewModal-{{ $roledata->id }}" tabindex="-1"
                                    aria-labelledby="viewModalLabel-{{ $roledata->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow">
                                            <!-- Header with gradient background -->
                                            <div class="modal-header border-bottom-0 bg-gradient-primary text-white p-4"
                                                style="background: linear-gradient(135deg, #367DE2 0%, #367DE2 100%);border-radius: 1rem 1rem 0 0;">
                                                <h5 class="modal-title text-white fw-bold"
                                                    id="viewModalLabel-{{ $roledata->id }}">
                                                    <i class="fas fa-edit me-2 text-white"></i>Edit Role
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <!-- Body with form -->
                                            <div class="modal-body p-4 p-md-5">
                                                <form action="{{ route('role.update', $roledata->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-4">
                                                        <label for="title" class="form-label fw-medium">Role
                                                            Title</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="fas fa-user-tag"></i>
                                                            </span>
                                                            <input type="text" class="form-control" id="title"
                                                                name="title" value="{{ $roledata->title }}"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <!-- Footer actions section -->
                                                    <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                                                        <button type="button" class="btn btn-light me-2"
                                                            data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-save me-1"></i> Update Role
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                        <tfoot class="">
                            <tr>
                                <th rowspan="1" colspan="1">#</th>
                                <th rowspan="1" colspan="1">Title</th>
                                <th rowspan="1" colspan="1">Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
