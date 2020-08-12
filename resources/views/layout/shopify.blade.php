<!DOCTYPE html>
<html>
@include('inc.header')
<body>
<div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
    @include('layout.shopify_sidebar')
    <main id="main-container">
        @include('flash_message.message')
        @if(auth()->check())
            @if(count(auth()->user()->has_shops) == 0)
                <div class="alert alert-info alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>You dont have connected any store yet. For store connection <a href="{{route('system.store.connect')}}">click here</a>.</strong>
                </div>
            @endif
        @endif
        @yield('content')
    </main>

    @include('inc.footer')
</div>

</body>
</html>
