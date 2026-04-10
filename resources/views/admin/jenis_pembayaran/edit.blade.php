<h2>Edit Jenis Pembayaran</h2>

@if($errors->any())
    <div style="color:red">
        @foreach($errors->all() as $e)
            <div>{{ $e }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.jenis-pembayaran.update', $data->id_jenis) }}">
    @csrf
    @method('PUT')

    <label>Nama</label>
    <input type="text" name="nama" value="{{ old('nama', $data->nama) }}" required>

    <label>Nominal</label>
    <input type="number" name="nominal" value="{{ old('nominal', $data->nominal) }}" required>

    <label>Keterangan</label>
    <textarea name="keterangan">{{ old('keterangan', $data->keterangan) }}</textarea>

    <button>Update</button>
</form>