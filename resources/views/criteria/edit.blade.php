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
                <h4 class="mb-1 fw-bold">Edit Kriteria</h4>
                <p class="text-muted mb-0 small">Perbarui informasi kriteria penilaian</p>
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

            <a href="{{ route('criteria.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Warning Alert -->
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
        <div>
            <small>Perubahan kriteria tidak dapat dilakukan jika sudah digunakan dalam sebuah periode, buat periode baru untuk melakukan perubahan.</small>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('criteria.update', $criterion->id) }}" method="post" id="editForm">
        @csrf 
        @method('PUT')
        
        <div class="row g-4">
            <!-- Kode Kriteria (Disabled) -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-code me-2"></i>Kode Kriteria
                    <span class="badge bg-secondary ms-2">Tidak dapat diubah</span>
                </label>
                <input type="text" 
                       value="{{ $criterion->code }}" 
                       class="form-control form-control-lg" 
                       disabled>
                <div class="form-text">
                    Kode kriteria bersifat tetap dan tidak dapat dimodifikasi
                </div>
            </div>

            <!-- Nama Kriteria -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-tag me-2"></i>Nama Kriteria
                    <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ $criterion->name }}" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: Pendapatan"
                       id="nameInput"
                       required>
                <div class="form-text">
                    Nama lengkap kriteria penilaian
                </div>
            </div>

            <!-- Jenis Kriteria -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-arrow-up-arrow-down me-2"></i>Jenis Kriteria
                    <span class="text-danger">*</span>
                </label>
                <select name="type" class="form-select form-select-lg" id="typeSelect" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="benefit" {{ $criterion->type == 'benefit' ? 'selected' : '' }}>
                        Benefit (Semakin tinggi semakin baik)
                    </option>
                    <option value="cost" {{ $criterion->type == 'cost' ? 'selected' : '' }}>
                        Cost (Semakin rendah semakin baik)
                    </option>
                </select>
                <div class="form-text" id="typeHelp">
                    @if($criterion->type == 'benefit')
                        Nilai tinggi = Lebih baik
                    @else
                        Nilai rendah = Lebih baik
                    @endif
                </div>
            </div>

            <!-- Bobot -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-weight-hanging me-2"></i>Bobot Kriteria
                    <span class="text-danger">*</span>
                </label>
                <input type="number" 
                       name="weight" 
                       value="{{ $criterion->weight }}" 
                       class="form-control form-control-lg" 
                       placeholder="Contoh: 0.25"
                       id="weightInput"
                       step="0.01"
                       min="0"
                       max="1"
                       required>
                <div class="form-text">
                    Nilai antara 0 - 1 (contoh: 0.25 = 25%)
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Icon Circle */
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

/* Form Controls */
.form-control-lg,
.form-select-lg {
    border-radius: 12px;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
}

.form-control-lg:focus,
.form-select-lg:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.15);
}

.form-control-lg:disabled {
    background-color: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.7;
}

.form-label {
    margin-bottom: 0.5rem;
    color: #495057;
}

.form-text {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Buttons */
.btn-lg {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 10px;
}

/* Alert */
.alert {
    border-radius: 12px;
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    .icon-circle {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}
</style>

@push('scripts')
    <script>
        // Form inputs
        const nameInput = document.getElementById('nameInput');
        const typeSelect = document.getElementById('typeSelect');
        const weightInput = document.getElementById('weightInput');
        const editForm = document.getElementById('editForm');
        const resetBtn = document.querySelector('button[type="reset"]');

        // Store original values
        const originalName = nameInput.value;
        const originalType = typeSelect.value;
        const originalWeight = weightInput.value;

        // Check for changes
        function hasChanges() {
            return nameInput.value !== originalName || 
                typeSelect.value !== originalType || 
                weightInput.value !== originalWeight;
        }

        // Alert warning
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

        // Form validation
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = nameInput.value.trim();
            const type = typeSelect.value;
            const weight = parseFloat(weightInput.value);

            if (!name || !type) {
                showWarning('Harap lengkapi semua field yang wajib diisi!');
                return;
            }

            if (isNaN(weight) || weight <= 0 || weight > 1) {
                showWarning('Bobot harus antara 0 dan 1! Contoh: 0.25 untuk 25%', weightInput);
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
                title: 'Update Kriteria?',
                text: `Update kriteria "${name}"?`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    editForm.submit();
                }
            });
        });

        // Reset form handler
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
                    nameInput.value = originalName;
                    typeSelect.value = originalType;
                    weightInput.value = originalWeight;

                    const weight = parseFloat(originalWeight);
                    const percentage = (weight * 100).toFixed(1);

                    previewWeight.innerHTML = `<i class="fa-solid fa-weight-hanging me-1"></i>Bobot: ${weight} (${percentage}%)`;
                }
            });
        });
    </script>
@endpush

@endsection