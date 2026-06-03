<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Impor Data Keuangan</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Unggah file Excel untuk menambahkan transaksi pemasukan dan pengeluaran secara langsung ke aplikasi.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 p-4 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 p-4 text-red-800 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <div class="text-sm text-slate-400">Status impor akan muncul di bawah setelah proses selesai.</div>
                <a href="{{ route('reports.import.history') }}" class="inline-flex items-center px-4 py-2 bg-slate-700 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-600">Lihat Riwayat Impor</a>
            </div>

            <div class="bg-slate-900 overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-800">
                <div class="p-6 text-slate-100">
                    <form action="{{ route('reports.import.excel') }}" method="POST" enctype="multipart/form-data" id="import-form">
                        @csrf
                        <div class="grid gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300">File Excel</label>
                                <input type="file" id="file-input" name="file" class="mt-1 block w-full text-slate-100 bg-slate-950 rounded-xl border border-slate-700 p-2" accept=".xlsx,.xls,.csv" required>
                                <p class="mt-2 text-xs text-slate-500">Sistem akan membaca file Anda dan menampilkan preview sebelum import.</p>
                            </div>
                            
                            <!-- Preview Section -->
                            <div id="preview-section" class="hidden rounded-3xl bg-slate-950/70 p-4 border border-slate-800">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-semibold text-slate-100">Preview Data (<span id="row-count">0</span> baris akan diimport)</p>
                                    <span id="validation-status" class="text-xs px-2 py-1 rounded-full"></span>
                                </div>
                                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                                    <table id="preview-table" class="min-w-full text-xs text-left text-slate-300">
                                        <thead class="bg-slate-950 text-slate-400 sticky top-0">
                                            <tr id="preview-header">
                                            </tr>
                                        </thead>
                                        <tbody id="preview-body">
                                        </tbody>
                                    </table>
                                </div>
                                <div id="error-messages" class="mt-3 hidden">
                                </div>
                            </div>

                            <div class="rounded-3xl bg-slate-950/70 p-4 border border-slate-800">
                                <p class="text-sm font-semibold text-slate-100">Format Kolom</p>
                                <p class="mt-2 text-sm text-slate-400">File harus memiliki header berikut:</p>
                                <ul class="list-disc ml-5 mt-2 text-sm text-slate-400">
                                    <li><strong>type</strong> (pemasukan / pengeluaran)</li>
                                    <li><strong>tanggal</strong></li>
                                    <li><strong>nominal</strong></li>
                                    <li><strong>kategori</strong></li>
                                    <li><strong>metode</strong> (opsional)</li>
                                    <li><strong>sumber_dana</strong> (opsional, hanya untuk pemasukan)</li>
                                    <li><strong>keterangan</strong> (opsional)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" id="cancel-btn" class="hidden inline-flex items-center px-4 py-2 bg-slate-700 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-600">Batal</button>
                            <button type="submit" id="submit-btn" disabled class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-500 disabled:opacity-50 disabled:cursor-not-allowed">Impor Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-slate-900 overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-800">
                <div class="p-6 text-slate-100">
                    <h3 class="text-lg font-semibold mb-4">Contoh isi Excel</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs uppercase bg-slate-950 text-slate-400">
                                <tr>
                                    <th class="px-4 py-2">type</th>
                                    <th class="px-4 py-2">tanggal</th>
                                    <th class="px-4 py-2">nominal</th>
                                    <th class="px-4 py-2">kategori</th>
                                    <th class="px-4 py-2">metode</th>
                                    <th class="px-4 py-2">sumber_dana</th>
                                    <th class="px-4 py-2">keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-slate-950 border-b border-slate-800">
                                    <td class="px-4 py-2">pemasukan</td>
                                    <td class="px-4 py-2">2026-06-01</td>
                                    <td class="px-4 py-2">1500000</td>
                                    <td class="px-4 py-2">Persembahan</td>
                                    <td class="px-4 py-2">Transfer</td>
                                    <td class="px-4 py-2">Donatur</td>
                                    <td class="px-4 py-2">Donasi rutin ibadah hari Minggu</td>
                                </tr>
                                <tr class="bg-slate-950/80 border-b border-slate-800">
                                    <td class="px-4 py-2">pengeluaran</td>
                                    <td class="px-4 py-2">2026-06-02</td>
                                    <td class="px-4 py-2">750000</td>
                                    <td class="px-4 py-2">Operasional Gereja</td>
                                    <td class="px-4 py-2">Tunai</td>
                                    <td class="px-4 py-2"></td>
                                    <td class="px-4 py-2">Belanja perlengkapan kebaktian</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/papaparse@5.4.1/papaparse.min.js"></script>
    <script>
        const fileInput = document.getElementById('file-input');
        const previewSection = document.getElementById('preview-section');
        const previewHeader = document.getElementById('preview-header');
        const previewBody = document.getElementById('preview-body');
        const validationStatus = document.getElementById('validation-status');
        const errorMessages = document.getElementById('error-messages');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const importForm = document.getElementById('import-form');

        const requiredColumns = ['type', 'tanggal', 'nominal', 'kategori'];

        fileInput.addEventListener('change', handleFileSelect);
        cancelBtn.addEventListener('click', resetForm);

        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) {
                previewSection.classList.add('hidden');
                submitBtn.disabled = true;
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                try {
                    const data = event.target.result;
                    const workbook = XLSX.read(data, { type: 'binary' });
                    const sheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[sheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet);

                    if (jsonData.length === 0) {
                        showError('File Excel kosong atau tidak memiliki data.');
                        return;
                    }

                    // Normalize headers
                    const headers = Object.keys(jsonData[0]).map(h => h.toLowerCase().trim());
                    const normalizedData = jsonData.map(row => {
                        const normalized = {};
                        Object.keys(row).forEach(key => {
                            normalized[key.toLowerCase().trim()] = row[key];
                        });
                        return normalized;
                    });

                    // Validate headers
                    const missingColumns = requiredColumns.filter(col => !headers.includes(col));
                    if (missingColumns.length > 0) {
                        showError(`Kolom yang hilang: ${missingColumns.join(', ')}`);
                        return;
                    }

                    // Display preview
                    displayPreview(headers, normalizedData);
                    previewSection.classList.remove('hidden');
                    submitBtn.disabled = false;
                    cancelBtn.classList.remove('hidden');

                } catch (error) {
                    showError('Gagal membaca file: ' + error.message);
                }
            };
            reader.readAsBinaryString(file);
        }

        function formatExcelDate(excelDate) {
            if (!excelDate) return excelDate;
            if (typeof excelDate === 'string') return excelDate;
            if (typeof excelDate === 'number') {
                const date = new Date((excelDate - 25569) * 86400 * 1000);
                return date.toISOString().split('T')[0];
            }
            return excelDate;
        }

        function displayPreview(headers, data) {
            errorMessages.innerHTML = '';
            errorMessages.classList.add('hidden');
            validationStatus.innerHTML = '';
            validationStatus.classList.remove('bg-red-900/50', 'text-red-200', 'bg-green-900/50', 'text-green-200');

            // Display header
            previewHeader.innerHTML = headers.map(h => `<th class="px-4 py-2">${h}</th>`).join('');

            // Display first 10 rows with formatted dates
            previewBody.innerHTML = data.slice(0, 10).map((row, idx) => `
                <tr class="border-b border-slate-700 hover:bg-slate-900/50">
                    ${headers.map(h => {
                        let value = row[h] || '-';
                        if (h === 'tanggal') value = formatExcelDate(value);
                        return `<td class="px-4 py-2">${value}</td>`;
                    }).join('')}
                </tr>
            `).join('');

            // Show validation status
            const rowCount = data.length;
            const validRows = data.filter(row => row.type && row.tanggal && row.nominal && row.kategori).length;
            
            document.getElementById('row-count').textContent = rowCount;
            
            if (validRows === rowCount) {
                validationStatus.textContent = `✓ ${rowCount} baris valid`;
                validationStatus.classList.add('bg-green-900/50', 'text-green-200');
            } else {
                validationStatus.textContent = `⚠ ${validRows}/${rowCount} baris valid`;
                validationStatus.classList.add('bg-orange-900/50', 'text-orange-200');
                showWarning(`${rowCount - validRows} baris mungkin akan dilewatkan karena data tidak lengkap.`);
            }
        }

        function showError(message) {
            errorMessages.innerHTML = `<div class="bg-rose-900/50 border border-rose-700 text-rose-200 px-3 py-2 rounded-lg text-xs">${message}</div>`;
            errorMessages.classList.remove('hidden');
            validationStatus.textContent = '✗ Error';
            validationStatus.classList.add('bg-rose-900/50', 'text-rose-200');
            previewSection.classList.remove('hidden');
            submitBtn.disabled = true;
            cancelBtn.classList.remove('hidden');
        }

        function showWarning(message) {
            const warning = document.createElement('div');
            warning.className = 'bg-amber-900/50 border border-amber-700 text-amber-200 px-3 py-2 rounded-lg text-xs';
            warning.textContent = '⚠ ' + message;
            errorMessages.appendChild(warning);
            errorMessages.classList.remove('hidden');
        }

        function resetForm() {
            fileInput.value = '';
            previewSection.classList.add('hidden');
            submitBtn.disabled = true;
            cancelBtn.classList.add('hidden');
            errorMessages.innerHTML = '';
            errorMessages.classList.add('hidden');
        }
    </script>
</x-app-layout>
