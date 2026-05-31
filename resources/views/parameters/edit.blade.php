@extends('layouts.app')

@section('content')

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
                        <option value="{{ $c->id }}"
                                data-type="{{ $c->type }}"
                                {{ $parameter->criterion_id == $c->id ? 'selected' : '' }}>
                            {{ $c->code }} - {{ $c->name }} ({{ ucfirst($c->type) }})
                        </option>
                    @endforeach
                </select>

                <div class="mt-2" id="criterionTypeInfo">
                    <span class="badge bg-info text-dark">
                        Jenis Kriteria: <span id="criterionTypeText"></span>
                    </span>
                    <small class="text-muted ms-2" id="criterionTypeDesc"></small>
                </div>
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

<style>
    .icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
</style>

@push('scripts')
<script>
const minInput = document.getElementById('minInput');
const maxInput = document.getElementById('maxInput');
const editForm = document.getElementById('editForm');
const scoreSelect = document.querySelector('select[name="score"]');
const resetBtn = document.querySelector('button[type="reset"]');

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

const criterionSelect = document.querySelector('select[name="criterion_id"]');
const originalCriterion = criterionSelect.value;
const originalMin = minInput.value;
const originalMax = maxInput.value;
const originalScore = scoreSelect.value;

function hasChanges() {
    return criterionSelect.value !== originalCriterion ||
           cleanNumber(minInput.value) !== cleanNumber(originalMin) ||
           cleanNumber(maxInput.value) !== cleanNumber(originalMax) ||
           scoreSelect.value !== originalScore;
}

function showWarning(message, input = null) {
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: message,
        confirmButtonText: 'Oke',
        confirmButtonColor: '#f59e0b'
    }).then(() => {
        if (input) {
            input.focus();
        }
    });
}

const criterionTypeInfo = document.getElementById('criterionTypeInfo');
const criterionTypeText = document.getElementById('criterionTypeText');
const criterionTypeDesc = document.getElementById('criterionTypeDesc');

function updateCriterionTypeInfo() {
    const selected = criterionSelect.options[criterionSelect.selectedIndex];
    const type = selected.dataset.type;

    if (!type) {
        criterionTypeInfo.style.display = 'none';
        return;
    }

    criterionTypeInfo.style.display = 'block';
    criterionTypeText.textContent = type.charAt(0).toUpperCase() + type.slice(1);

    if (type === 'benefit') {
        criterionTypeDesc.textContent = 'Semakin besar nilai, semakin baik.';
    } else {
        criterionTypeDesc.textContent = 'Semakin kecil nilai, semakin baik.';
    }
}

criterionSelect.addEventListener('change', updateCriterionTypeInfo);
window.addEventListener('load', updateCriterionTypeInfo);

// submit
editForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const min = parseInt(cleanNumber(minInput.value));
    const max = parseInt(cleanNumber(maxInput.value));
    const criterionText = criterionSelect.options[criterionSelect.selectedIndex].text;
    const score = scoreSelect.value;

    if (isNaN(min) || isNaN(max)) {
        showWarning('Nilai minimum dan maksimum harus berupa angka!', minInput);
        return;
    }

    if (min > max) {
        showWarning('Nilai minimum tidak boleh lebih besar dari maksimum!', minInput);
        return;
    }

    if (!hasChanges()) {
        Swal.fire({
            icon: 'info',
            title: 'Tidak Ada Perubahan',
            text: 'Tidak ada perubahan data yang dilakukan.',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    Swal.fire({
        icon: 'question',
        title: 'Update Parameter?',
        html: `
            <div style="text-align:left">
                <p>Apakah Anda yakin ingin mengupdate parameter scoring ini?</p>
                <hr>
                <p class="mb-1"><strong>Kriteria:</strong> ${criterionText}</p>
                <p class="mb-1"><strong>Rentang:</strong> ${minInput.value} - ${maxInput.value}</p>
                <p class="mb-1"><strong>Skor:</strong> ${score}</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ya, Update',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            minInput.value = cleanNumber(minInput.value);
            maxInput.value = cleanNumber(maxInput.value);

            editForm.submit();
        }
    });
});

// reser form
resetBtn.addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        icon: 'question',
        title: 'Reset Form?',
        text: 'Reset form ke data awal?',
        showCancelButton: true,
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            criterionSelect.value = originalCriterion;
            minInput.value = formatNumber(originalMin);
            maxInput.value = formatNumber(originalMax);
            scoreSelect.value = originalScore;

            updateCriterionTypeInfo();
        }
    });
});
</script>
@endpush

@endsection