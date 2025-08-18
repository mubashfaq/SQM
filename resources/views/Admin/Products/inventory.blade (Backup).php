@include('Admin.Includes.header')
@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    $logged_in_user = Auth::user();
@endphp
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
                    <h1 class="pd-0 mg-0 tx-20 tx-dark">{{ __('Manage Inventory') }}</h1>
                </div>
                <div class="breadcrumb pd-0 mg-0">
                    <a class="breadcrumb-item" href="{{ url('/') }}/admin"><i class="icon ion-ios-home-outline"></i>
                        Dashboard</a>
                    <span class="breadcrumb-item active">Manage Inventory</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <button type="button" onclick="window.location = '{{ url('/') }}/admin/products/create'"
                    class="btn btn-default mb-2 mb-md-0 d-none d-lg-block pd-t-6-force pd-b-5-force">
                    <i data-feather="package" class="wd-16 mr-2"></i>Create Product</button>
            </div>
        </div>
    </div>
    <!--/ Breadcrumb End -->
    <!--================================-->
    <!--================================-->
    <div class="col-md-12 col-lg-12 px-0">
        <div class="card mg-b-30">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-header-title tx-13 mb-0">{{ __('Manage Inventory') }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body pd-0">
                <div class="p-1 border-bottom d-flex">
                    <input type="text" class="form-control bg-light" id="searchFilter"
                        placeholder="Search Products...">
                    <button class="btn btn-primary" type="button" id="searchInventory">Search</button>
                </div>
                <form id="inventoryBlukUpdate">
                    @csrf
                    <table id="filteredResults" class="table hover responsive display nowrap"
                        style="width: 100% !important;">
                        <thead>
                            <tr>
                                <th scope="col" style="width:45%">Product</th>
                                <th scope="col">Sku</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Price</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            name="_stock[{{ $product->id }}]"
                                            value="{{ ProductClass::getOptionValue('_stock', $product->productMeta) }}">
                                    </td>
                                    <td><input type="number" class="form-control form-control-sm"
                                            name="_price[{{ $product->id }}]"
                                            value="{{ ProductClass::getOptionValue('_price', $product->productMeta) }}">
                                    </td>
                                    <td>
                                        <select class="form-control-sm" name="active[{{ $product->id }}]">
                                            <option value="0" @if ($product->active == 0) selected @endif>
                                                Draft
                                            </option>
                                            <option value="1" @if ($product->active == 1) selected @endif>
                                                Public
                                            </option>
                                            <option value="2" @if ($product->active == 2) selected @endif>
                                                Private
                                            </option>
                                            <option value="3" @if ($product->active == 3) selected @endif>
                                                Trash
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="w-100 d-flex justify-content-between p-2">
                        <div class="d-flex">
                            {!! $products->links() !!}
                        </div>
                        <div>
                            <button class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/  Order Active dataTable End -->
    <!--================================-->
</div>
<!--/ Page Inner End -->
@include('Admin.Includes.footer')

<script>
    $('#inventoryBlukUpdate').submit(function(event) {
        event.preventDefault();

        const stockData = {};
        const priceData = {};
        const activeData = {};

        $('input[name^="_stock"]').each(function() {
            const fieldName = $(this).attr('name'); // Example: _stock[1]
            const fieldId = fieldName.match(/\[(\d+)\]/)[1]; // Extract the ID from the field name
            const fieldValue = $(this).val();
            stockData[fieldId] = fieldValue;
        });

        $('input[name^="_price"]').each(function() {
            const fieldName = $(this).attr('name');
            const fieldId = fieldName.match(/\[(\d+)\]/)[1];
            const fieldValue = $(this).val();
            priceData[fieldId] = fieldValue;
        });

        $('select[name^="active"]').each(function() {
            const fieldName = $(this).attr('name');
            const fieldId = fieldName.match(/\[(\d+)\]/)[1];
            const fieldValue = $(this).val();
            activeData[fieldId] = fieldValue;
        });

        $.ajax({
            type: "POST",
            url: "{{ route('Admin.Product.Inventory.Update') }}",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data: {
                _token: '{{ csrf_token() }}',
                _stock: stockData,
                _price: priceData,
                active: activeData
            },
            success: function(response) {
                alert('Successfully Updated');
            },
            error: function(error) {
                console.log(error);
            }
        })
    })
</script>


<script>
    $(document).ready(function() {
        $("#searchInventory").click(function() {
            value = $('#searchFilter').val();
            fetchedData(value);
        });
    });

    function fetchedData(value) {
        $.ajax({
            type: "GET",
            url: "{{ url('/') }}/admin/products/inventory/search",
            data: {
                '_token': '{{ csrf_token() }}',
                'value': value
            },
            success: function(response) {
                $('#filteredResults').empty();
                $('#filteredResults').append(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }
</script>
