@include('Admin.Includes.header')

@php
    use Illuminate\Support\Facades\Auth;
    use App\General\ProductClass;
    $logged_in_user = Auth::user();
@endphp

<div class="page-content">
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
    <div class="container-fluid">
        <div class="card shadow-sm">

            <div class="  p-3 d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="clearfix">
                    <div class="pd-t-5 pd-b-5">
                        <h1 class="fs-4 pd-0 mg-0 tx-20 tx-dark">Manage Inventory</h1>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('Admin.Dashboard') }}" class="text-primary text-decoration-none">
                                    <i class="fa-solid fa-home me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="link-primary" href="{{ route('products.index') }}">
                                    Products
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Manage Inventory
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" onclick="window.location='{{ url('/admin/products/create') }}'"
                        class="btn btn-success">
                        <i class="fa-solid fa-circle-plus me-1"></i>Create Product</button>
                    <button type="button" id="exportCSV" class="btn btn-primary">
                        <i class="fa-solid fa-file-export me-1"></i>CSV Export
                    </button>
                    <!-- <button type="button" data-bs-toggle="modal" data-bs-target="#ImportModal" class="btn btn-warning">
                        <i class="fa-solid fa-file-import me-1"></i>CSV Import
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

        <!-- Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Manage Inventory Table</h5>
            </div>
            <form id="inventoryBlukUpdate">
                @csrf
                <div class="card-body  ">
                    <div class="table-responsive">
                        <table id="" class="table table-borderless align-middle">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Status</th> <!-- New column for Status -->
                                    <th>Actions</th> <!-- Actions for editing/deleting/changing status -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->title }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                <select class="form-control-sm" name="_stock_status[{{ $product->id }}]">
                    <option value="in_stock"
                        @if(ProductClass::getOptionValue('_stock_status', $product->productMeta) === 'in_stock') 
                            selected 
                        @endif
                    >
                        In Stock
                    </option>
                    <option value="out_stock"
                        @if(ProductClass::getOptionValue('_stock_status', $product->productMeta) === 'out_stock') 
                            selected 
                        @endif
                    >
                        Out of Stock
                    </option>
                </select>
            </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm " style="width:70px"
                                                name="_price[{{ $product->id }}]"
                                                value="{{ ProductClass::getOptionValue('_price', $product->productMeta) }}"
                                                step="0.0001">
                                        </td>
                                        <td class="active-status">
                                            <!-- Displaying the current product status as a badge -->
                                            @php
                                                $activeStatus = ProductClass::check_active($product->active);
                                                $badgeClass =
                                                    $activeStatus == 'Public'
                                                        ? 'bg-primary-subtle text-primary'
                                                        : ($activeStatus == 'Draft'
                                                            ? 'bg-warning-subtle text-warning'
                                                            : ($activeStatus == 'Private'
                                                                ? 'bg-info-subtle text-info'
                                                                : 'bg-secondary-subtle text-secondary'));
                                            @endphp
                                            <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">
                                                {{ $activeStatus }}
                                            </span>
                                        </td>
                                        <td>
                                            <!-- Three-dot dropdown for status change -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-dark rounded shadow-sm" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="background-color: #c2d6e7;">
                                                    &#8942; {{-- Three-dot icon --}}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if (in_array('Inventory Status', Session::get('permissions')))
                                                        <li>
                                                            <a class="dropdown-item {{ $product->active == 0 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $product->id }}, 0, this)">
                                                                <i class="fa-solid fa-file-pen me-2"></i> Draft
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $product->active == 1 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $product->id }}, 1, this)">
                                                                <i class="fa-solid fa-globe me-2"></i> Public
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item {{ $product->active == 2 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $product->id }}, 2, this)">
                                                                <i class="fa-solid fa-lock me-2"></i> Private
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning {{ $product->active == 3 ? 'active' : '' }} cursor-pointer"
                                                                onclick="ProductUpdateStatus({{ $product->id }}, 3, this)">
                                                                <i class="fa-solid fa-trash me-2"></i> Trash
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Status</th> <!-- New column for Status -->
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="w-100  p-2">
                        {{ $products->links('pagination::bootstrap-5') }} <!-- Laravel pagination links -->
                    </div>
                    <div class="w-100 d-flex justify-content-end p-2">
                        @if (Session::has('permissions') && in_array('Add Inventory', Session::get('permissions')))
                            <div>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        @endif
                    </div>
                </div>
            </form>


            <!-- Modern Bootstrap 5 Import Modal -->
            <div class="modal fade" id="ImportModal" tabindex="-1" aria-labelledby="ImportProductLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="ImportProductLabel">Import File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-4">
                            <form id="importproducts_form">
                                <h6 class="text-center mb-3 text-secondary fw-bold">SELECT CSV FILE</h6>

                                <div class="text-center mb-4">
                                    <label for="fileInput"
                                        class="drop-area d-flex flex-column align-items-center justify-content-center mx-auto p-4 rounded-3 border-2 border-dashed cursor-pointer"
                                        style="height: 180px; max-width: 320px; border-color: #dee2e6;">
                                        <div class="icon mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                fill="#6c757d" class="fa-solid fa-cloud-arrow-up"
                                                viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                                                <path
                                                    d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                                            </svg>
                                        </div>
                                        <div class="text fw-medium">Drag & drop your file or click to browse</div>
                                        <small class="text-muted mt-2">Supports CSV files only</small>
                                    </label>
                                    <input type="file" accept=".csv" id="fileInput" class="d-none">
                                    <div id="fileInfo" class="file-info mt-3 fw-medium text-secondary">No file
                                        selected</div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-success px-4 py-2" id="importCSVdata">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="fa-solid fa-file-import me-2"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707V11.5z" />
                                            <path
                                                d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                        </svg>
                                        Import File
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('Admin.Includes.footer')

