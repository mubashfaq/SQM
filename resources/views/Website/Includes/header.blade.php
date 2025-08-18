<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">

    <title>{{ $meta_Details['title'] }}</title>
    <meta name="description" content="{{ $meta_Details['description'] }}">
    <meta name="keywords" content="{{ $meta_Details['keywords'] }}">
    <meta name="author" content="{{ $meta_Details['author'] }}">

    <!-- Only include CSRF token on pages that need it -->
    @if (isset($needsCSRF) && $needsCSRF)
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="{{ asset('WebsiteAssets/assets/img/favlogo.png') }}" type="image/x-icon">

    <!-- Optimized resource hints with proper crossorigin -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Preload only truly critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style"
        fetchpriority="high">
    <link rel="preload" href="{{ asset('WebsiteAssets/assets/css/styles.css') }}" as="style" fetchpriority="high">

    <!-- Conditional font preloading -->
    @if (!isset($skipFonts) || !$skipFonts)
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
            as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
        </noscript>
    @endif

    <!-- Critical above-the-fold styles -->
    <style>
        /* Performance-first critical styles */
        :root {
            --primary-color: #A42125;
            --primary-hover: #8b1d20;
            --loader-bg: rgba(0, 0, 0, 0.9);
            --container-max: 1140px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* Optimized loader with GPU acceleration */
        .loader-container {
            position: fixed;
            inset: 0;
            background: var(--loader-bg);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            will-change: opacity;
            transition: opacity 0.3s ease;
        }

        .loader-image img {
            width: 80px;
            height: auto;
            will-change: opacity;
        }

        @keyframes loaderPulse {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 1;
            }
        }

        .loader-image img.active {
            animation: loaderPulse 1.5s infinite ease-in-out;
        }

        /* Optimized back-to-top with GPU acceleration */
        .back-to-top {
            position: fixed;
            bottom: 70px;
            right: 0;
            background: transparent;
            color: var(--primary-color);
            border: none;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            display: none;
            z-index: 1000;
            transform: rotate(270deg);
            will-change: color;
            transition: color 0.2s ease;
        }

        .back-to-top:hover {
            color: var(--primary-hover);
        }

        @media (max-width: 520px) {
            .back-to-top {
                right: -28px;
            }
        }

        /* Critical layout system - minimal Bootstrap equivalent */
        .container {
            width: 100%;
            max-width: var(--container-max);
            margin: 0 auto;
            padding: 0 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col {
            flex: 1;
            padding: 0 15px;
        }

        /* Essential utilities */
        .d-none {
            display: none !important;
        }

        .d-flex {
            display: flex !important;
        }

        .justify-content-center {
            justify-content: center !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .text-center {
            text-align: center !important;
        }

        .w-100 {
            width: 100% !important;
        }

        .h-100 {
            height: 100% !important;
        }

        /* Layout shift prevention */
        .nav-container {
            min-height: 60px;
            display: flex;
            align-items: center;
        }

        .hero-section {
            min-height: 400px;
            position: relative;
            background-color: #f8f9fa;
        }

        /* Image containers to prevent shifts */
        .img-container {
            position: relative;
            overflow: hidden;
        }

        .img-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Product grid optimization */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            aspect-ratio: 1;
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
        }

        .product-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Lazy loading states */
        .lazy {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lazy.loaded {
            opacity: 1;
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Server response indicators */
        .slow-server-indicator {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #ff6b6b;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .slow-server-indicator.show {
            transform: translateX(0);
        }

        /* Font loading optimization */
        .font-loading {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Responsive breakpoints */
        @media (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: var(--container-max);
            }
        }

        /* Print styles */
        @media print {

            .loader-container,
            .back-to-top,
            .slow-server-indicator {
                display: none !important;
            }
        }
    </style>

    <!-- Load critical CSS with proper fallbacks -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        media="all">
    <link rel="stylesheet" href="{{ asset('WebsiteAssets/assets/css/styles.css') }}" media="all">

    <!-- Performance monitoring script -->
    <script>
        (function() {
            'use strict';

            // Performance monitoring
            const perf = {
                start: performance.now(),
                serverThreshold: 2000,
                lcpThreshold: 2500,


                // Monitor performance
                monitor() {
                    // Server response time
                    if (performance.navigation) {
                        window.addEventListener('load', () => {
                            const serverTime = performance.timing.responseEnd - performance.timing
                                .requestStart;
                            if (serverTime > this.serverThreshold) {
                                console.warn('Slow server response:', serverTime + 'ms');
                                this.showSlowServerWarning();
                            }
                        });
                    }

                    // LCP monitoring
                    if ('PerformanceObserver' in window) {
                        const observer = new PerformanceObserver((list) => {
                            const entries = list.getEntries();
                            const lastEntry = entries[entries.length - 1];
                            if (lastEntry.startTime > this.lcpThreshold) {
                                console.warn('LCP too slow:', lastEntry.startTime + 'ms');
                            }
                        });
                        observer.observe({
                            entryTypes: ['largest-contentful-paint']
                        });
                    }
                }
            };

            // Font loading optimization
            const fontLoader = {
                loadFonts() {
                    if ('fonts' in document && document.fonts.load) {
                        const fontPromises = [
                            document.fonts.load('400 1em Inter'),
                            document.fonts.load('500 1em Inter'),
                            document.fonts.load('600 1em Inter'),
                            document.fonts.load('700 1em Inter')
                        ];

                        Promise.allSettled(fontPromises).finally(() => {
                            document.documentElement.classList.add('fonts-loaded');
                        });

                        // Fallback timeout
                        setTimeout(() => {
                            document.documentElement.classList.add('fonts-loaded');
                        }, 3000);
                    } else {
                        document.documentElement.classList.add('fonts-loaded');
                    }
                }
            };

            // Loader management
            const loaderManager = {
                loader: null,
                loaderImg: null,
                hidden: false,

                init() {
                    this.loader = document.getElementById('loader');
                    this.loaderImg = this.loader?.querySelector('.loader-image img');
                    this.show();
                },

                show() {
                    if (this.loader && this.loaderImg) {
                        this.loader.style.display = 'flex';
                        this.loaderImg.classList.add('active');
                    }
                },

                hide() {
                    if (this.hidden || !this.loader) return;

                    this.hidden = true;
                    this.loader.style.opacity = '0';

                    setTimeout(() => {
                        this.loader.style.display = 'none';
                        if (this.loaderImg) {
                            this.loaderImg.classList.remove('active');
                        }
                    }, 300);
                },

                setupHideEvents() {
                    // Multiple hide triggers
                    document.addEventListener('DOMContentLoaded', () => this.hide());
                    window.addEventListener('load', () => this.hide());

                    // Fallback timeout
                    setTimeout(() => this.hide(), 5000);

                    // Make globally available
                    window.hideLoader = () => this.hide();
                }
            };

            // Lazy loading for images
            const lazyLoader = {
                observer: null,

                init() {
                    if ('IntersectionObserver' in window) {
                        this.observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    this.loadImage(entry.target);
                                    this.observer.unobserve(entry.target);
                                }
                            });
                        }, {
                            rootMargin: '50px 0px',
                            threshold: 0.01
                        });

                        this.observeImages();
                    } else {
                        // Fallback for older browsers
                        this.loadAllImages();
                    }
                },

                observeImages() {
                    const lazyImages = document.querySelectorAll('img[data-src], img.lazy');
                    lazyImages.forEach(img => this.observer.observe(img));
                },

                loadImage(img) {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    img.classList.add('loaded');
                    img.classList.remove('lazy');
                },

                loadAllImages() {
                    const lazyImages = document.querySelectorAll('img[data-src], img.lazy');
                    lazyImages.forEach(img => this.loadImage(img));
                }
            };

            // Initialize everything
            function init() {
                perf.monitor();
                fontLoader.loadFonts();
                loaderManager.init();
                loaderManager.setupHideEvents();

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => {
                        lazyLoader.init();
                    });
                } else {
                    lazyLoader.init();
                }
            }

            init();
        })();
    </script>
