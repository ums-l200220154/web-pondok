@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header & Statistik (Tetap sama seperti kode sebelumnya) --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('keamanan.histori') }}" class="btn btn-outline-secondary rounded-circle me-3 shadow-sm border-0 bg-white">
            <i class="fas fa-arrow-left text-dark"></i>
        </a>
        <div>
            <h3 class="fw-bold mb-0 text-dark">{{ $santri->nama }}</h3>
            <span class="text-muted small">Nomor Induk Santri: <b class="text-dark">{{ $santri->nis }}</b></span>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 15px;">
                <small class="text-muted text-uppercase fw-bold mb-3 d-block" style="letter-spacing: 1px;">Ringkasan Keseluruhan</small>
                <div class="d-flex justify-content-around text-center">
                    <div><h4 class="fw-bold text-success mb-0">{{ $statsTotal['hadir'] }}</h4><small class="text-muted">Hadir</small></div>
                    <div><h4 class="fw-bold text-warning mb-0">{{ $statsTotal['sakit'] }}</h4><small class="text-muted">Sakit</small></div>
                    <div><h4 class="fw-bold text-danger mb-0">{{ $statsTotal['alpa'] }}</h4><small class="text-muted">Alpa</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3 bg-success text-white" style="border-radius: 15px;">
                <small class="text-white-50 text-uppercase fw-bold mb-3 d-block" style="letter-spacing: 1px;">Bulan Ini ({{ date('F') }})</small>
                <div class="d-flex justify-content-around text-center">
                    <div><h4 class="fw-bold mb-0 text-white">{{ $statsBulan['hadir'] }}</h4><small>Hadir</small></div>
                    <div><h4 class="fw-bold mb-0 text-white">{{ $statsBulan['sakit'] }}</h4><small>Sakit</small></div>
                    <div><h4 class="fw-bold mb-0 text-white">{{ $statsBulan['alpa'] }}</h4><small>Alpa</small></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat dengan Pagination --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0">
            <form action="{{ route('keamanan.absensi.index', $santri->nis) }}" method="GET">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <h5 class="fw-bold mb-0 text-dark me-3"><i class="fas fa-list me-2 text-success"></i>Riwayat Absensi</h5>
                    </div>
                    <div class="col-md-4 ms-md-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tanggal_cari" class="form-control bg-light border-0" value="{{ $searchDate }}">
                            <button type="submit" class="btn btn-primary px-3 fw-bold">Filter</button>
                            @if($searchDate)
                                <a href="{{ route('keamanan.absensi.index', $santri->nis) }}" class="btn btn-danger px-3"><i class="fas fa-times"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-center small text-uppercase fw-bold text-muted">
                    <tr>
                        <th width="30%" class="py-3">Tanggal</th>
                        <th class="py-3">Sesi Pagi</th>
                        <th class="py-3">Sesi Malam</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiGrouped as $tanggal => $data)
                    <tr class="text-center">
                        <td class="text-dark py-3">
                            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                        </td>
                        <td>@include('keamanan.partials.badge', ['absen' => $data['pagi']])</td>
                        <td>@include('keamanan.partials.badge', ['absen' => $data['malam']])</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <div class="mb-2"><i class="fas fa-calendar-times fs-2 opacity-25"></i></div>
                            Tidak ada data absensi untuk periode yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi Halaman (Pagination) --}}
        <div class="card-footer bg-white py-3 border-0 border-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $absensiRaw->firstItem() ?? 0 }}</strong> sampai <strong>{{ $absensiRaw->lastItem() ?? 0 }}</strong> dari <strong>{{ $absensiRaw->total() }}</strong> tanggal
                </div>
                <div>
                    {{ $absensiRaw->appends(['tanggal_cari' => $searchDate])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-link { border-radius: 8px !important; margin: 0 2px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background-color: #198754; border-color: #198754; }
</style>
@endsection