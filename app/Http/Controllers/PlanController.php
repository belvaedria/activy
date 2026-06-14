<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $today = now('Asia/Jakarta')->format('Y-m-d');

        $selectedDate = $request->tanggal ?? $today;

        $currentDate = Carbon::parse($selectedDate);

        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
        $daysInMonth = $currentDate->daysInMonth;

        $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->dayOfWeek;

        $monthName = $currentDate
            ->locale('id')
            ->translatedFormat('F');

        // Range minggu berdasarkan tanggal yang dipilih
        $startOfWeekCarbon = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeekCarbon = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);

        $startOfWeek = $startOfWeekCarbon->format('Y-m-d');
        $endOfWeek = $endOfWeekCarbon->format('Y-m-d');

        // Range bulan yang sedang ditampilkan
        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1)
            ->startOfMonth()
            ->format('Y-m-d');

        $endOfMonth = Carbon::create($currentYear, $currentMonth, 1)
            ->endOfMonth()
            ->format('Y-m-d');

        // Rencana sesuai tanggal yang dipilih
        $plans = Plan::where('user_id', Auth::id())
            ->where('tanggal', $selectedDate)
            ->orderBy('jam_mulai')
            ->get();

        // Semua rencana bulan ini untuk titik warna kalender
        $monthPlans = Plan::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // Card: Rencana hari ini
        $todayPlansCount = Plan::where('user_id', Auth::id())
            ->where('tanggal', $today)
            ->count();

        // Card: Rencana minggu ini
        $weekPlansCount = Plan::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->count();

        // Card: Selesai
        $completedPlansCount = Plan::where('user_id', Auth::id())
            ->whereIn('status', ['Terlambat', 'Tepat Waktu'])
            ->count();

        // Card: Terlambat
        $latePlansCount = Plan::where('user_id', Auth::id())
            ->whereIn('status', ['Terlambat', 'terlambat'])
            ->count();

        // Data rencana minggu ini per hari
        $weekDates = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeekCarbon->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');

            $count = Plan::where('user_id', Auth::id())
                ->where('tanggal', $dateString)
                ->count();

            $weekDates[] = [
                'date' => $dateString,
                'day' => $date->locale('id')->translatedFormat('l'),
                'short_day' => $date->locale('id')->translatedFormat('D'),
                'count' => $count,
                'is_selected' => $selectedDate === $dateString,
            ];
        }

        return view('plans.index', compact(
            'plans',
            'selectedDate',
            'currentMonth',
            'currentYear',
            'daysInMonth',
            'firstDayOfMonth',
            'monthName',
            'todayPlansCount',
            'weekPlansCount',
            'completedPlansCount',
            'latePlansCount',
            'monthPlans',
            'weekDates',
            'startOfWeek',
            'endOfWeek'
        ));
    }

    public function destroy($id)
    {
        $plan = Plan::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $selectedDate = $plan->tanggal;

        $plan->delete();

        return redirect()
            ->route('plans.index', ['tanggal' => $selectedDate])
            ->with('success', 'Rencana berhasil dihapus!');
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rencana' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $validated['status'] = 'Belum dimulai';
        $validated['user_id'] = Auth::id();

        Plan::create($validated);

        return redirect()
            ->route('plans.index', ['tanggal' => $validated['tanggal']])
            ->with('success', 'Rencana berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $plan = Plan::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_rencana' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'nullable|string',
        ]);

        $plan = Plan::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!isset($validated['status'])) {
            unset($validated['status']);
        }

        $plan->update($validated);

        return redirect()
            ->route('plans.index', ['tanggal' => $validated['tanggal']])
            ->with('success', 'Rencana berhasil diperbarui!');
    }
}
