<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        return view('admin.teachers.index', [
            'teachers' => Teacher::query()
                ->with(['user', 'classSubjects.subject', 'classSubjects.schoolClass'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.teachers.form', [
            'teacher' => null,
            'statuses' => ['active' => 'Aktif', 'inactive' => 'Nonaktif'],
        ]);
    }

    public function store(TeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $request->filled('password') ? $request->input('password') : 'password123',
            'role' => User::ROLE_TEACHER,
            'email_verified_at' => now(),
        ]);

        unset($data['name'], $data['email'], $data['password'], $data['password_confirmation']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storeSanitizedFile($request->file('photo'), 'photos/teachers');
        }

        $user->teacher()->create($data);

        return redirect()->route('portal.admin.teachers.index')->with('status', 'Guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers.form', [
            'teacher' => $teacher->load('user'),
            'statuses' => ['active' => 'Aktif', 'inactive' => 'Nonaktif'],
        ]);
    }

    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validated();

        $teacher->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $request->filled('password') ? $request->input('password') : $teacher->user->password,
        ]);

        unset($data['name'], $data['email'], $data['password'], $data['password_confirmation']);

        if ($request->hasFile('photo')) {
            $this->deleteStoredFile($teacher->photo);
            $data['photo'] = $this->storeSanitizedFile($request->file('photo'), 'photos/teachers');
        }

        $teacher->update($data);

        return redirect()->route('portal.admin.teachers.index')->with('status', 'Data guru diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->deleteStoredFile($teacher->photo);
        $teacher->user->delete();

        return redirect()->route('portal.admin.teachers.index')->with('status', 'Guru telah dihapus.');
    }
}
