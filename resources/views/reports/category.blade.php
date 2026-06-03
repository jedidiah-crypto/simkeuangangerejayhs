<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#06b6d4;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Laporan</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#e2e8f0;margin:0;">Laporan per Kategori</h1>
                <p style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;">{{ \Carbon\Carbon::parse($from)->isoFormat('D MMMM Y') }} — {{ \Carbon\Carbon::parse($to)->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="glass-card animate-in" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('reports.category') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label style="font-size:0.75rem;color:#94a3b8;display:block;margin-bottom:0.35rem;font-weight:500;">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $from }}" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:0.5rem 0.85rem;color:#e2e8f0;font-size:0.85rem;outline:none;">
            </div>
            <div>
                <label style="font-size:0.75rem;color:#94a3b8;display:block;margin-bottom:0.35rem;font-weight:500;">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $to }}" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:0.5rem 0.85rem;color:#e2e8f0;font-size:0.85rem;outline:none;">
            </div>
            <button type="submit" class="btn-primary" style="font-size:0.85rem;">Tampilkan</button>
        </form>
    </div>

    @php
        $allCategories = collect($pemasukan)->merge($pengeluaran);
        $totalPemasukan = $pemasukan->sum();
        $totalPengeluaran = $pengeluaran->sum();
    @endphp

    {{-- Pemasukan Section --}}
    <div style="margin-bottom:2rem;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:4px;height:24px;border-radius:2px;background:#34d399;"></div>
            <h2 style="color:#e2e8f0;font-size:1.1rem;font-weight:700;margin:0;">📈 Pemasukan</h2>
            <span style="margin-left:auto;background:rgba(52,211,153,0.1);color:#34d399;padding:0.3rem 0.8rem;border-radius:8px;font-size:0.8rem;font-weight:600;">Rp {{ number_format($totalPemasukan,0,',','.') }}</span>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
            @forelse($pemasukan as $kategori => $total)
                <div class="glass-card" style="padding:1.25rem;border-top:3px solid #34d399;">
                    <p style="font-size:0.8rem;color:#94a3b8;margin:0 0 0.5rem;">{{ $kategori }}</p>
                    <p style="font-size:1.3rem;font-weight:700;color:#34d399;margin:0 0 0.75rem;font-family:'Sora',sans-serif;">+Rp {{ number_format($total,0,',','.') }}</p>
                    <div style="border-top:1px solid rgba(255,255,255,0.08);padding-top:0.75rem;">
                        <p style="font-size:0.7rem;color:#64748b;margin:0;">{{ number_format(($total/$totalPemasukan)*100,1,',','.') }}% dari total</p>
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:2rem;">
                    <p style="color:#64748b;">Tidak ada data pemasukan</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pengeluaran Section --}}
    <div>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:4px;height:24px;border-radius:2px;background:#fb7185;"></div>
            <h2 style="color:#e2e8f0;font-size:1.1rem;font-weight:700;margin:0;">📉 Pengeluaran</h2>
            <span style="margin-left:auto;background:rgba(251,113,133,0.1);color:#fb7185;padding:0.3rem 0.8rem;border-radius:8px;font-size:0.8rem;font-weight:600;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</span>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
            @forelse($pengeluaran as $kategori => $total)
                <div class="glass-card" style="padding:1.25rem;border-top:3px solid #fb7185;">
                    <p style="font-size:0.8rem;color:#94a3b8;margin:0 0 0.5rem;">{{ $kategori }}</p>
                    <p style="font-size:1.3rem;font-weight:700;color:#fb7185;margin:0 0 0.75rem;font-family:'Sora',sans-serif;">-Rp {{ number_format($total,0,',','.') }}</p>
                    <div style="border-top:1px solid rgba(255,255,255,0.08);padding-top:0.75rem;">
                        <p style="font-size:0.7rem;color:#64748b;margin:0;">{{ number_format(($total/$totalPengeluaran)*100,1,',','.') }}% dari total</p>
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:2rem;">
                    <p style="color:#64748b;">Tidak ada data pengeluaran</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Summary Table --}}
    @if($pemasukan->count() > 0 || $pengeluaran->count() > 0)
        <div class="glass-card animate-in" style="margin-top:2rem;overflow:hidden;">
            <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);">
                <h3 style="color:#e2e8f0;font-size:0.9rem;font-weight:600;margin:0;">Ringkasan Kategori</h3>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Total</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemasukan as $kategori => $total)
                            <tr>
                                <td style="color:#e2e8f0;font-weight:600;">{{ $kategori }}</td>
                                <td><span style="background:rgba(52,211,153,0.15);color:#34d399;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.75rem;font-weight:600;">Pemasukan</span></td>
                                <td><span class="amount-in" style="font-family:'Sora',sans-serif;">+Rp {{ number_format($total,0,',','.') }}</span></td>
                                <td><span style="background:rgba(52,211,153,0.15);color:#34d399;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ number_format(($total/$totalPemasukan)*100,1,',','.') }}%</span></td>
                            </tr>
                        @endforeach
                        @foreach($pengeluaran as $kategori => $total)
                            <tr>
                                <td style="color:#e2e8f0;font-weight:600;">{{ $kategori }}</td>
                                <td><span style="background:rgba(251,113,133,0.15);color:#fb7185;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.75rem;font-weight:600;">Pengeluaran</span></td>
                                <td><span class="amount-out" style="font-family:'Sora',sans-serif;">-Rp {{ number_format($total,0,',','.') }}</span></td>
                                <td><span style="background:rgba(251,113,133,0.15);color:#fb7185;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ number_format(($total/$totalPengeluaran)*100,1,',','.') }}%</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</x-app-layout>
