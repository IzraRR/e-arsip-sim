<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,pimpinan',
            'status' => 'required|in:aktif,nonaktif',
            'nip' => 'nullable|string|max:50|unique:users',
            'unit_kerja' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'status.required' => 'Status harus dipilih',
            'nip.unique' => 'NIP sudah digunakan',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
                'nip' => $request->nip,
                'unit_kerja' => $request->unit_kerja,
                'telepon' => $request->telepon,
            ]);

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => auth()->id(),
                'aktivitas' => 'Tambah User',
                'modul' => 'Manajemen User',
                'keterangan' => 'Menambah user baru: ' . $user->name . ' (' . $user->email . ')',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan user. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['suratMasuk', 'suratKeluar', 'arsip']);
        
        // Get recent activities
        $recentActivities = DB::table('log_aktivitas')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.users.show', compact('user', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,pimpinan',
            'status' => 'required|in:aktif,nonaktif',
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'unit_kerja' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
        ]);

        try {
            $isOwnAccount = $user->id === auth()->id();
            $roleChanged = $user->role !== $request->role;
            $oldRole = $user->role;

            // Cegah jika user adalah satu-satunya admin dan mencoba mengubah role
            if ($isOwnAccount && $user->role === 'admin' && $request->role !== 'admin') {
                $adminCount = User::where('role', 'admin')
                    ->where('status', 'aktif')
                    ->where('id', '!=', $user->id)
                    ->count();

                if ($adminCount === 0) {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat mengubah role! Anda adalah satu-satunya admin aktif. Pastikan ada admin lain sebelum mengubah role Anda.')
                        ->withInput();
                }
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
                'nip' => $request->nip,
                'unit_kerja' => $request->unit_kerja,
                'telepon' => $request->telepon,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Log aktivitas
            $keterangan = 'Mengupdate user: ' . $user->name . ' (' . $user->email . ')';
            if ($roleChanged) {
                $keterangan .= ' | Role berubah dari ' . strtoupper($oldRole) . ' menjadi ' . strtoupper($request->role);
            }
            if ($isOwnAccount && $roleChanged) {
                $keterangan .= ' | PERINGATAN: User mengubah role akun sendiri!';
            }

            DB::table('log_aktivitas')->insert([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update User',
                'modul' => 'Manajemen User',
                'keterangan' => $keterangan,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            // Jika admin mengubah role mereka sendiri, redirect dengan peringatan
            if ($isOwnAccount && $roleChanged && $request->role !== 'admin') {
                return redirect()->route('dashboard')
                    ->with('warning', 'Role Anda telah diubah menjadi ' . strtoupper($request->role) . '. Anda akan diarahkan ke dashboard sesuai role baru.');
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate user. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        try {
            $userName = $user->name;
            $userEmail = $user->email;
            
            $user->delete();

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => auth()->id(),
                'aktivitas' => 'Hapus User',
                'modul' => 'Manajemen User',
                'keterangan' => 'Menghapus user: ' . $userName . ' (' . $userEmail . ')',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus user. Silakan coba lagi.');
        }
    }
}

