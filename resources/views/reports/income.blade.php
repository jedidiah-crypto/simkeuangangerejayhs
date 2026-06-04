<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#10b981;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Laporan</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Laporan Pemasukan</h1>
                <p style="font-size:0.8rem;color:#78716c;margin-top:0.2rem;">{{ \Carbon\Carbon::parse($from)->isoFormat('D MMMM Y') }} — {{ \Carbon\Carbon::parse($to)->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="glass-card animate-in" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('reports.income') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="font-size:0.75rem;color:#57534e;display:block;margin-bottom:0.35rem;font-weight:500;">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $from }}" style="background:#fefcf7;border:1px solid rgba(180,140,50,0.2);border-radius:10px;padding:0.5rem 0.85rem;color:#1a1200;font-size:0.85rem;outline:none;">
            </div>
            <div>
                <label style="font-size:0.75rem;color:#57534e;display:block;margin-bottom:0.35rem;font-weight:500;">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $to }}" style="background:#fefcf7;border:1px solid rgba(180,140,50,0.2);border-radius:10px;padding:0.5rem 0.85rem;color:#1a1200;font-size:0.85rem;outline:none;">
            </div>
            <button type="submit" class="btn-primary" style="font-size:0.85rem;">Tampilkan</button>
        </form>
    </div>

    @php
        $totalKeseluruhan = $data->sum('total');
    @endphp

    {{-- Summary Card --}}
    <div class="glass-card stat-emerald animate-in" style="margin-bottom:1.5rem;">
        <p style="font-size:0.72rem;font-weight:600;color:#1a7a4a;text-transform:uppercase;letter-spacing:0.08em;">Total Pemasukan</p>
        <p style="font-size:1.8rem;font-weight:800;color:#1a1200;margin-top:0.4rem;font-family:'Sora',sans-serif;">Rp {{ number_format($totalKeseluruhan,0,',','.') }}</p>
        <p style="font-size:0.75rem;color:#78716c;margin-top:0.5rem;">{{ $data->sum('count') }} transaksi dari {{ count($data) }} kategori</p>
    </div>

    {{-- Kategori Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;margin-bottom:1.5rem;" class="animate-in">
        @forelse($data as $kategori => $info)
            <div class="glass-card" style="padding:1.25rem;border-left:4px solid #34d399;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
                    <div>
                        <p style="font-size:0.8rem;color:#57534e;margin:0;">{{ $kategori }}</p>
                        <p style="font-size:1.3rem;font-weight:700;color:#34d399;margin:0.3rem 0;font-family:'Sora',sans-serif;">Rp {{ number_format($info['total'],0,',','.') }}</p>
                    </div>
                    <div style="background:rgba(52,211,153,0.1);padding:0.4rem 0.7rem;border-radius:8px;text-align:center;">
                        <p style="font-size:0.75rem;color:#34d399;font-weight:600;margin:0;">{{ $info['count'] }}</p>
                        <p style="font-size:0.65rem;color:#78716c;margin:0.1rem 0 0;">transaksi</p>
                    </div>
                </div>
                <div style="border-top:1px solid rgba(180,140,50,0.1);padding-top:0.75rem;">
                    <p style="font-size:0.7rem;color:#78716c;margin:0;">{{ $info['count'] }} transaksi · {{ number_format(($info['total']/$totalKeseluruhan)*100,1,',','.') }}% dari total</p>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem;">
                <p style="font-size:1.5rem;margin-bottom:0.5rem;">📭</p>
                <p style="color:#78716c;">Tidak ada data pemasukan pada periode ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Tabel Detail --}}
    <div class="glass-card animate-in" style="overflow:hidden;">
        <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);">
            <h3 style="color:#1a1200;font-size:0.9rem;font-weight:600;margin:0;">Detail Per Kategori</h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $kategori => $info)
                        <tr>
                            <td style="color:#1a1200;font-weight:600;">{{ $kategori }}</td>
                            <td style="color:#57534e;">{{ $info['count'] }}</td>
                            <td><span class="amount-in" style="font-family:'Sora',sans-serif;">+Rp {{ number_format($info['total'],0,',','.') }}</span></td>
                            <td><span style="background:rgba(52,211,153,0.15);color:#34d399;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ number_format(($info['total']/$totalKeseluruhan)*100,1,',','.') }}%</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:2rem;color:#78716c;">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($data) > 0)
                    <tfoot>
                        <tr>
                            <td style="padding:0.75rem 1rem;text-align:right;font-size:0.82rem;font-weight:600;color:#57534e;border-top:1px solid rgba(180,140,50,0.1);">Total</td>
                            <td style="border-top:1px solid rgba(180,140,50,0.1);padding:0.75rem 1rem;color:#57534e;font-weight:600;">{{ $data->sum('count') }}</td>
                            <td style="border-top:1px solid rgba(180,140,50,0.1);padding:0.75rem 1rem;">
                                <span class="amount-in" style="font-family:'Sora',sans-serif;font-size:0.9rem;font-weight:700;">+Rp {{ number_format($totalKeseluruhan,0,',','.') }}</span>
                            </td>
                            <td style="border-top:1px solid rgba(180,140,50,0.1);padding:0.75rem 1rem;"><span style="background:rgba(52,211,153,0.15);color:#34d399;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.78rem;font-weight:600;">100%</span></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</x-app-layout>
