<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ url('admin/dashboard') }}" class="brand-link">
        <img src="{{ url('logo_100x100.webp') }}" alt="BPB" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">BPB UNM</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(!empty(Auth::guard('admin')->user()->image))
                <img src="{{ url('admin/images/avatars/'.Auth::guard('admin')->user()->image) }}" class="img-circle elevation-2" alt="User Image">
                @else    
                <img src="{{ url('admin/images/default-150x150.png') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
            </div>
        </div>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ request()->is('admin/update-detail','admin/update-password') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Manajemen Admin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        
                        <li class="nav-item">
                            <a href="{{ url('admin/update-password') }}" class="nav-link {{ request()->is('admin/update-password') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Update Kata Sandi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/update-detail') }}" class="nav-link {{ request()->is('admin/update-detail') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Update Detail Admin</p>
                            </a>
                        </li>
                        @if(Auth::guard('admin')->user()->type=="admin")
                        <li class="nav-item">
                            <a href="{{ url('admin/subadmins') }}" class="nav-link {{ request()->is('admin/subadmins') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Daftar Admin
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->is('admin/roles') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @if(Auth::guard('admin')->user()->type=="admin")
                <li class="nav-item">
                    <a href="{{ url('admin/account-banks') }}" class="nav-link {{ request()->is('admin/account-banks') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill"></i> 
                        <p>
                            Akun Bank
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Manajemen Halaman <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/cms-pages') }}" class="nav-link {{ request()->is('admin/update-password') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>CMS</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link {{ request()->is('admin/update-detail','admin/update-password') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Manajemen Katalog
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/categories') }}" class="nav-link {{ request()->is('admin/update-password') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/products') }}" class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/banners') }}" class="nav-link {{ request()->is('admin/banners') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Banner</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin/coupons') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Kupon Subsidi
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-image"></i>
                        <p>
                         Manajemen Customer <i class="right fas fa-angle-left"></i>
                     </p>
                 </a>
                 <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('admin/customers') }}" class="nav-link {{ request()->is('admin/customers') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Customer</p>
                        </a>
                    </li>
                </ul>
            </li>   
            <li class="nav-item menu-open">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-image"></i>
                    <p>
                     Manajemen Order <i class="right fas fa-angle-left"></i>
                 </p>
             </a>
             <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ url('admin/orders') }}" class="nav-link {{ request()->is('admin/orders') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Orderan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin/calendar') }}" class="nav-link {{ request()->is('admin/calendar') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Kalender Penggunaan</p>
                    </a>
                </li>
            </ul>
        </li>  
        <li class="nav-header">PETUNJUK PENGGUNAAN</li>
        <li class="nav-item">
            <a href="{{ route('admin.files.index') }}" class="nav-link">
                <i class="nav-icon fas fa-file"></i>
                <p>File Manager</p>
            </a>
        </li>

        <li class="nav-header">SYSTEM</li>
        <li class="nav-item">
            <a href="{{ route('admin.system') }}" class="nav-link">
                <i class="nav-icon fas fa-info-circle"></i>
                <p>Informasi & Log</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.database') }}" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>Database</p>
          </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon far fa-circle text-warning"></i>
            <p>Warning</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon far fa-circle text-info"></i>
            <p>Informational</p>
        </a>
    </li>
</ul>
</nav>

</div>

</aside>