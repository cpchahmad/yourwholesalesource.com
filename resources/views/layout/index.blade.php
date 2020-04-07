<!DOCTYPE html>
<html>
@include('inc.header')
<body>
<div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
    @include('layout.sidebar')
    <main id="main-container">
        @yield('content')
    </main>

    @include('inc.footer')
</div>

</body>
</html>
