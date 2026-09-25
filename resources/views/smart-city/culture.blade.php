@extends('layouts.public')

@section('title', 'Budaya & Pariwisata Kesultanan Kutai — Habar Etam')
@section('meta_description', 'Eksplorasi warisan sejarah Kesultanan Kutai Kartanegara Ing Martadipura, Museum Mulawarman, Pulau Kumala, dan destinasi pariwisata Tenggarong.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Modern Tile Styling */
    .culture-tile-osm .leaflet-tile-pane {
        filter: contrast(104%) brightness(99%) saturate(88%) hue-rotate(-2deg);
    }
    .culture-tile-streets .leaflet-tile-pane {
        filter: contrast(102%) saturate(92%);
    }
    .culture-tile-dark .leaflet-tile-pane {
        filter: brightness(0.92) contrast(1.08);
    }

    #culture-map {
        min-height: 480px;
        z-index: 10;
        background: #f8fafc;
    }

    /* Hide standard ugly Leaflet default controls in favor of custom HUD */
    .leaflet-control-zoom {
        display: none !important;
    }
    .leaflet-control-attribution {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border-radius: 8px 0 0 0 !important;
        font-size: 9px !important;
        color: #64748b !important;
        padding: 2px 8px !important;
    }

    /* Radar Ping Animation for Culture Telemetry Pins */
    @keyframes culture-pulse {
        0% { transform: scale(0.6); opacity: 0.9; }
        50% { transform: scale(1.6); opacity: 0.35; }
        100% { transform: scale(2.4); opacity: 0; }
    }

    .culture-pulse-effect {
        animation: culture-pulse 2.4s cubic-bezier(0.1, 0.8, 0.3, 1) infinite;
    }

    .culture-custom-marker {
        transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .culture-custom-marker:hover {
        transform: scale(1.22) translateY(-4px) !important;
        z-index: 9999 !important;
    }

    /* Glassmorphism Popup */
    .leaflet-popup-content-wrapper {
        background: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(226, 232, 240, 0.85) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.04) !important;
        padding: 0 !important;
        overflow: hidden !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        line-height: normal !important;
    }
    .leaflet-popup-tip {
        background: rgba(255, 255, 255, 0.96) !important;
        box-shadow: none !important;
    }
    .leaflet-container a.leaflet-popup-close-button {
        top: 12px !important;
        right: 12px !important;
        color: #64748b !important;
        font-size: 14px !important;
        width: 22px !important;
        height: 22px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 999px !important;
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.15s ease !important;
    }
    .leaflet-container a.leaflet-popup-close-button:hover {
        color: #0f172a !important;
        background: #e2e8f0 !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-amber-700 transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-gray-400">Smart City Hub</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-amber-900 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">Budaya & Pariwisata Kesultanan</span>
    </nav>

    <!-- 1. Hero Banner: Royal Heritage & Tourism Hub -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-[#18130c] to-amber-950 text-white p-6 sm:p-10 shadow-2xl border border-amber-500/20 mb-10">
        <!-- Ambient Glow Effects -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-yellow-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header Badges -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-900/60 border border-amber-400/40 text-amber-300 backdrop-blur-md">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-400 animate-pulse"></i>
                        <span>Warisan Luhur Kesultanan Kutai Martadipura</span>
                    </span>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-gray-300 border border-white/10">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>Dinas Pariwisata & Cagar Budaya Kukar</span>
                    </span>
                </div>

                <!-- Live Status Telemetry Pill -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-950/80 border border-amber-500/50 text-amber-300 text-xs font-bold shadow-xs">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                    <span>Pusat Informasi Budaya & Wisata Aktif</span>
                </div>
            </div>

            <!-- Title & Narrative -->
            <div class="max-w-3xl">
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Budaya, Cagar Sejarah & Destinasi Wisata Kutai Kartanegara
                </h1>
                <p class="text-sm sm:text-base text-amber-100/80 mt-3 leading-relaxed">
                    Menelusuri jejak peradaban aksara tertua di Nusantara (Prasasti Yupa abad ke-4), kemegahan Kedaton Kesultanan Kutai Kartanegara Ing Martadipura, museum peninggalan pusaka raja, keindahan pulau delta Mahakam, dan tradisi akbar pesta adat Erau.
                </p>
            </div>

            <!-- Quick Metrics Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-8 pt-6 border-t border-white/10">
                <!-- Metric 1: Total Destinasi -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-amber-300 text-xs font-semibold mb-1">
                        <i data-lucide="landmark" class="w-4 h-4"></i>
                        <span>Total Cagar Budaya</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $totalDestinations }} Lokasi</div>
                    <div class="text-[11px] text-amber-400 font-medium flex items-center gap-1 mt-0.5">
                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                        <span>Terverifikasi Dispar Kukar</span>
                    </div>
                </div>

                <!-- Metric 2: Kesultanan & Museum -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-yellow-300 text-xs font-semibold mb-1">
                        <i data-lucide="crown" class="w-4 h-4"></i>
                        <span>Pusat Keraton & Museum</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ ($categoryCounts['kesultanan'] ?? 0) + ($categoryCounts['museum_sejarah'] ?? 0) }} Objek</div>
                    <div class="text-[11px] text-gray-300 font-medium mt-0.5">
                        Pusaka & Istana Raja
                    </div>
                </div>

                <!-- Metric 3: Wisata Alam -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-emerald-300 text-xs font-semibold mb-1">
                        <i data-lucide="palmtree" class="w-4 h-4"></i>
                        <span>Wisata Alam & Rekreasi</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $categoryCounts['wisata_alam'] ?? 0 }} Lokasi</div>
                    <div class="text-[11px] text-emerald-300/80 font-medium mt-0.5">
                        Tepian & Delta Mahakam
                    </div>
                </div>

                <!-- Metric 4: Festival & Tradisi -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-purple-300 text-xs font-semibold mb-1">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>Festival & Kuliner Adat</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ ($categoryCounts['festival_adat'] ?? 0) + ($categoryCounts['kuliner_tradisi'] ?? 0) }} Agenda</div>
                    <div class="text-[11px] text-purple-300 font-medium flex items-center gap-1 mt-0.5">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        <span>Tradisi Tahunan Erau</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Interactive GIS Heritage Map -->
    @if($allLocations->isNotEmpty())
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card mb-10 overflow-hidden" id="culture-map-section">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-amber-800 mb-1">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-600"></span>
                        </span>
                        <span>Peta Geografis Cagar Budaya & Pariwisata</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100/80 text-amber-800 border border-amber-300">
                            <i data-lucide="compass" class="w-2.5 h-2.5 text-amber-700"></i>
                            <span>Pusat Wisata: Tenggarong</span>
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-brand-black tracking-tight">Sebaran Destinasi Cagar Budaya & Wisata Kukar</h2>
                </div>

                <!-- Layer Switcher & Quick Navigation Bar -->
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200 shadow-inner" id="map-layer-switcher">
                        <button type="button" onclick="switchCultureLayer('osm')" id="layer-btn-osm" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all bg-white text-gray-900 shadow-xs flex items-center gap-1.5">
                            <i data-lucide="map" class="w-3.5 h-3.5 text-amber-600"></i>
                            <span>Modern OSM</span>
                        </button>
                        <button type="button" onclick="switchCultureLayer('streets')" id="layer-btn-streets" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>
                            <span>Esri Streets</span>
                        </button>
                        <button type="button" onclick="switchCultureLayer('satellite')" id="layer-btn-satellite" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5">
                            <i data-lucide="satellite" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Satelit HD</span>
                        </button>
                        <button type="button" onclick="switchCultureLayer('dark')" id="layer-btn-dark" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5">
                            <i data-lucide="moon" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span>Dark Gray</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Leaflet Map Canvas with Floating HUD Elements -->
            <div class="relative w-full rounded-3xl overflow-hidden border border-slate-200/90 shadow-inner group/map">
                <!-- Main Map DIV -->
                <div id="culture-map" class="h-[520px] w-full culture-tile-osm z-10 relative"></div>

                <!-- Floating HUD: Top Left Live Status Pill -->
                <div class="absolute top-4 left-4 z-20 pointer-events-auto">
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-2xl bg-white/90 backdrop-blur-md border border-white/60 shadow-lg text-xs font-bold text-slate-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>Fokus Wilayah: <strong>Tenggarong</strong></span>
                        <span class="text-slate-300">|</span>
                        <span class="text-amber-800 font-extrabold text-[11px]" id="culture-active-count">{{ $allLocations->count() }} Objek Terpetakan</span>
                    </div>
                </div>

                <!-- Floating HUD: Bottom Left Interactive Category Filter Pills -->
                <div class="absolute bottom-4 left-4 z-20 pointer-events-auto hidden sm:flex items-center gap-1.5 p-1 bg-white/90 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg">
                    <button type="button" onclick="filterCultureCategory('all')" id="culture-cat-all" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all bg-amber-900 text-white shadow-xs">
                        Semua Objek
                    </button>
                    <button type="button" onclick="filterCultureCategory('kesultanan')" id="culture-cat-kesultanan" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span>Kesultanan</span>
                    </button>
                    <button type="button" onclick="filterCultureCategory('museum_sejarah')" id="culture-cat-museum_sejarah" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                        <span>Museum</span>
                    </button>
                    <button type="button" onclick="filterCultureCategory('wisata_alam')" id="culture-cat-wisata_alam" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Wisata Alam</span>
                    </button>
                    <button type="button" onclick="filterCultureCategory('festival_adat')" id="culture-cat-festival_adat" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>Festival Erau</span>
                    </button>
                </div>

                <!-- Floating HUD: Bottom Right Action Stack -->
                <div class="absolute bottom-4 right-4 z-20 pointer-events-auto flex flex-col items-end gap-2">
                    <!-- Zoom Quick Action Capsule -->
                    <div class="flex flex-col bg-white/90 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg overflow-hidden p-1 gap-1">
                        <button type="button" onclick="zoomInCultureMap()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 transition-colors font-bold text-base shadow-xs" title="Perbesar (Zoom In)">
                            +
                        </button>
                        <button type="button" onclick="zoomOutCultureMap()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 transition-colors font-bold text-base shadow-xs" title="Perkecil (Zoom Out)">
                            −
                        </button>
                    </div>

                    <!-- Reset & Fit Buttons -->
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="resetCultureTenggarong()" class="px-3 py-2 rounded-2xl bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-1.5 hover:scale-105" title="Kembali ke Pusat Tenggarong">
                            <i data-lucide="compass" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Pusat Tenggarong</span>
                        </button>
                        <button type="button" onclick="fitAllCulturePoints()" class="p-2 sm:px-3 sm:py-2 rounded-2xl bg-white/90 backdrop-blur-md hover:bg-white text-slate-700 font-bold text-xs border border-white/60 shadow-lg transition-all flex items-center gap-1.5" title="Tampilkan Seluruh Titik Kukar">
                            <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Semua Kukar</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center justify-between text-[11px] text-gray-500 mt-4 px-1 gap-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="mouse-pointer-click" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Klik pin cagar budaya untuk melihat jam operasional, tiket masuk, dan rute navigasi Google Maps.</span>
                </div>
                <span class="text-gray-400 font-medium">Kamera terpusat di kawasan cagar budaya Tenggarong</span>
            </div>
        </div>
    @endif

    <!-- 3. Filter & Search Toolbar -->
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-subtle mb-8">
        <form method="GET" action="{{ route('smart-city.culture') }}" class="space-y-4">
            
            <!-- Category Horizon Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('smart-city.culture', array_merge(request()->except(['category', 'page']), ['category' => ''])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ !request('category') ? 'bg-amber-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                    <span>Semua Cagar Budaya</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ !request('category') ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $totalDestinations }}</span>
                </a>

                <a href="{{ route('smart-city.culture', array_merge(request()->except(['category', 'page']), ['category' => 'kesultanan'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'kesultanan' ? 'bg-amber-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="crown" class="w-3.5 h-3.5 text-yellow-600"></i>
                    <span>Kesultanan Kutai</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'kesultanan' ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['kesultanan'] ?? 0 }}</span>
                </a>

                <a href="{{ route('smart-city.culture', array_merge(request()->except(['category', 'page']), ['category' => 'museum_sejarah'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'museum_sejarah' ? 'bg-amber-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="landmark" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Museum & Sejarah</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'museum_sejarah' ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['museum_sejarah'] ?? 0 }}</span>
                </a>

                <a href="{{ route('smart-city.culture', array_merge(request()->except(['category', 'page']), ['category' => 'wisata_alam'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'wisata_alam' ? 'bg-amber-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="palmtree" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Wisata Alam & Mahakam</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'wisata_alam' ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['wisata_alam'] ?? 0 }}</span>
                </a>

                <a href="{{ route('smart-city.culture', array_merge(request()->except(['category', 'page']), ['category' => 'festival_adat'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'festival_adat' ? 'bg-amber-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-purple-600"></i>
                    <span>Festival & Tradisi Erau</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'festival_adat' ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['festival_adat'] ?? 0 }}</span>
                </a>

                <a href="{{ route('smart-city.culture', array_merge(request()->except(['category', 'page']), ['category' => 'kuliner_tradisi'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('category') === 'kuliner_tradisi' ? 'bg-amber-900 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="utensils" class="w-3.5 h-3.5 text-orange-600"></i>
                    <span>Kuliner Khas Kutai</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('category') === 'kuliner_tradisi' ? 'bg-amber-800 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $categoryCounts['kuliner_tradisi'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Search and Dropdowns Row -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-2 border-t border-gray-100">
                <!-- Search Input -->
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari kedaton, museum, pulau kumala, erau, atau sejarah..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all placeholder:text-gray-400 text-brand-black">
                    @if(request('q'))
                        <a href="{{ route('smart-city.culture', request()->except('q')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>

                <!-- District Dropdown -->
                <div class="md:col-span-4">
                    <select name="district" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-amber-500 text-brand-black">
                        <option value="">Semua Wilayah / Kecamatan</option>
                        @foreach($availableDistricts as $dist)
                            <option value="{{ $dist }}" {{ request('district') === $dist ? 'selected' : '' }}>
                                {{ $dist }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Submit Button -->
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="w-full py-2.5 bg-amber-800 hover:bg-amber-900 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-colors shadow-xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'district']))
                        <a href="{{ route('smart-city.culture') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors shrink-0" title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>

        </form>
    </div>

    <!-- 4. Cultural Destinations Grid -->
    <div class="mb-14">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-black text-brand-black flex items-center gap-2">
                <i data-lucide="landmark" class="w-5 h-5 text-amber-700"></i>
                <span>Destinasi & Warisan Budaya Terdaftar ({{ $destinations->total() }})</span>
            </h2>
            <span class="text-xs text-gray-400">Kutai Kartanegara, Kalimantan Timur</span>
        </div>

        @if($destinations->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center shadow-subtle max-w-xl mx-auto">
                <div class="w-16 h-16 bg-amber-50 text-amber-700 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-amber-100">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-black text-brand-black">Tidak Ada Destinasi Sesuai Pencarian</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                    Coba ganti kata kunci pencarian, pilih kategori lain, atau reset filter untuk melihat seluruh koleksi cagar budaya.
                </p>
                <div class="mt-6">
                    <a href="{{ route('smart-city.culture') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-800 text-white text-xs font-bold hover:bg-amber-900 transition-colors shadow-xs">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>Reset Filter</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($destinations as $dest)
                    @php
                        $badgeTheme = 'bg-amber-50/95 text-amber-900 border-amber-200';
                        $catIcon = 'landmark';
                        $borderAccent = 'border-l-amber-600';

                        if ($dest->category === 'kesultanan') {
                            $badgeTheme = 'bg-yellow-50/95 text-yellow-900 border-yellow-200';
                            $catIcon = 'crown';
                            $borderAccent = 'border-l-yellow-500';
                        } elseif ($dest->category === 'museum_sejarah') {
                            $badgeTheme = 'bg-amber-50/95 text-amber-900 border-amber-200';
                            $catIcon = 'landmark';
                            $borderAccent = 'border-l-amber-600';
                        } elseif ($dest->category === 'wisata_alam') {
                            $badgeTheme = 'bg-emerald-50/95 text-emerald-900 border-emerald-200';
                            $catIcon = 'palmtree';
                            $borderAccent = 'border-l-emerald-500';
                        } elseif ($dest->category === 'festival_adat') {
                            $badgeTheme = 'bg-purple-50/95 text-purple-900 border-purple-200';
                            $catIcon = 'sparkles';
                            $borderAccent = 'border-l-purple-500';
                        } elseif ($dest->category === 'kuliner_tradisi') {
                            $badgeTheme = 'bg-orange-50/95 text-orange-900 border-orange-200';
                            $catIcon = 'utensils';
                            $borderAccent = 'border-l-orange-500';
                        }
                    @endphp

                    <div class="bg-white border border-gray-200/90 border-l-4 {{ $borderAccent }} rounded-2xl overflow-hidden shadow-subtle hover:shadow-lg hover:border-gray-300 transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Destination Image Banner -->
                            <div class="relative w-full aspect-[16/10] bg-slate-950 overflow-hidden">
                                @if($dest->cover_image)
                                    <img src="{{ asset($dest->cover_image) }}" 
                                         alt="{{ $dest->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-900 via-amber-950 to-slate-900 flex items-center justify-center">
                                        <i data-lucide="{{ $catIcon }}" class="w-12 h-12 text-amber-400/40"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                <!-- Floating Badges -->
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10px] font-extrabold border backdrop-blur-md shadow-xs {{ $badgeTheme }}">
                                        <i data-lucide="{{ $catIcon }}" class="w-3 h-3"></i>
                                        <span>{{ strtoupper(str_replace('_', ' ', $dest->category)) }}</span>
                                    </span>
                                </div>

                                <div class="absolute top-3 right-3 z-10">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-black/60 text-white/95 backdrop-blur-md border border-white/20 shadow-xs">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-amber-400"></i>
                                        <span>{{ $dest->location_district }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="p-5 sm:p-6">
                                <!-- Title -->
                                <h3 class="text-base font-black text-brand-black group-hover:text-amber-800 transition-colors leading-snug mb-2">
                                    <a href="{{ route('smart-city.culture.show', $dest->slug) }}">
                                        {{ $dest->title }}
                                    </a>
                                </h3>

                                <!-- Historical Context Highlight -->
                                @if($dest->historical_context)
                                    <div class="my-3 p-3 rounded-xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-950 flex items-start gap-2">
                                        <i data-lucide="scroll" class="w-4 h-4 text-amber-700 shrink-0 mt-0.5"></i>
                                        <p class="leading-relaxed text-[11px] line-clamp-2">
                                            {{ $dest->historical_context }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Description -->
                                <p class="text-xs text-gray-600 leading-relaxed mt-2 line-clamp-2">
                                    {{ $dest->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer & Actions -->
                        <div class="px-5 pb-5 sm:px-6 sm:pb-6 pt-0 space-y-3">
                            <!-- Address & Operating Info -->
                            <div class="space-y-1.5 text-[11px] text-gray-500 border-t border-gray-100 pt-3">
                                <div class="flex items-center gap-1.5 text-brand-black font-medium">
                                    <i data-lucide="compass" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                    <span class="truncate">{{ $dest->address }}</span>
                                </div>
                                
                                @if($dest->operating_info)
                                    <div class="flex items-center gap-1.5 text-gray-500">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                        <span class="truncate">{{ $dest->operating_info }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2 pt-2">
                                <a href="{{ route('smart-city.culture.show', $dest->slug) }}" 
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-amber-900 hover:bg-amber-800 text-white text-xs font-bold transition-colors shadow-xs">
                                    <span>Pelajari Sejarah</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>

                                @if($dest->latitude && $dest->longitude)
                                    <button type="button" 
                                            onclick="focusCultureOnMap({{ $dest->latitude }}, {{ $dest->longitude }}, '{{ addslashes($dest->title) }}')"
                                            class="inline-flex items-center justify-center p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition-colors"
                                            title="Lihat Titik di Peta">
                                        <i data-lucide="map" class="w-4 h-4 text-amber-700"></i>
                                    </button>
                                @endif

                                <button type="button" 
                                        onclick="copyCultureSummary('{{ addslashes($dest->title) }}', '{{ addslashes($dest->address) }}', '{{ addslashes($dest->operating_info ?: '-') }}', '{{ addslashes($dest->description) }}')"
                                        class="inline-flex items-center justify-center p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors"
                                        title="Salin Rangkuman">
                                    <i data-lucide="copy" class="w-4 h-4 text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $destinations->links('partials.pagination') }}
            </div>
        @endif
    </div>

    <!-- 5. Educational Narrative Section: Jejak Sejarah Kerajaan Tertua Nusantara -->
    <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-amber-950 text-white rounded-3xl p-6 sm:p-10 shadow-card border border-amber-500/20 mb-12">
        <div class="max-w-3xl mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-900/60 border border-amber-400/40 text-amber-300 backdrop-blur-md mb-2">
                <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                <span>Ensiklopedi Sejarah Kutai</span>
            </span>
            <h3 class="text-xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                Pilar Peradaban & Warisan Luhur Kutai Kartanegara
            </h3>
            <p class="text-xs sm:text-sm text-amber-100/80 mt-2 leading-relaxed">
                Kutai Kartanegara memegang peranan sakral dalam sejarah kebangsaan Indonesia sebagai tempat lahirnya aksara dan pemerintahan kerajaan tertua di Tanah Nusantara.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Story Card 1 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center mb-4 border border-amber-500/30">
                    <i data-lucide="scroll" class="w-5 h-5"></i>
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Prasasti Yupa (Abad ke-4 M)</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Tujuh tugu batu beraksara Pallawa dan bahasa Sanskerta peninggalan Raja Mulawarman di Muara Kaman yang menjadi tonggak dimulainya era sejarah tertulis di Indonesia.
                </p>
            </div>

            <!-- Story Card 2 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-yellow-500/20 text-yellow-400 flex items-center justify-center mb-4 border border-yellow-500/30">
                    <i data-lucide="crown" class="w-5 h-5"></i>
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Kesultanan Ing Martadipura</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Didirikan pada abad ke-13 di Jembayan oleh Aji Batara Agung Dewa Sakti, bertransformasi menjadi kesultanan Islam maritim yang menguasai jalur perdagangan Sungai Mahakam.
                </p>
            </div>

            <!-- Story Card 3 -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mb-4 border border-purple-500/30">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <h4 class="text-base font-extrabold text-white mb-2">Tradisi Sakral Erau</h4>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Pesta adat akbar tahunan dengan ritual sakral Mendirikan Ayu, Bepelas, Mengulur Naga, dan Belimbur yang kini diakui sebagai agenda festival budaya internasional (EIFAF).
                </p>
            </div>
        </div>
    </div>

    <!-- 6. Bottom Civic Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-amber-900 via-slate-900 to-amber-950 p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-card border border-amber-500/20">
        <div class="space-y-1.5 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-800/80 text-amber-300 text-xs font-bold mb-1">
                <i data-lucide="compass" class="w-3.5 h-3.5"></i>
                <span>Panduan Wisata & Destinasi Edukasi</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-white">Rencanakan Kunjungan Budaya Anda</h3>
            <p class="text-xs sm:text-sm text-amber-100/80 max-w-xl">
                Dapatkan panduan lengkap lokasi bersejarah, jam buka museum, agenda festival Erau, serta paket wisata edukasi Kesultanan Kutai Kartanegara.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="https://maps.google.com/?q=Museum+Mulawarman+Tenggarong" target="_blank" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs inline-flex items-center gap-2 shadow-lg transition-transform hover:scale-105">
                <i data-lucide="navigation" class="w-4 h-4"></i>
                <span>Rute ke Museum Mulawarman</span>
            </a>
            <a href="{{ route('smart-city.emergency') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs inline-flex items-center gap-2 transition-all">
                <i data-lucide="phone-call" class="w-4 h-4 text-amber-400"></i>
                <span>Kontak Layanan Pengunjung</span>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let cultureMap = null;
    const cultureMarkersMap = {};
    const allCultureMarkersData = [];
    let tenggarongCenter = [-0.4300, 116.9800];
    let currentCultureTileLayer = null;
    let allCulturePointsGroup = [];

    // Switch Tile Layer
    window.switchCultureLayer = function(type) {
        if (!cultureMap || typeof L === 'undefined') return;

        const mapEl = document.getElementById('culture-map');
        if (mapEl) {
            mapEl.classList.remove('culture-tile-osm', 'culture-tile-streets', 'culture-tile-dark');
        }

        if (currentCultureTileLayer) {
            cultureMap.removeLayer(currentCultureTileLayer);
        }

        if (type === 'satellite') {
            currentCultureTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri Satellite Imagery • Habar Etam'
            });
        } else if (type === 'streets') {
            if (mapEl) mapEl.classList.add('culture-tile-streets');
            currentCultureTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri Street Map • Habar Etam'
            });
        } else if (type === 'dark') {
            if (mapEl) mapEl.classList.add('culture-tile-dark');
            currentCultureTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 16,
                attribution: '© Esri Dark Gray • Habar Etam'
            });
        } else {
            type = 'osm';
            if (mapEl) mapEl.classList.add('culture-tile-osm');
            currentCultureTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors • Habar Etam'
            });
        }

        currentCultureTileLayer.addTo(cultureMap);

        // Update Button State
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
    window.zoomInCultureMap = function() {
        if (cultureMap) cultureMap.zoomIn();
    };
    window.zoomOutCultureMap = function() {
        if (cultureMap) cultureMap.zoomOut();
    };

    // Reset ke Pusat Tenggarong
    window.resetCultureTenggarong = function() {
        if (!cultureMap) return;
        cultureMap.flyTo(tenggarongCenter, 13.5, {
            duration: 1.2,
            easeLinearity: 0.25
        });
    };

    // Fit Semua Titik
    window.fitAllCulturePoints = function() {
        if (!cultureMap || allCulturePointsGroup.length === 0) return;
        cultureMap.flyToBounds(allCulturePointsGroup, {
            padding: [50, 50],
            duration: 1.4,
            maxZoom: 14
        });
    };

    // Filter Kategori Langsung pada Peta
    window.filterCultureCategory = function(cat) {
        if (!cultureMap) return;

        let visibleCount = 0;

        allCultureMarkersData.forEach(item => {
            const isMatch = (cat === 'all') || (item.data.category === cat);

            if (isMatch) {
                if (!cultureMap.hasLayer(item.marker)) {
                    item.marker.addTo(cultureMap);
                }
                visibleCount++;
            } else {
                if (cultureMap.hasLayer(item.marker)) {
                    cultureMap.removeLayer(item.marker);
                }
            }
        });

        // Update Count
        const countEl = document.getElementById('culture-active-count');
        if (countEl) countEl.innerText = `${visibleCount} Objek Ditampilkan`;

        // Update Button State
        ['all', 'kesultanan', 'museum_sejarah', 'wisata_alam', 'festival_adat'].forEach(id => {
            const btn = document.getElementById(`culture-cat-${id}`);
            if (btn) {
                if (id === cat) {
                    btn.className = 'px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all bg-amber-900 text-white shadow-xs';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1';
                }
            }
        });
    };

    function initCultureMap() {
        const mapEl = document.getElementById('culture-map');
        if (!mapEl || typeof L === 'undefined') return;

        cultureMap = L.map('culture-map', {
            center: tenggarongCenter,
            zoom: 13.5,
            minZoom: 8,
            maxZoom: 19,
            zoomControl: false,
            scrollWheelZoom: true,
            smoothWheelZoom: true
        });

        // Set default tile
        switchCultureLayer('osm');

        const locations = @json($allLocations);
        allCulturePointsGroup = [];

        locations.forEach(loc => {
            if (loc.latitude && loc.longitude) {
                const lat = parseFloat(loc.latitude);
                const lng = parseFloat(loc.longitude);

                // Category theme setup
                let themeColor = '#b45309'; // Amber
                let lightBg = '#fef3c7';
                let textColor = '#92400e';
                let iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="2" y1="22" x2="22" y2="22"/><line x1="12" y1="2" x2="12" y2="18"/><path d="M4 22V10a8 8 0 0 1 16 0v12"/></svg>`;
                let badgeText = 'CAGAR BUDAYA';

                if (loc.category === 'kesultanan') {
                    themeColor = '#d97706'; // Gold
                    lightBg = '#fef3c7';
                    textColor = '#78350f';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>`;
                    badgeText = 'KESULTANAN KUTAI';
                } else if (loc.category === 'museum_sejarah') {
                    themeColor = '#92400e'; // Bronze
                    lightBg = '#ffedd5';
                    textColor = '#7c2d12';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x1="21" x2="21" y2="21"/><line x1="6" y1="21" x2="6" y2="10"/><line x1="18" y1="21" x2="18" y2="10"/><path d="M12 3 3 10h18L12 3z"/></svg>`;
                    badgeText = 'MUSEUM SEJARAH';
                } else if (loc.category === 'wisata_alam') {
                    themeColor = '#059669'; // Emerald
                    lightBg = '#d1fae5';
                    textColor = '#065f46';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h11z"/><path d="M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-9"/><path d="M5.89 15.5c-1.5 1.5-2.4 3.2-2.4 4.5h17c0-1.3-.9-3-2.4-4.5"/><line x1="12" y1="8" x2="12" y2="15.5"/></svg>`;
                    badgeText = 'WISATA ALAM';
                } else if (loc.category === 'festival_adat') {
                    themeColor = '#9333ea'; // Purple
                    lightBg = '#f3e8ff';
                    textColor = '#6b21a8';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3L12 3z"/></svg>`;
                    badgeText = 'FESTIVAL ERAU';
                } else if (loc.category === 'kuliner_tradisi') {
                    themeColor = '#ea580c'; // Orange
                    lightBg = '#ffedd5';
                    textColor = '#9a3412';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/><path d="M15 11v11"/><path d="M5 2v10a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V2"/></svg>`;
                    badgeText = 'KULINER KUTAI';
                }

                // Marker HTML
                const customMarkerHtml = `
                    <div class="relative w-10 h-10 flex items-center justify-center culture-custom-marker" style="cursor:pointer;">
                        <!-- Animated Ripple -->
                        <div class="culture-pulse-effect absolute w-10 h-10 rounded-full" style="background:${themeColor};"></div>
                        
                        <!-- Core Pin Badge -->
                        <div class="relative w-8 h-8 rounded-2xl flex items-center justify-center text-white shadow-xl" 
                             style="background:linear-gradient(135deg, ${themeColor}, ${themeColor}dd); border: 2.5px solid #ffffff; box-shadow: 0 8px 16px -2px ${themeColor}66;">
                            ${iconSvg}
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: customMarkerHtml,
                    className: 'culture-marker-wrapper',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -22]
                });

                // Glassmorphism Popup
                const imageHeaderHtml = loc.cover_image 
                    ? `<div style="width:100%; height:110px; overflow:hidden; position:relative; background:#0f172a;">
                         <img src="/${loc.cover_image.replace(/^\//, '')}" alt="${loc.title}" style="width:100%; height:100%; object-fit:cover; display:block;">
                         <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.55), transparent);"></div>
                       </div>` 
                    : '';

                const popupContent = `
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; width:280px; overflow:hidden;">
                        <!-- Header Accent Color Line -->
                        <div style="height:4px; width:100%; background:${themeColor};"></div>
                        ${imageHeaderHtml}

                        <div style="padding:14px 16px;">
                            <!-- Meta Badges -->
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                                <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:2.5px 8px; border-radius:999px; background:${lightBg}; color:${textColor};">
                                    ${badgeText}
                                </span>
                                <span style="font-size:10px; font-weight:600; color:#94a3b8;">
                                    ${loc.location_district || 'Tenggarong'}
                                </span>
                            </div>

                            <!-- Title -->
                            <h4 style="font-size:13px; font-weight:800; color:#0f172a; margin:0 0 6px 0; line-height:1.35;">
                                ${loc.title}
                            </h4>

                            <!-- Location Info -->
                            <div style="display:flex; align-items:flex-start; gap:6px; font-size:11px; color:#64748b; margin-bottom:8px;">
                                <svg style="width:13px; height:13px; color:#64748b; flex-shrink:0; margin-top:1px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>${loc.address || 'Kutai Kartanegara'}</span>
                            </div>

                            <!-- Operating Info Pill -->
                            ${loc.operating_info ? `
                                <div style="font-size:10.5px; font-weight:700; color:${textColor}; background:${lightBg}; border:1px solid ${themeColor}33; padding:5px 9px; border-radius:8px; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                                    <span style="width:5px; height:5px; border-radius:50%; background:${themeColor};"></span>
                                    <span style="line-height:1.3;">${loc.operating_info}</span>
                                </div>
                            ` : ''}

                            <!-- Footer Actions -->
                            <div style="padding-top:8px; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; gap:6px;">
                                <a href="/smart-city/budaya/${loc.slug}" 
                                   style="font-size:11px; font-weight:800; color:${themeColor}; text-decoration:none; display:inline-flex; align-items:center; gap:3px;">
                                    <span>Pelajari Detail</span>
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
                    .addTo(cultureMap)
                    .bindPopup(popupContent, { maxWidth: 320 });

                cultureMarkersMap[`${lat}_${lng}`] = marker;
                allCultureMarkersData.push({ marker: marker, data: loc, coords: [lat, lng] });
                allCulturePointsGroup.push([lat, lng]);
            }
        });

        // Set default view: Tenggarong
        cultureMap.setView(tenggarongCenter, 13.5);

        if (window.lucide) {
            window.lucide.createIcons();
        }

        setTimeout(() => {
            if (cultureMap) {
                cultureMap.invalidateSize();
            }
        }, 250);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCultureMap);
    } else {
        initCultureMap();
    }

    // Function to pan and focus on a specific map point
    window.focusCultureOnMap = function(lat, lng, title) {
        const mapSection = document.getElementById('culture-map-section');
        if (mapSection) {
            mapSection.scrollIntoView({ behavior: 'smooth' });
        }

        if (!cultureMap) return;

        setTimeout(() => {
            cultureMap.flyTo([lat, lng], 15.5, {
                duration: 1.2,
                easeLinearity: 0.25
            });

            const key = `${lat}_${lng}`;
            if (cultureMarkersMap[key]) {
                setTimeout(() => {
                    cultureMarkersMap[key].openPopup();
                }, 600);
            }
        }, 350);
    };

    // Copy Summary Toast
    window.copyCultureSummary = function(title, address, operating, desc) {
        const textToCopy = `[CAGAR BUDAYA & WISATA KUKAR]\nObjek: ${title}\nAlamat: ${address}\nOperasional: ${operating}\nDeskripsi: ${desc}\n\nSumber: Habar Etam Smart City`;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                if (window.showToast) {
                    window.showToast('success', 'Informasi Disalin', 'Rincian objek budaya berhasil disalin ke papan klip.');
                } else if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Informasi Disalin',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            });
        }
    };
</script>
@endpush
