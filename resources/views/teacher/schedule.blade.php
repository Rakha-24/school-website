<x-layouts.portal :title="'Jadwal Mengajar'" :role="'teacher'">
    <x-page-header title="Jadwal mengajar" description="Rincian jadwal mengajar Anda untuk satu pekan berjalan." />

    @php($todayNum = now()->isoWeekday())
    @php($defaultDay = in_array($todayNum, $week->pluck('number')->all()) ? $todayNum : 1)

    <div x-data="{ day: {{ $defaultDay }} }">
        <div class="flex gap-1.5 overflow-x-auto no-scrollbar" role="tablist" aria-label="Hari jadwal">
            @foreach ($week as $day)
                <button type="button" role="tab" @click="day = {{ $day['number'] }}"
                    :class="day === {{ $day['number'] }} ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800'"
                    class="shrink-0 rounded-md border px-4 py-2 text-left transition-colors">
                    <span class="block text-xs font-semibold uppercase tracking-wide opacity-70">{{ $day['label'] }}</span>
                    <span class="block text-sm font-semibold">{{ $day['date']?->translatedFormat('d M') }}</span>
                </button>
            @endforeach
        </div>

        <div class="mt-6 space-y-6">
            @foreach ($week as $day)
                <section class="panel" x-show="day === {{ $day['number'] }}" x-cloak>
                    <div class="panel-head">
                        <div>
                            <p class="eyebrow">Hari {{ $day['label'] }}</p>
                            <h2 class="font-display text-lg font-semibold text-brand-950">{{ $day['date']?->translatedFormat('d F Y') }}</h2>
                        </div>
                        <span class="badge badge-brand">{{ $day['items']->count() }} sesi</span>
                    </div>
                    <div class="divide-y divide-line">
                        @forelse ($day['items'] as $item)
                            <div class="flex flex-wrap items-center gap-4 px-5 py-3.5">
                                <span class="shrink-0 rounded-md bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-800">
                                    {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-brand-950">{{ $item->subject?->name }}</p>
                                    <p class="truncate text-xs text-ink-soft">{{ $item->schoolClass?->name }}{{ $item->room ? ' · Ruang '.$item->room : '' }}</p>
                                </div>
                                <span class="badge badge-brand">{{ $item->schoolClass?->name }}</span>
                            </div>
                        @empty
                            <div class="px-5 py-10">
                                <x-empty-state title="Tidak ada jadwal" :description="'Tidak ada jam mengajar pada hari '.$day['label'].'.'" icon="calendar" />
                            </div>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-layouts.portal>