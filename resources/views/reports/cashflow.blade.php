<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#7c3aed;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Laporan</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Laporan Arus Kas</h1>
                <p style="font-size:0.8rem;color:#78716c;margin-top:0.2rem;">{{ \Carbon\Carbon::parse($from)->isoFormat('D MMMM Y') }} — {{ \Carbon\Carbon::parse($to)->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="glass-card animate-in" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('reports.cashflow') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
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
        $pemasukanTotal = $pemasukan;
        $pengeluaranTotal = $pengeluaran;
        $selisih = $pemasukanTotal - $pengeluaranTotal;
    @endphp

    {{-- Flow Chart --}}
    <div class="glass-card animate-in" style="padding:1.5rem;margin-bottom:1.5rem;overflow:hidden;">
        <div style="display:grid;grid-template-columns:1fr auto 1fr auto 1fr;gap:1rem;align-items:center;">
            {{-- Saldo Awal --}}
            <div style="text-align:center;">
                <p style="font-size:0.75rem;color:#57534e;margin:0;">Saldo Awal</p>
                <p style="font-size:1.4rem;font-weight:700;color:#38bdf8;margin:0.5rem 0;font-family:'Sora',sans-serif;">Rp {{ number_format($saldoAwal,0,',','.') }}</p>
            </div>

            <div style="text-align:center;">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0 auto;color:#57534e;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>

            {{-- Pemasukan --}}
            <div style="background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.2);border-radius:12px;padding:1rem;text-align:center;">
                <p style="font-size:0.75rem;color:#78716c;margin:0;">Pemasukan</p>
                <p style="font-size:1.4rem;font-weight:700;color:#34d399;margin:0.5rem 0;font-family:'Sora',sans-serif;">+Rp {{ number_format($pemasukanTotal,0,',','.') }}</p>
            </div>

            <div style="text-align:center;">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0 auto;color:#57534e;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>

            {{-- Pengeluaran --}}
            <div style="background:rgba(251,113,133,0.08);border:1px solid rgba(251,113,133,0.2);border-radius:12px;padding:1rem;text-align:center;">
                <p style="font-size:0.75rem;color:#78716c;margin:0;">Pengeluaran</p>
                <p style="font-size:1.4rem;font-weight:700;color:#fb7185;margin:0.5rem 0;font-family:'Sora',sans-serif;">-Rp {{ number_format($pengeluaranTotal,0,',','.') }}</p>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;" class="animate-in">
        <div class="stat-card stat-emerald">
            <p style="font-size:0.72rem;font-weight:600;color:#1a7a4a;text-transform:uppercase;letter-spacing:0.08em;">Total Pemasukan</p>
            <p style="font-size:1.5rem;font-weight:800;color:#1a1200;margin-top:0.4rem;font-family:'Sora',sans-serif;">+Rp {{ number_format($pemasukanTotal,0,',','.') }}</p>
        </div>
        <div class="stat-card stat-rose">
            <p style="font-size:0.72rem;font-weight:600;color:#b91c1c;text-transform:uppercase;letter-spacing:0.08em;">Total Pengeluaran</p>
            <p style="font-size:1.5rem;font-weight:800;color:#1a1200;margin-top:0.4rem;font-family:'Sora',sans-serif;">-Rp {{ number_format($pengeluaranTotal,0,',','.') }}</p>
        </div>
        <div class="stat-card {{ $selisih >= 0 ? 'stat-violet' : 'stat-rose' }}">
            <p style="font-size:0.72rem;font-weight:600;{{ $selisih >= 0 ? 'color:#a78bfa' : 'color:#fb7185' }};text-transform:uppercase;letter-spacing:0.08em;">Saldo Akhir</p>
            <p style="font-size:1.5rem;font-weight:800;color:{{ $selisih >= 0 ? '#34d399' : '#fb7185' }};margin-top:0.4rem;font-family:'Sora',sans-serif;">
                {{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih),0,',','.') }}
            </p>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="glass-card animate-in" style="overflow:hidden;">
        <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);">
            <h3 style="color:#1a1200;font-size:0.9rem;font-weight:600;margin:0;">Ringkasan Arus Kas</h3>
        </div>
        <div style="padding:1.5rem;">
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="padding:1rem;color:#57534e;border-bottom:1px solid rgba(180,140,50,0.1);">Saldo Awal</td>
                    <td style="padding:1rem;text-align:right;color:#1a1200;border-bottom:1px solid rgba(180,140,50,0.1);font-weight:600;">Rp {{ number_format($saldoAwal,0,',','.') }}</td>
                </tr>
                <tr>
                    <td style="padding:1rem;color:#34d399;border-bottom:1px solid rgba(180,140,50,0.1);">Pemasukan</td>
                    <td style="padding:1rem;text-align:right;color:#34d399;border-bottom:1px solid rgba(180,140,50,0.1);font-weight:600;font-family:'Sora',sans-serif;">+Rp {{ number_format($pemasukanTotal,0,',','.') }}</td>
                </tr>
                <tr>
                    <td style="padding:1rem;color:#fb7185;border-bottom:1px solid rgba(180,140,50,0.1);">Pengeluaran</td>
                    <td style="padding:1rem;text-align:right;color:#fb7185;border-bottom:1px solid rgba(180,140,50,0.1);font-weight:600;font-family:'Sora',sans-serif;">-Rp {{ number_format($pengeluaranTotal,0,',','.') }}</td>
                </tr>
                <tr>
                    <td style="padding:1.25rem 1rem;color:#1a1200;background:rgba(200,150,26,0.06);border-radius:0 0 8px 8px;font-weight:700;">Saldo Akhir</td>
                    <td style="padding:1.25rem 1rem;text-align:right;color:{{ $selisih >= 0 ? '#34d399' : '#fb7185' }};background:rgba(200,150,26,0.06);border-radius:0 0 8px 8px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih),0,',','.') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

</x-app-layout>
