@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.santri.index') }}" class="btn btn-link text-success p-0 text-decoration-none fw-bold">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-content border-0 shadow-sm text-center p-4 mb-4">
            <div class="mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                <i class="fas fa-user-graduate fa-3x text-success"></i>
            </div>
            <h5 class="fw-bold mb-1">{{ $santri->nama }}</h5>
            <p class="text-muted small">NIS: {{ $santri->nis }}</p>
            <hr>
            <div class="text-start">
                <small class="text-muted d-block">NIK</small>
                <p class="fw-bold mb-2">{{ $santri->nik }}</p>
                <small class="text-muted d-block">No. HP</small>
                <p class="fw-bold mb-0 text-success">{{ $santri->no_hp }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-content border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Informasi Lengkap & Edit Data</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.santri.update', $santri->nis) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control shadow-none" value="{{ $santri->nama }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">NIK</label>
                            <input type="text" name="nik" class="form-control shadow-none" value="{{ $santri->nik }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control shadow-none" value="{{ $santri->tempat_lahir }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control shadow-none" value="{{ $santri->tanggal_lahir }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control shadow-none" rows="2" required>{{ $santri->alamat }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control shadow-none" value="{{ $santri->nama_ayah }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control shadow-none" value="{{ $santri->no_hp }}" required>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <button type="submit" class="btn btn-success px-4 shadow-sm">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                        
                        <form action="{{ route('admin.santri.destroy', $santri->nis) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none small" onclick="return confirm('Hapus santri ini secara permanen?')">
                                <i class="fas fa-trash me-1"></i> Hapus Santri
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection