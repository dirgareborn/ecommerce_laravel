
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'BPB - Universitas Negeri Makassar' }}</title>

    <meta name="robots" content="index, follow">
    <meta name="author" content="{{ app_author() }}">
    <meta name="version" content="{{ app_version() }}">
    <meta name="last-updated" content="{{ app_last_updated() }}">
    <meta name="theme-color" content="#192f59">

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Badan Pengembangan Bisnis UNM",
      "url": "{{ url('https://bpb.unm.ac.id') }}",
      "logo": "{{ is_logo($page_image ?? '') }}",
      "sameAs": [
        "https://www.facebook.com/bpb.unm",
        "https://x.com/bpb.unm",
        "https://www.instagram.com/bpb.unm"
      ]
    }
    </script>
<meta name="description" content="{{ $page_description ?? 'Website Resmi Badan Pengembangan Bisnis Universitas Negeri Makassar' }}">
    <meta name="keywords" content="Universitas Negeri Makassar">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:site_name" content="{{ url('') }}">
    <meta property="og:title" content="{{ $page_title ?? '' }}">
    <meta property="og:description" content="{{ $page_description ?? 'Website Resmi Badan Pengembangan Bisnis Universitas Negeri Makassar' }}">
    <meta property="og:image" content="{{ is_logo($page_image ?? '') }}?auto=format&fit=max&w=1200">
    <meta property="og:image:alt" content="{{ is_logo($page_image ?? '') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page_title ?? '' }}">
    <meta name="twitter:description" content="{{ $page_description ?? 'Website Resmi Badan Pengembangan Bisnis Universitas Negeri Makassar' }}">
    <meta name="twitter:image" content="{{ is_logo($page_image ?? '') }}?auto=format&fit=max&w=1200">
    <meta name="facebook-domain-verification" content="w5e39xmuhdt35pjpezg5pkif7f501x">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ url('favicon.ico') }}">
    <link rel="mask-icon" href="{{ url('favicon.ico') }}" color="#5bbad5">

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Libraries & Styles --}}
    <link href="{{ asset('front/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/custom_style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/slider.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/chat-widget.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/themes/smoothness/jquery-ui.min.css" rel="stylesheet">
    @stack('style')
   
</head>
<body>
<div class="container-fluid bg-white p-0">
    {{-- Spinner --}}
    <div id="spinner" class="show d-flex justify-content-center align-items-center position-fixed w-100 vh-100 bg-white" style="z-index:9999;">
        <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    {{-- Navbar --}}
    @include('front.layouts.top-header')
    @include('front.partials.navbar')

    {{-- Homepage Hero & Search --}}
    @if (Route::is('beranda') || Request::is('/'))
        @include('front.partials.slider')
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Homepage Testimonials --}}
    @if (Route::is('beranda'))
        @include('front.partials.testimonial')
        @include('front.partials.mitra')
    @endif

    {{-- Footer --}}
    @include('front.partials.footer')
    @include('front.layouts.menu-bottom')

    {{-- Back to Top --}}
    <!-- <a href="#" class="btn btn-lg btn-warning btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
</div>



<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('front/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('front/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('front/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('front/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front/js/main.js') }}"></script>
<script src="{{ asset('front/js/custom.js') }}"></script>
<script src="https://unpkg.com/feather-icons@4.29.2/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('front/js/chat-widget.js') }}"></script>
@stack('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    if (!localStorage.getItem("cookieAccepted")) {
        document.getElementById("cookieConsent").style.display = "block";
    }

    document.getElementById("acceptCookies").addEventListener("click", function () {
        localStorage.setItem("cookieAccepted", "yes");
        document.getElementById("cookieConsent").style.display = "none";
    });
});
</script>


@include('front.partials.chat')
<div id="toastContainer" class="position-fixed top-50 start-50 translate-middle" style="z-index: 2000;">
    <div id="centerToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none; min-width: 250px; max-width: 350px;">
        <div class="d-flex">
            <div class="toast-body text-center fw-semibold" id="centerToastBody">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<div id="cookieConsent" class="cookie-consent">
    <p>
        We are using cookies to give you the best experience. 
        You can find out more about which cookies we are using 
        or switch them off in privacy settings.
    </p>
    <div class="cookie-actions">
        <button id="acceptCookies" class="btn-accept">Accept</button>
        <a href="/kebijakan-cookies" class="btn-settings">Privacy Settings</a>
    </div>
</div>
</body>
</html>
