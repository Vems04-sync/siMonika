<div class="sidebar-responsive">
    <button class="btn-toggle-sidebar">
        <i class="bi bi-list"></i>
    </button>
</div>
<div class="sidebar-overlay"></div>
<div class="sidebar">
    <div class="sidebar-header">
        <div class="app-title">
            <h3>SiMonika</h3>
            <small>Sistem Monitoring Aplikasi</small>
        </div>
    </div>

    <nav class="sidebar-nav flex-grow-1">
        <ul class="nav flex-column">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('aplikasi.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('aplikasi.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Aplikasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-clipboard"></i>
                    <span>Atribut</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profile.index') }}">
                    <i class="bi bi-person-vcard"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="button nav-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
