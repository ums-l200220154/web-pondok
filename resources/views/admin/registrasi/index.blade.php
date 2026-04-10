@extends('layouts.app')

@section('content')
<div class="card card-content border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-0">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <h4 class="fw-bold text-dark mb-0"><i class="fas fa-users-cog me-2 text-primary"></i>Manajemen User</h4>
            </div>

            <div class="col-md-8 text-md-end">
                <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                    <form action="{{ route('admin.registrasi.index') }}" method="GET" class="d-inline-block">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0" 
                                   placeholder="Cari username / role..." value="{{ $search }}" style="width: 200px;">
                            @if($search)
                                <a href="{{ route('admin.registrasi.index') }}" class="btn btn-outline-secondary border-0 bg-light text-muted">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary px-3 shadow-sm">Cari</button>
                        </div>
                    </form>

                    <a href="{{ route('admin.register.create') }}" class="btn btn-success btn-sm shadow-sm rounded-pill px-3">
                        <i class="fas fa-plus me-1"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Terhubung Santri</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-bold text-dark">{{ $u->username }}</td>
                    <td>
                        @php
                            $badgeColor = [
                                'admin' => 'bg-danger text-white',
                                'bendahara' => 'bg-warning text-dark',
                                'keamanan' => 'bg-success text-white',
                                'user' => 'bg-info text-dark'
                            ][$u->role] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeColor }} rounded-pill px-3 py-2 small text-uppercase">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td>
                        @if($u->santri)
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-graduate text-primary small"></i>
                                </div>
                                <div>
                                    <div class="fw-medium mb-0">{{ $u->santri->nama }}</div>
                                    <small class="text-muted">{{ $u->santri->nis }}</small>
                                </div>
                            </div>
                        @else
                            <span class="text-muted small"><em>- Tidak Terhubung -</em></span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group gap-1">
                            <form action="{{ route('admin.register.destroy', $u->id_user) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" 
                                        onclick="return confirm('Hapus user {{ $u->username }}?')" 
                                        title="Hapus User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Tidak ada user ditemukan dengan kata kunci "{{ $search }}"</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }}</strong> sampai <strong>{{ $users->lastItem() ?? 0 }}</strong> dari <strong>{{ $users->total() }}</strong> user
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .card-content { border-radius: 15px; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .badge { font-weight: 600; letter-spacing: 0.5px; }
</style>
@endsection