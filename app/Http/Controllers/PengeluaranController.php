<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\KategoriKeuangan;
use App\Models\Rekening;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with(['kategori', 'rekening']);

        if ($request->filled('q')) {
            $query->where('keterangan', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tanggal', [$request->from, $request->to]);
        }
        // BARU: filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items   = $query->latest()->paginate(15);
        $kategori = KategoriKeuangan::where('type', 'pengeluaran')->get();

        return view('pengeluaran.index', compact('items', 'kategori'));
    }

    public function create()
    {
        $kategori = KategoriKeuangan::where('type', 'pengeluaran')->get();
        $rekening = Rekening::all();

        return view('pengeluaran.create', compact('kategori', 'rekening'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'     => 'required|date',
            'nominal'     => 'required|numeric',
            'kategori_id' => 'required|exists:kategori_keuangan,id',
            'rekening_id' => 'nullable|exists:rekenings,id',
            'metode'      => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string|max:1000',
            'nota'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($request->hasFile('nota')) {
            $data['nota'] = $request->file('nota')->store('pengeluaran/nota', 'public');
        }

        $data['nomor_transaksi'] = 'PG' . now()->format('Ymd') . Str::upper(Str::random(6));
        $data['created_by']      = $request->user()->id;
        $data['status']          = 'pending'; // selalu mulai dari pending

        Pengeluaran::create($data);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran tersimpan, menunggu persetujuan pendeta.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        // Hanya boleh edit jika masih pending
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Pengeluaran yang sudah diproses tidak dapat diubah.');
        }

        $kategori = KategoriKeuangan::where('type', 'pengeluaran')->get();
        $rekening = Rekening::all();

        return view('pengeluaran.edit', compact('pengeluaran', 'kategori', 'rekening'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Pengeluaran yang sudah diproses tidak dapat diubah.');
        }

        $data = $request->validate([
            'tanggal'     => 'required|date',
            'nominal'     => 'required|numeric',
            'kategori_id' => 'required|exists:kategori_keuangan,id',
            'rekening_id' => 'nullable|exists:rekenings,id',
            'metode'      => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string|max:1000',
            'nota'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($request->hasFile('nota')) {
            if ($pengeluaran->nota) {
                Storage::disk('public')->delete($pengeluaran->nota);
            }
            $data['nota'] = $request->file('nota')->store('pengeluaran/nota', 'public');
        }

        $pengeluaran->update($data);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->status === 'approved') {
            return back()->with('error', 'Pengeluaran yang sudah disetujui tidak dapat dihapus.');
        }

        if ($pengeluaran->nota) {
            Storage::disk('public')->delete($pengeluaran->nota);
        }

        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran dihapus.');
    }

    /**
     * Approve pengeluaran — hanya pendeta (dijaga di route middleware)
     */
    public function approve(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Hanya pengeluaran berstatus pending yang dapat disetujui.');
        }

        $pengeluaran->update([
            'status'      => 'approved',
            'approved_by' => request()->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Pengeluaran ' . $pengeluaran->nomor_transaksi . ' telah disetujui.');
    }

    /**
     * Reject pengeluaran — hanya pendeta (dijaga di route middleware)
     */
    public function reject(Request $request, Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->status !== 'pending') {
            return back()->with('error', 'Hanya pengeluaran berstatus pending yang dapat ditolak.');
        }

        $pengeluaran->update([
            'status'          => 'rejected',
            'approved_by'     => request()->user()->id,
            'approved_at'     => now(),
            'catatan_reject'  => $request->input('catatan_reject'),
        ]);

        return back()->with('success', 'Pengeluaran ' . $pengeluaran->nomor_transaksi . ' telah ditolak.');
    }
}
