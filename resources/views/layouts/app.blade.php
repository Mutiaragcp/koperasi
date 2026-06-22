<!DOCTYPE html>
<html lang="id">

@include('layouts.partials.head')

<body>

    @include('layouts.partials.mobile-header')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('layouts.partials.sidebar')

    <div class="main-wrapper">
        @yield('content')
    </div>

    @include('layouts.partials.scripts')

</body>

</html>
