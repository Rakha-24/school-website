<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        return view('admin.students.index', [
            'students' => Student::query()
                ->with(['user', 'schoolClass'])
                ->latest()
                ->paginate(20),
            'statuses' => ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'graduate' => 'Lulus', 'dropped' => 'Keluar'],
        ]);
    }

    public function create(): View
    {
        return view('admin.students.form', [
            'student' => null,
            'classes' => SchoolClass::query()->where('status', 'active')->orderBy('name')->get(),
            'statuses' => ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'graduate' => 'Lulus', 'dropped' => 'Keluar'],
        ]);
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $request->filled('password') ? $request->input('password') : 'password123',
            'role' => User::ROLE_STUDENT,
            'email_verified_at' => now(),
        ]);

        unset($data['name'], $data['email'], $data['password'], $data['password_confirmation']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storeSanitizedFile($request->file('photo'), 'photos/students');
        }

        $user->student()->create($data);

        return redirect()->route('portal.admin.students.index')->with('status', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Student $student): View
    {
        return view('admin.students.form', [
            'student' => $student->load('user'),
            'classes' => SchoolClass::query()->where('status', 'active')->orderBy('name')->get(),
            'statuses' => ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'graduate' => 'Lulus', 'dropped' => 'Keluar'],
        ]);
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $data = $request->validated();

        $student->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $request->filled('password') ? $request->input('password') : $student->user->password,
        ]);

        unset($data['name'], $data['email'], $data['password'], $data['password_confirmation']);

        if ($request->hasFile('photo')) {
            $this->deleteStoredFile($student->photo);
            $data['photo'] = $this->storeSanitizedFile($request->file('photo'), 'photos/students');
        }

        $student->update($data);

        return redirect()->route('portal.admin.students.index')->with('status', 'Data siswa diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->deleteStoredFile($student->photo);
        $student->user->delete();

        return redirect()->route('portal.admin.students.index')->with('status', 'Siswa telah dihapus.');
    }
}
