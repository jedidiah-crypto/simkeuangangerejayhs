<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Gereja</title>
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; line-height: 1.6; }

        /* Header menggunakan table agar kompatibel dengan DomPDF */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .col-logo {
            width: 90px;
            text-align: left;
        }
        .logo-box {
            width: 78px;
            height: 78px;
            border: 3px solid #1e40af;
            border-radius: 8px;
            text-align: center;
            padding-top: 12px;
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            line-height: 1.1;
        }
        .logo-box-sub {
            font-size: 9px;
            font-weight: normal;
            color: #555;
            margin-top: 3px;
        }
        .col-title {
            text-align: center;
        }
        .col-title h1 {
            font-size: 20px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .col-title p {
            font-size: 12px;
            color: #555;
            margin: 2px 0;
        }
        .col-stamp {
            width: 90px;
            text-align: center;
        }
        .stamp-circle {
            width: 78px;
            height: 78px;
            border: 2px solid #1e40af;
            border-radius: 40px;
            margin: 0 auto 4px auto;
            padding-top: 18px;
            font-weight: bold;
            font-size: 11px;
            color: #1e40af;
            text-align: center;
            line-height: 1.4;
        }
        .stamp-label {
            font-size: 9px;
            color: #666;
            text-align: center;
        }
        .header-border {
            border-bottom: 3px solid #1e40af;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .section { margin-bottom: 25px; }
        .section h2 { font-size: 14px; margin-bottom: 10px; color: #1e40af; font-weight: bold; }

        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table, table.data-table th, table.data-table td { border: 1px solid #bbb; }
        table.data-table th { background-color: #f0f0f0; padding: 10px; text-align: left; font-weight: bold; font-size: 12px; }
        table.data-table td { padding: 8px; text-align: left; font-size: 11px; }

        .footer { text-align: center; margin-top: 40px; font-size: 10px; color: #888; }
        .generated-date { text-align: right; font-size: 10px; color: #888; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header-border">
        <table class="header-table">
            <tr>
                <td class="col-logo">
                    <div class="logo-box">
                        YHS
                        <div class="logo-box-sub">Church<br>Solo</div>
                    </div>
                </td>
                <td class="col-title">
                    <h1>LAPORAN KEUANGAN GEREJA</h1>
                    <p>YHS Church Solo</p>
                    <p>Periode: <strong>{{ date('d/m/Y', strtotime($from)) }} - {{ date('d/m/Y', strtotime($to)) }}</strong></p>
                </td>
                <td class="col-stamp">
                    <div class="stamp-circle">&#10003;<br>RESMI</div>
                    <div class="stamp-label">Tanda Keaslian<br>Dokumen Gereja</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Pemasukan</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemasukan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nomor_transaksi }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Pengeluaran</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nomor_transaksi }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="generated-date">
        <p>Dokumen dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="footer">
        <p style="border-top: 1px solid #bbb; padding-top: 10px; margin-top: 20px;">
            Laporan ini adalah dokumen resmi Gereja YHS Church Solo.<br>
            Semua data telah diverifikasi oleh sistem informasi keuangan terintegrasi.
        </p>
    </div>
</body>
</html>
