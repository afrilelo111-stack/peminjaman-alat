<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // READ: Menampilkan daftar pengguna
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // CREATE: Menampilkan form tambah pengguna
    public function create()
    {
        return view('admin.users.create');
    }

    // STORE: Menyimpan pengguna baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'petugas', 'user'])],
        ]);

        $user = new User();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // EDIT: Menampilkan form edit pengguna
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // UPDATE: Memperbarui data pengguna
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'petugas', 'user'])],
        ]);

        // Admin tidak boleh mengubah role miliknya sendiri
        if ($request->user()->is($user) &&
            $validated['role'] !== 'admin') {
            throw ValidationException::withMessages([
                'role' => 'Anda tidak dapat mengubah role akun sendiri.',
            ]);
        }

        DB::transaction(function () use ($user, $validated) {

            // Mencegah admin terakhir kehilangan hak administrator
            $admins = User::where('role', 'admin')
                ->lockForUpdate()
                ->get();

            if (
                $user->role === 'admin' &&
                $validated['role'] !== 'admin' &&
                $admins->count() <= 1
            ) {
                throw ValidationException::withMessages([
                    'role' => 'Tidak dapat mengubah role admin terakhir.',
                ]);
            }

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }


// DELETE: Menghapus pengguna
public function destroy(Request $request, User $user)
{
    // Mencegah admin menghapus akun sendiri
    if ($request->user()->is($user)) {
        return back()->with(
            'error',
            'Anda tidak dapat menghapus akun sendiri.'
        );
    }

    $result = DB::transaction(function () use ($user) {

        // Mengunci data pengguna selama proses penghapusan
        $user = User::whereKey($user->id)
            ->lockForUpdate()
            ->firstOrFail();

        // Admin terakhir tidak boleh dihapus
        if ($user->role === 'admin') {
            $jumlahAdmin = User::where('role', 'admin')
                ->lockForUpdate()
                ->count();

            if ($jumlahAdmin <= 1) {
                return 'last_admin';
            }
        }


        $memilikiPeminjaman = DB::table('peminjamans')
            ->where('peminjam_id', $user->id)
            ->exists();

        $memilikiRiwayatPengembalian = DB::table('pengembalians')
            ->where(function ($query) use ($user) {
                $query->where('petugas_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->exists();

        if ($memilikiPeminjaman || $memilikiRiwayatPengembalian) {
            return 'has_history';
        }


        if ($memilikiPeminjaman) {
            return 'has_loans';
        }

        // Hapus pengguna jika tidak memiliki riwayat
        $user->delete();

        return 'deleted';
    });

    if ($result === 'last_admin') {
        return back()->with(
            'error',
            'Admin terakhir tidak dapat dihapus.'
        );
    }

    if ($result === 'has_history') {
        return back()->with(
            'error',
            'Pengguna tidak dapat dihapus karena masih memiliki riwayat transaksi.'
        );
    }
    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Pengguna berhasil dihapus.');
}

}
