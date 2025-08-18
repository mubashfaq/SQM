@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<style>
    /* Custom CSS styles */
    .customer-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        gap: 30px;
    }

    .customer-image {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 10px;
    }

    table.subscrible_model_table {
        table-layout: fixed;
        /* Ensures consistent column widths */
        width: 100%;
    }

    table.subscrible_model_table thead {
        width: calc(100% - 1em);
        /* Adjust for scrollbar width */
    }

    table.subscrible_model_table tbody {
        display: block;
        /* Allows scrolling inside tbody */
        height: 420px;
        /* Set the height for scrollable area */
        overflow-y: auto;
        /* Vertical scroll */
        overflow-x: hidden;
        /* Prevent horizontal scroll */
    }

    table.subscrible_model_table thead,
    table.subscrible_model_table tbody tr {
        display: table;
        /* Forces rows to behave like table rows */
        width: 100%;
        table-layout: fixed;
        /* Ensures columns stay aligned */
    }

    table.subscrible_model_table td {
        vertical-align: middle;
        /* Vertically center content */
        padding: 8px;
        /* Add padding for better readability */
    }

    table.subscrible_model_table td i {
        cursor: pointer;
        margin-left: 5px;
        /* Add a bit of space on the left */
        font-size: 1.2em;
        /* Adjust the icon size */
    }
</style>

<div class="page-content">
    <div class="page-inner">
        <!--================================-->
        <!-- Breadcrumb Start -->
        <!--================================-->
        <div class="card shadow-sm mb-4">

            <div class="pageheader pd-t-25 pd-b-35">
                <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div>
                        <h4 class="fs-4 mb-1">{{ __('Membership Plans') }}</h4>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center fw-semibold">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                
                                <li class="breadcrumb-item active" aria-current="page">
                                    Membership Plans List
                                </li>
                            </ol>
                        </nav>
                    </div>


                    @if (Session::has('permissions') && in_array('Create a Plan', Session::get('permissions')))
                        <div class="d-flex align-items-center">
                            <a href="{{ url('/') }}/admin/memberships/create" class="btn btn-success"
                                style="width:165px;">
                                <i class="fa-solid fa-circle-plus me-1"></i> Create Plan
                            </a>

                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--/ Breadcrumb End -->
        <div class="col-md-12 col-lg-12 px-0">
            <div class="card mg-b-30">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-header-title tx-13 mb-0">{{ __('Plans List') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success mt-2">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($membershipPlans->isEmpty())
                        <p>No membership plans available.</p>
                    @else
                        <div class="overflow-auto">
                            <table id="data_Table" class="table">
                                <thead>
                                    <tr>
                                        <th>Plan Name</th>
                                        <th>Discount Percentage</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Badge Text</th>
                                        <th>Description</th>
                                        <th>Default Signup</th>
                                        @if (Session::has('permissions') &&
                                                (in_array('Plan Edit', Session::get('permissions')) ||
                                                    in_array('Plan Delete', Session::get('permissions')) ||
                                                    in_array('Subscribers', Session::get('permissions'))))
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($membershipPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->plan_name }}</td>
                                            <td>{{ $plan->discount_percentage }}%</td>
                                            <td>{{ $plan->duration }}</td>
                                            <td>${{ $plan->price }}</td>
                                            <td>{{ $plan->badge_text }}</td>
                                            <td>{{ $plan->description }}</td>
                                            <td>
                                                <input type="checkbox" plan-id="{{ $plan->id }}"
                                                    onclick="changeDefaultSignup(this)" name="default_signup"
                                                    class="mx-2 checkbox plan_checkbox"
                                                    @if ($plan->signup) checked @endif>
                                            </td>
                                            @if (Session::has('permissions') &&
                                                    (in_array('Plan Edit', Session::get('permissions')) ||
                                                        in_array('Plan Delete', Session::get('permissions')) ||
                                                        in_array('Subscribers', Session::get('permissions'))))
                                                <td>
                                                    <div class="d-flex gap-1 ">
                                                        @if (Session::has('permissions') && in_array('Plan Edit', Session::get('permissions')))
                                                            <a href="{{ route('memberships.edit', ['membership' => $plan->id]) }}"
                                                                class="btn btn-sm btn-primary rounded-pill">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                            </a>
                                                        @endif

                                                        @if (Session::has('permissions') && in_array('Plan Delete', Session::get('permissions')))
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmModal{{ $plan->id }}">
                                                                <i class="fa-solid fa-trash me-1"></i> Delete
                                                            </button>
                                                        @endif

                                                        @if (Session::has('permissions') && in_array('Subscribers', Session::get('permissions')))
                                                            <button
                                                                class="btn btn-sm btn-info rounded-pill subscriber_model_btn"
                                                                plan-id="{{ $plan->id }}">
                                                                <i class="fa-solid fa-users me-1"></i> Subscribers
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade zoomIn" id="deleteConfirmModal{{ $plan->id }}"
                                            tabindex="-1" aria-labelledby="deleteConfirmModalLabel{{ $plan->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-bottom-0">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body p-md-5">
                                                        <div class="text-center">
                                                            <div class="text-danger">
                                                                <i class="fas fa-trash-alt display-4"></i>
                                                            </div>
                                                            <div class="mt-4">
                                                                <h4 class="mb-1 fw-semibold">Are you sure?</h4>
                                                                <p class="text-muted mx-3 fs-6 mb-0">
                                                                    Are you sure you want to delete
                                                                    <strong>{{ $plan->plan_name }}</strong>?<br>
                                                                    This action cannot be undone.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i> Cancel
                                                            </button>

                                                            <form
                                                                action="{{ route('memberships.destroy', ['membership' => $plan->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-check me-1"></i> Yes, Delete It!
                                                                </button>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Subscribers Modal -->
    <div class="modal fade" id="subscriber_model" tabindex="-1" aria-labelledby="subscriber_modelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <input type="hidden" id="plan_id_input" value="">
                    <h5 class="modal-title" id="subscriber_modelLabel">
                        <i class="fas fa-users me-2"></i>Plan Members List
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-0">
                        <div class="col-md-4 border-end">
                            <div class="form-group mb-3">
                                <label for="search_customer" class="form-label">Search Customer</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" oninput="searchCustomer(this)"
                                        placeholder="Search by name...">
                                </div>
                            </div>
                            <div class="border rounded shadow-sm" id="customer_list_SEction"
                                style="height: 400px; overflow-y: auto;">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center text-secondary">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p>No Customer Found!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 px-3">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle subscrible_model_table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Subscribed Date</th>
                                            <th>Expired Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="subscriber_section"
                                        style="height: 400px; overflow-y: auto; display: block;">
                                        <tr>
                                            <td colspan='5' class='text-center py-5'>
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mt-2 text-muted">Loading subscribers...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <span class="response_Section me-auto"></span>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-success" id="save_changes">
                        <i class="fas fa-save me-1"></i> Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>


