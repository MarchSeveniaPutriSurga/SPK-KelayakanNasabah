@extends('layouts.app')

@section('content')

<div class="card card-soft p-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                Riwayat Penilaian
            </h4>
            <p class="text-muted mb-0 small">
                Riwayat hasil penilaian dan perhitungan SMART
            </p>
        </div>
    </div>

    {{-- FILTER PERIODE --}}
    <form method="GET" action="{{ route('penilaian.riwayat') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">
                    Periode Penilaian
                </label>
                <select name="period_id"
                        class="form-select"
                        onchange="this.form.submit()">
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}"
                            {{ $selected == $p->id ? 'selected' : '' }}>
                            {{ $p->label }}
                            {{ $p->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- INFO --}}
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fa-solid fa-calendar-check fs-4 me-3"></i>
        <div>
            <strong>Periode:</strong> {{ $selectedPeriod->label ?? '-' }}
            <br>
            <small>Menampilkan {{ count($results) }} nasabah dengan {{ $criteria->count() }} kriteria penilaian</small>
        </div>
    </div>

    {{-- TABLE --}}
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
                        <div class="fw-bold">SMART</div>
                    </th>

                    <th class="text-center">Rekomendasi PENCAIRAN</th>

                </tr>
            </thead>

            <tbody>

            @forelse($results as $index => $row)

                <tr>

                    {{-- RANK --}}
                    <td class="text-center">
                        <strong>{{ $row['ranking'] }}</strong>
                    </td>

                    {{-- NAMA --}}
                    <td>
                        <strong>{{ $row['customer'] }}</strong>
                    </td>

                    {{-- KRITERIA --}}
                    @foreach($criteria as $c)

                        @php $val = $row['values'][$c->id] ?? null; @endphp

                        <td class="text-center">

                            @if($val)

                                @if(str_contains(strtolower($c->name), 'keuntungan'))

                                    <div class="small text-muted">
                                        Untung: Rp {{ number_format($val['keuntungan'],0,',','.') }}
                                    </div>
                                    <div class="small text-muted">
                                        Modal: Rp {{ number_format($val['modal'],0,',','.') }}
                                    </div>
                                    <hr class="my-1">
                                    <div class="fw-bold text-dark">
                                        {{ number_format($val['real_value'],1) }} %
                                    </div>

                                @else

                                    <div class="fw-semibold">
                                        {{ number_format($val['real_value'],0,',','.') }}
                                    </div>

                                @endif

                                <div class="mt-1">
                                    <span class="badge" style="background-color: #91C6BC !important;">
                                        Score {{ $val['score'] }}
                                    </span>
                                </div>

                            @else
                                -
                            @endif

                        </td>

                    @endforeach

                    {{-- SMART SCORE --}}
                    <td class="text-center">
                        <span style="background-color: rgba(145, 198, 188, 0.2); color: #2a7a6e; font-weight: 800; font-size: 1.1rem; padding: 0.4rem 0.85rem; border-radius: 8px; border: 1px solid rgba(145, 198, 188, 0.5);">
                            {{ number_format($row['smart_score'], 4) }}
                        </span>
                    </td>

                    {{-- REKOMENDASI --}}
                    <td class="text-center">
                        @if($row['rekomendasi'] > 0)
                            <span class="fw-bold">
                                Rp {{ number_format($row['rekomendasi'],0,',','.') }}
                            </span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="{{ $criteria->count() + 4 }}"
                        class="text-center text-muted py-5">
                        <i class="fa-solid fa-chart-simple mb-3 d-block" style="font-size: 3rem; opacity: 0.3;"></i>
                        <h6 class="text-muted">Belum ada data penilaian</h6>
                        <p class="text-muted small">Silakan input penilaian terlebih dahulu</p>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection