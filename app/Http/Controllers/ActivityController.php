<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // GET /api/activities (Daftar Aktivitas & Filter)
    public function index()
    {
        // Ambil semua data aktivitas, urutkan dari yang terbaru
        $activities = Activity::orderBy('tanggal', 'desc')->get();
        
        return view('activities.index', compact('activities'));
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dihapus!');
    }

    public function create()
    {
        return view('activities.create');
    }

    // POST /api/activities (Tambah Aktivitas)
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_aktivitas' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'durasi' => 'required|numeric',
            'deskripsi' => 'nullable|string'
        ]);

        // Simpan ke MongoDB
        $activity = Activity::create($validated);
        
        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    // Menampilkan form edit dengan data lama
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    // Memproses perubahan data
    public function update(Request $request, $id)
    {
        // Validasi input sama seperti saat tambah data
        $validated = $request->validate([
            'nama_aktivitas' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'durasi' => 'required|numeric',
            'deskripsi' => 'nullable|string'
        ]);

        // Cari data lama, lalu update dengan data baru
        $activity = Activity::findOrFail($id);
        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil diperbarui!');
    }
}