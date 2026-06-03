<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <p style="font-size:0.75rem; font-weight:600; color:#38bdf8; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.3rem;">
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </p>
                <h1 class="font-display" style="font-size:1.5rem; font-weight:700; color:#e2e8f0; margin:0;">Dashboard Keuangan</h1>
                <p style="font-size:0.825rem; color:#64748b; margin-top:0.2rem;">Ringkasan keuangan gereja bulan ini</p>
            </div>
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <a href="{{ route('reports.import.form') }}" class="btn-ghost" style="font-size:0.8rem; padding:0.55rem 1rem;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Impor Excel
                </a>
                <a href="{{ route('reports.export.pdf', ['from' => now()->startOfMonth()->toDateString(),'to' => now()->endOfMonth()->toDateString()]) }}" class="btn-primary" style="font-size:0.8rem; padding:0.55rem 1rem;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- Stat Cards -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap:1rem;" class="animate-in">
            <div class="stat-card stat-emerald">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-size:0.75rem; font-weight:600; color:#34d399; text-transform:uppercase; letter-spacing:0.08em;">Pemasukan Bulan Ini</p>
                        <p style="font-size:1.75rem; font-weight:800; color:#e2e8f0; margin-top:0.5rem; letter-spacing:-0.02em; font-family:'Sora',sans-serif;">Rp {{ number_format($totalPemasukan,0,',','.') }}</p>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(16,185,129,0.2);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#34d399"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                </div>
                <a href="{{ route('pemasukan.index') }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#34d399;text-decoration:none;margin-top:1rem;opacity:0.8;transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">
                    Lihat detail →
                </a>
            </div>

            <div class="stat-card stat-rose">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-size:0.75rem; font-weight:600; color:#fb7185; text-transform:uppercase; letter-spacing:0.08em;">Pengeluaran Bulan Ini</p>
                        <p style="font-size:1.75rem; font-weight:800; color:#e2e8f0; margin-top:0.5rem; letter-spacing:-0.02em; font-family:'Sora',sans-serif;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</p>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(244,63,94,0.2);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fb7185"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                </div>
                <a href="{{ route('pengeluaran.index') }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#fb7185;text-decoration:none;margin-top:1rem;opacity:0.8;transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">
                    Lihat detail →
                </a>
            </div>

            <div class="stat-card stat-violet">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-size:0.75rem; font-weight:600; color:#a78bfa; text-transform:uppercase; letter-spacing:0.08em;">Saldo Kas Gereja</p>
                        <p style="font-size:1.75rem; font-weight:800; color:#e2e8f0; margin-top:0.5rem; letter-spacing:-0.02em; font-family:'Sora',sans-serif;">Rp {{ number_format($saldo,0,',','.') }}</p>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(139,92,246,0.2);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#a78bfa"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                </div>
                @php $selisih = $totalPemasukan - $totalPengeluaran; @endphp
                <p style="font-size:0.75rem;margin-top:1rem;color:{{ $selisih >= 0 ? '#34d399' : '#fb7185' }};">
                    {{ $selisih >= 0 ? '▲' : '▼' }} Rp {{ number_format(abs($selisih),0,',','.') }} bulan ini
                </p>
            </div>
        </div>

        <!-- Charts — wrapper div wajib ada agar Chart.js bisa hitung tinggi -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;" class="animate-in delay-2">
            <div class="glass-card" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                    <h3 class="font-display" style="font-size:0.9rem;font-weight:700;color:#e2e8f0;">Tren Pemasukan &amp; Pengeluaran</h3>
                    <span style="font-size:0.72rem;color:#64748b;background:rgba(255,255,255,0.04);padding:0.3rem 0.7rem;border-radius:8px;border:1px solid rgba(255,255,255,0.06);">Mingguan</span>
                </div>
                {{-- Wrapper dengan height eksplisit agar Chart.js tidak collapse --}}
                <div style="position:relative; height:220px;">
                    <canvas id="chart"></canvas>
                </div>
            </div>

            <div class="glass-card" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                    <h3 class="font-display" style="font-size:0.9rem;font-weight:700;color:#e2e8f0;">Distribusi Pengeluaran</h3>
                    <span style="font-size:0.72rem;color:#64748b;background:rgba(255,255,255,0.04);padding:0.3rem 0.7rem;border-radius:8px;border:1px solid rgba(255,255,255,0.06);">Per Kategori</span>
                </div>
                {{-- Wrapper dengan height eksplisit agar Chart.js tidak collapse --}}
                <div style="position:relative; height:220px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="glass-card animate-in delay-3" style="padding:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                <h3 class="font-display" style="font-size:0.9rem;font-weight:700;color:#e2e8f0;">Transaksi Terbaru</h3>
                <div style="display:flex;gap:0.5rem;">
                    <a href="{{ route('pemasukan.index') }}" class="btn-ghost" style="font-size:0.72rem;padding:0.35rem 0.75rem;">Semua Pemasukan</a>
                    <a href="{{ route('pengeluaran.index') }}" class="btn-ghost" style="font-size:0.72rem;padding:0.35rem 0.75rem;">Semua Pengeluaran</a>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                @forelse($latest as $t)
                    @php $isPemasukan = $t->_type === 'pemasukan'; @endphp
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.9rem 1rem;border-radius:14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.04);transition:background 0.15s;cursor:default;"
                         onmouseover="this.style.background='rgba(56,189,248,0.04)'" onmouseout="this.style.background='rgba(255,255,255,0.02)'">
                        <div style="display:flex;align-items:center;gap:0.85rem;">
                            <div style="width:36px;height:36px;border-radius:10px;background:{{ $isPemasukan ? 'rgba(16,185,129,0.12)' : 'rgba(244,63,94,0.12)' }};display:flex;align-items:center;justify-content:center;font-size:1rem;color:{{ $isPemasukan ? '#34d399' : '#fb7185' }};">
                                {{ $isPemasukan ? '↑' : '↓' }}
                            </div>
                            <div>
                                <p style="font-size:0.85rem;font-weight:600;color:#e2e8f0;">{{ $t->nomor_transaksi ?? '-' }}</p>
                                <p style="font-size:0.75rem;color:#64748b;margin-top:0.1rem;">{{ $t->keterangan ?? ($t->kategori->nama ?? '-') }}</p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <p class="{{ $isPemasukan ? 'amount-in' : 'amount-out' }}" style="font-size:0.9rem;">
                                {{ $isPemasukan ? '+' : '-' }}Rp {{ number_format($t->nominal,0,',','.') }}
                            </p>
                            <p style="font-size:0.72rem;color:#64748b;margin-top:0.1rem;">{{ $t->tanggal ?? $t->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:3rem;color:#64748b;">
                        <div style="font-size:2.5rem;margin-bottom:0.75rem;">📊</div>
                        <p style="font-size:0.875rem;">Belum ada transaksi.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const weeklyLabels     = @json($weeklyLabels);
        const pemasukanSeries  = @json($pemasukanSeries);
        const pengeluaranSeries= @json($pengeluaranSeries);
        const categoryLabels   = @json($categoryLabels);
        const categoryValues   = @json($categoryValues);

        Chart.defaults.color       = '#64748b';
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        // --- Line Chart: Tren Mingguan ---
        const ctx = document.getElementById('chart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weeklyLabels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: pemasukanSeries,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.08)',
                        fill: true, tension: 0.4, borderWidth: 2.5,
                        pointBackgroundColor: '#10b981', pointRadius: 4, pointHoverRadius: 6
                    }, {
                        label: 'Pengeluaran',
                        data: pengeluaranSeries,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244,63,94,0.08)',
                        fill: true, tension: 0.4, borderWidth: 2.5,
                        pointBackgroundColor: '#f43f5e', pointRadius: 4, pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#94a3b8', padding: 20, font: { size: 12 } } },
                        tooltip: {
                            backgroundColor: 'rgba(13,20,36,0.95)',
                            borderColor: 'rgba(99,179,237,0.2)', borderWidth: 1,
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            padding: 12, cornerRadius: 10,
                            callbacks: {
                                label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b' } },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.04)' },
                            ticks: {
                                color: '#64748b',
                                callback: v => 'Rp ' + (v >= 1000000
                                    ? (v/1000000).toFixed(1) + 'jt'
                                    : v.toLocaleString('id-ID'))
                            }
                        }
                    }
                }
            });
        }

        // --- Donut Chart: Distribusi Pengeluaran ---
        const donutCtx = document.getElementById('categoryChart');
        if (donutCtx) {
            const hasData = categoryValues.length > 0 && categoryValues.some(v => v > 0);
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: hasData ? categoryLabels : ['Belum ada data'],
                    datasets: [{
                        data: hasData ? categoryValues : [1],
                        backgroundColor: hasData
                            ? ['#f43f5e','#f97316','#f59e0b','#38bdf8','#6366f1','#10b981','#8b5cf6']
                            : ['rgba(255,255,255,0.06)'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#94a3b8', padding: 16, font: { size: 11 } }
                        },
                        tooltip: {
                            enabled: hasData,
                            backgroundColor: 'rgba(13,20,36,0.95)',
                            borderColor: 'rgba(99,179,237,0.2)', borderWidth: 1,
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            padding: 12, cornerRadius: 10,
                            callbacks: {
                                label: ctx => ' Rp ' + ctx.parsed.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
