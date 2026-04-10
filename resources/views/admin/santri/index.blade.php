@extends('layouts.app')

@section('content')
<div class="card card-content border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h4 class="fw-bold text-success mb-0">Data Santri</h4>
            </div>
            <div class="col-md-8 text-md-end mt-2 mt-md-0">
                <form action="{{ route('admin.santri.index') }}" method="GET" class="d-inline-block me-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama/nis..." value="{{ $search }}">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                
                <button type="button" class="btn btn-sm btn-outline-success shadow-sm me-1" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                    <i class="fas fa-file-excel me-1"></i> Import Excel
                </button>

                <a href="{{ route('admin.santri.create') }}" class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Santri
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">NIS</th>
                    <th>Nama Santri</th>
                    <th>No. HP</th>
                    <th>Ayah</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($santri as $s)
                <tr>
                    <td class="ps-4">{{ $s->nis }}</td>
                    <td>
                        <div class="fw-bold">{{ $s->nama }}</div>
                        <small class="text-muted">{{ $s->alamat }}</small>
                    </td>
                    <td>{{ $s->no_hp }}</td>
                    <td>{{ $s->nama_ayah }}</td>
                    <td class="text-end pe-4">
                        <div class="btn-group">
                            <a href="{{ route('admin.santri.show', $s->nis) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.santri.destroy', $s->nis) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fas fa-user-slash fa-3x mb-3 d-block"></i>
                        Data santri tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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

{{-- MODAL IMPORT --}}
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.santri.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        Format header: <strong>nis, nik, nama, alamat, tempat_lahir, tanggal_lahir, nama_ayah, no_hp</strong>.
                    </div>
                    <div class="form-group">
                        <label class="mb-2">Pilih File Excel (.xlsx)</label>
                        <input type="file" name="file_excel" class="form-control" required accept=".xlsx, .xls">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection