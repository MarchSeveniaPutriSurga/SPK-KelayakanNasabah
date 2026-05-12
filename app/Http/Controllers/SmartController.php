<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Models\Customer;
use App\Models\Evaluation;
use App\Models\Period;
use Illuminate\Http\Request;

class SmartController extends Controller
{
    public function index(Request $request)
    {
        $periods  = Period::all();
        $criteria = Criterion::orderBy('id')->get();
        $results  = [];
        $selected = null;

        $period = Period::where('is_active', true)->first();

        $selectedPeriod = $period;

        if (!$period) {
            return view('smart.index', [
                'periods'        => $periods,
                'criteria'       => $criteria,
                'results'        => $results,
                'selected'       => null,
                'selectedPeriod' => null,
            ]);
        }

        $selected = $period->id;

        $evaluations = Evaluation::where('period_id', $selected)->get();

        if ($evaluations->isEmpty()) {
            return view('smart.index', compact('periods', 'criteria', 'results', 'selected', 'selectedPeriod'));
        }

        $customerIds = $evaluations->pluck('customer_id')->unique();
        $customers   = Customer::whereIn('id', $customerIds)->get();

        // --- 1. Build raw matrix ---
        $rawMatrix = [];
        foreach ($customers as $cust) {
            foreach ($criteria as $c) {
                $ev = $evaluations
                    ->where('customer_id', $cust->id)
                    ->where('criterion_id', $c->id)
                    ->first();

                $rawMatrix[$c->id][$cust->id] = $ev ? $ev->score : 0;
            }
        }

        // --- 2. Normalisasi & weighted ---
        foreach ($customers as $cust) {
            $detail = [];
            $total  = 0;

            foreach ($criteria as $c) {
                $ev           = $evaluations->where('customer_id', $cust->id)->where('criterion_id', $c->id)->first();
                $raw          = $rawMatrix[$c->id][$cust->id] ?? 0;
                $columnValues = array_values($rawMatrix[$c->id]);
                $maxVal       = max($columnValues);

                $norm     = $maxVal > 0 ? $raw / $maxVal : 0;
                $weighted = $norm * $c->weight;

                $detail[$c->id] = [
                    'raw'        => $raw,
                    'norm'       => round($norm, 4),
                    'weighted'   => round($weighted, 4),
                    'real_value' => $ev ? $ev->real_value : null,
                    'keuntungan' => $ev ? $ev->keuntungan : null,
                    'modal'      => $ev ? $ev->modal : null,
                ];

                $total += $weighted;
            }

            $results[] = [
                'customer' => $cust,
                'detail'   => $detail,
                'total'    => round($total, 4),
            ];
        }

        // --- 3. Sort descending ---
        usort($results, fn($a, $b) => $b['total'] <=> $a['total']);

        // --- 4. Rekomendasi proporsional terhadap pengajuan ---
        $maxScore = $results[0]['total'] ?? 1;

        foreach ($results as &$r) {
            $pengajuan = Evaluation::where('customer_id', $r['customer']->id)
                ->where('period_id', $selected)
                ->whereHas('criterion', function ($q) {
                    $q->where('name', 'like', '%pengajuan%');
                })
                ->value('real_value') ?? 0;

            $ratio = $maxScore > 0 ? $r['total'] / $maxScore : 0;

            $r['rekomendasi'] = round($ratio * $pengajuan);
        }

        return view('smart.index', compact('periods', 'criteria', 'results', 'selected', 'selectedPeriod'));
    }
}
