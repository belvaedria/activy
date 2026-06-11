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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('activities.index', compact('activities'));
    }

public function recap()
{
    $startDate = request('start_date');
    $endDate = request('end_date');

    $query = Activity::where('user_id', Auth::id());

    if (!empty($startDate)) {
        $query->where('tanggal', '>=', $startDate);
    }

    if (!empty($endDate)) {
        $query->where('tanggal', '<=', $endDate);
    }

    $activities = $query
        ->orderBy('tanggal', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    // Total aktivitas
    $totalActivities = $activities->count();

    // Total durasi
    $totalDuration = $activities->sum(function ($activity) {
        return (float) $activity->durasi;
    });

    // Rata-rata durasi
    $averageDuration = $totalActivities > 0
        ? round($totalDuration / $totalActivities, 2)
        : 0;

    // Rencana terpenuhi / selesai
    $completedPlans = $activities
        ->filter(function ($activity) {
            return !empty($activity->plan_id)
                && in_array($activity->status, ['Tepat Waktu', 'Selesai']);
        })
        ->pluck('plan_id')
        ->unique()
        ->count();

    // Aktivitas / rencana terlambat
    $latePlans = $activities
        ->filter(function ($activity) {
            return in_array($activity->status, ['Terlambat', 'terlambat']);
        })
        ->count();

    // Statistik kategori
    $categoryStats = $activities
        ->groupBy('kategori')
        ->map(function ($items, $category) use ($totalDuration) {
            $duration = $items->sum(function ($activity) {
                return (float) $activity->durasi;
            });

            return [
                'category' => $category,
                'count' => $items->count(),
                'duration' => $duration,
                'percent' => $totalDuration > 0
                    ? round(($duration / $totalDuration) * 100)
                    : 0,
            ];
        });

    // Aktivitas durasi panjang, misalnya lebih dari 1 jam
    $longActivities = $activities->filter(function ($activity) {
        return (float) $activity->durasi > 1;
    });

    return view('activities.recap', compact(
        'activities',
        'startDate',
        'endDate',
        'totalActivities',
        'totalDuration',
        'averageDuration',
        'completedPlans',
        'latePlans',
        'categoryStats',
        'longActivities'
    ));
}

    public function destroy($id)
    {
        $activity = Activity::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $planId = $activity->plan_id;

        $activity->delete();

        // Kalau aktivitas ini berasal dari rencana,
        // status rencananya dikembalikan lagi jadi Belum dimulai.
        if (!empty($planId)) {
            $plan = Plan::where('_id', $planId)
                ->where('user_id', Auth::id())
                ->first();

        if ($plan) {
            $plan->status = 'Belum dimulai';
            $plan->keterlambatan_menit = 0;
            $plan->overtime_menit = 0;
            $plan->save();
        }
        }

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil dihapus!');
    }

    public function create(Request $request)
    {
        $plans = Plan::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai')
            ->get();

        $selectedPlan = null;

        if ($request->filled('plan_id')) {

            $selectedPlan = Plan::where('_id', $request->plan_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view(
            'activities.create',
            compact('plans', 'selectedPlan')
        );
    }

    // POST /activities
    public function store(Request $request)
    {
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

        $validated['jam_selesai'] =
            $request->input('jam_selesai') ??
            $request->input('jam_selesai_rencana');

        unset($validated['jam_selesai_rencana']);

        if (empty($validated['plan_id'])) {
            $validated['plan_id'] = null;
        }

        $validated['user_id'] = Auth::id();

        // Kalau aktivitas dari rencana, jangan bikin dobel.
        // Kalau plan_id sudah ada di activities, update data lama.
        if (!empty($validated['plan_id'])) {
            $activity = Activity::where('user_id', Auth::id())
                ->where('plan_id', $validated['plan_id'])
                ->first();

            if ($activity) {
                $activity->update($validated);
            } else {
                Activity::create($validated);
            }

            // Update status rencana
            $plan = Plan::where('_id', $validated['plan_id'])
                ->where('user_id', Auth::id())
                ->first();

            if ($plan) {

                $planStart = strtotime($plan->jam_mulai);
                $planEnd = strtotime($plan->jam_selesai);

                $actualStart = strtotime($validated['jam_mulai']);
                $actualEnd = strtotime($validated['jam_selesai']);

                // Telat mulai
                $lateMinutes = max(
                    0,
                    round(($actualStart - $planStart) / 60)
                );

                // Overtime
                $overtimeMinutes = max(
                    0,
                    round(($actualEnd - $planEnd) / 60)
                );

                // Simpan statistik
                $plan->keterlambatan_menit = $lateMinutes;
                $plan->overtime_menit = $overtimeMinutes;

                // Status plan
                if ($lateMinutes > 15) {
                    $plan->status = 'Terlambat';
                } else {
                    $plan->status = 'Tepat Waktu';
                }

                $plan->save();
            }
        } else {
            // Aktivitas spontan boleh bikin data baru.
            Activity::create($validated);
        }

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil disimpan!');
    }

    public function edit($id)
    {
        $activity = Activity::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $plans = Plan::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'activities.edit',
            compact('activity', 'plans')
        );
    }
    public function update(Request $request, $id)
    {
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

        $validated['jam_selesai'] =
            $request->input('jam_selesai') ??
            $request->input('jam_selesai_rencana');

        unset($validated['jam_selesai_rencana']);

        if (empty($validated['plan_id'])) {
            $validated['plan_id'] = null;
        }

        $activity = Activity::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $oldPlanId = $activity->plan_id;

        $activity->update($validated);

        // Kalau sebelumnya punya rencana lama tapi sekarang pindah/hapus rencana,
        // rencana lama dibalikin statusnya.
        if (!empty($oldPlanId) && $oldPlanId !== $validated['plan_id']) {
            $oldPlan = Plan::where('_id', $oldPlanId)
                ->where('user_id', Auth::id())
                ->first();

            if ($oldPlan) {
                $oldPlan->status = 'Belum dimulai';
                $oldPlan->keterlambatan_menit = 0;
                $oldPlan->overtime_menit = 0;
                $oldPlan->save();
            }
        }

        // Update status rencana baru
        if (!empty($validated['plan_id'])) {
            $plan = Plan::where('_id', $validated['plan_id'])
                ->where('user_id', Auth::id())
                ->first();

            if ($plan) {

                $planStart = strtotime($plan->jam_mulai);
                $planEnd = strtotime($plan->jam_selesai);

                $actualStart = strtotime($validated['jam_mulai']);
                $actualEnd = strtotime($validated['jam_selesai']);

                // Telat mulai
                $lateMinutes = max(
                    0,
                    round(($actualStart - $planStart) / 60)
                );

                // Overtime
                $overtimeMinutes = max(
                    0,
                    round(($actualEnd - $planEnd) / 60)
                );

                // Simpan statistik
                $plan->keterlambatan_menit = $lateMinutes;
                $plan->overtime_menit = $overtimeMinutes;

                // Status plan
                if ($lateMinutes > 15) {
                    $plan->status = 'Terlambat';
                } else {
                    $plan->status = 'Tepat Waktu';
                }

                $plan->save();
            }
        }

        return redirect()
            ->route('activities.index')
            ->with('success', 'Aktivitas berhasil diperbarui!');
    }
}