<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassSubject;
use App\Models\ContactMessage;
use App\Models\Material;
use App\Models\News;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Submission;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $counts = [
            'students' => Student::query()->where('status', 'active')->count(),
            'teachers' => Teacher::query()->where('status', 'active')->count(),
            'classes' => SchoolClass::query()->where('status', 'active')->count(),
            'users' => User::query()->count(),
            'materials' => Material::query()->where('status', 'published')->count(),
            'assignments' => Assignment::query()->where('status', 'published')->count(),
            'submissions' => Submission::query()->count(),
            'messages' => ContactMessage::query()->where('is_read', false)->count(),
        ];

        $recentNews = News::query()->with('author')->latest('published_at')->take(5)->get();

        $unreadMessages = ContactMessage::query()->where('is_read', false)->latest()->take(5)->get();

        $todayAttendance = Attendance::query()->whereDate('date', today())->count();

        $assignmentSummary = Assignment::query()
            ->with(['classSubject.subject', 'classSubject.schoolClass'])
            ->withCount(['submissions as submitted_count', 'submissions as pending_count' => fn ($q) => $q->whereNull('score')])
            ->latest('due_at')
            ->take(5)
            ->get();

        $classDistribution = SchoolClass::query()
            ->where('status', 'active')
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', [
            'counts' => $counts,
            'recentNews' => $recentNews,
            'unreadMessages' => $unreadMessages,
            'todayAttendance' => $todayAttendance,
            'assignmentSummary' => $assignmentSummary,
            'classDistribution' => $classDistribution,
            'classSubjectCount' => ClassSubject::query()->count(),
        ]);
    }
}
