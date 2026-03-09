<!-- Stylesheets -->
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('client/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/swiper-bundle.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/datatable.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/spacing.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/cookie-consent.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/prescription.css') }}?v={{ $setting?->version }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">



<link rel="stylesheet" href="{{ asset('client/css/style.css') }}?v={{ $setting?->version }}">
@if (session()->get('text_direction') == 'rtl')
    <link rel="stylesheet" href="{{ asset('client/css/rtl.css') }}?v={{ $setting?->version }}">
@endif
<link rel="stylesheet" href="{{ asset('client/css/responsive.css') }}?v={{ $setting?->version }}">


<link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('client/css/jquery-ui.css') }}">

<style>
    :root {
        --colorPrimary: {{ $setting->theme_one }} !important;
        --colorSecondary: {{ $setting->theme_two }} !important;
    }

    @if ($setting->tawk_status != 'active')
        .scroll-top {
            bottom: 26px;
        }
    @endif
</style>
