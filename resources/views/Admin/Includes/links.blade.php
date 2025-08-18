<!-- HEAD SECTION -->
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="keyword" content="">
<meta name="author" content=""/>
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- App favicon -->
@php
    use App\General\GeneralSettingsClass;
    $favicon = GeneralSettingsClass::getRequiredValue('_fav_icon') ?? 'assets/images/favicon.png';
@endphp

<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset($favicon) }}" type="image/x-icon">
<!-- CSS Libraries - External CDN Resources -->
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<!-- Icons Libraries -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<!-- Local CSS Libraries -->
<link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/newstyle.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css">
<!-- Form specific CSS -->

<!-- FOOTER SCRIPTS SECTION -->
<!-- Essential JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- DataTables -->
<script>
    $(document).ready(function () {
        $('#data_Table').DataTable({
            responsive: true,
            autoWidth: false,
            columnDefs: [
                { orderable: false, targets: -1 } // Disable sorting on "Actions" column
            ]
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Charting Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.40.0/apexcharts.min.js"></script>

<!-- Framework Components -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>

<!-- Map Libraries -->
<script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

<!-- UI Components -->
<script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="https://themesbrand.com/assets/libs/list.js/list.min.js"></script>

<!-- Application Scripts -->
<script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


