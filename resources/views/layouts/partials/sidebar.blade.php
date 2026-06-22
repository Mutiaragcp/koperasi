<div class="sidebar" id="sidebar">
    <div class="sidebar-brand d-none d-md-block">
        <a href="/">
            <div class="brand-icon">S</div>
            <span class="brand-name">SIKOPSIM</span>
        </a>
    </div>

    <div class="sidebar-menu flex-grow-1">
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'bendahara']))
            @include('layouts.partials.sidebar-menu-admin')
        @elseif(auth()->check() && auth()->user()->role == 'anggota')
            @include('layouts.partials.sidebar-menu-anggota')
        @endif
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log Out Akun
            </button>
        </form>
    </div>
</div>