@extends('layouts.public')

@section('title', $place->name . ' — Kuliner Tenggarong (Habar Etam)')
@section('meta_description', Str::limit(strip_tags($place->description), 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-gray-500 mb-6 reveal-blur-spring">
        <a href="{{ route('home') }}" class="hover:text-brand-black transition-colors flex items-center gap-1">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <a href="{{ route('culinary.index') }}" class="hover:text-brand-black transition-colors flex items-center gap-1">
            <i data-lucide="utensils" class="w-3.5 h-3.5"></i>
            <span>Kuliner</span>
        </a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <span class="text-brand-black font-bold truncate max-w-xs sm:max-w-md">{{ $place->name }}</span>
    </nav>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Details & Map (8 Cols) -->
        <div class="lg:col-span-8 space-y-6 reveal-blur-spring">
            
            <!-- Culinary Main Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl shadow-subtle relative overflow-hidden">
                <!-- Hero Photo Banner -->
                <div class="h-64 sm:h-72 w-full relative overflow-hidden bg-slate-900">
                    <img src="{{ $place->photo_url }}" 
                         alt="{{ $place->name }}" 
                         class="w-full h-full object-cover">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>

                    <!-- Top Badges Overlay -->
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between gap-2 z-10">
                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-rose-600/90 backdrop-blur-md text-white border border-rose-400/40 uppercase tracking-wider shadow-sm">
                            {{ $place->culinary_type }}
                        </span>

                        <span class="text-xs font-black px-3 py-1.5 rounded-xl bg-amber-500 text-stone-950 shadow-sm">
                            {{ $place->price_range }}
                        </span>
                    </div>

                    <!-- Bottom Info Overlay -->
                    <div class="absolute bottom-4 left-4 right-4 flex items-end gap-3.5 z-10">
                        <div class="w-14 h-14 rounded-2xl bg-white text-amber-900 font-black text-xl flex items-center justify-center shadow-lg shrink-0 border-2 border-white">
                            {{ strtoupper(substr($place->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1 text-white">
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight leading-tight drop-shadow-md">
                                {{ $place->name }}
                            </h1>
                            <div class="text-xs sm:text-sm font-semibold text-gray-200 flex items-center gap-1.5 mt-1 drop-shadow-sm">
                                <i data-lucide="map-pin" class="w-4 h-4 text-rose-300"></i>
                                <span>{{ $place->address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <!-- Price & Hours Highlight Banner -->
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-amber-50/70 via-orange-50/50 to-gray-50 border border-amber-200/70 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="tag" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-[11px] text-amber-900 font-bold uppercase tracking-wider">Estimasi Harga / Porsi:</div>
                                <div class="text-sm sm:text-base font-black text-amber-950">
                                    {{ $place->price_range }}
                                </div>
                            </div>
                        </div>

                        <div class="sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-amber-200/60">
                            <div class="text-[11px] text-amber-900 font-bold uppercase tracking-wider flex sm:justify-end items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-700"></i>
                                <span>Jam Operasional:</span>
                            </div>
                            <div class="text-sm font-black text-amber-950 mt-0.5">
                                {{ $place->operating_hours ?: 'Setiap Hari (Buka Pagi - Malam)' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description & Signature Menus -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle">
                <div class="flex items-center gap-2 text-brand-black font-extrabold text-base mb-4 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </div>
                    <span>Menu Andalan & Uraian Rasa</span>
                </div>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line space-y-2">
                    {{ $place->description }}
                </div>
            </div>

            <!-- Interactive Map Section -->
            @if($place->latitude && $place->longitude)
                <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle">
                    <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2 text-brand-black font-extrabold text-base">
                            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                <i data-lucide="map" class="w-4 h-4"></i>
                            </div>
                            <span>Peta Lokasi Tempat Makan</span>
                        </div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $place->latitude }},{{ $place->longitude }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl transition-colors">
                            <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                            <span>Petunjuk Arah Maps</span>
                        </a>
                    </div>

                    <div id="culinary-map" class="h-72 w-full rounded-2xl border border-gray-200 shadow-inner z-10"></div>
                    <div class="flex items-center justify-between text-[11px] text-gray-500 mt-3">
                        <span>Koordinat: {{ $place->latitude }}, {{ $place->longitude }}</span>
                        <span>{{ $place->address }}</span>
                    </div>
                </div>
            @endif

            <!-- Related Culinary Places -->
            @if(isset($relatedPlaces) && $relatedPlaces->isNotEmpty())
                <div class="pt-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-brand-black flex items-center gap-2">
                            <i data-lucide="flame" class="w-5 h-5 text-amber-500"></i>
                            <span>Rekomendasi Kuliner Lainnya di Tenggarong</span>
                        </h2>
                        <a href="{{ route('culinary.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800 flex items-center gap-1">
                            <span>Lihat Semua</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($relatedPlaces as $rel)
                            <a href="{{ route('culinary.show', $rel->slug) }}" class="rounded-2xl bg-white border border-gray-200/90 hover:border-amber-500/70 shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between overflow-hidden">
                                <div class="h-28 w-full relative overflow-hidden bg-slate-900">
                                    <img src="{{ $rel->photo_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <span class="absolute top-2 left-2 text-[9px] font-bold px-2 py-0.5 rounded-full bg-rose-600/90 text-white uppercase">
                                        {{ $rel->culinary_type }}
                                    </span>
                                </div>
                                <div class="p-3.5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-xs font-black text-brand-black group-hover:text-amber-700 mt-1 line-clamp-2 transition-colors">
                                            {{ $rel->name }}
                                        </h4>
                                        <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $rel->description }}</p>
                                    </div>
                                    <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400">
                                        <span class="truncate font-semibold text-amber-800">{{ $rel->price_range }}</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400 group-hover:text-amber-700 group-hover:translate-x-0.5 transition-transform"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right: Location & Actions (4 Cols Sticky) -->
        <div class="lg:col-span-4 space-y-6 sticky top-24 reveal-blur-spring">
            
            <!-- Reservation & Contact Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 shadow-subtle relative overflow-hidden">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i data-lucide="phone-call" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-sm font-black text-brand-black uppercase tracking-wider">Reservasi & Kontak</h3>
                </div>

                <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                    Ingin memesan meja, katering, atau mengecek ketersediaan menu favorit? Hubungi langsung pihak pengelola.
                </p>

                <div class="space-y-2.5">
                    @if($place->phone_whatsapp)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $place->phone_whatsapp);
                            if (str_starts_with($cleanPhone, '08')) {
                                $cleanPhone = '628' . substr($cleanPhone, 2);
                            }
                        @endphp

                        <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($place->name) }},%20saya%20melihat%20rekomendasi%20kuliner%20Anda%20di%20portal%20*Habar%20Etam*.%20Bisa%20info%20ketersediaan%20menu%20dan%20reservasi%20tempat%3F" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-2xl font-black text-xs shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition-all hover:scale-[1.01]">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Hubungi / Pesan via WhatsApp</span>
                        </a>

                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $place->phone_whatsapp) }}" 
                           class="w-full py-3 px-4 rounded-2xl border border-gray-200 hover:border-gray-300 bg-gray-50 hover:bg-white text-gray-800 text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-2xs">
                            <i data-lucide="phone" class="w-4 h-4 text-emerald-600"></i>
                            <span>Telepon: {{ $place->phone_whatsapp }}</span>
                        </a>
                    @else
                        <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-200 text-center text-xs text-gray-500">
                            Silakan kunjungi langsung lokasi tempat makan sesuai alamat yang tertera.
                        </div>
                    @endif
                </div>

                <!-- Location Metadata -->
                <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 shrink-0">Alamat:</span>
                        <span class="font-bold text-gray-800 text-right">{{ $place->address }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Kecamatan:</span>
                        <span class="font-bold text-gray-800">{{ $place->location_district }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Direkomendasikan:</span>
                        <span class="font-bold text-gray-800">{{ $place->user->name }}</span>
                    </div>
                </div>

                <!-- Owner / Admin Controls -->
                @auth
                    @if(auth()->id() === $place->user_id || auth()->user()->isAdmin())
                        <div class="mt-5 pt-4 border-t border-gray-200">
                            <div class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                                <i data-lucide="settings-2" class="w-3.5 h-3.5 text-gray-500"></i>
                                <span>Kelola Rekomendasi Kuliner:</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('culinary.edit', $place->id) }}" class="btn-outline flex-1 text-xs py-2 px-3 rounded-xl font-bold flex items-center justify-center gap-1.5">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('culinary.destroy', $place->id) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekomendasi kuliner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger w-full text-xs py-2 px-3 rounded-xl font-bold flex items-center justify-center gap-1.5">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Share Culinary Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-5 shadow-subtle flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-gray-700 font-bold">
                    <i data-lucide="share-2" class="w-4 h-4 text-amber-600"></i>
                    <span>Bagikan Kuliner Ini</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="https://wa.me/?text={{ urlencode('Rekomendasi Kuliner Enak ' . $place->name . ' (' . $place->culinary_type . ' - Tenggarong): ' . url()->current()) }}" 
                       target="_blank" 
                       class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors flex items-center justify-center shadow-2xs" 
                       title="Bagikan ke WhatsApp">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                    </a>
                    <button type="button" 
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan kuliner berhasil disalin!');" 
                            class="w-8 h-8 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors flex items-center justify-center shadow-2xs" 
                            title="Salin Tautan">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@if($place->latitude && $place->longitude)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof L !== 'undefined') {
                const map = L.map('culinary-map', {
                    scrollWheelZoom: false
                }).setView([{{ $place->latitude }}, {{ $place->longitude }}], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                L.marker([{{ $place->latitude }}, {{ $place->longitude }}])
                    .addTo(map)
                    .bindPopup('<strong>{{ addslashes($place->name) }}</strong><br>{{ addslashes($place->address) }}')
                    .openPopup();
            }
        });
    </script>
    @endpush
@endif
