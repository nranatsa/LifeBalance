<div class="sidebar-wrapper" id="sidebar">
    <a href="{{ route('terapis.jadwal.index') }}" class="sidebar-brand">
        <i class="bi bi-asterisk"></i>
        <span>Life Balance</span>
    </a>

    <div class="flex-grow-1 overflow-y-auto">
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Menu</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('terapis.jadwal.index') }}" class="sidebar-menu-link {{ request()->routeIs('terapis.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i><span>Jadwal Saya</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('terapis.profil.edit') }}" class="sidebar-menu-link {{ request()->routeIs('terapis.profil.*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i><span>Profil Saya</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-profile">
        <img src="{{ $avatarUrl }}" alt="Terapis" class="sidebar-profile-img"
            onerror="this.src='{{ asset('spark/assets/images/avatar.png') }}'">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{ $authUser->nama_lengkap }}</div>
            <div class="sidebar-profile-email">{{ $authUser->terapis?->spesialisasi ?? $authUser->email }}</div>
        </div>
    </div>
</div>
