@extends('layouts.public')

@section('title', 'Event & Agenda Kegiatan Kukar — Habar Etam')
@section('meta_description', 'Kalender agenda kegiatan, festival budaya Erau, konser musik, pentas seni, turnamen olahraga, dan pameran UMKM di Tenggarong & Kutai Kartanegara.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Editorial Event Hero Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-slate-900 to-indigo-950 text-white rounded-3xl p-6 sm:p-10 mb-8 border border-indigo-500/20 shadow-2xl relative overflow-hidden reveal-blur-spring">
        <!-- Subtle Glow & Mesh Orbs -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-purple-600/15 rounded-full blur-3xl pointer-events-none animate-ambient-glow"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-400/30 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-3.5 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Kalender Kegiatan Warga • Kutai Kartanegara</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-md">
                    Agenda Acara & Festival Budaya <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-brand-gold to-yellow-200">Kukar</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2.5 leading-relaxed font-normal max-w-xl">
                    Jelajahi agenda pesta adat Erau, pentas seni tradisi, konser musik Mahakam, turnamen olahraga, dan expo kreatif antar warga se-Kutai Kartanegara.
                </p>

                <!-- Quick Stats Badges -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 mt-5 pt-1 text-xs">
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span><strong>{{ $totalPublished ?? $events->total() }}</strong> Agenda Terdaftar</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span><strong>{{ $thisMonthCount ?? 0 }}</strong> Bulan Ini</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>18 Kecamatan Kukar</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('events.create') }}" class="btn-shimmer-effect px-6 py-3.5 rounded-2xl bg-brand-gold hover:bg-brand-gold-dark text-brand-black text-xs sm:text-sm font-black shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group active:scale-95">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-brand-black group-hover:rotate-90 transition-transform"></i>
                    <span>Daftarkan Agenda Event</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Category Discovery Scroll Bar -->
    @php
        $categories = [
            ['name' => 'Semua Agenda', 'value' => '', 'icon' => 'layout-grid', 'color' => 'amber'],
            ['name' => 'Budaya & Adat', 'value' => 'Budaya & Adat', 'icon' => 'landmark', 'color' => 'amber'],
            ['name' => 'Musik & Komunitas', 'value' => 'Musik & Komunitas', 'icon' => 'music-2', 'color' => 'purple'],
            ['name' => 'Olahraga & Hobi', 'value' => 'Olahraga & Hobi', 'icon' => 'trophy', 'color' => 'emerald'],
            ['name' => 'Edukasi & Seminar', 'value' => 'Edukasi & Seminar', 'icon' => 'graduation-cap', 'color' => 'blue'],
        ];
        $currentCat = request('category', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Arrow -->
            <button type="button" id="eventCatScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Pills Container -->
            <div id="eventCatScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($currentCat === $cat['value']) || (empty($currentCat) && empty($cat['value']));
                        $queryParam = request()->except(['category', 'page']);
                        if (!empty($cat['value'])) {
                            $queryParam['category'] = $cat['value'];
                        }
                        $catUrl = route('events.index', $queryParam);
                        $count = !empty($cat['value']) ? ($categoryCounts[$cat['value']] ?? 0) : $totalPublished;
                    @endphp
                    <a href="{{ $catUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-slate-900 to-indigo-950 text-brand-gold border-indigo-900 shadow-md ring-2 ring-brand-gold/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-brand-gold/70 hover:bg-amber-50/60 hover:text-brand-black shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-brand-gold/20 text-brand-gold' : 'bg-gray-100 group-hover/item:bg-amber-100 text-gray-600 group-hover/item:text-brand-black' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $cat['name'] }}</span>
                        @if($count > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isActive ? 'bg-brand-gold/20 text-brand-gold border border-brand-gold/30' : 'bg-gray-100 text-gray-500' }}">
                                {{ $count }}
                            </span>
                        @endif
                        @if($isActive && !empty($cat['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Arrow -->
            <button type="button" id="eventCatScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="relative z-30 bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 mb-8 shadow-subtle reveal-blur-spring">
        <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <!-- Keyword Search -->
            <div class="sm:col-span-5">
                <label class="form-label mb-1.5 text-xs text-gray-600 font-bold flex items-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>Cari Nama Acara / Venue / Panitia</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Ketik Festival Erau, musik, museum, gowes..." 
                           class="form-input text-xs pl-3.5 pr-8 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20">
                    @if(request('q'))
                        <a href="{{ route('events.index', request()->except('q')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Time Filter -->
            <div class="sm:col-span-4">
                <label class="form-label mb-1.5 text-xs text-gray-600 font-bold flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>Periode Jadwal</span>
                </label>
                <select name="time_filter" class="form-input text-xs py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20">
                    <option value="" data-icon="clock">Semua Waktu</option>
                    <option value="upcoming" data-icon="sparkles" {{ request('time_filter') == 'upcoming' ? 'selected' : '' }}>Akan Datang (Upcoming)</option>
                    <option value="this_month" data-icon="calendar" {{ request('time_filter') == 'this_month' ? 'selected' : '' }}>Bulan Ini ({{ Carbon\Carbon::now()->translatedFormat('F Y') }})</option>
                    <option value="past" data-icon="history" {{ request('time_filter') == 'past' ? 'selected' : '' }}>Riwayat Selesai</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="btn-black flex-1 py-2.5 px-4 text-xs font-bold rounded-xl shadow-xs hover:bg-gray-800 transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->anyFilled(['q', 'category', 'time_filter']))
                    <a href="{{ route('events.index') }}" class="btn-outline py-2.5 px-3 text-xs rounded-xl border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-colors" title="Reset Semua Filter">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-gray-600"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Featured / Spotlight Event Banner (Shown when no active query on page 1) -->
    @if(isset($spotlightEvent) && $spotlightEvent && !request()->anyFilled(['q', 'category', 'time_filter']))
        @php
            $startDate = $spotlightEvent->start_date;
            $daysLeft = Carbon\Carbon::today()->diffInDays($startDate, false);
            $isOngoing = Carbon\Carbon::today()->betweenIncluded($startDate, $spotlightEvent->end_date ?? $startDate);
        @endphp
        <div class="mb-10 bg-gradient-to-br from-indigo-950 via-slate-900 to-gray-950 rounded-3xl border border-indigo-500/30 shadow-2xl p-6 sm:p-8 text-white relative z-10 overflow-hidden reveal-blur-spring group">
            <div class="absolute right-0 top-0 w-96 h-full bg-gradient-to-l from-indigo-600/10 to-transparent pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <!-- Date Highlight Left Column -->
                <div class="lg:col-span-3 flex flex-row lg:flex-col items-center justify-center p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center gap-3">
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-black uppercase tracking-widest text-brand-gold bg-brand-gold/10 px-3 py-0.5 rounded-full border border-brand-gold/20 mb-1">
                            {{ $startDate->translatedFormat('F') }}
                        </span>
                        <span class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-none my-1">
                            {{ $startDate->format('d') }}
                        </span>
                        <span class="text-xs text-gray-300 font-semibold">{{ $startDate->format('Y') }}</span>
                    </div>
                    <div class="h-8 w-px bg-white/10 lg:w-full lg:h-px my-1"></div>
                    @if($isOngoing)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-400/30 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Sedang Berlangsung</span>
                        </span>
                    @elseif($daysLeft >= 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-400/30">
                            <i data-lucide="timer" class="w-3.5 h-3.5"></i>
                            <span>{{ $daysLeft == 0 ? 'Hari Ini!' : ($daysLeft . ' Hari Lagi') }}</span>
                        </span>
                    @endif
                </div>

                <!-- Event Info Middle & Right -->
                <div class="lg:col-span-9 flex flex-col justify-between h-full space-y-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                                <span>Agenda Utama Terdekat</span>
                            </span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-white/10 text-gray-200">
                                {{ $spotlightEvent->category }}
                            </span>
                        </div>

                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-white group-hover:text-brand-gold transition-colors tracking-tight">
                            <a href="{{ route('events.show', $spotlightEvent->slug) }}">
                                {{ $spotlightEvent->title }}
                            </a>
                        </h2>

                        <p class="text-xs sm:text-sm text-gray-300 mt-2 line-clamp-2 leading-relaxed">
                            {{ $spotlightEvent->description }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-white/10">
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-300">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-4 h-4 text-rose-400 shrink-0"></i>
                                <span class="font-semibold text-white">{{ $spotlightEvent->location_name }}</span>
                            </div>
                            @if($spotlightEvent->start_time)
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-4 h-4 text-amber-400 shrink-0"></i>
                                    <span>{{ $spotlightEvent->start_time }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="users" class="w-4 h-4 text-indigo-400 shrink-0"></i>
                                <span class="text-gray-300">Panitia: {{ $spotlightEvent->organizer }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('events.show', $spotlightEvent->slug) }}" class="btn-shimmer-effect px-5 py-2.5 rounded-xl bg-brand-gold hover:bg-brand-gold-dark text-black text-xs font-black shadow-md flex items-center gap-1.5 transition-transform hover:scale-105">
                                <span>Lihat Rincian Lengkap</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-black"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Events Grid Section -->
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg sm:text-xl font-black text-brand-black tracking-tight flex items-center gap-2">
                <span>Daftar Agenda Kegiatan</span>
                @if(request('category'))
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                        {{ request('category') }}
                    </span>
                @endif
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Menampilkan {{ $events->total() }} agenda yang sedang atau akan berlangsung di Kutai Kartanegara.</p>
        </div>
    </div>

    @if($events->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200/80 p-12 text-center shadow-subtle my-6 reveal-blur-spring">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mx-auto mb-4">
                <i data-lucide="calendar-x-2" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-brand-black">Belum Ada Agenda yang Sesuai</h3>
            <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                Tidak ada agenda kegiatan yang cocok dengan filter pencarian Anda saat ini. Silakan coba kata kunci lain atau daftarkan agenda baru.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                @if(request()->anyFilled(['q', 'category', 'time_filter']))
                    <a href="{{ route('events.index') }}" class="btn-outline text-xs py-2 px-4 rounded-xl font-bold">
                        Reset Semua Filter
                    </a>
                @endif
                <a href="{{ route('events.create') }}" class="btn-gold text-xs py-2 px-5 rounded-xl font-bold shadow-sm">
                    + Daftarkan Acara Baru
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($events as $event)
                @php
                    $start = $event->start_date;
                    $end = $event->end_date;
                    $today = Carbon\Carbon::today();
                    $isOngoing = $today->betweenIncluded($start, $end ?? $start);
                    $isPast = ($end ? $end->lt($today) : $start->lt($today));
                    $daysRemaining = $today->diffInDays($start, false);

                    // Category Accent Palette
                    $catColors = [
                        'Budaya & Adat' => ['badge' => 'bg-amber-100 text-amber-900 border-amber-300/60', 'cover' => 'from-amber-600 to-amber-950', 'icon' => 'landmark', 'dateBg' => 'bg-amber-50 text-amber-900 border-amber-200'],
                        'Musik & Komunitas' => ['badge' => 'bg-purple-100 text-purple-900 border-purple-300/60', 'cover' => 'from-purple-600 to-indigo-950', 'icon' => 'music-2', 'dateBg' => 'bg-purple-50 text-purple-900 border-purple-200'],
                        'Olahraga & Hobi' => ['badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300/60', 'cover' => 'from-emerald-600 to-teal-950', 'icon' => 'trophy', 'dateBg' => 'bg-emerald-50 text-emerald-900 border-emerald-200'],
                        'Edukasi & Seminar' => ['badge' => 'bg-blue-100 text-blue-900 border-blue-300/60', 'cover' => 'from-blue-600 to-slate-950', 'icon' => 'graduation-cap', 'dateBg' => 'bg-blue-50 text-blue-900 border-blue-200'],
                    ];
                    $theme = $catColors[$event->category] ?? ['badge' => 'bg-indigo-100 text-indigo-900 border-indigo-300/60', 'cover' => 'from-indigo-600 to-slate-950', 'icon' => 'calendar', 'dateBg' => 'bg-indigo-50 text-indigo-900 border-indigo-200'];
                @endphp

                <div class="modern-hover-card bg-white rounded-3xl border border-gray-200/90 shadow-subtle hover:shadow-xl hover:border-indigo-300 flex flex-col justify-between overflow-hidden group transition-all duration-300">
                    
                    <div>
                        <!-- Card Banner Cover / Visual Area -->
                        <div class="relative h-44 w-full bg-gradient-to-br {{ $theme['cover'] }} overflow-hidden">
                            @if($event->poster_image)
                                <img src="{{ asset('storage/' . $event->poster_image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-95">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/20"></div>
                            @else
                                <!-- Procedural Cultural Decorative Motif Pattern -->
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                <div class="absolute right-4 bottom-2 text-white/10 group-hover:text-white/20 transition-colors pointer-events-none">
                                    <i data-lucide="{{ $theme['icon'] }}" class="w-24 h-24 stroke-[1]"></i>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-black/20"></div>
                            @endif

                            <!-- Top Badges Overlay -->
                            <div class="absolute top-3 inset-x-3 flex items-center justify-between gap-2 z-10">
                                <!-- Dynamic Date Pill -->
                                <div class="px-3 py-1 rounded-xl bg-black/60 backdrop-blur-md border border-white/25 text-white flex items-center gap-2 shadow-lg">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-brand-gold"></i>
                                    <span class="text-xs font-black tracking-tight">
                                        {{ $start->format('d M Y') }}
                                        @if($end && $end != $start)
                                            — {{ $end->format('d M') }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Category Pill -->
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-xl shadow-md border backdrop-blur-md {{ $theme['badge'] }}">
                                    {{ $event->category }}
                                </span>
                            </div>

                            <!-- Bottom Status Pill in Banner -->
                            <div class="absolute bottom-3 left-3 z-10">
                                @if($isOngoing)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-extrabold shadow-md animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                        <span>Sedang Berlangsung</span>
                                    </span>
                                @elseif($isPast)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-gray-800/90 text-gray-300 text-[10px] font-bold border border-white/20">
                                        <i data-lucide="check" class="w-3 h-3 text-gray-400"></i>
                                        <span>Telah Selesai</span>
                                    </span>
                                @elseif($daysRemaining >= 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-black/70 backdrop-blur-md text-amber-300 text-[10px] font-extrabold border border-amber-400/40">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span>{{ $daysRemaining == 0 ? 'Hari Ini!' : ($daysRemaining . ' Hari Lagi') }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Content Body -->
                        <div class="p-5">
                            <h3 class="text-base font-black text-brand-black group-hover:text-indigo-700 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <div class="flex items-center gap-1.5 text-xs text-gray-500 font-semibold mt-1.5">
                                <i data-lucide="users" class="w-3.5 h-3.5 text-indigo-500 shrink-0"></i>
                                <span class="truncate">Panitia: <strong>{{ $event->organizer }}</strong></span>
                            </div>

                            <p class="text-xs text-gray-600 mt-2.5 line-clamp-2 leading-relaxed">
                                {{ $event->description }}
                            </p>

                            <!-- Venue & Timing Snippets -->
                            <div class="mt-4 pt-3.5 border-t border-gray-100/90 text-xs text-gray-600 space-y-1.5">
                                <p class="flex items-center gap-2 truncate">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500 shrink-0"></i>
                                    <span class="truncate font-medium text-gray-700">{{ $event->location_name }}</span>
                                </p>
                                @if($event->start_time)
                                    <p class="flex items-center gap-2 truncate">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i>
                                        <span class="truncate text-gray-500">{{ $event->start_time }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="px-5 py-3.5 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between gap-2">
                        <button type="button" 
                                onclick="shareEvent('{{ addslashes($event->title) }}', '{{ route('events.show', $event->slug) }}')" 
                                class="p-2 rounded-xl text-gray-500 hover:text-indigo-600 hover:bg-white transition-all border border-transparent hover:border-gray-200" 
                                title="Bagikan Agenda">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                        </button>

                        <a href="{{ route('events.show', $event->slug) }}" class="btn-black py-2 px-4 text-xs font-bold rounded-xl flex items-center gap-1.5 hover:scale-[1.02] transition-transform">
                            <span>Lihat Rincian</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links('partials.pagination') }}
        </div>
    @endif

    <!-- Civic Event Submission Callout Banner -->
    <div class="mt-12 bg-gradient-to-r from-amber-500/10 via-brand-gold/15 to-indigo-500/10 rounded-3xl border border-brand-gold/30 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 reveal-blur-spring shadow-xs">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-brand-gold text-brand-black flex items-center justify-center shrink-0 shadow-md">
                <i data-lucide="megaphone" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-brand-black">Punya Agenda Acara atau Pentas di Kutai Kartanegara?</h3>
                <p class="text-xs text-gray-600 mt-1 max-w-xl leading-relaxed">
                    Sebarkan informasi kegiatan komunitas, turnamen, bazar, ataupun festival Anda secara gratis untuk menjangkau seluruh warga Tenggarong & 18 Kecamatan Kukar.
                </p>
            </div>
        </div>
        <a href="{{ route('events.create') }}" class="btn-black py-3 px-6 rounded-2xl text-xs font-black shrink-0 hover:scale-105 transition-transform shadow-md flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4 text-brand-gold"></i>
            <span>Daftarkan Event Anda</span>
        </a>
    </div>

</div>

<!-- Interactive Category Drag & Scroll + Share Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Horizontal Scroll Controls
    const container = document.getElementById('eventCatScrollContainer');
    const btnLeft = document.getElementById('eventCatScrollLeft');
    const btnRight = document.getElementById('eventCatScrollRight');

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

// Event Share Function
function shareEvent(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title + ' — Agenda Event Habar Etam',
            text: 'Cek agenda kegiatan: ' + title + ' di Habar Etam Kukar!',
            url: url
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Tautan agenda kegiatan berhasil disalin ke clipboard!');
        }).catch(() => {
            prompt('Salin tautan event ini:', url);
        });
    }
}
</script>
@endsection
