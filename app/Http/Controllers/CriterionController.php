<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Models\Period;        // <- TAMBAHKAN INI
use App\Models\Evaluation;
use Illuminate\Http\Request;

class CriterionController extends Controller
{
    public function index()
    {
        $criteria = Criterion::all();
        return view('criteria.index', compact('criteria'));
    }

    public function create()
    {
        return view('criteria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:criteria',
            'name' => 'required',
            'type' => 'required',
            'weight' => 'required|numeric'
        ]);

        Criterion::create($request->all());

        return redirect()->route('criteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $criterion = Criterion::findOrFail($id);
        return view('criteria.edit', compact('criterion'));
    }

    // public function update(Request $request, $id)
    // {
    //     $criterion = Criterion::findOrFail($id);

    //     $request->validate([
    //         'name' => 'required',
    //         'type' => 'required',
    //         'weight' => 'required|numeric'
    //     ]);

    //     $criterion->update([
    //         'name' => $request->name,
    //         'type' => $request->type,
    //         'weight' => $request->weight
    //     ]);

    //     return redirect()->route('criteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        $criterion = Criterion::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'weight' => 'required|numeric'
        ]);

        // CEK: ada periode aktif yang sudah punya evaluasi?
        $activePeriod = Period::where('is_active', true)->first();

        if ($activePeriod) {
            $hasEvaluations = Evaluation::where('period_id', $activePeriod->id)
                ->where('criterion_id', $id)
                ->exists();

            if ($hasEvaluations) {
                return redirect()->route('criteria.index')
                    ->with('error', 'Tidak bisa ubah bobot! Periode aktif sudah ada penilaian. Nonaktifkan periode dulu atau buat periode baru.');
            }
        }

        $criterion->update([
            'name' => $request->name,
            'type' => $request->type,
            'weight' => $request->weight
        ]);

        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $criterion = Criterion::findOrFail($id);

        $isUsed = \App\Models\Evaluation::where('criterion_id', $id)->exists();

        if ($isUsed) {
            return redirect()->route('criteria.index')
                ->with('error', 'Kriteria tidak dapat dihapus karena sudah digunakan dalam penilaian!');
        }

        $criterion->delete();
        return redirect()->route('criteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
