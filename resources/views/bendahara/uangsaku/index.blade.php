@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col">
        <h4 class="fw-bold text-dark mb-1">Manajemen Uang Saku</h4>
        <p class="text-muted small mb-0">Kelola tabungan harian dan uang saku seluruh santri.</p>
    </div>
    <div class="col-md-4 mt-3 mt-md-0">
        <form action="{{ route('bendahara.uangsaku.index') }}" method="GET">
            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Cari Nama atau NIS..." value="{{ $search }}">
                <button class="btn btn-primary px-3" type="submit">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-muted small text-uppercase fw-bold">Santri</th>
                    <th class="py-3 text-muted small text-uppercase fw-bold">NIS</th>
                    <th class="py-3 text-muted small text-uppercase fw-bold">Status Saldo</th>
                    <th class="text-end pe-4 py-3 text-muted small text-uppercase fw-bold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($santris as $s)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3 bg-primary bg-opacity-10 text-primary fw-bold">
                                {{ strtoupper(substr($s->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $s->nama }}</div>
                                <div class="text-muted small">Santri Aktif</div>
                            </div>
                        </div>
                    </td>
                    <td><code class="text-primary fw-medium">{{ $s->nis }}</code></td>
                    <td>
                        @php $saldo = \App\Models\UangSaku::where('nis', $s->nis)->latest('id_uangsaku')->value('saldo') ?? 0; @endphp
                        <span class="fw-bold {{ $saldo < 10000 ? 'text-danger' : 'text-dark' }}">
                            Rp {{ number_format($saldo, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('bendahara.uangsaku.show', $s->nis) }}" class="btn btn-sm btn-white border shadow-sm px-3 rounded-pill hover-primary">
                            Kelola Saldo <i class="fas fa-arrow-right ms-1 small"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Data santri tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Pagination Santri --}}
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Menampilkan {{ $santris->firstItem() ?? 0 }} - {{ $santris->lastItem() ?? 0 }} dari {{ $santris->total() }} santri
            </div>
            <div>
                {{ $santris->appends(['search' => $search])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .hover-primary:hover {
        background-color: #1a5d1a !important;
        color: white !important;
    }
    .page-link { border-radius: 8px !important; margin: 0 2px; }
</style>
@endsection