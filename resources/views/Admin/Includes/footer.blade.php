@php
    use App\General\GeneralSettingsClass;
    $businessName = GeneralSettingsClass::getRequiredValue('_b_name') ?? 'Sydney Quality Meats';
@endphp

<footer class="footer">
    <div class="row">
        <div class="col-sm-6">
            <script>document.write(new Date().getFullYear())</script> © {{ $businessName }}
        </div>
        <div class="col-sm-6">
            <div class="text-sm-end d-none d-sm-block">
                Design & Develop By 
                <a href="https://gtechsol.com.au" target="_blank" class="text-decoration-none" style="font-weight: bold; color: inherit;">
                    G-Tech Sol Pty Ltd
                </a>
            </div>
        </div>
    </div>
</footer>

@include('Admin.Includes.scripts')
