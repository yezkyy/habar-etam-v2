@extends('layouts.admin')

@section('title', 'Ruang Siar Redaksi & Script Generator — Studio PT SCM')
@section('page_title', 'Feed Redaksi & Script Generator (PT SCM)')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-16" 
     x-data="studioFeedApp()" 
     x-init="initClock()">

    <!-- 1. Top Broadcast Studio Header -->
    <div class="bg-gradient-to-r from-gray-950 via-slate-900 to-amber-950/80 text-white p-6 sm:p-8 rounded-3xl shadow-xl border border-amber-500/20 relative overflow-hidden">
        <!-- Ambient decorative broadcast glows -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-72 -top-12 w-64 h-64 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2.5 max-w-3xl">
                <!-- Live Studio Clock & On-Air Badge -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-black tracking-wider uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30 shadow-inner">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-rose-500 -ml-4"></span>
                        <span>STUDIO LIVE REDAKSI PT SCM</span>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono font-bold bg-white/10 text-gray-200 border border-white/15 backdrop-blur-md">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span x-text="currentTime" class="tracking-widest">--:--:-- WITA</span>
                    </div>

                    <div class="text-xs text-gray-400 hidden sm:inline-block">
                        • <span x-text="currentDate">--</span>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span>Ruang Siar Redaksi & Script Generator</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl">
                    Pusat kurasi materi siaran terintegrasi PT SCM. Menyediakan naskah narasi *on-air* instan untuk presenter/anchor, antrean siaran *live agenda*, serta aplikasi teleprompter studio di tab baru.
                </p>
            </div>

            <!-- Studio Action Bar -->
            <div class="relative z-10 flex flex-wrap items-center gap-3 self-start lg:self-center">
                <!-- Open Teleprompter in New Tab -->
                <a href="{{ route('admin.studio.teleprompter', ['mode' => 'all']) }}" 
                   target="_blank"
                   class="h-11 px-4 sm:px-5 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-gray-950 text-xs font-black rounded-2xl shadow-lg shadow-amber-500/20 transition-all inline-flex items-center gap-2 cursor-pointer transform active:scale-95">
                    <i data-lucide="tv" class="w-4 h-4 stroke-[2.5]"></i>
                    <span>Buka Teleprompter (Tab Baru)</span>
                    <i data-lucide="external-link" class="w-3.5 h-3.5 opacity-70"></i>
                </a>

                <!-- Print Rundown Button -->
                <button type="button" 
                        onclick="window.print()" 
                        class="h-11 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-2xl transition-all inline-flex items-center gap-2 border border-white/15 backdrop-blur-md shadow-xs cursor-pointer">
                    <i data-lucide="printer" class="w-4 h-4 text-gray-300"></i>
                    <span>Cetak Rundown</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Flash Notification -->
    @if(session('success'))
        <div class="p-4 sm:p-5 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-xs text-emerald-800 flex items-start sm:items-center justify-between gap-3 shadow-xs animate-fadeIn">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-500 text-white rounded-xl shadow-xs">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-950 text-xs sm:text-sm">Operasi Studio Sukses</h4>
                    <p class="text-[11px] sm:text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    <!-- 2. Studio KPI Metric Counters (6 Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- Total Materi Feed -->
        <a href="{{ route('admin.studio.feed', ['type' => 'all']) }}" 
           class="bg-white p-4 rounded-3xl border {{ $feedType === 'all' ? 'border-gray-900 ring-2 ring-gray-900/10' : 'border-gray-100' }} shadow-xs hover:border-gray-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Materi</span>
                <span class="p-1.5 rounded-xl bg-gray-100 text-gray-700 group-hover:scale-110 transition-transform">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-gray-950 tracking-tight">{{ $stats['total_items'] }}</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">Semua Sumber</span>
            </div>
        </a>

        <!-- Live On-Air Agenda -->
        <a href="{{ route('admin.studio.feed', ['type' => 'live']) }}" 
           class="bg-white p-4 rounded-3xl border {{ $feedType === 'live' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-gray-100' }} shadow-xs hover:border-rose-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600">On-Air Live</span>
                <span class="p-1.5 rounded-xl bg-rose-50 text-rose-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="radio" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-rose-600 tracking-tight">{{ $stats['live_count'] }}</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">Antrean Siaran</span>
            </div>
        </a>

        <!-- Lapor Etam Warga -->
        <a href="{{ route('admin.studio.feed', ['type' => 'reports']) }}" 
           class="bg-white p-4 rounded-3xl border {{ $feedType === 'reports' ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-100' }} shadow-xs hover:border-blue-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Lapor Etam</span>
                <span class="p-1.5 rounded-xl bg-blue-50 text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-blue-600 tracking-tight">{{ $stats['reports_count'] }}</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">Pengaduan Warga</span>
            </div>
        </a>

        <!-- Agenda Acara / Event -->
        <a href="{{ route('admin.studio.feed', ['type' => 'events']) }}" 
           class="bg-white p-4 rounded-3xl border {{ $feedType === 'events' ? 'border-purple-500 ring-2 ring-purple-500/20' : 'border-gray-100' }} shadow-xs hover:border-purple-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Event Kukar</span>
                <span class="p-1.5 rounded-xl bg-purple-50 text-purple-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-purple-600 tracking-tight">{{ $stats['events_count'] }}</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">Agenda Acara</span>
            </div>
        </a>

        <!-- Pantauan Harga Pasar -->
        <a href="{{ route('admin.studio.feed', ['type' => 'market']) }}" 
           class="bg-white p-4 rounded-3xl border {{ $feedType === 'market' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-100' }} shadow-xs hover:border-emerald-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Harga Pangan</span>
                <span class="p-1.5 rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-emerald-600 tracking-tight">{{ $stats['market_count'] }}</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">Info Komoditas</span>
            </div>
        </a>

        <!-- Jual Cepat & Karir -->
        <a href="{{ route('admin.studio.feed', ['type' => 'quick_sales']) }}" 
           class="bg-white p-4 rounded-3xl border {{ $feedType === 'quick_sales' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-100' }} shadow-xs hover:border-amber-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Jual Cepat</span>
                <span class="p-1.5 rounded-xl bg-amber-50 text-amber-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-black text-amber-600 tracking-tight">{{ $stats['sales_count'] }}</span>
                <span class="text-[10px] text-gray-400 block mt-0.5">Bursa Warga</span>
            </div>
        </a>
    </div>

    <!-- 3. Featured Live Rundown Queue (If items exist in Live Agenda) -->
    @if($liveRundownItems->count() > 0)
        <div class="bg-gradient-to-r from-rose-950 via-slate-950 to-gray-950 rounded-3xl p-5 sm:p-6 text-white border border-rose-500/30 shadow-lg relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 border-b border-rose-500/20 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-rose-500/20 border border-rose-500/40 text-rose-400">
                        <i data-lucide="radio" class="w-4 h-4 animate-pulse"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-extrabold text-white flex items-center gap-2">
                            <span>Antrean Segmen Siaran On-Air Live ({{ $liveRundownItems->count() }} Topik)</span>
                        </h3>
                        <p class="text-[11px] text-gray-300">
                            Materi laporan yang telah ditandai untuk dipaparkan secara langsung dalam program siaran SCM hari ini.
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.studio.teleprompter', ['mode' => 'live']) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                    <i data-lucide="play" class="w-3.5 h-3.5 fill-current"></i>
                    <span>Buka Rundown Live di Teleprompter</span>
                    <i data-lucide="external-link" class="w-3.5 h-3.5 opacity-80"></i>
                </a>
            </div>

            <!-- Queue Cards Carousel / Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($liveRundownItems as $idx => $liveItem)
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 hover:border-rose-400/40 transition-all flex flex-col justify-between group">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="px-2 py-0.5 rounded-lg bg-rose-500/20 text-rose-300 font-bold border border-rose-500/30">
                                    Segmen #{{ $idx + 1 }}
                                </span>
                                <span class="text-gray-400 font-mono text-[10px]">
                                    ~{{ $liveItem['reading_seconds'] }} dtk on-air
                                </span>
                            </div>
                            <h4 class="font-bold text-xs text-white line-clamp-1 group-hover:text-rose-300 transition-colors">
                                {{ $liveItem['title'] }}
                            </h4>
                            <p class="text-[11px] text-gray-400 line-clamp-2 leading-relaxed">
                                "{{ $liveItem['editorial_script'] }}"
                            </p>
                        </div>

                        <div class="pt-3 mt-2 border-t border-white/10 flex items-center justify-between">
                            <button type="button" 
                                    @click="copyText(`{{ addslashes($liveItem['editorial_script']) }}`)"
                                    class="text-[11px] font-semibold text-gray-300 hover:text-white inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="copy" class="w-3 h-3"></i>
                                <span>Salin</span>
                            </button>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.studio.teleprompter', ['type' => $liveItem['type'], 'id' => $liveItem['id']]) }}" 
                                   target="_blank"
                                   class="text-[11px] font-bold text-amber-400 hover:text-amber-300 inline-flex items-center gap-1">
                                    <span>Prompter</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>

                                <form action="{{ route('admin.studio.toggle-live', $liveItem['id']) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-[11px] font-bold text-rose-400 hover:text-rose-300 hover:underline cursor-pointer">
                                        Lepas Live
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 4. Filter Tabs & Search Toolbar -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-4 sm:p-5 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Filter Source Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'all'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'all' ? 'bg-gray-950 text-white border-gray-950 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                    <span>Semua Aliran</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'all' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $stats['total_items'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'live'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'live' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-rose-50 hover:text-rose-700' }}">
                    <i data-lucide="radio" class="w-3.5 h-3.5 text-rose-500 {{ $feedType === 'live' ? 'text-white' : '' }}"></i>
                    <span>Tayang Live</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'live' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $stats['live_count'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'reports'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'reports' ? 'bg-blue-600 text-white border-blue-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-blue-500 {{ $feedType === 'reports' ? 'text-white' : '' }}"></i>
                    <span>Lapor Etam</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'reports' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $stats['reports_count'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'events'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'events' ? 'bg-purple-600 text-white border-purple-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-purple-50 hover:text-purple-700' }}">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-500 {{ $feedType === 'events' ? 'text-white' : '' }}"></i>
                    <span>Agenda Acara</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'events' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-800' }}">{{ $stats['events_count'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'market'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'market' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-emerald-50 hover:text-emerald-700' }}">
                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5 text-emerald-500 {{ $feedType === 'market' ? 'text-white' : '' }}"></i>
                    <span>Harga Pangan</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'market' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $stats['market_count'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'quick_sales'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'quick_sales' ? 'bg-amber-600 text-white border-amber-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-amber-50 hover:text-amber-700' }}">
                    <i data-lucide="tag" class="w-3.5 h-3.5 text-amber-500 {{ $feedType === 'quick_sales' ? 'text-white' : '' }}"></i>
                    <span>Jual Cepat</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'quick_sales' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">{{ $stats['sales_count'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'jobs'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'jobs' ? 'bg-cyan-600 text-white border-cyan-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-cyan-50 hover:text-cyan-700' }}">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5 text-cyan-500 {{ $feedType === 'jobs' ? 'text-white' : '' }}"></i>
                    <span>Bursa Kerja</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'jobs' ? 'bg-white/20 text-white' : 'bg-cyan-100 text-cyan-800' }}">{{ $stats['jobs_count'] }}</span>
                </a>

                <a href="{{ route('admin.studio.feed', array_merge(request()->except('type'), ['type' => 'culture'])) }}" 
                   class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 cursor-pointer border {{ $feedType === 'culture' ? 'bg-yellow-600 text-white border-yellow-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-yellow-50 hover:text-yellow-700' }}">
                    <i data-lucide="landmark" class="w-3.5 h-3.5 text-yellow-500 {{ $feedType === 'culture' ? 'text-white' : '' }}"></i>
                    <span>Cagar Budaya</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $feedType === 'culture' ? 'bg-white/20 text-white' : 'bg-yellow-100 text-yellow-800' }}">{{ $stats['culture_count'] }}</span>
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.studio.feed') }}" class="flex items-center gap-2 w-full lg:w-80 shrink-0">
                @if($feedType !== 'all')
                    <input type="hidden" name="type" value="{{ $feedType }}">
                @endif
                <div class="relative w-full flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           placeholder="Cari naskah siaran / topik..."
                           class="w-full h-10 pl-10 pr-9 bg-gray-50/80 hover:bg-white focus:bg-white border border-gray-200 rounded-2xl text-xs font-semibold text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-amber-500/15 focus:border-amber-500 transition-all shadow-2xs">
                    @if($searchQuery)
                        <a href="{{ route('admin.studio.feed', request()->except('q')) }}" 
                           class="absolute right-3 p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                           title="Hapus pencarian">
                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </a>
                    @endif
                </div>
                <button type="submit" 
                        class="h-10 px-4 bg-gray-950 hover:bg-black text-white text-xs font-extrabold rounded-2xl transition-all shadow-xs cursor-pointer inline-flex items-center gap-1.5 shrink-0">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <span>Cari</span>
                </button>
            </form>
        </div>
    </div>

    <!-- 5. Main Feed Items Container -->
    <div class="space-y-4">
        @forelse($feedItems as $index => $item)
            <div class="bg-white rounded-3xl border {{ $item['is_featured_live'] ? 'border-rose-300 ring-2 ring-rose-500/10 shadow-md' : 'border-gray-100 shadow-xs' }} p-5 sm:p-7 transition-all hover:border-amber-300 group">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5">
                    
                    <!-- Left Body Content -->
                    <div class="space-y-4 flex-1 min-w-0">
                        <!-- Badges & Timestamps -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $item['badge_color'] }}">
                                <i data-lucide="{{ $item['badge_icon'] }}" class="w-3.5 h-3.5"></i>
                                <span>{{ $item['source'] }}</span>
                            </span>

                            @if($item['is_featured_live'])
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black bg-rose-500 text-white shadow-xs animate-pulse">
                                    <i data-lucide="radio" class="w-3.5 h-3.5"></i>
                                    <span>TAYANG LIVE ON-AIR</span>
                                </span>
                            @endif

                            @if($item['district'])
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-medium bg-gray-100 text-gray-700">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-gray-400"></i>
                                    <span>{{ $item['district'] }}</span>
                                </span>
                            @endif

                            <span class="text-gray-400 text-xs font-mono ml-auto">
                                {{ $item['date']->translatedFormat('d M Y, H:i') }} WITA 
                                <span class="text-gray-400 hidden sm:inline">({{ $item['date']->diffForHumans() }})</span>
                            </span>
                        </div>

                        <!-- Title, Media & Description -->
                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            @if($item['image'])
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gray-100 border border-gray-200 overflow-hidden shrink-0 shadow-2xs relative">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @if($item['media_count'] > 1)
                                        <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded-md bg-black/70 text-white text-[10px] font-bold">
                                            +{{ $item['media_count'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="space-y-1 min-w-0 flex-1">
                                <h3 class="text-base sm:text-lg font-black text-gray-900 leading-snug group-hover:text-amber-900 transition-colors">
                                    {{ $item['title'] }}
                                </h3>
                                <p class="text-xs text-gray-500 font-medium">
                                    {{ $item['subtitle'] }}
                                </p>
                                @if($item['description'])
                                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed pt-1">
                                        {{ Str::limit($item['description'], 160) }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- On-Air Teleprompter Lead-In Box -->
                        <div class="bg-gray-950 text-gray-100 rounded-2xl p-4 sm:p-5 border border-gray-800 relative group/prompter shadow-inner space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-800 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="p-1 rounded-lg bg-amber-500/20 text-amber-400">
                                        <i data-lucide="mic" class="w-3.5 h-3.5"></i>
                                    </span>
                                    <span class="text-xs font-black uppercase tracking-wider text-amber-400">
                                        Draft Naskah On-Air Presenter
                                    </span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 text-gray-300 font-mono">
                                        {{ $item['word_count'] }} kata • ~{{ $item['reading_seconds'] }} dtk siaran
                                    </span>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <!-- Edit Script Trigger (Only for reports) -->
                                    @if($item['type'] === 'reports')
                                        <button type="button" 
                                                @click="openScriptEditor({{ $item['id'] }}, `{{ addslashes($item['editorial_script']) }}`, `{{ addslashes($item['title']) }}`)"
                                                class="px-2.5 py-1 rounded-xl bg-white/10 hover:bg-white/20 text-[11px] font-semibold text-gray-300 hover:text-white transition-colors inline-flex items-center gap-1 cursor-pointer">
                                            <i data-lucide="edit-2" class="w-3 h-3"></i>
                                            <span>Edit Naskah</span>
                                        </button>
                                    @endif

                                    <!-- Copy Button -->
                                    <button type="button" 
                                            @click="copyText(`{{ addslashes($item['editorial_script']) }}`)"
                                            class="px-2.5 py-1 rounded-xl bg-white/10 hover:bg-amber-500 hover:text-gray-950 text-[11px] font-bold text-gray-200 transition-all inline-flex items-center gap-1 cursor-pointer">
                                        <i data-lucide="copy" class="w-3 h-3"></i>
                                        <span>Salin</span>
                                    </button>

                                    <!-- Launch this item in Teleprompter (New Tab) -->
                                    <a href="{{ route('admin.studio.teleprompter', ['type' => $item['type'], 'id' => $item['id']]) }}" 
                                       target="_blank"
                                       class="px-2.5 py-1 rounded-xl bg-amber-500/20 hover:bg-amber-500 text-amber-300 hover:text-gray-950 text-[11px] font-bold transition-all inline-flex items-center gap-1 cursor-pointer border border-amber-500/30">
                                        <i data-lucide="tv" class="w-3 h-3"></i>
                                        <span>Buka di Prompter</span>
                                        <i data-lucide="external-link" class="w-2.5 h-2.5 opacity-70"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Script text -->
                            <p class="text-xs sm:text-sm font-sans text-gray-200 leading-relaxed tracking-wide selection:bg-amber-500 selection:text-black">
                                "{{ $item['editorial_script'] }}"
                            </p>
                        </div>
                    </div>

                    <!-- Right Side Action Panel -->
                    <div class="flex lg:flex-col items-center lg:items-end gap-2.5 shrink-0 pt-2 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                        @if($item['type'] === 'reports')
                            <!-- Toggle Live Form -->
                            <form action="{{ route('admin.studio.toggle-live', $item['id']) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="h-10 px-4 rounded-2xl text-xs font-black transition-all inline-flex items-center gap-2 cursor-pointer shadow-xs {{ $item['is_featured_live'] ? 'bg-rose-100 hover:bg-rose-200 text-rose-800 border border-rose-200' : 'bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-gray-950' }}">
                                    <i data-lucide="radio" class="w-4 h-4"></i>
                                    <span>{{ $item['is_featured_live'] ? 'Lepas dari Live' : 'Pilih ke Live Agenda' }}</span>
                                </button>
                            </form>
                        @endif

                        <!-- Detail Deep Link -->
                        <a href="{{ $item['detail_url'] }}" 
                           target="_blank"
                           class="h-10 px-4 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                            <span>Buka Sumber</span>
                            <i data-lucide="external-link" class="w-3.5 h-3.5 text-gray-500"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-16 text-center border border-gray-100 shadow-xs space-y-4">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-3xl flex items-center justify-center mx-auto border border-gray-100">
                    <i data-lucide="rss" class="w-8 h-8"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-extrabold text-gray-900">Belum Ada Materi Feed Tersedia</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto leading-relaxed">
                        Tidak ditemukan materi siaran atau laporan warga yang sesuai dengan kriteria filter saat ini.
                    </p>
                </div>
                @if($searchQuery || $feedType !== 'all')
                    <div class="pt-2">
                        <a href="{{ route('admin.studio.feed') }}" class="px-4 py-2 bg-gray-950 text-white text-xs font-bold rounded-xl shadow-xs inline-flex items-center gap-2">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset Filter & Pencarian</span>
                        </a>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    <!-- 6. In-Place Edit Script Modal (For Lapor Etam Reports) -->
    <div x-show="editModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 p-4 backdrop-blur-xs animate-fadeIn">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 space-y-5 shadow-2xl border border-gray-100" 
             @click.outside="editModalOpen = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-xl bg-amber-100 text-amber-800">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Edit Naskah On-Air Presenter</h3>
                        <p class="text-[11px] text-gray-500" x-text="editModalTitle"></p>
                    </div>
                </div>
                <button type="button" @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/redaksi/feed') }}/' + editReportId + '/update-script'" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-700">
                        Draft Naskah On-Air (Editorial Summary) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="editorial_summary" 
                              rows="6" 
                              x-model="editScriptContent"
                              required 
                              placeholder="Tuliskan naskah siaran narasi presenter..." 
                              class="w-full p-4 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all leading-relaxed"></textarea>
                    <p class="text-[11px] text-gray-400">Naskah ini akan langsung dibaca oleh presenter pada teleprompter studio.</p>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-gray-950 shadow-xs cursor-pointer">
                        Simpan Perubahan Naskah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. Floating Toast Notification -->
    <div x-show="toastShow" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 bg-gray-950 text-white px-5 py-3 rounded-2xl shadow-2xl border border-amber-500/30 flex items-center gap-3 text-xs font-semibold">
        <span class="p-1 rounded-lg bg-emerald-500 text-white"><i data-lucide="check" class="w-3.5 h-3.5"></i></span>
        <span x-text="toastMessage">Naskah berhasil disalin!</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
function studioFeedApp() {
    return {
        currentTime: '--:--:-- WITA',
        currentDate: '',
        
        editModalOpen: false,
        editReportId: null,
        editModalTitle: '',
        editScriptContent: '',

        toastShow: false,
        toastMessage: '',

        initClock() {
            const updateTime = () => {
                const now = new Date();
                // WITA (UTC+8)
                const optionsTime = { timeZone: 'Asia/Makassar', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const optionsDate = { timeZone: 'Asia/Makassar', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                this.currentTime = now.toLocaleTimeString('id-ID', optionsTime) + ' WITA';
                this.currentDate = now.toLocaleDateString('id-ID', optionsDate);
            };
            updateTime();
            setInterval(updateTime, 1000);
        },

        copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.showToast('Naskah siaran berhasil disalin ke clipboard!');
            }).catch(() => {
                this.showToast('Gagal menyalin naskah.');
            });
        },

        showToast(msg) {
            this.toastMessage = msg;
            this.toastShow = true;
            setTimeout(() => {
                this.toastShow = false;
            }, 3000);
        },

        openScriptEditor(reportId, script, title) {
            this.editReportId = reportId;
            this.editScriptContent = script;
            this.editModalTitle = title;
            this.editModalOpen = true;
        }
    };
}
</script>
@endpush
