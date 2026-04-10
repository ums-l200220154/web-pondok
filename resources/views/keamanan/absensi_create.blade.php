<h3>Tambah Absensi</h3>

<p>
    <strong>{{ $santri->nama }}</strong> ({{ $santri->nis }})
</p>

<form method="POST" action="{{ route('keamanan.absensi.store', $santri->nis) }}">
@csrf

<label>Tanggal</label><br>
<input type="date" name="tanggal" required><br><br>

<label>Waktu</label><br>
<select name="waktu" required>
    <option value="pagi">Pagi</option>
    <option value="malam">Malam</option>
</select><br><br>

<label>Keterangan</label><br>
<select name="keterangan" required>
    <option value="hadir">Hadir</option>
    <option value="sakit">Sakit</option>
    <option value="tidak hadir">Tidak Hadir</option>
</select><br><br>

<button>Simpan</button>
<a href="{{ route('keamanan.absensi.index', $santri->nis) }}">Batal</a>
</form>
