@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4 bg-primary text-white">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-graduate text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="ms-3">
                    <h4 class="fw-bold mb-0 text-white">{{ $santri->nama }}</h4>
                    <p class="mb-0 opacity-75 small">NIS: {{ $santri->nis }} | Santri Pondok Pesantren</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                <span class="text-muted small d-block mb-1">Hadir</span>
                <h5 class="fw-bold text-success mb-0">{{ $absensi->where('keterangan', 'hadir')->count() }}</h5>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                <span class="text-muted small d-block mb-1">Sakit</span>
                <h5 class="fw-bold text-warning mb-0">{{ $absensi->where('keterangan', 'sakit')->count() }}</h5>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                <span class="text-muted small d-block mb-1">Izin/Alpa</span>
                <h5 class="fw-bold text-danger mb-0">{{ $absensi->where('keterangan', 'tidak hadir')->count() }}</h5>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-calendar-alt me-2 text-primary"></i>Riwayat Kehadiran</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Waktu</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $a)
                    <tr>
                        <td class="ps-4 text-dark fw-medium">
                            {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td>
                            @if($a->waktu == 'pagi')
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                    <i class="fas fa-sun me-1"></i> Pagi
                                </span>
                            @else
                                <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-2">
                                    <i class="fas fa-moon me-1"></i> Malam
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $color = [
                                    'hadir' => 'bg-success',
                                    'sakit' => 'bg-warning text-dark',
                                    'tidak hadir' => 'bg-danger'
                                ][$a->keterangan] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $color }} rounded-pill px-3 py-2 text-uppercase fw-bold" style="font-size: 10px; width: 100px;">
                                {{ $a->keterangan }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Belum ada catatan absensi masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Custom Styling tambahan */
    .bg-info-subtle { background-color: #e0f7fa !important; }
    .text-info { color: #00acc1 !important; }
    .bg-dark-subtle { background-color: #f5f5f5 !important; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .badge { letter-spacing: 0.5px; }
    .rounded-4 { border-radius: 1rem !important; }
</style>
@endsection