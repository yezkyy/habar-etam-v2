@extends('layouts.admin')

@section('title', 'Manajemen Pengaduan Warga (Lapor Etam) - Admin Habar Etam')
@section('page_title', 'Pusat Aspirasi & Pengaduan Warga')

@section('content')
<div class="space-y-6 pb-12">

    <!-- Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-6 sm:p-7 rounded-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-gold-500/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-32 h-32 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
        
        <div class="relative z-10 space-y-1.5">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-400 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gold-400 font-bold">Lapor Etam</span>
                <span>/</span>
                <span class="text-gray-300">Daftar Pengaduan</span>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    Pusat Pengaduan & Aspirasi Warga
                </h1>
                @if($stats['pending'] > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-400 text-black shadow-sm animate-pulse">
                        {{ $stats['pending'] }} Laporan Baru
                    </span>
                @endif
                @if($stats['live'] > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500 text-white shadow-sm flex items-center gap-1">
                        <i data-lucide="radio" class="w-3 h-3"></i> {{ $stats['live'] }} On-Air Live
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Manajemen keluhan fasilitas umum, jalan berlubang, banjir, dan aspirasi warga se-Kutai Kartanegara yang disuarakan melalui Redaksi PT SCM.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('admin.reports.map') }}" 
               class="h-9 px-4 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-xl transition-all inline-flex items-center gap-1.5 shadow-md shadow-brand-gold/20 cursor-pointer">
                <i data-lucide="map" class="w-3.5 h-3.5"></i>
                <span>Peta Sebaran GPS</span>
            </a>
            <a href="{{ route('admin.export.index') }}" 
               class="h-9 px-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-bold rounded-xl transition-all inline-flex items-center gap-1.5 backdrop-blur-xs">
                <i data-lucide="download" class="w-3.5 h-3.5 text-gold-400"></i>
                <span>Ekspor Data</span>
            </a>
        </div>
    </div>

    <!-- 5 Interactive Metric Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <!-- 1. Total Laporan -->
        <a href="{{ route('admin.reports.index', ['status' => 'all', 'category' => $category, 'district' => $district, 'q' => request('q')]) }}" 
           class="bg-white p-4.5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'all' ? 'border-gold-500 ring-2 ring-gold-500/20 bg-amber-50/20' : 'border-gray-100 hover:border-gray-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-gray-500 block uppercase tracking-wider">Total Laporan</span>
                    <span class="text-2xl font-black text-gray-900 mt-1 block font-mono">{{ number_format($stats['total']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-gray-100 text-gray-700 border border-gray-200 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-2.5 flex items-center gap-1 text-[10px] font-semibold text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-gray-400"></span>
                <span>Seluruh Aduan Warga</span>
            </div>
        </a>

        <!-- 2. Menunggu Verifikasi (Pending) -->
        <a href="{{ route('admin.reports.index', ['status' => 'pending_verification', 'category' => $category, 'district' => $district, 'q' => request('q')]) }}" 
           class="bg-white p-4.5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'pending_verification' ? 'border-amber-500 ring-2 ring-amber-500/20 bg-amber-50/40' : 'border-gray-100 hover:border-amber-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-amber-700 block uppercase tracking-wider">Menunggu Verif</span>
                    <span class="text-2xl font-black text-amber-600 mt-1 block font-mono">{{ number_format($stats['pending']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
            </div>
            <div class="mt-2.5 flex items-center gap-1 text-[10px] font-bold text-amber-700">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Validasi Kelayakan</span>
            </div>
        </a>

        <!-- 3. Diproses Redaksi -->
        <a href="{{ route('admin.reports.index', ['status' => 'processing_editorial', 'category' => $category, 'district' => $district, 'q' => request('q')]) }}" 
           class="bg-white p-4.5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'processing_editorial' ? 'border-blue-500 ring-2 ring-blue-500/20 bg-blue-50/40' : 'border-gray-100 hover:border-blue-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-blue-700 block uppercase tracking-wider">Proses Redaksi</span>
                    <span class="text-2xl font-black text-blue-600 mt-1 block font-mono">{{ number_format($stats['processing']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="newspaper" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-2.5 flex items-center gap-1 text-[10px] font-bold text-blue-700">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                <span>Investigasi SCM</span>
            </div>
        </a>

        <!-- 4. Agenda Live SCM -->
        <a href="{{ route('admin.reports.index', ['status' => 'live_agenda', 'category' => $category, 'district' => $district, 'q' => request('q')]) }}" 
           class="bg-white p-4.5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'live_agenda' ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/40' : 'border-gray-100 hover:border-rose-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-rose-700 block uppercase tracking-wider">Agenda Live</span>
                    <span class="text-2xl font-black text-rose-600 mt-1 block font-mono">{{ number_format($stats['live']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="radio" class="w-5 h-5 text-rose-600 animate-pulse"></i>
                </div>
            </div>
            <div class="mt-2.5 flex items-center gap-1 text-[10px] font-bold text-rose-700">
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                <span>Siaran On-Air Studio</span>
            </div>
        </a>

        <!-- 5. Selesai / Teratasi -->
        <a href="{{ route('admin.reports.index', ['status' => 'resolved', 'category' => $category, 'district' => $district, 'q' => request('q')]) }}" 
           class="bg-white p-4.5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md col-span-2 sm:col-span-1 {{ $status === 'resolved' ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/40' : 'border-gray-100 hover:border-emerald-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-emerald-700 block uppercase tracking-wider">Tuntas / Selesai</span>
                    <span class="text-2xl font-black text-emerald-600 mt-1 block font-mono">{{ number_format($stats['resolved']) }}</span>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
            </div>
            <div class="mt-2.5 flex items-center gap-1 text-[10px] font-bold text-emerald-700">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Disposisi OPD Tuntas</span>
            </div>
        </a>
    </div>

    <!-- Category Filter Ribbon -->
    <div class="bg-white p-2.5 rounded-2xl border border-gray-100 shadow-xs flex items-center gap-2 overflow-x-auto">
        <!-- Semua Kategori -->
        <a href="{{ route('admin.reports.index', ['category' => 'all', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'all' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
            <span>Semua Kategori</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'all' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['all'] }}</span>
        </a>

        <!-- Infrastruktur -->
        <a href="{{ route('admin.reports.index', ['category' => 'infrastruktur', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'infrastruktur' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="hammer" class="w-3.5 h-3.5 text-blue-500"></i>
            <span>Infrastruktur Jalan</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'infrastruktur' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['infrastruktur'] }}</span>
        </a>

        <!-- Kebersihan -->
        <a href="{{ route('admin.reports.index', ['category' => 'kebersihan', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'kebersihan' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="trash" class="w-3.5 h-3.5 text-emerald-500"></i>
            <span>Kebersihan & Sampah</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'kebersihan' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['kebersihan'] }}</span>
        </a>

        <!-- Pelayanan Publik -->
        <a href="{{ route('admin.reports.index', ['category' => 'pelayanan_publik', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'pelayanan_publik' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="building-2" class="w-3.5 h-3.5 text-purple-500"></i>
            <span>Pelayanan Publik</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'pelayanan_publik' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['pelayanan_publik'] }}</span>
        </a>

        <!-- Keamanan -->
        <a href="{{ route('admin.reports.index', ['category' => 'keamanan', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'keamanan' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-500"></i>
            <span>Keamanan</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'keamanan' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['keamanan'] }}</span>
        </a>

        <!-- Lingkungan -->
        <a href="{{ route('admin.reports.index', ['category' => 'lingkungan', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'lingkungan' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="trees" class="w-3.5 h-3.5 text-emerald-600"></i>
            <span>Lingkungan Hidup</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'lingkungan' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['lingkungan'] }}</span>
        </a>

        <!-- Lainnya -->
        <a href="{{ route('admin.reports.index', ['category' => 'lainnya', 'status' => $status, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $category == 'lainnya' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="help-circle" class="w-3.5 h-3.5 text-gray-500"></i>
            <span>Lainnya</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $category == 'lainnya' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $categoryCounts['lainnya'] }}</span>
        </a>
    </div>

    <!-- Search & Multi-Filter Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.reports.index') }}" 
              method="GET" 
              x-data="adminLiveFilter" 
              @change="$el.submit()" 
              class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="status" value="{{ $status }}">

            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Input with Live Debounce -->
                <div class="relative flex-1 min-w-[260px] flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari no. tiket, judul pengaduan, nama pelapor, lokasi..." 
                           class="admin-search-input pl-10"
                           x-on:input.debounce.450ms="$el.form.submit()">
                </div>

                <!-- District Filter -->
                <div class="w-48">
                    <select name="district" 
                            class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-gray-50 hover:bg-white focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all cursor-pointer shadow-2xs">
                        <option value="all">Semua Kecamatan</option>
                        @foreach($districts as $d)
                            <option value="{{ $d }}" {{ $district == $d ? 'selected' : '' }}>Kec. {{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Order Select -->
                <div class="w-44">
                    <select name="sort" 
                            class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-gray-50 hover:bg-white focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all cursor-pointer shadow-2xs">
                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Terbaru Masuk</option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Paling Lama</option>
                        <option value="ticket_asc" {{ $sort == 'ticket_asc' ? 'selected' : '' }}>No. Tiket (A - Z)</option>
                        <option value="title_asc" {{ $sort == 'title_asc' ? 'selected' : '' }}>Judul Aduan (A - Z)</option>
                    </select>
                </div>

                @if(request()->filled('q') || request()->filled('district') || request()->filled('sort') || $status !== 'all' || $category !== 'all')
                    <a href="{{ route('admin.reports.index') }}" 
                       class="h-10 px-3.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 transition-colors inline-flex items-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>

            <!-- Status Tabs -->
            <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl border border-gray-200/60 shrink-0 self-start lg:self-auto overflow-x-auto">
                <a href="{{ route('admin.reports.index', ['status' => 'all', 'category' => $category, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'all' ? 'bg-black text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Semua
                </a>
                <a href="{{ route('admin.reports.index', ['status' => 'pending_verification', 'category' => $category, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5 {{ $status == 'pending_verification' ? 'bg-amber-500 text-white shadow-xs' : 'text-amber-700 hover:bg-amber-100/60' }}">
                    <span>Verifikasi</span>
                    @if($stats['pending'] > 0)
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                    @endif
                </a>
                <a href="{{ route('admin.reports.index', ['status' => 'processing_editorial', 'category' => $category, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'processing_editorial' ? 'bg-blue-600 text-white shadow-xs' : 'text-blue-700 hover:bg-blue-100/60' }}">
                    Redaksi SCM
                </a>
                <a href="{{ route('admin.reports.index', ['status' => 'live_agenda', 'category' => $category, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'live_agenda' ? 'bg-rose-600 text-white shadow-xs' : 'text-rose-700 hover:bg-rose-100/60' }}">
                    Live Agenda
                </a>
                <a href="{{ route('admin.reports.index', ['status' => 'resolved', 'category' => $category, 'district' => $district, 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'resolved' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:bg-emerald-100/60' }}">
                    Selesai
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Master Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 border-b border-gray-100 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="px-6 py-4">Tiket & Kategori</th>
                        <th class="px-6 py-4">Uraian Pengaduan & Lokasi</th>
                        <th class="px-6 py-4">Pelapor Warga</th>
                        <th class="px-6 py-4">Status Alur</th>
                        <th class="px-6 py-4">Waktu Lapor</th>
                        <th class="px-6 py-4 text-right">Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reports as $r)
                        <tr class="hover:bg-amber-50/20 transition-colors group">
                            <!-- 1. Tiket & Kategori -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-mono font-black text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 text-xs">
                                            #{{ $r->ticket_number }}
                                        </span>
                                        @if($r->is_featured_live || $r->status === 'live_agenda')
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-500 text-white animate-pulse flex items-center gap-1 shadow-2xs">
                                                <i data-lucide="radio" class="w-2.5 h-2.5"></i> LIVE
                                            </span>
                                        @endif
                                    </div>
                                    @php
                                        $catStyle = match($r->category) {
                                            'infrastruktur' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'kebersihan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'pelayanan_publik' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'keamanan' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'lingkungan' => 'bg-teal-50 text-teal-700 border-teal-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold border capitalize {{ $catStyle }}">
                                        {{ str_replace('_', ' ', $r->category) }}
                                    </span>
                                </div>
                            </td>

                            <!-- 2. Uraian & Lokasi -->
                            <td class="px-6 py-4 max-w-sm">
                                <div class="space-y-1">
                                    <a href="{{ route('admin.reports.show', $r->id) }}" 
                                       class="font-bold text-gray-900 group-hover:text-gold-600 transition-colors text-sm line-clamp-1 block">
                                        {{ $r->title }}
                                    </a>
                                    <p class="text-[11px] text-gray-500 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-gold-600 shrink-0"></i>
                                        <span class="truncate">{{ $r->address ?: ('Kec. ' . ($r->location_district ?? 'Kutai Kartanegara')) }}</span>
                                    </p>
                                    @if($r->media->count() > 0)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-400">
                                            <i data-lucide="image" class="w-3 h-3"></i> {{ $r->media->count() }} Foto Bukti
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- 3. Pelapor Warga -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-2xl bg-amber-100 text-amber-900 border border-amber-200 flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($r->user->name ?? 'W', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-gray-900 block text-xs">{{ $r->user->name ?? 'Warga Kukar' }}</span>
                                            @if(($r->user->status ?? '') === 'verified')
                                                <span title="NIK Warga Terverifikasi" class="text-emerald-600">
                                                    <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-0.5">
                                            @if($r->user->phone ?? false)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $r->user->phone) }}" 
                                                   target="_blank" 
                                                   class="text-emerald-700 hover:text-emerald-800 font-mono font-semibold flex items-center gap-0.5">
                                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $r->user->phone }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">{{ $r->user->email ?? '-' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 4. Status Alur -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($r->status == 'resolved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-2xs">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Selesai
                                    </span>
                                @elseif($r->status == 'live_agenda')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 animate-pulse shadow-2xs">
                                        <i data-lucide="radio" class="w-3.5 h-3.5 text-rose-600"></i> Siaran Live SCM
                                    </span>
                                @elseif($r->status == 'processing_editorial')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-2xs">
                                        <i data-lucide="newspaper" class="w-3.5 h-3.5 text-blue-600"></i> Telaah Redaksi
                                    </span>
                                @elseif($r->status == 'pending_verification')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 shadow-2xs">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600 animate-spin"></i> Menunggu Verif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- 5. Tanggal Kirim -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-[11px]">
                                <div class="flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                    <span>{{ $r->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </td>

                            <!-- 6. Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.reports.show', $r->id) }}" 
                                   class="px-4 py-2 rounded-xl text-xs font-black bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black transition-all inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                                    <span>Detail & Proses</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center mx-auto text-gray-400 shadow-inner">
                                        <i data-lucide="megaphone" class="w-7 h-7"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Tidak ada laporan pengaduan</h3>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Tidak ada data pengaduan yang cocok dengan filter atau kata kunci pencarian yang dipilih.
                                    </p>
                                    <div class="pt-2">
                                        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-xl text-xs font-black bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black transition-colors inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Semua Filter
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($reports->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $reports->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

</div>
@endsection
