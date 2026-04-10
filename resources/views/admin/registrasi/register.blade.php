@extends('layouts.app')

@section('content')
<h2>Tambah User Baru</h2>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.register.store') }}" method="POST">
    @csrf
    <label>Username:</label>
    <input type="text" name="username" value="{{ old('username') }}" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <label>Konfirmasi Password:</label>
    <input type="password" name="password_confirmation" required><br>

    <label>Role:</label>
    <select name="role" required>
    <option value="">-- Pilih Role --</option>
    <option value="admin">Admin</option>
    <option value="user">User</option>
    <option value="bendahara">Bendahara</option>
    <option value="keamanan">Keamanan</option>
</select>

    <label>Santri (opsional):</label>
    <select name="nis_FK">
        <option value="">-</option>
        @foreach($santri as $s)
            <option value="{{ $s->nis }}" {{ old('nis_FK')==$s->nis?'selected':'' }}>
                {{ $s->nama }}
            </option>
        @endforeach
    </select><br>

    <button type="submit">Simpan</button>
</form>

<a href="{{ route('admin.register') }}">Kembali ke daftar user</a>
@endsection
