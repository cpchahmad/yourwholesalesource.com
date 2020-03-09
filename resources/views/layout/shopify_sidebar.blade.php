<nav id="sidebar">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="side-header side-content bg-white-op">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times"></i>
                </button>
                <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
                <div class="btn-group pull-right">
                    <button class="btn btn-link text-gray dropdown-toggle" data-toggle="dropdown" type="button">
                        <i class="si si-drop"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right font-s13 sidebar-mini-hide">
                        <li>
                            <a data-toggle="theme" data-theme="default" tabindex="-1" href="javascript:void(0)">
                                <i class="fa fa-circle text-default pull-right"></i> <span class="font-w600">Default</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" tabindex="-1" href="javascript:void(0)">
                                <i class="fa fa-circle text-amethyst pull-right"></i> <span class="font-w600">Amethyst</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="theme" data-theme="assets/css/themes/city.min.css" tabindex="-1" href="javascript:void(0)">
                                <i class="fa fa-circle text-city pull-right"></i> <span class="font-w600">City</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="theme" data-theme="assets/css/themes/flat.min.css" tabindex="-1" href="javascript:void(0)">
                                <i class="fa fa-circle text-flat pull-right"></i> <span class="font-w600">Flat</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="theme" data-theme="assets/css/themes/modern.min.css" tabindex="-1" href="javascript:void(0)">
                                <i class="fa fa-circle text-modern pull-right"></i> <span class="font-w600">Modern</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" tabindex="-1" href="javascript:void(0)">
                                <i class="fa fa-circle text-smooth pull-right"></i> <span class="font-w600">Smooth</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <a class="h5 text-white" href="index.html">
                    <i class="fa fa-circle-o-notch text-primary"></i> <span class="h4 font-w600 sidebar-mini-hide">Edropship</span>
                </a>
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="side-content side-content-full">
{{--                <ul class="nav-main">--}}
{{--                    <li>--}}
{{--                        <a class="active" href="/"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard</span></a>--}}
{{--                    </li>--}}

{{--                    <li class="nav-main-heading"><span class="sidebar-mini-hide">User Interface</span></li>--}}
{{--                    <li>--}}
{{--                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bar-chart"></i><span class="sidebar-mini-hide">Products</span></a>--}}
{{--                        <ul>--}}
{{--                            <li>--}}
{{--                                <a href="{{ route('product.create') }}">Add New</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="nav-item" href="{{ route('product.all')}}">View All</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}

{{--                    <li class="nav-main-heading"><span class="sidebar-mini-hide">Settings</span></li>--}}
{{--                    <li>--}}
{{--                        <a class="nav-item" href="{{route('sales-managers.index')}}"><i class="si si-user"></i><span class="sidebar-mini-hide">Sales Managers</span></a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="nav-item"  href="{{ route('category.create') }}"><i class="si si-camcorder"></i><span class="sidebar-mini-hide">Categories</span></a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="nav-item"  href="{{route('zone.index')}}"><i class="si si-map"></i><span class="sidebar-mini-hide">Shipping Zones</span></a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a class="nav-item"  href="{{ route('default_info') }}"><i class="si si-info"></i><span class="sidebar-mini-hide">Default Info</span></a>--}}
{{--                    </li>--}}

{{--                </ul>--}}
            </div>
            <!-- END Side Content -->
        </div>
        <!-- Sidebar Content -->
    </div>
    <!-- END Sidebar Scroll Container -->
</nav>
