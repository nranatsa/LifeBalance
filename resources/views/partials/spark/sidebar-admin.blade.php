<div class="sidebar-wrapper" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <i class="bi bi-asterisk"></i>
        <span>Life Balance</span>
    </a>

    <div class="flex-grow-1 overflow-y-auto">
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Menu</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.antrean.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.antrean.*') ? 'active' : '' }}">
                        <i class="bi bi-list-ol"></i><span>Antrean</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.pembayaran.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i><span>Pembayaran</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Data Klinik</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.paket.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i><span>Paket Layanan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.terapis.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.terapis.*') ? 'active' : '' }}">
                        <i class="bi bi-person-heart"></i><span>Terapis</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.jadwal.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i><span>Jadwal Praktik</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.pasien.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.pasien.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i><span>Pasien</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-profile">
        <img src="{{ $avatarUrl }}" alt="Admin" class="sidebar-profile-img">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{ $authUser->nama_lengkap }}</div>
            <div class="sidebar-profile-email">{{ $authUser->email }}</div>
        </div>
    </div>
</div>
