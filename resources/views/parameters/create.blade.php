@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/parameters-style.css') }}">

<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="icon-circle me-3">
            <i class="fa-solid fa-plus"></i>
        </div>
        <div>
            <h4 class="mb-1 fw-bold">Tambah Parameter Scoring</h4>
            <p class="text-muted mb-0 small">Definisikan rentang nilai dan skor untuk kriteria</p>
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
                <select name="criterion_id" class="form-select form-select-lg" id="criterionSelect" required>
                    <option value="">-- Pilih Kriteria --</option>
                    @foreach($criteria as $c)
                        <option value="{{ $c->id }}" data-code="{{ $c->code }}" data-name="{{ $c->name }}">
                            {{ $c->code }} - {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Pilih kriteria yang akan diberi parameter scoring
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
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 0"
                       id="minInput"
                       step="0.01"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Batas bawah rentang nilai (boleh sama dengan max)
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
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 10000000"
                       id="maxInput"
                       step="0.01"
                       required>
                <div class="form-text">
                    <i class="fa-solid fa-lightbulb me-1"></i>Batas atas rentang nilai (boleh sama dengan min)
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
                    <option value="1">1 - Sangat Rendah</option>
                    <option value="2">2 - Rendah</option>
                    <option value="3">3 - Sedang</option>
                    <option value="4">4 - Tinggi</option>
                    <option value="5">5 - Sangat Tinggi</option>
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
            <div class="preview-card">
                <div class="preview-icon" id="previewIcon">
                    <i class="fa-solid fa-question"></i>
                </div>
                <div class="preview-content">
                    <h5 class="mb-1" id="previewCriterion">Pilih Kriteria</h5>
                    <div class="preview-details">
                        <div class="preview-badge" id="previewRange">
                            <i class="fa-solid fa-arrows-left-right me-1"></i>
                            <span>Min: - | Max: -</span>
                        </div>
                        <div class="preview-badge" id="previewScore">
                            <i class="fa-solid fa-star me-1"></i>
                            <span>Skor: -</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Box -->
        <div class="mt-4">
            <div class="help-box">
                <h6 class="mb-2"><i class="fa-solid fa-circle-info me-2"></i>Contoh Parameter Scoring:</h6>
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="help-item">
                            <i class="fa-solid fa-chart-line"></i>
                            <div>
                                <strong>Pendapatan: 0 - 0</strong>
                                <small class="d-block">Skor: 1 (Nilai tunggal)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="help-item">
                            <i class="fa-solid fa-chart-line"></i>
                            <div>
                                <strong>Pendapatan: 0 - 5.000.000</strong>
                                <small class="d-block">Skor: 1 (Sangat Rendah)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="help-item">
                            <i class="fa-solid fa-chart-line"></i>
                            <div>
                                <strong>Pendapatan: 5.000.000 - 10.000.000</strong>
                                <small class="d-block">Skor: 3 (Sedang)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save me-2"></i>Simpan Parameter
            </button>
            <button type="reset" class="btn btn-outline-secondary btn-lg">
                <i class="fa-solid fa-rotate-left me-2"></i>Reset Form
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

// Update preview criterion
criterionSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const code = selectedOption.dataset.code;
    const name = selectedOption.dataset.name;
    
    if (code && name) {
        previewIcon.textContent = code;
        previewCriterion.textContent = `${code} - ${name}`;
    } else {
        previewIcon.innerHTML = '<i class="fa-solid fa-question"></i>';
        previewCriterion.textContent = 'Pilih Kriteria';
    }
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
    } else {
        previewRange.innerHTML = `
            <i class="fa-solid fa-arrows-left-right me-1"></i>
            <span>Min: - | Max: -</span>
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
    } else {
        previewScore.innerHTML = `
            <i class="fa-solid fa-star me-1"></i>
            <span>Skor: -</span>
        `;
    }
});

// Form validation
document.getElementById('parameterForm').addEventListener('submit', function(e) {
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
    
    // FIXED: Boleh sama (min <= max, bukan min < max)
    if (min > max) {
        e.preventDefault();
        alert('Nilai minimum tidak boleh lebih besar dari nilai maksimum!');
        minInput.focus();
        return false;
    }
    
    // Confirm before submit
    const criterionText = criterionSelect.options[criterionSelect.selectedIndex].text;
    if (!confirm(`Simpan parameter untuk "${criterionText}"?\nRange: ${min} - ${max}\nSkor: ${score}`)) {
        e.preventDefault();
        return false;
    }
});

// Reset form handler
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    if (!confirm('Reset semua input? Data yang dimasukkan akan hilang.')) {
        e.preventDefault();
        return false;
    }
    
    // Reset preview
    setTimeout(() => {
        previewIcon.innerHTML = '<i class="fa-solid fa-question"></i>';
        previewCriterion.textContent = 'Pilih Kriteria';
        previewRange.innerHTML = `
            <i class="fa-solid fa-arrows-left-right me-1"></i>
            <span>Min: - | Max: -</span>
        `;
        previewScore.innerHTML = `
            <i class="fa-solid fa-star me-1"></i>
            <span>Skor: -</span>
        `;
    }, 10);
});
</script>
@endpush

@endsection