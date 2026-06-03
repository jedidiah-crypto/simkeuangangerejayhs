<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#8b5cf6;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Data Master</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#e2e8f0;margin:0;">Daftar Donatur</h1>
                <p style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;">Kontributor dan mitra keuangan gereja</p>
            </div>
        </div>
    </x-slot>

    <div class="glass-card animate-in" style="overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.5rem;">
            <div style="width:8px;height:8px;border-radius:50%;background:#8b5cf6;box-shadow:0 0 8px rgba(139,92,246,0.6);"></div>
            <span style="font-size:0.8rem;color:#94a3b8;">{{ $items->total() ?? 0 }} donatur terdaftar</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Donatur</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Total Kontribusi</th>
                        <th></th>
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
                                    <span style="font-weight:600;font-size:0.875rem;color:#e2e8f0;">{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td style="color:#94a3b8;font-size:0.83rem;">{{ $item->email ?? '—' }}</td>
                            <td style="color:#94a3b8;font-size:0.83rem;">{{ $item->telepon ?? '—' }}</td>
                            <td>
                                <span class="amount-in" style="font-family:'Sora',sans-serif;font-size:0.85rem;">
                                    Rp {{ number_format($item->pemasukan_count * 1, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('donatur.show', $item) }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.78rem;color:#a78bfa;text-decoration:none;background:rgba(139,92,246,0.08);padding:0.3rem 0.75rem;border-radius:8px;border:1px solid rgba(139,92,246,0.2);transition:all 0.15s;" onmouseover="this.style.background='rgba(139,92,246,0.18)'" onmouseout="this.style.background='rgba(139,92,246,0.08)'">
                                    Detail
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:3rem;color:#64748b;">
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
