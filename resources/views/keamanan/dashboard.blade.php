@extends('layouts.app')

@section('title', 'Dashboard Keamanan')

@section('content')
<div class="dashboard-wrapper">
    {{-- Overlay Teks Khusus Header (Sama dengan Admin) --}}
    <div class="header-text-overlay py-5">
        <div class="inner-container">
            <h1 class="display-5 fw-bold text-white mb-2"><i class="fas fa-user-shield me-2"></i>Panel Keamanan</h1>
            <p class="h5 text-white opacity-90 mb-0 fw-light">Selamat bertugas, <strong>{{ Auth::user()->name }}</strong>. Pantau dan kelola kehadiran santri hari ini.</p>
        </div>
    </div>

    <div class="inner-container mt-n5 pb-5">
        <div class="row">
            {{-- Stat Cards dengan Warna Tematik Halus --}}
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-keamanan-total transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-success small text-uppercase fw-bold mb-1">Total Santri</h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $totalSantri }}</h2>
                                <span class="text-muted x-small"><i class="fas fa-users me-1"></i>Data Induk Santri</span>
                            </div>
                            <div class="p-3 bg-success bg-opacity-20 rounded-4">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-keamanan-absensi transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-primary small text-uppercase fw-bold mb-1" style="color: #0d6efd !important;">Absensi Hari Ini</h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $absensiHariIni }}</h2>
                                <span class="text-muted x-small"><i class="fas fa-check-circle me-1"></i>Sudah Terabsen</span>
                            </div>
                            <div class="p-3 bg-primary bg-opacity-20 rounded-4">
                                <i class="fas fa-user-check fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu Operasional dengan Panel Kendali Berwarna --}}
            <div class="col-12 mt-3">
                <div class="card border-0 shadow-lg rounded-4 p-4 card-glass card-control-panel">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-tasks me-2 text-success"></i>Menu Operasional Keamanan</h5>
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <a href="{{ route('keamanan.absensi.harian') }}" class="text-decoration-none">
                                <div class="menu-item-keamanan p-4 rounded-4 transition-hover">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-keamanan me-3 bg-success text-white shadow-sm rounded-4">
                                            <i class="fas fa-edit fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Input Absensi Harian</h6>
                                            <p class="text-muted x-small mb-0">Catat kehadiran santri secara cepat (pagi/sore).</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ route('keamanan.histori') }}" class="text-decoration-none">
                                <div class="menu-item-keamanan p-4 rounded-4 transition-hover">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box-keamanan me-3 bg-info text-white shadow-sm rounded-4">
                                            <i class="fas fa-history fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1">Histori Absensi</h6>
                                            <p class="text-muted x-small mb-0">Lihat rekam jejak kehadiran santri sebelumnya.</p>
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

    /* Wrapper Utama (Sama dengan Admin) */
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

    /* Header Overlay (Sama dengan Admin) */
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

    .mt-n5 { margin-top: -3rem !important; }

    /* Glassmorphism */
    .card-glass {
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    /* Stat Cards Color Accents */
    .card-keamanan-total { border-bottom: 4px solid #198754 !important; background: rgba(240, 255, 240, 0.8) !important; }
    .card-keamanan-absensi { border-bottom: 4px solid #0d6efd !important; background: rgba(240, 250, 255, 0.8) !important; }

    /* Control Panel (Mint Color) */
    .card-control-panel {
        background: var(--mint-green-glass) !important;
        border: 2px solid rgba(255,255,255,0.6) !important;
    }

    /* Menu Items */
    .menu-item-keamanan {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0,0,0,0.05) !important;
        height: 100%;
        transition: all 0.3s ease;
    }

    .menu-item-keamanan:hover {
        background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(220, 255, 230, 0.8) 100%);
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border-color: var(--primary-green) !important;
    }

    .icon-box-keamanan {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .x-small { font-size: 0.75rem; }
    .transition-up { transition: 0.3s ease; }
    .transition-up:hover { transform: translateY(-8px); }

    @media (max-width: 768px) {
        .dashboard-wrapper { background-attachment: scroll; }
        .header-text-overlay { padding-top: 2rem !important; padding-bottom: 4rem !important; }
        .inner-container { padding: 0 15px; }
        .display-5 { font-size: 2rem; }
    }
</style>
@endsection