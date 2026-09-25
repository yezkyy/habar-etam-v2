@extends('layouts.admin')

@section('title', 'Kelola Harga Pangan Pasar — Smart City Kukar')
@section('page_title', 'Smart City: Pemantauan Harga Pangan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-slate-900 to-emerald-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-emerald-500/20">
        <!-- Ambient Glow Orbs -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-72 -top-12 w-56 h-56 bg-brand-gold/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-400">Smart City</span>
                <span>/</span>
                <span class="text-brand-gold font-semibold">Harga Pangan & Sembako</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 shadow-inner">
                        <i data-lucide="shopping-basket" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                    </span>
                    <span>Monitoring Harga Pangan & Sembako Kukar</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Kelola pencatatan harian harga bahan pokok, sayur, bumbu dapur, ikan Mahakam, dan sembako di seluruh pasar tradisional Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-3 self-start lg:self-center">
            <a href="{{ route('smart-city.market-prices') }}" 
               target="_blank" 
               class="h-11 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-2xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer backdrop-blur-sm">
                <i data-lucide="external-link" class="w-4 h-4 text-emerald-300"></i>
                <span>Lihat Portal Publik</span>
            </a>

            <a href="{{ route('admin.smart-city.prices.create') }}" 
               class="h-11 px-5 bg-gradient-to-r from-brand-gold via-amber-400 to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-2xl shadow-lg shadow-brand-gold/25 transition-all inline-flex items-center gap-2 cursor-pointer hover:scale-[1.02]">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Input Harga Baru</span>
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
        <!-- Total Komoditas -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:border-brand-gold/40 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Catatan</span>
                <span class="p-2 rounded-2xl bg-gray-100 text-gray-700 group-hover:bg-brand-gold/10 group-hover:text-amber-800 transition-colors">
                    <i data-lucide="database" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($stats['total'], 0, ',', '.') }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Komoditas tercatat</span>
            </div>
        </div>

        <!-- Pasar Terpantau -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Pasar Aktif</span>
                <span class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                    <i data-lucide="store" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-emerald-700">{{ $stats['markets_count'] }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Titik pasar Kukar</span>
            </div>
        </div>

        <!-- Harga Naik -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-rose-100/80 shadow-sm flex flex-col justify-between hover:border-rose-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Harga Naik</span>
                <span class="p-2 rounded-2xl bg-rose-50 text-rose-600 group-hover:bg-rose-100 transition-colors">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-rose-600">{{ $stats['up'] }}</span>
                    <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded-md">&uarr; Naik</span>
                </div>
                <span class="text-[11px] text-gray-400 block mt-0.5">Mengalami kenaikan</span>
            </div>
        </div>

        <!-- Harga Turun -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-teal-100/80 shadow-sm flex flex-col justify-between hover:border-teal-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-teal-700 uppercase tracking-wider">Harga Turun</span>
                <span class="p-2 rounded-2xl bg-teal-50 text-teal-600 group-hover:bg-teal-100 transition-colors">
                    <i data-lucide="trending-down" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-teal-700">{{ $stats['down'] }}</span>
                    <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded-md">&darr; Turun</span>
                </div>
                <span class="text-[11px] text-gray-400 block mt-0.5">Mengalami penurunan</span>
            </div>
        </div>

        <!-- Harga Stabil -->
        <div class="col-span-2 sm:col-span-2 lg:col-span-1 bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between hover:border-blue-300 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Harga Stabil</span>
                <span class="p-2 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors">
                    <i data-lucide="minus" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-gray-900">{{ $stats['stable'] }}</span>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded-md">&bull; Tetap</span>
                </div>
                <span class="text-[11px] text-gray-400 block mt-0.5">Harga tidak berubah</span>
            </div>
        </div>
    </div>

    <!-- 4. Category Tabs Bar -->
    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Kelompok Kategori Komoditas</span>
            </span>
            <span class="text-[11px] text-gray-400 font-medium">Klik untuk memfilter per komoditas</span>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold no-scrollbar">
            <!-- Semua -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'all', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'all' ? 'bg-gray-950 text-white shadow-md font-black ring-2 ring-emerald-500/20' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 {{ $category === 'all' ? 'text-brand-gold' : 'text-gray-400' }}"></i>
                <span>Semua Komoditas</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['all'] }}</span>
            </a>

            <!-- Sembako & Beras -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'sembako', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'sembako' ? 'bg-emerald-900 text-white shadow-md font-black ring-2 ring-emerald-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-emerald-200' }}">
                <i data-lucide="wheat" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>Sembako & Beras</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'sembako' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-800' }}">{{ $categoryCounts['sembako'] }}</span>
            </a>

            <!-- Bumbu Dapur & Cabai -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'bumbu_dapur', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'bumbu_dapur' ? 'bg-rose-950 text-white shadow-md font-black ring-2 ring-rose-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-rose-200' }}">
                <i data-lucide="flame" class="w-3.5 h-3.5 text-rose-400"></i>
                <span>Bumbu & Cabai</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'bumbu_dapur' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-800' }}">{{ $categoryCounts['bumbu_dapur'] }}</span>
            </a>

            <!-- Daging & Ikan Mahakam -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'daging_ikan', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'daging_ikan' ? 'bg-blue-950 text-white shadow-md font-black ring-2 ring-blue-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-blue-200' }}">
                <i data-lucide="fish" class="w-3.5 h-3.5 text-cyan-400"></i>
                <span>Daging & Ikan</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'daging_ikan' ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-800' }}">{{ $categoryCounts['daging_ikan'] }}</span>
            </a>

            <!-- Sayur Mayur -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'sayur_mayur', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'sayur_mayur' ? 'bg-teal-950 text-white shadow-md font-black ring-2 ring-teal-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-teal-200' }}">
                <i data-lucide="leaf" class="w-3.5 h-3.5 text-emerald-400"></i>
                <span>Sayur Mayur</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'sayur_mayur' ? 'bg-white/20 text-white' : 'bg-teal-50 text-teal-800' }}">{{ $categoryCounts['sayur_mayur'] }}</span>
            </a>

            <!-- Telur & Susu -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'telur_susu', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'telur_susu' ? 'bg-amber-950 text-white shadow-md font-black ring-2 ring-amber-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-amber-200' }}">
                <i data-lucide="egg" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>Telur & Susu</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'telur_susu' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-800' }}">{{ $categoryCounts['telur_susu'] }}</span>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('admin.smart-city.prices.index', ['category' => 'lainnya', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'lainnya' ? 'bg-gray-800 text-white shadow-md font-black' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50' }}">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-gray-400"></i>
                <span>Lainnya</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'lainnya' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['lainnya'] }}</span>
            </a>
        </div>
    </div>

    @php
        $hasActiveFilter = request('q') || ($category !== 'all' && !empty($category)) || ($market !== 'all' && !empty($market)) || ($movement !== 'all' && !empty($movement)) || !empty($date);
    @endphp

    <!-- 5. Search & Multi-Filter Card -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <form action="{{ route('admin.smart-city.prices.index') }}" 
              method="GET" 
              class="space-y-4">
            <input type="hidden" name="category" value="{{ $category }}">

            <!-- Main Filter Controls Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-center">
                
                <!-- 1. Search Box -->
                <div class="lg:col-span-4 relative flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari komoditas, pasar, catatan..." 
                           class="w-full h-11 pl-10 pr-9 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs font-medium text-gray-800 placeholder-gray-400 focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs">
                    @if(request('q'))
                        <a href="{{ route('admin.smart-city.prices.index', ['category' => $category, 'market' => $market, 'movement' => $movement, 'date' => $date]) }}" 
                           title="Hapus kata kunci"
                           class="absolute right-3 z-10 p-1 rounded-lg hover:bg-gray-200 text-gray-400 hover:text-gray-700 transition-colors">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

                <!-- 2. Market Filter Dropdown -->
                <div class="lg:col-span-3 relative flex items-center">
                    <i data-lucide="store" class="w-4 h-4 text-emerald-600 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <select name="market" 
                            onchange="this.form.submit()" 
                            data-search-limit="50"
                            class="w-full h-11 pl-10 pr-8 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs text-gray-800 font-semibold focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs appearance-none">
                        <option value="all">Semua Pasar Tradisional</option>
                        @foreach($markets as $m)
                            <option value="{{ $m }}" {{ $market === $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3.5 pointer-events-none"></i>
                </div>

                <!-- 3. Movement Filter Dropdown -->
                <div class="lg:col-span-2 relative flex items-center">
                    <i data-lucide="trending-up" class="w-4 h-4 text-amber-600 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <select name="movement" 
                            onchange="this.form.submit()" 
                            class="w-full h-11 pl-10 pr-8 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs text-gray-800 font-semibold focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs appearance-none">
                        <option value="all" {{ $movement === 'all' ? 'selected' : '' }}>Semua Tren</option>
                        <option value="up" {{ $movement === 'up' ? 'selected' : '' }}>Harga Naik (&uarr;)</option>
                        <option value="down" {{ $movement === 'down' ? 'selected' : '' }}>Harga Turun (&darr;)</option>
                        <option value="stable" {{ $movement === 'stable' ? 'selected' : '' }}>Harga Stabil</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3.5 pointer-events-none"></i>
                </div>

                <!-- 4. Date Picker Filter -->
                <div class="lg:col-span-2 relative flex items-center">
                    <input type="date" 
                           name="date" 
                           value="{{ $date }}" 
                           onchange="this.form.submit()" 
                           class="w-full h-11 px-3.5 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs text-gray-800 font-semibold focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs">
                </div>

                <!-- 5. Submit / Action Button -->
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

                    @if(!$hasActiveFilter)
                        <span class="px-2.5 py-1 rounded-xl bg-gray-100 text-gray-500 text-[11px] font-medium">
                            Menampilkan seluruh data
                        </span>
                    @endif

                    <!-- Active Category Chip -->
                    @if($category !== 'all' && !empty($category))
                        <a href="{{ route('admin.smart-city.prices.index', ['category' => 'all', 'market' => $market, 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold hover:bg-emerald-100 transition-colors">
                            <span>Kategori: <strong>{{ str_replace('_', ' ', $category) }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-emerald-600"></i>
                        </a>
                    @endif

                    <!-- Active Market Chip -->
                    @if($market !== 'all' && !empty($market))
                        <a href="{{ route('admin.smart-city.prices.index', ['category' => $category, 'market' => 'all', 'movement' => $movement, 'date' => $date, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-[11px] font-bold hover:bg-blue-100 transition-colors">
                            <span>Pasar: <strong>{{ $market }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-blue-600"></i>
                        </a>
                    @endif

                    <!-- Active Movement Chip -->
                    @if($movement !== 'all' && !empty($movement))
                        <a href="{{ route('admin.smart-city.prices.index', ['category' => $category, 'market' => $market, 'movement' => 'all', 'date' => $date, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-bold hover:bg-amber-100 transition-colors">
                            <span>Tren: <strong>{{ $movement === 'up' ? 'Naik' : ($movement === 'down' ? 'Turun' : 'Stabil') }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-amber-600"></i>
                        </a>
                    @endif

                    <!-- Active Date Chip -->
                    @if(!empty($date))
                        <a href="{{ route('admin.smart-city.prices.index', ['category' => $category, 'market' => $market, 'movement' => $movement, 'date' => '', 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-50 border border-purple-200 text-purple-800 text-[11px] font-bold hover:bg-purple-100 transition-colors">
                            <span>Tanggal: <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-purple-600"></i>
                        </a>
                    @endif

                    <!-- Active Keyword Chip -->
                    @if(request('q'))
                        <a href="{{ route('admin.smart-city.prices.index', ['category' => $category, 'market' => $market, 'movement' => $movement, 'date' => $date]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-100 border border-gray-300 text-gray-800 text-[11px] font-bold hover:bg-gray-200 transition-colors">
                            <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                            <i data-lucide="x" class="w-3 h-3 text-gray-500"></i>
                        </a>
                    @endif

                    <!-- Reset All Button -->
                    @if($hasActiveFilter)
                        <a href="{{ route('admin.smart-city.prices.index') }}" 
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-[11px] font-bold transition-colors">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                            <span>Hapus Semua Filter</span>
                        </a>
                    @endif
                </div>

                <!-- Result Counter -->
                <div class="font-medium text-gray-500 text-[11px]">
                    Ditemukan <span class="font-bold text-gray-900">{{ $prices->total() }}</span> catatan komoditas
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
                        <th class="px-6 py-4">Komoditas & Kategori</th>
                        <th class="px-6 py-4">Pasar Pemantauan</th>
                        <th class="px-6 py-4">Harga Saat Ini</th>
                        <th class="px-6 py-4">Harga Sebelumnya</th>
                        <th class="px-6 py-4">Perubahan & Tren</th>
                        <th class="px-6 py-4">Tanggal Data</th>
                        <th class="px-6 py-4">Petugas</th>
                        <th class="px-6 py-4 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($prices as $p)
                        @php
                            $diff = $p->price_difference;
                            $hasPrev = !empty($p->previous_price) && $p->previous_price > 0;
                            $pctDiff = $hasPrev ? (($p->price - $p->previous_price) / $p->previous_price) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-amber-50/30 transition-colors group">
                            
                            <!-- 1. Komoditas & Kategori -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs font-black text-sm {{
                                        in_array($p->category, ['sembako', 'beras']) ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : (
                                        in_array($p->category, ['bumbu_dapur', 'cabai', 'bawang']) ? 'bg-rose-50 text-rose-600 border border-rose-200' : (
                                        in_array($p->category, ['daging_ikan', 'daging', 'ikan']) ? 'bg-blue-50 text-blue-600 border border-blue-200' : (
                                        in_array($p->category, ['sayur_mayur', 'sayuran', 'sayur']) ? 'bg-teal-50 text-teal-600 border border-teal-200' : (
                                        in_array($p->category, ['telur_susu', 'telur']) ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-700 border border-gray-200'))))
                                    }}">
                                        @if(in_array($p->category, ['sembako', 'beras']))
                                            <i data-lucide="wheat" class="w-5 h-5"></i>
                                        @elseif(in_array($p->category, ['bumbu_dapur', 'cabai', 'bawang']))
                                            <i data-lucide="flame" class="w-5 h-5"></i>
                                        @elseif(in_array($p->category, ['daging_ikan', 'daging', 'ikan']))
                                            <i data-lucide="fish" class="w-5 h-5"></i>
                                        @elseif(in_array($p->category, ['sayur_mayur', 'sayuran', 'sayur']))
                                            <i data-lucide="leaf" class="w-5 h-5"></i>
                                        @elseif(in_array($p->category, ['telur_susu', 'telur']))
                                            <i data-lucide="egg" class="w-5 h-5"></i>
                                        @else
                                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                                        @endif
                                    </div>
                                    <div class="space-y-0.5 max-w-xs">
                                        <span class="font-bold text-gray-900 text-sm block group-hover:text-amber-700 transition-colors leading-snug">
                                            {{ $p->commodity_name }}
                                        </span>
                                        <span class="text-[11px] text-gray-400 capitalize flex items-center gap-1.5">
                                            <span>{{ str_replace('_', ' ', $p->category) }}</span>
                                            <span>&bull;</span>
                                            <span class="font-medium text-gray-600">Per {{ $p->unit }}</span>
                                        </span>
                                        @if($p->notes)
                                            <p class="text-[10px] text-gray-500 italic line-clamp-1 mt-0.5">"{{ $p->notes }}"</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Pasar Pemantauan -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 text-gray-800 text-[11px] font-bold border border-gray-200/80">
                                    <i data-lucide="store" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>{{ $p->market_name }}</span>
                                </span>
                            </td>

                            <!-- 3. Harga Saat Ini -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-0.5">
                                    <span class="font-mono text-sm sm:text-base font-black text-gray-900 block">
                                        Rp {{ number_format($p->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-gray-400">/ {{ $p->unit }}</span>
                                </div>
                            </td>

                            <!-- 4. Harga Sebelumnya -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-500">
                                @if($hasPrev)
                                    <span class="text-xs font-bold text-gray-600">Rp {{ number_format($p->previous_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">Belum ada data</span>
                                @endif
                            </td>

                            <!-- 5. Perubahan & Tren -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($hasPrev)
                                    @if($p->price > $p->previous_price)
                                        <div class="inline-flex flex-col items-start">
                                            <span class="px-2.5 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-black inline-flex items-center gap-1">
                                                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                                                <span>+Rp {{ number_format(abs($diff), 0, ',', '.') }}</span>
                                            </span>
                                            <span class="text-[10px] font-bold text-rose-600 mt-0.5 ml-1">
                                                +{{ number_format($pctDiff, 1) }}% (Naik)
                                            </span>
                                        </div>
                                    @elseif($p->price < $p->previous_price)
                                        <div class="inline-flex flex-col items-start">
                                            <span class="px-2.5 py-1 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 text-[11px] font-black inline-flex items-center gap-1">
                                                <i data-lucide="trending-down" class="w-3.5 h-3.5"></i>
                                                <span>-Rp {{ number_format(abs($diff), 0, ',', '.') }}</span>
                                            </span>
                                            <span class="text-[10px] font-bold text-teal-700 mt-0.5 ml-1">
                                                {{ number_format($pctDiff, 1) }}% (Turun)
                                            </span>
                                        </div>
                                    @else
                                        <span class="px-2.5 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-[11px] font-bold inline-flex items-center gap-1">
                                            <i data-lucide="minus" class="w-3 h-3"></i>
                                            <span>Tetap (Stabil)</span>
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2.5 py-1 rounded-xl bg-gray-50 border border-gray-200 text-gray-500 text-[10px] font-medium">
                                        Data Awal
                                    </span>
                                @endif
                            </td>

                            <!-- 6. Tanggal Data -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                <div class="flex items-center gap-1.5 font-mono text-xs font-bold text-gray-800">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                    <span>{{ $p->recorded_date ? $p->recorded_date->translatedFormat('d M Y') : '-' }}</span>
                                </div>
                            </td>

                            <!-- 7. Petugas Input -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-gray-400"></i>
                                    <span class="text-[11px] font-medium truncate max-w-[120px]">
                                        {{ $p->creator->name ?? 'Admin Redaksi' }}
                                    </span>
                                </div>
                            </td>

                            <!-- 8. Aksi Manajemen (Edit & Hapus) -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit Link to Dedicated Edit Page -->
                                    <a href="{{ route('admin.smart-city.prices.edit', $p->id) }}" 
                                       title="Edit Data Harga" 
                                       class="h-8 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs inline-flex items-center gap-1.5 border border-amber-200/80 transition-colors shadow-2xs">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete Button Triggering Form -->
                                    <form action="{{ route('admin.smart-city.prices.destroy', $p->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan harga untuk komoditas {{ addslashes($p->commodity_name) }} di {{ addslashes($p->market_name) }}?');" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Data Harga" 
                                                class="h-8 w-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 inline-flex items-center justify-center border border-rose-200/80 transition-colors cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto shadow-2xs">
                                        <i data-lucide="search-x" class="w-7 h-7"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900">Tidak ada data harga pangan yang cocok</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Coba sesuaikan kata kunci pencarian, filter pasar, atau tambahkan data komoditas baru.
                                    </p>
                                    <a href="{{ route('admin.smart-city.prices.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-brand-gold text-brand-black rounded-xl text-xs font-bold shadow hover:bg-amber-400 transition-colors mt-2">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        <span>Input Data Komoditas Baru</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($prices->hasPages())
            <div class="p-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-gray-500 font-medium">
                    Menampilkan <span class="font-bold text-gray-900">{{ $prices->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $prices->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $prices->total() }}</span> data harga
                </div>
                <div>
                    {{ $prices->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
