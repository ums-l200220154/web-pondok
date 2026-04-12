@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-0">
        <div class="row align-items-center gy-3">
            <div class="col-12 col-md-6 text-center text-md-start">
                <h4 class="fw-bold mb-0">Konfirmasi Pembayaran</h4>
                <p class="text-muted small mb-0">Verifikasi data & bukti pembayaran santri</p>
            </div>

            <div class="col-12 col-md-6">
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-md-end">
                    {{-- Form Pencarian --}}
                    <form action="{{ route('bendahara.pembayaran.index') }}" method="GET" class="flex-grow-1 flex-md-grow-0">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control border-end-0 rounded-start-pill ps-3" 
                                   placeholder="Cari Nama/NIS..." value="{{ $search }}">
                            <button class="btn btn-outline-secondary border-start-0 rounded-end-pill px-3" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Tombol Input Manual --}}
                    <a href="{{ route('bendahara.pembayaran.manual') }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm d-flex align-items-center justify-content-center">
                        <i class="fas fa-plus me-1"></i> <span>Input Manual</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="min-width: 700px;">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Nama Santri</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $p)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">{{ date('d M Y', strtotime($p->tanggal_pembayaran)) }}</div>
                        <div class="text-muted small">ID: #{{ $p->id_pembayaran }}</div>
                    </td>
                    <td>
                        <strong>{{ $p->santri->nama ?? 'N/A' }}</strong>
                        <br>
                        <small class="text-muted">NIS: {{ $p->nis }}</small>
                    </td>
                    <td class="text-success fw-bold">
                        Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                    </td>
                    <td>
                        @php
                            $badgeClass = [
                                'lunas' => 'bg-success',
                                'belum lunas' => 'bg-warning text-dark',
                                'ditolak' => 'bg-danger',
                                'menunggu' => 'bg-secondary'
                            ][$p->status] ?? 'bg-dark';
                        @endphp
                        <span class="badge rounded-pill {{ $badgeClass }}">
                            {{ strtoupper($p->status) }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('bendahara.pembayaran.show', $p->id_pembayaran) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                            <i class="fas fa-eye me-1"></i> Periksa
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                        <p>Tidak ada data pembayaran ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer dengan Pagination --}}
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Menampilkan <strong>{{ $data->firstItem() ?? 0 }}</strong> sampai <strong>{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> transaksi
            </div>
            <div>
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Pagination Modern */
    .pagination { margin-bottom: 0; }
    .page-link { 
        padding: 0.5rem 0.85rem; 
        font-size: 0.85rem;
        border-radius: 8px !important;
        margin: 0 2px;
        color: #1a5d1a;
        border: 1px solid #dee2e6;
    }
    .page-item.active .page-link {
        background-color: #1a5d1a;
        border-color: #1a5d1a;
        color: white;
    }
    .table thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6c757d;
    }
    /* Mengatur lebar input pencarian di desktop agar tidak terlalu panjang */
    @media (min-width: 768px) {
        .flex-md-grow-0 {
            width: 250px;
        }
    }
</style>
@endsection