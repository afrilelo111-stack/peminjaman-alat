
@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard Admin
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Selamat datang di halaman administrator sistem peminjaman alat.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">Data User</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-800">Manajemen User</h2>
            <a href="{{ route('admin.users.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">
                Lihat Data →
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">Inventaris</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-800">Data Alat</h2>
            <a href="{{ route('admin.alats.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">
                Lihat Data →
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">Transaksi</p>
            <h2 class="mt-2 text-2xl font-bold text-gray-800">Peminjaman</h2>
            <a href="{{ route('admin.peminjamans.index') }}"
               class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">
                Lihat Data →
            </a>
        </div>

    </div>
@endsection
