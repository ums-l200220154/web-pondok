@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="mb-3">
            <a href="{{ route('bendahara.pembayaran.index') }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-cash-register me-2 text-primary"></i>Input Pembayaran Manual (Tunai)
                </h4>
            </div>
            
            <form action="{{ route('bendahara.pembayaran.storeManual') }}" method="POST" id="mainForm">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Sisi Kiri: Data Santri & Ringkasan --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="fw-bold small mb-1 text-uppercase">1. Pilih Santri</label>
                                <select name="nis" id="select-santri" class="form-select" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($listSantri as $s)
                                        <option value="{{ $s->nis }}">{{ $s->nis }} - {{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold small mb-1 text-uppercase">2. Tahun Akademik</label>
                                <input type="number" name="tahun" id="input-tahun" class="form-control" value="{{ date('Y') }}" required>
                            </div>
                            
                            <div class="card bg-dark text-white rounded-4 border-0 mt-4 shadow-sm">
                                <div class="card-body">
                                    <label class="small text-white-50">Total Harus Dibayar:</label>
                                    <h3 class="fw-bold text-warning mb-0" id="display-total">Rp 0</h3>
                                    <hr class="my-2 opacity-25">
                                    <p class="small mb-0" id="info-bulan">0 Bulan dipilih</p>
                                </div>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Pilihan Bulan --}}
                        <div class="col-md-8">
                            <label class="fw-bold small mb-1 text-uppercase">3. Pilih Bulan & Atur Rincian</label>
                            
                            <div id="loading-spinner" class="text-center d-none my-3">
                                <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                                <span class="small text-muted ms-2">Mengecek data santri...</span>
                            </div>

                            <div class="p-3 bg-light rounded-4" id="bulan-wrapper">
                                <div class="row">
                                    @php $allBulan = ['januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember']; @endphp
                                    @foreach($allBulan as $b)
                                        <div class="col-6 col-md-3 mb-3">
                                            <div class="custom-card-check text-center" id="card_{{ $b }}">
                                                <input class="form-check-input check-bulan d-none" type="checkbox" name="bulan[]" value="{{ $b }}" id="m_{{$b}}">
                                                <label class="form-check-label d-block cursor-pointer py-2" for="m_{{$b}}">
                                                    <span class="text-capitalize fw-bold">{{ $b }}</span>
                                                    <div class="status-label mt-1" style="font-size: 10px;"></div>
                                                </label>
                                                <div id="hidden-inputs-{{ $b }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="fw-bold small mb-2 text-uppercase d-block">Status Penerimaan Uang</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="status_manual" id="status_lunas" value="lunas" checked autocomplete="off">
                                <label class="btn btn-outline-success rounded-start-pill py-2" for="status_lunas">
                                    <i class="fas fa-check-circle me-1"></i> Lunas / Cukup
                                </label>

                                <input type="radio" class="btn-check" name="status_manual" id="status_kurang" value="belum lunas" autocomplete="off">
                                <label class="btn btn-outline-warning py-2" for="status_kurang">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Bayar Sebagian (Kurang)
                                </label>
                            </div>
                            <div id="wrapper-keterangan" class="mt-3 d-none">
                                <input type="text" name="keterangan_admin" class="form-control form-control-sm" placeholder="Catatan kekurangan (misal: Kurang 20rb)">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow" id="btn-submit" disabled>
                                Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Rincian (Sama seperti sebelumnya) --}}
