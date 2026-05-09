@extends('layouts.app')

@section('content')
<div class="card card-soft p-4">
  <!-- Header -->
  <div class="d-flex align-items-center mb-4">
    <div class="icon-circle me-3">
      <i class="fa-solid fa-ranking-star"></i>
    </div>
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
        <small>Menampilkan {{ is_array($results) ? count($results) : 0 }} nasabah dengan {{ is_array($criteria) || is_object($criteria) ? count($criteria) : 0 }} kriteria penilaian</small>
      </div>
    </div>

    <!-- Tabel Ranking -->
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th width="80" class="text-center">
              <i class="fa-solid fa-trophy me-1"></i>Rank
            </th>
            <th width="200">
              <i class="fa-solid fa-user me-1"></i>Nama Nasabah
            </th>
            @foreach($criteria as $c)
              <th class="text-center">
                <div class="fw-bold text-primary">{{ $c->code }}</div>
                <small class="text-muted d-block">{{ $c->name }}</small>
                <span class="badge bg-secondary mt-1">Bobot: {{ $c->weight }}</span>
              </th>
            @endforeach
            <th width="120" class="text-center bg-primary bg-opacity-10">
              <i class="fa-solid fa-calculator me-1"></i>
              <div class="fw-bold">Total Skor</div>
            </th>
            <th width="160" class="text-center">
              <i class="fa-solid fa-circle-check me-1"></i>Status
            </th>
            <th>Rekomendasi Pencairan</th>
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
                  <div class="d-flex align-items-center">
                    <div class="avatar-circle me-2">
                      {{ strtoupper(substr($r['customer']->name, 0, 1)) }}
                    </div>
                    <strong>{{ $r['customer']->name }}</strong>
                  </div>
                </td>
                
                <!-- Detail Kriteria -->
                @foreach($criteria as $c)
                  @php($d = $r['detail'][$c->id])
                  <td>
                    <div class="criteria-detail">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Skor:</small>
                        <span class="badge bg-info">{{ number_format($d['raw'], 2) }}</span>
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
                <td class="text-center bg-primary bg-opacity-10">
                  <div class="total-score">
                    {{ number_format($r['total'], 3) }}
                  </div>
                </td>

                <td class="text-center">
                  @if($r['status'] === 'Layak Lanjut')
                    <span class="badge bg-success">
                      <i class="fa-solid fa-check me-1"></i>Layak Lanjut
                    </span>
                  @elseif($r['status'] === 'Tidak Layak')
                    <span class="badge bg-danger">
                      <i class="fa-solid fa-xmark me-1"></i>Tidak Layak
                    </span>
                  @else
                    <span class="badge bg-secondary">-</span>
                  @endif
                </td>
                <td>
                    Rp {{ number_format($r['rekomendasi'], 0, ',', '.') }}
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="{{ count($criteria) + 4 }}" class="text-center py-5">
                <div class="empty-state">
                  <i class="fa-solid fa-chart-simple text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                  <h6 class="text-muted">Belum ada data penilaian</h6>
                  <p class="text-muted">Silakan input penilaian terlebih dahulu</p>
                </div>
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
        <div class="col-md-4">
          <small class="d-block mb-1"><strong>Skor:</strong> Nilai asli dari penilaian</small>
        </div>
        <div class="col-md-4">
          <small class="d-block mb-1"><strong>Norm:</strong> Nilai ternormalisasi (0-1)</small>
        </div>
        <div class="col-md-4">
          <small class="d-block mb-1"><strong>W×N:</strong> Bobot × Normalisasi</small>
        </div>
      </div>
    </div>
@else
    <!-- Empty State -->
    <div class="text-center py-5">
      <div class="empty-state">
        <i class="fa-solid fa-chart-simple text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
        <h5 class="text-muted">Belum ada data yang ditampilkan</h5>
        <p class="text-muted">Silakan pilih periode terlebih dahulu untuk menampilkan hasil perhitungan SMART</p>
      </div>
    </div>
@endif
</div>
@endsection