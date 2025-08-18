@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

    <!-- Contact Us Section 1 -->
    <section class="about-sec1">
        <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
<div class="container page-heading-center" >
            <div>
                <h2>Contact Us</h2>
            </div>
            <div>    
                <p><span>Sydney Quality Meats:</span> Contact Us</p>
            </div>
        </div>
    </section>

    <!-- Contact Us Section 2 & 3 -->
    <section class="ContactUs_Main_section">
        <!-- Contact Us Section 2 -->
        <section class="ContactUs_section">
            <div class="container">
                <div class="p-4 p-sm-5 Contact_Sec2_bg">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h2 class="ContactUs_title">GET IN TOUCH</h2>
                            <p class="ContactUs_text">
                                At Sydney Quality Meats, we're dedicated to delivering the finest cuts and exceptional
                                service. Got questions about our products, need help with an order, or curious about our
                                sourcing? Our team is ready to assist you.
                            </p>
                            <form id="contactForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="f_name" name="f_name" class="ContactUs_input"
                                            placeholder="Your Name" required />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" id="email" name="email" class="ContactUs_input"
                                            placeholder="Your Email" required />
                                    </div>
                                </div>
                                <textarea id="message" name="message" class="ContactUs_textarea"
                                    placeholder="Write Message..." required></textarea>
                                <div>
                                   <button type="submit" id="btn_sbmt" class="home-btn-7-sec3" aria-label="Send message">
                                        <span>Send Message</span>
                                        <span class="divider"></span>
                                        <i class="icon-meat-3"></i>
                                    </button>
                                </div>
                            </form>

                        </div>
                        <div class="col-lg-6 mt-5 mt-lg-0">
                            <img src="{{ asset('WebsiteAssets/assets/img/contact-1-1.jpg') }}"
                                alt="Butcher shop professional" class="ContactUs_image" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @php
            use App\Models\GeneralSettings;



            // Fetch all required options in one query
            $settings = GeneralSettings::whereIn('option', ['_instagram_link', '_facebook_link', '_twitter_link', '_youtube_link','_b_map_link', '_header_logo', '_footer_logo', '_b_street_address', '_b_email', '_b_area_address', '_b_number'])
                ->pluck('value', 'option');
            $footer_logo = $settings['_footer_logo'] ?? '#';
            $b_email = $settings['_b_email'] ?? '#';
            $b_street_address = $settings['_b_street_address'] ?? '#';
            $b_area_address = $settings['_b_area_address'] ?? '#';
            $b_map_link = $settings['_b_map_link'] ?? '';
            $b_number = $settings['_b_number'] ?? '#';
            $instagramLink = $settings['_instagram_link'] ?? '#';
            $facebookLink = $settings['_facebook_link'] ?? '#';
            $twitterLink = $settings['_twitter_link'] ?? '#';
            $youtubeLink = $settings['_youtube_link'] ?? '#';
        @endphp
        <!-- Contact Us Section 3 -->
        <section class="ContactUs_Sec3">
            <div class="container">
                <div class="row g-4">
                    <!-- Contact Information Section -->
                    <div class="col-lg-6">
                        <div class="ContactUs_Sec3_left">
                            <h2 class="ContactUs_Sec3_title">
                                YOU'RE ALWAYS WELCOME TO VISIT US
                            </h2>
                            <p class="ContactUs_Sec3_subtitle">
                                Our friendly staff will be happy to assist you with any
                                questions or concerns you may have.
                            </p>

                            <div class="ContactUs_Sec3_contact-item">
                                <div class="ContactUs_Sec3_icon">
                                    <i class="icon-phone-call"></i>
                                </div>
                                <div>
                                    <div class="ContactUs_Sec3_label">CALL NOW</div>
                                    <a href="tel:{{$b_number}}" class="text-decoration-none">
                                        <div class="ContactUs_Sec3_value">{{$b_number}}</div>
                                    </a>
                                </div>
                            </div>

                            <div class="ContactUs_Sec3_contact-item">
                                <div class="ContactUs_Sec3_icon">
                                    <i class="icon-paper-plane"></i>
                                </div>
                                <div>
                                    <div class="ContactUs_Sec3_label">EMAIL</div>
                                    <a href="mailto:{{$b_email}}" class="text-decoration-none">
                                        <div class="ContactUs_Sec3_value">{{$b_email}}</div>
                                    </a>
                                </div>
                            </div>

                            <div class="ContactUs_Sec3_contact-item">
                                <div class="ContactUs_Sec3_icon">
                                    <i class="icon-maps-and-flags"></i>
                                </div>
                                <div>
                                    <div class="ContactUs_Sec3_label">ADDRESS</div>
                                    <a href="{{$b_street_address}}" target="_blank" class="text-decoration-none">
                                        <div class="ContactUs_Sec3_value">
                                            {{$b_area_address}}
                                        </div>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours Section -->
                    <div class="col-lg-6">
                        <div class="ContactUs_Sec3_right">
                            <h2 class="ContactUs_Sec3_title">OUR BUSINESS HOURS</h2>
                            <p class="ContactUs_Sec3-2_subtitle">
                            Our business hours are Monday to Friday from 10:00 AM to 07:00 PM. We are closed on Saturdays.
                            </p>

                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Monday</div>
                                <div>10:00AM - 07:00PM</div>
                            </div>
                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Tuesday</div>
                                <div>10:00AM - 07:00PM</div>
                            </div>
                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Wednesday</div>
                                <div>10:00AM - 07:00PM</div>
                            </div>
                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Thursday</div>
                                <div>10:00AM - 07:00PM</div>
                            </div>
                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Friday</div>
                                <div>10:00AM - 07:00PM</div>
                            </div>
                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Saturday</div>
                                <div>Closed</div>
                            </div>
                            <div class="ContactUs_Sec3_hours-row">
                                <div><span class="ContactUs_Sec3_check">✓</span> Sunday</div>
                                <div>Closed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <style>
        @media screen and (max-width:767px) {
            .ContactUs_Main_section {
                padding-top: 50px;
            }

            .ContactUs_title {
                font-size: 40px;
            }
        }
    </style>
    <!-- Contact Us Section 4 -->
