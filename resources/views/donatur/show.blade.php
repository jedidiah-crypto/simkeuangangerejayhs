<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Detail Donatur</h2>
                <p class="mt-1 text-sm text-gray-500">Riwayat pemasukan dari donatur {{ $donatur->nama }}.</p>
            </div>
            <a href="{{ route('donatur.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid gap-6 md:grid-cols-2 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $donatur->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $donatur->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $donatur->telepon }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Pemasukan oleh Donatur</h3>
                        <div class="space-y-4">
                            @forelse($donatur->pemasukan as $item)
                                <div class="p-4 rounded-lg bg-white dark:bg-gray-800 border">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="font-semibold">{{ $item->nomor_transaksi }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->tanggal }}</p>
                                        </div>
                                        <p class="font-semibold text-green-600">Rp {{ number_format($item->nominal,0,',','.') }}</p>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $item->keterangan }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500">Belum ada pemasukan dari donatur ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
