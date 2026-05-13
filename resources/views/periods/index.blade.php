@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">Manajemen Periode</h4>
                <p class="text-muted mb-0 small">Kelola periode penilaian bulanan</p>
            </div>
        </div>
        <a href="{{ route('periods.create') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-plus me-2"></i>Tambah Periode
        </a>
    </div>

    <!-- Info Card -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-info-circle fs-4 me-3"></i>
        <div>
            <strong>Informasi:</strong> Periode yang aktif akan digunakan sebagai default untuk penilaian nasabah.
            <br>
            <small>Hanya satu periode yang dapat aktif dalam satu waktu.</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($periods->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fa-solid fa-calendar-xmark text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted">Belum ada periode</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan periode penilaian pertama Anda</p>
                <a href="{{ route('periods.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Periode Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle periods-table">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>
                            <i class="fa-solid fa-tag me-1"></i>Label Periode
                        </th>
                        <th width="150" class="text-center">
                            <i class="fa-solid fa-calendar me-1"></i>Bulan
                        </th>
                        <th width="120" class="text-center">
                            <i class="fa-solid fa-calendar-days me-1"></i>Tahun
                        </th>
                        <th width="120" class="text-center">
                            <i class="fa-solid fa-toggle-on me-1"></i>Status
                        </th>
                        <th width="200" class="text-center">
                            <i class="fa-solid fa-cog me-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periods as $index => $p)
                    <tr class="period-row">
                        <td class="text-center">
                            <div class="number-badge">{{ $index + 1 }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <strong>{{ $p->label }}</strong>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">
                                {{ DateTime::createFromFormat('!m', $p->month)->format('F') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $p->year }}</span>
                        </td>
                        <td class="text-center">
                            @if($p->is_active)
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fa-solid fa-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                @if(!$p->is_active)
                                    <a href="{{ route('periods.activate', $p->id) }}"
                                       class="btn btn-sm btn-outline-info rounded"
                                       title="Aktifkan Periode">
                                        <i class="fa-solid fa-power-off"></i>
                                    </a>
                                @endif

                                @if($p->evaluations_count === 0)
                                    <a href="{{ route('periods.edit', $p->id) }}"
                                       class="btn btn-sm btn-outline-warning rounded"
                                       title="Edit Periode">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded delete-btn"
                                            data-id="{{ $p->id }}"
                                            data-label="{{ $p->label }}"
                                            title="Hapus Periode">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @else
                                    <span class="badge bg-light text-muted" title="Sudah memiliki penilaian">
                                        <i class="fa-solid fa-lock me-1"></i>Terkunci
                                    </span>
                                @endif
                            </div>

                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $p->id }}"
                                  action="{{ route('periods.destroy', $p->id) }}"
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
    @endif
</div>

<style>
/* Number Badge */
.number-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #6c757d;
}

/* Table Styling */
.periods-table thead th {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.periods-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.period-row {
    transition: all 0.2s ease;
}

.period-row:hover {
    background-color: rgba(99, 102, 241, 0.05);
    transform: translateX(2px);
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .periods-table {
        font-size: 0.875rem;
    }
}
</style>

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const periodId = this.dataset.id;
        const periodLabel = this.dataset.label;

        if (confirm(`Apakah Anda yakin ingin menghapus periode "${periodLabel}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
            document.getElementById(`delete-form-${periodId}`).submit();
        }
    });
});
</script>
@endpush

@endsection