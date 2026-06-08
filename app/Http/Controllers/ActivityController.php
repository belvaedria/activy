<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    // GET /activities
    public function index()
    {
        $activities = Activity::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('activities.index', compact('activities'));
    }

    public function recap()
    {
        $activities = Activity::where('user_id', Auth::id())
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

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil dihapus!');
    }

    public function create()
    {
        $plans = Plan::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('activities.create', compact('plans'));
    }

    // POST /activities
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'plan_id' => 'nullable|string',
            'nama_aktivitas' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable|string',
            'jam_selesai' => 'nullable|string',
            'jam_selesai_rencana' => 'nullable|string',
            'durasi' => 'required|numeric',
            'status' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        // Kalau form pakai jam_selesai_rencana, disimpan ke field jam_selesai
        $validated['jam_selesai'] =
            $request->input('jam_selesai') ??
            $request->input('jam_selesai_rencana');

        unset($validated['jam_selesai_rencana']);

        // Kalau plan_id kosong, jadikan null
        if (empty($validated['plan_id'])) {
            $validated['plan_id'] = null;
        }

        // Default status
        $validated['status'] = $validated['status'] ?? 'Belum Dikerjakan';

        // Simpan user login
        $validated['user_id'] = Auth::id();

        // Simpan aktivitas ke MongoDB
        $activity = Activity::create($validated);

        // Kalau aktivitas terhubung ke rencana, update status rencananya juga
        if (!empty($validated['plan_id'])) {
            $plan = Plan::where('_id', $validated['plan_id'])
                ->where('user_id', Auth::id())
                ->first();

            if ($plan) {
                $plan->status = $validated['status'];
                $plan->save();
            }
        }

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil ditambahkan!');
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
        // Validasi input
        $validated = $request->validate([
            'plan_id' => 'nullable|string',
            'nama_aktivitas' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable|string',
            'jam_selesai' => 'nullable|string',
            'jam_selesai_rencana' => 'nullable|string',
            'durasi' => 'required|numeric',
            'status' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        // Kalau form pakai jam_selesai_rencana, disimpan ke field jam_selesai
        $validated['jam_selesai'] =
            $request->input('jam_selesai') ??
            $request->input('jam_selesai_rencana');

        unset($validated['jam_selesai_rencana']);

        // Kalau plan_id kosong, jadikan null
        if (empty($validated['plan_id'])) {
            $validated['plan_id'] = null;
        }

        // Default status
        $validated['status'] = $validated['status'] ?? 'Belum Dikerjakan';

        $activity = Activity::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $activity->update($validated);

        // Kalau aktivitas terhubung ke rencana, update status rencananya juga
        if (!empty($validated['plan_id'])) {
            $plan = Plan::where('_id', $validated['plan_id'])
                ->where('user_id', Auth::id())
                ->first();

            if ($plan) {
                $plan->status = $validated['status'];
                $plan->save();
            }
        }

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil diperbarui!');
    }
}