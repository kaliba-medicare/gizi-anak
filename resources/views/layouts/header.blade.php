<header id="page-topbar">
    <div class="navbar-header " >
        <div class="d-flex">
            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ri-menu-2-line align-middle"></i>
            </button>
          <div class="page-title-box align-self-center d-none d-md-block">
            <h4 class="page-title mb-0">Lombok Utara</h4>
          </div>
          <!-- end page title -->
        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            @auth
            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="rounded-circle header-profile-user avatar-sm bg-primary d-flex align-items-center justify-content-center text-white">
                        <i class="mdi mdi-account"></i>
                    </div>
                    <span class="d-none d-xl-inline-block ms-1">{{ Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- User Info -->
                    <div class="dropdown-header">
                        <h6 class="text-overflow m-0">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    
                    <!-- Profile & Settings -->
                    {{-- <a class="dropdown-item" href="#">
                        <i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> 
                        <span class="align-middle">Profile</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> 
                        <span class="align-middle">Settings</span>
                    </a>
                    <div class="dropdown-divider"></div> --}}
                    
                    <!-- Logout -->
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    
                    <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i>
                        <span class="align-middle">Logout</span>
                    </a>
                </div>
            </div>
            @else
            <div class="d-inline-block">
                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-login me-1"></i>Login
                </a>
            </div>
            @endauth

        </div>
    </div>
</header>