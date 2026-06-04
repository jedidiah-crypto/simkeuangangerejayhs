<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Gereja</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #1a1a2e; line-height: 1.6; font-size: 12px; }

        .header-wrap {
            border-bottom: 3px solid #0d2b6e;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        /* Logo */
        .col-logo { width: 110px; text-align: left; }
        .logo-img {
            width: 105px;
            height: auto;
            display: block;
            border-radius: 6px;
        }

        /* Judul tengah */
        .col-title { text-align: center; }
        .col-title h1 {
            font-size: 18px;
            color: #0d2b6e;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .title-divider {
            display: block;
            width: 60px;
            height: 2px;
            background: #c8a951;
            margin: 0 auto 6px auto;
        }
        .col-title p {
            font-size: 11px;
            color: #555;
            margin: 2px 0;
        }
        .col-title strong { color: #0d2b6e; }

        /* Stempel */
        .col-stamp { width: 88px; text-align: center; }
        .stamp-circle {
            width: 74px;
            height: 74px;
            border: 2px solid #0d2b6e;
            border-radius: 50%;
            margin: 0 auto 4px auto;
            padding-top: 14px;
            font-weight: bold;
            font-size: 10px;
            color: #0d2b6e;
            text-align: center;
            line-height: 1.4;
        }
        .stamp-icon {
            display: block;
            font-size: 15px;
            color: #c8a951;
            font-weight: bold;
        }
        .stamp-label {
            font-size: 8px;
            color: #777;
            text-align: center;
            line-height: 1.4;
        }

        /* Section */
        .section { margin-bottom: 22px; }
        .section-heading {
            background-color: #f5f0e8;
            border-left: 4px solid #0d2b6e;
            padding: 6px 10px;
            margin-bottom: 10px;
        }
        .section-heading h2 {
            font-size: 13px;
            color: #0d2b6e;
            font-weight: bold;
            margin: 0;
        }

        /* Tabel */
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table th {
            background-color: #0d2b6e;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        table.data-table td {
            border-bottom: 1px solid #ddd6c5;
            padding: 7px 10px;
            font-size: 11px;
            color: #222;
        }
        table.data-table tr.stripe td { background-color: #faf7f2; }
        table.data-table .nominal { text-align: right; font-weight: bold; }

        /* Total row */
        .summary-row td {
            background-color: #f5f0e8 !important;
            border-top: 2px solid #0d2b6e !important;
            font-weight: bold;
            color: #0d2b6e;
            font-size: 11px;
            padding: 8px 10px;
        }

        /* Footer */
        .generated-date {
            text-align: right;
            font-size: 9px;
            color: #999;
            margin-top: 8px;
            margin-bottom: 4px;
        }
        .footer {
            border-top: 1px solid #ddd6c5;
            margin-top: 18px;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #888;
            line-height: 1.6;
        }
        .footer-gold { color: #c8a951; font-weight: bold; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header-wrap">
        <table class="header-table">
            <tr>
                <td class="col-logo">
                    <img class="logo-img"
                         src="data:image/png;base64,{{base64_encode(file_get_contents(public_path('images/logoaja.png')))}}"
                         alt="YHS Church Solo" />
                </td>
                <td class="col-title">
                    <h1>LAPORAN KEUANGAN GEREJA</h1>
                    <span class="title-divider"></span>
                    <p>YHS Church &mdash; Solo</p>
                    <p>Periode: <strong>{{ date('d/m/Y', strtotime($from)) }} &mdash; {{ date('d/m/Y', strtotime($to)) }}</strong></p>
                </td>
                <td class="col-stamp">
                    <div class="stamp-circle">
                        <span class="stamp-icon">&#10003;</span>
                        RESMI
                    </div>
                    <div class="stamp-label">Tanda Keaslian<br>Dokumen Gereja</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- PEMASUKAN --}}
    <div class="section">
        <div class="section-heading"><h2>Pemasukan</h2></div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">No.</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th style="text-align:right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemasukan as $index => $item)
                    <tr @if($index % 2 == 1) class="stripe" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nomor_transaksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="nominal">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#aaa;padding:12px">Tidak ada data pemasukan</td></tr>
                @endforelse
                @if($pemasukan->count() > 0)
                <tr class="summary-row">
                    <td colspan="3">Total Pemasukan</td>
                    <td class="nominal">Rp {{ number_format($pemasukan->sum('nominal'), 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- PENGELUARAN --}}
    <div class="section">
        <div class="section-heading"><h2>Pengeluaran</h2></div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">No.</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th style="text-align:right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluaran as $index => $item)
                    <tr @if($index % 2 == 1) class="stripe" @endif>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nomor_transaksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="nominal">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#aaa;padding:12px">Tidak ada data pengeluaran</td></tr>
                @endforelse
                @if($pengeluaran->count() > 0)
                <tr class="summary-row">
                    <td colspan="3">Total Pengeluaran</td>
                    <td class="nominal">Rp {{ number_format($pengeluaran->sum('nominal'), 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- TANGGAL CETAK --}}
    <div class="generated-date">
        Dokumen dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Laporan ini adalah dokumen resmi <span class="footer-gold">Gereja Yesus Hidup Sejati (YHS) Church Solo</span>.<br>
        Semua data telah diverifikasi oleh sistem informasi keuangan terintegrasi.
    </div>

</body>
</html>