<script>
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

    // Call the function to display file information
    displayFileInfo();


    // Add an event listener to the "Save changes" button
    document.getElementById('importCSVdata').addEventListener('click', function() {
        var inputFile = document.getElementById('fileInput').files[0];
        var formData = new FormData();
        formData.append('file', inputFile);

        // Send the AJAX request
        $.ajax({
            type: 'POST',
            url: '{{ route('Admin.Inventory.Import') }}',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('CSV data imported successfully');
                window.location.reload();

            },
            error: function(xhr, status, error) {
                // Handle errors here
                alert('Error: ' + error);
            }
        });
    });
</script>




<script>
    // Function to convert table data to CSV
    function convertToCSV(table) {
        var csv = [];
        var headers = [];
        var rows = table.querySelectorAll('tbody tr');
        var headerCols = table.querySelectorAll('thead th');

        // Extract headers, excluding the "Status" column
        for (var h = 0; h < headerCols.length; h++) {
            if (h !== 4) { // Exclude the "Status" column (index 4)
                headers.push(headerCols[h].innerText);
            }
        }

        csv.push(headers.join(','));

        for (var i = 0; i < rows.length; i++) {
            var row = [];
            var cols = rows[i].querySelectorAll('td');
            // Extract data from specific columns (Product, Sku, Stock, and Price)
            var product = cols[0].innerText;
            var sku = cols[1].innerText;
            var stock = cols[2].querySelector('input').value;
            var price = cols[3].querySelector('input').value;

            row.push(product, sku, stock, price);
            csv.push(row.join(','));
        }

        return csv.join('\n');
    }

    // Function to trigger the CSV download
    function downloadCSV() {
        var table = document.getElementById('filteredResults');
        var csv = convertToCSV(table);
        var blob = new Blob([csv], {
            type: 'text/csv'
        });
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(blob);
        a.download = 'table_data.csv';
        // a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Add click event listener to the button
    document.getElementById('exportCSV').addEventListener('click', downloadCSV);
</script>

<script>
    document.getElementById('exportCSV').addEventListener('click', function() {
        // Perform an AJAX call to get the CSV data
        fetch('{{ route('admin.inventory.export-csv') }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                }
            })
            .then(response => response.blob()) // Get the response as a Blob (binary large object)
            .then(blob => {
                // Create a link element to trigger the download
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(blob);
                a.href = url;
                a.download = 'all_products.csv';
                document.body.appendChild(a); // Append the link to the body
                a.click(); // Trigger the download
                window.URL.revokeObjectURL(url); // Clean up the URL object
                document.body.removeChild(a); // Remove the link element
            })
            .catch(error => console.error('Error:', error)); // Handle any errors
    });
