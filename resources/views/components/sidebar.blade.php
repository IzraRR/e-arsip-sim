{{-- resources/views/components/sidebar.blade.php --}}

@php
    $currentRoute = request()->route()->getName();
    $role = auth()->user()->role;
@endphp

{{-- MENU UNTUK ADMIN --}}
@if($role == 'admin')
    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ str_contains($currentRoute, 'admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('surat-masuk.index') }}" class="menu-item {{ str_contains($currentRoute, 'surat-masuk') ? 'active' : '' }}">
        <i class="bi bi-envelope"></i> Surat Masuk
    </a>
    <a href="{{ route('surat-keluar.index') }}" class="menu-item {{ str_contains($currentRoute, 'surat-keluar') ? 'active' : '' }}">
        <i class="bi bi-send"></i> Surat Keluar
    </a>
    <a href="{{ route('arsip.index') }}" class="menu-item {{ str_contains($currentRoute, 'arsip') ? 'active' : '' }}">
        <i class="bi bi-folder"></i> Arsip Dokumen
    </a>
    <a href="{{ route('disposisi.index') }}" class="menu-item {{ str_contains($currentRoute, 'disposisi') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> Disposisi
    </a>
    
    <div class="menu-separator"></div>
    <small class="menu-label px-3 text-white-50">ADMIN MENU</small>
    
    <a href="{{ route('admin.users.index') }}" class="menu-item {{ str_contains($currentRoute, 'users') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Manajemen User
    </a>
    <a href="{{ route('admin.kategori.index') }}" class="menu-item {{ str_contains($currentRoute, 'kategori') ? 'active' : '' }}">
        <i class="bi bi-tags"></i> Kategori
    </a>
    <a href="{{ route('laporan.index') }}" class="menu-item {{ str_contains($currentRoute, 'laporan') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> Laporan
    </a>
    <a href="{{ route('admin.log-aktivitas.index') }}" class="menu-item {{ str_contains($currentRoute, 'log-aktivitas') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> Log Aktivitas
    </a>
    <a href="{{ route('admin.pengaturan.index') }}" class="menu-item {{ str_contains($currentRoute, 'pengaturan') ? 'active' : '' }}">
        <i class="bi bi-gear"></i> Pengaturan
    </a>
@endif

{{-- MENU UNTUK PIMPINAN --}}
@if($role == 'pimpinan')
    <a href="{{ route('pimpinan.dashboard') }}" class="menu-item {{ str_contains($currentRoute, 'pimpinan.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('surat-masuk.index') }}" class="menu-item {{ str_contains($currentRoute, 'surat-masuk') ? 'active' : '' }}">
        <i class="bi bi-envelope"></i> Surat Masuk
    </a>
    <a href="{{ route('surat-keluar.index') }}" class="menu-item {{ str_contains($currentRoute, 'surat-keluar') ? 'active' : '' }}">
        <i class="bi bi-send"></i> Surat Keluar
    </a>
    <a href="{{ route('disposisi.index') }}" class="menu-item {{ str_contains($currentRoute, 'disposisi') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> Disposisi Saya
    </a>
    <a href="{{ route('arsip.index') }}" class="menu-item {{ str_contains($currentRoute, 'arsip') ? 'active' : '' }}">
        <i class="bi bi-folder"></i> Arsip Dokumen
    </a>
    <a href="{{ route('laporan.index') }}" class="menu-item {{ str_contains($currentRoute, 'laporan') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> Laporan
    </a>
@endif

{{-- MENU UNTUK STAFF --}}
@if($role == 'staff')
    <a href="{{ route('staff.dashboard') }}" class="menu-item {{ str_contains($currentRoute, 'staff.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('surat-masuk.index') }}" class="menu-item {{ str_contains($currentRoute, 'surat-masuk') ? 'active' : '' }}">
        <i class="bi bi-envelope"></i> Surat Masuk
    </a>
    <a href="{{ route('surat-keluar.index') }}" class="menu-item {{ str_contains($currentRoute, 'surat-keluar') ? 'active' : '' }}">
        <i class="bi bi-send"></i> Surat Keluar
    </a>
    <a href="{{ route('disposisi.index') }}" class="menu-item {{ str_contains($currentRoute, 'disposisi') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> Disposisi Saya
    </a>
    <a href="{{ route('arsip.index') }}" class="menu-item {{ str_contains($currentRoute, 'arsip') ? 'active' : '' }}">
        <i class="bi bi-folder"></i> Arsip Dokumen
    </a>
@endif

<style>
.menu-separator {
    height: 1px;
    background: rgba(255,255,255,0.1);
    margin: 1rem 0;
}

.menu-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
</style>