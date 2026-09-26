<?php

use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\ClassSubjectController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\AcademicController;
use App\Http\Controllers\Public\AchievementController;
use App\Http\Controllers\Public\AdmissionController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\ExtracurricularController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\ProfileController as PublicProfileController;
use App\Http\Controllers\Student\AnnouncementController;
use App\Http\Controllers\Student\AssignmentController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\GradeController;
use App\Http\Controllers\Student\MaterialController;
use App\Http\Controllers\Student\ScheduleController;
use App\Http\Controllers\Teacher\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Situs publik sekolah
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'show'])->name('home');
Route::get('/about', [PublicProfileController::class, 'show'])->name('about.show');
Route::get('/academic', [AcademicController::class, 'show'])->name('academic.show');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
Route::get('/extracurriculars', [ExtracurricularController::class, 'index'])->name('extracurriculars.index');
Route::get('/extracurriculars/{extracurricular:slug}', [ExtracurricularController::class, 'show'])->name('extracurriculars.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/admission', [AdmissionController::class, 'show'])->name('admission.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/*
|--------------------------------------------------------------------------
| Portal — area yang membutuhkan login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/portal', [HomeController::class, 'portal'])->name('portal.home');

    Route::get('/portal/notifications', [NotificationController::class, 'index'])->name('portal.notifications');
    Route::post('/portal/notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('portal.notifications.read');

    Route::get('/portal/profile', [ProfileController::class, 'edit'])->name('portal.profile');
    Route::patch('/portal/profile', [ProfileController::class, 'update'])->name('portal.profile.update');
    Route::delete('/portal/profile', [ProfileController::class, 'destroy'])->name('portal.profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Portal Siswa
|--------------------------------------------------------------------------
*/

Route::prefix('portal/student')->name('portal.student.')->middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->whereNumber('material')->name('materials.show');

    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->whereNumber('assignment')->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->whereNumber('assignment')->name('assignments.submit');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->whereNumber('announcement')->name('announcements.show');
});

/*
|--------------------------------------------------------------------------
| Portal Guru
|--------------------------------------------------------------------------
*/

Route::prefix('portal/teacher')->name('portal.teacher.')->middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/materials', [App\Http\Controllers\Teacher\MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [App\Http\Controllers\Teacher\MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [App\Http\Controllers\Teacher\MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{material}', [App\Http\Controllers\Teacher\MaterialController::class, 'show'])->whereNumber('material')->name('materials.show');
    Route::get('/materials/{material}/edit', [App\Http\Controllers\Teacher\MaterialController::class, 'edit'])->whereNumber('material')->name('materials.edit');
    Route::put('/materials/{material}', [App\Http\Controllers\Teacher\MaterialController::class, 'update'])->whereNumber('material')->name('materials.update');
    Route::delete('/materials/{material}', [App\Http\Controllers\Teacher\MaterialController::class, 'destroy'])->whereNumber('material')->name('materials.destroy');

    Route::get('/assignments', [App\Http\Controllers\Teacher\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [App\Http\Controllers\Teacher\AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [App\Http\Controllers\Teacher\AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'show'])->whereNumber('assignment')->name('assignments.show');
    Route::get('/assignments/{assignment}/edit', [App\Http\Controllers\Teacher\AssignmentController::class, 'edit'])->whereNumber('assignment')->name('assignments.edit');
    Route::put('/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'update'])->whereNumber('assignment')->name('assignments.update');
    Route::delete('/assignments/{assignment}', [App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])->whereNumber('assignment')->name('assignments.destroy');

    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/assignment/{assignment}', [SubmissionController::class, 'show'])->whereNumber('assignment')->name('submissions.assignment');
    Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->whereNumber('submission')->name('submissions.grade');

    Route::get('/attendance', [App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/roster', [App\Http\Controllers\Teacher\AttendanceController::class, 'roster'])->name('attendance.roster');
    Route::post('/attendance', [App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/schedule', [App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('schedule.index');

    Route::get('/announcements', [App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [App\Http\Controllers\Teacher\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [App\Http\Controllers\Teacher\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Teacher\AnnouncementController::class, 'show'])->whereNumber('announcement')->name('announcements.show');
});

/*
|--------------------------------------------------------------------------
| Portal Admin
|--------------------------------------------------------------------------
*/

Route::prefix('portal/admin')->name('portal.admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Pesan masuk
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->whereNumber('message')->name('messages.show');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->whereNumber('message')->name('messages.destroy');

    // Pengguna, guru, siswa, kelas, mapel
    Route::resource('users', UserController::class)->except(['show'])->whereNumber('user');
    Route::resource('teachers', TeacherController::class)->except(['show'])->whereNumber('teacher');
    Route::resource('students', StudentController::class)->except(['show'])->whereNumber('student');
    Route::resource('classes', ClassController::class)->except(['show'])->whereNumber('class');
    Route::resource('subjects', SubjectController::class)->except(['show'])->whereNumber('subject');
    Route::resource('class-subjects', ClassSubjectController::class)->except(['show'])->whereNumber('classSubject');
    Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class)->except(['show'])->whereNumber('schedule');
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class)->except(['show'])->whereNumber('announcement');

    // Presensi
    Route::get('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');

    // Konten publik
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class)->except(['show'])->whereNumber('news');
    Route::resource('achievements', App\Http\Controllers\Admin\AchievementController::class)->except(['show'])->whereNumber('achievement');
    Route::resource('extracurriculars', App\Http\Controllers\Admin\ExtracurricularController::class)->except(['show'])->whereNumber('extracurricular');
    Route::resource('gallery', GalleryItemController::class)->except(['show'])->whereNumber('gallery');
    Route::resource('admission', App\Http\Controllers\Admin\AdmissionController::class)->except(['show'])->whereNumber('admission');
    Route::resource('faqs', FaqController::class)->except(['show'])->whereNumber('faq');

    // Pengaturan
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
