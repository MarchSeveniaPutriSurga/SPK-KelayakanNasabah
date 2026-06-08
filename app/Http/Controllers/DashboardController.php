<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Semua periode untuk dropdown
        $periods = Period::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Ambil periode
        if ($request->has('period') && $request->period != "") {
            $selectedPeriod = Period::find($request->period);
        } else {
            $selectedPeriod = Period::where('is_active', true)->first();
        }

        // Statistik dasar
        $totalCustomers    = Customer::count();
        $totalCriteria     = Criterion::count();
        $totalPeriods      = Period::count();
        $totalAssessments  = Evaluation::count();
        $totalWeight       = Criterion::sum('weight');

        // Data peminjaman per bulan untuk line chart
        $currentYear = now()->year;

        $loanData = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $loanLineLabels = [];
        $loanLineValues = [];

        for ($i = 1; $i <= 12; $i++) {
            $loanLineLabels[] = Carbon::create()->month($i)->translatedFormat('F');
            $loanLineValues[] = $loanData[$i] ?? 0;
        }

        if (!$selectedPeriod) {
            return view('dashboard.index', [
                'periods'          => $periods,
                'selectedPeriod'   => null,
                'totalCustomers'   => $totalCustomers,
                'totalCriteria'    => $totalCriteria,
                'totalPeriods'     => $totalPeriods,
                'totalAssessments' => $totalAssessments,
                'totalWeight'      => $totalWeight,
                'avgScore'         => 0,
                'lastUpdate'       => null,
                'rankings'         => [],
                'barLabels'        => [],
                'barValues'        => [],
                'donutLabels'      => [],
                'donutValues'      => [],
                'topFive'          => [],
                'criteria'         => collect(),
                // Data tambahan
                'criteriaLabels'   => [],
                'criteriaAvgScores' => [],
                'assessedCustomers' => 0,
                //Data line chart peminjam
                'loanLineLabels' => $loanLineLabels,
                'loanLineValues' => $loanLineValues,
            ])->with('error', 'Belum ada periode aktif atau belum dipilih.');
        }

        $periodId = $selectedPeriod->id;

        // Ambil evaluasi sesuai periode
        $evaluations = Evaluation::where('period_id', $periodId)->get();

        if ($evaluations->isEmpty()) {
            return view('dashboard.index', [
                'periods'          => $periods,
                'selectedPeriod'   => $selectedPeriod,
                'totalCustomers'   => $totalCustomers,
                'totalCriteria'    => $totalCriteria,
                'totalPeriods'     => $totalPeriods,
                'totalAssessments' => $totalAssessments,
                'totalWeight'      => $totalWeight,
                'avgScore'         => 0,
                'lastUpdate'       => null,
                'rankings'         => [],
                'barLabels'        => [],
                'barValues'        => [],
                'donutLabels'      => [],
                'donutValues'      => [],
                'topFive'          => [],
                'criteria'         => collect(),
                // Data tambahan
                'criteriaLabels'   => [],
                'criteriaAvgScores' => [],
                'assessedCustomers' => 0,
                //Data line chart peminjam
                'loanLineLabels' => $loanLineLabels,
                'loanLineValues' => $loanLineValues,
            ])->with('info', 'Belum ada penilaian pada periode ini.');
        }

        // Kriteria
        $criteria = Criterion::orderBy('id')->get();

        // Customer yang dinilai
        $customerIds = $evaluations->pluck('customer_id')->unique()->toArray();
        $customers = Customer::whereIn('id', $customerIds)->get()->keyBy('id');

        // Matriks nilai dan bobot
        $scoreMatrix = [];
        $weightMatrix = [];

        foreach ($customerIds as $cid) {
            foreach ($criteria as $c) {
                $ev = $evaluations
                    ->where('customer_id', $cid)
                    ->where('criterion_id', $c->id)
                    ->first();

                $scoreMatrix[$cid][$c->id] = $ev ? (int) $ev->score : 0;

                // Pakai bobot snapshot jika ada, kalau belum ada pakai bobot terbaru
                $weightMatrix[$cid][$c->id] = $ev && $ev->weight_snapshot !== null
                    ? (float) $ev->weight_snapshot
                    : (float) $c->weight;
            }
        }

        // Normalisasi nilai
        $normFactor = [];

        foreach ($criteria as $c) {
            $col = array_column($scoreMatrix, $c->id);

            $normFactor[$c->id] = [
                'max' => max($col),
                'min' => min($col),
            ];
        }

        // Hitung skor total SMART
        $results = [];

        foreach ($scoreMatrix as $cid => $rows) {
            $total = 0;

            foreach ($criteria as $c) {
                $raw = $rows[$c->id];

                $max = $normFactor[$c->id]['max'];
                $min = $normFactor[$c->id]['min'];

                // Rumus normalisasi benefit semua
                if (($max - $min) == 0) {
                    $norm = $raw > 0 ? 1 : 0;
                } else {
                    $norm = ($raw - $min) / ($max - $min);
                }

                $weight = $weightMatrix[$cid][$c->id];

                $total += $norm * $weight;
            }

            $results[] = [
                'customer' => $customers[$cid],
                'total'    => round($total, 6),
            ];
        }

        // Sort ranking
        usort($results, fn($a, $b) => $b['total'] <=> $a['total']);

        // Untuk bar chart
        $barLabels = array_map(fn($r) => $r['customer']->name, $results);
        $barValues = array_map(fn($r) => $r['total'], $results);

        // Rata-rata skor
        $avgScore = count($results)
            ? array_sum(array_column($results, 'total')) / count($results)
            : 0;

        // Last update
        $lastUpdate = Evaluation::where('period_id', $periodId)->latest()->first();

        // Donut - Distribusi Kategori
        $categories = [
            "Sangat Layak" => 0,
            "Layak" => 0,
            "Pertimbangan" => 0,
            "Tidak Layak" => 0,
        ];

        foreach ($results as $r) {
            if ($r['total'] >= 0.85) $categories["Sangat Layak"]++;
            elseif ($r['total'] >= 0.70) $categories["Layak"]++;
            elseif ($r['total'] >= 0.50) $categories["Pertimbangan"]++;
            else $categories["Tidak Layak"]++;
        }

        // === DATA TAMBAHAN ===

        // 1. Rata-rata skor per kriteria (untuk bar chart)
        $criteriaLabels = [];
        $criteriaAvgScores = [];

        foreach ($criteria as $c) {
            $criteriaLabels[] = $c->code;

            // Hitung rata-rata skor mentah untuk kriteria ini
            $scores = [];
            foreach ($customerIds as $cid) {
                $scores[] = $scoreMatrix[$cid][$c->id];
            }

            $criteriaAvgScores[] = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
        }

        // 2. Jumlah nasabah yang dinilai di periode ini
        $assessedCustomers = count($customerIds);

        return view('dashboard.index', [
            'periods'          => $periods,
            'selectedPeriod'   => $selectedPeriod,
            'totalCustomers'   => $totalCustomers,
            'totalCriteria'    => $totalCriteria,
            'totalPeriods'     => $totalPeriods,
            'totalAssessments' => $totalAssessments,
            'totalWeight'      => $totalWeight,
            'avgScore'         => $avgScore,
            'lastUpdate'       => $lastUpdate,
            'rankings'         => $results,
            'barLabels'        => $barLabels,
            'barValues'        => $barValues,
            'donutLabels'      => array_keys($categories),
            'donutValues'      => array_values($categories),
            'topFive'          => array_slice($results, 0, 5),
            'criteria'         => $criteria,
            // Data tambahan
            'criteriaLabels'   => $criteriaLabels,
            'criteriaAvgScores' => $criteriaAvgScores,
            'assessedCustomers' => $assessedCustomers,
            // Data line chart peminjaman
            'loanLineLabels' => $loanLineLabels,
            'loanLineValues' => $loanLineValues,
        ]);
    }
}
