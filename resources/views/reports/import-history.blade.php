<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Riwayat Impor Data</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lihat catatan impor Excel/CSV dan status proses impor terakhir.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.import.form') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Kembali ke Impor</a>
                <a href="{{ route('reports.import.history.csv') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Export CSV Riwayat</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-800">
                <div class="p-6 text-slate-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-800">
                            <thead class="bg-slate-950">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">File</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Jumlah Terimpor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-950 divide-y divide-slate-800">
                                @forelse($items as $item)
                                    @php
                                        $meta = json_decode($item->new_values, true) ?? [];
                                        $file = $meta['file'] ?? '-';
                                        $imported = $meta['imported'] ?? ($meta['error'] ? 0 : '-');
                                        $status = isset($meta['error']) ? 'Gagal' : 'Berhasil';
                                        $detail = $meta['error'] ?? 'Impor berhasil';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $file }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $imported }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status === 'Berhasil' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $status }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">{{ $detail }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat impor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">{{ $items->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
