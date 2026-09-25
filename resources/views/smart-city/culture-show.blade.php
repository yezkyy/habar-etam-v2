@extends('layouts.public')

@section('title', $destination->title . ' — Budaya & Wisata Kutai Kartanegara')
@section('meta_description', Str::limit(strip_tags($destination->description), 150))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .culture-tile-osm .leaflet-tile-pane {
        filter: contrast(104%) brightness(99%) saturate(88%) hue-rotate(-2deg);
    }
    #show-map {
        min-height: 280px;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-amber-700 transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <a href="{{ route('smart-city.culture') }}" class="hover:text-amber-700 transition-colors">Budaya & Wisata</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-amber-900 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 line-clamp-1">{{ $destination->title }}</span>
    </nav>

    <!-- Main Destination Card -->
    <div class="bg-white rounded-3xl border border-gray-200/90 shadow-card overflow-hidden mb-10">
        
        <!-- Header Banner with Background Image Ambient -->
        <div class="relative overflow-hidden bg-slate-950 text-white p-6 sm:p-10 border-b border-amber-500/20">
            @if($destination->cover_image)
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset($destination->cover_image) }}" alt="{{ $destination->title }}" class="w-full h-full object-cover opacity-25 scale-105 filter blur-xs">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/85 to-slate-950/60"></div>
                </div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-[#18130c] to-amber-950 z-0"></div>
            @endif
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-400 text-slate-950 shadow-xs">
                        <i data-lucide="crown" class="w-3.5 h-3.5"></i>
                        <span>{{ strtoupper(str_replace('_', ' ', $destination->category)) }}</span>
                    </span>

                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-amber-200 border border-white/10 backdrop-blur-md">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>{{ $destination->location_district ?: 'Kutai Kartanegara' }}</span>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight max-w-3xl">
                    {{ $destination->title }}
                </h1>
                
                <p class="text-xs sm:text-sm text-amber-100/80 mt-3 flex items-center gap-2">
                    <i data-lucide="compass" class="w-4 h-4 text-amber-400 shrink-0"></i>
                    <span>{{ $destination->address }}</span>
                </p>
            </div>
        </div>

        <!-- Content Body -->
        <div class="p-6 sm:p-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Story & Historical Context (8 Cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Featured Cover Image Showcase -->
                    @if($destination->cover_image)
                        <div class="relative w-full aspect-[16/9] rounded-2xl overflow-hidden shadow-lg border border-gray-200 bg-slate-950 group">
                            <img src="{{ asset($destination->cover_image) }}" 
                                 alt="{{ $destination->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between text-white/90 text-xs font-semibold">
                                <span class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-lg border border-white/10">
                                    Dokumentasi Warisan Budaya Kutai Kartanegara
                                </span>
                                <span class="text-[11px] text-amber-300 font-bold bg-amber-950/80 px-2.5 py-1 rounded-lg border border-amber-500/30">
                                    Dispar Kukar
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Historical Context Highlight -->
                    @if($destination->historical_context)
                        <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                            <div class="flex items-center gap-2 text-amber-900 font-extrabold text-xs uppercase tracking-wider mb-2">
                                <i data-lucide="scroll" class="w-4 h-4 text-amber-700"></i>
                                <span>Konteks Sejarah & Asal Usul</span>
                            </div>
                            <p class="text-xs sm:text-sm text-amber-950 leading-relaxed font-medium">
                                {{ $destination->historical_context }}
                            </p>
                        </div>
                    @endif

                    <!-- Detailed Description -->
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-brand-black mb-3 flex items-center gap-2">
                            <i data-lucide="book-open" class="w-5 h-5 text-amber-700"></i>
                            <span>Deskripsi & Ulasan Destinasi</span>
                        </h2>
                        <div class="text-xs sm:text-sm text-gray-700 leading-relaxed space-y-3 whitespace-pre-line font-normal">
                            {{ $destination->description }}
                        </div>
                    </div>

                    <!-- Interactive Location Map -->
                    @if($destination->latitude && $destination->longitude)
                        <div class="pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-black text-brand-black flex items-center gap-2">
                                    <i data-lucide="map" class="w-4 h-4 text-amber-700"></i>
                                    <span>Peta Lokasi & Koordinat GPS</span>
                                </h3>
                                <span class="text-xs text-gray-400 font-mono">{{ $destination->latitude }}, {{ $destination->longitude }}</span>
                            </div>

                            <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner">
                                <div id="show-map" class="w-full h-72 culture-tile-osm z-10"></div>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">Navigasi langsung rute Google Maps</span>
                                <a href="https://maps.google.com/?q={{ $destination->latitude }},{{ $destination->longitude }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs shadow-xs transition-colors">
                                    <span>Buka Rute di Google Maps</span>
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Visiting Info & Actions (4 Cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/80 space-y-5 shadow-xs">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 border-b border-slate-200 pb-3 flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4 text-amber-700"></i>
                            <span>Informasi Kunjungan</span>
                        </h3>
                        
                        <div class="space-y-4 text-xs">
                            <!-- Address -->
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-[11px] text-gray-400 block font-semibold">Alamat Lengkap</span>
                                    <span class="font-bold text-slate-800 leading-snug">{{ $destination->address }}</span>
                                </div>
                            </div>

                            <!-- Operating Info -->
                            @if($destination->operating_info)
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                                        <i data-lucide="clock" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-gray-400 block font-semibold">Jam Operasional & Tiket</span>
                                        <span class="font-bold text-slate-800 leading-snug">{{ $destination->operating_info }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- District -->
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                                    <i data-lucide="compass" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-[11px] text-gray-400 block font-semibold">Wilayah Kecamatan</span>
                                    <span class="font-bold text-slate-800">{{ $destination->location_district ?: 'Kutai Kartanegara' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="pt-4 border-t border-slate-200 space-y-2">
                            <a href="{{ route('smart-city.culture') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-700 bg-white hover:bg-gray-100 transition-colors shadow-xs">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                <span>Kembali ke Katalog Budaya</span>
                            </a>
                        </div>
                    </div>

                    <!-- Side Card: Emergency & Guide -->
                    <div class="bg-gradient-to-br from-amber-900 to-slate-900 text-white rounded-2xl p-5 border border-amber-500/20 shadow-xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-amber-300 mb-2">
                            <i data-lucide="phone-call" class="w-4 h-4 text-amber-400"></i>
                            <span>Bantuan Turis & Layanan Warga</span>
                        </div>
                        <p class="text-[11px] text-amber-100/80 leading-relaxed mb-4">
                            Butuh informasi pemandu wisata lokal atau informasi reservasi kelompok kesultanan?
                        </p>
                        <a href="{{ route('smart-city.emergency') }}" class="inline-flex items-center justify-center w-full gap-1.5 py-2 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-colors">
                            <span>Kontak Layanan Publik</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Destinations Section -->
    @if(isset($otherDestinations) && $otherDestinations->isNotEmpty())
        <div class="mt-12">
            <h3 class="text-lg font-black text-brand-black mb-6 flex items-center gap-2">
                <i data-lucide="compass" class="w-5 h-5 text-amber-700"></i>
                <span>Destinasi Cagar Budaya Lainnya di Kukar</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($otherDestinations as $other)
                    <div class="bg-white rounded-2xl border border-gray-200/90 overflow-hidden shadow-subtle hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <div class="relative w-full aspect-[16/10] bg-slate-900 overflow-hidden">
                                @if($other->cover_image)
                                    <img src="{{ asset($other->cover_image) }}" alt="{{ $other->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-900 to-amber-950 flex items-center justify-center">
                                        <i data-lucide="landmark" class="w-8 h-8 text-amber-400/40"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                <span class="absolute top-2.5 left-2.5 text-[10px] font-extrabold px-2 py-0.5 rounded-lg bg-amber-950/80 text-amber-300 border border-amber-500/30 backdrop-blur-md uppercase">
                                    {{ strtoupper(str_replace('_', ' ', $other->category)) }}
                                </span>
                            </div>

                            <div class="p-5">
                                <h4 class="text-sm font-black text-brand-black group-hover:text-amber-800 transition-colors leading-snug mb-1">
                                    <a href="{{ route('smart-city.culture.show', $other->slug) }}">
                                        {{ $other->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500 line-clamp-2 mt-1">
                                    {{ $other->description }}
                                </p>
                            </div>
                        </div>
                        <div class="px-5 pb-4 pt-2 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-gray-400 text-[11px] font-medium flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-red-500"></i>
                                <span>{{ $other->location_district }}</span>
                            </span>
                            <a href="{{ route('smart-city.culture.show', $other->slug) }}" class="font-bold text-amber-800 hover:text-amber-900 flex items-center gap-1">
                                <span>Lihat Detail</span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
@if($destination->latitude && $destination->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof L !== 'undefined') {
            const map = L.map('show-map', {
                center: [{{ $destination->latitude }}, {{ $destination->longitude }}],
                zoom: 15,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors • Habar Etam'
            }).addTo(map);

            const customMarkerHtml = `
                <div class="relative w-10 h-10 flex items-center justify-center" style="cursor:pointer;">
                    <div style="position:absolute; width:100%; height:100%; background:#d97706; opacity:0.25; border-radius:50%; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div>
                    <div style="width:30px; height:30px; background:#b45309; border-radius:50%; border:3px solid #ffffff; box-shadow:0 4px 6px -1px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:#ffffff; font-weight:bold; font-size:12px;">
                        <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>
                    </div>
                </div>
            `;

            const customIcon = L.divIcon({
                html: customMarkerHtml,
                className: 'culture-show-marker',
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });

            L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}], { icon: customIcon })
                .addTo(map)
                .bindPopup('<div style="font-family:sans-serif; padding:4px;"><strong>{{ addslashes($destination->title) }}</strong><br><span style="font-size:11px; color:#64748b;">{{ addslashes($destination->address) }}</span></div>')
                .openPopup();
        }
    });
</script>
@endif
@endpush
