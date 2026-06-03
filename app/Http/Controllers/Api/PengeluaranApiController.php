<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\KategoriKeuangan;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * PengeluaranApiController
 * CRUD pengeluaran + approval + upload nota untuk Flutter.
 */
class PengeluaranApiController extends Controller
{
    // ─── GET /api/v1/pengeluaran ─────────────────────────────────────────────
    // Query params: q, from, to, kategori_id, status (pending|approved), per_page
    public function index(Request $request)
    {
        $query = Pengeluaran::with(['kategori', 'rekening']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('keterangan', 'like', "%$q%")
                    ->orWhere('nomor_transaksi', 'like', "%$q%");
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tanggal', [$request->from, $request->to]);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $items   = $query->latest('tanggal')->paginate($perPage);

        return response()->json([
            'status' => true,
            'data'   => $items->map(fn($i) => $this->format($i)),
            'meta'   => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    // ─── GET /api/v1/pengeluaran/{id} ────────────────────────────────────────
    public function show($id)
    {
        $item = Pengeluaran::with(['kategori', 'rekening'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => $this->format($item),
        ]);
    }

    // ─── POST /api/v1/pengeluaran ────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'     => 'required|date',
            'nominal'     => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategori_keuangan,id',
            'rekening_id' => 'nullable|exists:rekenings,id',
            'metode'      => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string|max:1000',
        ]);

        $data['nomor_transaksi'] = 'PG' . now()->format('Ymd') . Str::upper(Str::random(6));
        $data['created_by']      = $request->user()->id;
        $data['status']          = 'pending';

        $item = Pengeluaran::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Pengeluaran berhasil disimpan',
            'data'    => $this->format($item->load(['kategori', 'rekening'])),
        ], 201);
    }

    // ─── PUT /api/v1/pengeluaran/{id} ────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $item = Pengeluaran::findOrFail($id);

        // Hanya boleh edit pengeluaran yang masih pending
        if ($item->status === 'approved') {
            return response()->json([
                'status'  => false,
                'message' => 'Pengeluaran yang sudah disetujui tidak dapat diubah.',
            ], 403);
        }

        $data = $request->validate([
            'tanggal'     => 'sometimes|date',
            'nominal'     => 'sometimes|numeric|min:0',
            'kategori_id' => 'sometimes|exists:kategori_keuangan,id',
            'rekening_id' => 'nullable|exists:rekenings,id',
            'metode'      => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string|max:1000',
        ]);

        $item->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Pengeluaran berhasil diperbarui',
            'data'    => $this->format($item->fresh(['kategori', 'rekening'])),
        ]);
    }

    // ─── DELETE /api/v1/pengeluaran/{id} ─────────────────────────────────────
    public function destroy($id)
    {
        $item = Pengeluaran::findOrFail($id);

        if ($item->status === 'approved') {
            return response()->json([
                'status'  => false,
                'message' => 'Pengeluaran yang sudah disetujui tidak dapat dihapus.',
            ], 403);
        }

        if ($item->nota) {
            Storage::disk('public')->delete($item->nota);
        }

        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Pengeluaran berhasil dihapus',
        ]);
    }

    // ─── POST /api/v1/pengeluaran/{id}/approve ───────────────────────────────
    public function approve(Request $request, $id)
    {
        $item = Pengeluaran::findOrFail($id);

        if ($item->status === 'approved') {
            return response()->json([
                'status'  => false,
                'message' => 'Pengeluaran sudah disetujui sebelumnya.',
            ], 422);
        }

        $item->update([
            'status'      => 'approved',
            'approved_by' => $request->user()->id,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pengeluaran berhasil disetujui',
            'data'    => $this->format($item->fresh(['kategori', 'rekening'])),
        ]);
    }

    // ─── POST /api/v1/pengeluaran/{id}/nota ──────────────────────────────────
    public function uploadNota(Request $request, $id)
    {
        $item = Pengeluaran::findOrFail($id);

        $request->validate([
            'nota' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($item->nota) {
            Storage::disk('public')->delete($item->nota);
        }

        $path = $request->file('nota')->store('pengeluaran/nota', 'public');
        $item->update(['nota' => $path]);

        return response()->json([
            'status'  => true,
            'message' => 'Nota berhasil diunggah',
            'data'    => ['nota_url' => asset('storage/' . $path)],
        ]);
    }

    // ─── Helper ─────────────────────────────────────────────────────────────
    private function format(Pengeluaran $item): array
    {
        return [
            'id'              => $item->id,
            'nomor_transaksi' => $item->nomor_transaksi,
            'tanggal'         => $item->tanggal,
            'nominal'         => (int) $item->nominal,
            'metode'          => $item->metode,
            'keterangan'      => $item->keterangan,
            'status'          => $item->status,
            'nota_url'        => $item->nota ? asset('storage/' . $item->nota) : null,
            'kategori'        => $item->kategori ? [
                'id'   => $item->kategori->id,
                'nama' => $item->kategori->nama,
            ] : null,
            'rekening'        => $item->rekening ? [
                'id'   => $item->rekening->id,
                'nama' => $item->rekening->nama,
            ] : null,
            'approved_by'     => $item->approved_by,
            'created_at'      => $item->created_at?->toDateTimeString(),
            'updated_at'      => $item->updated_at?->toDateTimeString(),
        ];
    }
}
