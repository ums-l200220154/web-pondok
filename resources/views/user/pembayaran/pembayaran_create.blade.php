@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold text-success mb-0"><i class="fas fa-hand-holding-usd me-2"></i>Form Pembayaran</h4>
                    <p class="text-muted small mb-0">Pilih tahun dan bulan yang ingin dibayar.</p>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('user.pembayaran.create') }}" method="GET" id="formTahun">
                        <label class="small fw-bold text-muted">Tahun Akademik</label>
                        <select name="tahun" class="form-select form-select-sm shadow-sm" onchange="document.getElementById('formTahun').submit()">
                            @foreach($listTahun as $t)
                                <option value="{{ $t }}" {{ $tahunDipilih == $t ? 'selected' : '' }}>Tahun {{ $t }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" action="{{ route('user.pembayaran.store') }}" enctype="multipart/form-data" id="mainForm">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">

                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="fw-bold small mb-2 text-uppercase text-muted">1. Pilih Bulan & Atur Rincian</label>
                            <div class="p-3 bg-light rounded-4">
                                <div class="row">
                                    @php
                                        $allBulan = ['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember'];
                                    @endphp
                                    @foreach($allBulan as $b)
                                        @php
                                            $isPaid = in_array($b, $bulanTerbayar);
                                            $isDebt = in_array($b, $bulanBelumLunas);
                                        @endphp
                                        <div class="col-6 col-md-3 mb-3">
                                            <div class="custom-card-check text-center {{ $isPaid ? 'is-paid' : ($isDebt ? 'is-debt' : '') }}" id="card_{{ $b }}">
                                                <input class="form-check-input check-bulan d-none" 
                                                       type="checkbox" name="bulan[]" value="{{ $b }}" id="m_{{$b}}"
                                                       {{ $isPaid ? 'disabled' : '' }}>
                                                
                                                <label class="form-check-label d-block cursor-pointer py-2" for="m_{{$b}}">
                                                    <span class="text-capitalize fw-bold">{{ $b }}</span>
                                                    <div class="status-label mt-1" style="font-size: 10px;">
                                                        @if($isPaid) 
                                                            <span class="badge bg-success">LUNAS</span>
                                                        @elseif($isDebt)
                                                            <span class="badge bg-danger">KURANG BAYAR</span>
                                                        @endif
                                                    </div>
                                                </label>
                                                <div id="hidden-inputs-{{ $b }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="fw-bold small mb-2 text-uppercase text-muted">2. Unggah Bukti Transfer</label>
                                <input type="file" name="bukti" class="form-control rounded-3 shadow-sm" required>
                            </div>

                            <div class="card bg-success text-white border-0 shadow-sm rounded-4 mb-3">
                                <div class="card-body">
                                    <label class="fw-bold mb-2 small text-uppercase"><i class="fas fa-wallet me-2"></i>3. Titipan Uang Saku</label>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text border-0 bg-white text-success">Rp</span>
                                        <input type="number" name="uang_saku" class="form-control border-0" value="0" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                                <div class="card-body p-3 text-center">
                                    <label class="small text-white-50">Total Harus Ditransfer:</label>
                                    <h3 class="fw-bold mb-0 text-warning" id="display-total">Rp 0</h3>
                                    <hr class="my-2 opacity-25">
                                    <p class="small mb-0 text-white-50" id="info-bulan">0 Bulan dipilih</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill shadow fw-bold" id="btn-submit" disabled>
                                    Konfirmasi Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRincianBulan" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 bg-success text-white">
                <h5 class="modal-title fw-bold text-capitalize" id="titleBulanModal">Atur Rincian</h5>
            </div>
            <div class="modal-body" id="list-rincian-modal"></div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success w-100 rounded-pill fw-bold" id="btnSimpanRincian">Simpan Rincian</button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-card-check { background: white; border: 2px solid #edeff2; border-radius: 12px; transition: 0.2s; cursor: pointer; position: relative; }
    .custom-card-check:hover:not(.is-paid) { border-color: #198754; background-color: #f8fffb; }
    .custom-card-check.active { border-color: #198754; background-color: #f0fdf4; box-shadow: 0 4px 8px rgba(25,135,84,0.1); }
    .is-paid { background-color: #f8f9fa !important; opacity: 0.7; border: 2px dashed #ccc !important; cursor: not-allowed !important; pointer-events: none; }
    .is-debt { border: 2px solid #dc3545 !important; background-color: #fff5f5; }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const masterJenis = @json($jenis);
    const rincianSudahAda = @json($rincianSudahAda);

    const checks = Array.from(document.querySelectorAll('.check-bulan'));
    const inputUangSaku = document.querySelector('input[name="uang_saku"]');
    const displayTotal = document.getElementById('display-total');
    const infoBulan = document.getElementById('info-bulan');
    const btnSubmit = document.getElementById('btn-submit');
    
    const modal = new bootstrap.Modal(document.getElementById('modalRincianBulan'));
    const listRincianModal = document.getElementById('list-rincian-modal');
    const btnSimpanRincian = document.getElementById('btnSimpanRincian');
    const titleModal = document.getElementById('titleBulanModal');
    let currentBulan = '';

    function openRincianModal(bulan) {
        currentBulan = bulan;
        titleModal.innerText = `Rincian: ${bulan}`;
        listRincianModal.innerHTML = '';
        const sudahBayar = rincianSudahAda[bulan] || [];

        masterJenis.forEach((j, index) => {
            const isPaid = sudahBayar.includes(j.nama);
            listRincianModal.innerHTML += `
                <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded-3 ${isPaid ? 'bg-light opacity-50' : 'bg-white shadow-sm'}">
                    <div class="form-check">
                        <input class="form-check-input item-check-modal" type="checkbox" 
                               value="${j.nama}" data-nominal="${j.nominal}" 
                               id="item_${index}" 
                               ${isPaid ? 'disabled' : 'checked'}>
                        <label class="form-check-label ${isPaid ? 'text-muted' : 'fw-bold'}" for="item_${index}">
                            ${j.nama} ${isPaid ? '(Lunas)' : ''}
                        </label>
                    </div>
                    <span class="fw-bold ${isPaid ? 'text-muted' : 'text-success'}">
                        Rp ${parseInt(j.nominal).toLocaleString('id-ID')}
                    </span>
                </div>`;
        });
        modal.show();
    }

    btnSimpanRincian.addEventListener('click', function() {
        const selectedItems = Array.from(document.querySelectorAll('.item-check-modal:checked:not(:disabled)'));
        const hiddenContainer = document.getElementById(`hidden-inputs-${currentBulan}`);
        const card = document.getElementById(`card_${currentBulan}`);
        
        hiddenContainer.innerHTML = ''; 
        if (selectedItems.length === 0) {
            document.getElementById(`m_${currentBulan}`).checked = false;
            card.classList.remove('active');
        } else {
            selectedItems.forEach(item => {
                hiddenContainer.innerHTML += `
                    <input type="hidden" name="rincian_bulan[${currentBulan}][]" value="${item.value}">
                    <input type="hidden" name="nominal_bulan[${currentBulan}][]" value="${item.dataset.nominal}">
                `;
            });
        }
        modal.hide();
        hitungTotal();
    });

    function hitungTotal() {
        let totalRincian = 0;
        // Hanya menghitung input nominal yang ada di DOM (dari bulan yang sedang dicentang)
        document.querySelectorAll('input[name^="nominal_bulan"]').forEach(input => {
            totalRincian += parseInt(input.value) || 0;
        });

        let uangSaku = parseInt(inputUangSaku.value) || 0;
        let grandTotal = totalRincian + uangSaku;

        displayTotal.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        const jumlahPilih = checks.filter(c => c.checked && !c.disabled).length;
        infoBulan.innerText = jumlahPilih + ' Bulan dipilih';
        btnSubmit.disabled = (grandTotal <= 0);
    }

    checks.forEach((check, i) => {
        check.addEventListener('change', function() {
            const card = document.getElementById(`card_${this.value}`);
            if (this.checked) {
                // Validasi Urutan
                for (let prev = 0; prev < i; prev++) {
                    if (!checks[prev].checked && !checks[prev].disabled) {
                        alert("Harus memilih bulan secara berurutan!");
                        this.checked = false;
                        return;
                    }
                }
                card.classList.add('active');
                openRincianModal(this.value);
            } else {
                card.classList.remove('active');
                document.getElementById(`hidden-inputs-${this.value}`).innerHTML = '';
                // Uncheck berantai untuk bulan setelahnya
                for (let next = i + 1; next < checks.length; next++) {
                    if (!checks[next].disabled) {
                        checks[next].checked = false;
                        document.getElementById(`card_${checks[next].value}`).classList.remove('active');
                        document.getElementById(`hidden-inputs-${checks[next].value}`).innerHTML = '';
                    }
                }
                hitungTotal();
            }
        });
    });

    inputUangSaku.addEventListener('input', hitungTotal);
    
    // Cegah inisialisasi otomatis yang salah
    // Kita hapus logika auto-check PHP dari JS agar user memicu modal secara manual
    hitungTotal();
});
</script>
@endsection