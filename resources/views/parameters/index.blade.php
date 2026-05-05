@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/parameters-style.css') }}">

<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Parameter Scoring</h4>
                <p class="text-muted mb-0 small">Kelola rentang nilai dan skor untuk setiap kriteria</p>
            </div>
        </div>
        <a href="{{ route('parameters.create') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-plus me-2"></i>Tambah Parameter
        </a>
    </div>

    <!-- Info Alert -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Parameter scoring digunakan untuk mengkonversi nilai mentah menjadi skor standar (1-5).
            <br>
            <small>Setiap rentang nilai (min-max) akan diberikan skor tertentu untuk memudahkan penilaian.</small>
        </div>
    </div>

    @if($parameters->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fa-solid fa-chart-line text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted">Belum ada parameter scoring</h5>
                <p class="text-muted mb-4">Tambahkan parameter untuk mengatur sistem penilaian</p>
                <a href="{{ route('parameters.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Parameter Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-primary-subtle">
                        <i class="fa-solid fa-list-check text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $parameters->count() }}</h3>
                        <small class="text-muted">Total Parameter</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-success-subtle">
                        <i class="fa-solid fa-chart-simple text-success"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $parameters->unique('criterion_id')->count() }}</h3>
                        <small class="text-muted">Kriteria Terkonfigurasi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-warning-subtle">
                        <i class="fa-solid fa-star text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">1 - 5</h3>
                        <small class="text-muted">Rentang Skor</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle parameters-table">
                <thead class="table-light">
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>
                            <i class="fa-solid fa-tag me-1"></i>Kriteria
                        </th>
                        <th width="180" class="text-center">
                            <i class="fa-solid fa-arrow-down-1-9 me-1"></i>Min Value
                        </th>
                        <th width="180" class="text-center">
                            <i class="fa-solid fa-arrow-up-9-1 me-1"></i>Max Value
                        </th>
                        <th width="120" class="text-center">
                            <i class="fa-solid fa-hashtag me-1"></i>Skor
                        </th>
                        <th width="140" class="text-center">
                            <i class="fa-solid fa-database me-1"></i>Status
                        </th>
                        <th width="150" class="text-center">
                            <i class="fa-solid fa-cog me-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parameters as $index => $p)
                    @php
                        $isUsed = \App\Models\Evaluation::where('criterion_id', $p->criterion_id)
                            ->whereBetween('real_value', [$p->min_value, $p->max_value])
                            ->exists();
                        $usageCount = \App\Models\Evaluation::where('criterion_id', $p->criterion_id)
                            ->whereBetween('real_value', [$p->min_value, $p->max_value])
                            ->count();
                    @endphp
                    <tr class="parameter-row">
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                        </td>
                        <td>
                                <strong>{{ $p->criterion->code }} - {{ $p->criterion->name }}</strong>
                        </td>
                        <td class="text-center">
                                <i class="fa-solid fa-greater-than-equal"></i>
                                <strong>{{ number_format($p->min_value, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                                <i class="fa-solid fa-less-than-equal"></i>
                                <strong>{{ number_format($p->max_value, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                                <strong>{{ $p->score }}</strong>
                        </td>
                        <td class="text-center">
                            @if($isUsed)
                                <span class="badge bg-info-subtle text-info" title="Parameter digunakan dalam {{ $usageCount }} penilaian">
                                    <i class="fa-solid fa-lock me-1"></i>Digunakan
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    <i class="fa-solid fa-circle me-1"></i>Belum Digunakan
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('parameters.edit', $p->id) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit Parameter">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($isUsed)
                                    <button type="button" 
                                            class="btn btn-sm btn-secondary" 
                                            disabled
                                            title="Tidak dapat dihapus karena sedang digunakan dalam penilaian">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-btn" 
                                            data-id="{{ $p->id }}"
                                            data-criterion="{{ $p->criterion->code }}"
                                            data-range="{{ number_format($p->min_value, 0, ',', '.') }} - {{ number_format($p->max_value, 0, ',', '.') }}"
                                            title="Hapus Parameter">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $p->id }}" 
                                  action="{{ route('parameters.destroy', $p->id) }}" 
                                  method="post" 
                                  style="display:none">
                                @csrf 
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Info Footer -->
        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="mb-3"><i class="fa-solid fa-circle-info me-2"></i>Keterangan:</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="help-item">
                        <i class="fa-solid fa-1 text-primary"></i>
                        <small>Nilai mentah nasabah akan dicek masuk ke rentang mana (min - max)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="help-item">
                        <i class="fa-solid fa-2 text-success"></i>
                        <small>Setelah ketemu rentangnya, nilai akan dikonversi ke skor yang ditentukan</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="help-item">
                        <i class="fa-solid fa-lock text-info"></i>
                        <small>Parameter yang sudah dipakai dalam penilaian tidak dapat dihapus</small>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Icon Circle */
.icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Stat Card Modern */
.stat-card-modern {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-content h3 {
    font-weight: 700;
    color: #212529;
}

/* Table Styling */
.parameters-table thead th {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.parameters-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.parameter-row {
    transition: all 0.2s ease;
}

.parameter-row:hover {
    background-color: rgba(99, 102, 241, 0.05);
}

/* Help Item */
.help-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.help-item i {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: white;
    font-weight: bold;
    flex-shrink: 0;
}

/* Buttons */
.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card-modern {
        padding: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
}
</style>

@push('scripts')
<script>
// Delete confirmation
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const paramId = this.dataset.id;
        const criterion = this.dataset.criterion;
        const range = this.dataset.range;
        
        if (confirm(`Apakah Anda yakin ingin menghapus parameter:\n\nKriteria: ${criterion}\nRentang: ${range}\n\nTindakan ini tidak dapat dibatalkan!`)) {
            document.getElementById(`delete-form-${paramId}`).submit();
        }
    });
});
</script>
@endpush

@endsection