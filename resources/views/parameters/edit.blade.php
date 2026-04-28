@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/parameters-style.css') }}">

<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle edit me-3">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Edit Parameter Scoring</h4>
            <p class="text-muted mb-0 small">Perbarui rentang nilai dan skor parameter</p>
        </div>
    </div>

    <!-- Warning Alert -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Perubahan parameter akan mempengaruhi hasil penilaian yang menggunakan parameter ini.
            <br>
            <small>Pastikan rentang nilai tidak overlap dengan parameter lain untuk kriteria yang sama.</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('parameters.update', $parameter->id) }}" method="post" id="editForm">
        @csrf 
        @method('PUT')
        
        <div class="row g-4">
            <!-- Kriteria -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-list-check me-2"></i>Kriteria
                    <span class="text-danger">*</span>
                </label>
                <select name="criterion_id" class="form-select form-select-lg" id="criterionSelect" required>
                    @foreach($criteria as $c)
                        <option value="{{ $c->id }}" 
                                data-code="{{ $c->code }}" 
                                data-name="{{ $c->name }}"
                                {{ $parameter->criterion_id == $c->id ? 'selected' : '' }}>
                            {{ $c->code }} - {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Kriteria yang menggunakan parameter ini
                </div>
            </div>

            <!-- Min Value -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-arrow-down-1-9 me-2"></i>Nilai Minimum
                    <span class="text-danger">*</span>
                </label>
                <input type="number" 
                       name="min_value" 
                       value="{{ $parameter->min_value }}"
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 0"
                       id="minInput"
                       step="0.01"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Batas bawah rentang nilai
                </div>
            </div>

            <!-- Max Value -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-arrow-up-9-1 me-2"></i>Nilai Maksimum
                    <span class="text-danger">*</span>
                </label>
                <input type="number" 
                       name="max_value" 
                       value="{{ $parameter->max_value }}"
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 10000000"
                       id="maxInput"
                       step="0.01"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Batas atas rentang nilai
                </div>
            </div>

            <!-- Score -->
            <div class="col-md-12">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-star me-2"></i>Skor Standar
                    <span class="text-danger">*</span>
                </label>
                <select name="score" class="form-select form-select-lg" id="scoreSelect" required>
                    <option value="">-- Pilih Skor --</option>
                    <option value="1" {{ $parameter->score == 1 ? 'selected' : '' }}>1 - Sangat Rendah</option>
                    <option value="2" {{ $parameter->score == 2 ? 'selected' : '' }}>2 - Rendah</option>
                    <option value="3" {{ $parameter->score == 3 ? 'selected' : '' }}>3 - Sedang</option>
                    <option value="4" {{ $parameter->score == 4 ? 'selected' : '' }}>4 - Tinggi</option>
                    <option value="5" {{ $parameter->score == 5 ? 'selected' : '' }}>5 - Sangat Tinggi</option>
                </select>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Skor yang akan diberikan untuk rentang nilai ini
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="mt-4">
            <label class="form-label fw-semibold">
                <i class="fa-solid fa-eye me-2"></i>Preview Parameter
            </label>
            <div class="preview-card edit">
                <div class="preview-icon edit" id="previewIcon">
                    {{ $parameter->criterion->code }}
                </div>
                <div class="preview-content">
                    <h5 class="mb-1" id="previewCriterion">
                        {{ $parameter->criterion->code }} - {{ $parameter->criterion->name }}
                    </h5>
                    <div class="preview-details">
                        <div class="preview-badge" id="previewRange">
                            <i class="fa-solid fa-arrows-left-right me-1"></i>
                            <span>Min: {{ number_format($parameter->min_value, 0, ',', '.') }} | Max: {{ number_format($parameter->max_value, 0, ',', '.') }}</span>
                        </div>
                        <div class="preview-badge" id="previewScore">
                            <i class="fa-solid fa-star me-1"></i>
                            <span>Skor: {{ $parameter->score }}</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fa-solid fa-hashtag me-1"></i>ID: {{ $parameter->id }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-4">
            <div class="info-box">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                <div>
                    <strong>Informasi Parameter:</strong>
                    <br>
                    <small class="text-muted">
                        Parameter ini dibuat pada {{ $parameter->created_at ? $parameter->created_at->format('d F Y, H:i') : '-' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save me-2"></i>Update Parameter
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset
            </button>
            <a href="{{ route('parameters.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Form inputs
const criterionSelect = document.getElementById('criterionSelect');
const minInput = document.getElementById('minInput');
const maxInput = document.getElementById('maxInput');
const scoreSelect = document.getElementById('scoreSelect');

// Preview elements
const previewIcon = document.getElementById('previewIcon');
const previewCriterion = document.getElementById('previewCriterion');
const previewRange = document.getElementById('previewRange');
const previewScore = document.getElementById('previewScore');

// Store original values
const originalCriterion = criterionSelect.value;
const originalMin = minInput.value;
const originalMax = maxInput.value;
const originalScore = scoreSelect.value;

// Update preview criterion
criterionSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const code = selectedOption.dataset.code;
    const name = selectedOption.dataset.name;
    
    previewIcon.textContent = code;
    previewCriterion.textContent = `${code} - ${name}`;
});

// Format number with thousand separator
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Update preview range
function updateRange() {
    const min = minInput.value;
    const max = maxInput.value;
    
    if (min && max) {
        previewRange.innerHTML = `
            <i class="fa-solid fa-arrows-left-right me-1"></i>
            <span>Min: ${formatNumber(min)} | Max: ${formatNumber(max)}</span>
        `;
    } else if (min) {
        previewRange.innerHTML = `
            <i class="fa-solid fa-arrows-left-right me-1"></i>
            <span>Min: ${formatNumber(min)} | Max: -</span>
        `;
    } else if (max) {
        previewRange.innerHTML = `
            <i class="fa-solid fa-arrows-left-right me-1"></i>
            <span>Min: - | Max: ${formatNumber(max)}</span>
        `;
    }
}

minInput.addEventListener('input', updateRange);
maxInput.addEventListener('input', updateRange);

// Update preview score
scoreSelect.addEventListener('change', function() {
    const score = this.value;
    const text = this.options[this.selectedIndex].text;
    
    if (score) {
        previewScore.innerHTML = `
            <i class="fa-solid fa-star me-1"></i>
            <span>Skor: ${score} - ${text.split(' - ')[1]}</span>
        `;
    }
});

// Check for changes
function hasChanges() {
    return criterionSelect.value !== originalCriterion || 
           minInput.value !== originalMin || 
           maxInput.value !== originalMax || 
           scoreSelect.value !== originalScore;
}

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
    const criterion = criterionSelect.value;
    const min = parseFloat(minInput.value);
    const max = parseFloat(maxInput.value);
    const score = scoreSelect.value;
    
    if (!criterion || !score) {
        e.preventDefault();
        alert('Harap lengkapi semua field yang wajib diisi!');
        return false;
    }
    
    if (isNaN(min) || isNaN(max)) {
        e.preventDefault();
        alert('Nilai min dan max harus berupa angka!');
        return false;
    }
    
    if (min >= max) {
        e.preventDefault();
        alert('Nilai minimum harus lebih kecil dari nilai maksimum!');
        minInput.focus();
        return false;
    }
    
    if (!hasChanges()) {
        e.preventDefault();
        alert('Tidak ada perubahan data yang dilakukan.');
        return false;
    }
    
    // Confirm before submit
    if (!confirm(`Update parameter?\nRange: ${min} - ${max}\nSkor: ${score}\n\nPerubahan akan disimpan ke database.`)) {
        e.preventDefault();
        return false;
    }
});

// Reset form handler
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    if (!confirm('Reset form ke data awal?')) {
        e.preventDefault();
        return false;
    }
    
    // Restore original values
    setTimeout(() => {
        criterionSelect.value = originalCriterion;
        minInput.value = originalMin;
        maxInput.value = originalMax;
        scoreSelect.value = originalScore;
        
        // Update preview
        const selectedOption = criterionSelect.options[criterionSelect.selectedIndex];
        previewIcon.textContent = selectedOption.dataset.code;
        previewCriterion.textContent = `${selectedOption.dataset.code} - ${selectedOption.dataset.name}`;
        updateRange();
        
        const scoreText = scoreSelect.options[scoreSelect.selectedIndex].text;
        previewScore.innerHTML = `
            <i class="fa-solid fa-star me-1"></i>
            <span>Skor: ${originalScore} - ${scoreText.split(' - ')[1]}</span>
        `;
    }, 10);
});

// Warn before leaving with unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (hasChanges()) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
@endpush

@endsection