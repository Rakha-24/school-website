<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;
        $class = $student->schoolClass;

        $week = collect(range(1, 5))->map(fn ($day) => [
            'number' => $day,
            'label' => Schedule::DAYS[$day],
            'date' => Carbon::now()->isoWeekday($day),
            'items' => $class
                ? $class->schedules()
                    ->with(['subject', 'teacher.user'])
                    ->where('day', $day)
                    ->orderBy('start_time')
                    ->get()
                : collect(),
        ]);

        return view('student.schedule', [
            'week' => $week,
            'class' => $class,
        ]);
    }
}
