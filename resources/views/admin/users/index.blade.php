<x-layouts.portal :title="'Manajemen Pengguna'" :role="'admin'">
    <x-page-header title="Pengguna" description="Akun portal untuk administrator, guru, dan siswa."
        :actions="[['label' => 'Tambah pengguna', 'route' => 'portal.admin.users.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    <section class="panel">
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <p class="font-medium text-brand-950">{{ $user->name }}</p>
                                <p class="text-xs text-ink-soft">{{ $user->email }}</p>
                            </td>
                            <td>
                                @php
                                    $roleBadge = match ($user->role) {
                                        'admin' => 'badge-accent',
                                        'teacher' => 'badge-brand',
                                        default => 'badge-slate',
                                    };
                                @endphp
                                <span class="badge {{ $roleBadge }}">{{ $user->roleLabel() }}</span>
                            </td>
                            <td>
                                @php
                                    $profileStatus = $user->student->status ?? $user->teacher->status ?? 'active';
                                @endphp
                                @if ($profileStatus === 'inactive')
                                    <span class="badge badge-slate">Nonaktif</span>
                                @elseif ($profileStatus === 'graduate')
                                    <span class="badge badge-amber">Lulus</span>
                                @elseif ($profileStatus === 'dropped')
                                    <span class="badge badge-red">Keluar</span>
                                @else
                                    <span class="badge badge-green">Aktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.users.edit', $user) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('portal.admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini beserta data terkait?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50">
                                                <x-icon name="trash" class="size-4" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-empty-state title="Belum ada pengguna" description="Buat akun pengguna pertama lewat tombol Tambah pengguna." icon="users" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $users->links() }}
        </div>
    </section>
</x-layouts.portal>