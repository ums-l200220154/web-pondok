@extends('layouts.app')

@section('content')
<h2>Tambah Pengambilan Harian: {{ $santri->nama }} ({{ $santri->nis }})</h2>

<!-- Pesan sukses -->
@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
@endif

<!-- Pesan error -->
@if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('bendahara.uangsaku.store', $santri->nis) }}">
    @csrf
    <div>
        <label for="jumlah">Jumlah (Rp):</label><br>
        <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required>
    </div>

    <br>
    <button type="submit">Simpan</button>
    <a href="{{ route('bendahara.uangsaku.show', $santri->nis) }}">Kembali</a>
</form>
@endsection