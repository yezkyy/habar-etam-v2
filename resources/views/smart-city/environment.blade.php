@extends('layouts.public')

@section('title', 'Lingkungan & Pantauan Air Mahakam — Habar Etam')
@section('meta_description', 'Pantauan tinggi muka air (TMA) Sungai Mahakam Tenggarong, titik pasang surut banjir, peringatan cuaca BMKG, dan kondisi infrastruktur Kukar.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Modern Tile Styling: removes the garish template look of standard OSM */
    .env-tile-osm .leaflet-tile-pane {
        filter: contrast(104%) brightness(99%) saturate(88%) hue-rotate(-2deg);
    }
    .env-tile-streets .leaflet-tile-pane {
        filter: contrast(102%) saturate(92%);
    }
    .env-tile-dark .leaflet-tile-pane {
        filter: brightness(0.92) contrast(1.08);
    }

    #env-map {
        min-height: 340px;
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

    /* Radar Ping Animation for Active Telemetry Pins */
    @keyframes radar-ripple {
        0% {
            transform: scale(0.6);
            opacity: 0.9;
        }
        50% {
            transform: scale(1.6);
            opacity: 0.35;
        }
        100% {
            transform: scale(2.4);
            opacity: 0;
        }
    }

    .radar-pulse-effect {
        animation: radar-ripple 2.4s cubic-bezier(0.1, 0.8, 0.3, 1) infinite;
    }

    .env-custom-marker {
        transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .env-custom-marker:hover {
        transform: scale(1.22) translateY(-4px) !important;
        z-index: 9999 !important;
    }

    /* Futuristic Glassmorphism Popup */
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
        <a href="{{ route('home') }}" class="hover:text-cyan-700 transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-gray-400">Smart City Hub</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-cyan-800 font-bold bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-200">Lingkungan & TMA Mahakam</span>
    </nav>

    <!-- 1. Hero Banner: Civic Environmental Telemetry Dashboard -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950 text-white p-6 sm:p-10 shadow-2xl border border-cyan-500/20 mb-10">
        <!-- Ambient Water / Glow Effects -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header Badges -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-cyan-900/60 border border-cyan-400/40 text-cyan-300 backdrop-blur-md">
                        <i data-lucide="waves" class="w-3.5 h-3.5 text-cyan-400 animate-pulse"></i>
                        <span>Sistem Pantauan Lingkungan Kukar</span>
                    </span>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-gray-300 border border-white/10">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>BWS Kalimantan IV & BPBD Kukar</span>
                    </span>
                </div>

                <!-- Live Status Telemetry Pill -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs font-bold shadow-xs">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span>Pos Sensor & Telemetri Aktif</span>
                </div>
            </div>

            <!-- Title & Narrative -->
            <div class="max-w-3xl">
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Lingkungan, Debit Sungai Mahakam & Infrastruktur
                </h1>
                <p class="text-sm sm:text-base text-cyan-100/80 mt-3 leading-relaxed">
                    Pusat transparansi data tinggi muka air (TMA) Sungai Mahakam, peringatan dini potensi genangan pasang surut, ramalan cuaca BMKG, serta pemeliharaan infrastruktur strategis di Kutai Kartanegara.
                </p>
            </div>

            <!-- Quick Metrics / Telemetry Counter Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-8 pt-6 border-t border-white/10">
                <!-- Metric 1: TMA Rata-Rata -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-cyan-300 text-xs font-semibold mb-1">
                        <i data-lucide="gauge" class="w-4 h-4"></i>
                        <span>TMA Pulau Kumala</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">4.10 M</div>
                    <div class="text-[11px] text-emerald-400 font-medium flex items-center gap-1 mt-0.5">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Level Normal / Aman</span>
                    </div>
                </div>

                <!-- Metric 2: Titik Siaga / Peringatan -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-amber-300 text-xs font-semibold mb-1">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <span>Peringatan & Siaga</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $warningCount }} Lokasi</div>
                    <div class="text-[11px] text-gray-300 font-medium mt-0.5">
                        {{ $alertCount }} Peringatan Aktif
                    </div>
                </div>

                <!-- Metric 3: Infrastruktur -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-blue-300 text-xs font-semibold mb-1">
                        <i data-lucide="wrench" class="w-4 h-4"></i>
                        <span>Infrastruktur & Jembatan</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $infraCount }} Titik</div>
                    <div class="text-[11px] text-cyan-300/80 font-medium mt-0.5">
                        Semua Jalur Dibuka
                    </div>
                </div>

                <!-- Metric 4: Indeks Kualitas Udara (AQI) -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-2 text-emerald-300 text-xs font-semibold mb-1">
                        <i data-lucide="wind" class="w-4 h-4"></i>
                        <span>Kualitas Udara (AQI)</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white">28 AQI</div>
                    <div class="text-[11px] text-emerald-400 font-medium flex items-center gap-1 mt-0.5">
                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                        <span>Baik / Sangat Bersih</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Live Telemetry & Hydro-Meteorology Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        
        <!-- Widget 1: TMA Mahakam Gauges -->
        <div class="bg-white border border-gray-200/90 rounded-2xl p-6 shadow-subtle flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <div class="flex items-center gap-2 text-cyan-800 font-bold text-sm">
                        <i data-lucide="activity" class="w-4 h-4 text-cyan-600"></i>
                        <span>Pos Pantau Air Mahakam</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-800">
                        {{ $waterLevels->count() }} Sensor Aktif
                    </span>
                </div>

                <div class="space-y-3.5">
                    @forelse($waterLevels as $wl)
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-cyan-300 transition-colors">
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <h3 class="text-xs font-extrabold text-brand-black leading-snug line-clamp-1">{{ $wl->title }}</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $wl->severity === 'danger' ? 'bg-red-100 text-red-800' : ($wl->severity === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ strtoupper($wl->severity) }}
                                </span>
                            </div>
                            <div class="flex items-baseline justify-between text-xs mt-1">
                                <span class="text-gray-500 text-[11px]">{{ $wl->location_district }}</span>
                                <span class="font-extrabold {{ $wl->severity === 'danger' ? 'text-red-600' : ($wl->severity === 'warning' ? 'text-amber-600' : 'text-cyan-700') }}">
                                    {{ $wl->status_condition ?: 'Normal' }}
                                </span>
                            </div>
                            <!-- Mini Gauge Progress Indicator -->
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $wl->severity === 'danger' ? 'bg-red-500 w-4/5' : ($wl->severity === 'warning' ? 'bg-amber-500 w-3/5' : 'bg-emerald-500 w-2/5') }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-gray-400">
                            Data pos pantau belum tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 mt-4 text-[11px] text-gray-500 flex items-center justify-between">
                <span>Sumber: BWS Kalimantan IV & BPBD</span>
                <span class="text-gray-400">Update Realtime</span>
            </div>
        </div>

        <!-- Widget 2: Prakiraan Cuaca & Kondisi Pasang Mahakam -->
        <div class="bg-white border border-gray-200/90 rounded-2xl p-6 shadow-subtle flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <div class="flex items-center gap-2 text-slate-800 font-bold text-sm">
                        <i data-lucide="cloud-sun" class="w-4 h-4 text-amber-500"></i>
                        <span>Prakiraan Cuaca & Pasang Surut</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        BMKG Stasiun Sepinggan
                    </span>
                </div>

                <!-- Weather Status Highlights -->
                <div class="bg-gradient-to-br from-amber-50/60 to-orange-50/60 border border-amber-200/60 rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-900 block">Tenggarong Kota</span>
                            <div class="text-2xl font-black text-brand-black mt-0.5">30°C</div>
                            <span class="text-xs font-semibold text-amber-800">Cerah Berawan • Siang Hari</span>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 border border-amber-500/20">
                            <i data-lucide="sun-medium" class="w-7 h-7"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-amber-200/60 text-[11px] text-gray-600">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="droplet" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Kelembaban: <strong>78%</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="wind" class="w-3.5 h-3.5 text-cyan-600"></i>
                            <span>Angin: <strong>11 km/j</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Mahakam Tide Note -->
                <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/70 text-xs text-blue-900 flex items-start gap-2.5">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5"></i>
                    <p class="leading-relaxed text-[11px]">
                        <strong>Fase Pasang Mahakam:</strong> Diprakirakan puncak pasang surut terjadi pada rentang pukul 20.30 - 23.00 WITA. Warga tepian disarankan waspada limpasan sesaat.
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 mt-4 text-[11px] text-gray-500 flex items-center justify-between">
                <span>Pembaruan: Hari ini, 06.00 WITA</span>
                <span class="text-cyan-700 font-bold">Kondisi Kondusif</span>
            </div>
        </div>

        <!-- Widget 3: Kanal Cepat Lapor Genangan & Darurat -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-2xl p-6 shadow-subtle flex flex-col justify-between border border-slate-800">
            <div>
                <div class="flex items-center gap-2 text-cyan-400 font-bold text-sm pb-3 border-b border-slate-800 mb-4">
                    <i data-lucide="megaphone" class="w-4 h-4"></i>
                    <span>Partisipasi Warga & Siaga Bencana</span>
                </div>

                <p class="text-xs text-gray-300 leading-relaxed mb-5">
                    Melihat titik genangan baru, pohon tumbang di bantaran Mahakam, atau jalan rusak pasca hujan deras? Laporkan langsung agar ditindaklanjuti instansi terkait.
                </p>

                <div class="space-y-3">
                    <a href="{{ route('reports.create') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs shadow-md transition-all">
                        <i data-lucide="file-plus" class="w-4 h-4"></i>
                        <span>Buat Laporan Genangan / Bencana</span>
                    </a>

                    <a href="{{ route('smart-city.emergency') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-gray-200 border border-white/10 font-bold text-xs transition-all">
                        <i data-lucide="phone-call" class="w-4 h-4 text-amber-400"></i>
                        <span>Kontak Darurat BPBD & Damkar (24 Jam)</span>
                    </a>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 mt-5 flex items-center justify-between text-[11px] text-gray-400">
                <span>Call Center BPBD Kukar:</span>
                <a href="tel:0541663242" class="font-bold text-cyan-400 hover:underline">(0541) 663242</a>
            </div>
        </div>

    </div>

    <!-- 3. Interactive Leaflet Map -->
    @if($allPoints->isNotEmpty())
        <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card mb-10 overflow-hidden" id="map-section">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-cyan-800 mb-1">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-600"></span>
                        </span>
                        <span>Sistem GIS Lingkungan & Telemetri Realtime</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-cyan-100/80 text-cyan-800 border border-cyan-300">
                            <i data-lucide="compass" class="w-2.5 h-2.5 text-cyan-700"></i>
                            <span>Fokus Utama: Tenggarong</span>
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-brand-black tracking-tight">Sebaran Pos Pantau, Debit Air & Infrastruktur</h2>
                </div>

                <!-- Layer Switcher & Quick Navigation Bar (Responsive Horizontal Scroll on Mobile) -->
                <div class="w-full lg:w-auto flex items-center overflow-x-auto max-w-full pb-1 scrollbar-none">
                    <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200 shadow-inner shrink-0" id="map-layer-switcher">
                        <button type="button" onclick="switchMapLayer('osm')" id="layer-btn-osm" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all bg-white text-gray-900 shadow-xs flex items-center gap-1.5 shrink-0">
                            <i data-lucide="map" class="w-3.5 h-3.5 text-cyan-600"></i>
                            <span>Modern OSM</span>
                        </button>
                        <button type="button" onclick="switchMapLayer('streets')" id="layer-btn-streets" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5 shrink-0">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>
                            <span>Esri Streets</span>
                        </button>
                        <button type="button" onclick="switchMapLayer('satellite')" id="layer-btn-satellite" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5 shrink-0">
                            <i data-lucide="satellite" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Satelit HD</span>
                        </button>
                        <button type="button" onclick="switchMapLayer('dark')" id="layer-btn-dark" class="px-3 py-1.5 text-[11px] font-bold rounded-xl transition-all text-gray-600 hover:text-gray-900 flex items-center gap-1.5 shrink-0">
                            <i data-lucide="moon" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span>Dark Gray</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Leaflet Map Canvas with Floating HUD Elements -->
            <div class="relative w-full rounded-3xl overflow-hidden border border-slate-200/90 shadow-inner group/map">
                <!-- Main Map DIV (Responsive height: 360px on mobile, 450px on tablet, 520px on desktop) -->
                <div id="env-map" class="h-[360px] sm:h-[450px] md:h-[520px] w-full env-tile-osm z-10 relative"></div>

                <!-- Floating HUD: Top Left Live Status Pill -->
                <div class="absolute top-4 left-4 z-20 pointer-events-auto">
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-2xl bg-white/90 backdrop-blur-md border border-white/60 shadow-lg text-xs font-bold text-slate-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Pusat: <strong>Tenggarong</strong></span>
                        <span class="text-slate-300">|</span>
                        <span class="text-cyan-700 font-extrabold text-[11px]" id="map-active-count">{{ $allPoints->count() }} Titik Aktif</span>
                    </div>
                </div>

                <!-- Floating HUD: Bottom Left Interactive Category Filter Pills -->
                <div class="absolute bottom-4 left-4 z-20 pointer-events-auto hidden sm:flex items-center gap-1.5 p-1 bg-white/90 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg">
                    <button type="button" onclick="filterMapCategory('all')" id="map-cat-all" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all bg-cyan-800 text-white shadow-xs">
                        Semua Titik
                    </button>
                    <button type="button" onclick="filterMapCategory('water_level')" id="map-cat-water_level" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>TMA Mahakam</span>
                    </button>
                    <button type="button" onclick="filterMapCategory('alert')" id="map-cat-alert" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Siaga / Peringatan</span>
                    </button>
                    <button type="button" onclick="filterMapCategory('infrastructure')" id="map-cat-infrastructure" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Infrastruktur</span>
                    </button>
                </div>

                <!-- Floating HUD: Bottom Right Action Stack -->
                <div class="absolute bottom-4 right-4 z-20 pointer-events-auto flex flex-col items-end gap-2">
                    <!-- Zoom & Pan Quick Action Capsule -->
                    <div class="flex flex-col bg-white/90 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg overflow-hidden p-1 gap-1">
                        <button type="button" onclick="zoomInMap()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 transition-colors font-bold text-base shadow-xs" title="Perbesar (Zoom In)">
                            +
                        </button>
                        <button type="button" onclick="zoomOutMap()" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 transition-colors font-bold text-base shadow-xs" title="Perkecil (Zoom Out)">
                            −
                        </button>
                    </div>

                    <!-- Reset & Fit Buttons -->
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="resetTenggarongView()" class="px-3 py-2 rounded-2xl bg-cyan-700 hover:bg-cyan-800 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-1.5 hover:scale-105" title="Kembali ke Pusat Tenggarong">
                            <i data-lucide="compass" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Pusat Tenggarong</span>
                        </button>
                        <button type="button" onclick="fitAllKukarPoints()" class="p-2 sm:px-3 sm:py-2 rounded-2xl bg-white/90 backdrop-blur-md hover:bg-white text-slate-700 font-bold text-xs border border-white/60 shadow-lg transition-all flex items-center gap-1.5" title="Tampilkan Seluruh Titik Kukar">
                            <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Semua Kukar</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center justify-between text-[11px] text-gray-500 mt-4 px-1 gap-2">
                <div class="flex items-center gap-2">
                    <i data-lucide="mouse-pointer-click" class="w-3.5 h-3.5 text-cyan-600"></i>
                    <span>Klik sembarang pin radar untuk melihat elevasi muka air, status peringatan, dan koordinat realtime.</span>
                </div>
                <span class="text-gray-400 font-medium">Data diupdate otomatis dari sensor pos pantau</span>
            </div>
        </div>
    @endif

    <!-- 4. Filter & Search Toolbar -->
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-subtle mb-8">
        <form method="GET" action="{{ route('smart-city.environment') }}" class="space-y-4">
            
            <!-- Category Pills Horizon Bar -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('smart-city.environment', array_merge(request()->except(['type', 'page']), ['type' => ''])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ !request('type') ? 'bg-cyan-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                    <span>Semua Pantauan</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ !request('type') ? 'bg-cyan-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $totalPoints }}</span>
                </a>

                <a href="{{ route('smart-city.environment', array_merge(request()->except(['type', 'page']), ['type' => 'water_level'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('type') === 'water_level' ? 'bg-cyan-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="waves" class="w-3.5 h-3.5 text-blue-500"></i>
                    <span>TMA Sungai Mahakam</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('type') === 'water_level' ? 'bg-cyan-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $waterLevelCount }}</span>
                </a>

                <a href="{{ route('smart-city.environment', array_merge(request()->except(['type', 'page']), ['type' => 'flood_alert'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('type') === 'flood_alert' ? 'bg-cyan-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Titik Rawan Genangan</span>
                </a>

                <a href="{{ route('smart-city.environment', array_merge(request()->except(['type', 'page']), ['type' => 'weather_alert'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('type') === 'weather_alert' ? 'bg-cyan-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="cloud-lightning" class="w-3.5 h-3.5 text-orange-500"></i>
                    <span>Peringatan Cuaca BMKG</span>
                </a>

                <a href="{{ route('smart-city.environment', array_merge(request()->except(['type', 'page']), ['type' => 'landslide_prone'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('type') === 'landslide_prone' ? 'bg-cyan-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="mountain-snow" class="w-3.5 h-3.5 text-red-500"></i>
                    <span>Rawan Longsor Lereng</span>
                </a>

                <a href="{{ route('smart-city.environment', array_merge(request()->except(['type', 'page']), ['type' => 'infrastructure'])) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('type') === 'infrastructure' ? 'bg-cyan-800 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i data-lucide="wrench" class="w-3.5 h-3.5 text-slate-600"></i>
                    <span>Infrastruktur & Jembatan</span>
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] {{ request('type') === 'infrastructure' ? 'bg-cyan-700 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $infraCount }}</span>
                </a>
            </div>

            <!-- Search and Dropdowns Row -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-2 border-t border-gray-100">
                <!-- Search Input -->
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari lokasi, jembatan, bendung, atau info cuaca..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all placeholder:text-gray-400 text-brand-black">
                    @if(request('q'))
                        <a href="{{ route('smart-city.environment', request()->except('q')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>

                <!-- Severity Dropdown -->
                <div class="md:col-span-3">
                    <select name="severity" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-cyan-500 text-brand-black">
                        <option value="">Semua Tingkat Status</option>
                        <option value="normal" {{ request('severity') === 'normal' ? 'selected' : '' }}>Status: Normal / Aman</option>
                        <option value="warning" {{ request('severity') === 'warning' ? 'selected' : '' }}>Status: Waspada / Siaga</option>
                        <option value="danger" {{ request('severity') === 'danger' ? 'selected' : '' }}>Status: Bahaya / Kritis</option>
                    </select>
                </div>

                <!-- District Dropdown -->
                <div class="md:col-span-3">
                    <select name="district" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-cyan-500 text-brand-black">
                        <option value="">Semua Kecamatan</option>
                        @foreach($availableDistricts as $dist)
                            <option value="{{ $dist }}" {{ request('district') === $dist ? 'selected' : '' }}>
                                {{ $dist }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Submit Button -->
                <div class="md:col-span-1 flex items-center">
                    <button type="submit" class="w-full h-full py-2.5 bg-cyan-700 hover:bg-cyan-800 text-white rounded-xl text-xs font-bold flex items-center justify-center transition-colors shadow-xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Active Filter Badges Reset -->
            @if(request('q') || request('type') || request('severity') || request('district'))
                <div class="flex items-center justify-between text-xs text-gray-500 pt-2 border-t border-gray-100">
                    <span>Menampilkan hasil filter khusus</span>
                    <a href="{{ route('smart-city.environment') }}" class="text-cyan-700 hover:underline font-bold flex items-center gap-1">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset Semua Filter</span>
                    </a>
                </div>
            @endif

        </form>
    </div>

    <!-- 5. List of Environmental & Infrastructure Points -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-black text-brand-black flex items-center gap-2">
                <i data-lucide="radio" class="w-5 h-5 text-cyan-700"></i>
                <span>Laporan & Titik Pemantauan Aktif ({{ $points->count() }})</span>
            </h2>
            <span class="text-xs text-gray-400">Kutai Kartanegara, Kalimantan Timur</span>
        </div>

        @if($points->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center shadow-subtle max-w-xl mx-auto">
                <div class="w-16 h-16 bg-cyan-50 text-cyan-700 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-cyan-100">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-black text-brand-black">Tidak Ada Titik Pantauan Sesuai Filter</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                    Coba ubah kata kunci pencarian, pilih tingkat status lain, atau reset filter untuk melihat seluruh pos pantau.
                </p>
                <div class="mt-6">
                    <a href="{{ route('smart-city.environment') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-cyan-700 text-white text-xs font-bold hover:bg-cyan-800 transition-colors shadow-xs">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>Reset Filter</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($points as $point)
                    @php
                        $badgeBg = $point->severity === 'danger' ? 'bg-red-50 text-red-700 border-red-200' : ($point->severity === 'warning' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                        $borderLeft = $point->severity === 'danger' ? 'border-l-red-500' : ($point->severity === 'warning' ? 'border-l-amber-500' : 'border-l-emerald-500');
                        
                        $typeIcon = 'activity';
                        $typeLabel = 'Pantauan Lingkungan';
                        if ($point->info_type === 'water_level') {
                            $typeIcon = 'waves';
                            $typeLabel = 'Tinggi Muka Air';
                        } elseif ($point->info_type === 'flood_alert') {
                            $typeIcon = 'alert-triangle';
                            $typeLabel = 'Titik Rawan Banjir';
                        } elseif ($point->info_type === 'weather_alert') {
                            $typeIcon = 'cloud-lightning';
                            $typeLabel = 'Peringatan Cuaca';
                        } elseif ($point->info_type === 'landslide_prone') {
                            $typeIcon = 'mountain-snow';
                            $typeLabel = 'Rawan Longsor';
                        } elseif (in_array($point->info_type, ['infrastructure', 'road_damage'])) {
                            $typeIcon = 'wrench';
                            $typeLabel = 'Infrastruktur';
                        }
                    @endphp

                    <div class="bg-white border border-gray-200/90 border-l-4 {{ $borderLeft }} rounded-2xl p-6 shadow-subtle hover:shadow-md hover:border-gray-300 transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Header Meta -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">
                                    <i data-lucide="{{ $typeIcon }}" class="w-3 h-3 text-cyan-600"></i>
                                    <span>{{ $typeLabel }}</span>
                                </span>

                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $badgeBg }}">
                                    @if($point->severity === 'danger')
                                        <i data-lucide="alert-octagon" class="w-3 h-3 text-red-600"></i>
                                    @elseif($point->severity === 'warning')
                                        <i data-lucide="alert-triangle" class="w-3 h-3 text-amber-600"></i>
                                    @else
                                        <i data-lucide="check" class="w-3 h-3 text-emerald-600"></i>
                                    @endif
                                    <span>{{ strtoupper($point->severity) }}</span>
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-base font-black text-brand-black group-hover:text-cyan-800 transition-colors leading-snug mb-2">
                                {{ $point->title }}
                            </h3>

                            <!-- Condition Status Pill -->
                            @if($point->status_condition)
                                <div class="my-3 px-3 py-1.5 rounded-xl text-xs font-extrabold flex items-center gap-2 {{ $point->severity === 'danger' ? 'bg-red-50 text-red-800 border border-red-200' : ($point->severity === 'warning' ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'bg-cyan-50 text-cyan-900 border border-cyan-200') }}">
                                    <i data-lucide="gauge" class="w-4 h-4 shrink-0"></i>
                                    <span class="line-clamp-1">{{ $point->status_condition }}</span>
                                </div>
                            @endif

                            <!-- Description -->
                            <p class="text-xs text-gray-600 leading-relaxed mt-2 line-clamp-3">
                                {{ $point->description }}
                            </p>
                        </div>

                        <!-- Footer & Actions -->
                        <div class="mt-6 pt-4 border-t border-gray-100 space-y-3">
                            <!-- Location and Source -->
                            <div class="space-y-1.5 text-[11px] text-gray-500">
                                <div class="flex items-center gap-1.5 text-brand-black font-semibold">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-500 shrink-0"></i>
                                    <span class="truncate">{{ $point->location_name }}</span>
                                    @if($point->location_district)
                                        <span class="text-[10px] px-1.5 py-0.2 rounded bg-gray-100 text-gray-600 font-bold ml-auto shrink-0">{{ $point->location_district }}</span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center justify-between text-[11px] text-gray-400">
                                    <span class="truncate">Sumber: {{ $point->source }}</span>
                                    <span class="shrink-0">{{ $point->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Action Toolbar -->
                            <div class="flex items-center gap-2 pt-2">
                                @if($point->latitude && $point->longitude)
                                    <button type="button" 
                                            onclick="focusPointOnMap({{ $point->latitude }}, {{ $point->longitude }}, '{{ addslashes($point->title) }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-cyan-800 text-xs font-bold border border-cyan-200 transition-colors">
                                        <i data-lucide="map" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        <span>Lihat di Peta</span>
                                    </button>

                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $point->latitude }},{{ $point->longitude }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors"
                                       title="Buka di Google Maps">
                                        <i data-lucide="navigation" class="w-4 h-4 text-gray-600"></i>
                                    </a>
                                @endif

                                <button type="button" 
                                        onclick="copyPointInfo('{{ addslashes($point->title) }}', '{{ addslashes($point->location_name) }}', '{{ addslashes($point->status_condition ?: $point->severity) }}', '{{ addslashes($point->description) }}')"
                                        class="inline-flex items-center justify-center p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors"
                                        title="Salin Info">
                                    <i data-lucide="copy" class="w-4 h-4 text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 6. Bottom Emergency & Civic Preparedness Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-cyan-900 via-slate-900 to-cyan-950 p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-card border border-cyan-500/20">
        <div class="space-y-1.5 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-800/80 text-cyan-300 text-xs font-bold mb-1">
                <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                <span>Pusat Komando Tanggap Bencana Kukar</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-white">Butuh Bantuan Evakuasi / Penanganan Cepat?</h3>
            <p class="text-xs sm:text-sm text-cyan-100/80 max-w-xl">
                Hubungi Posko Siaga Bencana BPBD Kukar atau Pemadam Kebakaran & Penyelamatan jika terjadi banjir tinggi, tanah longsor, atau pohon tumbang yang mengganggu jalur umum.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="tel:0541663242" class="px-5 py-3 rounded-2xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs inline-flex items-center gap-2 shadow-lg transition-transform hover:scale-105">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>Call BPBD: (0541) 663242</span>
            </a>
            <a href="{{ route('smart-city.emergency') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs inline-flex items-center gap-2 transition-all">
                <i data-lucide="contact" class="w-4 h-4"></i>
                <span>Daftar Lengkap Kontak Darurat</span>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    let envMap = null;
    const markersMap = {};
    const allMarkersData = [];
    let tenggarongCenter = [-0.4350, 116.9800];
    let currentTileLayer = null;
    let allPointsGroup = [];

    // Fungsi ganti style tema peta (OSM Standar, Esri Streets, Satelit HD, Dark Gray)
    window.switchMapLayer = function(type) {
        if (!envMap || typeof L === 'undefined') return;

        const mapEl = document.getElementById('env-map');
        if (mapEl) {
            mapEl.classList.remove('env-tile-osm', 'env-tile-streets', 'env-tile-dark');
        }

        if (currentTileLayer) {
            envMap.removeLayer(currentTileLayer);
        }

        if (type === 'satellite') {
            currentTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri Satellite Imagery • Habar Etam'
            });
        } else if (type === 'streets') {
            if (mapEl) mapEl.classList.add('env-tile-streets');
            currentTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri Street Map • Habar Etam'
            });
        } else if (type === 'dark') {
            if (mapEl) mapEl.classList.add('env-tile-dark');
            currentTileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 16,
                attribution: '© Esri Dark Gray • Habar Etam'
            });
        } else {
            type = 'osm';
            if (mapEl) mapEl.classList.add('env-tile-osm');
            currentTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors • Habar Etam'
            });
        }

        currentTileLayer.addTo(envMap);

        // Update button states
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
    window.zoomInMap = function() {
        if (envMap) envMap.zoomIn();
    };
    window.zoomOutMap = function() {
        if (envMap) envMap.zoomOut();
    };

    // Reset ke Pusat Tenggarong
    window.resetTenggarongView = function() {
        if (!envMap || typeof L === 'undefined') return;
        envMap.flyTo(tenggarongCenter, 13.5, {
            duration: 1.2,
            easeLinearity: 0.25
        });
    };

    // Tampilkan Seluruh Titik Kukar
    window.fitAllKukarPoints = function() {
        if (!envMap || allPointsGroup.length === 0) return;
        envMap.flyToBounds(allPointsGroup, {
            padding: [50, 50],
            duration: 1.4,
            maxZoom: 14
        });
    };

    // Filter Marker Kategori Langsung pada Peta
    window.filterMapCategory = function(cat) {
        if (!envMap) return;

        let visibleCount = 0;

        allMarkersData.forEach(item => {
            const isMatch = (cat === 'all') ||
                (cat === 'water_level' && item.data.info_type === 'water_level') ||
                (cat === 'alert' && ['flood_alert', 'weather_alert', 'landslide_prone'].includes(item.data.info_type)) ||
                (cat === 'infrastructure' && ['infrastructure', 'road_damage'].includes(item.data.info_type));

            if (isMatch) {
                if (!envMap.hasLayer(item.marker)) {
                    item.marker.addTo(envMap);
                }
                visibleCount++;
            } else {
                if (envMap.hasLayer(item.marker)) {
                    envMap.removeLayer(item.marker);
                }
            }
        });

        // Update Counter
        const countEl = document.getElementById('map-active-count');
        if (countEl) countEl.innerText = `${visibleCount} Titik Ditampilkan`;

        // Update Button Active State
        ['all', 'water_level', 'alert', 'infrastructure'].forEach(id => {
            const btn = document.getElementById(`map-cat-${id}`);
            if (btn) {
                if (id === cat) {
                    btn.className = 'px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all bg-cyan-800 text-white shadow-xs';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all text-slate-700 hover:bg-slate-100 flex items-center gap-1';
                }
            }
        });
    };

    function initEnvironmentMap() {
        const mapEl = document.getElementById('env-map');
        if (!mapEl || typeof L === 'undefined') return;

        envMap = L.map('env-map', {
            center: tenggarongCenter,
            zoom: 13.5,
            minZoom: 8,
            maxZoom: 19,
            zoomControl: false, // Custom sleek controls used instead
            scrollWheelZoom: true,
            smoothWheelZoom: true
        });

        // Default: Modern OSM
        switchMapLayer('osm');

        const points = @json($allPoints);
        allPointsGroup = [];

        points.forEach(p => {
            if (p.latitude && p.longitude) {
                const lat = parseFloat(p.latitude);
                const lng = parseFloat(p.longitude);

                // Styling themes
                let themeColor = '#0284c7'; // Cyan / Water
                let lightBg = '#e0f2fe';
                let textColor = '#0369a1';
                let iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>`;
                let badgeLabel = 'TMA MAHAKAM';

                if (p.severity === 'danger') {
                    themeColor = '#e11d48'; // Rose/Red
                    lightBg = '#ffe4e6';
                    textColor = '#9f1239';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;
                    badgeLabel = 'BAHAYA / KRITIS';
                } else if (p.severity === 'warning') {
                    themeColor = '#f59e0b'; // Amber
                    lightBg = '#fef3c7';
                    textColor = '#92400e';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;
                    badgeLabel = 'WASPADA / SIAGA';
                } else if (p.info_type === 'infrastructure' || p.info_type === 'road_damage') {
                    themeColor = '#10b981'; // Emerald
                    lightBg = '#d1fae5';
                    textColor = '#065f46';
                    iconSvg = `<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>`;
                    badgeLabel = 'INFRASTRUKTUR';
                }

                // Custom Futuristic Telemetry Radar Pin
                const customMarkerHtml = `
                    <div class="relative w-10 h-10 flex items-center justify-center env-custom-marker" style="cursor:pointer;">
                        <!-- Radar Animated Ripple Ring -->
                        <div class="radar-pulse-effect absolute w-10 h-10 rounded-full" style="background:${themeColor};"></div>
                        
                        <!-- Core Pin Badge -->
                        <div class="relative w-8 h-8 rounded-2xl flex items-center justify-center text-white shadow-xl transition-transform" 
                             style="background:linear-gradient(135deg, ${themeColor}, ${themeColor}dd); border: 2.5px solid #ffffff; box-shadow: 0 8px 16px -2px ${themeColor}66;">
                            ${iconSvg}
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: customMarkerHtml,
                    className: 'env-marker-wrapper',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -22]
                });

                // Glassmorphism Telemetry Popup Box
                const popupContent = `
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; width:280px; overflow:hidden;">
                        <!-- Header Accent Color Line -->
                        <div style="height:4px; width:100%; background:${themeColor};"></div>

                        <div style="padding:14px 16px 14px 16px;">
                            <!-- Meta Badges -->
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                                <span style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:2.5px 8px; border-radius:999px; background:${lightBg}; color:${textColor};">
                                    ${badgeLabel}
                                </span>
                                <span style="font-size:10px; font-weight:600; color:#94a3b8;">
                                    ${p.location_district || 'Tenggarong'}
                                </span>
                            </div>

                            <!-- Title -->
                            <h4 style="font-size:13px; font-weight:800; color:#0f172a; margin:0 0 6px 0; line-height:1.35;">
                                ${p.title}
                            </h4>

                            <!-- Location Info -->
                            <div style="display:flex; align-items:flex-start; gap:6px; font-size:11px; color:#64748b; margin-bottom:8px;">
                                <svg style="width:13px; height:13px; color:#64748b; flex-shrink:0; margin-top:1px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>${p.location_name || 'Kutai Kartanegara'}</span>
                            </div>

                            <!-- Telemetry / Status Pill -->
                            ${p.status_condition ? `
                                <div style="font-size:11px; font-weight:800; color:${textColor}; background:${lightBg}; border:1px solid ${themeColor}33; padding:6px 10px; border-radius:10px; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:${themeColor};"></span>
                                    <span>${p.status_condition}</span>
                                </div>
                            ` : ''}

                            <!-- Description Snippet -->
                            ${p.description ? `
                                <p style="font-size:11px; color:#475569; line-height:1.45; margin:0 0 10px 0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    ${p.description}
                                </p>
                            ` : ''}

                            <!-- Footer Actions -->
                            <div style="padding-top:8px; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; gap:6px;">
                                <span style="font-size:9.5px; color:#94a3b8; max-width:110px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    ${p.source || 'Habar Etam'}
                                </span>

                                <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" rel="noopener noreferrer" 
                                   style="font-size:10.5px; font-weight:800; color:#ffffff; background:${themeColor}; padding:4px 10px; border-radius:8px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:opacity 0.2s;">
                                    <span>Rute Maps</span>
                                    <svg style="width:11px; height:11px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                const marker = L.marker([lat, lng], { icon: customIcon })
                    .addTo(envMap)
                    .bindPopup(popupContent, { maxWidth: 320 });

                markersMap[`${lat}_${lng}`] = marker;
                allMarkersData.push({ marker: marker, data: p, coords: [lat, lng] });
                allPointsGroup.push([lat, lng]);
            }
        });

        // Set Default Center View: Tenggarong
        envMap.setView(tenggarongCenter, 13.5);

        if (window.lucide) {
            window.lucide.createIcons();
        }

        setTimeout(() => {
            if (envMap) {
                envMap.invalidateSize();
            }
        }, 250);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEnvironmentMap);
    } else {
        initEnvironmentMap();
    }

    // Function to pan and focus on a specific map point from list
    window.focusPointOnMap = function(lat, lng, title) {
        const mapSection = document.getElementById('map-section');
        if (mapSection) {
            mapSection.scrollIntoView({ behavior: 'smooth' });
        }

        if (!envMap) return;

        setTimeout(() => {
            envMap.flyTo([lat, lng], 15.5, {
                duration: 1.2,
                easeLinearity: 0.25
            });

            const key = `${lat}_${lng}`;
            if (markersMap[key]) {
                setTimeout(() => {
                    markersMap[key].openPopup();
                }, 600);
            }
        }, 350);
    };

    // Copy Point Information with SweetAlert2 Toast
    window.copyPointInfo = function(title, location, status, desc) {
        const textToCopy = `[PANTUAN LINGKUNGAN KUKAR]\nJudul: ${title}\nLokasi: ${location}\nStatus: ${status}\nKeterangan: ${desc}\n\nSumber: Habar Etam Smart City`;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                if (window.showToast) {
                    window.showToast('success', 'Info Lingkungan Disalin', 'Rincian titik pantauan berhasil disalin ke papan klip.');
                } else if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Info Lingkungan Disalin',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            }).catch(() => {
                fallbackCopy(textToCopy);
            });
        } else {
            fallbackCopy(textToCopy);
        }
    };

    function fallbackCopy(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            if (window.showToast) {
                window.showToast('success', 'Info Lingkungan Disalin', 'Rincian titik pantauan berhasil disalin.');
            }
        } catch (err) {
            console.error('Failed to copy', err);
        }
        document.body.removeChild(textArea);
    }
</script>
@endpush
