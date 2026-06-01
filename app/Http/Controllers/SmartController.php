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

        // Ambil periode aktif
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

        $data = $this->calculateSmartResults($period);

        return view('smart.index', [
            'periods'        => $periods,
            'criteria'       => $data['criteria'],
            'results'        => $data['results'],
            'selected'       => $selected,
            'selectedPeriod' => $selectedPeriod,
        ]);
    }

    private function calculateSmartResults($period): array
    {
        $criteria = Criterion::orderBy('id')->get();

        $evaluations = Evaluation::where('period_id', $period->id)->get();

        if ($evaluations->isEmpty()) {
            return [
                'criteria' => $criteria,
                'results'  => [],
            ];
        }

        $customerIds = $evaluations->pluck('customer_id')->unique();
        $customers   = Customer::whereIn('id', $customerIds)->get();

        // 1. BUILD MATRIX BERDASARKAN SCORE 1-5
        // Di sistem, score sudah hasil konversi parameter scoring.
        // Contoh:
        // - Cost: nilai kecil bisa dapat score tinggi.
        // - Benefit: nilai besar bisa dapat score tinggi.
        //
        // Jadi setelah masuk tahap SMART, score 1-5 dianggap:
        // "semakin besar semakin baik".
        $rawMatrix = [];

        foreach ($customers as $cust) {
            foreach ($criteria as $c) {
                $ev = $evaluations
                    ->where('customer_id', $cust->id)
                    ->where('criterion_id', $c->id)
                    ->first();

                $rawMatrix[$c->id][$cust->id] = $ev ? (float) $ev->score : 0;
            }
        }

        // =====================================================
        // 2. HITUNG UTILITY, WEIGHTED VALUE, TOTAL
        // =====================================================
        $results = [];

        foreach ($customers as $cust) {
            $detail = [];
            $total  = 0;

            foreach ($criteria as $c) {
                $ev = $evaluations
                    ->where('customer_id', $cust->id)
                    ->where('criterion_id', $c->id)
                    ->first();

                $raw = $rawMatrix[$c->id][$cust->id] ?? 0;

                $columnValues = array_values($rawMatrix[$c->id]);

                $maxVal = max($columnValues);
                $minVal = min($columnValues);

                // =====================================================
                // RUMUS UTILITY SMART
                // =====================================================
                // Karena score sudah dibuat 1-5 dan semakin besar semakin baik,
                // maka utility cukup pakai rumus benefit:
                //
                // u_i(a_i) = (Cout - Cmin) / (Cmax - Cmin)
                //
                // Jangan dibalik lagi berdasarkan cost,
                // karena cost/benefit sudah diatur di parameter scoring.
                if (($maxVal - $minVal) == 0) {
                    $utility = $raw > 0 ? 1 : 0;
                } else {
                    $utility = ($raw - $minVal) / ($maxVal - $minVal);
                }

                $weighted = $utility * $c->weight;

                $detail[$c->id] = [
                    'raw'        => $raw,
                    'norm'       => round($utility, 4),
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

        // =====================================================
        // 3. SORTING RANKING
        // =====================================================
        usort($results, fn($a, $b) => $b['total'] <=> $a['total']);

        // Tambahkan rank
        foreach ($results as $i => &$r) {
            $r['rank'] = $i + 1;
        }
        unset($r);

        // =====================================================
        // 4. HITUNG REKOMENDASI UANG CAIR
        // =====================================================
        $maxScore = $results[0]['total'] ?? 1;

        foreach ($results as &$r) {
            $pengajuan = Evaluation::where('customer_id', $r['customer']->id)
                ->where('period_id', $period->id)
                ->whereHas('criterion', function ($q) {
                    $q->where('name', 'like', '%pengajuan%');
                })
                ->value('real_value') ?? 0;

            $ratio = $maxScore > 0 ? $r['total'] / $maxScore : 0;

            // $r['rekomendasi'] = round($ratio * $pengajuan);
            $r['rekomendasi'] = (int) round(($ratio * $pengajuan) / 100000) * 100000;
        }
        unset($r);

        return [
            'criteria' => $criteria,
            'results'  => $results,
        ];
    }

    // =====================================================
    // HELPER UNTUK EXPORT EXCEL/PDF
    // =====================================================
    private function getResultsForActivePeriod(): array
    {
        $criteria = Criterion::orderBy('id')->get();
        $period   = Period::where('is_active', true)->first();

        if (!$period) {
            return [
                'period'   => null,
                'criteria' => $criteria,
                'results'  => [],
            ];
        }

        $data = $this->calculateSmartResults($period);

        return [
            'period'   => $period,
            'criteria' => $data['criteria'],
            'results'  => $data['results'],
        ];
    }

    // =====================================================
    // EXPORT EXCEL
    // =====================================================
    public function exportExcel()
    {
        ['period' => $period, 'criteria' => $criteria, 'results' => $results] =
            $this->getResultsForActivePeriod();

        $periodLabel = $period?->label ?? 'Tidak Diketahui';
        $filename    = 'SPK_SMART_' . str_replace(' ', '_', $periodLabel) . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil SPK SMART');

        // Info header
        $sheet->setCellValue('A1', 'Hasil Ranking SPK - SMART');
        $sheet->setCellValue('A2', 'Periode: ' . $periodLabel);
        $sheet->setCellValue('A3', 'Diekspor: ' . now()->format('d/m/Y H:i'));

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
        ]);

        // Header tabel
        $startRow = 5;

        $headers = ['Rank', 'Nama Nasabah'];

        foreach ($criteria as $c) {
            $headers[] = $c->code . ' - ' . $c->name;
        }

        $headers[] = 'Total Skor';
        $headers[] = 'Rekomendasi (Rp)';

        foreach ($headers as $colIdx => $value) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1) . $startRow;

            $sheet->setCellValue($cell, $value);

            $sheet->getStyle($cell)->applyFromArray([
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2a7a6e'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        // Data tabel
        foreach ($results as $i => $r) {
            $excelRow = $startRow + 1 + $i;

            $row = [
                $r['rank'] ?? ($i + 1),
                $r['customer']->name,
            ];

            foreach ($criteria as $c) {
                $d = $r['detail'][$c->id] ?? [];

                if (str_contains(strtolower($c->name), 'keuntungan')) {
                    $row[] = round($d['real_value'] ?? 0, 1) . '%';
                } else {
                    $row[] = $d['real_value'] ?? 0;
                }
            }

            $row[] = $r['total'];
            $row[] = ($r['rekomendasi'] ?? 0) > 0 ? $r['rekomendasi'] : 'Ditolak';

            foreach ($row as $colIdx => $value) {
                $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1) . $excelRow;
                $sheet->setCellValue($cell, $value);
            }

            // Warna baris selang-seling
            if ($i % 2 === 0) {
                $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($row));

                $sheet->getStyle("A{$excelRow}:{$lastCol}{$excelRow}")->applyFromArray([
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0FAF8'],
                    ],
                ]);
            }
        }

        // Auto width
        foreach (range(1, count($headers)) as $colIndex) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'spk_') . '.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // =====================================================
    // EXPORT PDF
    // =====================================================
    public function exportPdf()
    {
        ['period' => $period, 'criteria' => $criteria, 'results' => $results] =
            $this->getResultsForActivePeriod();

        $periodLabel = $period?->label ?? 'Tidak Diketahui';
        $filename    = 'SPK_SMART_' . str_replace(' ', '_', $periodLabel) . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('smart.export-pdf', [
            'criteria'    => $criteria,
            'results'     => $results,
            'periodLabel' => $periodLabel,
            'exportedAt'  => now()->format('d/m/Y H:i'),
        ])->setPaper('a3', 'landscape');

        return $pdf->download($filename);
    }
}
