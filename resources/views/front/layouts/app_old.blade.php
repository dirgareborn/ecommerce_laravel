<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <title>BPB - Universitas Negeri Makassar</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:site_name" content="{{ \URL::to('') }}">
    <meta property="og:title" content="{{ $page_title ?? '' }}">
    <meta property="og:description" content="{{ $page_description ?? 'Website Resmi BPB UNM' }}. ">
    <meta property="og:image" content="{{ is_logo($page_image ?? '') }}?auto=format&amp;fit=max&amp;w=1200">
    <meta property="og:image:alt" content="{{ is_logo($page_image ?? '') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page_title ?? '' }}">
    <meta name="twitter:description" content="{{ $page_description ?? 'Website Resmi BPB UNM' }}. ">
    <meta name="twitter:image" content="{{ is_logo($page_image ?? '') }}?auto=format&amp;fit=max&amp;w=1200">
    <link rel="alternate" href="/feed.xml" type="application/atom+xml" data-title="{{ Request::url() }}">
    <meta name="facebook-domain-verification" content="w5e39xmuhdt35pjpezg5pkif7f501x" />
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ url('favicon.ico') }}" />
    <link rel="mask-icon" href="{{ url('favicon.ico') }}" color="#5bbad5">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
    rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ url('front/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ url('front/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ url('front/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ url('front/css/custom_style.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/themes/smoothness/jquery-ui.min.css"
    rel="stylesheet" />
    <!-- Scripts -->
    @stack('style')
    <style>
.brand-text {
    font-size: 24px;
    font-weight: 700;
    white-space: nowrap;
}
/* Hamburger Animation */
.hamburger {
    width: 28px;
    height: 22px;
    position: relative;
    display: inline-block;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}
.hamburger span {
    position: absolute;
    height: 3px;
    width: 100%;
    background: #28a745;
    border-radius: 5px;
    left: 0;
    transition: all 0.35s ease-in-out;
}
.hamburger span:nth-child(1) { top: 0; }
.hamburger span:nth-child(2) { top: 9px; }
.hamburger span:nth-child(3) { top: 18px; }
.hamburger.active span:nth-child(1) {
    transform: rotate(45deg);
    top: 9px;
    background: #ff7f00;
}
.hamburger.active span:nth-child(2) {
    opacity: 0;
}
.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg);
    top: 9px;
    background: #ff7f00;
}


/* SEARCH OVERLAY */
.search-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.85);
    z-index: 2000;
    justify-content: center;
    align-items: center;
}
.search-overlay.active {
    display: flex;
}
.search-box {
    width: 80%;
    max-width: 600px;
}
.search-input {
    width: 100%;
    padding: 1rem 1.5rem;
    font-size: 1.25rem;
    border-radius: 50px;
    border: none;
}
.search-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px #198754;
}
/* Override aman: ganti bg SVG default dan tampilkan X putih besar */
#searchOverlay .btn-close {
  /* hapus background SVG bawaan supaya tidak konflik */
  background-image: none !important;
  background-color: rgba(255,255,255,0.06); /* sedikit latar agar tombol terlihat */
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: none;
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 20px;
  right: 20px;
  cursor: pointer;
  z-index: 2001;
  transition: transform .15s ease, background-color .15s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.35);
}

/* Buat tanda silang putih menggunakan pseudo-element */
#searchOverlay .btn-close::after {
  content: "\00D7"; /* × */
  color: #ffffff;
  font-size: 24px;
  line-height: 1;
  display: block;
  transform: translateY(-1px);
}

/* Hover effect */
#searchOverlay .btn-close:hover {
  transform: rotate(90deg);
  background-color: rgba(255,255,255,0.12);
}
/* Responsif: pastikan full height di desktop dan tetap proporsional di HP */
@media (max-width: 767px) {
    .navbar-brand h1 {
        font-size: 20px;
    }
}

@media (min-width: 768px) {
    .header .col-md-6 {
        height: 100vh;
    }
}
/* TOGGLER COLOR */
.custom-toggler {
    border: none;
}
.custom-toggler .navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='green' stroke-width='3' stroke-linecap='round' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
}
.custom-toggler.active .navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='orange' stroke-width='3' stroke-linecap='round' d='M6 6l18 18M6 24L24 6'/%3E%3C/svg%3E");
}th: 767.98px) {
    .header .col-12 {
        height: auto;
    }
    .header-carousel img {
        min-height: 50vh;
    }

     .navbar-brand .brand-text {
        font-size: 18px;
        display: inline-block;
        white-space: nowrap;
    }
    .navbar .icon img {
        width: 26px !important;
        height: 26px !important;
    }
    /* agar logo dan teks tidak terdorong oleh tombol kanan */
    .navbar-brand {
        max-width: 60%;
        overflow: visible;
    }
}

/* Posisi badge di atas ikon keranjang */
.nav-item .fa-shopping-cart {
  position: relative;
  font-size: 20px;
}

.nav-item .badge-notification {
  position: absolute;
  top: 15px;   /* naik ke atas */
  right: -1px; /* geser sedikit ke kanan */
  font-size: 11px;
  /*padding: 3px 6px;*/
  border-radius: 50%;
  line-height: 1;
}
</style>
    @laravelPWA
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->
    @include('front.partials.navbar')
    @if (Route::is('beranda') || Request::is('/'))
    @include('front.partials.slider')
    @include('front.partials.search')
    @endif
    @yield('content')
    @if (Route::is('beranda'))
    @include('front.partials.testimonial')
    @endif
    @include('front.partials.footer')
    @include('front.layouts.menu-bottom')
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ url('front/lib/wow/wow.min.js') }}"></script>
<script src="{{ url('front/lib/easing/easing.min.js') }}"></script>
<script src="{{ url('front/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ url('front/lib/owlcarousel/owl.carousel.min.js') }}"></script>

<!-- Template Javascript -->
<script src="{{ url('front/js/main.js') }}"></script>
<script src="{{ url('front/js/custom.js') }}"></script>
<script src="https://unpkg.com/feather-icons@4.29.2/dist/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.2/sweetalert2.min.js" integrity="sha512-JWPRTDebuCWNZTZP+EGSgPnO1zH4iie+/gEhIsuotQ2PCNxNiMfNLl97zPNjDVuLi9UWOj82DEtZFJnuOdiwZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stack('scripts')

<script>
    let prevScrollpos = window.pageYOffset;
    let ticking = false;

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                const currentScrollPos = window.pageYOffset;
                document.getElementById("navbarBottom").style.bottom =
                prevScrollpos > currentScrollPos ? "0" : "-70px";
                prevScrollpos = currentScrollPos;
                ticking = false;
            });
            ticking = true;
        }
    });

document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("searchOverlay");
    const openBtns = [document.getElementById("desktopSearchBtn"), document.getElementById("mobileSearchBtn")];
    const closeBtn = document.getElementById("closeSearch");
    const toggler = document.querySelector(".custom-toggler");

    openBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener("click", () => overlay.classList.add("active"));
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener("click", () => overlay.classList.remove("active"));
    }

    if (toggler) {
        toggler.addEventListener("click", () => toggler.classList.toggle("active"));
    }
});
</script>
</body>

</html>
