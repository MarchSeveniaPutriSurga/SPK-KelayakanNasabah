<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter sorting dari request
        $sort = $request->get('sort', 'name-asc');

        // Query dasar
        $query = Customer::query();

        // Apply sorting berdasarkan pilihan
        switch ($sort) {
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $customers = $query->get();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:customers,nik',
            'name' => 'required'
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Nasabah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:customers,nik' . $id,
            'name' => 'required'
        ]);

        Customer::findOrFail($id)->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Nasabah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->route('customers.index')->with('success', 'Nasabah berhasil dihapus.');
    }
}
