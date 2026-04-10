@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-3">
            <a href="{{ route('admin.registrasi.index') }}" class="btn btn-link text-success p-0 text-decoration-none fw-bold">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar User
            </a>
        </div>

        <div class="card card-content border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h4 class="fw-bold text-dark mb-0"><i class="fas fa-user-plus me-2 text-success"></i>Tambah User Baru</h4>
                <p class="text-muted small mb-0">Daftarkan akun baru untuk Admin, Bendahara, Keamanan, atau Santri.</p>
            </div>
            
            <div class="card-body p-4">
                {{-- Alert Error --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.register.store') }}" method="POST">
                    @csrf

                    {{-- Tambahkan field Email di dalam form create.blade.php --}}
                    <div class="col-md-12 mt-3">
                        <label class="form-label small fw-bold text-muted">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" placeholder="contoh@pondok.com" required>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-at text-muted"></i></span>
                                <input type="text" name="username" class="form-control bg-light border-0 @error('username') is-invalid @enderror" 
                                       value="{{ old('username') }}" placeholder="Masukkan username unik" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-0" 
                                       placeholder="Minimal 6 karakter" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-check-double text-muted"></i></span>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-0" 
                                       placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Role Akses</label>
                            <select name="role" id="roleSelect" class="form-select bg-light border-0" required>
                                <option value="" disabled selected>-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full Akses)</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Santri)</option>
                                <option value="bendahara" {{ old('role') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="keamanan" {{ old('role') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="santriGroup">
                            <label class="form-label small fw-bold text-muted">Hubungkan ke Santri</label>
                            <select name="nis_FK" class="form-select bg-light border-0">
                                <option value="">- Bukan Santri -</option>
                                @foreach($santri as $s)
                                    <option value="{{ $s->nis }}" {{ old('nis_FK') == $s->nis ? 'selected' : '' }}>
                                        [{{ $s->nis }}] {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted" style="font-size: 10px;">*Wajib diisi jika role adalah "User"</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm rounded-pill">
                            <i class="fas fa-save me-2"></i>Simpan User Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script sederhana untuk mempercantik interaksi --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const santriGroup = document.getElementById('santriGroup');

        // Menampilkan/Sembunyikan dropdown santri jika role adalah 'user'
        roleSelect.addEventListener('change', function() {
            if (this.value === 'user') {
                santriGroup.classList.remove('opacity-50');
            } else {
                santriGroup.classList.add('opacity-50');
            }
        });
    });
</script>

<style>
    .card-content { border-radius: 15px; }
    .input-group-text { border-radius: 10px 0 0 10px; }
    .form-control, .form-select { border-radius: 0 10px 10px 0; padding: 10px 15px; }
    .form-control:focus, .form-select:focus { box-shadow: none; background-color: #f0fdf4 !important; }
    .opacity-50 { opacity: 0.5; }
</style>
@endsection