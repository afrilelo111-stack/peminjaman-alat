
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log Aktivitas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    Riwayat Aktivitas Pengguna
                </h3>
                <p class="text-sm text-gray-500">
                    Pantau seluruh aktivitas yang dilakukan pengguna dalam aplikasi.
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm">

                {{-- Pencarian --}}
                <form method="GET" class="mb-5 flex gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari pengguna atau aktivitas..."
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Cari
                    </button>
                </form>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">

                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Pengguna</th>
                                <th class="px-4 py-3">Aktivitas</th>
                                <th class="px-4 py-3">Keterangan</th>
                                <th class="px-4 py-3">IP Address</th>
                                <th class="px-4 py-3">Waktu</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-4">
                                        {{ $logs->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $log->user?->name ?? 'Pengguna dihapus / Sistem' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $log->user?->role ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                            {{ $log->activity }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-gray-600">
                                        {{ $log->description }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-500">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-500 whitespace-nowrap">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-4 py-10 text-center text-gray-500">
                                        Belum ada aktivitas yang tercatat.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-5">
                    {{ $logs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