</head>

<body>
    <!-- Optimized loader -->


    @include('Website.Includes.navbar')
    <main>
        @yield('content')
    </main>
    @include('Website.Includes.footer')

    <button id="backToTop" class="back-to-top" aria-label="Back to top">
        Back To Top →
    </button>

    <!-- NON-BLOCKING SCRIPT LOADING - This is the key change -->
    <script>
        // Load jQuery and Bootstrap asynchronously after page load
        (function() {
            'use strict';

            // Wait for page to be interactive or loaded
            function loadScriptsAsync() {
                // Create and load jQuery
                const jqueryScript = document.createElement('script');
                jqueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                jqueryScript.async = true;
                jqueryScript.crossOrigin = 'anonymous';

                // Create and load Bootstrap
                const bootstrapScript = document.createElement('script');
                bootstrapScript.src =
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js';
                bootstrapScript.async = true;
                bootstrapScript.crossOrigin = 'anonymous';

                // Load jQuery first, then Bootstrap
                jqueryScript.onload = function() {
                    document.head.appendChild(bootstrapScript);

                    bootstrapScript.onload = function() {
                        // Initialize core functionality after both libraries are loaded
                        initializeCore();
                    };
                };

                // Start loading jQuery
                document.head.appendChild(jqueryScript);
            }

            // Initialize when scripts are ready
            function initializeCore() {
                // Library loader with caching and error handling (same as before)
                class LibraryLoader {
                    constructor() {
                        this.loaded = new Set();
                        this.loading = new Map();
                        this.dependencies = {
                            'select2': ['jquery'],
                            'slick': ['jquery'],
                            'jquery-ui': ['jquery']
                        };
                    }

                    async loadScript(src, id = null) {
                        if (id && this.loaded.has(id)) return Promise.resolve();
                        if (this.loading.has(src)) return this.loading.get(src);

                        const promise = new Promise((resolve, reject) => {
                            const script = document.createElement('script');
                            script.src = src;
                            script.async = true;
                            script.defer = true;
                            script.onload = () => {
                                if (id) this.loaded.add(id);
                                resolve();
                            };
                            script.onerror = () => reject(new Error(`Failed to load ${src}`));
                            document.head.appendChild(script);
                        });

                        this.loading.set(src, promise);
                        return promise;
                    }

                    async loadCSS(href, id = null) {
                        if (id && this.loaded.has(id)) return Promise.resolve();

                        return new Promise((resolve) => {
                            const link = document.createElement('link');
                            link.rel = 'stylesheet';
                            link.href = href;
                            link.onload = () => {
                                if (id) this.loaded.add(id);
                                resolve();
                            };
                            link.onerror = resolve; // Don't fail on CSS errors
                            document.head.appendChild(link);
                        });
                    }

                    async loadLibrary(name, options = {}) {
                        const configs = {
                            'jquery': {
                                js: 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js'
                            },
                            'bootstrap': {
                                js: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
                            },
                            'toastr': {
                                css: 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
                                js: 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'
                            },
                            'fontawesome': {
                                css: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'
                            },
                            'swiper': {
                                css: 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
                                js: 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js'
                            },
                            'select2': {
                                css: 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css',
                                js: 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js'
                            },
                            'slick': {
                                css: 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css',
                                js: 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js'
                            },
                            'aos': {
                                css: 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css',
                                js: 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js'
                            },
                            'jquery-ui': {
                                css: 'https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css',
                                js: 'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js'
                            }
                        };

                        const config = configs[name];
                        if (!config) throw new Error(`Unknown library: ${name}`);

                        // Load dependencies first
                        const deps = this.dependencies[name] || [];
                        for (const dep of deps) {
                            await this.loadLibrary(dep);
                        }

                        // Load CSS first, then JS
                        const promises = [];
                        if (config.css) {
                            promises.push(this.loadCSS(config.css, `${name}-css`));
                        }
                        if (config.js) {
                            promises.push(this.loadScript(config.js, `${name}-js`));
                        }

                        return Promise.all(promises);
                    }
                }

                // Initialize library loader
                const loader = new LibraryLoader();
                window.loadLibrary = (name) => loader.loadLibrary(name);

                // Core functionality
                class WebsiteCore {
                    constructor() {
                        this.init();
                    }

                    async init() {
                        // Load essential libraries (toastr only, jQuery and Bootstrap already loaded)
                        await loader.loadLibrary('toastr');

                        this.setupBackToTop();
                        this.setupToasts();
                        this.loadConditionalLibraries();
                        this.onDOMReady();
                    }

                    setupBackToTop() {
                        const backToTop = document.getElementById('backToTop');
                        if (!backToTop) return;

                        let ticking = false;
                        const updateVisibility = () => {
                            backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
                            ticking = false;
                        };

                        window.addEventListener('scroll', () => {
                            if (!ticking) {
                                requestAnimationFrame(updateVisibility);
                                ticking = true;
                            }
                        }, {
                            passive: true
                        });

                        backToTop.addEventListener('click', () => {
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        });
                    }

                    async setupToasts() {
                        window.showToast = async (message, type = 'success') => {
                            await loader.loadLibrary('toastr');

                            if (typeof toastr !== 'undefined') {
                                toastr.options = {
                                    closeButton: true,
                                    newestOnTop: true,
                                    progressBar: true,
                                    positionClass: "toast-top-right",
                                    timeOut: 5000,
                                    extendedTimeOut: 1000,
                                    showMethod: "fadeIn",
                                    hideMethod: "fadeOut"
                                };

                                toastr[type] ? toastr[type](message) : toastr.success(message);
                            }
                        };

                        // Handle Laravel messages
                        this.handleLaravelMessages();
                    }

                    handleLaravelMessages() {
                        const messages = [
                            @if (session('success'))
                                {
                                    message: '{!! addslashes(session('success')) !!}',
                                    type: 'success'
                                },
                            @endif
                            @if (session('error'))
                                {
                                    message: '{!! addslashes(session('error')) !!}',
                                    type: 'error'
                                },
                            @endif
                            @if (session('warning'))
                                {
                                    message: '{!! addslashes(session('warning')) !!}',
                                    type: 'warning'
                                },
                            @endif
                            @if (session('info'))
                                {
                                    message: '{!! addslashes(session('info')) !!}',
                                    type: 'info'
                                },
                            @endif
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    {
                                        message: '{!! addslashes($error) !!}',
                                        type: 'error'
                                    },
                                @endforeach
                            @endif
                        ];

                        messages.forEach(({
                            message,
                            type
                        }) => {
                            setTimeout(() => window.showToast(message, type), 100);
                        });
                    }

                    async loadConditionalLibraries() {
                        // Font Awesome
                        if (document.querySelector('[class*="fa-"], .fa, .fas, .far, .fab')) {
                            loader.loadLibrary('fontawesome');
                        }

                        // Swiper
                        if (document.querySelector('.swiper, .swiper-container, [data-swiper]')) {
                            await loader.loadLibrary('swiper');
                            this.initSwiper();
                        }

                        // Select2
                        if (document.querySelector('select[multiple], .select2, [data-select2]')) {
                            await loader.loadLibrary('select2');
                            this.initSelect2();
                        }

                        // Slick Carousel
                        if (document.querySelector('.slick, .slick-slider, [data-slick], .slider')) {
                            await loader.loadLibrary('slick');
                            this.initSlick();
                        }

                        // AOS
                        if (document.querySelector('[data-aos]')) {
                            await loader.loadLibrary('aos');
                            this.initAOS();
                        }

                        // jQuery UI
                        if (document.querySelector(
                                '.ui-draggable, .ui-sortable, .ui-datepicker, .ui-dialog, .ui-autocomplete, #slider-range, .ui-slider'
                                )) {
                            await loader.loadLibrary('jquery-ui');
                        }
                    }

                    initSwiper() {
                        const swipers = document.querySelectorAll('.swiper, .product-swiper');
                        swipers.forEach(swiperEl => {
                            if (!swiperEl.swiper && typeof Swiper !== 'undefined') {
                                new Swiper(swiperEl, {
                                    slidesPerView: 1,
                                    spaceBetween: 20,
                                    loop: true,
                                    breakpoints: {
                                        480: {
                                            slidesPerView: 1,
                                            spaceBetween: 20,
                                        },
                                        768: {
                                            slidesPerView: 2,
                                            spaceBetween: 25,
                                        },
                                        1024: {
                                            slidesPerView: 3,
                                            spaceBetween: 30,
                                        },
                                        1200: {
                                            slidesPerView: 4,
                                            spaceBetween: 20,
                                        }
                                    },
                                    autoplay: {
                                        delay: 3000,
                                        disableOnInteraction: false,
                                    },
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true,
                                    },
                                    navigation: {
                                        nextEl: '.swiper-button-next',
                                        prevEl: '.swiper-button-prev',
                                    },
                                    lazy: true,
                                });
                            }
                        });
                    }

                    initSelect2() {
                        if (typeof $ !== 'undefined' && $.fn.select2) {
                            $('.select2, select[data-select2], select[multiple]').select2({
                                theme: 'bootstrap-5'
                            });
                        }
                    }

                    initSlick() {
                        if (typeof $ !== 'undefined' && $.fn.slick) {
                            // Initialize standard slick sliders
                            $('.slick-slider, [data-slick]').slick({
                                dots: true,
                                infinite: true,
                                speed: 300,
                                slidesToShow: 1,
                                adaptiveHeight: true,
                                lazyLoad: 'ondemand'
                            });

                            // Initialize brand slider (Section 9)
                            const brandSlider = $('.slider');
                            if (brandSlider.length > 0) {
                                const slides = brandSlider.find('.w-card-4');
                                if (slides.length > 0) {
                                    const totalSlides = slides.length;

                                    // Clone slides for infinite loop
                                    for (let i = 0; i < 3; i++) {
                                        slides.each(function() {
                                            $(this).clone().appendTo(brandSlider);
                                        });
                                    }

                                    brandSlider.slick({
                                        slidesToShow: 5,
                                        slidesToScroll: 1,
                                        autoplay: true,
                                        autoplaySpeed: 2000,
                                        infinite: true,
                                        dots: false,
                                        arrows: false,
                                        responsive: [{
                                                breakpoint: 1024,
                                                settings: {
                                                    slidesToShow: 4,
                                                    slidesToScroll: 1,
                                                },
                                            },
                                            {
                                                breakpoint: 768,
                                                settings: {
                                                    slidesToShow: 4,
                                                    slidesToScroll: 1,
                                                },
                                            },
                                            {
                                                breakpoint: 480,
                                                settings: {
                                                    slidesToShow: 2,
                                                    slidesToScroll: 1,
                                                },
                                            },
                                        ],
                                    });
                                }
                            }
                        }
                    }

                    initAOS() {
                        if (typeof AOS !== 'undefined') {
                            AOS.init({
                                duration: 1000,
                                once: true,
                                mirror: false,
                                disable: window.innerWidth < 768
                            });
                        }
                    }

                    onDOMReady() {
                        // Dispatch ready event
                        document.dispatchEvent(new CustomEvent('websitecore:ready'));

                        // Remove loading classes
                        document.body.classList.remove('loading');
                        document.body.classList.add('loaded');
                    }
                }

                // Initialize core
                new WebsiteCore();
            }

            // Load scripts when DOM is ready or page is already loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', loadScriptsAsync);
            } else {
                // Page already loaded, load scripts immediately
                loadScriptsAsync();
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
