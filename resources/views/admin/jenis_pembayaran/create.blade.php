<h2>Tambah Jenis Pembayaran</h2>

<form method="POST" action="{{ route('admin.jenis-pembayaran.store') }}">
@csrf

<label>Nama</label>
<input type="text" name="nama">

<label>Nominal</label>
<input type="number" name="nominal">

<label>Keterangan</label>
<textarea name="keterangan"></textarea>

<button>Simpan</button>
</form>