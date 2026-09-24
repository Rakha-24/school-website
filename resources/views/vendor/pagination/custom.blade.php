@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex items-center justify-between gap-4">
        <p class="text-xs text-ink-500">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-line px-2 text-sm text-ink-300">
                    <x-icon name="chevron-left" class="size-4" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"
                   class="inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-line bg-white px-2 text-sm text-ink-700 transition-colors hover:border-brand-500 hover:text-brand-800">
                    <x-icon name="chevron-left" class="size-4" />
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-8 items-center px-1 text-sm text-ink-300">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-8 min-w-8 items-center justify-center rounded-md bg-brand-900 px-2 text-sm font-semibold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Halaman {{ $page }}"
                               class="inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-line bg-white px-2 text-sm text-ink-700 transition-colors hover:border-brand-500 hover:text-brand-800">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"
                   class="inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-line bg-white px-2 text-sm text-ink-700 transition-colors hover:border-brand-500 hover:text-brand-800">
                    <x-icon name="chevron-right" class="size-4" />
                </a>
            @else
                <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-line px-2 text-sm text-ink-300">
                    <x-icon name="chevron-right" class="size-4" />
                </span>
            @endif
        </div>
    </nav>
@endif