</script>






<script>
    // Global object to track product status changes
    let productStatusData = {};
    // Initialize the status data with current values
    $(document).ready(function() {
        // Populate initial statuses based on the current active values
        @foreach ($products as $product)
            productStatusData[{{ $product->id }}] = {{ $product->active }};
        @endforeach
    });
    // Function to update status when dropdown item is clicked
    function ProductUpdateStatus(productId, statusValue, element) {
        // Update our tracking object
        productStatusData[productId] = statusValue;

        // Update the badge display
        const statusCell = $(element).closest('tr').find('.active-status');
        let badgeClass = '';
        let statusText = '';

        switch (statusValue) {
            case 0:
                badgeClass = 'bg-warning-subtle text-warning';
                statusText = 'Draft';
                break;
            case 1:
                badgeClass = 'bg-primary-subtle text-primary';
                statusText = 'Public';
                break;
            case 2:
                badgeClass = 'bg-info-subtle text-info';
                statusText = 'Private';
                break;
            case 3:
                badgeClass = 'bg-secondary-subtle text-secondary';
                statusText = 'Trash';
                break;
        }

        // Replace the badge with updated status
        statusCell.html(`<span class="badge ${badgeClass} px-3 py-2 rounded-pill">${statusText}</span>`);

        // Update active status in dropdown menu
        $(element).closest('ul').find('.dropdown-item').removeClass('active');
        $(element).addClass('active');
    }

    // Handle form submission with stock, price, and status data
    // Handle form submission with stock, price, and status data
$('#inventoryBlukUpdate').submit(function(event) {
    event.preventDefault();

    const priceData = {};
    const stockStatusData = {};
    
    // Collect stock status (In Stock / Out of Stock)
    $('select[name^="_stock_status"]').each(function() {
        const fieldName = $(this).attr('name'); // e.g., "_stock_status[1]"
        const fieldId = fieldName.match(/\[(\d+)\]/)[1];
        const fieldValue = $(this).val();
        stockStatusData[fieldId] = fieldValue;
    });

    // Collect price data
    $('input[name^="_price"]').each(function() {
        const fieldName = $(this).attr('name');
        const fieldId = fieldName.match(/\[(\d+)\]/)[1];
        const fieldValue = $(this).val();
        priceData[fieldId] = fieldValue;
    });

    // Include our tracked status data
    const activeData = productStatusData;

    // Show loading indicator or disable button to prevent double submissions
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
    submitBtn.prop('disabled', true);

    $.ajax({
        type: "POST",
        url: "{{ route('Admin.Product.Inventory.Update') }}",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        data: {
            _token: '{{ csrf_token() }}',
            _price: priceData,
            active: activeData,
            _stock_status: stockStatusData
        },
        success: function(response) {
            // Show success message
            const alertHtml = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Inventory updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('.page-content').prepend(alertHtml);
            
            // Restore button state
            submitBtn.html(originalBtnText);
            submitBtn.prop('disabled', false);
            
            // Scroll to top to see the success message
            window.scrollTo(0, 0);
        },
        error: function(error) {
            console.log(error);
            // Show error message
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    An error occurred while updating inventory. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('.page-content').prepend(alertHtml);
            
            // Restore button state
            submitBtn.html(originalBtnText);
            submitBtn.prop('disabled', false);
        }
    });
});
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
