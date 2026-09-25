@extends('layouts.public')

@section('title', 'Klub & Komunitas Warga Kukar — Habar Etam')
@section('meta_description', 'Direktori klub, paguyuban seni tradisi, komunitas hobi, olahraga, otomotif, dan relawan pemuda di Tenggarong & Kutai Kartanegara.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Editorial Community Hero Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-slate-900 to-teal-950 text-white rounded-3xl p-6 sm:p-10 mb-8 border border-teal-500/20 shadow-2xl relative overflow-hidden reveal-blur-spring">
        <!-- Subtle Glow & Mesh Orbs -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-teal-600/15 rounded-full blur-3xl pointer-events-none animate-ambient-glow"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-400/30 text-teal-300 text-xs font-bold uppercase tracking-wider mb-3.5 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Ruang Kolaborasi & Jejaring Warga • Kukar</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-md">
                    Klub, Komunitas & Paguyuban <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-emerald-400 to-amber-300">Kutai Kartanegara</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2.5 leading-relaxed font-normal max-w-xl">
                    Temukan ruang berkarya, sanggar seni budaya Kutai, perkumpulan hobi fotografi, klub olahraga, dan jejaring relawan muda kreatif di Tenggarong & sekitarnya.
                </p>

                <!-- Quick Stats Badges -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 mt-5 pt-1 text-xs">
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="users-2" class="w-3.5 h-3.5 text-teal-400"></i>
                        <span><strong>{{ $totalCommunities ?? $communities->total() }}</strong> Komunitas Terdaftar</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Titik Kumpul Tenggarong & Kukar</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Terbuka untuk Anggota Baru</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('communities.create') }}" class="btn-shimmer-effect px-6 py-3.5 rounded-2xl bg-brand-gold hover:bg-brand-gold-dark text-brand-black text-xs sm:text-sm font-black shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group active:scale-95">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-brand-black group-hover:rotate-90 transition-transform"></i>
                    <span>Daftarkan Komunitas</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Category Discovery Scroll Bar -->
    @php
        $categories = [
            ['name' => 'Semua Minat', 'value' => '', 'icon' => 'layout-grid'],
            ['name' => 'Seni & Budaya', 'value' => 'Seni & Budaya', 'icon' => 'landmark'],
            ['name' => 'Hobi & Kreatif', 'value' => 'Hobi & Kreatif', 'icon' => 'camera'],
            ['name' => 'Olahraga', 'value' => 'Olahraga', 'icon' => 'trophy'],
            ['name' => 'Sosial Kemanusiaan', 'value' => 'Sosial Kemanusiaan', 'icon' => 'users-2'],
        ];
        $currentCat = request('category', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Arrow -->
            <button type="button" id="comCatScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-teal-50 hover:border-teal-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Pills Container -->
            <div id="comCatScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($currentCat === $cat['value']) || (empty($currentCat) && empty($cat['value']));
                        $queryParam = request()->except(['category', 'page']);
                        if (!empty($cat['value'])) {
                            $queryParam['category'] = $cat['value'];
                        }
                        $catUrl = route('communities.index', $queryParam);
                        $count = !empty($cat['value']) ? ($categoryCounts[$cat['value']] ?? 0) : $totalCommunities;
                    @endphp
                    <a href="{{ $catUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-slate-900 to-teal-950 text-teal-300 border-teal-900 shadow-md ring-2 ring-teal-500/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-teal-400/70 hover:bg-teal-50/60 hover:text-teal-950 shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-teal-400/20 text-teal-300' : 'bg-gray-100 group-hover/item:bg-teal-100 text-gray-600 group-hover/item:text-teal-900' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $cat['name'] }}</span>
                        @if($count > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isActive ? 'bg-teal-400/20 text-teal-300 border border-teal-400/30' : 'bg-gray-100 text-gray-500' }}">
                                {{ $count }}
                            </span>
                        @endif
                        @if($isActive && !empty($cat['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Arrow -->
            <button type="button" id="comCatScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-teal-50 hover:border-teal-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="relative z-30 bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 mb-8 shadow-subtle reveal-blur-spring">
        <form method="GET" action="{{ route('communities.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <!-- Keyword Search -->
            <div class="sm:col-span-6">
                <label class="form-label mb-1.5 text-xs text-gray-600 font-bold flex items-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>Cari Nama Komunitas / Lokasi / Pengurus</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Ketik fotografi, tari, gowes, tepian, IT..." 
                           class="form-input text-xs pl-3.5 pr-8 py-2.5 rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500/20">
                    @if(request('q'))
                        <a href="{{ route('communities.index', request()->except('q')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-4">
                <label class="form-label mb-1.5 text-xs text-gray-600 font-bold flex items-center gap-1.5">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>Bidang Minat</span>
                </label>
                <select name="category" class="form-input text-xs py-2.5 rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500/20">
                    <option value="" data-icon="layers">Semua Bidang Minat</option>
                    <option value="Seni & Budaya" data-icon="landmark" {{ request('category') == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                    <option value="Hobi & Kreatif" data-icon="camera" {{ request('category') == 'Hobi & Kreatif' ? 'selected' : '' }}>Hobi & Kreatif</option>
                    <option value="Olahraga" data-icon="trophy" {{ request('category') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="Sosial Kemanusiaan" data-icon="users-2" {{ request('category') == 'Sosial Kemanusiaan' ? 'selected' : '' }}>Sosial Kemanusiaan</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="btn-black flex-1 py-2.5 px-4 text-xs font-bold rounded-xl shadow-xs hover:bg-gray-800 transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
                @if(request()->anyFilled(['q', 'category']))
                    <a href="{{ route('communities.index') }}" class="btn-outline py-2.5 px-3 text-xs rounded-xl border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-colors" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-gray-600"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Section Heading -->
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg sm:text-xl font-black text-brand-black tracking-tight flex items-center gap-2">
                <span>Daftar Komunitas Warga</span>
                @if(request('category'))
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800">
                        {{ request('category') }}
                    </span>
                @endif
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Menampilkan {{ $communities->total() }} perkumpulan dan klub aktif di Kutai Kartanegara.</p>
        </div>
    </div>

    <!-- Communities Grid -->
    @if($communities->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200/80 p-12 text-center shadow-subtle my-6 reveal-blur-spring">
            <div class="w-16 h-16 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 mx-auto mb-4">
                <i data-lucide="users-2" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-brand-black">Belum Ada Komunitas yang Sesuai</h3>
            <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                Tidak ada komunitas yang cocok dengan kata kunci atau filter pencarian Anda. Daftarkan komunitas atau paguyuban Anda sekarang.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                @if(request()->anyFilled(['q', 'category']))
                    <a href="{{ route('communities.index') }}" class="btn-outline text-xs py-2 px-4 rounded-xl font-bold">
                        Reset Filter
                    </a>
                @endif
                <a href="{{ route('communities.create') }}" class="btn-gold text-xs py-2 px-5 rounded-xl font-bold shadow-sm">
                    + Daftarkan Komunitas Baru
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($communities as $com)
                @php
                    // Category Palette
                    $catColors = [
                        'Seni & Budaya' => ['badge' => 'bg-amber-100 text-amber-900 border-amber-300/60', 'cover' => 'from-amber-600 to-amber-950', 'icon' => 'landmark'],
                        'Hobi & Kreatif' => ['badge' => 'bg-indigo-100 text-indigo-900 border-indigo-300/60', 'cover' => 'from-indigo-600 to-slate-950', 'icon' => 'camera'],
                        'Olahraga' => ['badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300/60', 'cover' => 'from-emerald-600 to-teal-950', 'icon' => 'trophy'],
                        'Sosial Kemanusiaan' => ['badge' => 'bg-rose-100 text-rose-900 border-rose-300/60', 'cover' => 'from-rose-600 to-slate-950', 'icon' => 'users-2'],
                    ];
                    $theme = $catColors[$com->interest_category] ?? ['badge' => 'bg-teal-100 text-teal-900 border-teal-300/60', 'cover' => 'from-teal-600 to-slate-950', 'icon' => 'users-2'];
                @endphp

                <div class="modern-hover-card bg-white rounded-3xl border border-gray-200/90 shadow-subtle hover:shadow-xl hover:border-teal-300 flex flex-col justify-between overflow-hidden group transition-all duration-300">
                    
                    <div>
                        <!-- Card Banner Cover Visual -->
                        <div class="relative h-44 w-full bg-gradient-to-br {{ $theme['cover'] }} overflow-hidden">
                            @if($com->photo)
                                <img src="{{ asset('storage/' . $com->photo) }}" 
                                     alt="{{ $com->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-95">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/20"></div>
                            @else
                                <!-- Procedural Pattern Overlay -->
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                <div class="absolute right-4 bottom-2 text-white/10 group-hover:text-white/20 transition-colors pointer-events-none">
                                    <i data-lucide="{{ $theme['icon'] }}" class="w-24 h-24 stroke-[1]"></i>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-black/20"></div>
                            @endif

                            <!-- Top Badges Overlay -->
                            <div class="absolute top-3 inset-x-3 flex items-center justify-between gap-2 z-10">
                                <!-- Location Pill -->
                                <div class="px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md border border-white/25 text-white flex items-center gap-1.5 shadow-lg max-w-[60%] truncate">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-rose-400 shrink-0"></i>
                                    <span class="text-[11px] font-bold truncate">{{ $com->base_location }}</span>
                                </div>

                                <!-- Category Pill -->
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-xl shadow-md border backdrop-blur-md {{ $theme['badge'] }}">
                                    {{ $com->interest_category }}
                                </span>
                            </div>

                            <!-- Bottom Title Snippet on Banner -->
                            <div class="absolute bottom-3 left-3 right-3 z-10">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-black/70 backdrop-blur-md text-emerald-300 text-[10px] font-extrabold border border-emerald-400/40">
                                    <i data-lucide="user-check" class="w-3 h-3"></i>
                                    <span>{{ $com->contact_person ? 'Pengurus: ' . $com->contact_person : 'Komunitas Aktif' }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Content Body -->
                        <div class="p-5">
                            <h3 class="text-base font-black text-brand-black group-hover:text-teal-700 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('communities.show', $com->slug) }}">
                                    {{ $com->name }}
                                </a>
                            </h3>

                            <p class="text-xs text-gray-600 mt-2 line-clamp-2 leading-relaxed">
                                {{ $com->description }}
                            </p>

                            <!-- Activities & Location Snippets -->
                            <div class="mt-4 pt-3.5 border-t border-gray-100/90 text-xs text-gray-600 space-y-1.5">
                                @if($com->activity_schedule)
                                    <p class="flex items-center gap-2 truncate">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i>
                                        <span class="truncate font-medium text-gray-700">{{ $com->activity_schedule }}</span>
                                    </p>
                                @endif
                                @if($com->social_media)
                                    <p class="flex items-center gap-2 truncate text-gray-500">
                                        <i data-lucide="share-2" class="w-3.5 h-3.5 text-teal-500 shrink-0"></i>
                                        <span class="truncate">{{ $com->social_media }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="px-5 py-3.5 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between gap-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $com->contact_phone) }}?text=Halo%20{{ urlencode($com->contact_person ?: 'Pengurus ' . $com->name) }},%20saya%20tertarik%20bergabung%20dengan%20komunitas%20yang%20saya%20lihat%20di%20Habar%20Etam." 
                           target="_blank" 
                           class="py-1.5 px-3 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold flex items-center gap-1.5 border border-emerald-200/80 transition-colors"
                           title="Kirim Pesan WhatsApp">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Hubungi</span>
                        </a>

                        <a href="{{ route('communities.show', $com->slug) }}" class="btn-black py-1.5 px-3.5 text-xs font-bold rounded-xl flex items-center gap-1.5 hover:scale-[1.02] transition-transform">
                            <span>Rincian</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $communities->links('partials.pagination') }}
        </div>
    @endif

    <!-- Civic Community Submission Callout Banner -->
    <div class="mt-12 bg-gradient-to-r from-teal-500/10 via-emerald-500/10 to-amber-500/10 rounded-3xl border border-teal-400/30 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 reveal-blur-spring shadow-xs">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-md">
                <i data-lucide="users-2" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-brand-black">Punya Komunitas atau Klub Hobi di Tenggarong & Kukar?</h3>
                <p class="text-xs text-gray-600 mt-1 max-w-xl leading-relaxed">
                    Daftarkan perkumpulan Anda ke dalam direktori resmi warga secara gratis untuk merekrut anggota baru, berjejaring, dan mengumumkan kegiatan rutin.
                </p>
            </div>
        </div>
        <a href="{{ route('communities.create') }}" class="btn-black py-3 px-6 rounded-2xl text-xs font-black shrink-0 hover:scale-105 transition-transform shadow-md flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4 text-brand-gold"></i>
            <span>Daftarkan Komunitas Baru</span>
        </a>
    </div>

</div>

<!-- Interactive Category Drag & Scroll Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('comCatScrollContainer');
    const btnLeft = document.getElementById('comCatScrollLeft');
    const btnRight = document.getElementById('comCatScrollRight');

    if (container && btnLeft && btnRight) {
        const updateScrollButtons = () => {
            btnLeft.disabled = container.scrollLeft <= 5;
            btnRight.disabled = (container.scrollLeft + container.clientWidth) >= (container.scrollWidth - 5);
        };

        btnLeft.addEventListener('click', () => {
            container.scrollBy({ left: -260, behavior: 'smooth' });
        });

        btnRight.addEventListener('click', () => {
            container.scrollBy({ left: 260, behavior: 'smooth' });
        });

        container.addEventListener('scroll', updateScrollButtons, { passive: true });
        window.addEventListener('resize', updateScrollButtons);
        setTimeout(updateScrollButtons, 100);

        // Drag to scroll
        let isDown = false;
        let startX, scrollLeftVal;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.classList.add('active');
            startX = e.pageX - container.offsetLeft;
            scrollLeftVal = container.scrollLeft;
        });

        container.addEventListener('mouseleave', () => { isDown = false; });
        container.addEventListener('mouseup', () => { isDown = false; });
        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.6;
            container.scrollLeft = scrollLeftVal - walk;
        });
    }

    if (window.initIcons) {
        window.initIcons();
    }
});
</script>
@endsection
