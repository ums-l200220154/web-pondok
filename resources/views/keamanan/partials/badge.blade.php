@if($absen)
    @php
        $bg = 'bg-secondary';
        if($absen->keterangan == 'hadir') $bg = 'bg-success';
        elseif($absen->keterangan == 'sakit') $bg = 'bg-warning text-dark';
        elseif($absen->keterangan == 'tidak hadir') $bg = 'bg-danger';
    @endphp
    <span class="badge {{ $bg }} text-uppercase px-3 py-2" style="min-width: 100px;">
        {{ $absen->keterangan }}
    </span>
@else
    <span class="text-muted small"><em>Belum Absen</em></span>
@endif