@extends('layouts.app')

@section('title', 'Dashboard Bendahara')

@section('content')
<div class="dashboard-wrapper">
    {{-- Overlay Teks Khusus Header agar Teks Putih Terbaca Jelas --}}
    <div class="header-text-overlay py-5">
        <div class="inner-container">
            <h1 class="display-5 fw-bold text-white mb-2"><i class="fas fa-wallet me-2"></i>Panel Keuangan</h1>
            <p class="h5 text-white opacity-90 mb-0 fw-light">
                Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Kelola arus kas dan verifikasi pembayaran santri.
            </p>
        </div>
    </div>

    <div class="inner-container mt-n5 pb-5">
        <div class="row">
            {{-- Stat Cards dengan Warna Tematik Halus --}}
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-pembayaran transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-success small text-uppercase fw-bold mb-1">Pembayaran Sukses</h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalPembayaran ?? 0) }}</h2>
                                <span class="text-muted x-small"><i class="fas fa-check-circle me-1"></i>Transaksi Berhasil</span>
                            </div>
                            <div class="p-3 bg-success bg-opacity-20 rounded-4">
                                <i class="fas fa-file-invoice-dollar fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-waiting transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold mb-1" style="color: #dc3545 !important;">Menunggu Verifikasi</h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $menunggu ?? 0 }}</h2> 
                                <span class="text-muted x-small"><i class="fas fa-hourglass-half me-1"></i>Perlu Segera Diperiksa</span>
                            </div>
                            <div class="p-3 bg-danger bg-opacity-20 rounded-4">
                                <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 card-glass card-tagihan transition-up">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold mb-1" style="color: #087990 !important;">Jenis Tagihan</h6>
                                <h2 class="fw-bold mb-0 text-dark">{{ $totalJenis ?? 0 }}</h2>
                                <span class="text-muted x-small"><i class="fas fa-list me-1"></i>Kategori Iuran Aktif</span>
                            </div>
                            <div class="p-3 bg-info bg-opacity-20 rounded-4">
                                <i class="fas fa-tags fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Kendali Utama Bendahara --}}
            <div class="col-12 mt-3">
                <div class="card border-0 shadow-lg rounded-4 p-4 card-glass card-control-panel">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-cash-register me-2 text-success"></i>Manajemen Keuangan Utama</h5>
                    <div class="row g-3">
                        {{-- Tombol 1: Verifikasi --}}
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('bendahara.pembayaran.index') }}" class="text-decoration-none">
                                <div class="menu-item-bendahara p-4 rounded-4 transition-hover">
                                    <div class="icon-box-admin mb-3 bg-primary text-white shadow-sm rounded-4">
                                        <i class="fas fa-file-signature fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Verifikasi</h6>
                                    <p class="text-muted x-small mb-0">Konfirmasi bukti transfer santri.</p>
                                </div>
                            </a>
                        </div>

                        {{-- Tombol 2: Bayar Tunai --}}
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('bendahara.pembayaran.manual') }}" class="text-decoration-none">
                                <div class="menu-item-bendahara p-4 rounded-4 transition-hover">
                                    <div class="icon-box-admin mb-3 bg-info text-white shadow-sm rounded-4">
                                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Bayar Tunai</h6>
                                    <p class="text-muted x-small mb-0">Input pembayaran via kantor.</p>
                                </div>
                            </a>
                        </div>

                        {{-- Tombol 3: Uang Saku --}}
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('bendahara.uangsaku.index') }}" class="text-decoration-none">
                                <div class="menu-item-bendahara p-4 rounded-4 transition-hover">
                                    <div class="icon-box-admin mb-3 bg-success text-white shadow-sm rounded-4">
                                        <i class="fas fa-piggy-bank fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Uang Saku</h6>
                                    <p class="text-muted x-small mb-0">Manajemen saldo & tarik saku.</p>
                                </div>
                            </a>
                        </div>

                        {{-- Tombol 4: Rekap --}}
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('bendahara.pembayaran.rekap') }}" class="text-decoration-none">
                                <div class="menu-item-bendahara p-4 rounded-4 transition-hover">
                                    <div class="icon-box-admin mb-3 bg-warning text-white shadow-sm rounded-4">
                                        <i class="fas fa-print fa-lg"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Rekap</h6>
                                    <p class="text-muted x-small mb-0">Histori pembayaran per santri.</p>
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
        --mint-green-glass: rgba(220, 255, 230, 0.75);
    }

    /* Background Wrapper */
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

    /* Header Overlay */
    .header-text-overlay {
        background: linear-gradient(135deg, rgba(20, 69, 20, 0.9) 0%, rgba(45, 106, 79, 0.6) 60%, rgba(255,255,255,0) 100%);
        border-bottom: 2px solid var(--primary-green);
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

    /* Statistik Colors */
    .card-pembayaran { border-bottom: 4px solid #198754 !important; background: rgba(240, 255, 240, 0.8) !important; }
    .card-waiting { border-bottom: 4px solid #dc3545 !important; background: rgba(255, 240, 240, 0.8) !important; }
    .card-tagihan { border-bottom: 4px solid #0dcaf0 !important; background: rgba(240, 250, 255, 0.8) !important; }

    /* Control Panel */
    .card-control-panel {
        background: var(--mint-green-glass) !important;
        border: 2px solid rgba(255,255,255,0.6) !important;
    }

    /* Menu Item */
    .menu-item-bendahara {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(0,0,0,0.05) !important;
        height: 100%;
        transition: all 0.3s ease;
    }

    .menu-item-bendahara:hover {
        background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(220, 255, 230, 0.8) 100%);
        transform: scale(1.03);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border-color: var(--primary-green) !important;
    }

    .icon-box-admin {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
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