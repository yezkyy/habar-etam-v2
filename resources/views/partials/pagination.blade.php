@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-200/80 pt-6 pb-2 mt-10">
        
        <!-- Mobile Simple Navigation -->
        <div class="flex items-center justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl border border-gray-200 bg-gray-100 text-xs font-semibold text-gray-400 cursor-not-allowed">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-xs font-bold text-gray-800 hover:bg-amber-50 hover:border-brand-gold/60 transition-colors shadow-2xs">
                    &larr; Sebelumnya
                </a>
            @endif

            <span class="text-xs font-bold text-gray-600">
                Hal {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-xs font-bold text-gray-800 hover:bg-amber-50 hover:border-brand-gold/60 transition-colors shadow-2xs">
                    Berikutnya &rarr;
                </a>
            @else
                <span class="px-4 py-2 rounded-xl border border-gray-200 bg-gray-100 text-xs font-semibold text-gray-400 cursor-not-allowed">
                    Berikutnya &rarr;
                </span>
            @endif
        </div>

        <!-- Desktop Rich Pagination Navigation -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-xs text-gray-500 font-medium">
                    Menampilkan <strong class="text-brand-black font-bold">{{ $paginator->firstItem() }}</strong> - <strong class="text-brand-black font-bold">{{ $paginator->lastItem() }}</strong> dari <strong class="text-brand-black font-bold">{{ $paginator->total() }}</strong> total data
                </p>
            </div>

            <div>
                <div class="inline-flex items-center gap-1.5">
                    {{-- Previous Page Button --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Halaman Sebelumnya" class="w-9 h-9 rounded-xl border border-gray-200 bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman Sebelumnya" class="w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-700 hover:text-black hover:border-brand-gold hover:bg-amber-50 flex items-center justify-center transition-colors shadow-2xs">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true" class="w-9 h-9 rounded-xl text-xs font-bold text-gray-400 flex items-center justify-center select-none">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="w-9 h-9 rounded-xl bg-gradient-to-r from-gray-950 via-brand-black to-stone-900 text-brand-gold border border-brand-black font-black text-xs flex items-center justify-center shadow-md ring-2 ring-brand-gold/30 scale-105">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-700 hover:text-black hover:border-brand-gold hover:bg-amber-50 font-bold text-xs flex items-center justify-center transition-all shadow-2xs">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Button --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman Berikutnya" class="w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-700 hover:text-black hover:border-brand-gold hover:bg-amber-50 flex items-center justify-center transition-colors shadow-2xs">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Halaman Berikutnya" class="w-9 h-9 rounded-xl border border-gray-200 bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif

