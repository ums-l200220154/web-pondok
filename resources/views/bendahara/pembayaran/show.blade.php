@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('bendahara.pembayaran.index') }}" class="btn btn-link text-success p-0 text-decoration-none fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pembayaran
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body text-center p-4">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-user-graduate text-success fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $pembayaran->santri->nama }}</h5>
                <p class="text-muted small">NIS: {{ $pembayaran->nis }}</p>
                <hr class="opacity-50">
                <div class="text-start">
                    <label class="small text-muted d-block">Status Saat Ini:</label>
                    @if($pembayaran->status == 'menunggu')
                        <span class="badge bg-warning text-dark w-100 py-2">MENUNGGU KONFIRMASI</span>
                    @elseif($pembayaran->status == 'belum lunas')
                        <span class="badge bg-info text-dark w-100 py-2">BELUM LUNAS (SEBAGIAN)</span>
                    @elseif($pembayaran->status == 'ditolak')
                        <span class="badge bg-danger w-100 py-2">DITOLAK</span>
                    @else
                        <span class="badge bg-success w-100 py-2">LUNAS</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white fw-bold"><i class="fas fa-image me-2"></i>Bukti Transfer</div>
            <div class="card-body p-2 text-center">
                @if($pembayaran->bukti && $pembayaran->bukti != 'manual_entry.jpg')
                    <a href="{{ asset('storage/' . $pembayaran->bukti) }}" target="_blank">
                        <img src="{{ asset('storage/' . $pembayaran->bukti) }}" class="img-fluid rounded-3 shadow-sm">
                    </a>
                    <p class="small text-muted mt-2 mb-0">Klik gambar untuk memperbesar</p>
                @else
                    <div class="py-5 text-muted">
                        <i class="fas fa-file-invoice-dollar fa-3x mb-2 opacity-25"></i>
                        <p class="small">Input Manual / Tanpa Bukti</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Rincian Pembayaran</h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4 p-3 bg-light rounded-3 g-3">
                    <div class="col-6 col-md-4">
                        <label class="small text-muted">Untuk Periode</label>
                        <p class="fw-bold text-uppercase text-primary mb-0" style="font-size: 1.1rem;">
                            @php 
                                $rincianBulan = $pembayaran->rincian->where('kategori', 'pembayaran')->first(); 
                            @endphp
                            {{ $rincianBulan ? $rincianBulan->bulan : 'Uang Saku' }} {{ $rincianBulan ? $rincianBulan->tahun : '' }}
                        </p>
                    </div>
                    <div class="col-6 col-md-4 border-start">
                        <label class="small text-muted">Tanggal Upload</label>
                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-md-4 border-start d-none d-md-block">
                        <label class="small text-muted">ID Transaksi</label>
                        <p class="fw-bold mb-0">#TRX-{{ $pembayaran->id_pembayaran }}</p>
                    </div>
                </div>

                <table class="table">
                    <thead class="text-muted small">
                        <tr>
                            <th>DESKRIPSI PEMBAYARAN</th>
                            <th class="text-end">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaran->rincian as $r)
                        <tr>
                            <td class="py-3">
                                <i class="fas fa-check-circle text-success me-2"></i> {{ $r->jenis }}
                                @if($r->bulan)
                                    <span class="text-muted small">({{ $r->bulan }} {{ $r->tahun }})</span>
                                @endif
                            </td>
                            <td class="py-3 text-end fw-bold">Rp {{ number_format($r->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td class="py-3 fw-bold">TOTAL YANG DIBAYAR</td>
                            <td class="py-3 text-end text-success h4 fw-bold mb-0">
                                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                @if($pembayaran->status == 'menunggu')
                <div class="mt-4 pt-4 border-top">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <form action="{{ route('bendahara.pembayaran.approve', $pembayaran->id_pembayaran) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow">
                                    <i class="fas fa-check-double me-2"></i> SETUJU LUNAS
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-warning w-100 py-3 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#modalPartial">
                                <i class="fas fa-exclamation-triangle me-2"></i> KURANG BAYAR
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger w-100 py-3 fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#modalReject">
                                <i class="fas fa-times me-2"></i> TOLAK TOTAL
                            </button>
                        </div>
                    </div>
                    <p class="small text-muted mt-3 text-center">
                        *Menyetujui akan melunasi transaksi ini dan memperbarui riwayat kurang bayar pada bulan yang sama.
                    </p>
                </div>
                @else
                <div class="alert alert-info d-flex align-items-center mt-4 border-0 rounded-3">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Pembayaran Terverifikasi</h6>
                        <small>Status: {{ strtoupper($pembayaran->status) }}</small>
                        @if($pembayaran->keterangan_bendahara)
                            <div class="mt-1 small"><strong>Catatan:</strong> {{ $pembayaran->keterangan_bendahara }}</div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPartial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('bendahara.pembayaran.setBelumLunas', $pembayaran->id_pembayaran) }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="fw-bold mb-0">Konfirmasi Kurang Bayar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Gunakan ini jika nominal transfer tidak mencukupi. Status akan menjadi <strong>BELUM LUNAS</strong>.</p>
                    <label class="small fw-bold mb-2">Catatan Kekurangan:</label>
                    <textarea name="keterangan_admin" class="form-control rounded-3" rows="3" placeholder="Contoh: Uang kurang Rp 25.000, harap segera melunasi..." required></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('bendahara.pembayaran.reject', $pembayaran->id_pembayaran) }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="fw-bold mb-0">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Gunakan ini jika bukti transfer tidak valid/palsu. Status akan menjadi <strong>DITOLAK</strong>.</p>
                    <textarea name="keterangan_admin" class="form-control rounded-3" rows="4" placeholder="Contoh: Bukti transfer tidak terbaca atau tidak valid..." required></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection