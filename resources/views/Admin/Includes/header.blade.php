<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-body-image="none" data-bs-theme="light" data-layout-width="fluid"
    data-layout-position="fixed" data-layout-style="default">
<script src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/content/location/location.js"
    id="eppiocemhmnlbhjplcgkofciiegomcon"></script>
<script src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/libs/extend-native-history-api.js"></script>
<script src="chrome-extension://eppiocemhmnlbhjplcgkofciiegomcon/libs/requests.js"></script>

<head>
    @include('Admin.Includes.links')
    <title>@yield('title', 'Sydney Quality Meats')</title>
    <style>
        .app-menu {
            height: auto;
            /* Full viewport height */
            /* overflow-y: auto;  Enable scrolling */
            display: flex;
            flex-direction: column;
        }

        .avatar-sm {
            width: 2rem !important;
            height: 2rem !important;
        }
    </style>
    <style>
        /* Added famous professional font families */
        .font-family-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .font-family-options {
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .font-family-code {
            font-family: 'Courier New', Courier, monospace;
        }

        .font-family-headings {
            font-family: 'Playfair Display', serif;
        }

        .font-family-modern {
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .font-family-comic {
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }

        .font-family-business {
            font-family: 'Roboto', sans-serif;
        }

        .font-family-displays {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.pagination .disabled').removeClass('disabled');
        });
    </script>
    <!-- Add this within the <head> section -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- Add this before the closing </body> tag -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

</head>

<body>



    @include('Admin.Includes.navbar') {{-- Include the header file --}}

    @include('Admin.Includes.sidebar') {{-- Include the sidebar file --}}

    @yield('content') {{-- Dynamic content from child views --}}



    @include('Admin.Includes.footer')

</body>

</html>
