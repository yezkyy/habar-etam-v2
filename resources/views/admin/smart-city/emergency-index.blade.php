@extends('layouts.admin')

@section('title', 'Kelola Kontak Darurat & Hotline — Smart City Kukar')
@section('page_title', 'Smart City: Direktori Tanggap Darurat Kukar')

@section('content')
<div class="space-y-6 pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-6 sm:p-7 rounded-3xl shadow-lg relative overflow-hidden border border-white/5">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-72 -top-12 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-400">Smart City</span>
                <span>/</span>
                <span class="text-brand-gold font-semibold">Kontak Darurat & Hotline</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-rose-500/20 border border-rose-500/30 text-rose-400">
                        <i data-lucide="phone-call" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Direktori Tanggap Darurat & Hotline Penting</span>
                </h1>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                    Siaga 24 Jam
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-3xl leading-relaxed">
                Pusat manajemen direktori nomor telepon cepat tanggap (Damkar, Kepolisian 110, IGD RSUD, BPBD, PLN, PDAM) di seluruh 20 Kecamatan Kabupaten Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('smart-city.emergency') }}" target="_blank" 
               class="h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer">
                <i data-lucide="external-link" class="w-4 h-4 text-brand-gold"></i>
                <span>Tampilan Portal Warga</span>
            </a>
            <a href="{{ route('admin.smart-city.emergency.create') }}" 
               class="h-10 px-5 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-xl transition-all inline-flex items-center gap-2 shadow-lg shadow-brand-gold/25 cursor-pointer hover:scale-[1.02]">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Kontak Darurat</span>
            </a>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between gap-3 text-xs text-emerald-800 shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 space-y-1 shadow-2xs">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>Terjadi kesalahan saat menyimpan data:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 text-[11px]">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2. Summary Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <!-- Total Unit Kontak -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Kontak Terdata</span>
                <span class="p-2.5 rounded-2xl bg-gray-50 text-gray-700 border border-gray-100">
                    <i data-lucide="book-marked" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-bold text-gray-500">unit hotline</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-brand-gold"></span>
                <span>Tersebar di {{ $stats['districts_count'] }} kecamatan Kukar</span>
            </div>
        </div>

        <!-- Hotline Aktif Publik -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Status Aktif Tayang</span>
                <span class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <i data-lucide="shield-check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight">{{ number_format($stats['active']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700">online</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
                <span>{{ $stats['inactive'] }} unit dinonaktifkan</span>
                <span class="text-emerald-600 font-bold">100% Verified</span>
            </div>
        </div>

        <!-- Layanan Siaga 24 Jam Utama -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-rose-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-rose-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    Posko Siaga Kritis
                </span>
                <span class="p-2.5 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
                    <i data-lucide="siren" class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-rose-600 tracking-tight">{{ number_format($stats['priority_24h']) }}</span>
                <span class="text-[11px] font-bold text-rose-700">unit prioritas</span>
            </div>
            <div class="mt-2 text-[11px] text-rose-600 font-medium">
                Damkar, Polisi 110, IGD & SAR BPBD
            </div>
        </div>

        <!-- Terhubung WhatsApp -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Integrasi WhatsApp</span>
                <span class="p-2.5 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100">
                    <i data-lucide="message-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-teal-700 tracking-tight">{{ number_format($stats['whatsapp_count']) }}</span>
                <span class="text-[11px] font-bold text-teal-700">posko WA</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 font-medium">
                Respon cepat chat & share lokasi
            </div>
        </div>
    </div>

    <!-- 3. Category Filter Ribbon -->
    <div class="space-y-2">
        <div class="flex items-center justify-between px-1">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>Kelompok Instansi Kedaruratan</span>
            </span>
            <span class="text-[11px] text-gray-400 font-medium">Klik untuk memfilter jenis layanan</span>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-semibold no-scrollbar">
            <!-- Semua -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'all', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'all' || empty($category) ? 'bg-gray-950 text-white shadow-md font-black ring-2 ring-amber-500/20' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 {{ $category === 'all' || empty($category) ? 'text-brand-gold' : 'text-gray-400' }}"></i>
                <span>Semua Kategori</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'all' || empty($category) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['all'] }}</span>
            </a>

            <!-- Medis & RSUD -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'medis', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'medis' ? 'bg-rose-950 text-white shadow-md font-black ring-2 ring-rose-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-rose-200' }}">
                <i data-lucide="cross" class="w-3.5 h-3.5 text-rose-400"></i>
                <span>Medis & RSUD</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'medis' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-800' }}">{{ $categoryCounts['medis'] }}</span>
            </a>

            <!-- Damkar -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'damkar', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'damkar' ? 'bg-amber-950 text-white shadow-md font-black ring-2 ring-amber-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-amber-200' }}">
                <i data-lucide="flame" class="w-3.5 h-3.5 text-orange-400"></i>
                <span>Damkar</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'damkar' ? 'bg-white/20 text-white' : 'bg-orange-50 text-orange-800' }}">{{ $categoryCounts['damkar'] }}</span>
            </a>

            <!-- Polisi -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'polisi', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'polisi' ? 'bg-blue-950 text-white shadow-md font-black ring-2 ring-blue-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-blue-200' }}">
                <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-blue-400"></i>
                <span>Kepolisian</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'polisi' ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-800' }}">{{ $categoryCounts['polisi'] }}</span>
            </a>

            <!-- BPBD / SAR -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'sar_bpbd', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'sar_bpbd' ? 'bg-yellow-950 text-white shadow-md font-black ring-2 ring-yellow-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-yellow-200' }}">
                <i data-lucide="life-buoy" class="w-3.5 h-3.5 text-yellow-400"></i>
                <span>BPBD & SAR</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'sar_bpbd' ? 'bg-white/20 text-white' : 'bg-yellow-50 text-yellow-800' }}">{{ $categoryCounts['sar_bpbd'] }}</span>
            </a>

            <!-- PDAM -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'pdam', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'pdam' ? 'bg-cyan-950 text-white shadow-md font-black ring-2 ring-cyan-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-cyan-200' }}">
                <i data-lucide="droplet" class="w-3.5 h-3.5 text-cyan-400"></i>
                <span>PDAM Air</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'pdam' ? 'bg-white/20 text-white' : 'bg-cyan-50 text-cyan-800' }}">{{ $categoryCounts['pdam'] }}</span>
            </a>

            <!-- PLN -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'pln', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'pln' ? 'bg-amber-950 text-white shadow-md font-black ring-2 ring-amber-500/30' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50 hover:border-amber-200' }}">
                <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>PLN Listrik</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'pln' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-800' }}">{{ $categoryCounts['pln'] }}</span>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'lainnya', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category === 'lainnya' ? 'bg-gray-800 text-white shadow-md font-black' : 'bg-white border border-gray-200/80 text-gray-600 hover:bg-gray-50' }}">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-gray-400"></i>
                <span>Lainnya</span>
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $category === 'lainnya' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['lainnya'] }}</span>
            </a>
        </div>
    </div>

    @php
        $hasActiveEmergencyFilter = request('q') || ($category !== 'all' && !empty($category)) || ($district !== 'all' && !empty($district)) || ($status !== 'all' && !empty($status));
    @endphp

    <!-- 4. Search & Multi-Filter Card -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <form action="{{ route('admin.smart-city.emergency.index') }}" 
              method="GET" 
              class="space-y-4">
            <input type="hidden" name="category" value="{{ $category }}">

            <!-- Main Filter Controls Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-center">
                <!-- Search Box -->
                <div class="lg:col-span-5 relative flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari nama posko, unit, telepon, alamat..." 
                           class="w-full h-11 pl-10 pr-9 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs font-medium text-gray-800 placeholder-gray-400 focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs">
                    @if(request('q'))
                        <a href="{{ route('admin.smart-city.emergency.index', ['category' => $category, 'district' => $district, 'status' => $status]) }}" 
                           title="Hapus kata kunci"
                           class="absolute right-3 z-10 p-1 rounded-lg hover:bg-gray-200 text-gray-400 hover:text-gray-700 transition-colors">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

                <!-- District Filter Dropdown -->
                <div class="lg:col-span-3 relative flex items-center">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-600 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
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

                <!-- Status Filter Dropdown -->
                <div class="lg:col-span-3 relative flex items-center">
                    <i data-lucide="toggle-right" class="w-4 h-4 text-emerald-600 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <select name="status" 
                            onchange="this.form.submit()" 
                            class="w-full h-11 pl-10 pr-8 rounded-2xl bg-gray-50/80 border border-gray-200 text-xs text-gray-800 font-semibold focus:bg-white focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/30 outline-none transition-all shadow-2xs appearance-none">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status Publikasi</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Hanya Aktif (Tayang)</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Hanya Nonaktif (Disembunyikan)</option>
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

                    @if(!$hasActiveEmergencyFilter)
                        <span class="px-2.5 py-1 rounded-xl bg-gray-100 text-gray-500 text-[11px] font-medium">
                            Menampilkan seluruh kontak
                        </span>
                    @endif

                    <!-- Active Category Chip -->
                    @if($category !== 'all' && !empty($category))
                        <a href="{{ route('admin.smart-city.emergency.index', ['category' => 'all', 'district' => $district, 'status' => $status, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-bold hover:bg-amber-100 transition-colors">
                            <span>Kategori: <strong>{{ str_replace('_', ' ', $category) }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-amber-600"></i>
                        </a>
                    @endif

                    <!-- Active District Chip -->
                    @if($district !== 'all' && !empty($district))
                        <a href="{{ route('admin.smart-city.emergency.index', ['category' => $category, 'district' => 'all', 'status' => $status, 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-[11px] font-bold hover:bg-blue-100 transition-colors">
                            <span>Kecamatan: <strong>Kec. {{ $district }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-blue-600"></i>
                        </a>
                    @endif

                    <!-- Active Status Chip -->
                    @if($status !== 'all' && !empty($status))
                        <a href="{{ route('admin.smart-city.emergency.index', ['category' => $category, 'district' => $district, 'status' => 'all', 'q' => request('q')]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold hover:bg-emerald-100 transition-colors">
                            <span>Status: <strong>{{ $status === 'active' ? 'Aktif' : 'Nonaktif' }}</strong></span>
                            <i data-lucide="x" class="w-3 h-3 text-emerald-600"></i>
                        </a>
                    @endif

                    <!-- Active Keyword Chip -->
                    @if(request('q'))
                        <a href="{{ route('admin.smart-city.emergency.index', ['category' => $category, 'district' => $district, 'status' => $status]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-100 border border-gray-300 text-gray-800 text-[11px] font-bold hover:bg-gray-200 transition-colors">
                            <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                            <i data-lucide="x" class="w-3 h-3 text-gray-500"></i>
                        </a>
                    @endif

                    <!-- Reset All Button -->
                    @if($hasActiveEmergencyFilter)
                        <a href="{{ route('admin.smart-city.emergency.index') }}" 
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-[11px] font-bold transition-colors">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                            <span>Hapus Semua Filter</span>
                        </a>
                    @endif
                </div>

                <!-- Result Counter -->
                <div class="font-medium text-gray-500 text-[11px]">
                    Ditemukan <span class="font-bold text-gray-900">{{ $contacts->total() }}</span> kontak posko
                </div>
            </div>
        </form>
    </div>

    <!-- 5. Table of Emergency Contacts -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/75 text-gray-500 border-b border-gray-100 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-6 py-4">Instansi & Posko Layanan</th>
                        <th class="px-6 py-4">Kategori Unit</th>
                        <th class="px-6 py-4">Nomor Hotline / Telp</th>
                        <th class="px-6 py-4">WhatsApp Siaga</th>
                        <th class="px-6 py-4">Wilayah & Alamat</th>
                        <th class="px-6 py-4">Urutan & Status</th>
                        <th class="px-6 py-4 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contacts as $c)
                        <tr class="hover:bg-amber-50/30 transition-colors group">
                            
                            <!-- 1. Instansi & Posko Layanan -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    @if($c->image)
                                        <img src="{{ $c->image_url }}" 
                                             alt="{{ $c->name }}" 
                                             class="w-10 h-10 rounded-2xl object-cover border border-gray-200 shadow-2xs shrink-0 bg-white">
                                    @else
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs font-black text-sm {{ 
                                            in_array($c->category, ['damkar']) ? 'bg-orange-50 text-orange-600 border border-orange-200' : (
                                            in_array($c->category, ['polisi']) ? 'bg-blue-50 text-blue-600 border border-blue-200' : (
                                            in_array($c->category, ['rumah_sakit', 'medis', 'ambulans', 'puskesmas']) ? 'bg-rose-50 text-rose-600 border border-rose-200' : (
                                            in_array($c->category, ['sar_bpbd', 'bpbd', 'posko_bencana']) ? 'bg-amber-50 text-amber-700 border border-amber-200' : (
                                            in_array($c->category, ['pdam']) ? 'bg-cyan-50 text-cyan-600 border border-cyan-200' : (
                                            in_array($c->category, ['pln']) ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : 'bg-gray-100 text-gray-700 border border-gray-200')))))
                                        }}">
                                            @if($c->category === 'damkar')
                                                <i data-lucide="flame" class="w-5 h-5"></i>
                                            @elseif($c->category === 'polisi')
                                                <i data-lucide="shield-alert" class="w-5 h-5"></i>
                                            @elseif(in_array($c->category, ['rumah_sakit', 'medis', 'ambulans', 'puskesmas']))
                                                <i data-lucide="cross" class="w-5 h-5"></i>
                                            @elseif(in_array($c->category, ['sar_bpbd', 'bpbd', 'posko_bencana']))
                                                <i data-lucide="life-buoy" class="w-5 h-5"></i>
                                            @elseif($c->category === 'pdam')
                                                <i data-lucide="droplet" class="w-5 h-5"></i>
                                            @elseif($c->category === 'pln')
                                                <i data-lucide="zap" class="w-5 h-5"></i>
                                            @else
                                                <i data-lucide="phone-forwarded" class="w-5 h-5"></i>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="space-y-1 max-w-sm">
                                        <span class="font-bold text-gray-900 text-sm block group-hover:text-amber-700 transition-colors leading-snug">
                                            {{ $c->name }}
                                        </span>
                                        @if($c->description)
                                            <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                                                {{ $c->description }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Kategori Unit -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider {{
                                    in_array($c->category, ['damkar']) ? 'bg-orange-100 text-orange-800 border border-orange-200' : (
                                    in_array($c->category, ['polisi']) ? 'bg-blue-100 text-blue-800 border border-blue-200' : (
                                    in_array($c->category, ['rumah_sakit', 'medis', 'ambulans', 'puskesmas']) ? 'bg-rose-100 text-rose-800 border border-rose-200' : (
                                    in_array($c->category, ['sar_bpbd', 'bpbd', 'posko_bencana']) ? 'bg-amber-100 text-amber-900 border border-amber-200' : (
                                    in_array($c->category, ['pdam']) ? 'bg-cyan-100 text-cyan-800 border border-cyan-200' : (
                                    in_array($c->category, ['pln']) ? 'bg-yellow-100 text-yellow-900 border border-yellow-200' : 'bg-gray-100 text-gray-800 border border-gray-200')))))
                                }}">
                                    <span>{{ str_replace('_', ' ', $c->category) }}</span>
                                </span>
                            </td>

                            <!-- 3. Nomor Hotline / Telp -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $c->phone) }}" 
                                       class="font-mono text-sm font-black text-gray-900 hover:text-amber-600 transition-colors bg-gray-50 px-2.5 py-1 rounded-xl border border-gray-200 hover:border-amber-300 inline-flex items-center gap-1.5">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-amber-600"></i>
                                        <span>{{ $c->phone }}</span>
                                    </a>
                                </div>
                            </td>

                            <!-- 4. WhatsApp Siaga -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($c->whatsapp)
                                    @php
                                        $cleanWa = preg_replace('/[^0-9]/', '', $c->whatsapp);
                                        if (str_starts_with($cleanWa, '0')) {
                                            $cleanWa = '62' . substr($cleanWa, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanWa }}" target="_blank" 
                                       class="font-mono text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 px-2.5 py-1 rounded-xl border border-teal-200 inline-flex items-center gap-1.5 transition-colors">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5 text-teal-600"></i>
                                        <span>{{ $c->whatsapp }}</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-[11px] italic">- Tidak ada WA -</span>
                                @endif
                            </td>

                            <!-- 5. Wilayah & Alamat -->
                            <td class="px-6 py-4">
                                <div class="space-y-1 max-w-xs">
                                    <span class="inline-flex items-center gap-1 font-bold text-gray-800 text-xs bg-gray-100 px-2 py-0.5 rounded-lg">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-amber-600"></i>
                                        <span>Kec. {{ $c->location_district }}</span>
                                    </span>
                                    <p class="text-gray-500 text-[11px] line-clamp-1 leading-normal">
                                        {{ $c->address ?: 'Pusat Kecamatan' }}
                                    </p>
                                </div>
                            </td>

                            <!-- 6. Urutan & Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1.5">
                                    <div>
                                        @if($c->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif Tayang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-mono block">
                                        Urutan: #{{ $c->sort_order ?? 0 }}
                                    </span>
                                </div>
                            </td>

                            <!-- 7. Aksi Manajemen (Direct Page Edit & Hapus) -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit Direct Link Button -->
                                    <a href="{{ route('admin.smart-city.emergency.edit', $c->id) }}" 
                                       class="h-8 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer hover:scale-105 shadow-2xs">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.smart-city.emergency.destroy', $c->id) }}" 
                                          method="POST" 
                                          class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontak darurat {{ $c->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Kontak" 
                                                class="h-8 w-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center transition-all cursor-pointer hover:scale-105 shadow-2xs">
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
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-brand-gold border border-amber-200 flex items-center justify-center mx-auto">
                                        <i data-lucide="phone-off" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">Tidak ada kontak darurat ditemukan</h4>
                                        <p class="text-xs text-gray-500 mt-1">Coba sesuaikan kata kunci pencarian atau ubah filter kategori/kecamatan.</p>
                                    </div>
                                    <a href="{{ route('admin.smart-city.emergency.create') }}" class="px-4 py-2 bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black text-xs font-black rounded-xl inline-flex items-center gap-1.5 shadow-sm">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        <span>Tambah Kontak Pertama</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
