<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()
                ->with(['student', 'teacher'])
                ->latest()
                ->paginate(15),
            'roles' => User::ROLES,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'user' => null,
            'roles' => User::ROLES,
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = $request->filled('password') ? $request->input('password') : 'password123';
        $data['email_verified_at'] = now();

        $user = User::query()->create($data);

        if ($user->role === User::ROLE_STUDENT) {
            $user->student()->create([
                'student_number' => 'TEMP'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
                'enrollment_year' => now()->year,
                'status' => 'active',
            ]);
        }

        if ($user->role === User::ROLE_TEACHER) {
            $user->teacher()->create([
                'status' => 'active',
            ]);
        }

        return redirect()->route('portal.admin.users.index')->with('status', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->except(['password', 'email_verified_at', 'status']);
        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $user->update($data);

        if ($request->input('status') === 'inactive') {
            $user->update(['remember_token' => null]);
        }

        return redirect()->route('portal.admin.users.index')->with('status', 'Data pengguna diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'Anda tidak dapat menghapus akun sendiri.');

        $user->delete();

        return redirect()->route('portal.admin.users.index')->with('status', 'Pengguna telah dihapus.');
    }
}
