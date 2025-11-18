<div 
    class="w-100 d-flex align-items-center" 
    style="
        background:  linear-gradient(rgba(0, 31, 77, 0.7), rgba(0, 31, 77, 0.7)),
        url('{{ asset('front/img/header.webp') }}') center/cover no-repeat;
        height: 260px;
    "
>
    <div class="container text-white">
        <!-- Breadcrumb Modern -->
        <nav aria-label="breadcrumb" class="animated fadeIn">
                <ol class="breadcrumb text-uppercase mb-0">
                    <li class="breadcrumb-item">
                            <a href="/">
                                Beranda
                            </a>
                        </li>
                    @for ($i = 1; $i <= count(Request::segments()); $i++)
                        <li class="breadcrumb-item">
                            <a href="{{ URL::to(implode('/', array_slice(Request::segments(), 0, $i, true))) }}">
                                {{ strtoupper(Request::segment($i)) }}
                            </a>
                        </li>
                    @endfor
                </ol>
            </nav>
        <!-- Title -->
        <h1 class="fw-bold mb-0 text-white">{{ $page_title ?? '' }}</h1>
    </div>
</div>
