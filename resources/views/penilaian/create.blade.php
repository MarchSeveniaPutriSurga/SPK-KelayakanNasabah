@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Form Penilaian Nasabah</h4>
            <p class="text-muted mb-0 small">Input nilai kriteria untuk setiap nasabah berdasarkan periode</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="alert alert-primary d-flex align-items-center mb-4">
    <i class="fa-solid fa-calendar-check fs-4 me-3"></i>
    <div>
        <strong>Periode Aktif:</strong> 
        {{ $selectedPeriod->label ?? 'Tidak ada periode aktif' }}
    </div>
</div>

    @if(!$selected)
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fa-solid fa-clipboard-list text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted">Belum ada periode yang dipilih</h5>
                <p class="text-muted">Silakan pilih periode terlebih dahulu untuk mulai melakukan penilaian nasabah</p>
            </div>
        </div>
    @endif

    @if($selected)
        <!-- Info Periode -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fa-solid fa-info-circle fs-4 me-3"></i>
            <div>
                <strong>Periode:</strong> {{ $periods->firstWhere('id', $selected)->label ?? '-' }}
                <br>
                <small>Centang nasabah yang ingin dinilai, lalu isi nilai untuk setiap kriteria</small>
            </div>
        </div>

        <!-- Info Kriteria -->
        <div class="criteria-info mb-4">
            <h6 class="mb-3"><i class="fa-solid fa-list-check me-2"></i>Kriteria Penilaian:</h6>
            <div class="row g-2">
                @foreach($criteria as $c)
                    <div class="col-md-4 col-lg-3">
                        <div class="criteria-badge">
                            <span class="badge" style="background-color: #91C6BC !important;">{{ $c->code }}</span>
                            <small class="ms-2">{{ $c->name }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="post" action="{{ route('penilaian.store') }}">
            @csrf
            <input type="hidden" name="period_id" value="{{ $selected }}">

            <input type="hidden" name="period_id" value="{{ $selected }}">

            <!-- Quota Lolos -->
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-check-to-slot me-2"></i>Jumlah Nasabah Layak Lanjut
                </label>
                <input type="number"
                    name="quota_lolos"
                    class="form-control"
                    min="1"
                    placeholder="Contoh: 10"
                    value="{{ $periods->firstWhere('id', $selected)->quota_lolos ?? '' }}">
                <small class="text-muted">
                    Nasabah dengan peringkat 1 sampai nilai ini akan berstatus <b>Layak Lanjut</b>
                </small>
            </div>

            <!-- Tabel Penilaian -->
            <div class="table-responsive">
                <table class="table table-hover align-middle penilaian-table">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">
                                <input type="checkbox" id="checkAll" class="form-check-input" title="Pilih Semua">
                            </th>
                            <th width="200">
                                <i class="fa-solid fa-user me-1"></i>Nama Nasabah
                            </th>
                            @foreach($criteria as $c)
                                <th class="text-center" width="150">
                                    <div class="fw-bold text-green-950">{{ $c->code }}</div>
                                    <small class="text-muted d-block">{{ $c->name }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($customers as $cus)
                        @php
                            // Cek apakah customer ini sudah punya evaluasi di periode ini
                            $hasEvaluation = isset($existingData[$cus->id]) && !empty($existingData[$cus->id]);
                        @endphp
                        
                        <tr class="customer-row {{ $hasEvaluation ? 'selected' : '' }}">
                            <td class="text-center">
                                <input type="checkbox" 
                                       name="checked[]" 
                                       value="{{ $cus->id }}" 
                                       class="form-check-input select-row"
                                       {{ $hasEvaluation ? 'checked' : '' }}>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <strong>{{ $cus->name }}</strong>
                                </div>
                            </td>

                            @foreach($criteria as $c)
                            <td>
                                @if(str_contains(strtolower($c->name), 'keuntungan'))

                                    <!-- KEUNTUNGAN -->
                                <input type="text"
                                name="keuntungan[{{ $cus->id }}]"
                                value="{{ isset($existingData[$cus->id][$c->id]['keuntungan']) 
                                ? number_format($existingData[$cus->id][$c->id]['keuntungan'], 0, ',', '.') 
                                : '' }}"
                                class="form-control keuntungan-input real-input mb-1"
                                placeholder="Keuntungan"
                                {{ $hasEvaluation ? '' : 'disabled' }}>

                                    <!-- MODAL -->
                            <input type="text"
                                name="modal[{{ $cus->id }}]"
                                value="{{ isset($existingData[$cus->id][$c->id]['modal']) 
                                ? number_format($existingData[$cus->id][$c->id]['modal'], 0, ',', '.') 
                                : '' }}"
                                class="form-control modal-input real-input mb-1"
                                placeholder="Modal"
                                {{ $hasEvaluation ? '' : 'disabled' }}>

                                    <!-- BUTTON -->
                                    <button type="button"
                                        class="btn btn-sm w-100 hitung-btn mb-1"
                                        style="background-color: #c58671 !important; border-color: #c58671 !important; color: white;"
                                        {{ $hasEvaluation ? '' : 'readonly' }}>
                                        Hitung %
                                    </button>

                                    <!-- HASIL -->
                            <input type="text"
                                name="values[{{ $cus->id }}][{{ $c->id }}]"
                                value="{{ isset($existingData[$cus->id][$c->id]['persen']) 
                                    ? number_format($existingData[$cus->id][$c->id]['persen'], 1) . ' %' 
                                    : '' }}"
                                class="form-control persen-output text-center"
                                readonly>

                                @else

                                    <!-- 🔥 INI YANG KAMU KURANG -->
                                    <input type="text"
                                    name="values[{{ $cus->id }}][{{ $c->id }}]"
                                    class="form-control real-input text-center"
                                    value="{{ isset($existingData[$cus->id][$c->id]['persen']) 
                                ? number_format($existingData[$cus->id][$c->id]['persen'], 0, ',', '.') 
                                : '' }}"
                                    {{ $hasEvaluation ? '' : 'disabled' }}>

                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted" id="selectedCount">0 nasabah dipilih</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                        <i class="fa-solid fa-save me-2"></i> Simpan Penilaian
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>