<section data-aos="fade-up" class="ContactUs_Map_Section">
   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3311.703640818727!2d151.03567887646895!3d-33.897285620536096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12bc7a5a21fb97%3A0xe2caf0ea9daab4da!2s101%20Rookwood%20Rd%2C%20Yagoona%20NSW%202199%2C%20Australia!5e0!3m2!1sen!2s!4v1740748256134!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                if (typeof toggleLoader === 'function') {
                    toggleLoader(true);
                }

                var btn = $('#btn_sbmt');
                var originalText = btn.find('span').first().text();

                btn.find('span').first().text('Sending...');
                btn.prop('disabled', true);
                $('input, textarea').prop('readonly', true);

                // Get CSRF token from multiple sources
                var csrfToken = $('input[name="_token"]').val() ||
                    $('meta[name="csrf-token"]').attr('content');

                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ url('contact_us_data') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    timeout: 30000,
                    success: function(response) {
                        console.log('Success Response:', response);

                        if (typeof toggleLoader === 'function') {
                            toggleLoader(false);
                        }

                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message || 'Message sent successfully!');
                        } else {
                            alert(response.message || 'Message sent successfully!');
                        }

                        btn.find('span').first().text(originalText);
                        btn.prop('disabled', false);
                        $('input, textarea').prop('readonly', false).val('');
                    },
                    error: function(xhr, status, error) {

                        if (typeof toggleLoader === 'function') {
                            toggleLoader(false);
                        }

                        var message = 'Server Error, Try Again Later!';

                        // Handle different error types
                        if (xhr.status === 0) {
                            message = 'Network error. Please check your internet connection.';
                        } else if (xhr.status === 404) {
                            message =
                                'The contact form endpoint was not found (404). Please check the URL.';
                        } else if (xhr.status === 405) {
                            message =
                                'Method not allowed (405). Please check if the route accepts POST requests.';
                        } else if (xhr.status === 419) {
                            message =
                                'CSRF token mismatch (419). Please refresh the page and try again.';
                        } else if (xhr.status === 422) {
                            // Validation errors
                            try {
                                var errors = xhr.responseJSON?.errors;
                                if (errors) {
                                    var errorList = Object.values(errors).flat().map(error =>
                                        `<li>${error}</li>`).join('');
                                    message = `<ul>${errorList}</ul>`;
                                } else if (xhr.responseJSON?.message) {
                                    message = xhr.responseJSON.message;
                                }
                            } catch (e) {
                                console.log('Error parsing validation response:', e);
                            }
                        } else if (xhr.status === 500) {
                            message =
                                'Internal server error (500). Please check the server logs.';
                            // Try to get more specific error info
                            try {
                                if (xhr.responseJSON?.message) {
                                    message += ' Details: ' + xhr.responseJSON.message;
                                }
                            } catch (e) {
                                console.log('Error parsing 500 response:', e);
                            }
                        } else if (xhr.status >= 400) {
                            message = `Server error (${xhr.status}). Please try again later.`;
                        }

                        // Show the error
                        if (typeof toastr !== 'undefined') {
                            toastr.error(message);
                        } else {
                            alert(message);
                        }

                        // Reset form state
                        btn.find('span').first().text(originalText);
                        btn.prop('disabled', false);
                        $('input, textarea').prop('readonly', false);
                    }
                });
            });
        });

    </script>


@endsection