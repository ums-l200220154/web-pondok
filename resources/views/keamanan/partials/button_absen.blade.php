@php
    $key = $nis . '_' . $waktu;
    $dataAbsen = $absensi[$key] ?? null;
    
    // Menentukan Kelas Warna
    $colorClass = 'btn-outline-secondary opacity-50';
    $label = 'Belum Absen';
    
    if($dataAbsen) {
        if($dataAbsen->keterangan == 'hadir') {
            $colorClass = 'btn-success'; $label = 'HADIR';
        } elseif($dataAbsen->keterangan == 'sakit') {
            $colorClass = 'btn-warning text-white'; $label = 'SAKIT';
        } else {
            $colorClass = 'btn-danger'; $label = 'TIDAK HADIR';
        }
    }
@endphp

<button type="button" 
        class="btn btn-absen {{ $colorClass }}" 
        data-bs-toggle="modal" 
        data-bs-target="#modalAbsen"
        data-nis="{{ $nis }}" 
        data-waktu="{{ $waktu }}" 
        data-nama="{{ $s->nama }}">
    {{ $label }}
</button>