@extends('layouts.public')

@section('title', 'Lapor Etam — Saluran Pengaduan & Aspirasi Warga Kutai Kartanegara')
@section('meta_description', 'Lapor Etam adalah portal pengaduan sipil warga Kutai Kartanegara. Laporkan jalan rusak, lampu mati, banjir, sampah, dan fasilitas publik secara transparan.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .report-tile-osm .leaflet-tile-pane {
        filter: contrast(104%) brightness(99%) saturate(88%) hue-rotate(-2deg);
    }
    .report-tile-streets .leaflet-tile-pane {
        filter: contrast(102%) saturate(92%);
    }
    .report-tile-dark .leaflet-tile-pane {
        filter: brightness(0.92) contrast(1.08);
    }

    #incident-map {
        min-height: 340px;
        z-index: 10;
        background: #0f172a;
    }

    .leaflet-control-zoom {
        display: none !important;
    }
    .leaflet-control-attribution {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(8px) !important;
        border-radius: 8px 0 0 0 !important;
        font-size: 9px !important;
        color: #64748b !important;
        padding: 2px 8px !important;
    }

    /* Radar Pulse Animations */
    @keyframes incident-pulse-red {
        0% { transform: scale(0.6); opacity: 0.9; }
        50% { transform: scale(1.6); opacity: 0.4; }
        100% { transform: scale(2.5); opacity: 0; }
    }
    @keyframes incident-pulse-blue {
        0% { transform: scale(0.6); opacity: 0.9; }
        50% { transform: scale(1.6); opacity: 0.4; }
        100% { transform: scale(2.5); opacity: 0; }
    }

    .pulse-live {
        animation: incident-pulse-red 2s cubic-bezier(0.1, 0.8, 0.3, 1) infinite;
    }
    .pulse-processing {
        animation: incident-pulse-blue 2.4s cubic-bezier(0.1, 0.8, 0.3, 1) infinite;
    }

    .incident-marker {
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .incident-marker:hover {
        transform: scale(1.22) translateY(-4px) !important;
        z-index: 9999 !important;
    }

    /* Leaflet Popup Styling */
    .leaflet-popup-content-wrapper {
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.22), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
        padding: 0 !important;
        overflow: hidden !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: normal !important;
    }
    .leaflet-popup-tip {
        background: rgba(255, 255, 255, 0.98) !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-red-700 transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-red-900 font-bold bg-red-50 px-2 py-0.5 rounded-md border border-red-200 flex items-center gap-1">
            <i data-lucide="shield-alert" class="w-3 h-3 text-red-600"></i>
            <span>Lapor Etam</span>
        </span>
    </nav>

    <!-- 1. Hero Command Center Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-[#181111] to-red-950 text-white p-6 sm:p-10 shadow-2xl border border-red-500/20 mb-10">
        <!-- Ambient Glow Elements -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header Badges Row -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-900/60 border border-red-400/40 text-red-300 backdrop-blur-md">
                        <i data-lucide="radio" class="w-3.5 h-3.5 text-red-400 animate-pulse"></i>
                        <span>Pusat Kendali Pengaduan & Aspirasi Warga</span>
                    </span>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-gray-300 border border-white/10">
                        <i data-lucide="tv" class="w-3.5 h-3.5 text-purple-400"></i>
                        <span>Terhubung Live Studio SCM & OPD Kukar</span>
                    </span>
                </div>

                <!-- Live Radar Status Pill -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-950/80 border border-red-500/50 text-red-300 text-xs font-bold shadow-xs">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    <span>Saluran Aspirasi 24 Jam Aktif</span>
                </div>
            </div>

            <!-- Title & Narrative Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-8">
                    <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                        Lapor Etam — Suara, Keluhan & Tindak Lanjut Nyata Warga Kukar
                    </h1>
                    <p class="text-sm sm:text-base text-red-100/80 mt-3 leading-relaxed max-w-2xl">
                        Laporkan jalan berlubang, lampu PJU padam, parit tersumbat banjir, sampah liar, dan gangguan fasilitas umum. Laporan Anda dimoderasi secara transparan, disuarakan dalam siaran Live Studio SCM, dan dikawal hingga tuntas oleh OPD Pemkab Kukar.
                    </p>
                </div>

                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-end">
                    <a href="{{ route('reports.create') }}" class="btn-gold py-3.5 px-6 font-extrabold text-xs sm:text-sm shadow-gold-glow flex items-center justify-center gap-2 rounded-2xl transition-transform hover:scale-105">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Kirim Pengaduan Baru</span>
                    </a>

                    @auth
                        <a href="{{ route('reports.my-reports') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs inline-flex items-center justify-center gap-2 transition-colors">
                            <i data-lucide="user-check" class="w-4 h-4 text-amber-400"></i>
                            <span>Pengaduan Saya</span>
                        </a>
                    @else
                        <a href="#quick-tracker" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs inline-flex items-center justify-center gap-2 transition-colors">
                            <i data-lucide="search" class="w-4 h-4 text-red-400"></i>
                            <span>Cek Status Nomor Tiket</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Real-time KPI Stats Counters -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-8 pt-6 border-t border-white/10">
                <!-- Stat 1: Total Reports -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-gray-300 text-xs font-semibold mb-1">
                        <i data-lucide="file-text" class="w-4 h-4 text-amber-400"></i>
                        <span>Total Pengaduan Masuk</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $stats['total'] }} Laporan</div>
                    <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3 h-3 text-emerald-400"></i>
                        <span>Warga Terverifikasi</span>
                    </div>
                </div>

                <!-- Stat 2: Live Agenda Studio -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-purple-300 text-xs font-semibold mb-1">
                        <i data-lucide="tv" class="w-4 h-4 text-purple-400"></i>
                        <span>Agenda Live Studio SCM</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $stats['live'] }} Isu Publik</div>
                    <div class="text-[11px] text-purple-300/80 mt-0.5 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                        <span>Disiarkan Terbuka</span>
                    </div>
                </div>

                <!-- Stat 3: Processing Editorial -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-blue-300 text-xs font-semibold mb-1">
                        <i data-lucide="refresh-cw" class="w-4 h-4 text-blue-400"></i>
                        <span>Diproses Redaksi & OPD</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $stats['processing'] }} Kasus</div>
                    <div class="text-[11px] text-blue-300/80 mt-0.5">Koordinasi Lapangan</div>
                </div>

                <!-- Stat 4: Resolved -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-emerald-300 text-xs font-semibold mb-1">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                        <span>Tuntas Ditangani</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $stats['resolved'] }} Selesai</div>
                    <div class="text-[11px] text-emerald-400 mt-0.5 flex items-center gap-1">
                        <i data-lucide="check" class="w-3 h-3"></i>
                        <span>Solusi Lapangan Teruji</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Interactive GIS Incident Map -->
    @if(isset($allMappedReports) && $allMappedReports->isNotEmpty())
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card mb-10 overflow-hidden" id="incident-map-section">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-red-800 mb-1">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                        </span>
                        <span>Peta Geografis Aspirasi & Insiden Lapangan</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-800 border border-red-300">
                            <i data-lucide="map-pin" class="w-2.5 h-2.5 text-red-700"></i>
                            <span>Kutai Kartanegara</span>
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-brand-black tracking-tight">Sebaran Laporan Warga Real-Time</h2>
                </div>

                <!-- Layer Switcher Bar (Responsive Horizontal Scroll on Mobile) -->
                <div class="w-full lg:w-auto flex items-center overflow-x-auto max-w-full pb-1 scrollbar-none">
                    <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200 shadow-inner shrink-0" id="map-layer-switcher">
                        <button type="button" onclick="switchReportLayer('osm')" id="layer-btn-osm" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all bg-white text-gray-900 shadow-xs flex items-center gap-1.5 shrink-0">
                            <i data-lucide="map" class="w-3.5 h-3.5 text-red-600"></i>
                            <span>Modern OSM</span>
                        </button>
                        <button type="button" onclick="switchReportLayer('streets')" id="layer-btn-streets" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5 shrink-0">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>
                            <span>Esri Streets</span>
                        </button>
                        <button type="button" onclick="switchReportLayer('satellite')" id="layer-btn-satellite" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5 shrink-0">
                            <i data-lucide="satellite" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Satelit HD</span>
                        </button>
                        <button type="button" onclick="switchReportLayer('dark')" id="layer-btn-dark" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5 shrink-0">
                            <i data-lucide="moon" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span>Dark Mode</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Leaflet Map Canvas with Floating HUD Elements -->
            <div class="relative w-full rounded-3xl overflow-hidden border border-slate-200/90 shadow-inner group/map">
                <!-- Main Map DIV (Responsive height: 360px on mobile, 450px on tablet, 500px on desktop) -->
                <div id="incident-map" class="h-[360px] sm:h-[450px] md:h-[500px] w-full report-tile-osm z-10 relative"></div>

                <!-- Floating HUD: Top Left Live Status Pill -->
                <div class="absolute top-4 left-4 z-20 pointer-events-auto">
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-2xl bg-white/95 backdrop-blur-md border border-white/60 shadow-lg text-xs font-bold text-slate-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                        <span>Insiden Terpetakan:</span>
                        <span class="text-red-800 font-extrabold text-[11px]" id="incident-active-count">{{ $allMappedReports->count() }} Lokasi</span>
                    </div>
                </div>

                <!-- Floating HUD: Bottom Left Interactive Status Filter Pills -->
                <div class="absolute bottom-4 left-4 z-20 pointer-events-auto hidden sm:flex items-center gap-1.5 p-1 bg-white/95 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg">
                    <button type="button" onclick="filterReportStatus('all')" id="report-stat-all" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all bg-slate-900 text-white shadow-xs">
                        Semua Status
                    </button>
                    <button type="button" onclick="filterReportStatus('live_agenda')" id="report-stat-live_agenda" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                        <span>Live Studio</span>
                    </button>
                    <button type="button" onclick="filterReportStatus('processing_editorial')" id="report-stat-processing_editorial" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        <span>Diproses</span>
                    </button>
                    <button type="button" onclick="filterReportStatus('resolved')" id="report-stat-resolved" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span>Selesai</span>
                    </button>
                </div>

                <!-- Floating HUD: Bottom Right Action Stack -->
                <div class="absolute bottom-4 right-4 z-20 pointer-events-auto flex flex-col items-end gap-2">
                    <!-- Zoom Action Capsule -->
                    <div class="flex flex-col bg-white/95 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg overflow-hidden p-1 gap-1">
                        <button type="button" onclick="zoomInIncidentMap()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 transition-colors font-bold text-base shadow-xs" title="Zoom In">
                            +
                        </button>
                        <button type="button" onclick="zoomOutIncidentMap()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 transition-colors font-bold text-base shadow-xs" title="Zoom Out">
                            −
                        </button>
                    </div>

                    <!-- Reset & Fit Buttons -->
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="resetIncidentTenggarong()" class="px-3 py-2 rounded-2xl bg-red-800 hover:bg-red-900 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-1.5 hover:scale-105" title="Fokus Tenggarong">
                            <i data-lucide="compass" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Pusat Tenggarong</span>
                        </button>
                        <button type="button" onclick="fitAllIncidentPoints()" class="p-2 sm:px-3 sm:py-2 rounded-2xl bg-white/95 backdrop-blur-md hover:bg-white text-slate-700 font-bold text-xs border border-white/60 shadow-lg transition-all flex items-center gap-1.5" title="Tampilkan Seluruh Titik Kukar">
                            <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Semua Titik</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between text-[11px] text-gray-500 mt-4 px-1 gap-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="mouse-pointer-click" class="w-3.5 h-3.5 text-red-600"></i>
                    <span>Klik pin laporan untuk melihat foto bukti, alamat rinci, dan progres penanganan tim redaksi.</span>
                </div>
                <span class="text-gray-400 font-medium">GIS Telemetri Pengaduan Terintegrasi Kukar</span>
            </div>
        </div>
    @endif

    <!-- 3. Horizon Category Filter Tabs & Search Toolbar -->
    <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-subtle mb-8">
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
            
            <!-- Horizon Category Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('reports.index', array_merge(request()->except(['category', 'page']), ['category' => ''])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ !request('category') ? 'bg-slate-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                    <span>Semua Kategori</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ !request('category') ? 'bg-slate-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $stats['total'] }}</span>
                </a>

                <a href="{{ route('reports.index', array_merge(request()->except(['category', 'page']), ['category' => 'Jalan & Jembatan'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'Jalan & Jembatan' ? 'bg-red-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="construction" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Jalan & Jembatan</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'Jalan & Jembatan' ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['Jalan & Jembatan'] ?? 0 }}</span>
                </a>

                <a href="{{ route('reports.index', array_merge(request()->except(['category', 'page']), ['category' => 'Drainase & Banjir'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'Drainase & Banjir' ? 'bg-blue-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="waves" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span>Drainase & Banjir</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'Drainase & Banjir' ? 'bg-blue-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['Drainase & Banjir'] ?? 0 }}</span>
                </a>

                <a href="{{ route('reports.index', array_merge(request()->except(['category', 'page']), ['category' => 'Lampu & Penerangan'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'Lampu & Penerangan' ? 'bg-amber-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="lightbulb" class="w-3.5 h-3.5 text-yellow-600"></i>
                    <span>Lampu PJU</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'Lampu & Penerangan' ? 'bg-amber-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['Lampu & Penerangan'] ?? 0 }}</span>
                </a>

                <a href="{{ route('reports.index', array_merge(request()->except(['category', 'page']), ['category' => 'Sampah & Kebersihan'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'Sampah & Kebersihan' ? 'bg-emerald-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Sampah & Kebersihan</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'Sampah & Kebersihan' ? 'bg-emerald-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['Sampah & Kebersihan'] ?? 0 }}</span>
                </a>

                <a href="{{ route('reports.index', array_merge(request()->except(['category', 'page']), ['category' => 'Fasilitas Publik'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'Fasilitas Publik' ? 'bg-purple-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="trees" class="w-3.5 h-3.5 text-purple-600"></i>
                    <span>Fasilitas Publik</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'Fasilitas Publik' ? 'bg-purple-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['Fasilitas Publik'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Search, District, Status & Action Row -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-2 border-t border-gray-100">
                <!-- Search Input -->
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari nomor tiket (ETAM-...), kata kunci masalah, atau alamat..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all placeholder:text-gray-400 text-brand-black">
                    @if(request('q'))
                        <a href="{{ route('reports.index', request()->except('q')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>

                <!-- District Dropdown -->
                <div class="md:col-span-3">
                    <select name="district" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black">
                        <option value="">Semua Kecamatan</option>
                        @foreach($availableDistricts as $dist)
                            <option value="{{ $dist }}" {{ request('district') === $dist ? 'selected' : '' }}>
                                {{ $dist }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Dropdown -->
                <div class="md:col-span-2">
                    <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black">
                        <option value="">Semua Status</option>
                        <option value="live_agenda" {{ request('status') === 'live_agenda' ? 'selected' : '' }}>Live Agenda Studio</option>
                        <option value="processing_editorial" {{ request('status') === 'processing_editorial' ? 'selected' : '' }}>Diproses Redaksi</option>
                        <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Submit & Reset Actions -->
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'district', 'status']))
                        <a href="{{ route('reports.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors shrink-0" title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>

        </form>
    </div>

    <!-- 4. Reports Incidents Grid -->
    <div class="mb-14">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-black text-brand-black flex items-center gap-2">
                <i data-lucide="shield-alert" class="w-5 h-5 text-red-600"></i>
                <span>Daftar Pengaduan Warga ({{ $reports->total() }})</span>
            </h2>
            <span class="text-xs text-gray-400">Dimoderasi & Diverifikasi Redaksi Habar Etam</span>
        </div>

        @if($reports->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center shadow-subtle max-w-xl mx-auto">
                <div class="w-16 h-16 bg-red-50 text-red-700 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-black text-brand-black">Tidak Ada Pengaduan Sesuai Filter</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                    Coba ganti kata kunci pencarian, pilih kategori lain, atau laporkan masalah fasilitas di sekitar Anda.
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition-colors">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>Reset Filter</span>
                    </a>
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-700 text-white text-xs font-bold hover:bg-red-800 transition-colors shadow-xs">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Kirim Pengaduan Baru</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reports as $rep)
                    @php
                        $firstMedia = $rep->media->firstWhere('media_type', 'image');
                        $catIcon = 'alert-triangle';
                        $catColor = 'text-amber-600';
                        $accentBorder = 'border-l-amber-500';

                        if ($rep->category === 'Jalan & Jembatan') {
                            $catIcon = 'construction';
                            $catColor = 'text-red-600';
                            $accentBorder = 'border-l-red-500';
                        } elseif ($rep->category === 'Drainase & Banjir') {
                            $catIcon = 'waves';
                            $catColor = 'text-blue-600';
                            $accentBorder = 'border-l-blue-500';
                        } elseif ($rep->category === 'Lampu & Penerangan') {
                            $catIcon = 'lightbulb';
                            $catColor = 'text-yellow-600';
                            $accentBorder = 'border-l-yellow-500';
                        } elseif ($rep->category === 'Sampah & Kebersihan') {
                            $catIcon = 'trash-2';
                            $catColor = 'text-emerald-600';
                            $accentBorder = 'border-l-emerald-500';
                        } elseif ($rep->category === 'Fasilitas Publik') {
                            $catIcon = 'trees';
                            $catColor = 'text-purple-600';
                            $accentBorder = 'border-l-purple-500';
                        }
                    @endphp

                    <div class="bg-white border border-gray-200/90 border-l-4 {{ $accentBorder }} rounded-2xl overflow-hidden shadow-subtle hover:shadow-lg hover:border-gray-300 transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Card Media Banner -->
                            <div class="relative w-full aspect-[16/10] bg-slate-950 overflow-hidden">
                                @if($firstMedia)
                                    <img src="{{ asset('storage/' . $firstMedia->path) }}" 
                                         alt="{{ $rep->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 flex flex-col items-center justify-center p-4 text-center">
                                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-2 border border-white/10">
                                            <i data-lucide="{{ $catIcon }}" class="w-6 h-6 text-red-400"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ $rep->category }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>

                                <!-- Floating Status Ribbon Over Image -->
                                <div class="absolute top-3 left-3 z-10">
                                    @if($rep->status === 'live_agenda')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-black bg-purple-900/90 text-purple-200 border border-purple-400/50 backdrop-blur-md shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                                            <span>AGENDA LIVE STUDIO</span>
                                        </span>
                                    @elseif($rep->status === 'resolved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-black bg-emerald-900/90 text-emerald-200 border border-emerald-400/50 backdrop-blur-md shadow-xs">
                                            <i data-lucide="check-circle" class="w-3 h-3 text-emerald-400"></i>
                                            <span>SELESAI DITANGANI</span>
                                        </span>
                                    @elseif($rep->status === 'processing_editorial')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-black bg-blue-900/90 text-blue-200 border border-blue-400/50 backdrop-blur-md shadow-xs">
                                            <i data-lucide="refresh-cw" class="w-3 h-3 text-blue-400"></i>
                                            <span>DIPROSES REDAKSI</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-black bg-amber-900/90 text-amber-200 border border-amber-400/50 backdrop-blur-md shadow-xs">
                                            <i data-lucide="clock" class="w-3 h-3 text-amber-400"></i>
                                            <span>MENUNGGU VERIFIKASI</span>
                                        </span>
                                    @endif
                                </div>

                                <!-- District Pill -->
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-black/60 text-white/95 backdrop-blur-md border border-white/20 shadow-xs">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-red-400"></i>
                                        <span>{{ $rep->location_district }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="p-5 sm:p-6">
                                <!-- Meta: Ticket & Category -->
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-1.5 font-mono text-[11px] font-extrabold text-red-700 bg-red-50 border border-red-200 px-2 py-0.5 rounded-md">
                                        <span>{{ $rep->ticket_number }}</span>
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-500 flex items-center gap-1">
                                        <i data-lucide="{{ $catIcon }}" class="w-3.5 h-3.5 {{ $catColor }}"></i>
                                        <span>{{ $rep->category }}</span>
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-base font-black text-brand-black group-hover:text-red-700 transition-colors leading-snug mb-2">
                                    <a href="{{ route('reports.show', $rep->ticket_number) }}">
                                        {{ $rep->title }}
                                    </a>
                                </h3>

                                <!-- Description -->
                                <p class="text-xs text-gray-600 leading-relaxed line-clamp-2">
                                    {{ $rep->description }}
                                </p>

                                <!-- 4-Step Mini Workflow Indicator -->
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 mb-1.5">
                                        <span>Progres Penanganan</span>
                                        <span class="text-slate-800 font-extrabold">Tahap {{ $rep->workflow_step }} / 4</span>
                                    </div>
                                    <div class="grid grid-cols-4 gap-1">
                                        <div class="h-1.5 rounded-full {{ $rep->workflow_step >= 1 ? 'bg-amber-500' : 'bg-gray-200' }}"></div>
                                        <div class="h-1.5 rounded-full {{ $rep->workflow_step >= 2 ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                                        <div class="h-1.5 rounded-full {{ $rep->workflow_step >= 3 ? 'bg-purple-500' : 'bg-gray-200' }}"></div>
                                        <div class="h-1.5 rounded-full {{ $rep->workflow_step >= 4 ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer & Actions -->
                        <div class="px-5 pb-5 sm:px-6 sm:pb-6 pt-0 space-y-3">
                            <div class="flex items-center justify-between text-[11px] text-gray-400 border-t border-gray-100 pt-3">
                                <div class="flex items-center gap-1 truncate text-gray-600 font-medium">
                                    <i data-lucide="compass" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                    <span class="truncate">{{ $rep->address }}</span>
                                </div>
                                <span class="shrink-0 text-[10px]">{{ $rep->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <a href="{{ route('reports.show', $rep->ticket_number) }}" 
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors shadow-xs">
                                    <span>Pantau Progres</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>

                                @if($rep->latitude && $rep->longitude)
                                    <button type="button" 
                                            onclick="focusIncidentOnMap({{ $rep->latitude }}, {{ $rep->longitude }}, '{{ addslashes($rep->ticket_number) }}')"
                                            class="inline-flex items-center justify-center p-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-800 border border-red-200 transition-colors"
                                            title="Fokus di Peta GIS">
                                        <i data-lucide="map" class="w-4 h-4 text-red-700"></i>
                                    </button>
                                @endif

                                <button type="button" 
                                        onclick="copyTicketCode('{{ $rep->ticket_number }}')"
                                        class="inline-flex items-center justify-center p-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors"
                                        title="Salin Nomor Tiket">
                                    <i data-lucide="copy" class="w-4 h-4 text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $reports->links('partials.pagination') }}
            </div>
        @endif
    </div>

    <!-- 5. 4-Stage Workflow Transparency Guide -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-red-950 text-white rounded-3xl p-6 sm:p-10 shadow-card border border-red-500/20 mb-12">
        <div class="max-w-3xl mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-900/60 border border-red-400/40 text-red-300 backdrop-blur-md mb-2">
                <i data-lucide="git-commit" class="w-3.5 h-3.5"></i>
                <span>Transparansi Alur Pengaduan Warga</span>
            </span>
            <h3 class="text-xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                Bagaimana Laporan Anda Ditindaklanjuti?
            </h3>
            <p class="text-xs sm:text-sm text-red-100/80 mt-2 leading-relaxed">
                Setiap laporan masyarakat melalui 4 tahapan verifikasi dan pengawalan redaksi hingga solusi nyata di lapangan terealisasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Step 1 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center mb-4 border border-amber-500/30 font-black text-sm">
                    01
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Verifikasi Bukti</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Warga mengirimkan foto/video dan titik koordinat GPS. Data NIK dan nomor kontak pelapor dirahasiakan 100%.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-4 border border-blue-500/30 font-black text-sm">
                    02
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Koordinasi Redaksi</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Tim redaksi Habar Etam meninjau keabsahan dan meneruskan aduan ke dinas teknis (PU, Dishub, DLHK, dll).
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mb-4 border border-purple-500/30 font-black text-sm">
                    03
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Live Studio SCM</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Kasus genting diangkat ke siaran live studio interaktif untuk mengawal komitmen penanganan para pejabat terkait.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4 border border-emerald-500/30 font-black text-sm">
                    04
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Tuntas & Evaluasi</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Perbaikan fisik dikonfirmasi di lapangan, status diperbarui menjadi 'Selesai', dan dipublikasikan untuk warga.
                </p>
            </div>
        </div>
    </div>

    <!-- 6. Quick Ticket Tracker Widget -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card mb-12" id="quick-tracker">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-5 space-y-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-extrabold bg-red-100 text-red-800 border border-red-200">
                    <i data-lucide="search" class="w-3 h-3"></i>
                    <span>Pelacakan Mandiri</span>
                </span>
                <h3 class="text-xl font-black text-brand-black">Cek Status Pengaduan Anda</h3>
                <p class="text-xs text-gray-500">
                    Masukkan nomor tiket resmi (contoh: <strong class="font-mono text-red-700">ETAM-202609-001</strong>) untuk melihat perkembangan verifikasi dan catatan redaksi.
                </p>
            </div>

            <div class="lg:col-span-7">
                <form onsubmit="handleQuickTicketSearch(event)" class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="ticket" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               id="quick-ticket-input" 
                               placeholder="Ketik atau tempel nomor tiket ETAM-..." 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold uppercase focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-brand-black"
                               required>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-red-800 hover:bg-red-900 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 shrink-0">
                        <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
                        <span>Lacak Tiket Sekarang</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 7. Emergency & Civic Hotline Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-red-950 via-slate-900 to-red-950 p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-card border border-red-500/20">
        <div class="space-y-1.5 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-900/80 text-red-300 text-xs font-bold mb-1">
                <i data-lucide="phone-call" class="w-3.5 h-3.5 text-red-400"></i>
                <span>Layanan Kedaruratan & Bencana</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-white">Butuh Penanganan Darurat Segera?</h3>
            <p class="text-xs sm:text-sm text-red-100/80 max-w-xl">
                Untuk insiden kritis yang mengancam keselamatan (kebakaran, kecelakaan parah, banjir bandang), hubungi Layanan Darurat Terpadu 112 Kukar.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('smart-city.emergency') }}" class="px-5 py-3 rounded-2xl bg-red-600 hover:bg-red-500 text-white font-black text-xs inline-flex items-center gap-2 shadow-lg transition-transform hover:scale-105">
                <i data-lucide="siren" class="w-4 h-4"></i>
                <span>Buka Hotline Darurat 112</span>
            </a>
            <a href="{{ route('reports.create') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs inline-flex items-center gap-2 transition-all">
                <i data-lucide="edit-3" class="w-4 h-4 text-amber-400"></i>
                <span>Kirim Aduan Fasilitas</span>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let incidentMap = null;
    const incidentMarkersMap = {};
    const allIncidentMarkersData = [];
    let incidentTenggarongCenter = [-0.4300, 116.9850];
    let currentIncidentTileLayer = null;
    let allIncidentPointsGroup = [];

    // Switch Tile Layer
    window.switchReportLayer = function(type) {
        if (!incidentMap || typeof L === 'undefined') return;

        const mapEl = document.getElementById('incident-map');
        if (mapEl) {
            mapEl.classList.remove('report-tile-osm', 'report-tile-streets', 'report-tile-dark');
        }

        if (currentIncidentTileLayer) {
            incidentMap.removeLayer(currentIncidentTileLayer);
        }

        if (type === 'satellite') {
            currentIncidentTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri Satellite Imagery • Habar Etam'
            });
        } else if (type === 'streets') {
            if (mapEl) mapEl.classList.add('report-tile-streets');
            currentIncidentTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri Street Map • Habar Etam'
            });
        } else if (type === 'dark') {
            if (mapEl) mapEl.classList.add('report-tile-dark');
            currentIncidentTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 16,
                attribution: '© Esri Dark Gray • Habar Etam'
            });
        } else {
            type = 'osm';
            if (mapEl) mapEl.classList.add('report-tile-osm');
            currentIncidentTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors • Habar Etam'
            });
        }

        currentIncidentTileLayer.addTo(incidentMap);

        ['osm', 'streets', 'satellite', 'dark'].forEach(id => {
            const btn = document.getElementById(`layer-btn-${id}`);
            if (btn) {
                if (id === type) {
                    btn.className = 'px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all bg-white text-gray-900 shadow-xs flex items-center gap-1.5';
                } else {
                    btn.className = 'px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5';
                }
            }
        });
    };

    // Zoom Helpers
    window.zoomInIncidentMap = function() {
        if (incidentMap) incidentMap.zoomIn();
    };
    window.zoomOutIncidentMap = function() {
        if (incidentMap) incidentMap.zoomOut();
    };

    // Reset ke Pusat Tenggarong
    window.resetIncidentTenggarong = function() {
        if (!incidentMap) return;
        incidentMap.flyTo(incidentTenggarongCenter, 13.5, {
            duration: 1.2,
            easeLinearity: 0.25
        });
    };

    // Fit Semua Titik
    window.fitAllIncidentPoints = function() {
        if (!incidentMap || allIncidentPointsGroup.length === 0) return;
        incidentMap.flyToBounds(allIncidentPointsGroup, {
            padding: [50, 50],
            duration: 1.4,
            maxZoom: 14
        });
    };

    // Filter Status Langsung pada Peta
    window.filterReportStatus = function(stat) {
        if (!incidentMap) return;

        let visibleCount = 0;

        allIncidentMarkersData.forEach(item => {
            const isMatch = (stat === 'all') || (item.data.status === stat);

            if (isMatch) {
                if (!incidentMap.hasLayer(item.marker)) {
                    item.marker.addTo(incidentMap);
                }
                visibleCount++;
            } else {
                if (incidentMap.hasLayer(item.marker)) {
                    incidentMap.removeLayer(item.marker);
                }
            }
        });

        const countEl = document.getElementById('incident-active-count');
        if (countEl) countEl.innerText = `${visibleCount} Lokasi`;

        ['all', 'live_agenda', 'processing_editorial', 'resolved'].forEach(id => {
            const btn = document.getElementById(`report-stat-${id}`);
            if (btn) {
                if (id === stat) {
                    btn.className = 'px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all bg-slate-900 text-white shadow-xs';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1.5';
                }
            }
        });
    };

    function initIncidentMap() {
        const mapEl = document.getElementById('incident-map');
        if (!mapEl || typeof L === 'undefined') return;

        incidentMap = L.map('incident-map', {
            center: incidentTenggarongCenter,
            zoom: 13,
            minZoom: 8,
            maxZoom: 19,
            zoomControl: false,
            scrollWheelZoom: true,
            smoothWheelZoom: true
        });

        switchReportLayer('osm');

        const reports = @json($allMappedReports ?? []);
        allIncidentPointsGroup = [];

        reports.forEach(rep => {
            if (rep.latitude && rep.longitude) {
                const lat = parseFloat(rep.latitude);
                const lng = parseFloat(rep.longitude);

                // Theme based on Status
                let themeColor = '#d97706'; // Amber default
                let lightBg = '#fef3c7';
                let textColor = '#92400e';
                let pulseClass = '';
                let statusLabel = rep.status_label || 'Menunggu Verifikasi';

                if (rep.status === 'live_agenda') {
                    themeColor = '#9333ea'; // Purple
                    lightBg = '#f3e8ff';
                    textColor = '#6b21a8';
                    pulseClass = 'pulse-live';
                } else if (rep.status === 'processing_editorial') {
                    themeColor = '#2563eb'; // Blue
                    lightBg = '#dbeafe';
                    textColor = '#1e40af';
                    pulseClass = 'pulse-processing';
                } else if (rep.status === 'resolved') {
                    themeColor = '#059669'; // Emerald
                    lightBg = '#d1fae5';
                    textColor = '#065f46';
                }

                // Category Icon
                let iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;

                if (rep.category === 'Jalan & Jembatan') {
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="8" rx="1"/><path d="M17 14v7"/><path d="M7 14v7"/><path d="M14 6V3"/><path d="M10 6V3"/></svg>`;
                } else if (rep.category === 'Drainase & Banjir') {
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>`;
                } else if (rep.category === 'Lampu & Penerangan') {
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="18" x2="15" y2="18"/><line x1="10" y1="22" x2="14" y2="22"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>`;
                } else if (rep.category === 'Sampah & Kebersihan') {
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>`;
                }

                const markerHtml = `
                    <div class="relative w-10 h-10 flex items-center justify-center incident-marker" style="cursor:pointer;">
                        ${pulseClass ? `<div class="${pulseClass} absolute w-10 h-10 rounded-full" style="background:${themeColor};"></div>` : ''}
                        <div class="relative w-8 h-8 rounded-2xl flex items-center justify-center text-white shadow-xl" 
                             style="background:linear-gradient(135deg, ${themeColor}, ${themeColor}dd); border: 2.5px solid #ffffff; box-shadow: 0 8px 16px -2px ${themeColor}66;">
                            ${iconSvg}
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: markerHtml,
                    className: 'incident-marker-wrapper',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -22]
                });

                const imageHeaderHtml = rep.image_url 
                    ? `<div style="width:100%; height:110px; overflow:hidden; position:relative; background:#0f172a;">
                         <img src="${rep.image_url}" alt="${rep.title}" style="width:100%; height:100%; object-fit:cover; display:block;">
                         <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.55), transparent);"></div>
                       </div>` 
                    : '';

                const popupContent = `
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; width:280px; overflow:hidden;">
                        <div style="height:4px; width:100%; background:${themeColor};"></div>
                        ${imageHeaderHtml}

                        <div style="padding:14px 16px;">
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                                <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:2.5px 8px; border-radius:999px; background:${lightBg}; color:${textColor};">
                                    ${statusLabel}
                                </span>
                                <span style="font-size:10px; font-family:monospace; font-weight:700; color:#dc2626;">
                                    ${rep.ticket_number}
                                </span>
                            </div>

                            <h4 style="font-size:13px; font-weight:800; color:#0f172a; margin:0 0 6px 0; line-height:1.35;">
                                ${rep.title}
                            </h4>

                            <div style="display:flex; align-items:flex-start; gap:6px; font-size:11px; color:#64748b; margin-bottom:10px;">
                                <svg style="width:13px; height:13px; color:#64748b; flex-shrink:0; margin-top:1px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>${rep.address} (${rep.location_district})</span>
                            </div>

                            <div style="padding-top:8px; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; gap:6px;">
                                <a href="/lapor-etam/tiket/${rep.ticket_number}" 
                                   style="font-size:11px; font-weight:800; color:${themeColor}; text-decoration:none; display:inline-flex; align-items:center; gap:3px;">
                                    <span>Pantau Progres</span>
                                    <span>→</span>
                                </a>

                                <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" rel="noopener noreferrer" 
                                   style="font-size:10.5px; font-weight:800; color:#ffffff; background:${themeColor}; padding:4px 10px; border-radius:8px; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                    <span>Rute Maps</span>
                                    <svg style="width:11px; height:11px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                const marker = L.marker([lat, lng], { icon: customIcon })
                    .addTo(incidentMap)
                    .bindPopup(popupContent, { maxWidth: 320 });

                incidentMarkersMap[`${lat}_${lng}`] = marker;
                allIncidentMarkersData.push({ marker: marker, data: rep, coords: [lat, lng] });
                allIncidentPointsGroup.push([lat, lng]);
            }
        });

        incidentMap.setView(incidentTenggarongCenter, 13);

        if (window.lucide) {
            window.lucide.createIcons();
        }

        setTimeout(() => {
            if (incidentMap) incidentMap.invalidateSize();
        }, 250);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIncidentMap);
    } else {
        initIncidentMap();
    }

    // Function to pan & focus on a specific map incident
    window.focusIncidentOnMap = function(lat, lng, ticket) {
        const mapSection = document.getElementById('incident-map-section');
        if (mapSection) {
            mapSection.scrollIntoView({ behavior: 'smooth' });
        }

        if (!incidentMap) return;

        setTimeout(() => {
            incidentMap.flyTo([lat, lng], 15.5, {
                duration: 1.2,
                easeLinearity: 0.25
            });

            const key = `${lat}_${lng}`;
            if (incidentMarkersMap[key]) {
                setTimeout(() => {
                    incidentMarkersMap[key].openPopup();
                }, 600);
            }
        }, 350);
    };

    // Copy Ticket Code Helper
    window.copyTicketCode = function(ticket) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(ticket).then(() => {
                if (window.showToast) {
                    window.showToast('success', 'Nomor Tiket Disalin', `Nomor tiket ${ticket} berhasil disalin ke clipboard.`);
                } else if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nomor Tiket Disalin',
                        text: ticket,
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            });
        }
    };

    // Quick Ticket Search Handler
    window.handleQuickTicketSearch = function(e) {
        e.preventDefault();
        const input = document.getElementById('quick-ticket-input');
        if (input && input.value.trim()) {
            const ticket = input.value.trim().toUpperCase();
            window.location.href = `/lapor-etam/tiket/${ticket}`;
        }
    };
</script>
@endpush
