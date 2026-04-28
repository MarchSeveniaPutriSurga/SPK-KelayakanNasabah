@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Master Kriteria Penilaian</h4>
                <p class="text-muted mb-0 small">Kelola kriteria untuk metode SMART</p>
            </div>
        </div>
        @php $count = $criteria->count(); @endphp
        @if($count >= 4)
            <button class="btn btn-secondary btn-lg" disabled title="Maksimal 4 kriteria">
                <i class="fa-solid fa-ban me-2"></i>Maksimal 4 Kriteria
            </button>
        @else
            <a href="{{ route('criteria.create') }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-plus me-2"></i>Tambah Kriteria
            </a>
        @endif
    </div>

    <!-- Alert Info -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Sistem mendukung maksimal 4 kriteria penilaian untuk metode SMART.
            <br>
            <small>Total bobot semua kriteria harus sama dengan 1 (100%). Saat ini: <strong>{{ $criteria->sum('weight') }}</strong></small>
        </div>
    </div>

    @if($criteria->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fa-solid fa-clipboard-question text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted">Belum ada kriteria</h5>
                <p class="text-muted mb-4">Tambahkan kriteria penilaian untuk memulai sistem SMART</p>
                <a href="{{ route('criteria.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Kriteria Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-primary-subtle">
                        <i class="fa-solid fa-list-check text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $count }}</h3>
                        <small class="text-muted">Total Kriteria</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-success-subtle">
                        <i class="fa-solid fa-arrow-trend-up text-success"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $criteria->where('type', 'benefit')->count() }}</h3>
                        <small class="text-muted">Benefit</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-danger-subtle">
                        <i class="fa-solid fa-arrow-trend-down text-danger"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $criteria->where('type', 'cost')->count() }}</h3>
                        <small class="text-muted">Cost</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-warning-subtle">
                        <i class="fa-solid fa-weight-hanging text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ number_format($criteria->sum('weight'), 2) }}</h3>
                        <small class="text-muted">Total Bobot</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weight Validation Alert -->
        @php $totalWeight = $criteria->sum('weight'); @endphp
        @if(abs($totalWeight - 1) > 0.01)
            <div class="alert alert-warning d-flex align-items-center mb-4">
                <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
                <div>
                    <strong>Peringatan:</strong> Total bobot kriteria adalah <strong>{{ $totalWeight }}</strong>, seharusnya <strong>1.00</strong>
                    <br>
                    <small>Silakan sesuaikan bobot kriteria agar total = 1.00 (100%)</small>
                </div>
            </div>
        @else
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <strong>Valid:</strong> Total bobot kriteria sudah sesuai (1.00 / 100%)
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle criteria-table">
                <thead class="table-light">
                    <tr>
                        <th width="80" class="text-center">
                            <i class="fa-solid fa-code me-1"></i>Kode
                        </th>
                        <th>
                            <i class="fa-solid fa-tag me-1"></i>Nama Kriteria
                        </th>
                        <th width="150" class="text-center">
                            <i class="fa-solid fa-arrow-up-arrow-down me-1"></i>Jenis
                        </th>
                        <th width="120" class="text-center">
                            <i class="fa-solid fa-weight-hanging me-1"></i>Bobot
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
                    @foreach($criteria as $c)
                    @php
                        $isUsed = \App\Models\Evaluation::where('criterion_id', $c->id)->exists();
                        $usageCount = \App\Models\Evaluation::where('criterion_id', $c->id)->count();
                    @endphp
                    <tr class="criterion-row">
                        <td class="text-center">
                            <span class="code-badge">{{ $c->code }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="criterion-icon me-2">
                                    <i class="fa-solid fa-chart-simple"></i>
                                </div>
                                <strong>{{ $c->name }}</strong>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($c->type === 'benefit')
                                <span class="badge bg-success-subtle text-success">
                                    <i class="fa-solid fa-arrow-trend-up me-1"></i>Benefit
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="fa-solid fa-arrow-trend-down me-1"></i>Cost
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="weight-badge">
                                {{ $c->weight }}
                                <small class="text-muted">({{ number_format($c->weight * 100, 1) }}%)</small>
                            </span>
                        </td>
                        <td class="text-center">
                            @if($isUsed)
                                <span class="badge bg-info-subtle text-info" title="Kriteria sudah digunakan dalam {{ $usageCount }} penilaian">
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
                                <a href="{{ route('criteria.edit', $c->id) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit Kriteria">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($isUsed)
                                    <button type="button" 
                                            class="btn btn-sm btn-secondary" 
                                            disabled
                                            title="Tidak dapat dihapus karena sudah digunakan dalam penilaian">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-btn" 
                                            data-id="{{ $c->id }}"
                                            data-code="{{ $c->code }}"
                                            data-name="{{ $c->name }}"
                                            title="Hapus Kriteria">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $c->id }}" 
                                  action="{{ route('criteria.destroy', $c->id) }}" 
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
                    <div class="info-item">
                        <span class="badge bg-success me-2">Benefit</span>
                        <small>Nilai semakin tinggi semakin baik (Pendapatan, Aset)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <span class="badge bg-danger me-2">Cost</span>
                        <small>Nilai semakin rendah semakin baik (Hutang, Resiko)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <span class="badge bg-info me-2">
                            <i class="fa-solid fa-lock me-1"></i>Digunakan
                        </span>
                        <small>Kriteria yang sudah dipakai tidak dapat dihapus</small>
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
    padding: 1.25rem;
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
    width: 52px;
    height: 52px;
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

/* Criterion Icon */
.criterion-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.2));
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Code Badge */
.code-badge {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    border-radius: 8px;
    font-weight: bold;
    font-size: 0.875rem;
}

/* Weight Badge */
.weight-badge {
    font-weight: 600;
    color: #212529;
}

/* Table Styling */
.criteria-table thead th {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.criteria-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.criterion-row {
    transition: all 0.2s ease;
}

.criterion-row:hover {
    background-color: rgba(99, 102, 241, 0.05);
    transform: translateX(2px);
}

/* Info Item */
.info-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
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

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

.btn-sm.btn-secondary:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

/* Alert */
.alert {
    border-radius: 12px;
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card-modern {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 44px;
        height: 44px;
        font-size: 1.25rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
}
</style>

@push('scripts')
<script>
// Delete confirmation
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const criterionId = this.dataset.id;
        const criterionCode = this.dataset.code;
        const criterionName = this.dataset.name;
        
        if (confirm(`Apakah Anda yakin ingin menghapus kriteria "${criterionCode} - ${criterionName}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
            document.getElementById(`delete-form-${criterionId}`).submit();
        }
    });
});
</script>
@endpush

@endsection