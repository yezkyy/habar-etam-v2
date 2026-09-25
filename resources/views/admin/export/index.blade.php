@extends('layouts.admin')

@section('title', 'Pusat Ekspor Data & Laporan CSV - Admin Habar Etam')
@section('page_title', 'Pusat Ekspor & Laporan Komprehensif')

@section('content')
<div class="space-y-8" x-data="exportDashboard()">

    <!-- 1. Header & Quick Stat Overview -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 p-6 sm:p-8 text-white border border-gray-800 shadow-xl">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-gold/20 text-brand-gold border border-brand-gold/30">
                        <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                        Format CSV Standard (RFC 4180)
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-emerald-300 border border-white/15">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        UU PDP No. 27/2022 Compliant (NIK Terproteksi)
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Pusat Ekspor Data Operasional Platform
                </h2>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    Unduh seluruh data platform Habar Etam ke format CSV berstandar internasional dengan <span class="text-brand-gold font-semibold">UTF-8 BOM</span> untuk kompatibilitas instan di Microsoft Excel, Google Sheets, dan Apple Numbers tanpa distorsi karakter.
                </p>
            </div>

            <!-- Stats Highlight Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 shrink-0">
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Total Dataset</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $globalStats['total_datasets'] }}</span>
                    <span class="text-[10px] text-brand-gold font-bold">Kategori Lengkap</span>
                </div>
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Total Rekap Baris</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $globalStats['total_records'] }}</span>
                    <span class="text-[10px] text-emerald-400 font-bold">Data Terindeks</span>
                </div>
                <div class="col-span-2 sm:col-span-1 bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Ekspor Hari Ini</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $globalStats['export_count_today'] }}</span>
                    <span class="text-[10px] text-blue-400 font-medium">{{ $globalStats['last_export'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Filter & Export Parameters Toolbar -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-5 sm:p-6 space-y-5">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Filter Kategori Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <button type="button" 
                        @click="categoryFilter = 'all'" 
                        :class="categoryFilter === 'all' ? 'bg-gray-950 text-white border-gray-950 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span>Semua Dataset</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px]" :class="categoryFilter === 'all' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'">{{ count($datasets) }}</span>
                </button>

                <button type="button" 
                        @click="categoryFilter = 'pelayanan'" 
                        :class="categoryFilter === 'pelayanan' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-rose-50 hover:text-rose-700'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border">
                    <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                    <span>Pelayanan Publik</span>
                </button>

                <button type="button" 
                        @click="categoryFilter = 'ekonomi'" 
                        :class="categoryFilter === 'ekonomi' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-emerald-50 hover:text-emerald-700'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span>Ekonomi & Pasar</span>
                </button>

                <button type="button" 
                        @click="categoryFilter = 'smart_city'" 
                        :class="categoryFilter === 'smart_city' ? 'bg-purple-600 text-white border-purple-600 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-purple-50 hover:text-purple-700'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Smart City & Wilayah</span>
                </button>

                <button type="button" 
                        @click="categoryFilter = 'sistem'" 
                        :class="categoryFilter === 'sistem' ? 'bg-slate-700 text-white border-slate-700 shadow-xs' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-slate-100 hover:text-slate-900'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer border">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                    <span>Audit & Keamanan</span>
                </button>
            </div>

            <!-- Search Dataset Input -->
            <div class="relative w-full lg:w-72 shrink-0">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Cari dataset atau topik..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-gray-50 border border-gray-200 text-xs font-medium text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold transition-all">
            </div>
        </div>

        <!-- Filter Options Grid (Date Range, District, Delimiter, Metadata Header) -->
        <div class="pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-gray-50/60 p-4 rounded-2xl border border-gray-100">
            <!-- 1. Rentang Waktu -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-brand-gold"></i>
                    <span>Filter Periode</span>
                </label>
                <select x-model="datePreset" @change="applyDatePreset()" class="w-full text-xs font-semibold text-gray-900 rounded-xl border border-gray-200 bg-white px-3 py-2 focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    <option value="all">Semua Waktu (Sepanjang Masa)</option>
                    <option value="today">Hari Ini</option>
                    <option value="this_month">Bulan Ini (September 2026)</option>
                    <option value="last_30">30 Hari Terakhir</option>
                    <option value="this_year">Tahun Berjalan (2026)</option>
                    <option value="custom">Rentang Kustom...</option>
                </select>
            </div>

            <!-- Custom Date Inputs (Conditional) -->
            <div x-show="datePreset === 'custom'" x-cloak class="flex items-center gap-2">
                <div class="w-1/2">
                    <label class="block text-[10px] font-bold text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" x-model="startDate" class="w-full text-xs text-gray-900 rounded-xl border border-gray-200 bg-white px-2.5 py-1.5">
                </div>
                <div class="w-1/2">
                    <label class="block text-[10px] font-bold text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" x-model="endDate" class="w-full text-xs text-gray-900 rounded-xl border border-gray-200 bg-white px-2.5 py-1.5">
                </div>
            </div>

            <!-- 2. Filter Wilayah Kecamatan -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-gold"></i>
                    <span>Kecamatan (Kukar)</span>
                </label>
                <select x-model="selectedDistrict" class="w-full text-xs font-semibold text-gray-900 rounded-xl border border-gray-200 bg-white px-3 py-2 focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    <option value="">Semua Kecamatan (20 Wilayah)</option>
                    @foreach($districts as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Delimiter CSV -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-1.5">
                    <i data-lucide="split" class="w-3.5 h-3.5 text-brand-gold"></i>
                    <span>Pemisah Kolom (Delimiter)</span>
                </label>
                <select x-model="delimiter" class="w-full text-xs font-semibold text-gray-900 rounded-xl border border-gray-200 bg-white px-3 py-2 focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                    <option value=",">Koma ( , ) - Standar Internasional</option>
                    <option value=";">Titik Koma ( ; ) - Standar Excel Indo/EU</option>
                </select>
            </div>

            <!-- 4. Toggle Header Metadata Block -->
            <div class="flex items-center justify-between sm:justify-start gap-3 pt-4 sm:pt-6">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" x-model="includeMetadata" class="sr-only peer">
                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-gold"></div>
                </label>
                <span class="text-xs font-semibold text-gray-700">Sertakan Ringkasan Header Laporan</span>
            </div>
        </div>
    </div>

    <!-- 3. Datasets Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($datasets as $key => $ds)
            @php
                $colorMap = [
                    'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'badge' => 'bg-rose-100 text-rose-800', 'border' => 'hover:border-rose-300', 'btn' => 'hover:bg-rose-600 hover:text-white'],
                    'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'badge' => 'bg-blue-100 text-blue-800', 'border' => 'hover:border-blue-300', 'btn' => 'hover:bg-blue-600 hover:text-white'],
                    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'badge' => 'bg-emerald-100 text-emerald-800', 'border' => 'hover:border-emerald-300', 'btn' => 'hover:bg-emerald-600 hover:text-white'],
                    'cyan' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'badge' => 'bg-cyan-100 text-cyan-800', 'border' => 'hover:border-cyan-300', 'btn' => 'hover:bg-cyan-600 hover:text-white'],
                    'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'badge' => 'bg-amber-100 text-amber-800', 'border' => 'hover:border-amber-300', 'btn' => 'hover:bg-amber-600 hover:text-white'],
                    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'badge' => 'bg-purple-100 text-purple-800', 'border' => 'hover:border-purple-300', 'btn' => 'hover:bg-purple-600 hover:text-white'],
                    'teal' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-600', 'badge' => 'bg-teal-100 text-teal-800', 'border' => 'hover:border-teal-300', 'btn' => 'hover:bg-teal-600 hover:text-white'],
                    'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'badge' => 'bg-red-100 text-red-800', 'border' => 'hover:border-red-300', 'btn' => 'hover:bg-red-600 hover:text-white'],
                    'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'badge' => 'bg-yellow-100 text-yellow-800', 'border' => 'hover:border-yellow-300', 'btn' => 'hover:bg-yellow-600 hover:text-white'],
                    'slate' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'badge' => 'bg-slate-200 text-slate-800', 'border' => 'hover:border-slate-300', 'btn' => 'hover:bg-slate-800 hover:text-white'],
                ];
                $c = $colorMap[$ds['color']] ?? $colorMap['slate'];
            @endphp

            <div x-show="matchesFilter('{{ $ds['category_key'] }}', '{{ strtolower($ds['title'] . ' ' . $ds['description']) }}')"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xs flex flex-col justify-between transition-all {{ $c['border'] }} hover:shadow-md group">
                
                <div class="space-y-4">
                    <!-- Top Row: Icon + Category Badge -->
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center font-black shadow-xs group-hover:scale-105 transition-transform">
                            <i data-lucide="{{ $ds['icon'] }}" class="w-6 h-6"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $c['badge'] }}">
                            {{ $ds['category_name'] }}
                        </span>
                    </div>

                    <!-- Title & Description -->
                    <div>
                        <h4 class="text-base font-bold text-gray-900 group-hover:text-brand-black transition-colors">{{ $ds['title'] }}</h4>
                        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed line-clamp-2">{{ $ds['description'] }}</p>
                    </div>

                    <!-- Metadata Badges: Total Records, Column Count, Est Size -->
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-gray-100 text-gray-800 border border-gray-200">
                            <i data-lucide="database" class="w-3 h-3 text-gray-500"></i>
                            <span>{{ $ds['formatted_count'] }} Data</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-gray-50 text-gray-600 border border-gray-100">
                            <i data-lucide="columns" class="w-3 h-3 text-gray-400"></i>
                            <span>{{ count($ds['columns']) }} Kolom</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-gray-50 text-gray-600 border border-gray-100">
                            <i data-lucide="hard-drive" class="w-3 h-3 text-gray-400"></i>
                            <span>{{ $ds['estimated_size'] }}</span>
                        </span>
                    </div>
                </div>

                <!-- Action Buttons: Preview & Direct Download -->
                <div class="pt-6 mt-4 border-t border-gray-100 grid grid-cols-2 gap-2">
                    <button type="button" 
                            @click="openPreview('{{ $key }}')" 
                            class="py-2.5 px-3 rounded-2xl bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-gray-200 cursor-pointer active:scale-95">
                        <i data-lucide="eye" class="w-4 h-4 text-gray-500"></i>
                        <span>Pratinjau</span>
                    </button>

                    <a :href="getDownloadUrl('{{ $key }}')" 
                       @click="recordExportFeedback('{{ $ds['title'] }}')"
                       class="py-2.5 px-3 rounded-2xl bg-brand-gold text-brand-black hover:bg-yellow-400 text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-xs shadow-brand-gold/20 cursor-pointer active:scale-95">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Unduh CSV</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 4. Security & Data Compliance Banner -->
    <div class="bg-emerald-50/80 rounded-3xl border border-emerald-200/60 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                <i data-lucide="lock" class="w-5 h-5"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-emerald-950">Proteksi Privasi & Keamanan Data Warga Terjamin</h4>
                <p class="text-xs text-emerald-800 leading-relaxed max-w-3xl">
                    Sesuai ketentuan Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27/2022), data sensitif kependudukan seperti Nomor Induk Kependudukan (NIK) terenkripsi di database dan tidak diekspos dalam file ekspor publik. Seluruh riwayat pengunduhan tercatat dalam Audit Log sistem.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.audit.index') }}" class="shrink-0 px-4 py-2.5 rounded-2xl bg-white hover:bg-emerald-100 text-emerald-900 border border-emerald-200 text-xs font-bold transition-all inline-flex items-center gap-2">
            <i data-lucide="shield" class="w-4 h-4 text-emerald-600"></i>
            <span>Buka Audit Log</span>
        </a>
    </div>

    <!-- 5. Recent Export Audit Trail (Last 6 activities) -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center">
                    <i data-lucide="history" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Pengunduhan Ekspor Terbaru</h3>
                    <p class="text-xs text-gray-500">Aktivitas pengunduhan berkas CSV oleh tim redaksi & administrator.</p>
                </div>
            </div>
            <span class="text-xs text-gray-400 font-medium">{{ count($recentExports) }} Aktivitas Tercatat</span>
        </div>

        @if($recentExports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 uppercase text-[10px] font-bold tracking-wider">
                            <th class="py-3 px-3">Waktu Unduh</th>
                            <th class="py-3 px-3">Operator</th>
                            <th class="py-3 px-3">Dataset</th>
                            <th class="py-3 px-3">Pemisah</th>
                            <th class="py-3 px-3">Alamat IP</th>
                            <th class="py-3 px-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @foreach($recentExports as $log)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="py-3 px-3 font-semibold text-gray-900 whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y - H:i') }} WITA
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-brand-gold/20 text-brand-black font-black text-[9px] flex items-center justify-center">
                                            {{ strtoupper(substr($log->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900 truncate max-w-[140px]">{{ $log->user->name ?? 'Admin Sistem' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-800">
                                        {{ $log->metadata['dataset_name'] ?? $log->metadata['dataset'] ?? ($log->metadata['type'] ?? 'Dataset') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-mono text-[11px] text-gray-500">
                                    {{ ($log->metadata['delimiter'] ?? ',') === ';' ? 'Titik Koma (;)' : 'Koma (,)' }}
                                </td>
                                <td class="py-3 px-3 font-mono text-[11px] text-gray-500">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                        Selesai
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-400 bg-gray-50/50 rounded-2xl border border-gray-100">
                <i data-lucide="file-text" class="w-8 h-8 mx-auto text-gray-300 mb-2"></i>
                <p class="text-xs">Belum ada riwayat ekspor baru yang tercatat pada sesi ini.</p>
            </div>
        @endif
    </div>

    <!-- 6. Slide-Over Modal for Instant Dataset Preview -->
    <div x-show="previewModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-hidden flex justify-end bg-gray-950/60 backdrop-blur-xs"
         @keydown.escape.window="previewModalOpen = false">
        
        <div x-show="previewModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             @click.outside="previewModalOpen = false"
             class="w-full max-w-4xl bg-white h-full shadow-2xl flex flex-col justify-between">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-gold/20 text-brand-black" x-text="previewData?.category_name"></span>
                        <span class="text-xs text-gray-500 font-semibold" x-text="previewData?.formatted_total + ' Baris Total'"></span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900" x-text="'Pratinjau: ' + (previewData?.title || 'Dataset')"></h3>
                    <p class="text-xs text-gray-500 font-mono" x-text="'Nama Berkas: ' + (previewData?.filename || '')"></p>
                </div>
                <button type="button" @click="previewModalOpen = false" class="w-9 h-9 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Content (Sample Rows Table) -->
            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                <!-- Loading State -->
                <div x-show="previewLoading" class="py-20 text-center space-y-3">
                    <div class="w-10 h-10 border-3 border-brand-gold border-t-transparent rounded-full animate-spin mx-auto"></div>
                    <p class="text-xs font-bold text-gray-500">Menyiapkan sampel data dari database...</p>
                </div>

                <!-- Data Table State -->
                <div x-show="!previewLoading && previewData">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                            <i data-lucide="table" class="w-4 h-4 text-brand-gold"></i>
                            <span>Menampilkan 5 Baris Pertama (Struktur CSV)</span>
                        </span>
                        <span class="text-[11px] text-gray-400" x-text="'Total ' + (previewData?.columns?.length || 0) + ' Kolom'"></span>
                    </div>

                    <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                        <div class="overflow-x-auto max-h-[420px] scrollbar-thin">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-900 text-white font-bold text-[11px] tracking-wider whitespace-nowrap sticky top-0 z-10">
                                        <template x-for="(col, idx) in previewData?.columns" :key="idx">
                                            <th class="py-3 px-3.5 border-r border-gray-800 last:border-r-0" x-text="col"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                                    <template x-for="(row, rIdx) in previewData?.sample_data" :key="rIdx">
                                        <tr class="hover:bg-amber-50/40 transition-colors whitespace-nowrap">
                                            <template x-for="(val, cIdx) in row" :key="cIdx">
                                                <td class="py-2.5 px-3.5 border-r border-gray-100 last:border-r-0 text-gray-800" x-text="val || '-'"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Column Structure List -->
                    <div class="mt-5 p-4 rounded-2xl bg-gray-50 border border-gray-100 space-y-2">
                        <h5 class="text-xs font-bold text-gray-900">Daftar Header Kolom CSV:</h5>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="(col, idx) in previewData?.columns" :key="idx">
                                <span class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 text-[11px] font-semibold text-gray-700 shadow-2xs" x-text="col"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-4">
                <button type="button" @click="previewModalOpen = false" class="px-5 py-2.5 rounded-2xl bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-xs font-bold transition-all">
                    Tutup Pratinjau
                </button>
                <a :href="getDownloadUrl(activePreviewKey)" 
                   @click="recordExportFeedback(previewData?.title)"
                   class="px-6 py-2.5 rounded-2xl bg-brand-gold text-brand-black hover:bg-yellow-400 text-xs font-black transition-all inline-flex items-center gap-2 shadow-xs shadow-brand-gold/30">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Unduh Dataset Lengkap (CSV)</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function exportDashboard() {
    return {
        categoryFilter: '{{ $selectedCategory ?? "all" }}',
        searchQuery: '',
        datePreset: '{{ $datePreset ?? "all" }}',
        startDate: '{{ $startDate ?? "" }}',
        endDate: '{{ $endDate ?? "" }}',
        selectedDistrict: '{{ $selectedDistrict ?? "" }}',
        delimiter: ',',
        includeMetadata: true,
        
        // Preview State
        previewModalOpen: false,
        previewLoading: false,
        activePreviewKey: '',
        previewData: null,

        matchesFilter(catKey, textContent) {
            const matchesCategory = (this.categoryFilter === 'all' || this.categoryFilter === catKey);
            const matchesSearch = !this.searchQuery || textContent.includes(this.searchQuery.toLowerCase());
            return matchesCategory && matchesSearch;
        },

        applyDatePreset() {
            const now = new Date();
            const formatDate = (d) => d.toISOString().split('T')[0];

            if (this.datePreset === 'today') {
                this.startDate = formatDate(now);
                this.endDate = formatDate(now);
            } else if (this.datePreset === 'this_month') {
                const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
                const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                this.startDate = formatDate(firstDay);
                this.endDate = formatDate(lastDay);
            } else if (this.datePreset === 'last_30') {
                const past30 = new Date(now);
                past30.setDate(past30.getDate() - 30);
                this.startDate = formatDate(past30);
                this.endDate = formatDate(now);
            } else if (this.datePreset === 'this_year') {
                this.startDate = `${now.getFullYear()}-01-01`;
                this.endDate = `${now.getFullYear()}-12-31`;
            } else if (this.datePreset === 'all') {
                this.startDate = '';
                this.endDate = '';
            }
        },

        getDownloadUrl(type) {
            if (!type) return '#';
            const params = new URLSearchParams();
            if (this.startDate) params.append('start_date', this.startDate);
            if (this.endDate) params.append('end_date', this.endDate);
            if (this.selectedDistrict) params.append('district', this.selectedDistrict);
            if (this.delimiter) params.append('delimiter', this.delimiter);
            params.append('include_metadata', this.includeMetadata ? '1' : '0');

            return `{{ url('/admin/export') }}/${type}?${params.toString()}`;
        },

        async openPreview(type) {
            this.activePreviewKey = type;
            this.previewModalOpen = true;
            this.previewLoading = true;
            this.previewData = null;

            try {
                const params = new URLSearchParams();
                if (this.startDate) params.append('start_date', this.startDate);
                if (this.endDate) params.append('end_date', this.endDate);
                if (this.selectedDistrict) params.append('district', this.selectedDistrict);

                const response = await fetch(`{{ url('/admin/export/preview') }}/${type}?${params.toString()}`);
                const data = await response.json();
                
                if (data.success) {
                    this.previewData = data;
                } else {
                    alert('Gagal memuat pratinjau data.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi saat memuat pratinjau.');
            } finally {
                this.previewLoading = false;
                this.$nextTick(() => {
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                });
            }
        },

        recordExportFeedback(title) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-gray-100 p-4 font-sans text-xs'
                    }
                });
                Toast.fire({
                    icon: 'success',
                    title: `Mengunduh berkas CSV ${title}...`
                });
            }
        }
    }
}
</script>
@endsection
