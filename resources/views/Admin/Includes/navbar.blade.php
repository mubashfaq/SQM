@php
    $brands = \App\Models\Brand::all();
@endphp


<div class="top-tagbar">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">

            <div class="col-md-12 col-12 d-xxl-block">
                <div class="d-flex align-items-center justify-content-center gap-3 fs-14 fw-medium">
                    <div>
                        <i class="bi bi-globe align-middle me-2"></i>
                        <a href="{{url('/')}}" class="text-dark text-decoration-none"
                            target="_blank">Sydney Quality Meats</a>
                    </div>

                </div>
            </div>
            <div class="col-md-4 col-3">
            </div>
        </div>
    </div>
</div>
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-dark rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-sun align-middle fs-20"></i>
                    </button>
                    <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                        <button class="dropdown-item" data-mode="light"><i class="bi bi-sun align-middle me-2"></i> light mode</button>
                        <button class="dropdown-item" data-mode="dark"><i class="bi bi-moon align-middle me-2"></i> Dark</button>
                    </div>
                </div>
                <div class="header-item topbar-user topbar-head-dropdown" id="dropdown-container">
                    <div class="btn" id="page-header-user-dropdown">
                        <span class="d-flex align-items-center">
                            <!-- Check if user has a profile image -->
                            @if (auth()->user()->profile_image )
                                <img class="rounded-circle header-profile-user"
                                    src="{{asset( auth()->user()->profile_image)}}"
                                    alt="User Avatar">
                            @else
                                <!-- Show the first initial if no image exists -->
                                <span class="header-profile-user rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; background-color: #6c757d; color: #fff;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @endif

                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->user()->name }}</span>
                            </span>
                        </span>
                    </div>

                    <!-- Dropdown menu that will be toggled -->
                    <div class="dropdown-menu" id="dropdown-menu">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome {{ auth()->user()->name }}!</h6>
                        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="{{url('/admin/profile')}}">
                            <i class="fa fa-user-circle fs-5 me-2 text-primary"></i>  <!-- Font Awesome user icon -->
                            <span>My Profile</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="{{ route('Admin.Logout') }}">
                            <i class="fa fa-sign-out-alt fs-5 me-2 text-danger"></i>  <!-- Font Awesome logout icon -->
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
    .scrollable-wrapper {
        overflow-x: auto;
        white-space: nowrap;
    }

    .scrollable-wrapper::-webkit-scrollbar {
        height: 6px;
    }

    .scrollable-wrapper::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
    .dropdown-menu {
        display: none; /* Hide dropdown by default */
    }
    
    .dropdown-menu.show {
        display: block; /* Show dropdown when 'show' class is added */
    }
</style>
<script>
    // Apply theme on page load from localStorage
    const storedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-bs-theme', storedTheme);
    document.documentElement.setAttribute('data-topbar', storedTheme);
    document.documentElement.setAttribute('data-sidebar', storedTheme);

    // Event listener for theme change
    document.querySelectorAll('#light-dark-mode .dropdown-item').forEach(item => {
      item.addEventListener('click', function() {
        const mode = this.getAttribute('data-mode');
        
        // Save theme in localStorage
        localStorage.setItem('theme', mode);

        // Apply the theme
        if (mode === 'light') {
          document.documentElement.setAttribute('data-bs-theme', 'light');
          document.documentElement.setAttribute('data-topbar', 'light');
          document.documentElement.setAttribute('data-sidebar', 'light');
        } else if (mode === 'dark') {
          document.documentElement.setAttribute('data-bs-theme', 'dark');
          document.documentElement.setAttribute('data-topbar', 'dark');
          document.documentElement.setAttribute('data-sidebar', 'dark');
        }
      });
    });
  </script>
<script>
     const dropdownButton = document.querySelector("#page-header-user-dropdown");
    const dropdownMenu = document.querySelector("#dropdown-menu");

    // Add click event listener to toggle dropdown visibility
    dropdownButton.addEventListener("click", function(event) {
        event.stopPropagation(); // Prevent event from propagating to document
        dropdownMenu.classList.toggle("show"); // Toggle dropdown visibility
    });

    // Close the dropdown if clicking outside
    document.addEventListener("click", function(event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove("show"); // Hide dropdown if clicked outside
        }
    });
</script>
<script>
    document.getElementById("topnav-hamburger-icon").addEventListener("click", function() {
        const hamburger = this.querySelector(".hamburger-icon");

        if (window.innerWidth > 767) {
            const html = document.documentElement;
            const currentSize = html.getAttribute("data-sidebar-size");

            html.setAttribute("data-sidebar-size", currentSize === "lg" ? "sm" : "lg");
            hamburger.classList.toggle("open");
        } else {
            document.body.classList.toggle("vertical-sidebar-enable");

        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const hamburger = document.querySelector(".hamburger-icon");

        if (window.innerWidth <= 480) {
            if (hamburger) {
                hamburger.classList.add("open");
            }
            document.body.classList.add("twocolumn-panel");
        }
    });

    document.addEventListener("click", function(e) {
        if (window.innerWidth <= 767) {
            const sidebar = document.querySelector(".app-menu");
            const toggleButton = document.getElementById("topnav-hamburger-icon");
            if (
                !sidebar.contains(e.target) &&
                !toggleButton.contains(e.target) &&
                document.body.classList.contains("vertical-sidebar-enable")
            ) {
                document.body.classList.remove("vertical-sidebar-enable");
            }
        }
    });
    /* Hover effect for the dropdown */


</script>
