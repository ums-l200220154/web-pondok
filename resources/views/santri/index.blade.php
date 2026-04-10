<h3>Data Santri</h3>

<a href="{{ route('santri.create') }}">➕ Tambah Santri</a>

<table border="1">
    <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>TTL</th>
        <th>Aksi</th>
    </tr>

    @foreach($santri as $s)
<tr>
    <td>{{ $s->nis }}</td>
    <td>{{ $s->nama }}</td>
    <td>{{ $s->ttl }}</td>
    <td>

        {{-- EDIT & DELETE (ADMIN SAJA) --}}
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('santri.edit', $s->nis) }}">✏️ Edit</a>

            <form action="{{ route('santri.destroy', $s->nis) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Hapus santri?')">🗑️ Hapus</button>
            </form>
        @endif

        {{-- ABSENSI (HANYA KEAMANAN) --}}
        @if(auth()->user()->role == 'keamanan')
            <a href="{{ route('keamanan.absensi.index', $s->nis) }}">
                📋 Absensi
            </a>
        @endif

    </td>
</tr>
@endforeach
</table>
