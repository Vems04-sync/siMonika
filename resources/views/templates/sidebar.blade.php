<div class="sidebar">
    <div class="sidebar-header">
        <div class="app-title">
            <h3>SiMonika</h3>
            <small>Sistem Monitoring Aplikasi</small>
        </div>
    </div>

    <nav class="sidebar-nav">
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
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="button nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div> 