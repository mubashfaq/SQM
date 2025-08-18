@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')
  @php
    use App\Models\GeneralSettings;



    // Fetch all required options in one query
    $settings = GeneralSettings::whereIn('option', ['_b_number'])
    ->pluck('value', 'option');
    $b_number = $settings['_b_number'] ?? '#';
  @endphp
  @if ($page->permalink == 'about-us')
    <!-- About Section 1 -->
    <section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
  <div class="container page-heading-center" >

    <div>
    <h2> {{$page->title}}</h2>
    </div>
    <div>
    <p><span>Sydney Quality Meats:</span> {{$page->title}}</p>
    </div>
    </div>
    </section>

    <!-- About Section 2 -->
    <section data-aos="fade-up" class="about-sec2 position-relative">
    <div class="about-sec2-img-1-wrapper">
    <img src="{{ asset('WebsiteAssets/assets/img/about-shape-3-3.png') }}" class="about-sec2-img-1" alt="Image" />
    </div>

    <img src="{{ asset('WebsiteAssets/assets/img/about-shape-3-2.png') }}" class="about-sec2-img-2" alt="Image" />
    <div class="container">
    <div class="row about-sec2-row_2">
      <div></div>
      <div class="col-12 col-lg-6">
      <div class="position-relative about-sec2-bg" style="
          background-image: url('{{ asset('WebsiteAssets/assets/img/about-sec2-bg.jpg') }}');
          background-size: cover;
          background-position: center;
          height: 100%;
          min-height: 597px;
        ">
      <div class="aboutsec2_small_image d-none d-sm-block">
      <img src="{{ asset('WebsiteAssets/assets/img/about-3-2.jpg') }}" alt="small image" />
      </div>
      <div class="aboutsec2_small_sec_div">
      <div class="about-three__experience" style="text-decoration: initial">
        <div class="about-three__experience__bg"
        style="background-image: url({{ asset('WebsiteAssets/assets/img/about-shape-3-1.png') }})">
        </div>
        <!-- /.about-three__experience__bg -->
        <div class="about-three__experience__content" style="text-decoration: initial">
        <div class="about-three__experience__text" style="text-decoration: initial">
        <h4 class="about-three__experience__title" style="text-decoration: initial">
        years of <br />
        experience
        </h4>
        <!-- /.about-three__experience__title -->
        </div>
        <!-- /.about-three__experience__text -->
        <h4 class="about-three__experience__year" style="text-decoration: initial">
        25+
        </h4>
        </div>
      </div>
      </div>
      </div>
      </div>
      <div class="col-12 col-lg-6">
      <div class="ps-lg-4 pt-5 pt-lg-0">
      <div>
      <img src="{{ asset('WebsiteAssets/assets/img/logo-dark.png') }}" alt="Meat Shop Logo"
        class="aboutsec2_logo" />
      <h6 class="aboutsec2_subtitle">ABOUT Sydney Quality Meats SHOP</h6>
      <h1 class="aboutsec2_title">{{ $page->hero_heading ?? 'No Heading' }}</h1>
      <div class="aboutsec2_description">
        {!! $page->content ?? '' !!}
      </div>
      </div>

      <div class="aboutsec2_features">
      <div class="aboutsec2_feature">
        <div class="aboutsec2_feature-icon">
        <span class="icon-healthcare-1"></span>
        </div>
        <h5 class="aboutsec2_feature-text">HEALTH AND SAFETY</h5>
      </div>
      <div class="aboutsec2_feature">
        <div class="aboutsec2_feature-icon">
        <span class="icon-meat"></span>
        </div>
        <h5 class="aboutsec2_feature-text">100% FRESH PRODUCE</h5>
      </div>
      </div>

      <div class="aboutsec2_quality">
      <div class="aboutsec2_quality-header">
        <h6 class="aboutsec2_quality-text">QUALITY OF MEAT</h6>
        <span>95%</span>
      </div>
      <div class="aboutsec2_quality-bar">
        <div class="aboutsec2_quality-progress"></div>
      </div>
      </div>

      <div class="aboutsec2_footer">
      <div>
        <a href="{{ url('/page/about-us') }}" class="home-btn-7-sec3">
        <span>KNOW ABOUT US</span>
        <span class="divider"></span>
        <i class="icon-meat-3"></i>
        </a>
      </div>
      <div class="aboutsec2_director d-none">
        <img src="{{ asset('WebsiteAssets/assets/img/images.jpg') }}" alt="Director"
        class="aboutsec2_director-image" />
        <div class="aboutsec2_director-info">
        <p class="aboutsec2_director-title">DIRECTOR</p>
        <h5 class="aboutsec2_director-name">BENJAMIN</h5>
        </div>
        <img src="{{ asset('WebsiteAssets/assets/img/about-director-sign-3-1.png') }}" alt="Signature"
        class="aboutsec2_signature" />
      </div>
      </div>
      </div>
      </div>
    </div>
    </div>
    </section>

    <!-- About Sections 3 -->
    <section data-aos="fade-up" class="statistics-section d-flex justify-content-center align-items-center">
        <!-- Dark Overlay -->
        <div class="overlay"></div>

        <!-- Statistics Content -->
        <div class="statistics-content">
            <div class="row justify-content-center SC_Main_SEC align-items-center text-center ">
                <!-- Statistic Item -->
                <div
                    class="col-12 col-sm-6 Circle_SEC position-relative justify-content-center align-items-center text-center col-lg-3">
                    <div class="stat-item position-relative text-white text-center">
                        <div class="background-animation"></div>
                        <h1 class="stat-number" data-count="30">0</h1>
                        <p class="stat-label">YEAR OF EXPERIENCE</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 Circle_SEC col-lg-3">
                    <div class="stat-item position-relative text-white text-center">
                        <div class="background-animation"></div>
                        <h1 class="stat-number" data-count="45000">0K</h1>
                        <p class="stat-label">TONS OF MEAT</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 Circle_SEC col-lg-3">
                    <div class="stat-item position-relative text-white text-center">
                        <div class="background-animation"></div>
                        <h1 class="stat-number" data-count="35000">0K</h1>
                        <p class="stat-label">HAPPY CUSTOMERS</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 Circle_SEC col-lg-3">
                    <div class="stat-item position-relative text-white text-center">
                        <div class="background-animation"></div>
                        <h1 class="stat-number" data-count="32">0</h1>
                        <p class="stat-label">MEAT PRODUCTS</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section 4 -->
    <section data-aos="fade-up" class="about-sec2 position-relative">
    <div class="about-sec4-img-1-wrapper">
    <img src="{{ asset('WebsiteAssets/assets/img/sec7-btm-img.png') }}" class="about-sec4-img-1" alt="Image" />
    </div>
    <div class="container">
    <div class="row about-sec2-row_2">
      <div class="col-12 col-lg-6">
      <div>
      <h6 class="aboutsec2_subtitle">DELIVERY SERVICE</h6>
      <h1 class="aboutsec2_title">Express delivery to your doorstep</h1>
      <p class="aboutsec2_description">
      A meat store is a specialized retail establishment that offers a
      wide range of fresh and high-quality meats, including beef,
      pork, chicken, lamb, and other varieties. At a meat store,
      customers can find a variety of cuts and preparations, from
      steaks and roasts to ground meats and sausages.
      </p>
      </div>

      <!-- Buttons -->
      <div class="d-flex flex-column flex-sm-row gap-3 pt-3 Alin___Intems">
      <div>
      <a href="{{ url('/page/about-us') }}" class="home-btn-7-sec3">
        <span>KNOW ABOUT US</span>
        <span class="divider"></span>
        <i class="icon-meat-3"></i>
      </a>
      </div>

      <!-- Call for Delivery -->
      <div class="d-flex align-items-center gap-3 call-wrapper">
      <div
        class="w-5 h-5 rounded-circle bg-light d-flex align-items-center justify-content-center position-relative overflow-hidden call-icon-container">
        <span class="call-icon-overlay"></span>
        <span class="icon-telephone"></span>
      </div>
      <div class="d-flex flex-column align-items-start">
        <span class="small text-muted">CALL FOR DELIVERY</span>
        <span class="text-sm plus-jakarta-sans">
        <a href="tel:{{$b_number}}"
        class="text_color_red fw-semibold text-decoration-none underline-animation">{{$b_number}}</a>
        </span>
      </div>
      </div>

      </div>
      </div>
      <div class="col-12 col-lg-6">
      <div class="pt-5 pt-lg-0">
      <div class="delivery-one__image ms-lg-4">
      <div class="delivery-one__image__inner-one">
        <img decoding="async" src="https://bracketweb.com/boskerywp/wp-content/uploads/2024/02/delivery-1-1.png"
        alt="delivery-1-1" title="delivery-1-1" class="delivery-one__image__one" />
        <div class="delivery-one__image__bg">
        <div class="delivery-one__image__bg__inner" style="
            background-image: url(https://bracketweb.com/boskerywp/wp-content/plugins/elementor/assets/images/placeholder.png);
          ">
        </div>
        </div>
        <div class="delivery-one__circle-text">
        <div class="delivery-one__circle-text__bg"></div>
        <div class="delivery-one__circle-text__plus"></div>
        <div class="delivery-one__curved-circle curved-circle">
        <div class="delivery-one__curved-circle__item curved-circle__item" data-circle-text-options='{
             "radius": 87,
             "forceWidth": true,
             "forceHeight": true}' style="
            position: relative;
            visibility: visible;
            height: 28px;
            ">
        <span class="char1" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-173.395deg);
            transform-origin: center 5.8em;
            ">n</span><span class="char2" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-162.338deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char3" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(-151.784deg);
            transform-origin: center 5.8em;
            ">t</span><span class="char4" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-141.229deg);
            transform-origin: center 5.8em;
            ">u</span><span class="char5" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(-130.675deg);
            transform-origin: center 5.8em;
            ">r</span><span class="char6" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-120.12deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char7" style="
            position: absolute;
            left: 50%;
            margin-left: -0.3em;
            transform: rotate(-110.068deg);
            transform-origin: center 5.8em;
            ">l</span><span class="char8" style="
            position: absolute;
            left: 50%;
            margin-left: -0.2em;
            transform: rotate(-102.529deg);
            transform-origin: center 5.8em;
            ">&nbsp;</span><span class="char9" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-93.9852deg);
            transform-origin: center 5.8em;
            ">o</span><span class="char10" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(-83.4307deg);
            transform-origin: center 5.8em;
            ">r</span><span class="char11" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-72.8762deg);
            transform-origin: center 5.8em;
            ">g</span><span class="char12" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-61.8191deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char13" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-50.7621deg);
            transform-origin: center 5.8em;
            ">n</span><span class="char14" style="
            position: absolute;
            left: 50%;
            margin-left: -0.2em;
            transform: rotate(-42.2179deg);
            transform-origin: center 5.8em;
            ">i</span><span class="char15" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(-33.6738deg);
            transform-origin: center 5.8em;
            ">c</span><span class="char16" style="
            position: absolute;
            left: 50%;
            margin-left: -0.2em;
            transform: rotate(-25.1297deg);
            transform-origin: center 5.8em;
            ">&nbsp;</span><span class="char17" style="
            position: absolute;
            left: 50%;
            margin-left: -0.433333em;
            transform: rotate(-15.5804deg);
            transform-origin: center 5.8em;
            ">m</span><span class="char18" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(-4.02076deg);
            transform-origin: center 5.8em;
            ">e</span><span class="char19" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(6.53373deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char20" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(17.0882deg);
            transform-origin: center 5.8em;
            ">t</span><span class="char21" style="
            position: absolute;
            left: 50%;
            margin-left: -0.2em;
            transform: rotate(25.1297deg);
            transform-origin: center 5.8em;
            ">&nbsp;</span><span class="char22" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(33.6738deg);
            transform-origin: center 5.8em;
            ">n</span><span class="char23" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(44.7309deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char24" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(55.2854deg);
            transform-origin: center 5.8em;
            ">t</span><span class="char25" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(65.8399deg);
            transform-origin: center 5.8em;
            ">u</span><span class="char26" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(76.3944deg);
            transform-origin: center 5.8em;
            ">r</span><span class="char27" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(86.9489deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char28" style="
            position: absolute;
            left: 50%;
            margin-left: -0.3em;
            transform: rotate(97.0007deg);
            transform-origin: center 5.8em;
            ">l</span><span class="char29" style="
            position: absolute;
            left: 50%;
            margin-left: -0.2em;
            transform: rotate(104.54deg);
            transform-origin: center 5.8em;
            ">&nbsp;</span><span class="char30" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(113.084deg);
            transform-origin: center 5.8em;
            ">o</span><span class="char31" style="
            position: absolute;
            left: 50%;
            margin-left: -0.333333em;
            transform: rotate(123.638deg);
            transform-origin: center 5.8em;
            ">r</span><span class="char32" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(134.193deg);
            transform-origin: center 5.8em;
            ">g</span><span class="char33" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(145.25deg);
            transform-origin: center 5.8em;
            ">a</span><span class="char34" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(156.307deg);
            transform-origin: center 5.8em;
            ">n</span><span class="char35" style="
            position: absolute;
            left: 50%;
            margin-left: -0.2em;
            transform: rotate(164.851deg);
            transform-origin: center 5.8em;
            ">i</span><span class="char36" style="
            position: absolute;
            left: 50%;
            margin-left: -0.366667em;
            transform: rotate(173.395deg);
            transform-origin: center 5.8em;
            ">c</span>
        </div>
        </div>
        </div>
      </div>
      <div class="delivery-one__image__inner-two">
        <img decoding="async" src="https://bracketweb.com/boskerywp/wp-content/uploads/2024/02/delivery-1-2.jpg"
        alt="delivery-1-2" title="delivery-1-2" class="delivery-one__image__two" />
        <div class="delivery-one__image__box"></div>
      </div>
      </div>
      </div>
      </div>
    </div>
    </div>
    </section>

    <!-- Section 9 -->
    <div data-aos="fade-up" style="background-color: #f6f2ec">
    <section class="container pt-5 pb-5 Slider_section">
    <div class="row mt-2 rounded-5 counter-sec justify-content-around">
      <div class="col-12 user-images position-relative">
      <div class="slider">
      @foreach($brands as $brand)
      <div class="w-card-4">
      <img class="card-img-top" src="{{ asset($brand->logo_image) }}" alt="Card image cap" />
      </div>
    @endforeach
      </div>
      </div>
    </div>
    </section>
    </div>
    <script>
    $(document).ready(function () {
    const $slider = $(".slider");
    let $slides = $slider.find(".w-card-4");

    if ($slides.length > 0) {
      const totalSlides = $slides.length;

      // Clone all slides multiple times to create an endless loop effect
      for (let i = 0; i < 3; i++) { // Adjust the number of clones as needed
      $slides.each(function () {
      $(this).clone().appendTo($slider);
      });
      }

      // Initialize the Slick slider
      $slider.slick({
      slidesToShow: Math.min(5, totalSlides), // Adjust slides to show dynamically
      slidesToScroll: 1,
      prevArrow: ".slick-prev",
      nextArrow: ".slick-next",
      autoplay: true,
      autoplaySpeed: 1000,
      infinite: true, // Ensures infinite loop
      responsive: [
      {
      breakpoint: 1024, // Tablet devices
      settings: {
        slidesToShow: Math.min(4, totalSlides),
        slidesToScroll: 1,
      },
      },
      {
      breakpoint: 768, // Mobile devices
      settings: {
        slidesToShow: Math.min(2, totalSlides),
        slidesToScroll: 1,
      },
      },
      {
      breakpoint: 500, // Extra small screens
      settings: {
        slidesToShow: Math.min(2, totalSlides),
        slidesToScroll: 1,
      },
      },
      ],
      });
    }
    });
    </script>
  @else
    <style>
    .card {
    border: none;
    }
    </style>
    <section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2> {{$page->title}}</h2>
            <p><span>Sydney Quality Meats:</span> {{$page->title}}</p>
        </div>
    </div>
</section>
    <section class="mtop-50">
    <div class="container">

    <div class="row">
      <div class="col-12">
      <div class="card">
      <div class="card-body">
      <h2> {{ $page->hero_heading ?? '' }}</h2>
      {!! $page->content ?? '' !!}
      </div>
      </div>
      </div>
    </div>
    </div>
    </section>
  @endif
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statNumbers = document.querySelectorAll('.stat-number');

    const animateCount = (element, finalValue) => {
        let startValue = 0;
        const duration = 5000; // 2 seconds for the animation
        const increment = finalValue / (duration / 100); // How much to increment every 100ms
        let frame;

        const step = () => {
            startValue += increment;
            if (startValue >= finalValue) {
                startValue = finalValue; // Ensure we stop at the final value
                cancelAnimationFrame(frame); // Stop the animation once the target is reached
            }
            
            // Format the number with "K" if it's over 1000 during the animation
            const formattedValue = formatNumberWithK(startValue);
            element.textContent = formattedValue; // Update the number displayed
            frame = requestAnimationFrame(step); // Repeat the animation frame
        };

        frame = requestAnimationFrame(step); // Start the animation
    };

    // Function to format the number with "K"
    const formatNumberWithK = (value) => {
        if (value >= 5000) {
            // If the number is a whole number, do not show decimal points
            const valueInK = value / 1000;
            return valueInK % 1 === 0 ? `${valueInK}K` : `${valueInK.toFixed(1)}K`; // Check if the value is whole
        }
        return Math.floor(value); // Otherwise just return the number
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Start animating the number when it comes into view
                const statNumber = entry.target;
                const finalValue = parseInt(statNumber.getAttribute('data-count'));
                animateCount(statNumber, finalValue);

                observer.unobserve(statNumber); // Stop observing this stat after animation starts
            }
        });
    }, { threshold: 0.5 });

    // Observe each stat-number element
    statNumbers.forEach(statNumber => {
        observer.observe(statNumber);
    });
});
</script>