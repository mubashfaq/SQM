@include('Admin.Includes.header')

<div class="page-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="p-3 mb-3">
                <h4 class="mb-1">{{ __('Subscription List') }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent p-0 m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <a class="link-primary text-decoration-none" href="{{ route('Admin.Dashboard') }}">
                                <i class="fa-solid fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Subscription List
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <!-- Success message display -->
        <div class="success-message">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

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
                <h5 class="mb-0">Subscription Table</h5>
                <button class="btn btn-danger" id="multi_delete" onclick="deleteSelected()">Delete Selected</button>
            </div>

            <div class="card-body">
                <div class="table-responsive table-borderless p-3">
                    <table id="data_Table" class="table table-borderless align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th><input type="checkbox" id="all_checked" onclick="toggleAllCheckboxes()"></th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox" data-id="{{ $subscription->id }}"></td>
                                    <td>{{ $subscription->email }}</td>
                                    <td>{{ $subscription->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm text-dark rounded shadow-sm"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #c2d6e7;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $subscription->id }}">
                                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- DELETE MODAL --}}
                                <div class="modal fade" id="deleteModal-{{ $subscription->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $subscription->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-md-5">
                                                <div class="text-center">
                                                    <div class="text-danger">
                                                        <i class="fa-solid fa-trash fs-1"></i>
                                                    </div>
                                                    <div class="mt-4">
                                                        <h4 class="mb-1">Are you sure?</h4>
                                                        <p class="text-muted mx-3 fs-16 mb-0">
                                                            Do you really want to delete the subscription for<br>
                                                            <strong>{{ $subscription->email }}</strong>?
                                                        </p>
                                                        <p class="text-muted mt-2 mb-0">
                                                            <small>This action cannot be undone.</small>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-3 justify-content-center mt-4 mb-2">
                                                    <button type="button" class="btn btn-light btn-hover w-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('Admin.Subscription.destroy', $subscription->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-hover w-sm">Yes, Delete It!</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle all checkboxes when the header checkbox is clicked
    function toggleAllCheckboxes() {
        const masterCheckbox = document.getElementById('all_checked');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = masterCheckbox.checked;
        });
    }

    // Function to handle bulk deletion
    function deleteSelected() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.getAttribute('data-id'));
        
        if (selectedIds.length === 0) {
            document.querySelector('.success-message').innerHTML = 
                '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                'Please select at least one record to delete.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
            return;
        }
        
        if (confirm('Are you sure? Selected subscriptions will be permanently deleted!')) {
            // Send AJAX request to delete selected subscriptions
            fetch("{{ route('Admin.subscription.bulk-delete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                document.querySelector('.success-message').innerHTML = 
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    data.message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';
                    
                // Reload the page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                document.querySelector('.success-message').innerHTML = 
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    'An error occurred while deleting subscriptions.' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';
            });
        }
    }

    // Auto-dismiss alerts after 5 seconds
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