</div>


<script>
    $(document).ready(function() {
        $('.subscriber_model_btn').click(function() {
            var plan_id = $(this).attr('plan-id');
            $('#plan_id_input').val(plan_id);
            $('.response_Section').html('');
            var subscriberList = document.getElementById('subscriber_section');
            subscriberList.innerHTML = `<tr><td colspan='5' class='text-center py-5'>
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading subscribers...</p>
        </td></tr>`;
            subscriber_list(plan_id);
            $('#subscriber_model').modal('show');
        });

        $('#save_changes').click(function() {
            var plan_id = $('#plan_id_input').val();
            var customerids = [];
            $('.customer_checkbox:checked').each(function() {
                customerids.push($(this).val());
            });
            if (customerids.length > 0) {
                subscriber_add(plan_id, customerids);
            } else {
                alert('There are no changes to save!');
            }
        });
    });

    function subscriber_add(plan_id, customerids) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                subscriber_list(plan_id);
                $('.response_Section').html('<div class="alert alert-success py-1 my-0">' + response.message +
                    '</div>');
            }
        };

        xhr.open('GET', '/admin/memberships/plans/add_subscriber?plan_id=' + plan_id + '&subscriber_ids=' + customerids,
            true);
        xhr.send();
    }

    function subscriber_list(plan_id) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.subscribers) {
                    displaySubscriber(response.subscribers);
                } else if (response.customers) {
                    displaySubscriber(response.customers);
                } else {
                    displaySubscriber([]);
                    console.error('API response missing both subscribers and customers arrays:', response);
                }
            }
        };

        xhr.open('GET', '/admin/memberships/plans/subscriber_list?plan_id=' + plan_id, true);
        xhr.send();
    }

    function searchCustomer(obj_) {
        var plan_id = $('#plan_id_input').val();
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                displayCustomers(response.customers, plan_id);
            }
        };

        xhr.open('GET', '/admin/memberships/customers/search?customer-search=' + obj_.value + '&plan_id=' + plan_id,
            true);
        xhr.send();
    }

    function displaySubscriber(subscribers) {
        var subscriberList = document.getElementById('subscriber_section');
        var current_plan_id = $('#plan_id_input').val();

        let tableContent = '';

        if (!subscribers || subscribers.length === 0) {
            tableContent =
                "<tr><td colspan='5' class='text-center py-4'><i class='fas fa-info-circle me-2'></i>This plan doesn't have any subscribers!</td></tr>";
            subscriberList.innerHTML = tableContent;
            return;
        }

        subscribers.forEach(function(subscriber) {
            var subscription = '<span class="badge bg-success text-light">Valid</span>';
            if (subscriber.expired == 1) {
                subscription = '<span class="badge bg-secondary text-light">Expired</span>';
            }

            tableContent += `<tr>
            <td>${subscriber.name}</td>
            <td>${subscriber.starting_date}</td>
            <td>${subscriber.ending_date}</td>
            <td>${subscription}</td>
            <td>
                <button type="button" class="btn btn-sm btn-link p-0" 
                        data-bs-toggle="modal" data-bs-target="#deleteSubscriberModal${subscriber.id}">
                    <i class="fas fa-trash text-danger"></i>
                </button>
            </td>
        </tr>`;
        });

        subscriberList.innerHTML = tableContent;

        // Create modals HTML
        let modalsHTML = '';
        subscribers.forEach(function(subscriber) {
            const plan_id = subscriber.plan_id || current_plan_id;

            modalsHTML += `
        <div class="modal fade zoomIn" id="deleteSubscriberModal${subscriber.id}" tabindex="-1" 
            aria-labelledby="deleteSubscriberModalLabel${subscriber.id}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-md-5">
                        <div class="text-center">
                            <div class="text-danger">
                                <i class="fas fa-trash-alt display-4"></i>
                            </div>
                            <div class="mt-4">
                                <h4 class="mb-1 fw-semibold">Are you sure?</h4>
                                <p class="text-muted mx-3 fs-6 mb-0">
                                    Are you sure you want to remove <strong>${subscriber.name}</strong> from this plan?<br>
                                    This action cannot be undone.
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            
                            <button type="button" class="btn btn-danger" onclick="confirmRemoveSubscriber(${subscriber.id}, ${plan_id})" data-bs-dismiss="modal">
                                <i class="fas fa-check me-1"></i> Yes, Remove It!
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        });

        if (!document.getElementById('subscriber-modals-container')) {
            const modalContainer = document.createElement('div');
            modalContainer.id = 'subscriber-modals-container';
            document.body.appendChild(modalContainer);
        }

        document.getElementById('subscriber-modals-container').innerHTML = modalsHTML;
    }

    function confirmRemoveSubscriber(subscriberId, planId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                subscriber_list(planId);
                $('.response_Section').html('<div class="alert alert-success py-1 my-0">' + response.message +
                    '</div>');
            }
        };

        xhr.open('GET', '/admin/memberships/plans/remove_subscriber?subscriber_id=' + subscriberId + '&plan_id=' +
            planId, true);
        xhr.send();
    }

    function displayCustomers(customers, plan_id) {
        var customerList = document.getElementById('customer_list_SEction');
        customerList.innerHTML = '';

        if (customers.length === 0) {
            customerList.innerHTML = '<div class="my-5 text-secondary text-center">No Customer Found!</div>';
            return;
        }

        var ul = '<ul class="m-0 p-0" style="list-style:none">';

        customers.forEach(function(customer) {
            // Determine what to display in the avatar area
            let avatarContent;

            if (customer.profile_image && customer.profile_image !== '' && customer.profile_image !== 'null') {
                avatarContent =
                    `<img src="${customer.profile_image}" alt="${customer.name}" class="customer-image">`;
            } else {
                const firstInitial = customer.name.charAt(0).toUpperCase();
                const randomColor = getAvatarColor(firstInitial);

                avatarContent = `
            <div class="customer-initial rounded-circle d-flex align-items-center justify-content-center" 
                style="background-color: ${randomColor}; width: 40px; height: 40px; color: white; font-weight: bold;">
                ${firstInitial}
            </div>`;
            }

            ul += `<li class="customer-item">
            ${avatarContent}
            <div>
                <div>${customer.name}</div>
                <small class="text-secondary">${customer.email}</small>
            </div>
            <div class="custom-control custom-checkbox ml-auto">
                <input type="checkbox" class="custom-control-input customer_checkbox" value="${customer.id}" id="customer${customer.id}" data-plan-id="${plan_id}">
                <label class="custom-control-label" for="customer${customer.id}"></label>
            </div>
        </li>`;
        });

        ul += '</ul>';
        customerList.innerHTML = ul;
    }

    // Helper function to generate consistent colors based on the initial
    function getAvatarColor(initial) {
        const colors = [
            '#3498db', // Blue
            '#2ecc71', // Green
            '#e74c3c', // Red
            '#9b59b6', // Purple
            '#f1c40f', // Yellow
            '#1abc9c', // Teal
            '#e67e22', // Orange
            '#34495e' // Dark Blue
        ];

        const charCode = initial.charCodeAt(0);
        const colorIndex = charCode % colors.length;

        return colors[colorIndex];
    }

    function changeDefaultSignup(btnObj) {
        var id = btnObj.getAttribute('plan-id');

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.message) {
                    $('.plan_checkbox').prop('checked', false);
                    btnObj.checked = true;
                    alert(response.message);
                }
            }
        };
        xhr.open('GET', '/admin/memberships/plans/change_default_signup/' + id, true);
        xhr.send();
    }
</script>
