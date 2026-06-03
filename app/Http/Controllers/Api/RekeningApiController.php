<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use Illuminate\Http\Request;

/**
 * RekeningApiController
 * CRUD rekening bank / kas gereja.
 */
class RekeningApiController extends Controller
{
    // ─── GET /api/v1/rekening ────────────────────────────────────────────────
    public function index()
    {
        $items = Rekening::orderBy('nama')->get()->map(fn($r) => $this->format($r));

        return response()->json(['status' => true, 'data' => $items]);
    }

    // ─── POST /api/v1/rekening ───────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'           => 'required|string|max:150',
            'nomor_rekening' => 'nullable|string|max:50',
            'bank'           => 'nullable|string|max:100',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        $rekening = Rekening::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Rekening berhasil ditambahkan',
            'data'    => $this->format($rekening),
        ], 201);
    }

    // ─── PUT /api/v1/rekening/{id} ───────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $rekening = Rekening::findOrFail($id);

        $data = $request->validate([
            'nama'           => 'sometimes|string|max:150',
            'nomor_rekening' => 'nullable|string|max:50',
            'bank'           => 'nullable|string|max:100',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        $rekening->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Rekening diperbarui',
            'data'    => $this->format($rekening->fresh()),
        ]);
    }

    // ─── DELETE /api/v1/rekening/{id} ────────────────────────────────────────
    public function destroy($id)
    {
        $rekening = Rekening::withCount(['pemasukan', 'pengeluaran'])->findOrFail($id);

        if ($rekening->pemasukan_count > 0 || $rekening->pengeluaran_count > 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Rekening tidak dapat dihapus karena masih digunakan dalam transaksi.',
            ], 422);
        }

        $rekening->delete();

        return response()->json(['status' => true, 'message' => 'Rekening berhasil dihapus']);
    }

    private function format(Rekening $r): array
    {
        return [
            'id'             => $r->id,
            'nama'           => $r->nama,
            'nomor_rekening' => $r->nomor_rekening,
            'bank'           => $r->bank,
            'keterangan'     => $r->keterangan,
            'created_at'     => $r->created_at?->toDateTimeString(),
        ];
    }
}
