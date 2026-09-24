<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $student = Auth::user()->student;
        $year = max(1, (int) $request->query('year', now()->year));
        $month = max(1, min(12, (int) $request->query('month', now()->month)));

        $records = $student->attendanceRecords()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        $summary = $student->attendanceRecords()
            ->whereYear('date', Carbon::now()->year)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('student.attendance', [
            'records' => $records,
            'summary' => $summary,
            'statuses' => Attendance::STATUSES,
            'year' => $year,
            'month' => $month,
            'months' => collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create()->month($m)->translatedFormat('F')]),
            'years' => range(now()->year - 3, now()->year + 1),
        ]);
    }
}
