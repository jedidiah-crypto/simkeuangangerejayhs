# Format Excel/CSV untuk Import Transaksi

## Struktur File yang Diperlukan

File Excel atau CSV harus memiliki **header (baris pertama)** dengan kolom-kolom berikut:

| Kolom | Wajib | Format | Keterangan |
|-------|-------|--------|-----------|
| **type** | ✓ | Teks | `pemasukan` atau `pengeluaran` |
| **tanggal** | ✓ | Tanggal | `YYYY-MM-DD` atau `DD/MM/YYYY` atau `DD-MM-YYYY` |
| **nominal** | ✓ | Angka | Nilai transaksi (bisa dengan pemisah . atau ,) |
| **kategori** | ✓ | Teks | Nama kategori yang sudah ada di sistem |
| **metode** | ✓* | Teks | `Tunai` atau `Transfer` (*wajib untuk pengeluaran) |
| **rekening_sumber** | - | Teks | Nama rekening bank (opsional, untuk pengeluaran) |
| **keterangan** | - | Teks | Catatan/deskripsi transaksi (opsional) |

## Contoh Baris Data

### Pemasukan
```
type,tanggal,nominal,kategori,metode,rekening_sumber,keterangan
pemasukan,2026-06-01,1500000,Persembahan Mingguan,Tunai,,Donasi rutin minggu
pemasukan,2026-06-02,500000,Perpuluhan,Transfer,,Donasi perpuluhan jemaat
pemasukan,2026-06-03,750000,Donasi Khusus,Tunai,,Sumbangan renovasi gereja
```

### Pengeluaran
```
pengeluaran,2026-06-01,500000,Gaji/Honor,Transfer,BCA Gereja,Gaji ketua gereja
pengeluaran,2026-06-02,250000,Operasional,Tunai,,Belanja perlengkapan
pengeluaran,2026-06-03,100000,Biaya Bank,Transfer,,Biaya admin bulanan
```

## Kategori yang Tersedia

### Pemasukan (Laporan Pemasukan)
- Taburan
- Persembahan Mingguan
- Perpuluhan
- Donasi Khusus

### Pengeluaran (Laporan Pengeluaran)
- Operasional
- Gaji/Honor
- Transportasi
- Konsumsi
- Aset/Inventaris
- Biaya Bank

## Metode Pembayaran
- `Tunai` - Pembayaran tunai
- `Transfer` - Transfer bank

## Format Tanggal yang Diterima
- `2026-06-01` (ISO format: YYYY-MM-DD)
- `01/06/2026` (Format lokal: DD/MM/YYYY)
- `01-06-2026` (Format lokal: DD-MM-YYYY)

## Format Nominal yang Diterima
- `1500000` (tanpa pemisah)
- `1.500.000` (dengan pemisah titik)
- `1,500,000` (dengan pemisah koma)

## Rekening Bank yang Tersedia
Gunakan nama rekening yang sudah terdaftar di sistem untuk kolom `rekening_sumber`:
- BCA Gereja
- Mandiri Gereja
- (Sesuaikan dengan data rekening yang ada di sistem)

## Panduan Upload

1. **Siapkan file Excel** dengan struktur di atas
2. **Pastikan header sudah benar** di baris pertama
3. **Isi data mulai dari baris ke-2**
4. **Simpan sebagai format** `.xlsx`, `.xls`, atau `.csv`
5. **Upload file** di halaman Import Data
6. **Sistem akan memvalidasi** setiap baris
7. **Lihat hasil** import (berhasil/gagal) di pesan notifikasi

## Validasi Sistem

Sistem akan melakukan pengecekan berikut:
- ✓ Type hanya `pemasukan` atau `pengeluaran`
- ✓ Tanggal valid dan format benar
- ✓ Nominal harus angka positif
- ✓ Kategori harus ada di sistem
- ✓ Metode pembayaran valid (untuk pengeluaran)
- ✓ Rekening bank valid (jika diisi)
- ✓ Tidak ada data kosong di kolom wajib

## Contoh File Template

Anda bisa menggunakan template CSV berikut (copy-paste ke file .csv atau .xlsx):

```csv
type,tanggal,nominal,kategori,metode,rekening_sumber,keterangan
pemasukan,2026-06-01,1500000,Persembahan Mingguan,Tunai,,Donasi rutin minggu
pemasukan,2026-06-02,500000,Perpuluhan,Transfer,,Donasi perpuluhan jemaat
pengeluaran,2026-06-01,500000,Gaji/Honor,Transfer,BCA Gereja,Gaji ketua
pengeluaran,2026-06-02,250000,Operasional,Tunai,,Belanja perlengkapan
```

## Troubleshooting

| Error | Penyebab | Solusi |
|-------|---------|--------|
| "Kategori tidak ditemukan" | Nama kategori salah | Periksa kembali nama kategori di tabel kategori sistem |
| "Format tanggal tidak valid" | Format tanggal salah | Gunakan format YYYY-MM-DD atau DD/MM/YYYY |
| "Metode pembayaran tidak ditemukan" | Metode salah | Gunakan "Tunai" atau "Transfer" |
| "Rekening tidak ditemukan" | Nama rekening salah | Sesuaikan dengan nama rekening di sistem |
| "Nominal harus lebih dari 0" | Nominal kosong/nol | Masukkan nominal positif |

## Batasan File

- **Format:** Excel (.xlsx, .xls) atau CSV
- **Ukuran maksimal:** 5 MB
- **Jumlah baris:** Tidak terbatas (per-file)
- **Header:** Harus ada di baris pertama

## Tips
- Gunakan aplikasi seperti Excel, Google Sheets, atau LibreOffice untuk membuat/edit file
- Periksa kembali setiap baris sebelum upload
- Jika ada error, perbaiki baris yang error dan upload ulang
- Sistem tidak akan menghapus data yang sudah ada, hanya menambahkan data baru
