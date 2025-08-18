
@php
    use App\Models\GeneralSettings;
    use App\Models\Menus;
    use App\Models\DynamicPage;

    // Fetch all required options in one query
    $settings = GeneralSettings::whereIn('option', ['_instagram_link', '_facebook_link', '_twitter_link', '_youtube_link', '_header_logo', '_footer_logo', '_b_street_address', '_b_email', '_b_area_address', '_b_number'])
        ->pluck('value', 'option');

    $menus = Menus::with('menuMeta')->orderBy('number', 'asc')->get();
    $dynamicpages = DynamicPage::all();
    $footer_logo = $settings['_footer_logo'] ?? '#';
    $b_email = $settings['_b_email'] ?? '#';
    $b_street_address = $settings['_b_street_address'] ?? '#';
    $b_area_address = $settings['_b_area_address'] ?? '#';
    $b_number = $settings['_b_number'] ?? '#';
    $instagramLink = $settings['_instagram_link'] ?? '#';
    $facebookLink = $settings['_facebook_link'] ?? '#';
    $twitterLink = $settings['_twitter_link'] ?? '#';
    $youtubeLink = $settings['_youtube_link'] ?? '#';
@endphp
<style>
    #lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: none;
        /* Ensure it's hidden on load */
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }


    body.lightbox-open {
        overflow: hidden;
        /* Disable scroll */
    }

    header {
        z-index: 12;
        /* Ensure header stays behind lightbox */
    }

    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
        text-align: center;
        z-index: 1000;
    }

    .lightbox img {
        max-width: 90%;
        max-height: 90%;
    }

    .lightbox .close-btn {
        position: absolute;
        top: 30px;
        right: 20px;
        font-size: 30px;
        color: white;
        cursor: pointer;
    }

    .lightbox .prev,
    .lightbox .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    .lightbox .prev {
        left: 50px;
    }

    .lightbox .next {
        right: 50px;
    }
    @media (min-width: 1020px) and (max-width: 1150px){
        .FOOTER_LOGOO{
            max-width: 230px !important;
        }
    }
