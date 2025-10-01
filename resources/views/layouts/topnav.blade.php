<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a wire:navigate.prefetch class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="mdi mdi-view-dashboard-outline"></i> Dashboard
                        </a>
                    </li>

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button">
                            <i class="uim uim-comment-message"></i> Data Gizi Anak <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                    role="button">
                                    Data Master <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a wire:navigate.prefetch href="{{ route('admin.data_desa') }}"
                                        class="dropdown-item">Data Desa</a>
                                    <a wire:navigate.prefetch href="{{ route('admin.data_posyandu') }}"
                                        class="dropdown-item"> Data Posyandu</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                    role="button">
                                    Data Gizi <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a wire:navigate.prefetch href="{{ route('admin.type') }}"
                                        class="dropdown-item">Type Gizi</a>
                                    <a href="{{ route('admin.status_gizi') }}" class="dropdown-item">Data Status Gizi
                                        Anak</a>
                                </div>
                            </div>
                        </div>
                    </li> --}}

                    <li class="nav-item">
                        <a wire:navigate.prefetch class="nav-link" href="{{ route('admin.data_desa') }}">
                            <i class="mdi mdi-home-city"></i> Data Desa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate.prefetch class="nav-link" href="{{ route('admin.data_posyandu') }}">
                            <i class="mdi mdi-home-flood"></i> Data Posyandu
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate.prefetch class="nav-link" href="{{ route('admin.type') }}">
                            <i class="mdi mdi-family-tree"></i> Type
                        </a>
                    </li>

                    <li class="nav-item">
                        <a wire:navigate.prefetch class="nav-link" href="{{ route('admin.status_gizi') }}">
                            <i class="mdi mdi-human-female-girl"></i> Status Gizi
                        </a>
                    </li>



                </ul>
            </div>
        </nav>
    </div>
</div>
