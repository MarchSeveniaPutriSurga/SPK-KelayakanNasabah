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
    
    {{-- TOP BAR (FILTER + EXPORT) --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">

        {{-- FILTER PERIODE--}}
        <div class="alert alert-primary d-flex align-items-center mb-4">
            <i class="fa-solid fa-calendar-check fs-4 me-3"></i>
            <div>
                <strong>Periode Aktif:</strong>
                {{ $selectedPeriod->label ?? 'Tidak ada periode aktif' }}
            </div>
        </div>

        {{-- EXPORT BUTTON --}}
        @if(count($results) > 0)
            <div class="d-flex gap-2">

                <a href="{{ route('smart.export.excel') }}"
                class="btn btn-success">
                    <i class="fa-solid fa-file-excel me-1"></i>
                    Excel
                </a>

                <a href="{{ route('smart.export.pdf') }}"
                class="btn btn-danger">
                    <i class="fa-solid fa-file-pdf me-1"></i>
                    PDF
                </a>

            </div>
        @endif

    </div>

    {{-- INFO METODE SMART --}}
        <div class="mb-4 p-4" style="background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:44px;height:44px;background:#2a7a6e;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-lightbulb text-white"></i>
                </div>
                <h6 class="fw-bold mb-0">Tentang Metode SMART</h6>
            </div>

            <p class="text-muted small mb-3">
                <strong>SMART (Simple Multi-Attribute Rating Technique)</strong> adalah metode pengambilan keputusan multi kriteria
                yang menggunakan pembobotan linear untuk menghitung nilai utilitas setiap alternatif.
                Skor akhir dihitung dengan rumus:
            </p>

            {{-- Rumus --}}
            <div class="mb-3 px-3 py-2" style="border-left:4px solid #2a7a6e; background:#fff; border-radius:0 8px 8px 0;">
                <div class="fw-bold" style="font-size:1rem;">
                    Nilai(A) = &Sigma; (W<sub>j</sub> &times; U<sub>j</sub>(A))
                </div>
                <small class="text-muted">
                    dimana W<sub>j</sub> = bobot kriteria j,&nbsp; U<sub>j</sub> = nilai utilitas kriteria j
                </small>
            </div>

            {{-- Langkah --}}
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="p-2 rounded text-center h-100" style="background:#fff;border:1px solid #e2e8f0;">
                        <div class="fw-bold mb-1" style="color:#2a7a6e;">
                            <span class="badge mb-1" style="background:#2a7a6e;">1</span><br>Input Nilai
                        </div>
                        <small class="text-muted">Nilai real tiap kriteria diinput per nasabah</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-2 rounded text-center h-100" style="background:#fff;border:1px solid #e2e8f0;">
                        <div class="fw-bold mb-1" style="color:#2a7a6e;">
                            <span class="badge mb-1" style="background:#2a7a6e;">2</span><br>Konversi Skor
                        </div>
                        <small class="text-muted">Nilai dikonversi ke skor 1–5 berdasarkan parameter</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-2 rounded text-center h-100" style="background:#fff;border:1px solid #e2e8f0;">
                        <div class="fw-bold mb-1" style="color:#2a7a6e;">
                            <span class="badge mb-1" style="background:#2a7a6e;">3</span><br>Normalisasi
                        </div>
                        <small class="text-muted">Skor dinormalisasi: nilai / nilai maks kolom</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-2 rounded text-center h-100" style="background:#fff;border:1px solid #e2e8f0;">
                        <div class="fw-bold mb-1" style="color:#2a7a6e;">
                            <span class="badge mb-1" style="background:#2a7a6e;">4</span><br>Total Skor
                        </div>
                        <small class="text-muted">Norm &times; bobot dijumlahkan → ranking akhir</small>
                    </div>
                </div>
            </div>
        </div>

    @if(isset($selected) && $selected)
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
                    <small class="d-block mb-1"><strong>Norm:</strong> Nilai ternormalisasi / utilitas (0-1)</small>
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