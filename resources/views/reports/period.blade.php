<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#38bdf8;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Laporan</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#e2e8f0;margin:0;">Laporan Periode</h1>
                <p style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;">{{ \Carbon\Carbon::parse($from)->isoFormat('D MMMM Y') }} — {{ \Carbon\Carbon::parse($to)->isoFormat('D MMMM Y') }}</p>
            </div>
            <a href="{{ route('reports.export.pdf', ['from' => $from, 'to' => $to]) }}" class="btn-primary" style="font-size:0.8rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Cetak PDF
            </a>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="glass-card animate-in" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('reports.period') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
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

    {{-- Summary Cards --}}
    @php
        $totalP  = $pemasukan->sum('nominal');
        $totalPg = $pengeluaran->sum('nominal');
        $selisih = $totalP - $totalPg;
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;" class="animate-in">
        <div class="stat-card stat-emerald">
            <p style="font-size:0.72rem;font-weight:600;color:#34d399;text-transform:uppercase;letter-spacing:0.08em;">Total Pemasukan</p>
            <p style="font-size:1.5rem;font-weight:800;color:#e2e8f0;margin-top:0.4rem;font-family:'Sora',sans-serif;">Rp {{ number_format($totalP,0,',','.') }}</p>
            <p style="font-size:0.75rem;color:#64748b;margin-top:0.5rem;">{{ $pemasukan->count() }} transaksi</p>
        </div>
        <div class="stat-card stat-rose">
            <p style="font-size:0.72rem;font-weight:600;color:#fb7185;text-transform:uppercase;letter-spacing:0.08em;">Total Pengeluaran</p>
            <p style="font-size:1.5rem;font-weight:800;color:#e2e8f0;margin-top:0.4rem;font-family:'Sora',sans-serif;">Rp {{ number_format($totalPg,0,',','.') }}</p>
            <p style="font-size:0.75rem;color:#64748b;margin-top:0.5rem;">{{ $pengeluaran->count() }} transaksi</p>
        </div>
        <div class="stat-card stat-violet">
            <p style="font-size:0.72rem;font-weight:600;color:#a78bfa;text-transform:uppercase;letter-spacing:0.08em;">Selisih Periode</p>
            <p style="font-size:1.5rem;font-weight:800;color:{{ $selisih >= 0 ? '#34d399' : '#fb7185' }};margin-top:0.4rem;font-family:'Sora',sans-serif;">
                {{ $selisih >= 0 ? '+' : '-' }}Rp {{ number_format(abs($selisih),0,',','.') }}
            </p>
            <p style="font-size:0.75rem;color:#64748b;margin-top:0.5rem;">{{ $selisih >= 0 ? 'Surplus' : 'Defisit' }}</p>
        </div>
    </div>

    {{-- Tabel Pemasukan --}}
    <div class="glass-card animate-in" style="margin-bottom:1.5rem;overflow:hidden;">
        <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.5rem;">
            <div style="width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 8px rgba(16,185,129,0.6);"></div>
            <h3 class="font-display" style="font-size:0.88rem;font-weight:700;color:#34d399;margin:0;">Pemasukan</h3>
            <span style="margin-left:auto;font-size:0.75rem;color:#64748b;">{{ $pemasukan->count() }} transaksi</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Sumber Dana</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemasukan as $item)
                        <tr>
                            <td>
                                <span style="font-family:'Sora',sans-serif;font-size:0.78rem;font-weight:600;color:#38bdf8;background:rgba(56,189,248,0.08);padding:0.2rem 0.55rem;border-radius:7px;border:1px solid rgba(56,189,248,0.15);">
                                    {{ $item->nomor_transaksi }}
                                </span>
                            </td>
                            <td style="color:#94a3b8;font-size:0.82rem;">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}</td>
                            <td style="color:#94a3b8;font-size:0.82rem;">{{ $item->kategori->nama ?? '-' }}</td>
                            <td style="color:#94a3b8;font-size:0.82rem;">{{ $item->sumber_dana ?? '-' }}</td>
                            <td style="color:#94a3b8;font-size:0.82rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->keterangan ?? '-' }}</td>
                            <td><span class="amount-in" style="font-family:'Sora',sans-serif;">+Rp {{ number_format($item->nominal,0,',','.') }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:2.5rem;color:#64748b;">
                                <div style="font-size:1.8rem;margin-bottom:0.5rem;">📭</div>
                                <p style="font-size:0.85rem;">Tidak ada pemasukan pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pemasukan->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="5" style="padding:0.75rem 1rem;text-align:right;font-size:0.82rem;font-weight:600;color:#94a3b8;border-top:1px solid rgba(255,255,255,0.06);">Total Pemasukan</td>
                            <td style="padding:0.75rem 1rem;border-top:1px solid rgba(255,255,255,0.06);">
                                <span class="amount-in" style="font-family:'Sora',sans-serif;font-size:0.9rem;font-weight:700;">+Rp {{ number_format($totalP,0,',','.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Tabel Pengeluaran --}}
    <div class="glass-card animate-in" style="overflow:hidden;">
        <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.5rem;">
            <div style="width:8px;height:8px;border-radius:50%;background:#f43f5e;box-shadow:0 0 8px rgba(244,63,94,0.6);"></div>
            <h3 class="font-display" style="font-size:0.88rem;font-weight:700;color:#fb7185;margin:0;">Pengeluaran</h3>
            <span style="margin-left:auto;font-size:0.75rem;color:#64748b;">{{ $pengeluaran->count() }} transaksi</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Metode</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluaran as $item)
                        <tr>
                            <td>
                                <span style="font-family:'Sora',sans-serif;font-size:0.78rem;font-weight:600;color:#f43f5e;background:rgba(244,63,94,0.08);padding:0.2rem 0.55rem;border-radius:7px;border:1px solid rgba(244,63,94,0.15);">
                                    {{ $item->nomor_transaksi }}
                                </span>
                            </td>
                            <td style="color:#94a3b8;font-size:0.82rem;">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}</td>
                            <td style="color:#94a3b8;font-size:0.82rem;">{{ $item->kategori->nama ?? '-' }}</td>
                            <td style="color:#94a3b8;font-size:0.82rem;">{{ $item->metode ?? '-' }}</td>
                            <td style="color:#94a3b8;font-size:0.82rem;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->keterangan ?? '-' }}</td>
                            <td><span class="amount-out" style="font-family:'Sora',sans-serif;">-Rp {{ number_format($item->nominal,0,',','.') }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:2.5rem;color:#64748b;">
                                <div style="font-size:1.8rem;margin-bottom:0.5rem;">📭</div>
                                <p style="font-size:0.85rem;">Tidak ada pengeluaran pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pengeluaran->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="5" style="padding:0.75rem 1rem;text-align:right;font-size:0.82rem;font-weight:600;color:#94a3b8;border-top:1px solid rgba(255,255,255,0.06);">Total Pengeluaran</td>
                            <td style="padding:0.75rem 1rem;border-top:1px solid rgba(255,255,255,0.06);">
                                <span class="amount-out" style="font-family:'Sora',sans-serif;font-size:0.9rem;font-weight:700;">-Rp {{ number_format($totalPg,0,',','.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</x-app-layout>
