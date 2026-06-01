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
            'checked'     => 'nullable|array',
            'values'      => 'array',
            'keuntungan'  => 'array',
            'modal'       => 'array',
        ]);

        $period = Period::findOrFail($request->period_id);

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
                    // Hitung dari keuntungan & modal, ABAIKAN $val dari form
                    $clean = ($modal > 0) ? ($keuntungan / $modal) * 100 : 0;
                } else {
                    // Kriteria biasa: hapus titik ribuan saja
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
            ->with('success', 'Penilaian berhasil disimpan');
    }

    public function riwayat(Request $request)
    {
        $periods = Period::all();

        // Default ambil periode aktif, tapi tetap bisa pilih periode lain lewat request
        $selected = $request->period_id
            ?? Period::where('is_active', true)->value('id');

        $selectedPeriod = Period::find($selected);

        $criteria = Criterion::orderBy('id')->get();

        $evaluations = Evaluation::with(['customer', 'criterion'])
            ->where('period_id', $selected)
            ->get();

        $data = [];

        // GROUPING DATA PENILAIAN PER NASABAH
        foreach ($evaluations as $ev) {
            $data[$ev->customer_id]['customer'] = $ev->customer->name;

            $data[$ev->customer_id]['values'][$ev->criterion_id] = [
                'real_value' => $ev->real_value,
                'score'      => $ev->score,
                'keuntungan' => $ev->keuntungan,
                'modal'      => $ev->modal,
            ];
        }

        // BUILD RAW MATRIX
        // Yang dihitung SMART adalah SCORE hasil konversi parameter, bukan real value mentah
        $rawMatrix = [];

        foreach ($data as $customerId => $row) {
            foreach ($criteria as $criterion) {
                $rawMatrix[$criterion->id][$customerId] =
                    $row['values'][$criterion->id]['score'] ?? 0;
            }
        }

        // HITUNG SMART
        $results = [];

        foreach ($data as $customerId => $row) {

            $total = 0;

            foreach ($criteria as $criterion) {

                $raw = $rawMatrix[$criterion->id][$customerId] ?? 0;
                $columnValues = array_values($rawMatrix[$criterion->id] ?? []);

                $minVal = count($columnValues) > 0 ? min($columnValues) : 0;
                $maxVal = count($columnValues) > 0 ? max($columnValues) : 0;

                // Jika semua nilai sama, supaya tidak error pembagian 0
                if (($maxVal - $minVal) == 0) {
                    $utility = $raw > 0 ? 1 : 0;
                } else {
                    $utility = ($raw - $minVal) / ($maxVal - $minVal);
                }

                $weighted = $utility * $criterion->weight;

                $total += $weighted;
            }

            $results[] = [
                'customer_id' => $customerId,
                'customer'    => $row['customer'],
                'values'      => $row['values'],
                'smart_score' => round($total, 4),
            ];
        }

        // SORT RANKING DARI NILAI TERBESAR
        usort($results, function ($a, $b) {
            return $b['smart_score'] <=> $a['smart_score'];
        });

        // RANKING DAN REKOMENDASI PENCAIRAN
        $maxScore = $results[0]['smart_score'] ?? 1;

        foreach ($results as $index => &$r) {

            $r['ranking'] = $index + 1;

            // Ambil nilai pengajuan pinjaman
            $pengajuan = Evaluation::where('customer_id', $r['customer_id'])
                ->where('period_id', $selected)
                ->whereHas('criterion', function ($q) {
                    $q->where('name', 'like', '%pengajuan%');
                })
                ->value('real_value') ?? 0;

            // Rekomendasi pencairan proporsional berdasarkan nilai SMART
            $ratio = $maxScore > 0 ? $r['smart_score'] / $maxScore : 0;

            // $r['rekomendasi'] = round($ratio * $pengajuan);
            $r['rekomendasi'] = (int) round(($ratio * $pengajuan) / 100000) * 100000;
        }

        return view('penilaian.riwayat', compact(
            'periods',
            'selected',
            'selectedPeriod',
            'criteria',
            'results'
        ));
    }
}
