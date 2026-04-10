@extends('layouts.app')

@section('title', 'Input Absensi Harian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-content shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="fw-bold text-success mb-0"><i class="fas fa-fingerprint me-2"></i>Absensi Santri</h4>
                        <small class="text-muted">Hari ini: {{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</small>
                    </div>
                    <div class="col-md-6 mt-2 mt-md-0">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchSantri" class="form-control bg-light border-0" placeholder="Cari nama santri...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tableSantri">
                        <thead class="table-light">
                            <tr>
                                <th>Santri</th>
                                <th class="text-center">Sesi Pagi (04:00-15:59)</th>
                                <th class="text-center">Sesi Malam (16:00-03:59)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($santri as $s)
                            <tr class="santri-row">
                                <td>
                                    <div class="fw-bold">{{ $s->nama }}</div>
                                    <div class="small text-muted">NIS: {{ $s->nis }}</div>
                                </td>
                                <td class="text-center">
                                    @include('keamanan.partials.button_absen', ['nis' => $s->nis, 'waktu' => 'pagi', 'isActive' => $isPagiActive])
                                </td>
                                <td class="text-center">
                                    @include('keamanan.partials.button_absen', ['nis' => $s->nis, 'waktu' => 'malam', 'isActive' => $isMalamActive])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white py-3 border-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="text-muted small">
            Menampilkan {{ $santri->firstItem() }} - {{ $santri->lastItem() }} dari {{ $santri->total() }} santri
        </div>
        <div>
            {{ $santri->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INPUT --}}
<div class="modal fade" id="modalAbsen" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('keamanan.absensi.harian.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <input type="hidden" name="nis" id="m-nis">
            <input type="hidden" name="waktu" id="m-waktu">
            <div class="modal-body p-4">
                <h5 class="fw-bold mb-1" id="m-nama"></h5>
                <p class="text-muted small mb-4">Pilih keterangan absen sesi <span id="m-label-waktu" class="fw-bold text-primary"></span></p>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="keterangan" value="hadir" class="btn btn-outline-success py-2 fw-bold"><i class="fas fa-check me-2"></i> HADIR</button>
                    <button type="submit" name="keterangan" value="sakit" class="btn btn-outline-warning py-2 fw-bold"><i class="fas fa-medkit me-2"></i> SAKIT</button>
                    <button type="submit" name="keterangan" value="tidak hadir" class="btn btn-outline-danger py-2 fw-bold"><i class="fas fa-times me-2"></i> TIDAK HADIR</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Pencarian
    document.getElementById('searchSantri').addEventListener('keyup', function(){
        let val = this.value.toLowerCase();
        document.querySelectorAll('.santri-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // Modal Data Fill
    const modal = document.getElementById('modalAbsen');
    modal.addEventListener('show.bs.modal', function (event) {
        let btn = event.relatedTarget;
        document.getElementById('m-nis').value = btn.dataset.nis;
        document.getElementById('m-waktu').value = btn.dataset.waktu;
        document.getElementById('m-nama').innerText = btn.dataset.nama;
        document.getElementById('m-label-waktu').innerText = btn.dataset.waktu.toUpperCase();
    });
</script>
@endsection