@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-th-large me-2 text-success"></i>Dashboard Utama Admin</h3>
        <p class="text-muted">Ringkasan data dan kendali sistem administrasi pondok pesantren.</p>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card card-content border-0 bg-white p-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Santri</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ $totalSantri }}</h2>
                        <span class="text-success small"><i class="fas fa-user-check me-1"></i>Terdaftar Aktif</span>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded-4">
                        <i class="fas fa-user-graduate fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card card-content border-0 bg-white p-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Registrasi Baru</h6>
                        <h2 class="fw-bold mb-0 text-dark">0</h2> <span class="text-warning small"><i class="fas fa-clock me-1"></i>Menunggu Verifikasi</span>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 rounded-4">
                        <i class="fas fa-user-plus fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card card-content border-0 bg-white p-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Total User Sistem</h6>
                        <h2 class="fw-bold mb-0 text-dark">4</h2>
                        <span class="text-info small"><i class="fas fa-shield-alt me-1"></i>Multi-role Aktif</span>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 rounded-4">
                        <i class="fas fa-users-cog fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="card card-content border-0 bg-white p-4">
            <h5 class="fw-bold mb-4">Kendali Administrasi</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="{{ route('admin.santri.index') }}" class="text-decoration-none">
                        <div class="d-flex align-items-center p-3 border rounded-3 transition-hover bg-light">
                            <div class="icon-box me-3 bg-white shadow-sm rounded p-3">
                                <i class="fas fa-id-card fa-lg text-success"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Kelola Data Santri</h6>
                                <p class="text-muted small mb-0">Tambah, edit, dan hapus data induk santri.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{ route('admin.registrasi.index') }}" class="text-decoration-none">
                        <div class="d-flex align-items-center p-3 border rounded-3 transition-hover bg-light">
                            <div class="icon-box me-3 bg-white shadow-sm rounded p-3">
                                <i class="fas fa-user-edit fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Kelola Registrasi</h6>
                                <p class="text-muted small mb-0">Atur pendaftaran dan akun login user.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
    <a href="{{ route('admin.jenis-pembayaran.index') }}" class="text-decoration-none">
        <div class="d-flex align-items-center p-3 border rounded-3 transition-hover bg-light">
            <div class="icon-box me-3 bg-white shadow-sm rounded p-3">
                <i class="fas fa-tags fa-lg text-warning"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1">Jenis Pembayaran</h6>
                <p class="text-muted small mb-0">Atur kategori & besaran iuran/SPP.</p>
            </div>
        </div>
    </a>
</div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s ease;
        border: 1px solid #eee !important;
    }
    .transition-hover:hover {
        background-color: #f8fdf8 !important; /* Hijau sangat muda */
        border-color: var(--primary-green) !important;
        transform: translateX(5px);
    }
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection