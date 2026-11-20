<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // READ: Menampilkan daftar kategori
    public function index(Request $request)
    {
        $kategoris = Kategori::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama_kategori', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategoris.index', compact('kategoris'));
    }

    // CREATE: Menampilkan form tambah kategori
    public function create()
    {
        return view('admin.kategoris.create');
    }

    // STORE: Menyimpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        Kategori::create($validated);

        return redirect()
            ->route('admin.kategoris.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // EDIT: Menampilkan form edit kategori
    public function edit(Kategori $kategori)
    {
        return view('admin.kategoris.edit', compact('kategori'));
    }

    // UPDATE: Memperbarui kategori
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        $kategori->update($validated);

        return redirect()
            ->route('admin.kategoris.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // DELETE: Menghapus kategori
    // DELETE: Menghapus kategori
public function destroy(Kategori $kategori)
{
    $jumlahAlat = \Illuminate\Support\Facades\DB::table('alats')
        ->where('kategori_id', $kategori->id)
        ->count();

    if ($jumlahAlat > 0) {
        return redirect()
            ->route('admin.kategoris.index')
            ->with(
                'error',
                "Kategori tidak dapat dihapus karena masih digunakan oleh {$jumlahAlat} alat."
            );
    }

    $kategori->delete();

    return redirect()
        ->route('admin.kategoris.index')
        ->with('success', 'Kategori berhasil dihapus.');
}
}