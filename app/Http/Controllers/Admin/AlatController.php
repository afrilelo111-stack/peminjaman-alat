<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    // READ: Menampilkan daftar alat
    public function index(Request $request)
    {
        $alats = Alat::with('kategori')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_alat', 'like', "%{$search}%")
                        ->orWhere('kode_alat', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($request->kategori_id, function ($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.alats.index', compact('alats', 'kategoris'));
    }

    // CREATE: Menampilkan form tambah alat
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.alats.create', compact('kategoris'));
    }

    // STORE: Menyimpan alat baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'nama_alat' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'kode_alat' => [
                'required',
                'string',
                'max:255',
                'unique:alats,kode_alat',
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
            'kondisi' => [
                'required',
                Rule::in(['Baik', 'Rusak', 'Perlu Perbaikan']),
            ],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('alat', 'public');
        }

        Alat::create($validated);

        return redirect()
            ->route('admin.alats.index')
            ->with('success', 'Data alat berhasil ditambahkan.');
    }

    // EDIT: Menampilkan form edit alat
    public function edit(Alat $alat)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.alats.edit', compact('alat', 'kategoris'));
    }

    // UPDATE: Memperbarui data alat
    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategoris,id',
            ],
            'nama_alat' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'kode_alat' => [
                'required',
                'string',
                'max:255',
                Rule::unique('alats', 'kode_alat')->ignore($alat->id),
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'stok' => [
                'required',
                'integer',
                'min:0',
            ],
            'kondisi' => [
                'required',
                Rule::in(['Baik', 'Rusak', 'Perlu Perbaikan']),
            ],
        ]);

        if ($request->hasFile('gambar')) {
            $gambarLama = $alat->gambar;

            $validated['gambar'] = $request->file('gambar')
                ->store('alat', 'public');

            $alat->update($validated);

            if ($gambarLama) {
                Storage::disk('public')->delete($gambarLama);
            }
        } else {
            unset($validated['gambar']);

            $alat->update($validated);
        }

        return redirect()
            ->route('admin.alats.index')
            ->with('success', 'Data alat berhasil diperbarui.');
    }

    // DELETE: Menghapus alat
    public function destroy(Alat $alat)
    {
        $gambar = $alat->gambar;

        $alat->delete();

        if ($gambar) {
            Storage::disk('public')->delete($gambar);
        }

        return redirect()
            ->route('admin.alats.index')
            ->with('success', 'Data alat berhasil dihapus.');
    }
}