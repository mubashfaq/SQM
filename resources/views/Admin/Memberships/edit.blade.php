@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="card shadow-sm mb-4">

        <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div class="clearfix">
                <div class="pd-t-5 pd-b-5">
                    <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Update Membership Plan') }}</h1>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                <i class="fa-solid fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin/memberships') }}" class="link-primary text-decoration-none">
                                Membership Plans
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit
                        </li>
                    </ol>
                </nav>
            </div>

            @if (Session::has('permissions') &&
                    (in_array('Plans List', Session::get('permissions')) || in_array('Create a Plan', Session::get('permissions'))))
                <div class="d-flex gap-2 align-items-center mt-3 mt-md-0">
                    @if (in_array('Plans List', Session::get('permissions')))
                        <button type="button" onclick="window.location = '{{ url('/admin/memberships') }}'"
                            class="btn btn-primary">
                            <i class="fa-solid fa-list-ul me-1"></i> Plans List
                        </button>
                    @endif

                    @if (in_array('Create a Plan', Session::get('permissions')))
                        <button type="button" onclick="window.location = '{{ url('/admin/memberships/create') }}'"
                            class="btn btn-success">
                            <i class="fa fa-circle-plus me-1"></i> Create Plan
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12 col-lg-12 px-0">
        <div class="card mg-b-30">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header-title tx-13 mb-0">{{ __('Plan Details') }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('memberships.update', ['membership' => $membership->id]) }}"
                    class="row">
                    @csrf
                    @method('PATCH') <!-- Use the PATCH method for updates -->

                    <div class="col-md-12 form-group mb-3">
                        <label for="membershipName">Plan Name:</label>
                        <input type="text" class="form-control" id="membershipName" name="membershipName"
                            value="{{ old('membershipName', $membership->plan_name) }}"
                            placeholder="Enter membership name" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="discountPercentage">Discount Percentage: <span>*</span></label>
                        <input type="number" class="form-control" id="discountPercentage" name="discountPercentage"
                            value="{{ old('discountPercentage', $membership->discount_percentage) }}"
                            placeholder="Enter discount percentage" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="duration">Duration (in days):</label>
                        <select class="form-select form-select2-demo" id="duration" name="duration" required>
                            <option>Select Duration</option>
                            <option value="3"
                                {{ old('duration', $membership->duration) === '3' ? 'selected' : '' }}>3 Days</option>
                            <option value="7"
                                {{ old('duration', $membership->duration) === '7' ? 'selected' : '' }}>Weekly</option>
                            <option value="15"
                                {{ old('duration', $membership->duration) === '15' ? 'selected' : '' }}>15 Days
                            </option>
                            <option value="30"
                                {{ old('duration', $membership->duration) === '30' ? 'selected' : '' }}>30 Days
                            </option>
                            <option value="45"
                                {{ old('duration', $membership->duration) === '45' ? 'selected' : '' }}>45 Days
                            </option>
                            <option value="60"
                                {{ old('duration', $membership->duration) === '60' ? 'selected' : '' }}>2 Months
                            </option>
                            <option value="90"
                                {{ old('duration', $membership->duration) === '90' ? 'selected' : '' }}>3 Months
                            </option>
                            <option value="180"
                                {{ old('duration', $membership->duration) === '180' ? 'selected' : '' }}>6 Months
                            </option>
                            <option value="365"
                                {{ old('duration', $membership->duration) === '365' ? 'selected' : '' }}>1 Year
                            </option>
                            <option value="Un-Limited"
                                {{ old('duration', $membership->duration) === 'Un-Limited' ? 'selected' : '' }}>
                                Un-Limited</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="price">Price:</label>
                        <input type="number" class="form-control" id="price" name="price"
                            value="{{ old('price', $membership->price) }}" placeholder="Enter price" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="badge">Badge Text:</label>
                        <input type="text" class="form-control" id="badge" name="badge"
                            value="{{ old('badge', $membership->badge_text) }}" placeholder="Premium">
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Description....">{{ old('description', $membership->description) }}</textarea>
                    </div>
                    <div class="col-md-3 col-12">
                        <button type="submit" class="btn btn-success">Update Plan</button>
                    </div>
                </form>
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
