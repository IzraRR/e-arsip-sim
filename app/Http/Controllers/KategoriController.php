<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('arsip')->latest()->paginate(15);
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kategori,kode|max:10',
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);

        Kategori::create($request->all());

        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Kategori',
            'modul' => 'Kategori',
            'keterangan' => 'Menambah kategori: ' . $request->nama_kategori,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kode' => 'required|unique:kategori,kode,' . $kategori->id . '|max:10',
            'nama_kategori' => 'required|max:100',
            'deskripsi' => 'nullable',
        ]);

        $kategori->update($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->arsip()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Kategori tidak bisa dihapus karena masih ada arsip yang menggunakan kategori ini!');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}