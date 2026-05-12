@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/parameters-style.css') }}">

<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
    
        <!-- Kiri: icon + title -->
        <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
            <h4 class="mb-1 fw-bold">Tambah Parameter Scoring</h4>
            <p class="text-muted mb-0 small">Definisikan rentang nilai dan skor untuk kriteria</p>
            </div>
        </div>

        <!-- Kanan: semua tombol -->
        <div class="d-flex gap-2">
            <button type="submit" form="parameterForm" class="btn btn-primary">
                <i class="fa-solid fa-save me-1"></i> Simpan
            </button>

            <button type="reset" form="parameterForm" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </button>

            <a href="{{ route('parameters.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Parameter scoring mengkonversi nilai mentah menjadi skor standar 1-5.
            <br>
            <small>Contoh: Jika pendapatan 5.000.000 - 10.000.000 maka skornya 3. Min dan Max boleh sama untuk nilai tunggal (contoh: 0 - 0).</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('parameters.store') }}" method="post" id="parameterForm">
        @csrf
        
        <div class="row g-4">
            <!-- Kriteria -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-list-check me-2"></i>Pilih Kriteria
                    <span class="text-danger">*</span>
                </label>
                <select name="criterion_id" class="form-select form-select-lg" required>
                    <option value="">-- Pilih Kriteria --</option>
                    @foreach($criteria as $c)
                        <option value="{{ $c->id }}">
                            {{ $c->code }} - {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Min Value -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-arrow-down-1-9 me-2"></i>Nilai Minimum
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="min_value" 
                       class="form-control form-control-lg" 
                       id="minInput"
                       placeholder="Contoh: 0"
                       required>
            </div>

            <!-- Max Value -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-arrow-up-9-1 me-2"></i>Nilai Maksimum
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="max_value" 
                       class="form-control form-control-lg" 
                       id="maxInput"
                       placeholder="Contoh: 10000000"
                       required>
            </div>

            <!-- Score -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-star me-2"></i>Skor Standar
                    <span class="text-danger">*</span>
                </label>
                <select name="score" class="form-select form-select-lg" required>
                    <option value="">-- Pilih Skor --</option>
                    <option value="1">1 - Sangat Rendah</option>
                    <option value="2">2 - Rendah</option>
                    <option value="3">3 - Sedang</option>
                    <option value="4">4 - Tinggi</option>
                    <option value="5">5 - Sangat Tinggi</option>
                </select>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// ambil input
const minInput = document.getElementById('minInput');
const maxInput = document.getElementById('maxInput');

// format ribuan
function formatRupiah(angka) {
    let number_string = angka.replace(/[^,\d]/g, '').toString();
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah;
}

// hapus titik
function unformatRupiah(angka) {
    return angka.replace(/\./g, '');
}

// event input
minInput.addEventListener('input', function() {
    this.value = formatRupiah(this.value);
});

maxInput.addEventListener('input', function() {
    this.value = formatRupiah(this.value);
});

// validasi + kirim angka bersih
document.getElementById('parameterForm').addEventListener('submit', function(e) {
    const min = parseFloat(unformatRupiah(minInput.value));
    const max = parseFloat(unformatRupiah(maxInput.value));

    if (isNaN(min) || isNaN(max)) {
        e.preventDefault();
        alert('Nilai harus berupa angka!');
        return false;
    }

    if (min > max) {
        e.preventDefault();
        alert('Nilai minimum tidak boleh lebih besar dari maksimum!');
        return false;
    }

    // kirim ke backend tanpa titik
    minInput.value = unformatRupiah(minInput.value);
    maxInput.value = unformatRupiah(maxInput.value);
});
</script>
@endpush

@endsection