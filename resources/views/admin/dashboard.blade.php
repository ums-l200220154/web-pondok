@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-wrapper">
    {{-- Overlay Teks Khusus Header agar Teks Putih Terbaca Jelas --}}
    <div class="header-text-overlay py-5">
        <div class="inner-container">
            <h1 class="display-5 fw-bold text-white mb-2"><i class="fas fa-th-large me-2"></i>Dashboard Utama Admin</h1>
            <p class="h5 text-white opacity-90 mb-0 fw-light">Ringkasan data dan kendali sistem administrasi pondok pesantren An-Najah.</p>
        </div>
    </div>

    <div class="inner-container mt-n5 pb-5">
        <div class="row">
            {{-- Stat Cards dengan Warna Tematik Halus --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-santri transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-success small text-uppercase fw-bold mb-1">Total Santri</h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $totalSantri }}</h2>
                                <span class="text-muted x-small"><i class="fas fa-user-check me-1"></i>Terdaftar Aktif</span>
                            </div>
                            <div class="p-3 bg-success bg-opacity-20 rounded-4">
                                <i class="fas fa-user-graduate fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-registrasi transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold mb-1" style="color: #b38b00 !important;">Registrasi Baru</h6>
                                <h2 class="fw-bold mb-0 text-dark">0</h2> 
                                <span class="text-muted x-small"><i class="fas fa-clock me-1"></i>Menunggu Verifikasi</span>
                            </div>
                            <div class="p-3 bg-warning bg-opacity-20 rounded-4">
                                <i class="fas fa-user-plus fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-user transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold mb-1" style="color: #087990 !important;">Total User Sistem</h6>
                                <h2 class="fw-bold mb-0 text-dark">4</h2>
                                <span class="text-muted x-small"><i class="fas fa-shield-alt me-1"></i>Multi-role Aktif</span>
                            </div>
                            <div class="p-3 bg-info bg-opacity-20 rounded-4">
                                <i class="fas fa-users-cog fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kendali Administrasi dengan Warna Latar yang Sesuai --}}
            <div class="col-12 mt-3">
                <div class="card border-0 shadow-lg rounded-4 p-4 card-glass card-control-panel">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-sliders-h me-2 text-success"></i>Panel Kendali Administrasi</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('admin.santri.index') }}" class="text-decoration-none">
                                <div class="menu-item-admin p-4 rounded-4 transition-hover">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-admin me-3 bg-success text-white shadow-sm rounded-4">
                                            <i class="fas fa-id-card fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Kelola Data Santri</h6>
                                            <p class="text-muted x-small mb-0">Manajemen induk dan profil santri.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('admin.registrasi.index') }}" class="text-decoration-none">
                                <div class="menu-item-admin p-4 rounded-4 transition-hover">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-admin me-3 bg-primary text-white shadow-sm rounded-4">
                                            <i class="fas fa-user-edit fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Kelola Registrasi</h6>
                                            <p class="text-muted x-small mb-0">Aktivasi akun dan verifikasi pendaftaran.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('admin.jenis-pembayaran.index') }}" class="text-decoration-none">
                                <div class="menu-item-admin p-4 rounded-4 transition-hover">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-admin me-3 bg-warning text-white shadow-sm rounded-4">
                                            <i class="fas fa-tags fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Jenis Pembayaran</h6>
                                            <p class="text-muted x-small mb-0">Atur kategori biaya & besaran SPP.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-green: #1a5d1a;
        --accent-green: #2ecc71;
        --dark-green: #144514;
        --mint-green-glass: rgba(220, 255, 230, 0.75);
    }

    /* Wrapper Utama Penuh Selayar dengan Gambar Latar */
    .dashboard-wrapper {
        background: url('{{ asset('storage/background/bg-pesantren.jpg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    /* Overlay Teks Header agar Teks Putih Terbaca Jelas */
    .header-text-overlay {
        background: linear-gradient(135deg, rgba(20, 69, 20, 0.9) 0%, rgba(45, 106, 79, 0.6) 60%, rgba(255,255,255,0) 100%);
        border-bottom: 2px solid var(--primary-green);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .inner-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Margin Negatif agar Stat Cards naik ke atas Header Overlay */
    .mt-n5 {
        margin-top: -3rem !important;
    }

    /* Base Glassmorphism - Lebih Bening */
    .card-glass {
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    /* Warna Aksentuasi Halus untuk Kartu Statistik */
    .card-santri { border-bottom: 4px solid #198754 !important; background: rgba(240, 255, 240, 0.8) !important; }
    .card-registrasi { border-bottom: 4px solid #ffc107 !important; background: rgba(255, 252, 240, 0.8) !important; }
    .card-user { border-bottom: 4px solid #0dcaf0 !important; background: rgba(240, 250, 255, 0.8) !important; }

    /* Container Kendali dengan Warna Hijau Mint Transparan */
    .card-control-panel {
        background: var(--mint-green-glass) !important;
        border: 2px solid rgba(255,255,255,0.6) !important;
    }

    /* Menu Item Styling - Tidak Putih Polos */
    .menu-item-admin {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0,0,0,0.05) !important;
        height: 100%;
        transition: all 0.3s ease;
    }

    .menu-item-admin:hover {
        background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(220, 255, 230, 0.8) 100%);
        transform: scale(1.03);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border-color: var(--primary-green) !important;
    }

    .icon-box-admin {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0; /* Mencegah ikon menyusut di HP */
    }

    .x-small { font-size: 0.75rem; }
    .opacity-90 { opacity: 0.9; }

    /* Animasi Naik */
    .transition-up { transition: 0.3s ease; }
    .transition-up:hover { transform: translateY(-8px); }

    /* Responsif Mobile */
    @media (max-width: 768px) {
        .dashboard-wrapper { background-attachment: scroll; }
        .header-text-overlay { padding-top: 2rem !important; padding-bottom: 4rem !important; }
        .inner-container { padding: 0 15px; }
        .display-5 { font-size: 2rem; }
        .card-body { padding: 1.25rem !important; }
    }
</style>
@endsection