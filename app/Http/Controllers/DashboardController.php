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
        $today = Carbon::today()->toDateString();

        // Aktivitas hari ini
        $todayActivities = Activity::where('user_id', Auth::id())
            ->where('tanggal', $today)
            ->get();

        // Rencana hari ini
        $todayPlans = Plan::where('user_id', Auth::id())
            ->where('tanggal', $today)
            ->get();

        // Rencana terpenuhi
        $completedPlans = $todayActivities
            ->whereNotNull('plan_id')
            ->pluck('plan_id')
            ->unique()
            ->count();

        // Tingkat kepatuhan
        $complianceRate =
            $todayPlans->count() > 0
            ? round(($completedPlans / $todayPlans->count()) * 100)
            : 0;

        $selectedDate =
            request('date')
            ?? Carbon::today()->toDateString();

        $datePlans = Plan::where('user_id', Auth::id())
            ->where('tanggal', $selectedDate)
            ->get();

        $dateActivities = Activity::where('user_id', Auth::id())
            ->where('tanggal', $selectedDate)
            ->get();

        return view(
            'dashboard',
            compact(
                'todayActivities',
                'todayPlans',
                'completedPlans',
                'complianceRate',
                'datePlans',
                'dateActivities',
                'selectedDate'
            )
        );
    }
}