<nav id="sidebar" aria-label="Main Navigation">
<div class="content-header bg-white-5">
    <a class="font-w600 text-dual" href="index.html">
        <i class="fa fa-circle-notch text-primary"></i>
        <span class="smini-hide">
                            <span class="font-w700 font-size-h5">WeFullFill</span>
                        </span>
    </a>
    <div>
        <a class="d-lg-none btn btn-sm btn-dual ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
            <i class="fa fa-fw fa-times"></i>
        </a>
    </div>
</div>

<div class="content-side content-side-full">
    <ul class="nav-main">
        <li class="nav-main-item">
            <a class="nav-main-link active" href="{{route('store.dashboard')}}">
                <i class="nav-main-link-icon si si-speedometer"></i>
                <span class="nav-main-link-name">Dashboard</span>
            </a>
        </li>
        <li class="nav-main-item open">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon si si-layers"></i>
                <span class="nav-main-link-name">Products</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('store.product.wefulfill')}}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">By WeFullfill</span>
                    </a>
                </li>

{{--                <li class="nav-main-item">--}}
{{--                    <a class="nav-main-link" href="{{route('store.product.wefulfill')}}">--}}
{{--                        <i class="nav-main-link-icon si si-bag"></i>--}}
{{--                        <span class="nav-main-link-name">By Aliexpress</span>--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('store.import_list')}}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">Import List</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('store.my_products')}}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">My Products</span>
                    </a>
                </li>

            </ul>
        </li>
        <li class="nav-main-item open">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                <i class="nav-main-link-icon si si-layers"></i>
                <span class="nav-main-link-name">Orders</span>
            </a>
            <ul class="nav-main-submenu open">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('store.product.wefulfill')}}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">By WeFullfill</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('store.product.wefulfill')}}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">By Aliexpress</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{route('store.import_list')}}">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">Payment History</span>
                    </a>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link" href="">
                        <i class="nav-main-link-icon si si-bag"></i>
                        <span class="nav-main-link-name">Tracking Info</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon si si-wrench"></i>
                <span class="nav-main-link-name">Customer</span>
            </a>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link" href="{{route('store.index')}}">
                <i class="nav-main-link-icon si si-wrench"></i>
                <span class="nav-main-link-name">Settings</span>
            </a>
        </li>

        <li class="nav-main-item">
            <a class="nav-main-link" href="#">
                <i class="nav-main-link-icon si si-wrench"></i>
                <span class="nav-main-link-name">Help Center</span>
            </a>
        </li>

    </ul>
</div>
</nav>

<header id="page-header">
    <div class="content-header">
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
                <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>

            <button type="button" class="btn btn-sm btn-dual d-sm-none" data-toggle="layout" data-action="header_search_on">
                <i class="si si-magnifier"></i>
            </button>
            <form class="d-none d-sm-inline-block" action="" method="POST">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="page-header-search-input2">
                    <div class="input-group-append">
                                    <span class="input-group-text bg-body border-0">
                                        <i class="si si-magnifier"></i>
                                    </span>
                    </div>
                </div>
            </form>
        </div>



        <div class="d-flex align-items-center">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block ml-2">
                <button type="button" class="btn btn-sm btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded" src="{{ asset('assets/media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 18px;">
                    <span class="d-none d-sm-inline-block ml-1">Adam</span>
                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-user-dropdown">
                    <div class="p-3 text-center bg-primary">
                        <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('assets/media/avatars/avatar10.jpg') }}" alt="">
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_profile.html">
                            <span>Profile</span>
                            <span>
                                            <span class="badge badge-pill badge-success">1</span>
                                            <i class="si si-user ml-1"></i>
                                        </span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                            <span>Settings</span>
                            <i class="si si-settings"></i>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="op_auth_signin.html">
                            <span>Log Out</span>
                            <i class="si si-logout ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block ml-2">
                <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="si si-bell"></i>
                    <span class="badge badge-primary badge-pill">6</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-2 bg-primary text-center">
                        <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
                    </div>
                    <ul class="nav-items mb-0">
                        <li>
                            <a class="text-dark media py-2" href="javascript:void(0)">
                                <div class="mr-2 ml-3">
                                    <i class="fa fa-fw fa-check-circle text-success"></i>
                                </div>
                                <div class="media-body pr-2">
                                    <div class="font-w600">You have a new follower</div>
                                    <small class="text-muted">15 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark media py-2" href="javascript:void(0)">
                                <div class="mr-2 ml-3">
                                    <i class="fa fa-fw fa-plus-circle text-info"></i>
                                </div>
                                <div class="media-body pr-2">
                                    <div class="font-w600">1 new sale, keep it up</div>
                                    <small class="text-muted">22 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark media py-2" href="javascript:void(0)">
                                <div class="mr-2 ml-3">
                                    <i class="fa fa-fw fa-times-circle text-danger"></i>
                                </div>
                                <div class="media-body pr-2">
                                    <div class="font-w600">Update failed, restart server</div>
                                    <small class="text-muted">26 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark media py-2" href="javascript:void(0)">
                                <div class="mr-2 ml-3">
                                    <i class="fa fa-fw fa-plus-circle text-info"></i>
                                </div>
                                <div class="media-body pr-2">
                                    <div class="font-w600">2 new sales, keep it up</div>
                                    <small class="text-muted">33 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark media py-2" href="javascript:void(0)">
                                <div class="mr-2 ml-3">
                                    <i class="fa fa-fw fa-user-plus text-success"></i>
                                </div>
                                <div class="media-body pr-2">
                                    <div class="font-w600">You have a new subscriber</div>
                                    <small class="text-muted">41 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark media py-2" href="javascript:void(0)">
                                <div class="mr-2 ml-3">
                                    <i class="fa fa-fw fa-check-circle text-success"></i>
                                </div>
                                <div class="media-body pr-2">
                                    <div class="font-w600">You have a new follower</div>
                                    <small class="text-muted">42 min ago</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="p-2 border-top">
                        <a class="btn btn-sm btn-light btn-block text-center" href="javascript:void(0)">
                            <i class="fa fa-fw fa-arrow-down mr-1"></i> Load More..
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header bg-white">
        <div class="content-header">
            <form class="w-100" action="be_pages_generic_search.html" method="POST">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-danger" data-toggle="layout" data-action="header_search_off">
                            <i class="fa fa-fw fa-times-circle"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                </div>
            </form>
        </div>
    </div>
    <!-- END Header Search -->

    <!-- Header Loader -->
    <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-white">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>

