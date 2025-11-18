<div class="col-lg-4 col-md-5 col-sm-12 wow fadeInLeft" data-wow-delay="0.1s" style="margin-top: 0 !important;">
    {{-- Header User --}}
    <div class="card border-0 shadow-sm rounded-3 mt-2" style="margin-top:0 !important;">
        <div class="card-header bg-primary text-white text-center py-2 rounded-top">
            <h6 class="mb-0 fw-semibold text-white ">
                <i class="bi bi-person-circle me-2"></i> Halo, {{ Auth::user()->name }}
            </h6>
        </div>

        {{-- Sidebar Menu --}}
        <ul class="list-group list-group-flush">
            {{-- Profil --}}
            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('profil') ? 'bg-primary text-white' : '' }}">
                <a class="flex-grow-1 nav-link p-0 {{ request()->is('profil') ? 'text-white' : 'text-dark' }}" href="{{ url('/profil') }}">
                    Profil
                </a>
                <i class="bi bi-person-fill {{ request()->is('profil') ? 'text-white' : 'text-primary' }}"></i>
            </li>

            {{-- Update Password --}}
            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('account') ? 'bg-primary text-white' : '' }}">
                <a class="flex-grow-1 nav-link p-0 {{ request()->is('account') ? 'text-white' : 'text-dark' }}" href="{{ url('/account') }}">
                    Update Password
                </a>
                <i class="bi bi-key-fill {{ request()->is('account') ? 'text-white' : 'text-warning' }}"></i>
            </li>

            {{-- Testimonial --}}
            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('testimonial') ? 'bg-primary text-white' : '' }}">
                <a class="flex-grow-1 nav-link p-0 {{ request()->is('testimonial') ? 'text-white' : 'text-dark' }}" href="{{ url('/testimonial') }}">
                    Testimonial
                </a>
                <i class="bi bi-chat-dots-fill {{ request()->is('testimonial') ? 'text-white' : 'text-success' }}"></i>
            </li>

            {{-- Daftar Pesanan --}}
            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('daftar-pesanan') ? 'bg-primary text-white' : '' }}">
                <a class="flex-grow-1 nav-link p-0 {{ request()->is('daftar-pesanan') ? 'text-white' : 'text-dark' }}" href="{{ url('/daftar-pesanan') }}">
                    Daftar Pesanan
                </a>
                <div class="d-flex align-items-center">
        <span class="badge 
            {{ request()->is('daftar-pesanan') ? 'bg-white text-primary' : 'bg-primary text-white' }} 
            rounded-pill me-2 shadow-sm">
            {{ $orderCount ?? 0 }}
        </span>
        <i class="bi bi-basket-fill 
            {{ request()->is('daftar-pesanan') ? 'text-white' : 'text-info' }}"></i>
    </div>
            </li>
        </ul>
    </div>
</div>
