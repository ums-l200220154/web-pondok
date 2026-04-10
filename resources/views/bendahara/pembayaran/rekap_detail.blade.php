@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('bendahara.pembayaran.rekap') }}" class="text-decoration-none text-success fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Rekapitulasi
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="row align-items-center">
                    <div class="col-md-4 border-end">
                        <h5 class="fw-bold text-dark mb-1">{{ $santri->nama }}</h5>
                        <p class="text-muted">NIS: {{ $santri->nis }}</p>
                        <div class="bg-light p-3 rounded-4">
                            <small class="text-muted d-block">Total Kontribusi (Lunas)</small>
                            <h4 class="fw-bold text-success mb-0">Rp {{ number_format($riwayat->where('status', 'lunas')->sum('total_bayar'), 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-8 ps-md-5">
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Monitoring Iuran {{ date('Y') }}</h6>
                        <div class="row g-2 text-center">
                            @php
                                $listBulan = [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun', 7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'];
                                $bulanLunas = [
                                    1=>'januari', 2=>'februari', 3=>'maret', 4=>'april', 5=>'mei', 6=>'juni',
                                    7=>'juli', 8=>'agustus', 9=>'september', 10=>'oktober', 11=>'november', 12=>'desember'
                                ];
                            @endphp
                            @foreach($listBulan as $num => $nama)
                                <div class="col-3 col-md-2">
                                    <div class="p-2 rounded-3 border {{ in_array($bulanLunas[$num], $lunas) ? 'bg-success text-white' : 'bg-light text-muted border-dashed' }}" style="font-size: 0.75rem;">
                                        <strong>{{ $nama }}</strong><br>
                                        <i class="fas {{ in_array($bulanLunas[$num], $lunas) ? 'fa-check-circle' : 'fa-clock opacity-25' }}"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="fw-bold mb-3">Histori Semua Transaksi</h5>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>ID Transaksi</th>
                            <th>Rincian</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr>
                            <td class="ps-4 small">{{ $r->tanggal_pembayaran }}</td>
                            <td class="fw-bold text-dark">#{{ $r->id_pembayaran }}</td>
                            <td>
                                @foreach($r->rincian as $rin)
                                    <span class="badge bg-light text-dark border fw-normal">{{ $rin->jenis }} ({{ $rin->bulan ?? 'N/A' }})</span>
                                @endforeach
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($r->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 {{ $r->status == 'lunas' ? 'bg-success' : ($r->status == 'menunggu' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ asset('storage/'.$r->bukti) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="fas fa-image me-1 text-primary"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed { border: 2px dashed #dee2e6 !important; }
</style>
@endsection