<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $today = now('Asia/Jakarta')->format('Y-m-d');

        $selectedDate = request('date') ?? $today;
        $calendarDate = Carbon::parse($selectedDate);

        $currentMonth = $calendarDate->month;
        $currentYear = $calendarDate->year;
        $daysInMonth = $calendarDate->daysInMonth;

        $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1);
        $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;

        // Range bulan untuk titik warna kalender
        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1)
            ->startOfMonth()
            ->format('Y-m-d');

        $endOfMonth = Carbon::create($currentYear, $currentMonth, 1)
            ->endOfMonth()
            ->format('Y-m-d');

        // Range minggu berdasarkan tanggal yang dipilih
        $startOfWeekCarbon = $calendarDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeekCarbon = $calendarDate->copy()->endOfWeek(Carbon::SUNDAY);

        $startOfWeek = $startOfWeekCarbon->format('Y-m-d');
        $endOfWeek = $endOfWeekCarbon->format('Y-m-d');

        // Aktivitas hari ini
        $todayActivities = Activity::where('user_id', $userId)
            ->where('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Rencana hari ini
        $todayPlans = Plan::where('user_id', $userId)
            ->where('tanggal', $today)
            ->orderBy('jam_mulai')
            ->get();

        // Rencana tanggal yang dipilih
        $datePlans = Plan::where('user_id', $userId)
            ->where('tanggal', $selectedDate)
            ->orderBy('jam_mulai')
            ->get();

        // Aktivitas tanggal yang dipilih
        $dateActivities = Activity::where('user_id', $userId)
            ->where('tanggal', $selectedDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Semua rencana bulan ini untuk titik warna kalender
        $monthPlans = Plan::where('user_id', $userId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // Rencana terpenuhi dihitung dari aktivitas yang punya plan_id
        $completedPlans = $todayActivities
            ->whereNotNull('plan_id')
            ->pluck('plan_id')
            ->unique()
            ->count();

        // Tingkat kepatuhan
        $complianceRate = $todayPlans->count() > 0
            ? round(($completedPlans / $todayPlans->count()) * 100)
            : 0;

        // Rencana minggu ini per hari
        $weekDates = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeekCarbon->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');

            $count = Plan::where('user_id', $userId)
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

        return view('dashboard', compact(
            'todayActivities',
            'todayPlans',
            'completedPlans',
            'complianceRate',
            'datePlans',
            'dateActivities',
            'selectedDate',
            'currentMonth',
            'currentYear',
            'daysInMonth',
            'firstDayOfWeek',
            'monthPlans',
            'weekDates',
            'startOfWeek',
            'endOfWeek'
        ));
    }
}