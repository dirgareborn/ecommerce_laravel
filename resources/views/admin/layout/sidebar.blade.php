<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ url('logo_100x100.webp') }}" alt="BPB" class="brand-image img-circle elevation-3" style="opacity:.8">
        <span class="brand-text font-weight-light">BPB UNM</span>
    </a>

    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::guard('admin')->user()->image ? url('admin/images/avatars/'.Auth::guard('admin')->user()->image) : url('admin/images/default-150x150.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @foreach(config('menu') as $item)

                    @if(isset($item['header']) && $item['header'])
                        <li class="nav-header">{{ $item['title'] }}</li>
                        @continue
                    @endif

                    @if(isset($item['permission']) && Auth::guard('admin')->user()->type != $item['permission'])
                        @continue
                    @endif

                    @if(isset($item['submenu']))
                        <li class="nav-item has-treeview {{ collect($item['submenu'])->pluck('route')->contains(fn($r) => request()->routeIs($r)) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ collect($item['submenu'])->pluck('route')->contains(fn($r) => request()->routeIs($r)) ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                <p>{{ $item['title'] }} <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach($item['submenu'] as $sub)
                                    @if(isset($sub['permission']) && Auth::guard('admin')->user()->type != $sub['permission'])
                                        @continue
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ route($sub['route']) }}" class="nav-link {{ isActiveRoute($sub['route']) }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ $sub['title'] }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link {{ isActiveRoute($item['route']) }}">
                                <i class="nav-icon {{ $item['icon'] }}"></i>
                                <p>{{ $item['title'] }}</p>
                            </a>
                        </li>
                    @endif

                @endforeach

            </ul>
        </nav>
    </div>
</aside>
