@extends('layouts.admin')

@section('title', 'GIS Peta Sebaran Pengaduan Warga — Habar Etam Kukar')
@section('page_title', 'GIS Peta Sebaran Pengaduan Warga')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* Leaflet custom popup styling */
    .leaflet-popup-content-wrapper {
        padding: 0 !important;
        border-radius: 1.25rem !important;
        overflow: hidden !important;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.25), 0 10px 10px -5px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid rgba(229, 231, 235, 0.8) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: 1.4 !important;
    }
    .leaflet-container {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
    }
    .leaflet-popup-tip {
        background: white !important;
    }

    /* Custom Pulse Marker Animation */
    @keyframes marker-pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(225, 29, 72, 0.7);
        }
        70% {
            transform: scale(1.08);
            box-shadow: 0 0 0 14px rgba(225, 29, 72, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(225, 29, 72, 0);
        }
    }
    .pulse-live-marker {
        animation: marker-pulse 2s infinite;
    }

    /* Custom Scrollbar for sidebar list */
    .custom-map-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-map-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 9999px;
    }
    .custom-map-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .custom-map-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@section('content')
<div x-data="kukarGisMap()" x-init="initMap()" class="space-y-6 pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-6 sm:p-7 rounded-3xl shadow-lg relative overflow-hidden border border-white/5">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-64 -top-12 w-48 h-48 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.reports.index') }}" class="hover:text-brand-gold transition-colors">Lapor Etam</a>
                <span>/</span>
                <span class="text-brand-gold font-semibold">GIS Peta Sebaran Spasial</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-brand-gold">
                        <i data-lucide="map-pinned" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Peta Sebaran Aspirasi & Laporan Warga</span>
                </h1>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    GIS Aktif
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-3xl leading-relaxed">
                Visualisasi geografis titik laporan infrastruktur, kebersihan, penerangan, dan fasilitas publik di seluruh 20 Kecamatan Kabupaten Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2.5 self-start lg:self-center">
            <button type="button" @click="resetView()" 
                    class="h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer">
                <i data-lucide="locate-fixed" class="w-4 h-4 text-brand-gold"></i>
                <span>Reset Pusat Peta</span>
            </button>
            <a href="{{ route('admin.reports.index') }}" 
               class="h-10 px-5 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-xl transition-all inline-flex items-center gap-2 shadow-lg shadow-brand-gold/25 cursor-pointer hover:scale-[1.02]">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Tampilan Tabel & Verifikasi</span>
            </a>
        </div>
    </div>

    <!-- 2. Summary Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        <!-- Total Titik Terpetakan -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Titik GPS</span>
                <span class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100">
                    <i data-lucide="map-pin" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stats['total_mapped']) }}</span>
                <span class="text-[11px] font-bold text-gray-500">titik koordinat</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-brand-gold"></span>
                <span>Terdata dari seluruh wilayah Kukar</span>
            </div>
        </div>

        <!-- Agenda Live Studio SCM -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-rose-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-rose-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    Agenda Live SCM
                </span>
                <span class="p-2.5 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
                    <i data-lucide="radio" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-rose-600 tracking-tight">{{ number_format($stats['live']) }}</span>
                <span class="text-[11px] font-bold text-rose-700">siaran on-air</span>
            </div>
            <div class="mt-2 text-[11px] text-rose-600 font-medium">
                Prioritas liputan & investigasi studio
            </div>
        </div>

        <!-- Menunggu Verifikasi & Diproses -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Antrean Penanganan</span>
                <span class="p-2.5 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100">
                    <i data-lucide="clock" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-blue-900 tracking-tight">{{ number_format($stats['pending'] + $stats['processing']) }}</span>
                <span class="text-[11px] font-bold text-blue-700">laporan aktif</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between">
                <span>{{ $stats['pending'] }} Verif</span>
                <span>•</span>
                <span>{{ $stats['processing'] }} Redaksi/OPD</span>
            </div>
        </div>

        <!-- Selesai / Teratasi -->
        <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Selesai / Teratasi</span>
                <span class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <i data-lucide="check-circle-2" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight">{{ number_format($stats['resolved']) }}</span>
                <span class="text-[11px] font-bold text-emerald-700">tuntas</span>
            </div>
            <div class="mt-2 text-[11px] text-emerald-600 font-medium">
                Masalah diselesaikan instansi terkait
            </div>
        </div>
    </div>

    <!-- 3. GIS Interactive Workspace (Sidebar List + Full Leaflet Map) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- LEFT PANEL: Filter & Interactive Reports List (4 Cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[760px]">
            
            <!-- List Header & Search -->
            <div class="p-4 sm:p-5 border-b border-gray-100 space-y-3.5 bg-gray-50/50 shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-black text-gray-900 flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4 text-brand-gold"></i>
                            <span>Katalog Titik Laporan</span>
                        </h2>
                        <p class="text-[11px] text-gray-500 mt-0.5" x-text="`Menampilkan ${filteredReports.length} dari ${allReports.length} titik`"></p>
                    </div>
                    <button type="button" @click="resetFilters()" x-show="hasActiveFilters" 
                            class="text-[11px] font-bold text-amber-600 hover:text-amber-800 hover:underline">
                        Reset Filter
                    </button>
                </div>

                <!-- Live Search Box -->
                <div class="relative flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10 shrink-0"></i>
                    <input type="text" 
                           x-model="searchQuery" 
                           @input.debounce.250ms="applyFilters()"
                           placeholder="Cari tiket, judul, warga, atau alamat..." 
                           class="admin-search-input !h-10 !pl-10 !pr-9 !rounded-xl !bg-white !text-xs !border-gray-200 focus:!border-brand-gold focus:!ring-2 focus:!ring-brand-gold/30">
                    <button type="button" 
                            x-show="searchQuery" 
                            @click="searchQuery = ''; applyFilters()" 
                            class="absolute right-2.5 z-10 p-1 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 cursor-pointer">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>

                <!-- Status Filter Chips -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 custom-map-scroll">
                    <button type="button" 
                            @click="setStatusFilter('all')" 
                            :class="activeStatus === 'all' ? 'bg-gray-900 text-white font-bold' : 'bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold'"
                            class="px-3 py-1.5 rounded-xl text-[11px] transition-colors shrink-0">
                        Semua (<span x-text="allReports.length"></span>)
                    </button>
                    <button type="button" 
                            @click="setStatusFilter('live')" 
                            :class="activeStatus === 'live' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold'"
                            class="px-3 py-1.5 rounded-xl text-[11px] transition-colors shrink-0 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                        Live On-Air
                    </button>
                    <button type="button" 
                            @click="setStatusFilter('pending_verification')" 
                            :class="activeStatus === 'pending_verification' ? 'bg-amber-500 text-black font-bold shadow-xs' : 'bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold'"
                            class="px-3 py-1.5 rounded-xl text-[11px] transition-colors shrink-0">
                        Verifikasi
                    </button>
                    <button type="button" 
                            @click="setStatusFilter('processing_editorial')" 
                            :class="activeStatus === 'processing_editorial' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'bg-blue-50 hover:bg-blue-100 text-blue-800 font-semibold'"
                            class="px-3 py-1.5 rounded-xl text-[11px] transition-colors shrink-0">
                        Diproses
                    </button>
                    <button type="button" 
                            @click="setStatusFilter('resolved')" 
                            :class="activeStatus === 'resolved' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold'"
                            class="px-3 py-1.5 rounded-xl text-[11px] transition-colors shrink-0">
                        Selesai
                    </button>
                </div>

                <!-- Secondary Filters: Category & District -->
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <select x-model="activeCategory" @change="applyFilters()" 
                            class="w-full px-2.5 py-1.5 bg-white border border-gray-200 rounded-xl text-[11px] font-semibold text-gray-700 focus:outline-none focus:ring-1 focus:ring-brand-gold">
                        <option value="all">Semua Kategori</option>
                        <option value="infrastruktur">Infrastruktur Jalan</option>
                        <option value="kebersihan">Kebersihan & Sampah</option>
                        <option value="pelayanan_publik">Pelayanan Publik</option>
                        <option value="keamanan">Keamanan</option>
                        <option value="lingkungan">Lingkungan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>

                    <select x-model="activeDistrict" @change="applyFilters()" 
                            class="w-full px-2.5 py-1.5 bg-white border border-gray-200 rounded-xl text-[11px] font-semibold text-gray-700 focus:outline-none focus:ring-1 focus:ring-brand-gold">
                        <option value="all">Semua Kecamatan</option>
                        @foreach($districts as $dst)
                            <option value="{{ $dst }}">Kec. {{ $dst }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- List Content (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-3.5 space-y-2.5 custom-map-scroll divide-y divide-gray-50">
                <template x-if="filteredReports.length === 0">
                    <div class="py-16 px-4 text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-brand-gold border border-amber-200 flex items-center justify-center mx-auto">
                            <i data-lucide="map-pin-off" class="w-6 h-6"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xs font-bold text-gray-900">Tidak ada laporan sesuai filter</h3>
                            <p class="text-[11px] text-gray-500 max-w-xs mx-auto">Coba ubah kata kunci pencarian atau bersihkan filter status dan kategori.</p>
                        </div>
                        <button type="button" @click="resetFilters()" class="px-3.5 py-1.5 rounded-xl bg-gray-900 text-white text-[11px] font-bold hover:bg-black transition-colors">
                            Reset Semua Filter
                        </button>
                    </div>
                </template>

                <template x-for="item in filteredReports" :key="item.id">
                    <div @click="focusReport(item)" 
                         :class="selectedReportId === item.id ? 'ring-2 ring-brand-gold bg-amber-50/40 border-amber-300' : 'hover:bg-gray-50 border-gray-100 bg-white'"
                         class="p-3 rounded-2xl border transition-all cursor-pointer group space-y-2 shadow-2xs">
                        
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-mono text-[10px] font-black text-amber-800 bg-amber-100 px-2 py-0.5 rounded-md border border-amber-200" x-text="`#${item.ticket_number}`"></span>
                                <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md" x-text="item.category_label"></span>
                            </div>
                            
                            <template x-if="item.is_featured_live || item.status === 'live_agenda'">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    LIVE SCM
                                </span>
                            </template>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-900 group-hover:text-amber-700 transition-colors line-clamp-1 leading-snug" x-text="item.title"></h4>
                            <p class="text-[11px] text-gray-500 line-clamp-2 mt-0.5 leading-relaxed" x-text="item.description"></p>
                        </div>

                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-500">
                            <div class="flex items-center gap-1 text-gray-700 font-medium truncate max-w-[170px]">
                                <i data-lucide="map-pin" class="w-3 h-3 text-amber-600 shrink-0"></i>
                                <span class="truncate" x-text="`Kec. ${item.district}`"></span>
                            </div>
                            
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span :class="{
                                    'bg-amber-100 text-amber-800 border-amber-200': item.status === 'pending_verification',
                                    'bg-blue-100 text-blue-800 border-blue-200': item.status === 'processing_editorial',
                                    'bg-rose-100 text-rose-800 border-rose-200': item.status === 'live_agenda',
                                    'bg-emerald-100 text-emerald-800 border-emerald-200': item.status === 'resolved',
                                    'bg-gray-100 text-gray-700 border-gray-200': item.status === 'rejected'
                                }" class="px-2 py-0.5 rounded-md font-bold border text-[9px]" x-text="item.status_label"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- List Footer / Legend Summary -->
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-[11px] text-gray-500 flex items-center justify-between shrink-0">
                <span class="flex items-center gap-1.5 font-medium">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-gray-400"></i> Klik kartu untuk fokus peta
                </span>
                <span class="font-mono text-[10px] text-gray-400 font-bold" x-text="`Kukar GIS Engine`"></span>
            </div>
        </div>

        <!-- RIGHT PANEL: Full Interactive Leaflet Map (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-3.5 sm:p-5 space-y-3.5 relative">
            
            <!-- Map Control Ribbon -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/80 p-2.5 rounded-2xl border border-gray-200/70">
                
                <!-- Layer Basemap Selector -->
                <div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-gray-200 shadow-2xs">
                    <button type="button" @click="changeTileLayer('streets')" 
                            :class="currentTile === 'streets' ? 'bg-gray-900 text-white font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <i data-lucide="map" class="w-3 h-3"></i> Standar
                    </button>
                    <button type="button" @click="changeTileLayer('satellite')" 
                            :class="currentTile === 'satellite' ? 'bg-gray-900 text-white font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <i data-lucide="satellite" class="w-3 h-3"></i> Satelit
                    </button>
                    <button type="button" @click="changeTileLayer('light')" 
                            :class="currentTile === 'light' ? 'bg-gray-900 text-white font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <i data-lucide="sun" class="w-3 h-3"></i> Clean
                    </button>
                    <button type="button" @click="changeTileLayer('dark')" 
                            :class="currentTile === 'dark' ? 'bg-gray-900 text-white font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-[11px] transition-colors flex items-center gap-1">
                        <i data-lucide="moon" class="w-3 h-3"></i> Dark
                    </button>
                </div>

                <!-- Hotspot District Shortcuts -->
                <div class="flex items-center gap-1.5 overflow-x-auto custom-map-scroll">
                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider shrink-0 mr-1">Zoom:</span>
                    <button type="button" @click="zoomToDistrict('tenggarong')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-50 hover:border-amber-300 border border-gray-200 text-[11px] font-bold text-gray-700 transition-colors shrink-0">
                        Tenggarong
                    </button>
                    <button type="button" @click="zoomToDistrict('samboja')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-50 hover:border-amber-300 border border-gray-200 text-[11px] font-bold text-gray-700 transition-colors shrink-0">
                        Samboja
                    </button>
                    <button type="button" @click="zoomToDistrict('muara_badak')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-50 hover:border-amber-300 border border-gray-200 text-[11px] font-bold text-gray-700 transition-colors shrink-0">
                        Muara Badak
                    </button>
                    <button type="button" @click="zoomToDistrict('kota_bangun')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-amber-50 hover:border-amber-300 border border-gray-200 text-[11px] font-bold text-gray-700 transition-colors shrink-0">
                        Kota Bangun
                    </button>
                </div>
            </div>

            <!-- Map Viewport -->
            <div class="relative w-full h-[660px] rounded-2xl border border-gray-200 overflow-hidden shadow-inner bg-slate-100">
                
                <!-- Leaflet Canvas Element -->
                <div id="kukar-gis-canvas" class="w-full h-full z-10"></div>

                <!-- Floating Map Legend Overlay -->
                <div class="absolute top-4 right-4 z-20 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl border border-gray-200 shadow-md space-y-2 text-xs max-w-[210px] hidden sm:block">
                    <span class="font-black text-[11px] text-gray-900 uppercase tracking-wider block border-b border-gray-100 pb-1.5 flex items-center gap-1.5">
                        <i data-lucide="layers-2" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Legenda Indikator</span>
                    </span>
                    <div class="space-y-1.5 text-[11px]">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500 ring-4 ring-rose-200 shrink-0 inline-block animate-pulse"></span>
                            <span class="font-bold text-rose-700">Agenda Live SCM</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500 ring-2 ring-amber-200 shrink-0 inline-block"></span>
                            <span class="text-gray-700">Menunggu Verifikasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500 ring-2 ring-blue-200 shrink-0 inline-block"></span>
                            <span class="text-gray-700">Diproses Redaksi / OPD</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-emerald-200 shrink-0 inline-block"></span>
                            <span class="text-gray-700">Tuntas / Selesai</span>
                        </div>
                    </div>
                </div>

                <!-- Floating Bottom Coordinate HUD -->
                <div class="absolute bottom-3 left-3 z-20 bg-gray-900/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 text-white text-[10px] font-mono flex items-center gap-3 shadow-lg">
                    <span class="flex items-center gap-1 text-gray-300">
                        <i data-lucide="crosshair" class="w-3 h-3 text-brand-gold"></i>
                        <span x-text="mouseCoords">Lat: -0.4439, Lng: 116.9856</span>
                    </span>
                    <span class="text-gray-600">|</span>
                    <span class="text-brand-gold font-bold" x-text="`Zoom: ${currentZoom}`"></span>
                </div>
            </div>

            <!-- Map Bottom Info -->
            <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-gray-500 pt-1">
                <div class="flex items-center gap-2 text-[11px]">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Akurasi GPS terverifikasi melalui data EXIF foto & koordinat geolokasi pelapor.</span>
                </div>
                <div class="flex items-center gap-2 text-[11px] font-medium">
                    <span>Pusat Peta: Kab. Kutai Kartanegara, Kalimantan Timur</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function kukarGisMap() {
        return {
            map: null,
            markersGroup: null,
            tileLayers: {},
            currentTile: 'streets',
            allReports: @json($mapData),
            filteredReports: [],
            searchQuery: '',
            activeStatus: 'all',
            activeCategory: 'all',
            activeDistrict: 'all',
            selectedReportId: null,
            mouseCoords: 'Lat: -0.4439, Lng: 116.9856',
            currentZoom: 11,

            get hasActiveFilters() {
                return this.searchQuery !== '' || this.activeStatus !== 'all' || this.activeCategory !== 'all' || this.activeDistrict !== 'all';
            },

            initMap() {
                this.filteredReports = [...this.allReports];

                this.$nextTick(() => {
                    if (typeof L === 'undefined') return;

                    // 1. Initialize Leaflet Map
                    this.map = L.map('kukar-gis-canvas', {
                        center: [-0.4439, 116.9856],
                        zoom: 11,
                        zoomControl: false
                    });

                    // Add Custom Zoom Control to top-left
                    L.control.zoom({
                        position: 'topleft'
                    }).addTo(this.map);

                    // 2. Define Tile Layers
                    this.tileLayers = {
                        streets: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            maxZoom: 19
                        }),
                        satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: '&copy; Esri World Imagery',
                            maxZoom: 18
                        }),
                        light: L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; CartoDB Positron',
                            maxZoom: 19
                        }),
                        dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; CartoDB Dark Matter',
                            maxZoom: 19
                        })
                    };

                    this.tileLayers[this.currentTile].addTo(this.map);

                    // 3. Layer Group for Markers
                    this.markersGroup = L.layerGroup().addTo(this.map);

                    // 4. Mouse movement HUD
                    this.map.on('mousemove', (e) => {
                        this.mouseCoords = `Lat: ${e.latlng.lat.toFixed(5)}, Lng: ${e.latlng.lng.toFixed(5)}`;
                    });
                    this.map.on('zoomend', () => {
                        this.currentZoom = this.map.getZoom();
                    });

                    // 5. Render Markers
                    this.renderMarkers();

                    // 6. Refresh Lucide icons inside map controls
                    if (window.lucide && window.lucide.createIcons) {
                        window.lucide.createIcons();
                    }
                });
            },

            changeTileLayer(layerName) {
                if (this.currentTile === layerName) return;
                this.map.removeLayer(this.tileLayers[this.currentTile]);
                this.currentTile = layerName;
                this.tileLayers[this.currentTile].addTo(this.map);
            },

            setStatusFilter(status) {
                this.activeStatus = status;
                this.applyFilters();
            },

            applyFilters() {
                const q = this.searchQuery.toLowerCase().trim();
                
                this.filteredReports = this.allReports.filter(rep => {
                    // Status check
                    if (this.activeStatus === 'live') {
                        if (!rep.is_featured_live && rep.status !== 'live_agenda') return false;
                    } else if (this.activeStatus !== 'all') {
                        if (rep.status !== this.activeStatus) return false;
                    }

                    // Category check
                    if (this.activeCategory !== 'all') {
                        if (rep.category !== this.activeCategory) return false;
                    }

                    // District check
                    if (this.activeDistrict !== 'all') {
                        if (rep.district !== this.activeDistrict) return false;
                    }

                    // Search Query match
                    if (q) {
                        const matchTicket = (rep.ticket_number || '').toLowerCase().includes(q);
                        const matchTitle = (rep.title || '').toLowerCase().includes(q);
                        const matchDesc = (rep.description || '').toLowerCase().includes(q);
                        const matchAddress = (rep.address || '').toLowerCase().includes(q);
                        const matchAuthor = (rep.author_name || '').toLowerCase().includes(q);
                        const matchDistrict = (rep.district || '').toLowerCase().includes(q);
                        if (!matchTicket && !matchTitle && !matchDesc && !matchAddress && !matchAuthor && !matchDistrict) {
                            return false;
                        }
                    }

                    return true;
                });

                this.renderMarkers();
            },

            resetFilters() {
                this.searchQuery = '';
                this.activeStatus = 'all';
                this.activeCategory = 'all';
                this.activeDistrict = 'all';
                this.selectedReportId = null;
                this.applyFilters();
                this.resetView();
            },

            renderMarkers() {
                if (!this.markersGroup) return;
                this.markersGroup.clearLayers();

                const bounds = [];

                this.filteredReports.forEach(rep => {
                    if (!rep.latitude || !rep.longitude) return;

                    const isLive = rep.is_featured_live || rep.status === 'live_agenda';
                    let pinColor = '#F59E0B'; // Amber default
                    let pinBg = 'bg-amber-500';
                    let ringClass = 'ring-amber-200';
                    let iconHtml = '<i data-lucide="alert-circle" class="w-3.5 h-3.5 text-white"></i>';

                    if (isLive) {
                        pinColor = '#E11D48';
                        pinBg = 'bg-rose-600';
                        ringClass = 'ring-rose-200';
                        iconHtml = '<i data-lucide="radio" class="w-3.5 h-3.5 text-white"></i>';
                    } else if (rep.status === 'resolved') {
                        pinColor = '#10B981';
                        pinBg = 'bg-emerald-600';
                        ringClass = 'ring-emerald-200';
                        iconHtml = '<i data-lucide="check" class="w-3.5 h-3.5 text-white"></i>';
                    } else if (rep.status === 'processing_editorial') {
                        pinColor = '#2563EB';
                        pinBg = 'bg-blue-600';
                        ringClass = 'ring-blue-200';
                        iconHtml = '<i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-white"></i>';
                    } else if (rep.status === 'rejected') {
                        pinColor = '#64748B';
                        pinBg = 'bg-slate-600';
                        ringClass = 'ring-slate-200';
                        iconHtml = '<i data-lucide="x" class="w-3.5 h-3.5 text-white"></i>';
                    }

                    // Custom HTML Pin Icon
                    const customHtml = isLive ? `
                        <div class="relative flex items-center justify-center">
                            <div class="absolute -inset-2 rounded-full bg-rose-500/40 pulse-live-marker"></div>
                            <div class="w-8 h-8 rounded-full ${pinBg} text-white shadow-lg flex items-center justify-center ring-2 ring-white border-2 border-rose-700 relative z-10">
                                ${iconHtml}
                            </div>
                        </div>
                    ` : `
                        <div class="w-7 h-7 rounded-full ${pinBg} text-white shadow-md flex items-center justify-center ring-2 ring-white border border-black/10 hover:scale-110 transition-transform">
                            ${iconHtml}
                        </div>
                    `;

                    const customIcon = L.divIcon({
                        className: 'custom-gis-pin',
                        html: customHtml,
                        iconSize: isLive ? [32, 32] : [28, 28],
                        iconAnchor: isLive ? [16, 16] : [14, 14],
                        popupAnchor: [0, isLive ? -18 : -15]
                    });

                    const marker = L.marker([rep.latitude, rep.longitude], {
                        icon: customIcon
                    }).addTo(this.markersGroup);

                    // Image preview inside popup
                    const mediaThumbnail = rep.media_url ? `
                        <div class="w-full h-32 bg-gray-100 relative overflow-hidden border-b border-gray-100">
                            <img src="${rep.media_url}" alt="Bukti Laporan" class="w-full h-full object-cover">
                            <div class="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-black/70 backdrop-blur-xs text-[10px] text-white font-bold">
                                ${rep.media_count} Foto
                            </div>
                        </div>
                    ` : '';

                    const livePill = isLive ? `
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            AGENDA LIVE SCM
                        </span>
                    ` : '';

                    const popupContent = `
                        <div class="font-sans text-xs w-[280px] sm:w-[310px]">
                            ${mediaThumbnail}
                            <div class="p-4 space-y-2.5">
                                <div class="flex items-center justify-between gap-1.5 flex-wrap">
                                    <span class="font-mono font-black text-amber-800 bg-amber-100 px-2 py-0.5 rounded border border-amber-200 text-[10px]">#${rep.ticket_number}</span>
                                    <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded">${rep.category_label}</span>
                                    ${livePill}
                                </div>

                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2">${rep.title}</h4>
                                    <p class="text-gray-500 text-[11px] mt-1 line-clamp-2 leading-relaxed">${rep.description}</p>
                                </div>

                                <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 space-y-1 text-[11px]">
                                    <div class="flex items-start gap-1.5 text-gray-700">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-600 shrink-0 mt-0.5"></i>
                                        <span class="font-semibold text-gray-800">Kec. ${rep.district}</span>
                                    </div>
                                    <p class="text-gray-500 text-[10px] pl-5 line-clamp-1">${rep.address}</p>
                                    <div class="pt-1.5 border-t border-gray-200/60 flex items-center justify-between text-[10px] text-gray-500">
                                        <span>Pelapor: <strong class="text-gray-700">${rep.author_name}</strong></span>
                                        <span>${rep.created_at_diff}</span>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-gray-100 flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold ${
                                        rep.status === 'resolved' ? 'bg-emerald-100 text-emerald-800' :
                                        (rep.status === 'live_agenda' ? 'bg-rose-100 text-rose-800' :
                                        (rep.status === 'processing_editorial' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800'))
                                    }">${rep.status_label}</span>

                                    <a href="${rep.admin_url}" 
                                       class="px-3 py-1.5 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-[11px] font-black rounded-xl transition-all shadow-xs inline-flex items-center gap-1.5 cursor-pointer">
                                        <span>Kelola & Moderasi</span>
                                        &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent, { maxWidth: 320 });
                    bounds.push([rep.latitude, rep.longitude]);

                    // Store marker reference in report
                    rep._marker = marker;
                });

                // Re-init lucide icons
                setTimeout(() => {
                    if (window.lucide && window.lucide.createIcons) {
                        window.lucide.createIcons();
                    }
                }, 50);
            },

            focusReport(report) {
                this.selectedReportId = report.id;
                if (report.latitude && report.longitude && this.map) {
                    this.map.flyTo([report.latitude, report.longitude], 15, {
                        animate: true,
                        duration: 1.2
                    });

                    setTimeout(() => {
                        if (report._marker) {
                            report._marker.openPopup();
                        }
                    }, 1250);
                }
            },

            resetView() {
                if (!this.map) return;
                if (this.filteredReports.length > 0) {
                    const validCoords = this.filteredReports
                        .filter(r => r.latitude && r.longitude)
                        .map(r => [r.latitude, r.longitude]);
                    
                    if (validCoords.length > 0) {
                        this.map.fitBounds(validCoords, {
                            padding: [50, 50],
                            maxZoom: 13
                        });
                        return;
                    }
                }
                this.map.setView([-0.4439, 116.9856], 11);
            },

            zoomToDistrict(districtKey) {
                if (!this.map) return;
                const coords = {
                    tenggarong: { lat: -0.4439, lng: 116.9856, zoom: 14 },
                    samboja: { lat: -1.0333, lng: 117.0167, zoom: 13 },
                    muara_badak: { lat: -0.3167, lng: 117.4167, zoom: 13 },
                    kota_bangun: { lat: -0.2333, lng: 116.5833, zoom: 13 }
                };

                const target = coords[districtKey];
                if (target) {
                    this.map.flyTo([target.lat, target.lng], target.zoom, {
                        animate: true,
                        duration: 1
                    });
                }
            }
        };
    }
</script>
@endpush
