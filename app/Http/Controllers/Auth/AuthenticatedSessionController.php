<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validasi kredensial
        $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        // Cek status user
        $user = Auth::user();
        
        if ($user->status !== 'aktif') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ]);
        }

        // Log aktivitas login
        DB::table('log_aktivitas')->insert([
            'user_id' => $user->id,
            'aktivitas' => 'Login',
            'modul' => 'Authentication',
            'keterangan' => 'User ' . $user->name . ' berhasil login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        // Redirect berdasarkan role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'pimpinan':
                return redirect()->intended(route('pimpinan.dashboard'));
            case 'staff':
                return redirect()->intended(route('staff.dashboard'));
            default:
                return redirect()->intended(route('dashboard'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log aktivitas logout
        if (Auth::check()) {
            DB::table('log_aktivitas')->insert([
                'user_id' => Auth::id(),
                'aktivitas' => 'Logout',
                'modul' => 'Authentication',
                'keterangan' => 'User ' . Auth::user()->name . ' logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}