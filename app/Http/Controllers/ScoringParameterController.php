<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Models\ScoringParameter;
use Illuminate\Http\Request;

class ScoringParameterController extends Controller
{
    public function index()
    {
        $parameters = ScoringParameter::with('criterion')->orderBy('criterion_id')->get();
        return view('parameters.index', compact('parameters'));
    }

    public function create()
    {
        $criteria = Criterion::all();
        return view('parameters.create', compact('criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'criterion_id' => 'required',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric',
            'score' => 'required|numeric|min:1|max:5'
        ]);

        ScoringParameter::create($request->all());

        return redirect()->route('parameters.index')->with('success', 'Parameter berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $parameter = ScoringParameter::findOrFail($id);

        $isUsed = \App\Models\Evaluation::where('criterion_id', $parameter->criterion_id)
            ->whereBetween('real_value', [$parameter->min_value, $parameter->max_value])
            ->exists();

        if ($isUsed) {
            return redirect()->route('parameters.index')
                ->with('error', 'Parameter tidak dapat diedit karena sudah digunakan dalam penilaian.');
        }

        $criteria = Criterion::all();
        return view('parameters.edit', compact('parameter', 'criteria'));
    }

    public function update(Request $request, $id)
    {
        $parameter = ScoringParameter::findOrFail($id);

        $isUsed = \App\Models\Evaluation::where('criterion_id', $parameter->criterion_id)
            ->whereBetween('real_value', [$parameter->min_value, $parameter->max_value])
            ->exists();

        if ($isUsed) {
            return redirect()->route('parameters.index')
                ->with('error', 'Parameter tidak dapat diperbarui karena sudah digunakan dalam penilaian.');
        }

        $request->validate([
            'criterion_id' => 'required',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric',
            'score' => 'required|numeric|min:1|max:5'
        ]);

        $parameter->update($request->all());

        return redirect()->route('parameters.index')->with('success', 'Parameter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $parameter = ScoringParameter::findOrFail($id);
        $isUsed = \App\Models\Evaluation::where('criterion_id', $parameter->criterion_id)
            ->whereBetween('real_value', [$parameter->min_value, $parameter->max_value])
            ->exists();

        if ($isUsed) {
            return redirect()->route('parameters.index')
                ->with('error', 'Parameter tidak dapat dihapus karena sedang digunakan dalam penilaian!');
        }

        $parameter->delete();
        return redirect()->route('parameters.index')
            ->with('success', 'Parameter berhasil dihapus.');
    }
}
