@extends('layouts.app')

@section('title', 'Histori Absensi')

@section('content')
<div class="container py-4">
    <div class="card card-content border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h4 class="fw-bold text-success mb-0"><i class="fas fa-history me-2"></i>Histori Absensi</h4>
                
                {{-- Form Pencarian Server Side (PHP) --}}
                <form action="{{ route('keamanan.histori') }}" method="GET">
                    <div class="input-group" style="min-width: 320px;">
                        <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Nama atau NIS..." value="{{ $search ?? '' }}">
                        <button type="submit" class="btn btn-success px-4 rounded-end-pill">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">NIS Santri</th>
                            <th class="py-3">Nama Lengkap</th>
                            <th class="text-end pe-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santri as $s)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $s->nis }}</td>
                            <td class="fw-medium text-dark">{{ $s->nama }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('keamanan.absensi.index', $s->nis) }}" class="btn btn-sm btn-success rounded-pill px-4 shadow-sm">
                                    <i class="fas fa-chart-bar me-1"></i> Lihat Histori
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                                <p>Data santri tidak ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER PAGINATION --}}
        <div class="card-footer bg-white py-3 border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $santri->firstItem() ?? 0 }}</strong> - <strong>{{ $santri->lastItem() ?? 0 }}</strong> dari <strong>{{ $santri->total() }}</strong> santri
                </div>
                <div>
                    {{ $santri->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-link { border-radius: 8px !important; margin: 0 2px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background-color: #198754; border-color: #198754; }
    .table-hover tbody tr:hover { background-color: #f8fff9; transition: 0.2s; }
</style>
@endsection