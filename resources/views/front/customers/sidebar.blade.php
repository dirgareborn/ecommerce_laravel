<div class="col-lg-4 col-md-5 col-sm-12 wow fadeInLeft" data-wow-delay="0.1s">

    <div class="card border-0 shadow-sm rounded-3 mt-2">

        {{-- Header --}}
        <div class="card-header bg-primary text-white text-center py-2 rounded-top">
            <h6 class="mb-0 fw-semibold text-white">
                <i class="bi bi-person-circle me-2"></i>
                Halo, {{ Auth::user()->name }}
            </h6>
        </div>

        {{-- Menu --}}
        <ul class="list-group list-group-flush">

            @foreach(config('menumember') as $menu)

            @php
            $isActive = request()->routeIs($menu['route']);

            @endphp

            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
            {{ $isActive ? 'bg-primary text-white' : '' }}">

            {{-- Label --}}
            <a href="{{ route($menu['route']) }}"
            class="flex-grow-1 nav-link p-0 {{ $isActive ? 'text-white' : 'text-primary' }}">
            {{ $menu['label'] }}
        </a>

        {{-- Badge + Icon --}}
        <div class="d-flex align-items-center">


            @if(!empty($menu['badge']) && function_exists($menu['badge']))
            @php $count = $menu['badge'](); 
            @endphp
            @if($count > 0)
            <span class="ms-2 px-2 py-1 fs-7 badge bg-danger rounded-circle">
                {{ $count }}
            </span>

            @endif
            @endif

            <i class="{{ $menu['icon'] }} {{ $isActive ? 'text-white' : $menu['icon_color'] }}"></i>

        </div>

    </li>

    @endforeach

</ul>
</div>

</div>
