@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">
    {{-- Overlay Teks Khusus Header (Sama dengan Admin) --}}
    <div class="header-text-overlay py-5">
        <div class="inner-container">
            <h1 class="display-5 fw-bold text-white mb-2"><i class="fas fa-user-circle me-2"></i>Dashboard Santri</h1>
            <p class="h5 text-white opacity-90 mb-0 fw-light">Selamat datang di Sistem Administrasi Digital Pondok Pesantren An-Najah.</p>
        </div>
    </div>

    <div class="inner-container mt-n5 pb-5">
        {{-- SECTION 1: NOTIFIKASI & SALDO (Stat Cards Style) --}}
        <div class="row g-4 mb-4">
            {{-- KOLOM NOTIFIKASI --}}
            <div class="col-md-7">
                <div class="card border-0 shadow-lg rounded-4 h-100 card-glass card-control-panel">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3 text-success">
                            <div class="bg-success bg-opacity-20 p-2 rounded-3 me-3">
                                <i class="fas fa-bell fa-lg text-success"></i>
                            </div>
                            <span class="fw-bold text-uppercase small text-dark" style="letter-spacing: 1px;">Pemberitahuan Terkini</span>
                        </div>

                        <div class="notif-scroll-stack flex-grow-1" style="max-height: 220px; overflow-y: auto; padding-right: 5px;">
                            @forelse($notifikasi as $notif)
                                <a href="{{ route('user.pembayaran.index') }}" class="text-decoration-none d-block mb-2">
                                    <div class="menu-item-user p-3 rounded-4 border-start border-4 shadow-sm {{ $notif->status == 'lunas' ? 'border-success' : 'border-danger' }}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-0 text-dark small">
                                                    {{ $notif->status == 'lunas' ? 'Pembayaran Disetujui' : 'Pembayaran Ditolak' }}
                                                </h6>
                                                @if($notif->status == 'belum lunas' && $notif->keterangan_admin)
                                                    <p class="x-small text-danger mb-1 mt-1"><strong>Alasan:</strong> {{ $notif->keterangan_admin }}</p>
                                                @endif
                                                <p class="x-small text-muted mb-0 mt-1">
                                                    ID: #{{ $notif->id_pembayaran }} • {{ date('d M Y', strtotime($notif->tanggal_pembayaran)) }}
                                                </p>
                                            </div>
                                            <i class="fas fa-chevron-right text-muted opacity-25 mt-1"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted py-5">
                                    <i class="fas fa-comment-slash fa-2x opacity-25 mb-2"></i>
                                    <p class="small mb-0">Belum ada aktivitas terbaru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM SALDO (Themed Style) --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-lg rounded-4 h-100 text-white card-saldo transition-up">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white bg-opacity-25 p-2 rounded-3 me-3">
                                    <i class="fas fa-wallet fa-lg"></i>
                                </div>
                                <span class="fw-bold text-uppercase small" style="letter-spacing: 1px;">Saldo Uang Saku</span>
                            </div>
                            <h1 class="display-6 fw-bold mb-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h1>
                            <p class="small opacity-75 mb-0"><i class="fas fa-sync-alt me-1 x-small"></i>Terupdate otomatis oleh sistem.</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.uangsaku.index') }}" class="btn btn-light btn-lg w-100 rounded-pill fw-bold text-success shadow-sm">
                                Riwayat Saku <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: MONITORING SPP (Glassmorphism Color) --}}
        <div class="card border-0 shadow-lg rounded-4 mb-5 overflow-hidden card-glass card-control-panel">
            <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-calendar-check text-success me-2"></i>Status Administrasi SPP {{ date('Y') }}</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-2 text-center mb-4">
                    @foreach($listBulan as $num => $nama)
                        <div class="col-4 col-md-2">
                            <div class="p-2 rounded-3 border-2 transition-all {{ in_array($num, $pembayaranLunas) ? 'bg-success text-white border-success shadow-sm shadow-success' : 'bg-white bg-opacity-40 text-muted border-dashed-custom' }}">
                                <span class="d-block fw-bold small text-uppercase" style="font-size: 0.65rem;">{{ $nama }}</span>
                                <i class="fas {{ in_array($num, $pembayaranLunas) ? 'fa-check-circle mt-1' : 'fa-clock mt-1 opacity-25' }}"></i>
                            </div>
                        </div>
                    @endforeach
                </div>

                @php $bulanSekarang = (int)date('n'); @endphp
                @if(!in_array($bulanSekarang, $pembayaranLunas))
                    <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center p-4 mb-0 shadow-sm animate-pulse">
                        <i class="fas fa-exclamation-triangle me-3 fa-2x text-warning"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-dark">Tagihan {{ $listBulan[$bulanSekarang] }} Belum Terbayar</h6>
                            <p class="small mb-0 text-muted">Segera lakukan pembayaran untuk mendukung operasional pesantren.</p>
                        </div>
                        <a href="{{ route('user.pembayaran.create') }}" class="btn btn-dark btn-md rounded-pill px-4 fw-bold shadow">Bayar Sekarang</a>
                    </div>
                @else
                    <div class="alert alert-success border-0 rounded-4 d-flex align-items-center p-4 mb-0 shadow-sm" style="background: rgba(25, 135, 84, 0.1) !important;">
                        <i class="fas fa-check-double me-3 fa-2x text-success"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-dark">Alhamdulillah, Tagihan bulan ini sudah lunas!</h6>
                            <p class="small mb-0 text-muted">Terima kasih atas kedisiplinan Anda dalam administrasi.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 3: INFORMASI PUSAT (Grid Style) --}}
        <div class="d-flex align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0"><i class="fas fa-bullhorn text-success me-2"></i>Informasi Pusat</h4>
            <div class="flex-grow-1 ms-3 border-bottom opacity-25"></div>
        </div>
        
        <div class="row g-4">
            @foreach($adminContents as $content)
                <div class="col-md-4">
                    <div class="card border-0 shadow-lg rounded-4 h-100 transition-up overflow-hidden card-glass">
                        @if($content->image)
                            <img src="{{ asset('storage/' . $content->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-2 h5">{{ $content->title }}</h6>
                            <div class="text-muted small mb-0 line-clamp">{!! $content->value !!}</div>
                        </div>
                    </div>
                </div>
            @endforeach
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

    /* Header Overlay (Identik dengan Admin) */
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

    .mt-n5 { margin-top: -3.5rem !important; }

    /* Glassmorphism */
    .card-glass {
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    .card-control-panel {
        background: var(--mint-green-glass) !important;
        border: 2px solid rgba(255,255,255,0.6) !important;
    }

    /* Saldo Card */
    .card-saldo {
        background: linear-gradient(45deg, #1a5d1a, #2ecc71) !important;
        border: none !important;
    }

    /* List Item Notifikasi (Menu Item Style) */
    .menu-item-user {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .menu-item-user:hover {
        background: #ffffff;
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .border-dashed-custom { border: 2px dashed rgba(0,0,0,0.1) !important; }

    .line-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .x-small { font-size: 0.75rem; }
    .opacity-90 { opacity: 0.9; }

    .transition-up { transition: 0.3s ease; }
    .transition-up:hover { transform: translateY(-10px); }

    .animate-pulse {
        animation: pulse-shadow 2s infinite;
    }

    @keyframes pulse-shadow {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }

    /* Scrollbar */
    .notif-scroll-stack::-webkit-scrollbar { width: 4px; }
    .notif-scroll-stack::-webkit-scrollbar-thumb { background: var(--primary-green); border-radius: 10px; }

    @media (max-width: 768px) {
        .dashboard-wrapper { background-attachment: scroll; }
        .header-text-overlay { padding-top: 2rem !important; padding-bottom: 5rem !important; }
        .mt-n5 { margin-top: -3rem !important; }
        .display-5 { font-size: 1.8rem; }
    }
</style>
@endsection