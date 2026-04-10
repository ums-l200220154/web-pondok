@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Top Header --}}
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('bendahara.uangsaku.index') }}" class="btn btn-white shadow-sm rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">{{ $santri->nama }}</h4>
            <span class="text-muted small">NIS: {{ $santri->nis }}</span>
        </div>
    </div>

    {{-- BARIS ATAS: Saldo & Aksi --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 text-white h-100" style="background: linear-gradient(135deg, #1a5d1a 0%, #2ecc71 100%); min-height: 180px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="text-white-50 small text-uppercase tracking-wider mb-0">Total Saldo Tabungan</h6>
                        <i class="fas fa-wallet fa-2x opacity-25"></i>
                    </div>
                    <h1 class="fw-bold mb-0">Rp {{ number_format($saldoSekarang, 0, ',', '.') }}</h1>
                    <div class="mt-3">
                        <span class="badge bg-white bg-opacity-20 rounded-pill px-3 py-2">Status: Akun Terverifikasi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-bolt me-2 text-warning"></i>Aksi Cepat</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-success w-100 py-3 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMasuk">
                                <i class="fas fa-plus-circle d-block mb-1 fa-lg"></i>
                                <span class="small fw-bold">Setoran</span>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-danger w-100 py-3 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKeluar">
                                <i class="fas fa-minus-circle d-block mb-1 fa-lg"></i>
                                <span class="small fw-bold">Penarikan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS BAWAH: Riwayat Mutasi --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white p-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-0">Riwayat Mutasi</h5>
                <p class="text-muted small mb-0">Daftar transaksi masuk dan keluar</p>
            </div>
            <form action="{{ route('bendahara.uangsaku.show', $santri->nis) }}" method="GET">
                <div class="input-group input-group-sm border rounded-pill px-3 bg-light">
                    <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Cari keterangan..." value="{{ $search }}">
                    <button class="btn btn-link text-muted" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small text-uppercase">Tanggal Transaksi</th>
                        <th class="py-3 text-muted small text-uppercase">Keterangan</th>
                        <th class="py-3 text-muted small text-uppercase text-end">Jumlah</th>
                        <th class="pe-4 py-3 text-muted small text-uppercase text-end">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($uangSaku as $u)
                    <tr>
                        <td class="ps-4">
                            {{-- Hanya menampilkan Tanggal karena data waktu tidak tersedia di DB --}}
                            <div class="fw-bold text-dark">
                                {{ \Carbon\Carbon::parse($u->tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="text-dark mb-1 small">{{ $u->keterangan }}</div>
                            <span class="badge {{ $u->jenis == 'masuk' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill" style="font-size: 0.7rem;">
                                {{ strtoupper($u->jenis) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold {{ $u->jenis == 'masuk' ? 'text-success' : 'text-danger' }}">
                            {{ $u->jenis == 'masuk' ? '+' : '-' }} {{ number_format($u->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="pe-4 text-end fw-medium text-muted">
                            {{ number_format($u->saldo, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-5 text-muted small">Belum ada riwayat transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Menampilkan {{ $uangSaku->firstItem() ?? 0 }} - {{ $uangSaku->lastItem() ?? 0 }} mutasi
                </div>
                <div>
                    {{ $uangSaku->appends(['search' => $search])->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SETORAN --}}
<div class="modal fade" id="modalMasuk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('bendahara.uangsaku.store', $santri->nis) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            @csrf
            <input type="hidden" name="jenis" value="masuk">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-bold mb-0">Setoran Tunai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="mb-3">
                    <label class="small fw-bold mb-2">Nominal Setoran</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-0">Rp</span>
                        <input type="number" name="jumlah" class="form-control bg-light border-0" required placeholder="0">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-2">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control bg-light border-0" required placeholder="Contoh: Kiriman orang tua">
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow">Proses Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PENARIKAN --}}
<div class="modal fade" id="modalKeluar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('bendahara.uangsaku.store', $santri->nis) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            @csrf
            <input type="hidden" name="jenis" value="keluar">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-bold mb-0">Penarikan Uang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="mb-3">
                    <label class="small fw-bold mb-2">Nominal Penarikan</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-0">Rp</span>
                        <input type="number" name="jumlah" class="form-control bg-light border-0" required placeholder="0">
                    </div>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold mb-2">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control bg-light border-0" required placeholder="Contoh: Belanja harian">
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="submit" class="btn btn-danger w-100 py-3 fw-bold rounded-3 shadow">Konfirmasi Keluar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-success-subtle { background-color: #e8f5e9; color: #2e7d32; }
    .bg-danger-subtle { background-color: #ffebee; color: #c62828; }
    .btn-white { background-color: white; border: 1px solid #eee; }
    .page-link { border-radius: 8px !important; margin: 0 2px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background-color: #1a5d1a; border-color: #1a5d1a; }
</style>
@endsection