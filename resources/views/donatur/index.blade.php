<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#8b5cf6;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Data Master</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Daftar Donatur</h1>
                <p style="font-size:0.8rem;color:#78716c;margin-top:0.2rem;">Kontributor dan mitra keuangan gereja</p>
            </div>
            <a href="{{ route('donatur.create') }}" class="btn-primary" style="font-size:0.8rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Donatur
            </a>
        </div>
    </x-slot>

    <div class="glass-card animate-in" style="overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.5rem;">
            <div style="width:8px;height:8px;border-radius:50%;background:#8b5cf6;box-shadow:0 0 8px rgba(139,92,246,0.6);"></div>
            <span style="font-size:0.8rem;color:#57534e;">{{ $items->total() ?? 0 }} donatur terdaftar</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Donatur</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Total Kontribusi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.75rem;">
                                    <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,rgba(139,92,246,0.2),rgba(109,40,217,0.1));border:1px solid rgba(139,92,246,0.25);display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;color:#a78bfa;flex-shrink:0;">
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                    <span style="font-weight:600;font-size:0.875rem;color:#1a1200;">{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td style="color:#57534e;font-size:0.83rem;">{{ $item->email ?? '—' }}</td>
                            <td style="color:#57534e;font-size:0.83rem;">{{ $item->telepon ?? '—' }}</td>
                            <td>
                                <span class="amount-in" style="font-family:'Sora',sans-serif;font-size:0.85rem;">
                                    Rp {{ number_format($item->pemasukan_count * 1, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                    <a href="{{ route('donatur.show', $item) }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#a78bfa;text-decoration:none;background:rgba(139,92,246,0.08);padding:0.3rem 0.75rem;border-radius:8px;border:1px solid rgba(139,92,246,0.2);transition:all 0.15s;" onmouseover="this.style.background='rgba(139,92,246,0.18)'" onmouseout="this.style.background='rgba(139,92,246,0.08)'">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        Lihat
                                    </a>
                                    <a href="{{ route('donatur.edit', $item) }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#fbbf24;text-decoration:none;background:rgba(251,191,36,0.08);padding:0.3rem 0.75rem;border-radius:8px;border:1px solid rgba(251,191,36,0.2);transition:all 0.15s;" onmouseover="this.style.background='rgba(251,191,36,0.15)'" onmouseout="this.style.background='rgba(251,191,36,0.08)'">
                                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('donatur.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus donatur ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#fb7185;text-decoration:none;background:rgba(251,113,133,0.08);padding:0.3rem 0.75rem;border-radius:8px;border:1px solid rgba(251,113,133,0.2);transition:all 0.15s;cursor:pointer;font-family:inherit;" onmouseover="this.style.background='rgba(251,113,133,0.15)'" onmouseout="this.style.background='rgba(251,113,133,0.08)'">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:3rem;color:#78716c;">
                                <div style="font-size:2rem;margin-bottom:0.5rem;">👥</div>
                                <p>Belum ada donatur terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);">{{ $items->links() }}</div>
        @endif
    </div>
</x-app-layout>
