@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-primary mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Jenis Pembayaran</h4>
            <p class="text-muted small mb-0">Kelola kategori tagihan bulanan atau tahunan santri.</p>
        </div>
        <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i>Tambah Jenis
        </button>
    </div>

    @if(session('success'))
        <div class="px-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase fw-bold text-muted">
                <tr>
                    <th class="ps-4">Nama Pembayaran</th>
                    <th>Nominal</th>
                    <th>Keterangan</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td class="ps-4 fw-bold text-dark">{{ $d->nama }}</td>
                    <td class="text-success fw-bold">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                    <td><small class="text-muted">{{ $d->keterangan ?? '-' }}</small></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-warning border-0 rounded-circle me-1" 
                                title="Edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEdit{{ $d->id_jenis }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.jenis-pembayaran.destroy', $d->id_jenis) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" 
                                    onclick="return confirm('Hapus jenis pembayaran ini?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="modalEdit{{ $d->id_jenis }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <form action="{{ route('admin.jenis-pembayaran.update', $d->id_jenis) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-header border-0">
                                    <h5 class="fw-bold mb-0">Edit Jenis Pembayaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nama Pembayaran</label>
                                        <input type="text" name="nama" class="form-control" value="{{ $d->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Nominal (Rp)</label>
                                        <input type="number" name="nominal" class="form-control" value="{{ $d->nominal }}" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small fw-bold">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="2">{{ $d->keterangan }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary px-4">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data jenis pembayaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.jenis-pembayaran.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="fw-bold mb-0">Tambah Jenis Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Pembayaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Misal: SPP Bulanan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 15px; overflow: hidden; }
    .form-control:focus { box-shadow: none; border-color: #0d6efd; }
    .table thead th { font-size: 11px; letter-spacing: 1px; }
</style>
@endsection