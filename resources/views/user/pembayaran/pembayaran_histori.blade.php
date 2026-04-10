@extends('layouts.app')

@section('content')
<div class="container pb-5">
    {{-- HEADER & TOMBOL TAMBAH --}}
    <div class="row mb-4">
        <div class="col-md-7">
            <h3 class="fw-bold text-dark mb-0">Riwayat Pembayaran</h3>
            <p class="text-muted">Pantau status iuran dan rincian transaksi Anda.</p>
        </div>
        <div class="col-md-5 text-md-end align-self-center">
            <a href="{{ route('user.pembayaran.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>Bayar Iuran
            </a>
        </div>
    </div>

    {{-- FITUR PENCARIAN --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('user.pembayaran.index') }}" method="GET">
                <div class="input-group bg-white shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text border-0 bg-white ps-3">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-0 py-2" 
                           placeholder="Cari ID transaksi atau bulan..." value="{{ $search ?? '' }}">
                    <button class="btn btn-primary px-4" type="submit">Cari</button>
                </div>
                @if($search)
                    <div class="mt-2 ps-2">
                        <a href="{{ route('user.pembayaran.index') }}" class="text-decoration-none small text-danger">
                            <i class="fas fa-times me-1"></i> Hapus Pencarian
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- LIST DATA --}}
    <div class="row">
        @forelse($data as $index => $p)
        <div class="col-12 mb-3">
            {{-- Bagian Card (Sama seperti sebelumnya dengan Collapse) --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative" 
                 style="cursor: pointer;" 
                 data-bs-toggle="collapse" 
                 data-bs-target="#collapseDetail{{ $index }}" 
                 aria-expanded="false">
                
                <div class="row g-0">
                    <div class="col-auto" style="width: 8px; background-color: {{ $p->status == 'lunas' ? '#198754' : ($p->status == 'menunggu' ? '#ffc107' : ($p->status == 'belum lunas' ? '#fd7e14' : '#dc3545')) }};"></div>
                    
                    <div class="col p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-3 me-3 d-none d-md-block">
                                    <i class="far fa-calendar-alt text-secondary fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
                                        @php
                                            $bulanList = $p->rincian->where('kategori', 'pembayaran')->pluck('bulan')->unique();
                                            $tahunTampil = $p->rincian->first()->tahun ?? '';
                                        @endphp
                                        @if($bulanList->count() > 0)
                                            {{ implode(', ', $bulanList->map(fn($b) => ucfirst($b))->toArray()) }}
                                        @else
                                            Topup Uang Saku
                                        @endif
                                        {{ $tahunTampil }}
                                    </h5>
                                    <div class="text-muted" style="font-size: 0.85rem;">
                                        #PAY-{{ $p->id_pembayaran }} • {{ date('d M Y', strtotime($p->tanggal_pembayaran)) }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <div class="text-end me-2 d-none d-md-block">
                                    <small class="text-muted d-block">Total:</small>
                                    <span class="fw-bold text-success">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</span>
                                </div>

                                @if($p->status == 'lunas')
                                    <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">Lunas</span>
                                @elseif($p->status == 'menunggu')
                                    <span class="badge bg-warning-subtle text-warning border border-warning rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">Verifikasi</span>
                                @elseif($p->status == 'belum lunas')
                                    <span class="badge bg-orange-subtle text-orange border border-orange rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">Sebagian</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3 py-2 text-uppercase" style="font-size: 0.7rem;">Ditolak</span>
                                @endif
                                <i class="fas fa-chevron-down text-muted ms-2 rotate-icon"></i>
                            </div>
                        </div>

                        {{-- DETAIL COLLAPSE --}}
                        <div class="collapse mt-3" id="collapseDetail{{ $index }}">
                            <hr class="my-3 opacity-25">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="small fw-bold text-secondary text-uppercase mb-2">Item Rincian:</h6>
                                    <ul class="list-unstyled mb-0">
                                        @php $itemsUnik = $p->rincian->groupBy('jenis'); @endphp
                                        @foreach($itemsUnik as $namaJenis => $group)
                                            <li class="d-flex justify-content-between mb-1 small text-dark border-bottom border-dashed pb-1">
                                                <span>{{ $namaJenis }} 
                                                    @if($group->first()->kategori == 'pembayaran')
                                                        <small class="text-muted">(x{{ $group->count() }} bln)</small>
                                                    @endif
                                                </span>
                                                <span class="fw-bold">Rp {{ number_format($group->sum('jumlah'), 0, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-6 border-start-md">
                                    <div class="ps-md-4">
                                        @if($p->keterangan_bendahara)
                                            <div class="alert alert-warning border-0 small py-2 mb-3">
                                                <i class="fas fa-info-circle me-1"></i> <strong>Pesan:</strong> {{ $p->keterangan_bendahara }}
                                            </div>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="small text-muted">Bukti:</span>
                                            @if($p->bukti != 'manual_tunai.jpg')
                                                <a href="{{ asset('storage/'.$p->bukti) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">Lihat Bukti</a>
                                            @else
                                                <span class="badge bg-light text-dark">Tunai</span>
                                            @endif
                                        </div>
                                        <div class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center border shadow-sm">
                                            <span class="fw-bold text-secondary small">TOTAL BAYAR</span>
                                            <span class="h5 fw-bold text-success mb-0">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                <div class="card-body">
                    <i class="fas fa-receipt fa-4x text-light mb-3"></i>
                    <h5 class="text-muted">Data tidak ditemukan.</h5>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- NAVIGASI PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    /* Style sama seperti sebelumnya */
    .border-start-md { border-left: 1px solid #eee; }
    .border-dashed { border-bottom-style: dashed !important; }
    .bg-success-subtle { background-color: #e6f4ea; }
    .bg-warning-subtle { background-color: #fff8e1; }
    .bg-danger-subtle { background-color: #fce8e8; }
    .bg-orange-subtle { background-color: #fff3e0; }
    .text-orange { color: #fd7e14; }
    .border-orange { border-color: #fd7e14 !important; }
    .rotate-icon { transition: transform 0.3s ease; }
    [aria-expanded="true"] .rotate-icon { transform: rotate(180deg); }
    .card:hover { background-color: #fafafa; transition: 0.2s; }
    @media (max-width: 767.98px) { 
        .border-start-md { border-left: none; border-top: 1px solid #eee; padding-top: 1.5rem; margin-top: 1rem; } 
    }
</style>
@endsection