@push('scripts')
    <script>
        // Update selected count
        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.select-row:checked').length;
            document.getElementById('selectedCount').textContent = `${checkedCount} nasabah dipilih`;
            document.getElementById('submitBtn').disabled = checkedCount === 0;
        }

        // 🔥 FORMAT ANGKA + SIMPAN VALUE ASLI
        document.querySelectorAll('.real-input, .keuntungan-input, .modal-input')
        .forEach(input => {
            input.addEventListener('input', function() {

                // ambil angka asli (hapus semua selain digit)
                let raw = this.value.replace(/\D/g, '');

                if (raw === '') {
                    this.value = '';
                    this.dataset.raw = '';
                    return;
                }

                // simpan angka asli TANPA format
                this.dataset.raw = raw;

                // format tampilan ribuan
                let formatted = raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                this.value = formatted;
            });
        });

        document.querySelectorAll('.hitung-btn').forEach(btn => {
    btn.addEventListener('click', function() {

        let td = this.closest('td');

        let keuntunganInput = td.querySelector('.keuntungan-input');
        let modalInput      = td.querySelector('.modal-input');
        let output          = td.querySelector('.persen-output');

        let keuntungan = keuntunganInput.dataset.raw || keuntunganInput.value.replace(/\./g,'');
        let modal      = modalInput.dataset.raw || modalInput.value.replace(/\./g,'');

        keuntungan = parseFloat(keuntungan);
        modal = parseFloat(modal);

        if (!keuntungan || !modal || modal <= 0) {
            alert('Isi keuntungan & modal dengan benar dulu');
            return;
        }

        let persen = (keuntungan / modal) * 100;

        // tampilkan dengan desimal
        output.value = persen.toFixed(1) + ' %';

        // simpan angka asli
        output.dataset.raw = persen.toFixed(2);
    });
});

        // 🔥 CHECKBOX SELECT
        document.querySelectorAll('.select-row').forEach(cb => {
            cb.addEventListener('change', function() {
                let tr = this.closest('tr');

                tr.classList.toggle('selected', this.checked);

                tr.querySelectorAll('input, button').forEach(el => {
                if (el.type !== 'checkbox') {
                    el.disabled = !this.checked;

                    if (!this.checked) {
                        if (el.tagName === 'INPUT') {
                            el.value = "";
                            el.dataset.raw = "";
                        }
                    }
                }
            });

                updateSelectedCount();
            });
        });

        // 🔥 CHECK ALL
        document.getElementById('checkAll').addEventListener('change', function() {
            const isChecked = this.checked;

            document.querySelectorAll('.select-row').forEach(cb => {
                cb.checked = isChecked;
                cb.dispatchEvent(new Event('change'));
            });
        });

        // INIT
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();
        });

        // RESET
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            e.preventDefault();

            if (confirm('Apakah Anda yakin ingin mereset semua pilihan dan input?')) {
                document.querySelectorAll('.select-row').forEach(cb => {
                    cb.checked = false;
                    cb.dispatchEvent(new Event('change'));
                });
                document.getElementById('checkAll').checked = false;
            }
        });

        // 🔥 TAMBAHAN WAJIB
document.addEventListener('DOMContentLoaded', function() {

    updateSelectedCount();

    // 🔥 FORMAT ULANG SAAT LOAD
    document.querySelectorAll('.real-input').forEach(input => {
        let raw = input.value.replace(/\D/g,'');

        if (!raw) return;

        input.dataset.raw = raw;

        input.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

});

        // 🔥 FIX SEBELUM SUBMIT (INI YANG PALING PENTING)
document.querySelector('form').addEventListener('submit', function(e) {

    // 🔥 AKTIFKAN SEMUA INPUT BIAR KEKIRIM
    document.querySelectorAll('input').forEach(inp => {
        inp.disabled = false;
    });

    let hasEmpty = false;

    document.querySelectorAll('.select-row:checked').forEach(cb => {
        let tr = cb.closest('tr');

        tr.querySelectorAll('input[name^="values"]').forEach(inp => {

            let raw = inp.dataset.raw || inp.value.replace('%','').trim();

            if (!raw || raw === '') {
                hasEmpty = true;
            }

            inp.value = raw;
        });
    });

    if (hasEmpty) {
        e.preventDefault();
        alert('Pastikan semua nilai termasuk hasil % sudah dihitung!');
        return;
    }
});
    </script>
@endpush

@endsection