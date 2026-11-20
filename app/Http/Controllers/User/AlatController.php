<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
public function index(Request $request)
{
    $validated = $request->validate([
        'search' => ['nullable', 'string', 'max:255'],
        'kategori_id' => ['nullable', 'integer', 'exists:kategoris,id'],
    ]);

    $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();

    $alats = Alat::with('kategori')
        ->when($validated['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                    ->orWhere('kode_alat', 'like', "%{$search}%")
                    ->orWhereHas('kategori', function ($kategori) use ($search) {
                        $kategori->where('nama_kategori', 'like', "%{$search}%");
                    });
            });
        })
        ->when($validated['kategori_id'] ?? null, function ($query, $kategoriId) {
            $query->where('kategori_id', $kategoriId);
        })
        ->latest()
        ->paginate(12)
        ->withQueryString();

    return view('user.alats.index', compact('alats', 'kategoris'));
}

    public function show(Alat $alat)
    {
        $alat->load('kategori');

        return view('user.alats.show', compact('alat'));
    }
}
