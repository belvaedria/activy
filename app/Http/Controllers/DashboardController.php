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
        $totalPlannedMinutes = 0;
        $totalPenaltyMinutes = 0;

        foreach ($todayPlans as $plan) {

            $planStart = strtotime($plan->jam_mulai);
            $planEnd = strtotime($plan->jam_selesai);

            $planDuration =
                max(0, round(($planEnd - $planStart) / 60));

            $totalPlannedMinutes += $planDuration;

            $activityExists = Activity::where('user_id', $userId)
                ->where('plan_id', (string) $plan->_id)
                ->exists();

            if ($activityExists) {

                $totalPenaltyMinutes +=
                    (int) ($plan->keterlambatan_menit ?? 0);

            } else {

                $planDate =
                    Carbon::parse($plan->tanggal);

                if ($planDate->lt(today())) {

                    $totalPenaltyMinutes +=
                        $planDuration;
                }
            }
        }

        $complianceRate =
            $totalPlannedMinutes > 0
                ? round(
                    max(
                        0,
                        (($totalPlannedMinutes - $totalPenaltyMinutes)
                        / $totalPlannedMinutes)
                        * 100
                    )
                )
                : 0;

        $completedPlanIds = Activity::where('user_id', $userId)
            ->whereNotNull('plan_id')
            ->pluck('plan_id')
            ->toArray();

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
            'completedPlanIds'
        ));
    }
}