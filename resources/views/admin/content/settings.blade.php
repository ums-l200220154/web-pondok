@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4"><i class="fas fa-cogs me-2"></i>Pengaturan Website</h3>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white fw-bold">Konten Unggulan (Dashboard Santri)</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="small fw-bold">Judul Konten</label>
                <input type="text" name="settings[featured_info][title]" class="form-control" value="{{ $contents['featured_info']->title ?? '' }}">
            </div>
            <div class="mb-3">
                <label class="small fw-bold">Gambar Banner</label>
                <input type="file" name="settings[featured_info][image]" class="form-control">
            </div>
            <div class="mb-3">
                <label class="small fw-bold">Keterangan / Isi</label>
                <textarea name="settings[featured_info][value]" class="form-control" rows="4">{{ $contents['featured_info']->value ?? '' }}</textarea>
            </div>
        </div>
    </div>
    <button class="btn btn-primary px-5 rounded-pill">Simpan Semua Perubahan</button>
</form>
        </div>
    </div>
</div>
@endsection