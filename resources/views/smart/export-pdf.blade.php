<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body  { font-family: Arial, sans-serif; font-size: 9px; color: #222; }
    h2    { font-size: 14px; margin-bottom: 2px; color: #2a7a6e; }
    .meta { font-size: 8px; color: #666; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th    { background-color: #2a7a6e; color: #fff; padding: 5px 4px; text-align: center; font-size: 8px; }
    td    { padding: 4px; border: 1px solid #ddd; text-align: center; font-size: 8px; }
    tr:nth-child(even) td { background-color: #f0faf8; }
    .rank  { font-weight: bold; font-size: 11px; }
    .name  { text-align: left; font-weight: bold; }
    .total { font-weight: bold; color: #2a7a6e; font-size: 10px; }
    .reject { color: #c0392b; font-weight: bold; }
    .rekom { font-weight: bold; color: #1a5c52; }
</style>
</head>
<body>

<h2>Hasil Ranking SPK - SMART</h2>
<div class="meta">
    Periode: <strong>{{ $periodLabel }}</strong> &nbsp;|&nbsp;
    Diekspor: {{ $exportedAt }} &nbsp;|&nbsp;
    Jumlah Nasabah: {{ count($results) }}
</div>

<table>
    <thead>
        <tr>
            <th style="width:30px">Rank</th>
            <th style="width:120px;text-align:left">Nama Nasabah</th>
            @foreach($criteria as $c)
                <th>{{ $c->code }}<br><span style="font-weight:normal">{{ $c->name }}</span></th>
            @endforeach
            <th>Total Skor</th>
            <th>Rekomendasi Pencairan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($results as $i => $r)
            <tr>
                <td class="rank">{{ $i + 1 }}</td>
                <td class="name">{{ $r['customer']->name }}</td>

                @foreach($criteria as $c)
                    @php $d = $r['detail'][$c->id] ?? []; @endphp
                    <td>
                        @if(str_contains(strtolower($c->name), 'keuntungan'))
                            {{ number_format($d['real_value'] ?? 0, 1) }} %
                        @else
                            {{ number_format($d['real_value'] ?? 0, 0, ',', '.') }}
                        @endif
                    </td>
                @endforeach

                <td class="total">{{ number_format($r['total'], 2) }}</td>
                <td>
                    @if($r['rekomendasi'] > 0)
                        <span class="rekom">Rp {{ number_format($r['rekomendasi'], 0, ',', '.') }}</span>
                    @else
                        <span class="reject">Ditolak</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($criteria) + 4 }}" style="text-align:center;padding:20px">
                    Tidak ada data
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>