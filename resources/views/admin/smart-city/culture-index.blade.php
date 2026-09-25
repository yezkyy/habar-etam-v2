@extends('layouts.admin')

@section('title', 'Direktori Budaya & Wisata Kukar — Admin Smart City')
@section('page_title', 'Smart City: Cagar Budaya, Sejarah & Wisata Kukar')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- 1. Top Hero Header Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-amber-950/70 to-slate-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-amber-500/20">
        <!-- Ambient decorative glows -->
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-72 -top-12 w-48 h-48 bg-yellow-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2.5">
            <div class="flex items-center gap-2 text-xs text-amber-200/80">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-300 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-amber-200/60">Smart City</span>
                <span>/</span>
                <span class="text-amber-400 font-semibold">Budaya & Wisata</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-300 shadow-inner">
                        <i data-lucide="landmark" class="w-6 h-6"></i>
                    </span>
                    <span>Khasanah Budaya, Sejarah & Wisata Kukar</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Kelola inventarisasi cagar budaya Kesultanan Kutai Kartanegara Ing Martadipura, museum, festival adat tradisi, serta destinasi wisata alam se-Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-3 self-start lg:self-center">
            <a href="{{ route('smart-city.culture') }}" target="_blank"
               class="h-11 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-2xl transition-all inline-flex items-center gap-2 border border-white/15 shadow-sm backdrop-blur-md cursor-pointer">
                <i data-lucide="external-link" class="w-4 h-4 text-amber-300"></i>
                <span>Lihat Portal Publik</span>
            </a>
            <a href="{{ route('admin.smart-city.culture.create') }}" 
               class="h-11 px-5 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-gray-950 text-xs font-extrabold rounded-2xl shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 transition-all inline-flex items-center gap-2 cursor-pointer transform active:scale-95">
                <i data-lucide="plus" class="w-4 h-4 text-gray-950 stroke-[3]"></i>
                <span>Tambah Destinasi</span>
            </a>
        </div>
    </div>

    <!-- Success & Status Notifications -->
    @if(session('success'))
        <div class="p-4 sm:p-5 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-xs text-emerald-800 flex items-start sm:items-center justify-between gap-3 shadow-xs animate-fadeIn">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-500 text-white rounded-xl shadow-xs">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-950 text-xs sm:text-sm">Operasi Berhasil</h4>
                    <p class="text-[11px] sm:text-xs text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    <!-- 2. Summary KPI Metric Cards (5 Cards) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
        <!-- Total Objek -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Destinasi</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-700">
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-gray-950 tracking-tight">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Situs & Objek Terdata</span>
            </div>
        </div>

        <!-- Terbit / Published -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider text-emerald-600 uppercase">Tayang Publik</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <i data-lucide="globe-2" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight">{{ number_format($stats['published']) }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Dapat Diakses Warga</span>
            </div>
        </div>

        <!-- Draft / Arsip -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider text-slate-500 uppercase">Draft / Arsip</span>
                <span class="p-2 rounded-xl bg-slate-100 text-slate-700">
                    <i data-lucide="file-edit" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">{{ number_format($stats['draft']) }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Belum Dipublikasikan</span>
            </div>
        </div>

        <!-- Cagar Kesultanan -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider text-amber-700 uppercase">Kesultanan Kutai</span>
                <span class="p-2 rounded-xl bg-amber-100 text-amber-800">
                    <i data-lucide="crown" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-amber-800 tracking-tight">{{ number_format($stats['kesultanan_count']) }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Situs Keraton & Makam</span>
            </div>
        </div>

        <!-- Wilayah Kecamatan -->
        <div class="col-span-2 lg:col-span-1 bg-white p-4 sm:p-5 rounded-3xl border border-gray-100 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold tracking-wider text-blue-600 uppercase">Kecamatan</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </span>
            </div>
            <div class="mt-3">
                <span class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight">{{ number_format($stats['districts_count']) }}</span>
                <span class="text-[11px] text-gray-400 block mt-0.5">Sebaran Wilayah Kukar</span>
            </div>
        </div>
    </div>

    <!-- 3. Category Quick Filter Pill Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'all' ? 'bg-gray-950 text-white border-gray-950 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
            <span>Semua Destinasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $categoryCounts['all'] }}</span>
        </a>

        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'kesultanan'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'kesultanan' ? 'bg-amber-600 text-white border-amber-600 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-amber-50/50 hover:text-amber-800' }}">
            <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-500 {{ $category === 'kesultanan' ? 'text-white' : '' }}"></i>
            <span>Kesultanan Kutai</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'kesultanan' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-900' }}">{{ $categoryCounts['kesultanan'] }}</span>
        </a>

        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'museum_sejarah'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'museum_sejarah' ? 'bg-purple-600 text-white border-purple-600 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-purple-50/50 hover:text-purple-800' }}">
            <i data-lucide="landmark" class="w-3.5 h-3.5 text-purple-500 {{ $category === 'museum_sejarah' ? 'text-white' : '' }}"></i>
            <span>Museum & Sejarah</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'museum_sejarah' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-900' }}">{{ $categoryCounts['museum_sejarah'] }}</span>
        </a>

        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'wisata_alam'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'wisata_alam' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-emerald-50/50 hover:text-emerald-800' }}">
            <i data-lucide="trees" class="w-3.5 h-3.5 text-emerald-500 {{ $category === 'wisata_alam' ? 'text-white' : '' }}"></i>
            <span>Wisata Alam & Rekreasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'wisata_alam' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-900' }}">{{ $categoryCounts['wisata_alam'] }}</span>
        </a>

        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'festival_adat'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'festival_adat' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-rose-50/50 hover:text-rose-800' }}">
            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-rose-500 {{ $category === 'festival_adat' ? 'text-white' : '' }}"></i>
            <span>Festival & Adat Tradisi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'festival_adat' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-900' }}">{{ $categoryCounts['festival_adat'] }}</span>
        </a>

        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'kuliner_tradisi'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'kuliner_tradisi' ? 'bg-orange-600 text-white border-orange-600 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-orange-50/50 hover:text-orange-800' }}">
            <i data-lucide="utensils-crossed" class="w-3.5 h-3.5 text-orange-500 {{ $category === 'kuliner_tradisi' ? 'text-white' : '' }}"></i>
            <span>Kuliner Tradisi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'kuliner_tradisi' ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-900' }}">{{ $categoryCounts['kuliner_tradisi'] }}</span>
        </a>

        <a href="{{ route('admin.smart-city.culture.index', array_merge(request()->except('category', 'page'), ['category' => 'lainnya'])) }}"
           class="px-4 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border {{ $category === 'lainnya' ? 'bg-slate-700 text-white border-slate-700 shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            <i data-lucide="more-horizontal" class="w-3.5 h-3.5"></i>
            <span>Lainnya</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $category === 'lainnya' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $categoryCounts['lainnya'] }}</span>
        </a>
    </div>

    <!-- 4. Search & Multi-Filter Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-5 space-y-4">
        <form method="GET" action="{{ route('admin.smart-city.culture.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            <!-- Hidden Category if active -->
            @if($category !== 'all')
                <input type="hidden" name="category" value="{{ $category }}">
            @endif

            <!-- Search input -->
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" 
                       placeholder="Cari nama destinasi, cagar, alamat, narasi sejarah..."
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
            </div>

            <!-- Kategori dropdown -->
            <div class="lg:col-span-3">
                <select name="category" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                    <option value="all">Semua Kategori</option>
                    <option value="kesultanan" {{ $category === 'kesultanan' ? 'selected' : '' }}>Kesultanan Kutai</option>
                    <option value="museum_sejarah" {{ $category === 'museum_sejarah' ? 'selected' : '' }}>Museum & Sejarah</option>
                    <option value="wisata_alam" {{ $category === 'wisata_alam' ? 'selected' : '' }}>Wisata Alam & Rekreasi</option>
                    <option value="festival_adat" {{ $category === 'festival_adat' ? 'selected' : '' }}>Festival & Adat Tradisi</option>
                    <option value="kuliner_tradisi" {{ $category === 'kuliner_tradisi' ? 'selected' : '' }}>Kuliner Tradisi</option>
                    <option value="lainnya" {{ $category === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <!-- Status dropdown -->
            <div class="lg:col-span-2">
                <select name="status" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                    <option value="all">Semua Status</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Tayang Publik</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft / Arsip</option>
                </select>
            </div>

            <!-- Kecamatan dropdown -->
            <div class="lg:col-span-2">
                <select name="district" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                    <option value="all">Semua Kecamatan</option>
                    @foreach($districts as $dName)
                        <option value="{{ $dName }}" {{ $district === $dName ? 'selected' : '' }}>Kec. {{ $dName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="lg:col-span-1 flex items-center gap-2">
                <button type="submit" 
                        class="w-full h-10 bg-amber-500 hover:bg-amber-600 text-gray-950 text-xs font-bold rounded-2xl transition-all flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Filter</span>
                </button>
            </div>
        </form>

        <!-- Active Filter Badges & Reset Action -->
        @php
            $hasActiveFilter = request()->filled('q') || ($category !== 'all' && !empty($category)) || ($status !== 'all' && !empty($status)) || ($district !== 'all' && !empty($district));
        @endphp

        @if($hasActiveFilter)
            <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[11px] font-bold text-gray-400">Filter Aktif:</span>
                    
                    @if(request()->filled('q'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-medium">
                            <span>Pencarian: "<strong>{{ request('q') }}</strong>"</span>
                            <a href="{{ route('admin.smart-city.culture.index', request()->except('q', 'page')) }}" class="text-amber-500 hover:text-amber-800"><i data-lucide="x" class="w-3 h-3"></i></a>
                        </span>
                    @endif

                    @if($category !== 'all' && !empty($category))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-medium">
                            <span>Kategori: <strong>{{ ucfirst(str_replace('_', ' ', $category)) }}</strong></span>
                            <a href="{{ route('admin.smart-city.culture.index', request()->except('category', 'page')) }}" class="text-amber-500 hover:text-amber-800"><i data-lucide="x" class="w-3 h-3"></i></a>
                        </span>
                    @endif

                    @if($status !== 'all' && !empty($status))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-medium">
                            <span>Status: <strong>{{ $status === 'published' ? 'Tayang Publik' : 'Draft' }}</strong></span>
                            <a href="{{ route('admin.smart-city.culture.index', request()->except('status', 'page')) }}" class="text-emerald-500 hover:text-emerald-800"><i data-lucide="x" class="w-3 h-3"></i></a>
                        </span>
                    @endif

                    @if($district !== 'all' && !empty($district))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-medium">
                            <span>Kecamatan: <strong>{{ $district }}</strong></span>
                            <a href="{{ route('admin.smart-city.culture.index', request()->except('district', 'page')) }}" class="text-blue-500 hover:text-blue-800"><i data-lucide="x" class="w-3 h-3"></i></a>
                        </span>
                    @endif
                </div>

                <a href="{{ route('admin.smart-city.culture.index') }}" 
                   class="text-xs font-bold text-rose-600 hover:text-rose-700 inline-flex items-center gap-1.5 transition-colors">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Reset Semua Filter</span>
                </a>
            </div>
        @endif
    </div>

    <!-- 5. Main Data Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-gray-50/75 text-gray-500 border-b border-gray-100 uppercase tracking-wider text-[10px] font-bold">
                    <tr>
                        <th class="px-6 py-4">Destinasi / Objek Budaya</th>
                        <th class="px-6 py-4">Kategori Warisan</th>
                        <th class="px-6 py-4">Kecamatan & Koordinat</th>
                        <th class="px-6 py-4">Operasional & Tiket</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($destinations as $d)
                        <tr class="hover:bg-amber-50/30 transition-colors group">
                            <!-- Destinasi & Foto Cover -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 overflow-hidden shrink-0 flex items-center justify-center relative shadow-xs">
                                        @if($d->cover_image)
                                            <img src="{{ str_starts_with($d->cover_image, 'http') ? $d->cover_image : asset('storage/' . $d->cover_image) }}" 
                                                 alt="{{ $d->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <i data-lucide="landmark" class="w-6 h-6 text-amber-400"></i>
                                        @endif
                                    </div>
                                    <div class="space-y-1 min-w-0 max-w-sm">
                                        <h4 class="font-extrabold text-gray-900 text-sm tracking-tight group-hover:text-amber-800 transition-colors truncate">
                                            {{ $d->title }}
                                        </h4>
                                        <p class="text-gray-500 text-[11px] line-clamp-1 leading-relaxed">
                                            {{ $d->address }}
                                        </p>
                                        <div class="flex items-center gap-2 pt-0.5">
                                            <span class="text-[10px] text-gray-400">Slug: <code class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded text-[10px]">{{ $d->slug }}</code></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori Warisan -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $catStyles = [
                                        'kesultanan' => 'bg-amber-100 text-amber-900 border-amber-200',
                                        'museum_sejarah' => 'bg-purple-100 text-purple-900 border-purple-200',
                                        'wisata_alam' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                                        'festival_adat' => 'bg-rose-100 text-rose-900 border-rose-200',
                                        'kuliner_tradisi' => 'bg-orange-100 text-orange-900 border-orange-200',
                                    ];
                                    $catNames = [
                                        'kesultanan' => 'Kesultanan Kutai',
                                        'museum_sejarah' => 'Museum & Sejarah',
                                        'wisata_alam' => 'Wisata Alam',
                                        'festival_adat' => 'Festival & Adat',
                                        'kuliner_tradisi' => 'Kuliner Tradisi',
                                    ];
                                    $style = $catStyles[$d->category] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                    $label = $catNames[$d->category] ?? ucfirst(str_replace('_', ' ', $d->category));
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold border {{ $style }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <!-- Kecamatan & Koordinat -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-500"></i>
                                        <span>Kec. {{ $d->location_district }}</span>
                                    </div>
                                    @if($d->latitude && $d->longitude)
                                        <a href="https://maps.google.com/?q={{ $d->latitude }},{{ $d->longitude }}" 
                                           target="_blank"
                                           class="text-[11px] text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                            <i data-lucide="navigation" class="w-3 h-3"></i>
                                            <span>{{ number_format($d->latitude, 4) }}, {{ number_format($d->longitude, 4) }}</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Koordinat belum diatur</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Operasional & Tiket -->
                            <td class="px-6 py-4">
                                <div class="space-y-1 max-w-xs">
                                    <div class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i>
                                        <span>{{ $d->operating_info ?: 'Setiap Hari / Fleksibel' }}</span>
                                    </div>
                                    @if($d->historical_context)
                                        <span class="text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md inline-block line-clamp-1 border border-amber-100" title="{{ $d->historical_context }}">
                                            Konteks: {{ Str::limit($d->historical_context, 35) }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Publikasi -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($d->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Tayang Publik</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        <span>Draft / Arsip</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($d->status === 'published')
                                        <a href="{{ route('smart-city.culture.show', $d->slug) }}" 
                                           target="_blank"
                                           class="p-2 text-gray-500 hover:text-amber-700 hover:bg-amber-50 rounded-xl transition-all cursor-pointer"
                                           title="Lihat Halaman Publik">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.smart-city.culture.edit', $d->id) }}" 
                                       class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-xl transition-all cursor-pointer"
                                       title="Edit Destinasi">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>

                                    <form action="{{ route('admin.smart-city.culture.destroy', $d->id) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi budaya \'{{ addslashes($d->title) }}\'? Data yang dihapus tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-all cursor-pointer"
                                                title="Hapus Destinasi">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-16 h-16 bg-amber-50 text-amber-400 rounded-3xl flex items-center justify-center mx-auto border border-amber-100 shadow-inner">
                                        <i data-lucide="landmark" class="w-8 h-8"></i>
                                    </div>
                                    <h3 class="text-base font-extrabold text-gray-900">Belum Ada Destinasi Budaya</h3>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Tidak ditemukan data cagar budaya atau destinasi wisata yang sesuai dengan filter pencarian saat ini.
                                    </p>
                                    <div class="pt-2 flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.smart-city.culture.create') }}" 
                                           class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-gray-950 text-xs font-bold rounded-xl shadow-xs transition-all inline-flex items-center gap-2">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            <span>Tambah Destinasi Baru</span>
                                        </a>
                                        @if($hasActiveFilter)
                                            <a href="{{ route('admin.smart-city.culture.index') }}" 
                                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-all">
                                                Reset Filter
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($destinations->hasPages())
            <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                {{ $destinations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
