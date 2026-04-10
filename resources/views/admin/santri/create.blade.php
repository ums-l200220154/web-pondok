@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('admin.santri.index') }}" class="btn btn-link text-success p-0 text-decoration-none fw-bold">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-content border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="fw-bold mb-0 text-success"><i class="fas fa-user-plus me-2"></i>Tambah Santri Baru</h4>
                    <p class="text-muted small mb-0">Lengkapi formulir di bawah ini untuk mendaftarkan santri.</p>
                </div>
                <div class="card-body p-4">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.santri.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nomor Induk Santri (NIS)</label>
                                <input type="text" name="nis" class="form-control bg-light border-0 shadow-none" placeholder="Contoh: 2024001" value="{{ old('nis') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">NIK (Sesuai KK)</label>
                                <input type="text" name="nik" class="form-control bg-light border-0 shadow-none" placeholder="16 digit NIK" value="{{ old('nik') }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Nama Lengkap Santri</label>
                                <input type="text" name="nama" class="form-control bg-light border-0 shadow-none" placeholder="Nama lengkap tanpa gelar" value="{{ old('nama') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control bg-light border-0 shadow-none" placeholder="Kota/Kabupaten" value="{{ old('tempat_lahir') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control bg-light border-0 shadow-none" value="{{ old('tanggal_lahir') }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="Nama Jalan, Desa, RT/RW, Kecamatan" required>{{ old('alamat') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Ayah Kandung</label>
                                <input type="text" name="nama_ayah" class="form-control bg-light border-0 shadow-none" placeholder="Nama Ayah" value="{{ old('nama_ayah') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nomor HP/WhatsApp</label>
                                <input type="text" name="no_hp" class="form-control bg-light border-0 shadow-none" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" required>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-top d-flex justify-content-end">
                            <button type="reset" class="btn btn-light px-4 me-2 fw-bold">Reset</button>
                            <button type="submit" class="btn btn-success px-5 shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i>Simpan Data Santri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-content { border-radius: 15px; }
    .form-control { border-radius: 10px; padding: 10px 15px; }
    .form-control:focus { background-color: #fff !important; border: 1px solid #198754 !important; }
</style>
@endsection