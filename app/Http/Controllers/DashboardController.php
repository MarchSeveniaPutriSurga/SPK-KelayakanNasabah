<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\Period;
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
            ])->with('info', 'Belum ada penilaian pada periode ini.');
        }

        // Kriteria
        $criteria = Criterion::orderBy('id')->get();

        // Customer yang dinilai
        $customerIds = $evaluations->pluck('customer_id')->unique()->toArray();
        $customers = Customer::whereIn('id', $customerIds)->get()->keyBy('id');

        // Matriks nilai
        $scoreMatrix = [];
        foreach ($customerIds as $cid) {
            foreach ($criteria as $c) {
                $ev = $evaluations
                    ->where('customer_id', $cid)
                    ->where('criterion_id', $c->id)
                    ->first();

                $scoreMatrix[$cid][$c->id] = $ev ? (int) $ev->score : 0;
            }
        }

        // Normalisasi
        $normFactor = [];
        foreach ($criteria as $c) {
            $col = array_column($scoreMatrix, $c->id);
            $normFactor[$c->id] = [
                'max' => max($col),
                'min' => min($col)
            ];
        }

        // Hitung skor total SMART
        $results = [];
        foreach ($scoreMatrix as $cid => $rows) {
            $total = 0;

            foreach ($criteria as $c) {
                $raw = $rows[$c->id];

                if ($c->type === 'benefit') {
                    $norm = ($normFactor[$c->id]['max'] > 0)
                        ? $raw / $normFactor[$c->id]['max']
                        : 0;
                } else {
                    $norm = ($raw > 0)
                        ? $normFactor[$c->id]['min'] / $raw
                        : 0;
                }

                $total += $norm * $c->weight;
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
        ]);
    }
}
