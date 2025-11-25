<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    /**
     * Get notifikasi untuk user yang login
     */
    public function index()
    {
        $notifikasi = DB::table('notifikasi')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    /**
     * Mark notifikasi as read
     */
    public function markAsRead($id)
    {
        DB::table('notifikasi')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        // Redirect ke URL notifikasi jika ada
        $notif = DB::table('notifikasi')->find($id);
        if ($notif && $notif->url) {
            return redirect($notif->url);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    /**
     * Mark all as read
     */
    public function markAllAsRead()
    {
        DB::table('notifikasi')
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    /**
     * Get unread count (untuk badge)
     */
    public function getUnreadCount()
    {
        $count = DB::table('notifikasi')
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Delete notifikasi
     */
    public function destroy($id)
    {
        DB::table('notifikasi')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Get latest notifications untuk preview dropdown
     */
    public function getLatest()
    {
        $notifikasi = DB::table('notifikasi')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $html = '';
        
        if ($notifikasi->isEmpty()) {
            $html = '<div class="text-center py-3 text-muted">Tidak ada notifikasi</div>';
        } else {
            foreach ($notifikasi as $item) {
                $bgClass = $item->is_read ? '' : 'bg-light';
                $badge = !$item->is_read ? '<span class="badge bg-primary">Baru</span>' : '';
                
                $html .= '
                    <a href="' . route('notifikasi.read', $item->id) . '" 
                    class="dropdown-item ' . $bgClass . ' py-3" 
                    onclick="event.preventDefault(); document.getElementById(\'notif-form-' . $item->id . '\').submit();">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-bell text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 small">' . $item->judul . ' ' . $badge . '</h6>
                                <p class="mb-0 small text-muted">' . substr($item->pesan, 0, 50) . '...</p>
                                <small class="text-muted">' . \Carbon\Carbon::parse($item->created_at)->diffForHumans() . '</small>
                            </div>
                        </div>
                    </a>
                    <form id="notif-form-' . $item->id . '" action="' . route('notifikasi.read', $item->id) . '" method="POST" style="display: none;">
                        ' . csrf_field() . '
                    </form>
                ';
            }
        }

        return response()->json(['html' => $html]);
    }
}