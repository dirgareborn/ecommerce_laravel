<!-- Navbar Start -->
<div class="container-fluid nav-bar bg-dark">
    <div class="container-xxl">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-0 px-4 m-0">
            <!-- Brand -->
            <a href="{{ route('beranda') }}"
                class="navbar-brand d-flex align-items-center text-center flex-shrink-0 justify-content-start px-2 px-sm-4">
                <div class="p-1 me-2 flex-shrink-0">
                    <img class="img-fluid" src="{{ url('logo_100x100.webp') }}" alt="Icon"
                        style="width: 60px; height: 60px;">
                </div>
                <h1 class="m-0 text-white brand-text d-none d-sm-inline">BPB UNM</h1>
            </a>


            <!-- Mobile Right Icons (Search + Cart + Toggle) -->
            <div class="d-flex align-items-center ms-auto d-lg-none">
                <!-- Search Icon -->
                <button class="btn btn-link text-orange me-2 p-0" id="mobileSearchBtn">
                    <i class="fas fa-search fa-lg"></i>
                </button>
                <!-- Cart Icon -->
                <a class="text-reset  d-none d-sm-inline  me-2" href="{{ url('cart') }}">
                    <i class="fas fa-shopping-cart fa-lg text-orange"></i>
                    <span class="badge rounded-pill bg-danger">
                        {{ $totalCartItems }}
                    </span>
                </a>
                <!-- Toggle Button -->
                <button class="navbar-toggler custom-toggler text-orange" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="{{ route('beranda') }}"
                        class="nav-item  nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>

                    <div class="nav-item dropdown">
                        <a href="#"
                            class="nav-link dropdown-toggle {{ request()->is('visi-misi', 'struktur-organisasi') ? 'active' : '' }}"
                            data-bs-toggle="dropdown">Tentang BPB</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="{{ url('visi-misi') }}" class="dropdown-item">Visi Misi</a>
                            <a href="{{ url('struktur-organisasi') }}" class="dropdown-item">Struktur Organisasi</a>
                        </div>
                    </div>
                    <!-- Test Mega Menu  -->
                    <!-- @include('front.partials._megamenu') -->

                    <!-- EndTest Mega Menu  -->
                    <div class="nav-item dropdown">
                        <a href="#"
                            class="nav-link dropdown-toggle {{ request()->is('kategori') || request()->is('kategori/*') ? 'active' : '' }}"
                            data-bs-toggle="dropdown">
                            Unit Usaha
                        </a>

                        <ul class="dropdown-menu rounded-0 m-0">

                            @foreach ($MenuCategories->where('parent_id', 0) as $parent)
                                @php
                                    $children = $MenuCategories->where('parent_id', $parent->id);
                                @endphp

                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle"
                                        href="{{ $children->count() ? '#' : url('kategori/' . $parent->url) }}">
                                        {{ ucfirst($parent->category_name) }}
                                    </a>

                                    {{-- CHILD --}}
                                    @if ($children->count())
                                        <ul class="dropdown-menu">
                                            @foreach ($children as $child)
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('kategori/' . $child->url) }}">
                                                        {{ ucfirst($child->category_name) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </li>
                            @endforeach

                        </ul>
                    </div>

                    <!-- Menu Baru -->
                    <a href="{{ route('kerjasama') }}"
                        class="nav-item nav-link {{ request()->is('kerjasama') ? 'active' : '' }}">Kerja Sama</a>
                    <a href="{{ route('dokumen.index') }}"
                        class="nav-item nav-link {{ request()->is('dokumen') ? 'active' : '' }}">Dokumen</a>

                    <!-- End Menu Baru -->
                    <a href="{{ url('kontak-kami') }}"
                        class="nav-item nav-link {{ request()->is('kontak-kami') ? 'active' : '' }}">Kontak</a>
                    @guest <a href="{{ route('login') }}" class="nav-item nav-link">Login</a> @endguest
                    @if (Auth::check())
                        <li class="nav-item dropdown"> <a
                                class=" nav-link dropdown-toggle d-flex align-items-center hidden-arrow"
                                data-bs-toggle="dropdown" href="#" id="navbarDropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="{{ is_user(Auth::user()->image) }}" class="rounded-circle" height="25"
                                    alt="user image" loading="lazy" /> </a>
                            <div class="dropdown-menu dropdown-menu-end rounded-0 m-0" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }} </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf </form> <a class="dropdown-item"
                                    href="{{ route('member.profil') }}">Profil</a> <a class="dropdown-item"
                                    href="{{ route('member.pesanan') }}">Daftar Pesanan</a>
                            </div>
                        </li>
                    @endif <!-- Isi minicart akan diganti via AJAX -->
                    <!-- Desktop Icons -->
                    <div class="d-none d-lg-flex align-items-center ms-3">
                        <!-- Search Icon -->
                        <button class="btn btn-link text-warning me-3 p-0" id="desktopSearchBtn">
                            <i class="fas fa-search fa-lg"></i>
                        </button>
                    </div>

                    <!-- Isi minicart akan diganti via AJAX -->
                    @if (Auth::check())
                        <div class="nav-item nav-link dropdown" id="appendMiniCartItems"> <!-- Cart Icon --> <a
                                class="text-reset me-3 d-none d-sm-inline" href="{{ url('cart') }}"> <i
                                    class="fas fa-shopping-cart"></i> <span
                                    class="badge rounded-pill badge-notification d-none d-sm-inline bg-danger">
                                    {{ $totalCartItems }} </span> </a>
                            <ul class="dropdown-menu rounded-0 m-0" role="menu"> @include('front.carts.minicart_items_inner') </ul>
                        </div>
                    @endif
        </nav>
    </div>
</div>
<!-- Fullscreen Search Overlay -->
<div id="searchOverlay" class="search-overlay">
    <div class="search-box">
        <form action="{{ route('global.search') }}" method="get"
            class="d-flex justify-content-center align-items-center w-100">
            <input type="text" name="q" class="form-control search-input" placeholder="Cari...." autofocus>
            <button type="button" class="btn-close ms-3" id="closeSearch"></button>
        </form>
    </div>
</div>