</style>
<footer class="footer" style="
    position: relative !important;">

    <img src="/WebsiteAssets/assets/img/footer-bg.webp" alt="" class="footer-bg" style="
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        pointer-events: none !important;
        opacity: 0.16 !important;
    ">
    <div class="container">
        <div class="row pb-5">
            <form id="contactForm" action="{{ url('send-mail') }}" method="POST">
                @csrf
                <div class="col-12">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center pb-5 footer_first_div">
                        <a href="{{ url('/') }}" class="mb-3 mb-md-0">
                            <img src="{{ asset($footer_logo) ?? '#' }}" alt="Sydney Quality Meat Logo"
                                class="footer-logo mb-0" />
                        </a>
                        <div class="email-container">
                            <input type="email" name="email" placeholder="Email Address" class="email-input" required />
                            <button type="submit" id="btn_sbmt" class="btn btn-link p-0"
                                style="background-color: transparent; border: none;" aria-label="Submit email subscription">
                                <i class="fas fa-paper-plane submit-arrow"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <!-- TIMES -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 mb-4">
                <h2 class="text-uppercase mb-3 fw-bold fs-4">Opening Hours</h2>
                <table class="Footer_Table table-sm   mb-4">
                    <tbody class="text-capitalize">
                        <tr class="Footers_TR">
                            <td class="footers_td" class="day">monday</td>
                            <td class="footers_td" class="shift">10:00AM - 07:00PM</td>
                        </tr>
                        <tr class="Footers_TR">
                            <td class="footers_td" class="day">tuesday</td>
                            <td class="footers_td" class="shift">10:00AM - 07:00PM</td>
                        </tr>
                        <tr class="Footers_TR">
                            <td class="footers_td" class="day">wednesday</td>
                            <td class="footers_td" class="shift">10:00AM - 07:00PM</td>
                        </tr>
                        <tr class="Footers_TR">
                            <td class="footers_td" class="day">thursday</td>
                            <td class="footers_td" class="shift">10:00AM - 07:00PM</td>
                        </tr>
                        <tr class="Footers_TR">
                            <td class="footers_td" class="day">friday</td>
                            <td class="footers_td" class="shift">10:00AM - 07:00PM</td>
                        </tr>
                        <tr class="Footers_TR">
                            <td class="footers_td" class="day">saturday</td>
                            <td class="footers_td" class="shift">Closed</td>
                        </tr>
                        <tr class="footers_tr">
                            <td class="footers_td" class="day">sunday</td>
                            <td class="footers_td" class="shift">Closed</td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-none d-xl-block">
                    <h2 class="text-uppercase mb-3 fw-bold fs-4">Address</h2>
                    <a class="mb-4" style="text-decoration: none; color: white;" href="{{$b_street_address}}" target="_blank">{{$b_area_address}} <i class="text-danger">></i></a>
                </div>            
            </div>

            <!-- CONTACT-US -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-3 mb-4">
                <h2 class="text-uppercase mb-3 fw-bold fs-4">Contact Us</h2>
                <ul class="list-unstyled footer-link_s">
                    <li class="mb-3"><a href="{{ url('/contact-us') }}">Customer Support</a><i>></i></li>
                </ul>
                
                <h2 class="text-uppercase mb-3 fw-bold fs-4">Be Social</h2>
                <ul class="list-unstyled footer-link_s">
                    <li>Like us on<a href="{{ strpos($facebookLink, 'http') === false ? 'https://' . $facebookLink : $facebookLink }}" class="fw-bold ms-1" target="_blank">Facebook</a><i>></i></li>
                    <li>Like us on<a href="{{ strpos($twitterLink, 'http') === false ? 'https://' . $twitterLink : $twitterLink }}" class="fw-bold ms-1" target="_blank">Twitter</a><i>></i></li>
                    <li>Like us on<a href="{{ strpos($youtubeLink, 'http') === false ? 'https://' . $youtubeLink : $youtubeLink }}" class="fw-bold ms-1" target="_blank">LinkedIn</a><i>></i></li>
                    <li>Like us on<a href="{{ strpos($instagramLink, 'http') === false ? 'https://' . $instagramLink : $instagramLink }}" class="fw-bold ms-1" target="_blank">Instagram</a><i>></i></li>

                </ul>
                
            </div>
            
            <!-- ABOUT-US -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                <h2 class="text-uppercase mb-3 fw-bold fs-4">About Us</h2>
                <ul class="list-unstyled footer-link_s mb-4">
                @foreach ($dynamicpages as $page)
                            <li><a href="{{ url('page/'.$page->permalink) }}">{{ $page->title }}</a><i>></i></li>
                    @endforeach
                </ul>

              
                <div class="d-block d-xl-none pb-3 pb-sm-0">
                    <h2 class="text-uppercase mb-3 fw-bold fs-4">Address</h2>
                    <a class="mb-4" style="text-decoration: none; color: white;" href="{{$b_street_address}}" target="_blank">{{$b_area_address}} <i class="text-danger">></i></a>
                </div>
               
            </div>
            
            <!-- LOGO -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
             
                <img src="{{ asset('/WebsiteAssets/assets/img/FOOTERlogo.png')}}"  alt="Logo" class="img-fluid mb-4 FOOTER_LOGOO" style="max-width: 260px;">
                
            </div>
        </div>

        
    </div>
</footer>
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <span class="close-btn" onclick="closeLightbox(event)">&times;</span>
    <img id="lightbox-img" src="" alt="Expanded Image">
    <button class="prev" onclick="changeImage(-1)">&#10094;</button>
    <button class="next" onclick="changeImage(1)">&#10095;</button>
</div>
<footer class="footer-2">
    <div class="container">
        <div class="copyright">© Copyright 2025 by G-Tech Solutions</div>
    </div>
</footer>

<!-- Footer End -->

<!-- Custom Script -->
<script src="{{ asset('WebsiteAssets/assets/js/scripts.js') }}"></script>
<script src="{{ asset('WebsiteAssets/assets/js/shop.js') }}"></script>
<script src="{{ asset('WebsiteAssets/assets/js/checkout.js') }}"></script>
@stack('scripts')
