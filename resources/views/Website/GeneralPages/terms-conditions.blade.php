@php
    use App\Models\GeneralSettings;

 

    // Fetch all required options in one query
    $settings = GeneralSettings::whereIn('option', ['_instagram_link', '_facebook_link', '_twitter_link', '_youtube_link','_header_logo','_footer_logo','_b_street_address','_b_email','_b_area_address','_b_number'])
    ->pluck('value', 'option');

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
@extends('Website.Includes.header', ['meta_Details' => $meta_details])
@section('content')

<!-- Privacy & Policy Section 1 -->

<section class="about-sec1">
    <img src="{{ asset('WebsiteAssets/assets/img/counter-bg-1.jpg') }}" alt="Background Image" class="bg-image" />
    <div class="container">
        <div class="text-center">
            <h2>Terms And Conditions</h2>
            <p><span>Sydney Quality Meats:</span> Terms And Conditions</p>
        </div>
    </div>
</section>

<!-- Privacy & Policy Section 2 -->

<section class="container PPSec2">
    <section class="privacy-section">
        <h2 class="privacy-heading">1. Introduction</h2>
        <p class="privacy-paragraph">Welcome to TechNova Solutions' Privacy Policy. This policy was last updated
            on July 15, 2025. At TechNova Solutions, we respect your privacy and are committed to protecting
            your personal data. This privacy policy will inform you about how we look after your personal data
            when you visit our website (regardless of where you visit it from) and tell you about your privacy
            rights and how the law protects you.</p>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">2. Information We Collect</h2>
        <p class="privacy-paragraph">We collect several types of information from and about users of our
            website, including:</p>
        <ul class="privacy-list">
            <li>Personal data, such as name, email address, telephone number, and company name.</li>
            <li>Usage data, including IP address, browser type and version, time zone setting and location,
                operating system, and other technology on the devices you use to access this website.</li>
            <li>Marketing and communications data, including your preferences in receiving marketing from us and
                our third parties.</li>
        </ul>
        <p class="privacy-paragraph">We collect this data through direct interactions, automated technologies or
            interactions, and third parties or publicly available sources.</p>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">3. How We Use Your Information</h2>
        <p class="privacy-paragraph">We use the information we collect for various purposes, including:</p>
        <ul class="privacy-list">
            <li>To provide and maintain our service</li>
            <li>To notify you about changes to our service</li>
            <li>To allow you to participate in interactive features of our service when you choose to do so</li>
            <li>To provide customer support</li>
            <li>To gather analysis or valuable information so that we can improve our service</li>
            <li>To monitor the usage of our service</li>
            <li>To detect, prevent and address technical issues</li>
            <li>To provide you with news, special offers and general information about other goods, services and
                events which we offer</li>
        </ul>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">4. Sharing of Information</h2>
        <p class="privacy-paragraph">We may share your personal information in the following situations:</p>
        <ul class="privacy-list">
            <li>With service providers: We may share your information with third-party service providers to
                perform services on our behalf, such as payment processing, data analysis, email delivery,
                hosting services, customer service, and marketing assistance.</li>
            <li>For business transfers: We may share or transfer your information in connection with, or during
                negotiations of, any merger, sale of company assets, financing, or acquisition of all or a
                portion of our business to another company.</li>
            <li>With affiliates: We may share your information with our affiliates, in which case we will
                require those affiliates to honor this privacy policy.</li>
            <li>With business partners: We may share your information with our business partners to offer you
                certain products, services or promotions.</li>
            <li>With your consent: We may disclose your personal information for any other purpose with your
                consent.</li>
        </ul>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">5. Data Retention</h2>
        <p class="privacy-paragraph">We will only retain your personal data for as long as necessary to fulfil
            the purposes we collected it for, including for the purposes of satisfying any legal, accounting, or
            reporting requirements. To determine the appropriate retention period for personal data, we consider
            the amount, nature, and sensitivity of the personal data, the potential risk of harm from
            unauthorised use or disclosure of your personal data, the purposes for which we process your
            personal data and whether we can achieve those purposes through other means, and the applicable
            legal requirements.</p>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">6. Your Rights</h2>
        <p class="privacy-paragraph">Under certain circumstances, you have rights under data protection laws in
            relation to your personal data. These include the right to:</p>
        <ul class="privacy-list">
            <li>Request access to your personal data.</li>
            <li>Request correction of your personal data.</li>
            <li>Request erasure of your personal data.</li>
            <li>Object to processing of your personal data.</li>
            <li>Request restriction of processing your personal data.</li>
            <li>Request transfer of your personal data.</li>
            <li>Right to withdraw consent.</li>
        </ul>
        <p class="privacy-paragraph">If you wish to exercise any of these rights, please contact us using the
            contact information provided at the end of this policy.</p>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">7. Security</h2>
        <p class="privacy-paragraph">The security of your personal information is important to us, but remember
            that no method of transmission over the Internet, or method of electronic storage is 100% secure.
            While we strive to use commercially acceptable means to protect your personal information, we cannot
            guarantee its absolute security. We have put in place appropriate security measures to prevent your
            personal data from being accidentally lost, used or accessed in an unauthorised way, altered or
            disclosed.</p>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">8. Changes to This Privacy Policy</h2>
        <p class="privacy-paragraph">We may update our Privacy Policy from time to time. We will notify you of
            any changes by posting the new Privacy Policy on this page and updating the "last updated" date at
            the top of this Privacy Policy. You are advised to review this Privacy Policy periodically for any
            changes.</p>
    </section>
    <section class="privacy-section">
        <h2 class="privacy-heading">9. Contact Us</h2>
        <p class="privacy-paragraph">If you have any questions about this Privacy Policy, please contact us:</p>
        <ul class="privacy-list">
            <li>By Email: <a class="text-decoration-none position-relative animated-link privacy-list_a" href="mailto:{{$b_email}}">{{$b_email}}</a></li>
        <li>By Phone Number: <a class="text-decoration-none position-relative animated-link privacy-list_a" href="tel:{{$b_number}}">{{ $b_number}}</a></li>
            <li>By Address: <a class="text-decoration-none position-relative animated-link privacy-list_a" href="{{$b_street_address}}">{{$b_area_address}}</a></li>
        </ul>
    </section>
</section>


@endsection