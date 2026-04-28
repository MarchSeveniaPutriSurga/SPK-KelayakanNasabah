@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-circle me-3">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Data Nasabah</h4>
                <p class="text-muted mb-0 small">Kelola informasi nasabah untuk penilaian</p>
            </div>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-user-plus me-2"></i>Tambah Nasabah
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="search-box">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" 
                       class="form-control form-control-lg ps-5" 
                       id="searchInput" 
                       placeholder="Cari nasabah berdasarkan nama, email, atau nomor telepon...">
            </div>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('customers.index') }}" id="sortForm">
                <select class="form-select form-select-lg" id="filterSort" name="sort" onchange="this.form.submit()">
                    <option value="name-asc" {{ request('sort', 'name-asc') == 'name-asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
            </form>
        </div>
    </div>

    @if($customers->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="fa-solid fa-user-slash text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="text-muted">Belum ada data nasabah</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan nasabah pertama Anda</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-user-plus me-2"></i>Tambah Nasabah Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-primary-subtle">
                        <i class="fa-solid fa-users text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $customers->count() }}</h3>
                        <small class="text-muted">Total Nasabah</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-success-subtle">
                        <i class="fa-solid fa-phone text-success"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $customers->filter(fn($c) => $c->phone)->count() }}</h3>
                        <small class="text-muted">Dengan Telepon</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-modern">
                    <div class="stat-icon bg-info-subtle">
                        <i class="fa-solid fa-id-card text-info"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="mb-0">{{ $customers->filter(fn($c) => $c->identifier)->count() }}</h3>
                        <small class="text-muted">Dengan Email</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle customers-table" id="customersTable">
                <thead class="table-light">
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>
                            <i class="fa-solid fa-user me-1"></i>Nama Nasabah
                        </th>
                        <th width="200">
                            <i class="fa-solid fa-id-card me-1"></i>Email
                        </th>
                        <th width="180">
                            <i class="fa-solid fa-phone me-1"></i>Telepon
                        </th>
                        <th width="150" class="text-center">
                            <i class="fa-solid fa-cog me-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $index => $c)
                    <tr class="customer-row" data-name="{{ strtolower($c->name) }}" data-identifier="{{ strtolower($c->identifier ?? '') }}" data-phone="{{ $c->phone ?? '' }}">
                        <td class="text-center">
                            <div class="number-badge">{{ $index + 1 }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                {{-- <div class="avatar-circle me-2">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div> --}}
                                <div>
                                    <strong class="d-block">{{ $c->name }}</strong>
                                    <small class="text-muted">Nasabah #{{ $c->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($c->identifier)
                                <span class="badge bg-light text-dark">
                                    <i class="fa-solid fa-hashtag me-1"></i>{{ $c->identifier }}
                                </span>
                            @else
                                <span class="text-muted small">
                                    <i class="fa-solid fa-minus"></i> Tidak ada
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($c->phone)
                                <a href="tel:{{ $c->phone }}" class="text-decoration-none">
                                    <i class="fa-solid fa-phone-volume me-1 text-success"></i>
                                    {{ $c->phone }}
                                </a>
                            @else
                                <span class="text-muted small">
                                    <i class="fa-solid fa-phone-slash me-1"></i> Tidak ada
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2">
                                <a href="{{ route('customers.edit', $c->id) }}"
                                class="btn btn-sm btn-outline-warning rounded"
                                title="Edit Nasabah">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded delete-btn"
                                        data-id="{{ $c->id }}"
                                        data-name="{{ $c->name }}"
                                        title="Hapus Nasabah">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $c->id }}" 
                                action="{{ route('customers.destroy', $c->id) }}" 
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

        <!-- No Results Message -->
        <div id="noResults" class="text-center py-4" style="display: none;">
            <i class="fa-solid fa-search text-muted mb-2" style="font-size: 3rem; opacity: 0.3;"></i>
            <h6 class="text-muted">Tidak ada hasil yang ditemukan</h6>
            <small class="text-muted">Coba ubah kata kunci pencarian Anda</small>
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

/* Avatar Circle */
.avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    flex-shrink: 0;
}

/* Search Box */
.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 1.125rem;
}

.search-box .form-control {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
}

.search-box .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15);
}

/* Form Select Styling */
.form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.2s ease;
    cursor: pointer;
}

.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15);
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

/* Number Badge */
.number-badge {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #6c757d;
    font-size: 0.875rem;
}

/* Table Styling */
.customers-table thead th {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.customers-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.customer-row {
    transition: all 0.2s ease;
}

.customer-row:hover {
    background-color: rgba(99, 102, 241, 0.05);
    transform: translateX(2px);
}

/* Buttons */
.btn {
    border-radius: 0;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
    
    .customers-table {
        font-size: 0.875rem;
    }
    
    .stat-card-modern {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
    
    .avatar-circle {
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }
}
</style>

@push('scripts')
<script>
// Search functionality
const searchInput = document.getElementById('searchInput');
const tableRows = document.querySelectorAll('.customer-row');
const noResults = document.getElementById('noResults');

searchInput?.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    let visibleCount = 0;
    
    tableRows.forEach(row => {
        const name = row.dataset.name || '';
        const identifier = row.dataset.identifier || '';
        const phone = row.dataset.phone || '';
        
        if (name.includes(searchTerm) || identifier.includes(searchTerm) || phone.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
});

// Delete confirmation
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const customerId = this.dataset.id;
        const customerName = this.dataset.name;
        
        if (confirm(`Apakah Anda yakin ingin menghapus nasabah "${customerName}"?\n\nTindakan ini tidak dapat dibatalkan!`)) {
            document.getElementById(`delete-form-${customerId}`).submit();
        }
    });
});
</script>
@endpush

@endsection