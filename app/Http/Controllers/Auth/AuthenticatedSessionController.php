<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Peminjaman;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    private function redirectByRole(): string
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'   => route('admin.dashboard'),
            'petugas' => route('petugas.dashboard'),
            'user'    => route('user.alats.index'),
            default   => abort(403, 'Role pengguna tidak valid.'),
        };
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Periksa keterlambatan khusus untuk role user.
        if ($user->role === 'user') {
            $jumlahTerlambat = Peminjaman::where('peminjam_id', $user->id)
                ->where('status', 'Disetujui')
                ->whereDate('tanggal_rencana_kembali', '<', today())
                ->count();

            if ($jumlahTerlambat > 0) {
                $request->session()->flash(
                    'login_warning',
                    "Perhatian! Kamu memiliki {$jumlahTerlambat} peminjaman yang telah melewati batas waktu pengembalian. Segera lakukan pengembalian alat."
                );
            }
        }

        return redirect()->intended($this->redirectByRole());
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
