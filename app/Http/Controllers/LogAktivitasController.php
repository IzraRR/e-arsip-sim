<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('log_aktivitas')
            ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->select(
                'log_aktivitas.*',
                'users.name as user_name',
                'users.email as user_email'
            );

        // Filter berdasarkan modul
        if ($request->has('modul') && $request->modul != '') {
            $query->where('log_aktivitas.modul', $request->modul);
        }

        // Filter berdasarkan user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('log_aktivitas.user_id', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('log_aktivitas.created_at', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('log_aktivitas.created_at', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('log_aktivitas.aktivitas', 'like', "%{$search}%")
                  ->orWhere('log_aktivitas.modul', 'like', "%{$search}%")
                  ->orWhere('log_aktivitas.keterangan', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('log_aktivitas.created_at', 'desc')
            ->paginate(20);

        // Get unique modules for filter
        $modules = DB::table('log_aktivitas')
            ->select('modul')
            ->distinct()
            ->orderBy('modul')
            ->pluck('modul');

        // Get users for filter
        $users = User::orderBy('name')->get();

        return view('admin.log-aktivitas.index', compact('logs', 'modules', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $log = DB::table('log_aktivitas')
            ->leftJoin('users', 'log_aktivitas.user_id', '=', 'users.id')
            ->select(
                'log_aktivitas.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.role as user_role'
            )
            ->where('log_aktivitas.id', $id)
            ->first();

        if (!$log) {
            return redirect()->route('admin.log-aktivitas.index')
                ->with('error', 'Log aktivitas tidak ditemukan!');
        }

        return view('admin.log-aktivitas.show', compact('log'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            DB::table('log_aktivitas')->where('id', $id)->delete();

            return redirect()->route('admin.log-aktivitas.index')
                ->with('success', 'Log aktivitas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus log aktivitas. Silakan coba lagi.');
        }
    }

    /**
     * Clear old logs
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        try {
            $days = $request->days;
            $deleted = DB::table('log_aktivitas')
                ->where('created_at', '<', now()->subDays($days))
                ->delete();

            return redirect()->route('admin.log-aktivitas.index')
                ->with('success', "Berhasil menghapus {$deleted} log aktivitas yang lebih dari {$days} hari.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus log aktivitas. Silakan coba lagi.');
        }
    }
}


