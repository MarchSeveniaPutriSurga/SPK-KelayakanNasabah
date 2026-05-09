<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Models\Customer;
use App\Models\Evaluation;
use App\Models\Period;
use App\Models\ScoringParameter;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function create(Request $request)
    {
        $periods   = Period::all();
        $criteria  = Criterion::all();
        $customers = Customer::all();

        $selected = Period::where('is_active', true)->value('id');

        if (!$selected) {
            return view('penilaian.create', [
                'periods'        => $periods,
                'criteria'       => $criteria,
                'customers'      => $customers,
                'selected'       => null,
                'selectedPeriod' => null,
                'existingData'   => []
            ]);
        }

        $selectedPeriod = Period::find($selected);

        $evaluations = Evaluation::where('period_id', $selected)->get();

        $existingData = [];
        foreach ($evaluations as $ev) {
            $existingData[$ev->customer_id][$ev->criterion_id] = [
                'persen'     => $ev->real_value,
                'keuntungan' => $ev->keuntungan,
                'modal'      => $ev->modal,
            ];
        }

        return view('penilaian.create', [
            'periods'        => $periods,
            'criteria'       => $criteria,
            'customers'      => $customers,
            'selected'       => $selected,
            'selectedPeriod' => $selectedPeriod,
            'existingData'   => $existingData
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_id'   => 'required',
            'quota_lolos' => 'nullable|integer|min:1',
            'checked'     => 'nullable|array',
            'values'      => 'array',
            'keuntungan'  => 'array',
            'modal'       => 'array',
        ]);

        $period = Period::findOrFail($request->period_id);

        if ($request->filled('quota_lolos')) {
            $period->update(['quota_lolos' => $request->quota_lolos]);
        }

        $checkedCustomers = $request->checked ?? [];

        $existingCustomerIds = Evaluation::where('period_id', $period->id)
            ->pluck('customer_id')
            ->unique()
            ->toArray();

        $uncheckedCustomers = array_diff($existingCustomerIds, $checkedCustomers);

        if (!empty($uncheckedCustomers)) {
            Evaluation::where('period_id', $period->id)
                ->whereIn('customer_id', $uncheckedCustomers)
                ->delete();
        }

        foreach ($checkedCustomers as $customer_id) {
            if (!isset($request->values[$customer_id])) continue;

            // Parse keuntungan & modal: hapus titik ribuan → float
            $keuntungan = floatval(str_replace('.', '', $request->keuntungan[$customer_id] ?? 0));
            $modal      = floatval(str_replace('.', '', $request->modal[$customer_id] ?? 0));

            foreach ($request->values[$customer_id] as $criterion_id => $val) {

                $criterion = Criterion::find($criterion_id);

                if (str_contains(strtolower($criterion->name), 'keuntungan')) {
                    // ✅ Hitung dari keuntungan & modal, ABAIKAN $val dari form
                    $clean = ($modal > 0) ? ($keuntungan / $modal) * 100 : 0;
                } else {
                    // ✅ Kriteria biasa: hapus titik ribuan saja
                    $clean = floatval(str_replace('.', '', $val));
                }

                $param = ScoringParameter::where('criterion_id', $criterion_id)
                    ->where('min_value', '<=', $clean)
                    ->where('max_value', '>=', $clean)
                    ->first();

                $score = $param ? $param->score : 1;

                Evaluation::updateOrCreate(
                    [
                        'period_id'    => $period->id,
                        'customer_id'  => $customer_id,
                        'criterion_id' => $criterion_id,
                    ],
                    [
                        'real_value' => $clean,
                        'score'      => $score,
                        'keuntungan' => $keuntungan,
                        'modal'      => $modal,
                    ]
                );
            }
        }

        return redirect()
            ->route('smart.index', ['period_id' => $period->id])
            ->with('success', 'Penilaian & quota lolos berhasil disimpan');
    }
}
