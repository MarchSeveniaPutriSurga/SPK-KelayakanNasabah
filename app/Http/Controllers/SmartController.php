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
        $periods = Period::all();
        $criteria = Criterion::orderBy('id')->get();
        $results = []; // Inisialisasi dengan array kosong
        $selected = null;

        // Ambil periode aktif atau dari request
        $period = null;
        if ($request->has('period_id')) {
            $period = Period::find($request->period_id);
        } else {
            $period = Period::where('is_active', true)->first();
        }

        if (!$period) {
            return view('smart.index', compact('periods', 'criteria', 'results', 'selected'));
        }

        $selected = $period->id;

        // Ambil semua evaluasi untuk periode terpilih
        $evaluations = Evaluation::where('period_id', $selected)->get();

        if ($evaluations->isEmpty()) {
            return view('smart.index', compact('periods', 'criteria', 'results', 'selected'));
        }

        // Ambil customer unik dari evaluasi
        $customerIds = $evaluations->pluck('customer_id')->unique();
        $customers = Customer::whereIn('id', $customerIds)->get();

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
            $total = 0;

            foreach ($criteria as $c) {
                $raw = $rawMatrix[$c->id][$cust->id] ?? 0;
                $columnValues = array_values($rawMatrix[$c->id]);

                if ($c->type === 'benefit') {
                    $maxVal = max($columnValues);
                    $norm = $maxVal > 0 ? $raw / $maxVal : 0;
                } else { // cost
                    $minVal = min($columnValues);
                    $norm = $raw > 0 ? $minVal / $raw : 0;
                }

                $weighted = $norm * $c->weight;

                $detail[$c->id] = [
                    'raw' => $raw,
                    'norm' => round($norm, 4),
                    'weighted' => round($weighted, 4),
                ];

                $total += $weighted;
            }

            $results[] = [
                'customer' => $cust,
                'detail' => $detail,
                'total' => round($total, 4),
            ];
        }

        // --- 3. Sort descending ---
        usort($results, fn($a, $b) => $b['total'] <=> $a['total']);

        // --- 4. Tentukan status kelayakan berdasarkan quota_lolos ---
        $quota = $period->quota_lolos ?? null;

        if ($quota) {
            foreach ($results as $i => &$r) {
                $r['status'] = ($i + 1 <= $quota)
                    ? 'Layak Lanjut'
                    : 'Tidak Layak';
            }
        } else {
            foreach ($results as &$r) {
                $r['status'] = '-';
            }
        }

        return view('smart.index', compact('periods', 'criteria', 'results', 'selected'));
    }
}
