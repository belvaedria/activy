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

        $activitiesQuery = Activity::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startDate, $endDate]);

        $plansQuery = Plan::where('user_id', Auth::id())
            ->whereBetween('tanggal', [$startDate, $endDate]);

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
                return $completedPlanIds->contains((string) $plan->_id);
            })
            ->count();

        $unfulfilledPlans = $plans
            ->filter(function ($plan) use ($completedPlanIds) {
                $status = $plan->status ?? 'Belum dimulai';

                return $status !== 'Selesai'
                    && !$completedPlanIds->contains((string) $plan->_id);
            })
            ->values();

        $attentionPlans = $plans
            ->sortByDesc(function ($plan) {
                return (int) ($plan->keterlambatan_menit ?? 0);
            })
            ->take(5)
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

        $totalLateMinutes = $plans->sum(function ($plan) {
            return (int) ($plan->keterlambatan_menit ?? 0);
        });

        $complianceRate =
            $totalPlans > 0
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
            ->map(function ($group) {

                return round(
                    $group->sum(function ($activity) {
                        return (float) ($activity->durasi ?? 0);
                    }),
                    1
                );
            })
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

        $complianceTrend = [
            'labels' => [],
            'values' => [],
        ];

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $dateRangeDays =
            $start->diffInDays($end) + 1;

        $aggregationMode = 'daily';

        if ($dateRangeDays > 60) {

            $aggregationMode = 'monthly';

        } elseif ($dateRangeDays > 14) {

            $aggregationMode = 'weekly';
        }

        $planRealizationTrend = [
            'labels' => [],
            'planned' => [],
            'realized' => [],
        ];

        $adaptiveComplianceTrend = [
            'labels' => [],
            'values' => [],
        ];

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

            // =========================
            // Kepatuhan Harian
            // =========================

            $dayPlans = $plans->where('tanggal', $dateString);

            $realizedPlans = $dayPlans
                ->filter(function ($plan) use ($completedPlanIds) {
                    return $completedPlanIds->contains((string) $plan->_id);
                })
                ->count();

            $dailyCompliance =
                $dayPlans->count() > 0
                    ? round(($realizedPlans / $dayPlans->count()) * 100, 1)
                    : 0;

            $complianceTrend['labels'][] =
                Carbon::parse($dateString)
                    ->locale('id')
                    ->translatedFormat('d M');

            $complianceTrend['values'][] =
                $dailyCompliance;

            $dailyComparison['planned'][] = $plannedDuration;
            $dailyComparison['actual'][] = $actualDuration;

            $start->addDay();
        }

        if ($aggregationMode === 'daily') {

            $cursor = Carbon::parse($startDate);

            while ($cursor->lte($end)) {

                $dateString = $cursor->format('Y-m-d');

                $dayPlans =
                    $plans->where('tanggal', $dateString);

                $dayActivities =
                    $activities->where('tanggal', $dateString);

                $planRealizationTrend['labels'][] =
                    $cursor->locale('id')
                        ->translatedFormat('d M');

                $planRealizationTrend['planned'][] =
                    $dayPlans->count();

                $planRealizationTrend['realized'][] =
                    $dayActivities
                        ->whereNotNull('plan_id')
                        ->count();

                $cursor->addDay();
            }

            $adaptiveComplianceTrend =
                $complianceTrend;
        } elseif ($aggregationMode === 'weekly') {

            $cursor = Carbon::parse($startDate);

            while ($cursor->lte($end)) {

                $weekStart = $cursor->copy();

                $weekEnd = $cursor->copy()->addDays(6);

                if ($weekEnd->gt($end)) {
                    $weekEnd = $end->copy();
                }

                $label =
                    $weekStart->format('d M')
                    . ' - '
                    . $weekEnd->format('d M');

                $weekPlans = $plans
                    ->filter(function ($plan) use ($weekStart, $weekEnd) {

                        $date = Carbon::parse($plan->tanggal);

                        return $date->between(
                            $weekStart,
                            $weekEnd
                        );
                    });

                $weekActivities = $activities
                    ->filter(function ($activity) use ($weekStart, $weekEnd) {

                        $date = Carbon::parse($activity->tanggal);

                        return $date->between(
                            $weekStart,
                            $weekEnd
                        );
                    });

                $planRealizationTrend['labels'][] =
                    $label;

                $planRealizationTrend['planned'][] =
                    $weekPlans->count();

                $planRealizationTrend['realized'][] =
                    $weekActivities
                        ->whereNotNull('plan_id')
                        ->count();

                $realizedPlans = $weekPlans
                    ->filter(function ($plan) use ($completedPlanIds) {
                        return $completedPlanIds->contains((string) $plan->_id);
                    })
                    ->count();

                $compliance =
                    $weekPlans->count() > 0
                        ? round(($realizedPlans / $weekPlans->count()) * 100, 1)
                        : 0;

                $adaptiveComplianceTrend['labels'][] =
                    $label;

                $adaptiveComplianceTrend['values'][] =
                    $compliance;

                $cursor->addWeek();
            }
        }

        $bestDay = '-';
        $worstDay = '-';

        if (!empty($complianceTrend['values'])) {

            $bestIndex = array_keys(
                $complianceTrend['values'],
                max($complianceTrend['values'])
            )[0];

            $worstIndex = array_keys(
                $complianceTrend['values'],
                min($complianceTrend['values'])
            )[0];

            $bestDay =
                $complianceTrend['labels'][$bestIndex] ?? '-';

            $worstDay =
                $complianceTrend['labels'][$worstIndex] ?? '-';
        }

        return view('activities.recap', compact(
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
            'dailyComparison',
            'totalLateMinutes',
            'complianceTrend',
            'attentionPlans',
            'bestDay',
            'worstDay',
            'totalDuration',
            'planRealizationTrend',
            'adaptiveComplianceTrend',
            'latePlans',
            'inProgressPlans',
            'notFinishedPlans',

        ));
    }
}
