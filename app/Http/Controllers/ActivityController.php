<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    // GET /api/activities (Daftar Aktivitas & Filter)
    public function index()
    {
        $activities = Activity::where(
            'user_id',
            Auth::id()
        )
        ->orderBy('tanggal', 'desc')
        ->get();
        
        return view('activities.index', compact('activities'));
    }

    public function recap()
    {
        $activities = Activity::where(
            'user_id',
            Auth::id()
        )
        ->orderBy('tanggal', 'desc')
        ->get();

        return view('activities.recap', compact('activities'));
    }

    public function destroy($id)
    {
        $activity = Activity::where('_id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dihapus!');
    }

    public function create()
    {
        $plans = Plan::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('activities.create', compact('plans'));
    }

    // POST /api/activities (Tambah Aktivitas)
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'plan_id' => 'nullable|string',
            'nama_aktivitas' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'durasi' => 'required|numeric',
            'deskripsi' => 'nullable|string'
        ]);

        // Simpan ke MongoDB
        $validated['user_id'] = Auth::id();

        $activity = Activity::create($validated);
        
        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    // Menampilkan form edit dengan data lama
    public function edit($id)
    {
        $activity = Activity::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        return view('activities.edit', compact('activity'));
    }

    // Memproses perubahan data
    public function update(Request $request, $id)
    {
        // Validasi input sama seperti saat tambah data
        $validated = $request->validate([
            'plan_id' => 'nullable|string',
            'nama_aktivitas' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'durasi' => 'required|numeric',
            'deskripsi' => 'nullable|string'
        ]);

        $activity = Activity::where('_id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil diperbarui!');
    }
}
