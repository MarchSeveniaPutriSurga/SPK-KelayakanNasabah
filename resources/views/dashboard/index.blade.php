@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Header -->
    <div class="welcome-banner card-soft mb-4">
        <div class="welcome-content">
            <div class="welcome-text">
                <h2 class="welcome-title">Dashboard </h2>
                <p class="welcome-subtitle">Sistem Pendukung Keputusan Penentuan Kelayakan Nasabah untuk Pinjaman Lanjutan</p>
            </div>
        </div>
        <div class="welcome-meta">
            <div class="welcome-date">
                <i class="fa-solid fa-calendar-day"></i>
                <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
        </div>
    </div>

    <!-- Filter Periode Global -->
    <div class="filter-section mb-4">
        <div class="filter-card">
            <div class="filter-header">
                <i class="fa-solid fa-filter"></i>
                <span>Filter Periode Analisis</span>
            </div>
            <form method="get" action="{{ route('dashboard') }}" class="filter-form-inline">
                <select name="period" class="form-select-modern" onchange="this.form.submit()">
                    <option value="">Pilih Periode</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" 
                            {{ (request('period') == $p->id) || (!request('period') && $selectedPeriod && $selectedPeriod->id == $p->id) ? 'selected' : '' }}>
                            {{ $p->label }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-sync"></i> Terapkan
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <!-- Total Nasabah -->
        <div class="stat-card stat-primary">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Nasabah</span>
                <h3 class="stat-value">{{ $totalCustomers }}</h3>
                <div class="stat-meta">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Data Master</span>
                </div>
            </div>
            <a href="{{ route('customers.index') }}" class="stat-action">
                <span>Lihat Detail</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Nasabah Dinilai (Periode) -->
        <div class="stat-card stat-info">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <div class="stat-details">
                <span class="stat-label">Nasabah Dinilai</span>
                <h3 class="stat-value">{{ $assessedCustomers }}</h3>
                <div class="stat-meta">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span>Periode Ini</span>
                </div>
            </div>
            <a href="{{ route('smart.index') }}" class="stat-action">
                <span>Lihat</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Total Kriteria -->
        <div class="stat-card stat-success">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Kriteria</span>
                <h3 class="stat-value">{{ $totalCriteria }}</h3>
                <div class="stat-meta">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span>Aktif</span>
                </div>
            </div>
            <a href="{{ route('criteria.index') }}" class="stat-action">
                <span>Kelola</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Total Periode -->
        <div class="stat-card stat-warning">
            <div class="stat-icon-wrapper">
                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Periode</span>
                <h3 class="stat-value">{{ $totalPeriods }}</h3>
                <div class="stat-meta">
                    <i class="fa-solid fa-check-circle"></i>
                    <span>Terdaftar</span>
                </div>
            </div>
            <a href="{{ route('periods.index') }}" class="stat-action">
                <span>Lihat</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid-enhanced mb-4">
        <!-- Ranking Chart -->
        <div class="chart-card ranking-card">
            <div class="card-header-custom">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fa-solid fa-ranking-star"></i>
                    </div>
                    <div>
                        <h5 class="card-title-custom">Top 5 Nasabah Terbaik</h5>
                        <p class="card-subtitle">Berdasarkan perhitungan metode SMART</p>
                    </div>
                </div>
            </div>
            <div class="card-body-custom">
                @if(isset($rankings) && count($rankings) > 0)
                    <div class="ranking-list">
                        @foreach(array_slice($rankings, 0, 5) as $index => $rank)
                            <div class="ranking-item">
                                <div class="rank-badge rank-{{ $index + 1 }}">
                                    @if($index == 0)
                                        <i class="fa-solid fa-trophy"></i>
                                    @elseif($index == 1)
                                        <i class="fa-solid fa-medal"></i>
                                    @elseif($index == 2)
                                        <i class="fa-solid fa-medal"></i>
                                    @else
                                        <span>{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="rank-info">
                                    <h6 class="rank-name">{{ $rank['customer']->name }}</h6>
                                    <span class="rank-score">Skor: {{ number_format($rank['total'], 3) }}</span>
                                </div>
                                <div class="rank-progress">
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill" style="width: {{ ($rank['total'] / ($rankings[0]['total'] ?? 1)) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-chart-simple"></i>
                        <h6>Belum Ada Data Ranking</h6>
                        <p>Pilih periode untuk melihat ranking nasabah</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bar Chart - Rata-rata Skor per Kriteria -->
        <div class="chart-card">
            <div class="card-header-custom">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>
                    <div>
                        <h5 class="card-title-custom">Analisis Per Kriteria</h5>
                        <p class="card-subtitle">Rata-rata skor setiap kriteria penilaian</p>
                    </div>
                </div>
            </div>
            <div class="card-body-custom">
                @if(isset($criteriaAvgScores) && count($criteriaAvgScores) > 0)
                    <div class="chart-wrapper">
                        <canvas id="criteriaBarChart"></canvas>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-chart-column"></i>
                        <h6>Belum Ada Data</h6>
                        <p>Pilih periode untuk melihat analisis kriteria</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats-section mb-4">
        <div class="chart-card">
            <div class="card-header-custom">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <div>
                        <h5 class="card-title-custom">Statistik Cepat</h5>
                        <p class="card-subtitle">Ringkasan data sistem</p>
                    </div>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="quick-stats-grid">
                    <div class="quick-stat-item">
                        <div class="quick-stat-icon bg-primary">
                            <i class="fa-solid fa-weight-hanging"></i>
                        </div>
                        <div class="quick-stat-content">
                            <span class="quick-stat-label">Total Bobot</span>
                            <h4 class="quick-stat-value">{{ number_format($totalWeight, 2) }}</h4>
                            @if(abs($totalWeight - 1) < 0.01)
                                <span class="badge-success">Valid 100%</span>
                            @else
                                <span class="badge-warning">Perlu Penyesuaian</span>
                            @endif
                        </div>
                    </div>

                    <div class="quick-stat-item">
                        <div class="quick-stat-icon bg-success">
                            <i class="fa-solid fa-percentage"></i>
                        </div>
                        <div class="quick-stat-content">
                            <span class="quick-stat-label">Rata-rata Skor</span>
                            <h4 class="quick-stat-value">{{ isset($avgScore) ? number_format($avgScore, 2) : '0.00' }}</h4>
                            <span class="quick-stat-desc">Dari semua nasabah</span>
                        </div>
                    </div>

                    <div class="quick-stat-item">
                        <div class="quick-stat-icon bg-warning">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div class="quick-stat-content">
                            <span class="quick-stat-label">Metode</span>
                            <h4 class="quick-stat-value">SMART</h4>
                            <span class="quick-stat-desc">Simple Multi-Attribute</span>
                        </div>
                    </div>

                    <div class="quick-stat-item">
                        <div class="quick-stat-icon bg-info">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="quick-stat-content">
                            <span class="quick-stat-label">Update Terakhir</span>
                            <h4 class="quick-stat-value-small">
                                {{ $lastUpdate ? $lastUpdate->updated_at->diffForHumans() : 'Belum ada' }}
                            </h4>
                            <span class="quick-stat-desc">Data penilaian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section mb-4">
        <div class="section-header">
            <div class="section-icon">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <h5 class="section-title">Aksi Cepat</h5>
        </div>
        <div class="actions-grid">
            <a href="{{ route('penilaian.create') }}" class="action-card action-primary">
                <div class="action-icon">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <span class="action-label">Input Penilaian</span>
            </a>
            <a href="{{ route('smart.index') }}" class="action-card action-success">
                <div class="action-icon">
                    <i class="fa-solid fa-ranking-star"></i>
                </div>
                <span class="action-label">Lihat Ranking</span>
            </a>
            <a href="{{ route('customers.create') }}" class="action-card action-warning">
                <div class="action-icon">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <span class="action-label">Tambah Nasabah</span>
            </a>
            <a href="{{ route('periods.create') }}" class="action-card action-info">
                <div class="action-icon">
                    <i class="fa-solid fa-calendar-plus"></i>
                </div>
                <span class="action-label">Tambah Periode</span>
            </a>
        </div>
    </div>

    <!-- Kriteria Overview -->
    <div class="chart-card">
        <div class="card-header-custom">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h5 class="card-title-custom">Kriteria Penilaian</h5>
                    <p class="card-subtitle">Daftar kriteria yang digunakan dalam sistem</p>
                </div>
            </div>
            <a href="{{ route('criteria.index') }}" class="btn-link-custom">
                Kelola Kriteria <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body-custom">
            @if($criteria->count() > 0)
                <div class="criteria-grid">
                    @foreach($criteria as $c)
                        <div class="criteria-item">
                            <div class="criteria-header">
                                <span class="criteria-code">{{ $c->code }}</span>
                                <span class="criteria-weight">{{ number_format($c->weight * 100, 0) }}%</span>
                            </div>
                            <h6 class="criteria-name">{{ $c->name }}</h6>
                            <div class="criteria-footer">
                                <span class="criteria-type">
                                    <i class="fa-solid fa-{{ $c->type == 'benefit' ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ ucfirst($c->type) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-list"></i>
                    <h6>Belum Ada Kriteria</h6>
                    <p>Tambahkan kriteria untuk memulai penilaian</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Donut Chart - Distribusi Kategori
@if(isset($donutValues) && array_sum($donutValues) > 0)
const donutCtx = document.getElementById('categoryDonutChart');
if (donutCtx) {
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($donutLabels) !!},
            datasets: [{
                data: {!! json_encode($donutValues) !!},
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',   // Sangat Layak - Green
                    'rgba(59, 130, 246, 0.8)',  // Layak - Blue
                    'rgba(251, 146, 60, 0.8)',  // Pertimbangan - Orange
                    'rgba(239, 68, 68, 0.8)',   // Tidak Layak - Red
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(251, 146, 60, 1)',
                    'rgba(239, 68, 68, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}
@endif

// Bar Chart - Rata-rata per Kriteria
@if(isset($criteriaAvgScores) && count($criteriaAvgScores) > 0)
const barCtx = document.getElementById('criteriaBarChart');
if (barCtx) {
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($criteriaLabels) !!},
            datasets: [{
                label: 'Rata-rata Skor',
                data: {!! json_encode($criteriaAvgScores) !!},
                backgroundColor: 'rgba(88, 180, 204, 0.8)',
                borderColor: 'rgba(88, 180, 204, 1)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rata-rata: ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}
@endif

// Sticky navbar with scroll effect
window.addEventListener('scroll', function() {
    const banner = document.querySelector('.welcome-banner');
    
    if (window.scrollY > 50) {
        banner.classList.add('scrolled');
    } else {
        banner.classList.remove('scrolled');
    }
});
</script>
@endpush

@endsection