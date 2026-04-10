@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-0">Rekapitulasi Pembayaran Santri</h4>
            <p class="text-muted small mb-0">Pantau status kelunasan iuran tahun {{ date('Y') }}</p>
        </div>
        <form action="{{ route('bendahara.pembayaran.rekap') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control rounded-start-pill px-3" placeholder="Cari Nama/NIS..." value="{{ $search ?? '' }}">
                <button class="btn btn-success rounded-end-pill px-3" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small text-uppercase">Santri</th>
                        <th class="py-3 text-muted small text-uppercase">Progress Iuran {{ date('Y') }}</th>
                        <th class="py-3 text-muted small text-uppercase">Total Terbayar</th>
                        <th class="text-end pe-4 py-3 text-muted small text-uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santri as $s)
                        @php
                            // Hitung jumlah bulan unik yang sudah lunas tahun ini
                            $lunasCount = $s->pembayaran
                                ->where('status', 'lunas')
                                ->flatMap->rincian
                                ->where('kategori', 'pembayaran')
                                ->where('tahun', date('Y'))
                                ->pluck('bulan')
                                ->unique()
                                ->count();
                            
                            $persen = ($lunasCount / 12) * 100;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 me-3 d-none d-sm-block">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $s->nama }}</h6>
                                        <small class="text-muted">{{ $s->nis }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center" style="min-width: 150px;">
                                    <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                        <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $persen }}%"></div>
                                    </div>
                                    <small class="fw-bold text-dark">{{ $lunasCount }}/12</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($s->pembayaran->where('status', 'lunas')->sum('total_bayar'), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('bendahara.pembayaran.rekap.detail', $s->nis) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    <i class="fas fa-history me-1"></i> Histori
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                                <p>Data santri tidak ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer dengan Navigasi Halaman --}}
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $santri->firstItem() ?? 0 }}</strong> sampai <strong>{{ $santri->lastItem() ?? 0 }}</strong> dari <strong>{{ $santri->total() }}</strong> santri
                </div>
                <div>
                    {{ $santri->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .progress { background-color: #f0f0f0; overflow: visible; }
    .page-link { border-radius: 8px !important; margin: 0 2px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background-color: #198754; border-color: #198754; }
    .table-hover tbody tr:hover { background-color: #f8fff9; transition: 0.2s; }
</style>
@endsection