<div class="modal fade" id="modalRincianManual" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title fw-bold text-capitalize" id="titleBulanModal">Rincian Pembayaran</h5>
            </div>
            <div class="modal-body" id="list-rincian-modal"></div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold" id="btnSimpanRincian">Oke, Simpan Rincian</button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-card-check { background: white; border: 2px solid #edeff2; border-radius: 12px; transition: 0.2s; cursor: pointer; position: relative; }
    .custom-card-check:hover:not(.is-paid) { border-color: #0d6efd; background-color: #f0f7ff; }
    .custom-card-check.active { border-color: #0d6efd; background-color: #e7f1ff; box-shadow: 0 4px 8px rgba(13,110,253,0.1); }
    .is-paid { background-color: #f8f9fa !important; opacity: 0.7; border: 2px dashed #ccc !important; cursor: not-allowed !important; pointer-events: none; }
    .is-debt { border: 2px solid #dc3545 !important; background-color: #fff5f5; }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const masterJenis = @json($jenis);
    let rincianSudahAda = {};
    let bulanTerbayar = [];

    const selectSantri = document.getElementById('select-santri');
    const inputTahun = document.getElementById('input-tahun');
    const checks = Array.from(document.querySelectorAll('.check-bulan'));
    const displayTotal = document.getElementById('display-total');
    const btnSubmit = document.getElementById('btn-submit');
    const spinner = document.getElementById('loading-spinner');
    
    // Logic Toggle Keterangan
    const radioKurang = document.getElementById('status_kurang');
    const radioLunas = document.getElementById('status_lunas');
    const wrapperKet = document.getElementById('wrapper-keterangan');

    [radioKurang, radioLunas].forEach(r => {
        r.addEventListener('change', () => {
            wrapperKet.classList.toggle('d-none', !radioKurang.checked);
        });
    });

    const modalElement = document.getElementById('modalRincianManual');
    const modal = new bootstrap.Modal(modalElement);
    const listRincianModal = document.getElementById('list-rincian-modal');
    const btnSimpanRincian = document.getElementById('btnSimpanRincian');
    const titleModal = document.getElementById('titleBulanModal');
    let currentBulan = '';

    // AJAX: Update Data Santri
    async function updateDataSantri() {
        const nis = selectSantri.value;
        const tahun = inputTahun.value;
        if (!nis) { resetChecks(); return; }

        spinner.classList.remove('d-none');
        try {
            const response = await fetch(`/bendahara/pembayaran/get-santri/${nis}?tahun=${tahun}`);
            const data = await response.json();
            
            bulanTerbayar = data.bulanTerbayar || [];
            rincianSudahAda = data.rincianSudahAda || {};

            checks.forEach(c => {
                const card = document.getElementById(`card_${c.value}`);
                const statusLabel = card.querySelector('.status-label');
                c.disabled = false;
                c.checked = false;
                card.classList.remove('is-paid', 'is-debt', 'active');
                statusLabel.innerHTML = '';
                document.getElementById(`hidden-inputs-${c.value}`).innerHTML = '';

                if (bulanTerbayar.includes(c.value)) {
                    c.disabled = true;
                    card.classList.add('is-paid');
                    statusLabel.innerHTML = '<span class="badge bg-success">LUNAS</span>';
                } else if (rincianSudahAda[c.value]) {
                    card.classList.add('is-debt');
                    statusLabel.innerHTML = '<span class="badge bg-danger text-dark" style="font-size:9px">KURANG BAYAR</span>';
                }
            });
            hitungTotal();
        } catch (e) { console.error(e); } finally { spinner.classList.add('d-none'); }
    }

    function resetChecks() {
        checks.forEach(c => {
            c.checked = false; c.disabled = false;
            const card = document.getElementById(`card_${c.value}`);
            card.classList.remove('is-paid', 'is-debt', 'active');
            card.querySelector('.status-label').innerHTML = '';
            document.getElementById(`hidden-inputs-${c.value}`).innerHTML = '';
        });
        hitungTotal();
    }

    // Modal Logic
    function openRincianModal(bulan) {
        currentBulan = bulan;
        titleModal.innerText = `Rincian: ${bulan}`;
        listRincianModal.innerHTML = '';
        const sudahDibayar = rincianSudahAda[bulan] || [];

        masterJenis.forEach((j, index) => {
            const isAlreadyPaid = sudahDibayar.includes(j.nama);
            listRincianModal.innerHTML += `
                <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded-3 ${isAlreadyPaid ? 'bg-light opacity-50' : 'bg-white shadow-sm'}">
                    <div class="form-check">
                        <input class="form-check-input item-check-modal" type="checkbox" 
                               value="${j.nama}" data-nominal="${j.nominal}" 
                               id="item_${index}" ${isAlreadyPaid ? 'disabled' : 'checked'}>
                        <label class="form-check-label ${isAlreadyPaid ? 'text-muted small' : 'fw-bold'}" for="item_${index}">
                            ${j.nama} ${isAlreadyPaid ? '(Sudah)' : ''}
                        </label>
                    </div>
                    <span class="small fw-bold ${isAlreadyPaid ? 'text-muted' : 'text-primary'}">
                        Rp ${parseInt(j.nominal).toLocaleString('id-ID')}
                    </span>
                </div>`;
        });
        modal.show();
    }

    btnSimpanRincian.addEventListener('click', function() {
        const selectedItems = Array.from(document.querySelectorAll('.item-check-modal:checked:not(:disabled)'));
        const hiddenContainer = document.getElementById(`hidden-inputs-${currentBulan}`);
        hiddenContainer.innerHTML = ''; 
        if (selectedItems.length === 0) {
            const chk = document.getElementById(`m_${currentBulan}`);
            chk.checked = false;
            document.getElementById(`card_${currentBulan}`).classList.remove('active');
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
        let total = 0;
        document.querySelectorAll('input[name^="nominal_bulan"]').forEach(input => {
            total += parseInt(input.value);
        });
        displayTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
        btnSubmit.disabled = (total <= 0);
        document.getElementById('info-bulan').innerText = checks.filter(c => c.checked).length + ' Bulan dipilih';
    }

    // Event Checkbox dengan Validasi Urutan
    checks.forEach((check, i) => {
        check.addEventListener('change', function() {
            const card = document.getElementById(`card_${this.value}`);
            if (this.checked) {
                for (let prev = 0; prev < i; prev++) {
                    if (!checks[prev].disabled && !checks[prev].checked) {
                        alert("Pilih bulan secara berurutan! Bulan " + checks[prev].value.toUpperCase() + " belum dipilih.");
                        this.checked = false; return;
                    }
                }
                card.classList.add('active');
                openRincianModal(this.value);
            } else {
                card.classList.remove('active');
                document.getElementById(`hidden-inputs-${this.value}`).innerHTML = '';
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

    selectSantri.addEventListener('change', updateDataSantri);
    inputTahun.addEventListener('change', updateDataSantri);
});
</script>
@endsection