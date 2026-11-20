
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Laporan Peminjaman --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600"
                                fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414 0L19 8v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg text-gray-800">
                                Laporan Peminjaman
                            </h3>
                            <p class="text-sm text-gray-500">
                                Data seluruh transaksi peminjaman.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('petugas.laporan.peminjaman') }}"
                        method="GET">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="tanggal_mulai"
                                    value="{{ request('tanggal_mulai') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="tanggal_selesai"
                                    value="{{ request('tanggal_selesai') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <button type="submit"
                                class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-white font-medium hover:bg-blue-700 transition">
                                Export Excel Peminjaman
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Laporan Pengembalian --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600"
                                fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.291 9 11.622C17.176 19.291 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="font-semibold text-lg text-gray-800">
                                Laporan Pengembalian
                            </h3>
                            <p class="text-sm text-gray-500">
                                Data seluruh transaksi pengembalian.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('petugas.laporan.pengembalian') }}"
                        method="GET">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="tanggal_mulai"
                                    value="{{ request('tanggal_mulai') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Selesai
                                </label>
                                <input type="date" name="tanggal_selesai"
                                    value="{{ request('tanggal_selesai') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            </div>

                            <button type="submit"
                                class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-white font-medium hover:bg-green-700 transition">
                                Export Excel Pengembalian
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
