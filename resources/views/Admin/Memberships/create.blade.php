@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp
<div class="page-content">
    <div class="card shadow-sm mb-4">

        <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h4 class="fs-4 mb-1">{{ __('Create Membership Plan') }}</h4>
                <nav aria-label="breadcrumb text-dark ">
                    <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center ">
                        <li class="breadcrumb-item">
                            <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                <i class="fa-solid fa-house me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/memberships') }}" class="link-primary text-decoration-none">
                                Membership Plans
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Create
                        </li>
                    </ol>
                </nav>
            </div>


            @if (Session::has('permissions') && in_array('Plans List', Session::get('permissions')))
                <div class="d-flex align-items-center">
                    <button type="button" onclick="window.location = '{{ url('/') }}/admin/memberships'"
                        class="btn btn-primary">
                        <i class="fa-solid fa-list-ul me-1"></i>Plans List</button>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12 col-lg-12 px-0">
        @if (session('success'))
            <div class="alert alert-success my-2">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger my-2">
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
                        <h6 class="card-header-title tx-13 mb-0">{{ __('Plan Details') }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('memberships.store') }}" class="row">
                    @csrf
                    <div class="col-md-12 form-group mb-3">
                        <label for="membershipName">Plan Name:</label>
                        <input type="text" class="form-control" id="membershipName" name="membershipName"
                            placeholder="Enter membership name" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="discountPercentage">Discount Percentage: <span>*</span></label>
                        <input type="number" class="form-control" id="discountPercentage" name="discountPercentage"
                            placeholder="Enter discount percentage" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="duration">Duration (in days):</label>
                        <select class="form-select form-select2-demo" id="duration" name="duration" required>
                            <option value="">Select Duration</option>
                            <option value="3">3 Days</option>
                            <option value="7">Weekly</option>
                            <option value="15">15 Days</option>
                            <option value="30">30 Days</option>
                            <option value="45">45 Days</option>
                            <option value="60">2 Months</option>
                            <option value="90">3 Months</option>
                            <option value="180">6 Months</option>
                            <option value="365">1 Year</option>
                            <option value="Un-Limited">Un-Limited</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="price">Price <span>*</span> </label>
                        <input type="number" class="form-control" id="price" name="price"
                            placeholder="Enter price" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="badge">Badge Text:</label>
                        <input type="text" class="form-control" id="badge" name="badge" placeholder="Premium"
                            required>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Description...." required></textarea>
                    </div>
                    <div class="col-md-3 col-12">
                        <button type="submit" class="btn btn-success">Create Plan</button>
                </form>
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
