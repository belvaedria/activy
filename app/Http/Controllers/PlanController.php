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
        $selectedDate = $request->tanggal
            ?? now()->format('Y-m-d');

        $currentDate = Carbon::parse($selectedDate);
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;

        $daysInMonth = $currentDate->daysInMonth;

        $firstDayOfMonth = Carbon::create(
            $currentYear,
            $currentMonth,
            1
        )->dayOfWeek;

        $monthName = $currentDate->translatedFormat('F');

        $plans = Plan::where(
            'user_id',
            Auth::id()
        )
        ->where(
            'tanggal',
            $selectedDate
        )
        ->orderBy('jam_mulai')
        ->get();

        return view(
            'plans.index',
            compact(
                'plans',
                'selectedDate',
                'currentMonth',
                'currentYear',
                'daysInMonth',
                'firstDayOfMonth',
                'monthName'
            )
        );
    }

    /*
    public function recap()
    {
        $plans = Plan::where(
            'user_id',
            Auth::id()
        )
        ->orderBy('tanggal', 'desc')
        ->get();

        return view('plans.recap', compact('plans'));
    }
    */

    public function destroy($id)
    {
        $plan = Plan::where('_id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Rencana berhasil dihapus!');
    }

    public function create()
    {
        return view('plans.create');
    }

    // POST /api/plans (Tambah Rencana)
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_rencana' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $validated['status'] = 'pending';
        
        // Simpan ke MongoDB
        $validated['user_id'] = Auth::id();

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Rencana berhasil ditambahkan!');
    }

    // Menampilkan form edit dengan data lama
    public function edit($id)
    {
        $plan = Plan::where('_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        return view('plans.edit', compact('plan'));
    }

    // Memproses perubahan data
    public function update(Request $request, $id)
    {
        // Validasi input sama seperti saat tambah data
        $validated = $request->validate([
            'nama_rencana' => 'required|string',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $plan = Plan::where('_id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Rencana berhasil diperbarui!');
    }
}
