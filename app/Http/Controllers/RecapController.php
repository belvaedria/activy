<?php

namespace App\Http\Controllers;
use App\Models\Activity;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function index()
    {
        $startDate =
            request('start_date')
            ?? Carbon::now()->startOfMonth()->toDateString();

        $endDate =
            request('end_date')
            ?? Carbon::now()->endOfMonth()->toDateString();

        // Aktivitas dalam periode
        $activities = Activity::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        // Rencana dalam periode
        $plans = Plan::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $completedPlans = $activities
        ->whereNotNull('plan_id')
        ->pluck('plan_id')
        ->unique()
        ->count();

        $complianceRate =
            $plans->count() > 0
            ? round(($completedPlans / $plans->count()) * 100)
            : 0;

        $averageDuration =
            $activities->count() > 0
            ? round(
                $activities->avg('durasi'),
                1
            )
            : 0;

        $topCategory =
            $activities
                ->groupBy('kategori')
                ->sortByDesc(fn ($group) => $group->count())
                ->keys()
                ->first()
            ?? '-';

        $categoryDistribution =
            $activities
                ->groupBy('kategori')
                ->map(fn ($group) => $group->count())
                ->toArray();

        $planComparison = [
            'rencana' => $plans->count(),
            'terlaksana' => $completedPlans,
        ];

        $completedPlanIds =
            $activities
                ->whereNotNull('plan_id')
                ->pluck('plan_id')
                ->unique();

        $unfulfilledPlans =
            $plans
                ->whereNotIn('_id', $completedPlanIds);

        $unplannedActivities =
            $activities
                ->whereNull('plan_id');

        return view(
            'activities.recap',
            compact(
                'activities',
                'plans',
                'startDate',
                'endDate',
                'completedPlans',
                'complianceRate',
                'averageDuration',
                'topCategory',
                'categoryDistribution',
                'planComparison',

                'unfulfilledPlans',
                'unplannedActivities',
            )
        );
    }
}