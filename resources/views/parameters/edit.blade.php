@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/parameters-style.css') }}">

<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-circle edit me-3">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Edit Parameter Scoring</h4>
                <p class="text-muted mb-0 small">Perbarui rentang nilai dan skor parameter</p>
            </div>
        </div>

        <!-- Button kanan -->
        <div class="d-flex gap-2">
            <button type="submit" form="editForm" class="btn btn-primary">
                <i class="fa-solid fa-save me-1"></i> Update
            </button>

            <button type="reset" form="editForm" class="btn btn-outline-secondary">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset
            </button>

            <a href="{{ route('parameters.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Warning -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Perubahan parameter akan mempengaruhi hasil penilaian.
        </div>
    </div>
    
    <!-- Form -->
    <form action="{{ route('parameters.update', $parameter->id) }}" method="post" id="editForm">
        @csrf 
        @method('PUT')
        
        <div class="row g-4">
            <!-- Kriteria -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">Kriteria</label>
                <select name="criterion_id" class="form-select form-select-lg" required>
                    @foreach($criteria as $c)
                        <option value="{{ $c->id }}" {{ $parameter->criterion_id == $c->id ? 'selected' : '' }}>
                            {{ $c->code }} - {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Min -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nilai Minimum</label>
                <input type="text" 
                       name="min_value"
                       id="minInput"
                       class="form-control form-control-lg"
                       value="{{ (int) $parameter->min_value }}"
                       required>
            </div>

            <!-- Max -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nilai Maksimum</label>
                <input type="text" 
                       name="max_value"
                       id="maxInput"
                       class="form-control form-control-lg"
                       value="{{ (int) $parameter->max_value }}"
                       required>
            </div>

            <!-- Score -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">Skor</label>
                <select name="score" class="form-select form-select-lg" required>
                    <option value="1" {{ $parameter->score == 1 ? 'selected' : '' }}>1 - Sangat Rendah</option>
                    <option value="2" {{ $parameter->score == 2 ? 'selected' : '' }}>2 - Rendah</option>
                    <option value="3" {{ $parameter->score == 3 ? 'selected' : '' }}>3 - Sedang</option>
                    <option value="4" {{ $parameter->score == 4 ? 'selected' : '' }}>4 - Tinggi</option>
                    <option value="5" {{ $parameter->score == 5 ? 'selected' : '' }}>5 - Sangat Tinggi</option>
                </select>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
const minInput = document.getElementById('minInput');
const maxInput = document.getElementById('maxInput');

// format angka (aman dari decimal)
function formatNumber(value) {
    value = value.toString().replace(/\D/g, '');
    if (!value) return '';
    return new Intl.NumberFormat('id-ID').format(value);
}

// hapus format
function cleanNumber(value) {
    return value.replace(/\D/g, '');
}

// format awal (penting)
window.addEventListener('load', function () {
    minInput.value = formatNumber(minInput.value);
    maxInput.value = formatNumber(maxInput.value);
});

// auto format saat input
[minInput, maxInput].forEach(input => {
    input.addEventListener('input', function () {
        this.value = formatNumber(this.value);
    });
});

// submit
document.getElementById('editForm').addEventListener('submit', function(e) {
    const min = parseInt(cleanNumber(minInput.value));
    const max = parseInt(cleanNumber(maxInput.value));

    if (isNaN(min) || isNaN(max)) {
        e.preventDefault();
        alert('Nilai harus berupa angka');
        return;
    }

    if (min > max) {
        e.preventDefault();
        alert('Nilai minimum tidak boleh lebih besar dari maksimum');
        return;
    }

    // kirim ke backend angka bersih
    minInput.value = cleanNumber(minInput.value);
    maxInput.value = cleanNumber(maxInput.value);
});
</script>
@endpush

@endsection