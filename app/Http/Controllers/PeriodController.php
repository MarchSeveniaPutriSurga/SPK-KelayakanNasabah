<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::withCount('evaluations')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('periods.index', compact('periods'));
    }

    public function create()
    {
        return view('periods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month'        => 'required|numeric|min:1|max:12',
            'year'         => 'required|numeric|min:2000',
            'quota_lolos'  => 'nullable|integer|min:1'
        ]);

        $label = \DateTime::createFromFormat('!m', $request->month)
            ->format('F') . ' ' . $request->year;

        Period::create([
            'month'        => $request->month,
            'year'         => $request->year,
            'label'        => $label,
            'quota_lolos'  => $request->quota_lolos
        ]);

        return redirect()
            ->route('periods.index')
            ->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $period = Period::findOrFail($id);

        if ($period->evaluations()->exists()) {
            return redirect()->route('periods.index')
                ->with('error', 'Periode ini tidak dapat diedit karena sudah memiliki penilaian.');
        }

        return view('periods.edit', compact('period'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'month'        => 'required|numeric|min:1|max:12',
            'year'         => 'required|numeric|min:2000',
            'quota_lolos'  => 'nullable|integer|min:1'
        ]);

        $p = Period::findOrFail($id);

        if ($p->evaluations()->exists()) {
            return redirect()->route('periods.index')
                ->with('error', 'Periode ini tidak dapat diedit karena sudah memiliki penilaian.');
        }

        $label = \DateTime::createFromFormat('!m', $request->month)
            ->format('F') . ' ' . $request->year;

        $p->update([
            'month'        => $request->month,
            'year'         => $request->year,
            'label'        => $label,
            'quota_lolos'  => $request->quota_lolos
        ]);

        return redirect()
            ->route('periods.index')
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $period = Period::findOrFail($id);

        if ($period->evaluations()->exists()) {
            return redirect()->route('periods.index')
                ->with('error', 'Periode ini tidak dapat dihapus karena sudah memiliki penilaian.');
        }

        $period->delete();

        return redirect()
            ->route('periods.index')
            ->with('success', 'Periode berhasil dihapus.');
    }

    public function setActive($id)
    {
        // matikan semua periode
        Period::query()->update(['is_active' => false]);

        // aktifkan periode terpilih
        Period::where('id', $id)->update(['is_active' => true]);

        return back()->with('success', 'Periode berhasil diaktifkan!');
    }
}
