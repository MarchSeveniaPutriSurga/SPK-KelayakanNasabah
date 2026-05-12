@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Hasil Ranking SPK - SMART</h4>
            <p class="text-muted mb-0 small">Sistem Pendukung Keputusan menggunakan metode SMART</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="alert alert-primary d-flex align-items-center mb-4">
        <i class="fa-solid fa-calendar-check fs-4 me-3"></i>
        <div>
            <strong>Periode Aktif:</strong>
            {{ $selectedPeriod->label ?? 'Tidak ada periode aktif' }}
        </div>
    </div>

    @if(isset($selected) && $selected)

        <!-- Info Periode -->
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fa-solid fa-info-circle fs-4 me-3"></i>
            <div>
                <strong>Periode Terpilih:</strong> {{ $periods->firstWhere('id',$selected)->label ?? '-' }}
                <br>
                <small>Menampilkan {{ is_array($results) ? count($results) : 0 }} nasabah dengan {{ count($criteria) }} kriteria penilaian</small>
            </div>
        </div>

        <!-- Tombol Export -->
        @if(count($results) > 0)
        <div class="d-flex gap-2 justify-content-end mb-3">
            <a href="{{ route('smart.export.excel') }}"
              class="btn btn-sm btn-success"
              title="Export ke Excel">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('smart.export.pdf') }}"
              class="btn btn-sm btn-danger"
              title="Export ke PDF">
                <i class="fa-solid fa-file-pdf me-1"></i> Export PDF
            </a>
        </div>
        @endif

        <!-- Tabel Ranking -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80" class="text-center">Rank</th>
                        <th width="200">Nama Nasabah</th>

                        @foreach($criteria as $c)
                            <th class="text-center">
                                <div class="fw-bold">{{ $c->code }}</div>
                                <small class="text-muted d-block">{{ $c->name }}</small>
                                <span class="badge bg-secondary mt-1">Bobot: {{ $c->weight }}</span>
                            </th>
                        @endforeach

                        <th width="120" class="text-center bg-primary bg-opacity-10">
                            <i class="fa-solid fa-calculator me-1"></i>
                            <div class="fw-bold">Total Skor</div>
                        </th>

                        <th class="text-center">Rekomendasi Pencairan</th>
                    </tr>
                </thead>

                <tbody>
                @if(count($results) > 0)
                    @foreach($results as $i => $r)
                        <tr>

                            <!-- Rank -->
                            <td class="text-center">
                                <span class="fs-5 fw-bold">{{ $i + 1 }}</span>
                            </td>

                            <!-- Nama Nasabah -->
                            <td>
                                <strong>{{ $r['customer']->name }}</strong>
                            </td>

                            <!-- Detail Kriteria -->
                            @foreach($criteria as $c)
                                @php $d = $r['detail'][$c->id]; @endphp
                                <td class="text-center">

                                    {{-- NILAI REAL --}}
                                    @if(str_contains(strtolower($c->name), 'keuntungan'))

                                        <div class="small text-muted">
                                            Untung: Rp {{ number_format($d['keuntungan'] ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="small text-muted">
                                            Modal: Rp {{ number_format($d['modal'] ?? 0, 0, ',', '.') }}
                                        </div>
                                        <hr class="my-1">
                                        <div class="fw-bold text-dark">
                                            {{ number_format($d['real_value'] ?? 0, 1) }} %
                                        </div>

                                    @else

                                        <div class="fw-semibold">
                                            {{ number_format($d['real_value'] ?? 0, 0, ',', '.') }}
                                        </div>

                                    @endif

                                    {{-- SKOR / NORM / WEIGHTED --}}
                                    <div class="criteria-detail mt-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">Skor:</small>
                                            <span class="badge" style="background-color: #91C6BC !important;">
                                                {{ number_format($d['raw'], 2) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">Norm:</small>
                                            <span class="badge bg-light text-dark">{{ number_format($d['norm'], 3) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">W×N:</small>
                                            <span class="badge bg-light text-dark">{{ number_format($d['weighted'], 3) }}</span>
                                        </div>
                                    </div>

                                </td>
                            @endforeach

                            <!-- Total -->
                            <td class="text-center">
                                <span style="background-color: rgba(145, 198, 188, 0.2); color: #2a7a6e; font-weight: 800; font-size: 1.2rem; padding: 0.4rem 0.85rem; border-radius: 8px; border: 1px solid rgba(145, 198, 188, 0.5);">
                                    {{ number_format($r['total'], 2) }}
                                </span>
                            </td>

                            <!-- Rekomendasi -->
                            <td class="text-center">
                                @if($r['rekomendasi'] > 0)
                                    <span class="fw-bold">
                                        Rp {{ number_format($r['rekomendasi'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($criteria) + 3 }}" class="text-center py-5">
                            <i class="fa-solid fa-chart-simple mb-3 d-block" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h6 class="text-muted">Belum ada data penilaian</h6>
                            <p class="text-muted">Silakan input penilaian terlebih dahulu</p>
                        </td>
                    </tr>
                @endif
                </tbody>

            </table>
        </div>

        <!-- Legend -->
        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="mb-3"><i class="fa-solid fa-circle-info me-2"></i>Keterangan:</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <small class="d-block mb-1"><strong>Nilai:</strong> Data real hasil input penilaian</small>
                </div>
                <div class="col-md-3">
                    <small class="d-block mb-1"><strong>Skor:</strong> Nilai skor berdasarkan parameter</small>
                </div>
                <div class="col-md-3">
                    <small class="d-block mb-1"><strong>Norm:</strong> Nilai ternormalisasi (0-1)</small>
                </div>
                <div class="col-md-3">
                    <small class="d-block mb-1"><strong>W×N:</strong> Bobot × Normalisasi</small>
                </div>
            </div>
        </div>

    @else

        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="fa-solid fa-chart-simple text-muted mb-3 d-block" style="font-size: 4rem; opacity: 0.3;"></i>
            <h5 class="text-muted">Belum ada data yang ditampilkan</h5>
            <p class="text-muted">Silakan pilih periode terlebih dahulu untuk menampilkan hasil perhitungan SMART</p>
        </div>

    @endif

</div>
@endsection