@extends('layouts.admin')

@section('title', 'Kelola Pantauan Lingkungan & Sensor — Smart City Kukar')
@section('page_title', 'Smart City: Titik Pantau Lingkungan & TMA Sungai Mahakam')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-slate-900 to-cyan-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-cyan-500/20">
        <!-- Ambient Glow Orbs -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-72 -top-12 w-56 h-56 bg-brand-gold/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-400">Smart City</span>
                <span>/</span>
                <span class="text-brand-gold font-semibold">Lingkungan & Infrastruktur</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-cyan-500/20 border border-cyan-500/30 text-cyan-300 shadow-inner">
                        <i data-lucide="activity" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                    </span>
                    <span>Telemetri Lingkungan & Pantauan Air Mahakam</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Pusat kendali peringatan dini Tinggi Muka Air (TMA) Sungai Mahakam, indeks standar pencemar udara (ISPU), titik panas karhutla, dan peringatan genangan air di Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-3 self-start lg:self-center">
            <a href="{{ route('smart-city.environment') }}" 
               target="_blank" 
               class="h-11 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-2xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer backdrop-blur-sm">
                <i data-lucide="external-link" class="w-4 h-4 text-cyan-300"></i>
                <span>Lihat Peta Publik</span>
            </a>

            <a href="{{ route('admin.smart-city.environment.create') }}" 
               class="h-11 px-5 bg-gradient-to-r from-brand-gold via-amber-400 to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-2xl shadow-lg shadow-brand-gold/25 transition-all inline-flex items-center gap-2 cursor-pointer hover:scale-[1.02]">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Titik Pantau</span>
            </a>
        </div>
    </div>

    <!-- 2. Flash Feedback Alert -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-800 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5 font-bold">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    <!-- 3. KPI / Summary Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
        <!-- Total Titik Pantau -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:border-brand-gold/40 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Sensor / Pos</span>
                <span class="p-2 rounded-2xl bg-gray-100 text-gray-700 group-hover:bg-brand-gold/10 group-hover:text-amber-800 transition-colors">
                    <i data-lucide="radio" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Pos pantau aktif</span>
            </div>
        </div>

        <!-- Status Normal / Aman -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-emerald-100/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Status Normal</span>
                <span class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-emerald-700">{{ $stats['normal'] }}</span>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded-md">Aman</span>
                </div>
                <span class="text-[11px] text-gray-400 block mt-0.5">Kondisi terkendali</span>
            </div>
        </div>

        <!-- Status Waspada -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-amber-100/80 shadow-sm flex flex-col justify-between hover:border-amber-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Status Waspada</span>
                <span class="p-2 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-100 transition-colors">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-amber-600">{{ $stats['warning'] }}</span>
                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded-md">Siaga 2</span>
                </div>
                <span class="text-[11px] text-gray-400 block mt-0.5">Perlu perhatian</span>
            </div>
        </div>

        <!-- Status Bahaya -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-rose-100/80 shadow-sm flex flex-col justify-between hover:border-rose-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Status Bahaya</span>
                <span class="p-2 rounded-2xl bg-rose-50 text-rose-600 group-hover:bg-rose-100 transition-colors">
                    <i data-lucide="alert-octagon" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-rose-600">{{ $stats['danger'] }}</span>
                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-md animate-pulse">Siaga 1</span>
                </div>
                <span class="text-[11px] text-gray-400 block mt-0.5">Kondisi kritis / darurat</span>
            </div>
        </div>

        <!-- Wilayah Terpantau -->
        <div class="col-span-2 sm:col-span-2 lg:col-span-1 bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:border-cyan-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Kecamatan</span>
                <span class="p-2 rounded-2xl bg-cyan-50 text-cyan-600 group-hover:bg-cyan-100 transition-colors">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ $stats['districts_count'] }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Wilayah kecamatan</span>
            </div>
        </div>
    </div>

    <!-- 4. Category / Info Type Tabs Bar -->
    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-cyan-600"></i>
                <span>Jenis Parameter Telemetri</span>
            </span>
            <span class="text-[11px] text-gray-400 font-medium">Klik untuk memfilter jenis pemantauan</span>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold no-scrollbar">
            <!-- Semua -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'all', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'all' ? 'bg-gray-950 text-white shadow-md font-black ring-2 ring-cyan-500/20' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 {{ $infoType === 'all' ? 'text-brand-gold' : 'text-gray-400' }}"></i>
                <span>Semua Sensor</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['all'] }}</span>
            </a>

            <!-- TMA Air Sungai Mahakam -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'water_level', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'water_level' ? 'bg-cyan-950 text-white shadow-md font-black ring-2 ring-cyan-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-cyan-200' }}">
                <i data-lucide="waves" class="w-3.5 h-3.5 text-cyan-400"></i>
                <span>TMA Sungai Mahakam</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'water_level' ? 'bg-white/20 text-white' : 'bg-cyan-50 text-cyan-800' }}">{{ $categoryCounts['water_level'] }}</span>
            </a>

            <!-- Kualitas Udara (ISPU) -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'air_quality', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'air_quality' ? 'bg-emerald-950 text-white shadow-md font-black ring-2 ring-emerald-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-emerald-200' }}">
                <i data-lucide="wind" class="w-3.5 h-3.5 text-emerald-400"></i>
                <span>Kualitas Udara (ISPU)</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'air_quality' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-800' }}">{{ $categoryCounts['air_quality'] }}</span>
            </a>

            <!-- Titik Genangan / Banjir -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'flood_alert', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'flood_alert' ? 'bg-blue-950 text-white shadow-md font-black ring-2 ring-blue-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-blue-200' }}">
                <i data-lucide="droplet" class="w-3.5 h-3.5 text-blue-400"></i>
                <span>Genangan / Pasang Air</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'flood_alert' ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-800' }}">{{ $categoryCounts['flood_alert'] }}</span>
            </a>

            <!-- Hotspot Karhutla -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'hotspot', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'hotspot' ? 'bg-rose-950 text-white shadow-md font-black ring-2 ring-rose-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-rose-200' }}">
                <i data-lucide="flame" class="w-3.5 h-3.5 text-rose-400"></i>
                <span>Titik Panas Karhutla</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'hotspot' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-800' }}">{{ $categoryCounts['hotspot'] }}</span>
            </a>

            <!-- Cuaca Ekstrem -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'weather', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'weather' ? 'bg-amber-950 text-white shadow-md font-black ring-2 ring-amber-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-amber-200' }}">
                <i data-lucide="cloud-lightning" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>Cuaca Ekstrem BMKG</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'weather' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-800' }}">{{ $categoryCounts['weather'] }}</span>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'other', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $infoType === 'other' ? 'bg-gray-800 text-white shadow-md font-black' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50' }}">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-gray-400"></i>
                <span>Infrastruktur Lainnya</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $infoType === 'other' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['other'] }}</span>
            </a>
        </div>
    </div>

    @php
        $hasActiveEnvFilter = request('q') || ($infoType !== 'all' && !empty($infoType)) || ($severity !== 'all' && !empty($severity)) || ($district !== 'all' && !empty($district));
    @endphp

    <!-- 5. Search & Multi-Filter Card -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <form action="{{ route('admin.smart-city.environment.index') }}" 
              method="GET" 
              class="space-y-4">
            <input type="hidden" name="info_type" value="{{ $infoType }}">

            <!-- Main Filter Controls Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-center">
                <!-- Search Box -->
                <div class="lg:col-span-5 relative flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari nama titik, lokasi, instansi pelapor, nilai..." 
                           class="w-full h-11 pl-10 pr-9 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs font-medium text-gray-800 placeholder-gray-400 focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs">
                    @if(request('q'))
                        <a href="{{ route('admin.smart-city.environment.index', ['info_type' => $infoType, 'severity' => $severity, 'district' => $district]) }}" 
                           title="Hapus kata kunci"
                           class="absolute right-3 z-10 p-1 rounded-lg hover:bg-gray-200 text-gray-400 hover:text-gray-700 transition-colors">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

                <!-- District Filter Dropdown -->
                <div class="lg:col-span-3 relative flex items-center">
                    <i data-lucide="map-pin" class="w-4 h-4 text-cyan-600 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <select name="district" 
                            onchange="this.form.submit()" 
                            data-search-limit="50"
                            class="w-full h-11 pl-10 pr-8 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs text-gray-800 font-semibold focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs appearance-none">
                        <option value="all">Semua Kecamatan Kukar</option>
                        @foreach($districts as $dst)
                            <option value="{{ $dst }}" {{ $district === $dst ? 'selected' : '' }}>Kec. {{ $dst }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3.5 pointer-events-none"></i>
                </div>

                <!-- Severity Level Filter Dropdown -->
                <div class="lg:col-span-3 relative flex items-center">
                    <i data-lucide="shield-alert" class="w-4 h-4 text-amber-600 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <select name="severity" 
                            onchange="this.form.submit()" 
                            class="w-full h-11 pl-10 pr-8 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs text-gray-800 font-semibold focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs appearance-none">
                        <option value="all" {{ $severity === 'all' ? 'selected' : '' }}>Semua Tingkat Status</option>
                        <option value="normal" {{ $severity === 'normal' ? 'selected' : '' }}>Status Normal (Aman)</option>
                        <option value="warning" {{ $severity === 'warning' ? 'selected' : '' }}>Status Waspada (Siaga 2)</option>
                        <option value="danger" {{ $severity === 'danger' ? 'selected' : '' }}>Status Bahaya (Siaga 1)</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3.5 pointer-events-none"></i>
                </div>

                <!-- Action Button -->
                <div class="lg:col-span-1 flex items-center gap-2">
                    <button type="submit" 
                            title="Terapkan Filter"
                            class="w-full h-11 px-4 bg-gray-900 hover:bg-black text-white rounded-2xl text-xs font-bold inline-flex items-center justify-center gap-1.5 transition-all cursor-pointer shadow-md hover:scale-[1.02]">
                        <i data-lucide="filter" class="w-4 h-4 text-brand-gold"></i>
                        <span class="lg:hidden">Terapkan</span>
                    </button>
                </div>
            </div>

            <!-- Active Filters & Result Counter Bar -->
            <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                        <i data-lucide="list-filter" class="w-3.5 h-3.5 text-gray-500"></i>
                        <span>Filter Aktif:</span>
                    </span>

                    @if(!$hasActiveEnvFilter)
                        <span class="px-2.5 py-1 rounded-xl bg-gray-100 text-gray-500 text-[11px] font-medium">
                            Menampilkan seluruh titik sensor
                        </span>
                    @endif

                    <!-- Active Info Type Chip -->
                    @if($infoType !== 'all' && !empty($infoType))
                        <a href="{{ route('admin.smart-city.environment.index', ['info_type' => 'all', 'severity' => $severity, 'district' => $district, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-cyan-50 border border-cyan-200 text-cyan-900 text-[11px] font-bold hover:bg-cyan-100 transition-colors">
                            <span>Parameter: <strong>{{ str_replace('_', ' ', $infoType) }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-cyan-600"></i>
                        </a>
                    @endif

                    <!-- Active District Chip -->
                    @if($district !== 'all' && !empty($district))
                        <a href="{{ route('admin.smart-city.environment.index', ['info_type' => $infoType, 'severity' => $severity, 'district' => 'all', 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-[11px] font-bold hover:bg-blue-100 transition-colors">
                            <span>Kecamatan: <strong>Kec. {{ $district }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-blue-600"></i>
                        </a>
                    @endif

                    <!-- Active Severity Chip -->
                    @if($severity !== 'all' && !empty($severity))
                        <a href="{{ route('admin.smart-city.environment.index', ['info_type' => $infoType, 'severity' => 'all', 'district' => $district, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-bold hover:bg-amber-100 transition-colors">
                            <span>Status: <strong>{{ strtoupper($severity) }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-amber-600"></i>
                        </a>
                    @endif

                    <!-- Active Keyword Chip -->
                    @if(request('q'))
                        <a href="{{ route('admin.smart-city.environment.index', ['info_type' => $infoType, 'severity' => $severity, 'district' => $district]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-100 border border-gray-300 text-gray-800 text-[11px] font-bold hover:bg-gray-200 transition-colors">
                            <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                            <i data-lucide="x" class="w-3 h-3 text-gray-500"></i>
                        </a>
                    @endif

                    <!-- Reset All Button -->
                    @if($hasActiveEnvFilter)
                        <a href="{{ route('admin.smart-city.environment.index') }}" 
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-[11px] font-bold transition-colors">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                            <span>Hapus Semua Filter</span>
                        </a>
                    @endif
                </div>

                <!-- Result Counter -->
                <div class="font-medium text-gray-500 text-[11px]">
                    Ditemukan <span class="font-bold text-gray-900">{{ $points->total() }}</span> titik pantau
                </div>
            </div>
        </form>
    </div>

    <!-- 6. Main Data Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/75 text-gray-500 border-b border-gray-100 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-6 py-4">Titik Pantau & Parameter</th>
                        <th class="px-6 py-4">Tingkat Status</th>
                        <th class="px-6 py-4">Nilai / Kondisi</th>
                        <th class="px-6 py-4">Lokasi & Kecamatan</th>
                        <th class="px-6 py-4">Sumber Data</th>
                        <th class="px-6 py-4">Pembaruan</th>
                        <th class="px-6 py-4 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($points as $pt)
                        <tr class="hover:bg-cyan-50/20 transition-colors group">
                            
                            <!-- 1. Titik Pantau & Parameter -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs font-black text-sm {{
                                        $pt->info_type === 'water_level' ? 'bg-cyan-50 text-cyan-600 border border-cyan-200' : (
                                        $pt->info_type === 'air_quality' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : (
                                        in_array($pt->info_type, ['flood_alert', 'flood_point']) ? 'bg-blue-50 text-blue-600 border border-blue-200' : (
                                        in_array($pt->info_type, ['hotspot', 'karhutla']) ? 'bg-rose-50 text-rose-600 border border-rose-200' : (
                                        $pt->info_type === 'weather' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-700 border border-gray-200'))))
                                    }}">
                                        @if($pt->info_type === 'water_level')
                                            <i data-lucide="waves" class="w-5 h-5"></i>
                                        @elseif($pt->info_type === 'air_quality')
                                            <i data-lucide="wind" class="w-5 h-5"></i>
                                        @elseif(in_array($pt->info_type, ['flood_alert', 'flood_point']))
                                            <i data-lucide="droplet" class="w-5 h-5"></i>
                                        @elseif(in_array($pt->info_type, ['hotspot', 'karhutla']))
                                            <i data-lucide="flame" class="w-5 h-5"></i>
                                        @elseif($pt->info_type === 'weather')
                                            <i data-lucide="cloud-lightning" class="w-5 h-5"></i>
                                        @else
                                            <i data-lucide="activity" class="w-5 h-5"></i>
                                        @endif
                                    </div>
                                    <div class="space-y-0.5 max-w-sm">
                                        <span class="font-bold text-gray-900 text-sm block group-hover:text-cyan-800 transition-colors leading-snug">
                                            {{ $pt->title }}
                                        </span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">
                                            {{ str_replace('_', ' ', $pt->info_type) }}
                                        </span>
                                        @if($pt->description)
                                            <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5 leading-relaxed">
                                                {{ $pt->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Tingkat Status (Severity) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pt->severity === 'normal')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-bold bg-emerald-100/80 text-emerald-900 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Normal / Aman</span>
                                    </span>
                                @elseif($pt->severity === 'warning')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-black bg-amber-100 text-amber-900 border border-amber-300">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                        <span>Waspada (Siaga 2)</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[11px] font-black bg-rose-100 text-rose-900 border border-rose-300">
                                        <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                                        <span>Bahaya (Siaga 1)</span>
                                    </span>
                                @endif
                            </td>

                            <!-- 3. Nilai / Kondisi Saat Ini -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-xs sm:text-sm font-black text-gray-900 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200/80 inline-block shadow-2xs">
                                    {{ $pt->status_condition ?: 'Terpantau Normal' }}
                                </span>
                            </td>

                            <!-- 4. Lokasi & Wilayah -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                <div class="space-y-1">
                                    <span class="font-semibold text-gray-900 block text-xs">{{ $pt->location_name }}</span>
                                    <span class="inline-flex items-center gap-1 text-[11px] text-gray-500 font-medium">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-cyan-600"></i>
                                        <span>Kec. {{ $pt->location_district }}</span>
                                    </span>
                                    @if($pt->latitude && $pt->longitude)
                                        <a href="https://www.google.com/maps?q={{ $pt->latitude }},{{ $pt->longitude }}" 
                                           target="_blank" 
                                           class="text-[10px] text-cyan-700 hover:underline font-mono block">
                                            {{ $pt->latitude }}, {{ $pt->longitude }}
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- 5. Sumber Data -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-gray-100 text-gray-700 text-[11px] font-medium border border-gray-200/60">
                                    <i data-lucide="building" class="w-3 h-3 text-gray-400"></i>
                                    <span>{{ $pt->source }}</span>
                                </span>
                            </td>

                            <!-- 6. Waktu Pembaruan & Updater -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                <div class="space-y-0.5 font-mono text-[11px]">
                                    <span class="font-bold text-gray-800 block">
                                        {{ $pt->updated_at ? $pt->updated_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 flex items-center gap-1">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        <span>{{ $pt->updater->name ?? 'Admin Redaksi' }}</span>
                                    </span>
                                </div>
                            </td>

                            <!-- 7. Aksi Manajemen (Edit & Hapus) -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit Link to Dedicated Edit Page -->
                                    <a href="{{ route('admin.smart-city.environment.edit', $pt->id) }}" 
                                       title="Edit Titik Pantau" 
                                       class="h-8 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs inline-flex items-center gap-1.5 border border-amber-200/80 transition-colors shadow-2xs">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete Button Form -->
                                    <form action="{{ route('admin.smart-city.environment.destroy', $pt->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus titik pantau lingkungan: {{ addslashes($pt->title) }}?');" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Titik Pantau" 
                                                class="h-8 w-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 inline-flex items-center justify-center border border-rose-200/80 transition-colors cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center mx-auto shadow-2xs">
                                        <i data-lucide="activity" class="w-7 h-7"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900">Tidak ada titik pantau lingkungan yang cocok</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Coba sesuaikan kata kunci pencarian, filter kecamatan, atau tambahkan titik sensor lingkungan baru.
                                    </p>
                                    <a href="{{ route('admin.smart-city.environment.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-brand-gold text-brand-black rounded-xl text-xs font-bold shadow hover:bg-amber-400 transition-colors mt-2">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        <span>Tambah Titik Pantau Baru</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($points->hasPages())
            <div class="p-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500 font-medium">
                    Menampilkan <span class="font-bold text-gray-900">{{ $points->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $points->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $points->total() }}</span> titik pantau
                </div>
                <div>
                    {{ $points->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
