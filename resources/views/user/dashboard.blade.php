@extends('layouts.app')

@section('content')
<div class="container py-4">

<div class="row g-4 mb-4">
    {{-- KOLOM NOTIFIKASI (Tampil di kiri pada Desktop, tampil paling ATAS pada HP) --}}
    <div class="col-md-7 order-1 order-md-1">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center mb-3 text-success">
                    <div class="bg-light p-2 rounded-3 me-3">
                        <i class="fas fa-bell fa-lg"></i>
                    </div>
                    <span class="fw-bold text-uppercase small" style="letter-spacing: 1px;">Pemberitahuan Terkini</span>
                </div>

                <div class="notif-scroll-stack flex-grow-1" style="max-height: 180px; overflow-y: auto;">
                    @forelse($notifikasi as $notif)
                        <a href="{{ route('user.pembayaran.index') }}" class="text-decoration-none d-block mb-2">
                            <div class="p-3 rounded-4 border-start border-4 shadow-sm hover-notif {{ $notif->status == 'lunas' ? 'border-success bg-success-light' : 'border-danger bg-danger-light' }}">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0 text-dark small">
                                            {{ $notif->status == 'lunas' ? 'Pembayaran Disetujui' : 'Pembayaran Ditolak' }}
                                        </h6>
                                        
                                        @if($notif->status == 'belum lunas' && $notif->keterangan_admin)
                                            <p class="x-small text-danger mb-1 mt-1">
                                                <strong>Alasan:</strong> {{ $notif->keterangan_admin }}
                                            </p>
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
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted py-4">
                            <i class="fas fa-comment-slash fa-2x opacity-25 mb-2"></i>
                            <p class="small mb-0">Belum ada aktivitas pembayaran.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM SALDO (Tampil di kanan pada Desktop, tampil di BAWAH notifikasi pada HP) --}}
    <div class="col-md-5 order-2 order-md-2">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 p-2 rounded-3 me-3">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                        <span class="fw-bold text-uppercase small" style="letter-spacing: 1px;">Saldo Uang Saku</span>
                    </div>
                    <h2 class="fw-bold mb-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
                    <p class="small opacity-75 mb-0">Update otomatis saat admin menyetujui top-up.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user.uangsaku.index') }}" class="btn btn-light btn-sm rounded-pill px-4 fw-bold text-success shadow-sm">
                        Riwayat Saku <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- SECTION 2: MONITORING PEMBAYARAN --}}
    <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden text-start">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
            <i class="fas fa-calendar-check text-success me-2"></i>
            <h6 class="fw-bold mb-0">Status SPP Tahun {{ date('Y') }}</h6>
        </div>
        <div class="card-body bg-light-subtle p-4">
            <div class="row g-2 text-center mb-4">
                @foreach($listBulan as $num => $nama)
                    <div class="col-4 col-md-2">
                        <div class="p-2 rounded-3 border-2 transition-all {{ in_array($num, $pembayaranLunas) ? 'bg-success text-white border-success shadow-sm' : 'bg-white text-muted border-dashed-custom' }}">
                            <span class="d-block fw-bold small text-uppercase" style="font-size: 0.7rem;">{{ $nama }}</span>
                            <div class="mt-1">
                                <i class="fas {{ in_array($num, $pembayaranLunas) ? 'fa-check-circle' : 'fa-clock opacity-25' }}"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @php $bulanSekarang = (int)date('n'); @endphp
            @if(!in_array($bulanSekarang, $pembayaranLunas))
                <div class="alert alert-warning border-0 rounded-4 shadow-sm d-flex align-items-center p-3 mb-0">
                    <div class="bg-warning bg-opacity-25 p-2 rounded-3 me-3">
                        <i class="fas fa-exclamation-circle text-dark"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1 text-dark small">Tagihan {{ $listBulan[$bulanSekarang] }} Belum Terbayar</h6>
                        <p class="x-small mb-0 text-dark opacity-75">Silakan lakukan konfirmasi pembayaran segera.</p>
                    </div>
                    <a href="{{ route('user.pembayaran.create') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold">Bayar</a>
                </div>
            @else
                <div class="alert alert-success border-0 rounded-4 shadow-sm d-flex align-items-center p-3 mb-0">
                    <div class="bg-success bg-opacity-25 p-2 rounded-3 me-3 text-success">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="flex-grow-1 text-start">
                        <h6 class="fw-bold mb-1 small text-dark">Alhamdulillah, Aman!</h6>
                        <p class="x-small mb-0 text-muted text-start">Tagihan bulan {{ $listBulan[$bulanSekarang] }} sudah lunas.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION 3: KONTEN ADMIN --}}
    <h5 class="fw-bold mb-3 text-dark text-start"><i class="fas fa-bullhorn text-success me-2"></i>Informasi Pusat</h5>
    <div class="row g-4 text-start">
        @foreach($adminContents as $content)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 hover-up overflow-hidden">
                    @if($content->image)
                        <img src="{{ asset('storage/' . $content->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h6 class="fw-bold text-dark mb-2">{{ $content->title }}</h6>
                        <div class="text-muted small mb-0 line-clamp">{!! $content->value !!}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .x-small { font-size: 0.75rem; }
    .bg-success-light { background-color: #f0fdf4; }
    .bg-danger-light { background-color: #fef2f2; }
    .hover-notif { transition: all 0.2s ease; }
    .hover-notif:hover { transform: translateX(5px); background: #fff !important; }
    .border-dashed-custom { border: 2px dashed #e5e7eb !important; }
    .hover-up:hover { transform: translateY(-5px); transition: 0.3s; }
    .line-clamp { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .notif-scroll-stack::-webkit-scrollbar { width: 3px; }
    .notif-scroll-stack::-webkit-scrollbar-thumb { background: #eee; }
</style>
@endsection