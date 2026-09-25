@if ($paginator->hasPages())
    <div class="p-4 sm:p-5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Result Counter -->
        <div class="text-xs text-gray-500 flex items-center gap-1.5">
            <span>Menampilkan</span>
            <span class="font-bold text-gray-900 font-mono">{{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }}</span>
            <span>dari total</span>
            <span class="font-bold text-gray-900 font-mono">{{ number_format($paginator->total()) }}</span>
            <span>data</span>
        </div>

        <!-- Pagination Controls -->
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1 bg-white p-1 rounded-2xl border border-gray-200/80 shadow-2xs">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 cursor-not-allowed select-none" aria-disabled="true" aria-label="Previous">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-700 hover:text-black hover:bg-gold-500 transition-all font-bold" aria-label="Previous">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-xs font-bold text-gray-400 select-none">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black bg-black text-gold-400 shadow-xs select-none" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-gray-700 hover:text-black hover:bg-gold-500 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-700 hover:text-black hover:bg-gold-500 transition-all font-bold" aria-label="Next">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-300 cursor-not-allowed select-none" aria-disabled="true" aria-label="Next">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
