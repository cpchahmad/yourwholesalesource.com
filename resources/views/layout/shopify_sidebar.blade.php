<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header bg-white-5">
        <a class="font-w600 text-dual">
            <span class=""><img src="https://yourwholesalesource.com/wp-content/uploads/2018/07/footer-logo-300x106.png" class="w-75" alt=""></span>
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
                <a class="nav-main-link active" href="{{route('users.dashboard')}}">
                    <i class="nav-main-link-icon si si-speedometer"></i>
                    <span class="nav-main-link-name">Dashboard</span>
                </a>
            </li>


            <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-layers"></i>
                    <span class="nav-main-link-name">Products</span>
                </a>
                <ul class="nav-main-submenu open">
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{route('users.product.wefulfill')}}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Awareness Drop Shipping Products</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        @php  $categories = \App\Category::orderBy('title')->get(); @endphp
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                            <i class="nav-main-link-icon si si-layers"></i>
                            <span class="nav-main-link-name">Categories</span>
                        </a>
                        <ul class="nav-main-submenu open">
                           @foreach($categories as $category)
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{route('users.product.wefulfill')}}?category={{$category->title}}">
                                        <i class="nav-main-link-icon si si-bag"></i>
                                        <span class="nav-main-link-name">{{ $category->title }}</span>
                                    </a>
                                </li>
                           @endforeach
                        </ul>
                    </li>
{{--                    <li class="nav-main-item">--}}
{{--                        <a class="nav-main-link" href="{{route('users.product.dropship')}}">--}}
{{--                            <i class="nav-main-link-icon si si-bag"></i>--}}
{{--                            <span class="nav-main-link-name">My Dropship Products</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                </ul>
            </li>

            <li class="nav-main-item">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-layers"></i>
                    <span class="nav-main-link-name">Orders</span>
                </a>
                <ul class="nav-main-submenu open">
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{route('users.custom.orders')}}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Orders</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{route('users.files')}}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Imported Files</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{route('users.refunds')}}">
                            <i class="nav-main-link-icon si si-bag"></i>
                            <span class="nav-main-link-name">Refunds</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link"  href="{{route('store.user.wallet.show')}}">
                    <i class="nav-main-link-icon si si-wallet"></i>
                    <span class="nav-main-link-name">Wallet</span>
                </a>
            </li>
{{--            <li class="nav-main-item">--}}
{{--                <a class="nav-main-link"  href="{{route('users.wishlist')}}">--}}
{{--                    <i class="nav-main-link-icon fa fa-heart"></i>--}}
{{--                    <span class="nav-main-link-name">Wishlist</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-main-item">--}}
{{--                <a class="nav-main-link" href="{{route('users.dropship.requests')}}">--}}
{{--                    <i class="nav-main-link-icon fa fa-shipping-fast"></i>--}}
{{--                    <span class="nav-main-link-name">Dropship Request</span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="nav-main-item" style="display: none">
                <a class="nav-main-link active" href="{{route('users.stores')}}">
                    <i class="nav-main-link-icon si si-share"></i>
                    <span class="nav-main-link-name">Stores</span>
                </a>
            </li>

{{--            <li class="nav-main-item">--}}
{{--                <a class="nav-main-link" href="{{route('users.notifications')}}">--}}
{{--                    <i class="nav-main-link-icon fa fa-sticky-note"></i>--}}
{{--                    <span class="nav-main-link-name">Notifications</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('users.help-center')}}">
                    <i class="nav-main-link-icon fa fa-hands-helping"></i>
                    <span class="nav-main-link-name">Tickets</span>
                </a>
            </li>

            <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('users.settings')}}">
                    <i class="nav-main-link-icon si si-wrench"></i>
                    <span class="nav-main-link-name">Settings</span>
                </a>
            </li>

{{--            <li class="nav-main-item">--}}
{{--                <a class="nav-main-link" href="{{route('users.reports')}}">--}}
{{--                    <i class="nav-main-link-icon si si-chart"></i>--}}
{{--                    <span class="nav-main-link-name">Reports</span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="nav-main-item">--}}
{{--                <a class="nav-main-link" href="{{route('users.invoice')}}">--}}
{{--                    <i class="nav-main-link-icon si si-chart"></i>--}}
{{--                    <span class="nav-main-link-name">Invoice Zone</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <li class="nav-main-item">
                <a class="nav-main-link" data-target="#feedback" data-toggle="modal">
                    <i class="nav-main-link-icon fa fa-question"></i>
                    <span class="nav-main-link-name">Add Suggestions</span>
                </a>
            </li>

            <li class="nav-main-item">
                <a class="nav-main-link" href="{{route('users.university.index')}}">
                    <i class="nav-main-link-icon si si-info"></i>
                    <span class="nav-main-link-name">Help Center</span>
                </a>
            </li>

        </ul>
    </div>
</nav>

<header id="page-header">
    <div class="content-header">
        <div class="d-flex align-items-center  justify-content-between">
            <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
                <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>

        @php
            if(auth()->user()->has_manager != null){
            $manager = auth()->user()->has_manager;
            }
            else{
                $manager = null;
            }
        @endphp
        <!-- User Dropdown -->
        </div>



        <div class="d-flex align-items-center">
{{--            <span class="badge badge-primary mt-1 mr-1" style="font-size: 13px"> Wallet: {{number_format($balance,2)}} USD </span>--}}

{{--            <div class="status-section">--}}
{{--                <a href="/users/wishlist?status=2" class="text-white">--}}
{{--                    <span class="badge badge-info" style="font-size: 13px"> Approved Wishlist {{$approved_wishlist}} </span>--}}
{{--                </a>--}}
{{--                <a href="/users/wishlist?status=5&imported=0" class="text-white">--}}
{{--                    <span class="badge badge-success" style="font-size: 13px"> Completed Wishlist {{$completed_wishlist}} </span>--}}
{{--                </a>--}}
{{--                <a href="/users/help-center" class="text-white">--}}
{{--                    <span class="badge badge-dark" style="font-size: 13px"> Pending Tickets {{$pending_ticket_count}} </span>--}}
{{--                </a>--}}
{{--            </div>--}}

            <!-- User Dropdown -->
            <div class="d-flex align-items-center">
{{--                <select name="change-view" id="change-view-store" class="form-control">--}}
{{--                    <option value="">Switch Store</option>--}}
{{--                    @foreach(auth()->user()->has_shops as $shop)--}}
{{--                        <option value="/shop/install?shop={{$shop->shopify_domain}}">{{explode('.',$shop->shopify_domain)[0]}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}

            <div class="dropdown d-inline-block ml-2">
                @if(auth()->user()->has_shops()->count())
                    <button type="button" class="btn btn-sm btn-dual" id="page-header-user-dropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-sync-alt"></i>

                        <span class="d-none d-md-inline-block ml-1">Switch to Store</span>
                        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-left p-0 border-0 font-size-sm" aria-labelledby="page-header-user-dropdown2">
                    <div class="p-2">
                        <form method="POST" action="{{ route('authenticate') }}" class="shop-login-form">
                            @csrf
                            @foreach(auth()->user()->has_shops as $shop)
                                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between shop-log-btn" >
                                    <span class="shop-domain-name">{{$shop->shopify_domain}}</span>
                                    <input type="hidden" name="shop" value="" class="shop-domain-input">
                                </button>
                            @endforeach
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <div class="dropdown d-inline-block ml-2">
                    <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="si si-bell text-danger"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-2 bg-primary text-center">
                            <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
                        </div>
                        <ul class="nav-items mb-0">
                            {{--                        <li>--}}
                            {{--                            <a class="text-dark media py-2" href="/users/wishlist?status=2">--}}
                            {{--                                <div class="mr-2 ml-3">--}}
                            {{--                                    <i class="fa fa-fw fa-check-circle text-success"></i>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="media-body pr-2">--}}
                            {{--                                    <div class="font-w600">Approved Wishlist {{$approved_wishlist}}</div>--}}
                            {{--                                </div>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}

                            {{--                        <li>--}}
                            {{--                            <a class="text-dark media py-2" href="/users/wishlist?status=5&imported=0">--}}
                            {{--                                <div class="mr-2 ml-3">--}}
                            {{--                                    <i class="fa fa-fw fa-check-circle text-success"></i>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="media-body pr-2">--}}
                            {{--                                    <div class="font-w600">Completed Wishlist {{$completed_wishlist}}</div>--}}
                            {{--                                </div>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}

                            <li>
                                <a class="text-dark media py-2" href="/users/help-center">
                                    <div class="mr-2 ml-3">
                                        <i class="fa fa-fw fa-check-circle text-success"></i>
                                    </div>
                                    <div class="media-body pr-2">
                                        <div class="font-w600">Pending Tickets {{$pending_ticket_count}}</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="dropdown d-inline-block ml-2">
                <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="si si-bell"></i>
                    <span class="badge badge-primary badge-pill d-none d-md-inline-block">{{$notifications_count}}</span>

                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-2 bg-primary text-center">
                        <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
                    </div>
                    <ul class="nav-items mb-0">
                        @if(count($notifications) > 0)
                            @foreach($notifications as $notification)
                                <li>
                                    <a class="text-dark media py-2" href="{{route('users.notification',$notification->id)}}">
                                        <div class="mr-2 ml-3">
                                            <i class="fa fa-fw fa-check-circle text-success"></i>
                                        </div>
                                        <div class="media-body pr-2">
                                            <div class="font-w600">{{$notification->message}}</div>
                                            <small class="text-muted">{{$notification->created_at->diffForHumans()}}</small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a class="text-dark media py-2" href="javascript:void(0)">
                                    <div class="mr-2 ml-3">
                                        <i class="fa fa-fw fa-check-circle text-success"></i>
                                    </div>
                                    <div class="media-body pr-2">
                                        <div class="font-w600">No Notification</div>
                                    </div>
                                </a>
                            </li>
                        @endif

                    </ul>
                    <div class="p-2 border-top">
                        <a class="btn btn-sm btn-light btn-block text-center" href="{{route('users.notifications')}}">
                            <i class="fa fa-fw fa-arrow-down mr-1"></i> See All
                        </a>
                    </div>
                </div>
            </div>
            <div class="dropdown d-inline-block ml-2">
                <button type="button" class="btn btn-sm btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded" @if(\Illuminate\Support\Facades\Auth::user()->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{\Illuminate\Support\Facades\Auth::user()->profile}}" @endif alt="Header Avatar" style="width: 18px;">
                    <span class="d-none d-md-inline-block ml-1">{{\Illuminate\Support\Facades\Auth::user()->name}}</span>
                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-user-dropdown">
                    <div class="p-3 text-center bg-primary">
                        <img class="img-avatar img-avatar48 img-avatar-thumb" @if(\Illuminate\Support\Facades\Auth::user()->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{\Illuminate\Support\Facades\Auth::user()->profile}}" @endif alt="">
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{route('users.settings')}}">
                            <span>Settings</span>
                            <i class="si si-settings"></i>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="/logout">
                            <span>Log Out</span>
                            <i class="si si-logout ml-1"></i>
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
</header>

