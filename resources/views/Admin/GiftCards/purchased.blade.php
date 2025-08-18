@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\DiscountClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
    <div class="container-fluid">
        <div class="page-inner">
            <!--================================-->
            <!-- Breadcrumb Start -->
            <!--================================-->
            <div class="card shadow-sm mb-4">

            <div class="pageheader pd-t-25 pd-b-35">
                <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="clearfix">
                        <div class="pd-t-5 pd-b-5">
                            <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">{{ __('Gift Cards List') }}</h1>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('Admin.Dashboard') }}" class="link-primary text-decoration-none">
                                        <i class="fa-solid fa-house me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/admin/gift-cards') }}"
                                        class="link-primary text-decoration-none">
                                        Gift Cards
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Purchased Cards List
                                </li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ url('/') }}/admin/gift-cards/create" class="btn btn-success" style="width:160px;">
                            <i class="fa-solid fa-circle-plus me-1"></i> Create Gift Card
                        </a>
                        <a href="{{ url('/') }}/admin/gift-cards" class="btn btn-primary" style="width:154px;">
                            <i class="fa-solid fa-list-ul me-1"></i> Gift Card List
                        </a>
                        <a href="{{ url('/') }}/admin/gift-cards/expired/list" class="btn btn-warning" style="width:154px;">
                            <i class="fa-solid fa-calendar-xmark me-1"></i> Expired List
                        </a> 
                        
                        <a href="{{ url('/') }}/admin/gift-cards/deleted/list" class="btn btn-danger" style="width:154px;">
                            <i class="fa-solid fa-trash-can me-1"></i> Trash List
                        </a>
                    </div>
                </div>
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
                                <h6 class="card-header-title tx-13 mb-0">{{ __('Purchased Cards List') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_Table" class="table table-hover table-borderless">
                                <thead>
                                    <tr>
                                        <th width="25px">#</th>
                                        <th>Card Title</th>
                                        <th>User Name</th>
                                        <th>User Email</th>
                                        <th>User Phone</th>
                                        <th>Purchasing Date</th>
                                        <th>Expiry Date</th>
                                        <th>Used</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($gift_cards as $gift_card)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                {{ $gift_card->card->title ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->customer_name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->customer_email ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->customer_phone ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->purchasing_date ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $gift_card->expiry_date ?? 'N/A' }}
                                            </td>
                                            <td>{{ $gift_card->used_times }} Times</td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th width="25px">#</th>
                                        <th>Card Title</th>
                                        <th>User Name</th>
                                        <th>User Email</th>
                                        <th>User Phone</th>
                                        <th>Purchasing Date</th>
                                        <th>Expiry Date</th>
                                        <th>Used</th>
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

    </div>

</div>


<script>
    function GiftCardUpdateStatus(cardId, status, elementObj) {
        $.ajax({
            url: '/admin/gift-cards/' + cardId + '/update-status',
            type: 'PUT',
            data: {
                status: status,
                id: cardId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showToast('success', 'status updated successfully.');
                $(elementObj).parent().parent().parent().parent().parent().siblings('.active-status').text(
                    response.data);
                if (response.data == 'deleted') {
                    window.location = '{{ route('Admin.gift_card.deleted') }}';
                } else if (response.data == 'expired') {
                    window.location = '{{ route('Admin.gift_card.expired') }}';
                } else if (response.data == 'active') {
                    window.location = '{{ url('/admin/gift-cards') }}';
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
