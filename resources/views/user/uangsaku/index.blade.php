@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-1 opacity-75 fw-medium">Saldo Uang Saku Saat Ini</p>
                        <h1 class="fw-bold mb-0">
                            Rp {{ number_format($data->first()->saldo ?? 0, 0, ',', '.') }}
                        </h1>
                        <small class="opacity-75">
                            *Terakhir diperbarui: {{ $data->first() ? \Carbon\Carbon::parse($data->first()->tanggal)->translatedFormat('d F Y') : '-' }}
                        </small>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="fas fa-wallet fa-5x opacity-25"></i>
                    </div>
                </div>
                <div class="position-absolute translate-middle" style="right: -50px; bottom: -80px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history me-2 text-primary"></i>Riwayat Transaksi</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th class="pe-4">Sisa Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $u)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d M Y') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($u->created_at)->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                @if($u->jenis == 'masuk')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                        <i class="fas fa-arrow-down small me-1"></i> Masuk
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                        <i class="fas fa-arrow-up small me-1"></i> Keluar
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark small">{{ $u->keterangan ?? '-' }}</span>
                            </td>
                            <td class="fw-bold {{ $u->jenis == 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $u->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($u->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="pe-4 fw-bold text-dark">
                                Rp {{ number_format($u->saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">Belum ada catatan transaksi uang saku.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-subtle { background-color: #d1e7dd; }
    .bg-danger-subtle { background-color: #f8d7da; }
    .card-content { border-radius: 15px; }
    .table thead th { font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px; }
</style>
@endsection