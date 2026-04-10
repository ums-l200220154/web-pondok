@extends('layouts.app')

@section('title', 'Dashboard Bendahara')

@section('content')
<div class="row g-4">
    {{-- Bagian Welcome Hero --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-8 p-4 p-lg-5" style="background: linear-gradient(135deg, #1a5d1a 0%, #2ecc71 100%); color: white;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 p-2 rounded-3 me-3">
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                            <h2 class="fw-bold mb-0">Panel Keuangan</h2>
                        </div>
                        <p class="mb-0 opacity-75 fs-5">
                            Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. 
                            Pantau dan kelola arus kas pesantren secara real-time di sini.
                        </p>
                    </div>
                    <div class="col-md-4 d-none d-md-flex align-items-center justify-content-center bg-white p-4 text-center">
                        <div class="text-success">
                            <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Jenis Tagihan</h6>
                            <h3 class="fw-bold">{{ $totalJenis ?? 0 }}</h3>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="col-md-6">
        <div class="card card-content border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold tracking-wider">Total Pembayaran Sukses</h6>
                        <h2 class="fw-bold mb-0 text-success">{{ number_format($totalPembayaran ?? 0) }}</h2>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded-4">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
                <hr class="opacity-50">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i> Transaksi yang sudah masuk ke sistem
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-content border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted small text-uppercase fw-bold tracking-wider">Menunggu Verifikasi</h6>
                        <h2 class="fw-bold mb-0 text-danger">{{ $menunggu ?? 0 }}</h2>
                    </div>
                    <div class="p-3 bg-danger bg-opacity-10 rounded-4">
                        <i class="fas fa-hourglass-half fa-2x text-danger"></i>
                    </div>
                </div>
                <hr class="opacity-50">
                <p class="text-muted small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i> Bukti bayar yang perlu segera diperiksa
                </p>
            </div>
        </div>
    </div>

    {{-- Grid Menu Navigasi Cepat --}}
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center mb-4">
            <div class="vr me-3" style="width: 4px; background-color: var(--primary-green); border-radius: 2px; height: 25px;"></div>
            <h5 class="fw-bold mb-0">Manajemen Utama</h5>
        </div>
        
        <div class="row g-4">
            {{-- Tombol 1 --}}
            <div class="col-6 col-lg-3">
                <a href="{{ route('bendahara.pembayaran.index') }}" class="text-decoration-none group">
                    <div class="card card-content border-0 shadow-sm transition-card text-center p-4">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                            <i class="fas fa-file-signature fa-2x"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Verifikasi</h6>
                        <p class="small text-muted mb-0">Konfirmasi Bukti</p>
                    </div>
                </a>
            </div>

            {{-- Tombol 2 --}}
            <div class="col-6 col-lg-3">
                <a href="{{ route('bendahara.pembayaran.manual') }}" class="text-decoration-none">
                    <div class="card card-content border-0 shadow-sm transition-card text-center p-4">
                        <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                            <i class="fas fa-cash-register fa-2x"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Bayar Tunai</h6>
                        <p class="small text-muted mb-0">Input di Kantor</p>
                    </div>
                </a>
            </div>

            {{-- Tombol 3 --}}
            <div class="col-6 col-lg-3">
                <a href="{{ route('bendahara.uangsaku.index') }}" class="text-decoration-none">
                    <div class="card card-content border-0 shadow-sm transition-card text-center p-4">
                        <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                            <i class="fas fa-piggy-bank fa-2x"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Uang Saku</h6>
                        <p class="small text-muted mb-0">Input & Tarik Saldo</p>
                    </div>
                </a>
            </div>

            {{-- Tombol 4 --}}
            <div class="col-6 col-lg-3">
                <a href="{{ route('bendahara.pembayaran.rekap') }}" class="text-decoration-none">
                    <div class="card card-content border-0 shadow-sm transition-card text-center p-4">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                            <i class="fas fa-file-invoice-dollar fa-2x"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Rekap</h6>
                        <p class="small text-muted mb-0">Histori Per Santri</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-wider {
        letter-spacing: 0.05rem;
    }

    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .transition-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.02) !important;
    }

    .transition-card:hover {
        transform: translateY(-7px);
        background-color: #ffffff;
        box-shadow: 0 12px 25px rgba(0,0,0,0.08) !important;
        border-color: var(--accent-green) !important;
    }

    .transition-card:hover .icon-box {
        transform: scale(1.1);
    }

    /* Penyesuaian untuk Mobile */
    @media (max-width: 576px) {
        .icon-box {
            width: 50px;
            height: 50px;
        }
        .icon-box i {
            font-size: 1.2rem;
        }
        h6.fw-bold {
            font-size: 0.9rem;
        }
    }
</style>
@endsection