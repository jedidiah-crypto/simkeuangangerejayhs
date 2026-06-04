<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#10b981;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Manajemen Dana</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Daftar Pemasukan</h1>
                <p style="font-size:0.8rem;color:#78716c;margin-top:0.2rem;">Kelola dan pantau seluruh arus masuk keuangan gereja</p>
            </div>
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                <a href="{{ route('reports.import.form') }}" class="btn-ghost" style="font-size:0.8rem;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Excel
                </a>
                <a href="{{ route('pemasukan.create') }}" class="btn-success" style="font-size:0.8rem;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pemasukan
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="glass-card animate-in" style="padding:1rem 1.5rem;margin-bottom:1rem;">
        <form method="GET" action="{{ route('pemasukan.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="font-size:0.73rem;color:#57534e;display:block;margin-bottom:0.3rem;font-weight:500;">Cari Keterangan</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari keterangan..."
                    style="background:#fefcf7;border:1px solid rgba(180,140,50,0.2);border-radius:10px;padding:0.45rem 0.85rem;color:#1a1200;font-size:0.82rem;outline:none;width:190px;">
            </div>
            <div>
                <label style="font-size:0.73rem;color:#57534e;display:block;margin-bottom:0.3rem;font-weight:500;">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    style="background:#fefcf7;border:1px solid rgba(180,140,50,0.2);border-radius:10px;padding:0.45rem 0.85rem;color:#1a1200;font-size:0.82rem;outline:none;">
            </div>
            <div>
                <label style="font-size:0.73rem;color:#57534e;display:block;margin-bottom:0.3rem;font-weight:500;">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    style="background:#fefcf7;border:1px solid rgba(180,140,50,0.2);border-radius:10px;padding:0.45rem 0.85rem;color:#1a1200;font-size:0.82rem;outline:none;">
            </div>
            <button type="submit" class="btn-primary" style="font-size:0.82rem;">Filter</button>
            @if(request()->hasAny(['q','from','to']))
                <a href="{{ route('pemasukan.index') }}" class="btn-ghost" style="font-size:0.82rem;">Reset</a>
            @endif
        </form>
    </div>

    <div class="glass-card animate-in" style="overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <div style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 8px rgba(16,185,129,0.6);"></div>
                <span style="font-size:0.8rem;color:#57534e;">{{ $items->total() ?? 0 }} transaksi ditemukan</span>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nomor Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Kategori</th>
                        <th>Sumber Dana</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <span style="font-family:'Sora',sans-serif;font-size:0.8rem;font-weight:600;color:#38bdf8;background:rgba(56,189,248,0.08);padding:0.25rem 0.6rem;border-radius:8px;border:1px solid rgba(56,189,248,0.15);">
                                    {{ $item->nomor_transaksi }}
                                </span>
                            </td>
                            <td style="color:#57534e;font-size:0.83rem;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td><span class="amount-in" style="font-family:'Sora',sans-serif;">+Rp {{ number_format($item->nominal,0,',','.') }}</span></td>
                            <td>
                                <span style="font-size:0.78rem;color:#57534e;background:rgba(180,140,50,0.06);padding:0.2rem 0.6rem;border-radius:6px;border:1px solid rgba(180,140,50,0.12);">
                                    {{ $item->kategori->nama ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#57534e;font-size:0.83rem;">{{ $item->sumber_dana ?? '-' }}</td>
                            <td>
                                @if($item->bukti)
                                    <a href="{{ asset('storage/'.$item->bukti) }}" target="_blank" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.78rem;color:#38bdf8;text-decoration:none;background:rgba(56,189,248,0.08);padding:0.25rem 0.6rem;border-radius:8px;border:1px solid rgba(56,189,248,0.15);transition:all 0.15s;" onmouseover="this.style.background='rgba(56,189,248,0.15)'" onmouseout="this.style.background='rgba(56,189,248,0.08)'">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat
                                    </a>
                                @else
                                    <span style="font-size:0.78rem;color:#6b7280;">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                    <a href="{{ route('pemasukan.edit', $item) }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#fbbf24;text-decoration:none;background:rgba(251,191,36,0.08);padding:0.3rem 0.6rem;border-radius:6px;border:1px solid rgba(251,191,36,0.2);transition:all 0.15s;" onmouseover="this.style.background='rgba(251,191,36,0.15)'" onmouseout="this.style.background='rgba(251,191,36,0.08)'">
                                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('pemasukan.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pemasukan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#fb7185;text-decoration:none;background:rgba(251,113,133,0.08);padding:0.3rem 0.6rem;border-radius:6px;border:1px solid rgba(251,113,133,0.2);transition:all 0.15s;cursor:pointer;font-family:inherit;" onmouseover="this.style.background='rgba(251,113,133,0.15)'" onmouseout="this.style.background='rgba(251,113,133,0.08)'">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:3rem;color:#78716c;">
                                <div style="font-size:2rem;margin-bottom:0.5rem;">📭</div>
                                <p>Belum ada data pemasukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">{{ $items->appends(request()->query())->links() }}</div>
        @endif
    </div>
</x-app-layout>
