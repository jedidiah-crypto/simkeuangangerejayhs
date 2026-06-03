<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donatur;

class DonaturController extends Controller
{
    public function index()
    {
        $items = Donatur::withCount('pemasukan')->paginate(15);
        return view('donatur.index', compact('items'));
    }

    public function create()
    {
        return view('donatur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150|unique:donaturs,nama',
            'email' => 'nullable|email|max:100',
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
        ]);

        Donatur::create($request->all());

        return redirect()->route('donatur.index')->with('success', 'Donatur berhasil ditambahkan');
    }

    public function show(Donatur $donatur)
    {
        $donatur->load('pemasukan');
        return view('donatur.show', compact('donatur'));
    }

    public function edit(Donatur $donatur)
    {
        return view('donatur.edit', compact('donatur'));
    }

    public function update(Request $request, Donatur $donatur)
    {
        $request->validate([
            'nama' => 'required|string|max:150|unique:donaturs,nama,'.$donatur->id,
            'email' => 'nullable|email|max:100',
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
        ]);

        $donatur->update($request->all());

        return redirect()->route('donatur.index')->with('success', 'Donatur berhasil diperbarui');
    }

    public function destroy(Donatur $donatur)
    {
        $donatur->delete();
        return redirect()->route('donatur.index')->with('success', 'Donatur berhasil dihapus');
    }
}
