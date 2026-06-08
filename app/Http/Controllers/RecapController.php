<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ?? now('Asia/Jakarta')->startOfWeek(Carbon::MONDAY)->format('Y-m-d');

        $endDate = $request->end_date
            ?? now('Asia/Jakarta')->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');

        $selectedCategory = $request->category;
        $selectedStatus = $request->status;
        $selectedPeriod = $request->period ?? 'mingguan';

        $activitiesQuery = Activity::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startDate, $endDate]);

        $plansQuery = Plan::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if (!empty($selectedCategory)) {
            $activitiesQuery->where('kategori', $selectedCategory);
            $plansQuery->where('kategori', $selectedCategory);
        }

        if (!empty($selectedStatus)) {
            $activitiesQuery->where('status', $selectedStatus);
            $plansQuery->where('status', $selectedStatus);
        }

        $activities = $activitiesQuery
            ->orderBy('tanggal', 'asc')
            ->get();

        $plans = $plansQuery
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPlans = $plans->count();

        $completedPlanIds = $activities
            ->whereNotNull('plan_id')
            ->pluck('plan_id')
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->unique()
            ->values();

        $completedPlans = $plans
            ->filter(function ($plan) use ($completedPlanIds) {
                return ($plan->status ?? '') === 'Selesai'
                    || $completedPlanIds->contains((string) $plan->_id);
            })
            ->count();

        $unfulfilledPlans = $plans
            ->filter(function ($plan) use ($completedPlanIds) {
                $status = $plan->status ?? 'Belum dimulai';

                return $status !== 'Selesai'
                    && !$completedPlanIds->contains((string) $plan->_id);
            })
            ->values();

        $unplannedActivities = $activities
            ->filter(fn ($activity) => empty($activity->plan_id))
            ->values();

        $latePlans = $plans
            ->filter(fn ($plan) => ($plan->status ?? '') === 'Terlambat')
            ->count();

        $inProgressPlans = $plans
            ->filter(fn ($plan) => ($plan->status ?? '') === 'Sedang Dikerjakan')
            ->count();

        $notFinishedPlans = $plans
            ->filter(function ($plan) use ($completedPlanIds) {
                $status = $plan->status ?? 'Belum dimulai';

                return !in_array($status, ['Selesai', 'Terlambat', 'Sedang Dikerjakan'])
                    && !$completedPlanIds->contains((string) $plan->_id);
            })
            ->count();

        $statusDistribution = [
            'Selesai' => $completedPlans,
            'Belum Selesai' => $notFinishedPlans,
            'Terlambat' => $latePlans,
            'Sedang Dikerjakan' => $inProgressPlans,
        ];

        $complianceRate = $totalPlans > 0
            ? round(($completedPlans / $totalPlans) * 100)
            : 0;

        $totalDuration = $activities->sum(function ($activity) {
            return (float) ($activity->durasi ?? 0);
        });

        $averageDuration = $activities->count() > 0
            ? round($totalDuration / $activities->count(), 1)
            : 0;

        $categoryDistribution = $activities
            ->groupBy('kategori')
            ->map(fn ($group) => $group->count())
            ->toArray();

        $topCategory = count($categoryDistribution) > 0
            ? array_keys($categoryDistribution, max($categoryDistribution))[0]
            : '-';

        $planComparison = [
            'rencana' => $totalPlans,
            'terlaksana' => $completedPlans,
        ];

        /*
            Ini khusus buat grafik mingguan:
            Durasi Rencana vs Durasi Aktual per hari.
        */
        $dailyComparison = [
            'labels' => [],
            'planned' => [],
            'actual' => [],
        ];

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $dateString = $start->format('Y-m-d');

            $dailyComparison['labels'][] = $start->locale('id')->translatedFormat('D');

            $plannedDuration = $plans
                ->where('tanggal', $dateString)
                ->sum(function ($plan) {
                    if (!empty($plan->jam_mulai) && !empty($plan->jam_selesai)) {
                        $startTime = Carbon::parse($plan->jam_mulai);
                        $endTime = Carbon::parse($plan->jam_selesai);

                        return round($startTime->diffInMinutes($endTime) / 60, 1);
                    }

                    return 0;
                });

            $actualDuration = $activities
                ->where('tanggal', $dateString)
                ->sum(function ($activity) {
                    return (float) ($activity->durasi ?? 0);
                });

            $dailyComparison['planned'][] = $plannedDuration;
            $dailyComparison['actual'][] = $actualDuration;

            $start->addDay();
        }

        return view('activities.recap', compact(
            'activities',
            'plans',
            'startDate',
            'endDate',
            'selectedCategory',
            'selectedStatus',
            'selectedPeriod',
            'completedPlans',
            'complianceRate',
            'averageDuration',
            'topCategory',
            'categoryDistribution',
            'planComparison',
            'statusDistribution',
            'unfulfilledPlans',
            'unplannedActivities',
            'dailyComparison'
        ));
    }
}