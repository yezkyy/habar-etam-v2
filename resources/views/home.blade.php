@extends('layouts.public')

@section('title', 'Habar Etam — Pusat Informasi & Kontribusi Warga Tenggarong - Kutai Kartanegara')
@section('meta_description', 'Portal informasi dan layanan warga Tenggarong & Kukar. Bursa kerja lokal, direktori UMKM, kuliner khas, agenda event, jual cepat kilat, dan pengaduan Lapor Etam.')

@section('content')


<!-- Enhanced Cinematic Editorial Hero Section with Full Background & Bottom White Gradient -->
<section class="relative pt-32 sm:pt-36 lg:pt-40 pb-36 sm:pb-40 lg:pb-44 overflow-hidden bg-brand-black">
    <!-- Background Image & Seamless Gradient Overlays -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="{{ asset('assets/hero-kukar.webp') }}" alt="Kutai Kartanegara Tenggarong" class="w-full h-full object-cover object-center brightness-[0.60] contrast-[1.1] scale-105 transform animate-hero-bg">

        <!-- Top & Center Dark Scrim (ensures floating navbar & hero text stay 100% readable) -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/85 via-black/55 to-black/30"></div>

        <!-- Ambient Kinetic Glow Layer behind Headline -->
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] sm:w-[750px] h-[350px] bg-gradient-to-tr from-amber-500/20 via-brand-gold/15 to-rose-500/10 rounded-full blur-[110px] pointer-events-none animate-ambient-glow"></div>

        <!-- Bottom Smooth Gradient to Solid White (strictly below action buttons) -->
        <div class="absolute inset-x-0 bottom-0 h-44 sm:h-52 lg:h-60 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <!-- Official Civic Trust Badge (Animated with live radar pulse) -->
        <div class="hero-animate-1 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/70 backdrop-blur-md border border-white/20 text-white text-xs font-bold shadow-xl mb-6 hover:border-brand-gold/60 transition-all hover:scale-105 cursor-default group">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="tracking-wide text-gray-200">Pusat Informasi & Layanan Terpadu Warga</span>
            <span class="text-brand-gold font-extrabold uppercase tracking-wider text-[11px] bg-brand-gold/10 px-2 py-0.5 rounded-full border border-brand-gold/30">Tenggarong</span>
        </div>

        <!-- Hero Headline with Gold Gradient Accents & Animated Gradient Flow -->
        <h1 class="hero-animate-2 text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.15] drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]">
            Kabar, Usaha & Suara Warga <br class="hidden sm:inline">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-brand-gold via-yellow-200 to-amber-400 bg-[length:200%_auto] animate-gradient-text drop-shadow">Kutai Kartanegara</span>.
        </h1>

        <!-- Subtitle / Descriptive Copy with strong contrast -->
        <p class="hero-animate-3 mt-5 text-sm sm:text-base lg:text-lg text-white/95 leading-relaxed max-w-3xl mx-auto drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)] font-semibold">
            Temukan lowongan kerja lokal, kuliner khas Mahakam, direktori UMKM, agenda acara budaya, jual beli kilat antar warga, dan salurkan pengaduan fasilitas publik melalui <strong class="text-white font-black underline decoration-brand-gold decoration-2">Lapor Etam</strong>.
        </p>

        <!-- Action Buttons with Staggered Entrance & Micro-interactions -->
        <div class="hero-animate-4 flex flex-wrap items-center justify-center gap-3.5 pt-8">
            <a href="{{ route('reports.create') }}" class="btn-shimmer-effect animate-pulse-ring px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-black shadow-lg shadow-rose-600/40 hover:shadow-rose-600/60 hover:-translate-y-1 transition-all inline-flex items-center gap-2 group active:scale-95">
                <i data-lucide="shield-alert" class="w-4 h-4 text-rose-200 group-hover:rotate-12 transition-transform duration-300"></i>
                <span>Kirim Pengaduan Warga</span>
            </a>
            <a href="{{ route('quick-sales.create') }}" class="btn-shimmer-effect px-5 py-3 rounded-2xl bg-brand-gold hover:bg-brand-gold-dark text-black text-xs sm:text-sm font-black shadow-lg shadow-amber-500/30 hover:-translate-y-1 transition-all inline-flex items-center gap-2 group active:scale-95">
                <i data-lucide="tag" class="w-4 h-4 text-black group-hover:scale-110 transition-transform"></i>
                <span>Pasang Jual Cepat</span>
            </a>
            <a href="{{ route('smart-city.emergency') }}" class="px-4 py-3 rounded-2xl bg-black/70 hover:bg-black/90 backdrop-blur-md border border-white/30 text-white text-xs sm:text-sm font-bold shadow-md hover:border-white/60 hover:-translate-y-1 transition-all inline-flex items-center gap-2 group active:scale-95">
                <i data-lucide="phone-call" class="w-4 h-4 text-brand-gold group-hover:animate-bounce"></i>
                <span>Kontak Darurat 24 Jam</span>
            </a>
        </div>

        <!-- Live Quick Stats / Trust Indicators -->
        <div class="hero-animate-5 mt-8 flex flex-wrap items-center justify-center gap-2.5 sm:gap-4 text-[11px] sm:text-xs text-white/80 font-medium">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 hover:border-white/25 transition-all">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>18 Kecamatan Terintegrasi</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 hover:border-white/25 transition-all">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                <span>Lapor Etam Terbuka & Cepat</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/40 backdrop-blur-sm border border-white/10 hover:border-white/25 transition-all">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                <span>100% Gratis Pasang Iklan</span>
            </div>
        </div>

    </div>
</section>

<!-- Main Civic Navigation Grid (Quick Category Shortcuts - Seamlessly entering on white gradient) -->
<section class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-14 sm:-mt-16">

    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-2xl p-4 sm:p-6 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 reveal-stagger">

        <a href="{{ route('quick-sales.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-amber-50/80 transition-all text-center group border border-transparent hover:border-amber-200">
            <div class="w-11 h-11 rounded-2xl bg-amber-100 text-brand-gold-dark flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="tag" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Jual Cepat</span>
            <span class="text-[10px] text-gray-500">Jual Beli Kilat</span>
        </a>

        <a href="{{ route('jobs.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-blue-50/80 transition-all text-center group border border-transparent hover:border-blue-200">
            <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="briefcase" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Bursa Kerja</span>
            <span class="text-[10px] text-gray-500">Lowongan Lokal</span>
        </a>

        <a href="{{ route('businesses.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-emerald-50/80 transition-all text-center group border border-transparent hover:border-emerald-200">
            <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="store" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Produk & Jasa</span>
            <span class="text-[10px] text-gray-500">UMKM Kukar</span>
        </a>

        <a href="{{ route('culinary.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-rose-50/80 transition-all text-center group border border-transparent hover:border-rose-200">
            <div class="w-11 h-11 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="utensils" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Kuliner</span>
            <span class="text-[10px] text-gray-500">Makanan Khas</span>
        </a>

        <a href="{{ route('events.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-purple-50/80 transition-all text-center group border border-transparent hover:border-purple-200">
            <div class="w-11 h-11 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="calendar" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Event</span>
            <span class="text-[10px] text-gray-500">Agenda Kegiatan</span>
        </a>

        <a href="{{ route('communities.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-indigo-50/80 transition-all text-center group border border-transparent hover:border-indigo-200">
            <div class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="users-2" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Komunitas</span>
            <span class="text-[10px] text-gray-500">Klub & Hobi</span>
        </a>

        <a href="{{ route('smart-city.market-prices') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-teal-50/80 transition-all text-center group border border-transparent hover:border-teal-200">
            <div class="w-11 h-11 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-xs">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-brand-black">Harga Pasar</span>
            <span class="text-[10px] text-gray-500">Harga Pangan</span>
        </a>

        <a href="{{ route('reports.index') }}" class="modern-hover-card flex flex-col items-center p-3 rounded-2xl hover:bg-red-100/90 transition-all text-center group bg-red-50/80 border border-red-200 shadow-xs">
            <div class="w-11 h-11 rounded-2xl bg-red-600 text-white flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-sm">
                <i data-lucide="shield-alert" class="w-5 h-5"></i>
            </div>
            <span class="text-xs font-bold text-red-900">Lapor Etam</span>
            <span class="text-[10px] text-red-600 font-bold">Pengaduan</span>
        </a>

    </div>
</section>

<!-- Velocity Marquee & Civic Highlight Bar -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-2 overflow-hidden reveal-blur-spring">
    <div class="bg-[#F8F4EB] border border-[#E9DFCF] rounded-2xl py-2.5 px-4 flex items-center gap-3 shadow-xs">
        <span class="px-2.5 py-0.5 rounded-full bg-brand-gold text-black text-[10px] font-black uppercase tracking-wider shrink-0 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-black animate-ping"></span>
            Info Terkini
        </span>
        <div class="overflow-hidden relative w-full">
            <div class="velocity-marquee-track text-xs font-bold text-gray-700 gap-8">
                <span class="flex items-center gap-1.5"><i data-lucide="waves" class="w-3.5 h-3.5 text-cyan-600 shrink-0"></i> TMA Mahakam Dermaga Kumala: <strong class="text-emerald-700 font-extrabold">4.10 M (Aman / Hijau)</strong></span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5"><i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-600 shrink-0"></i> Saluran Siaga 24 Jam: <strong class="text-rose-700 font-extrabold">Damkar 113 / Call Center 112</strong></span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5"><i data-lucide="shopping-basket" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i> Pasar Tangga Arung: <strong class="text-gray-900 font-extrabold">Pantauan Sembako Stabil</strong></span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5"><i data-lucide="megaphone" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i> Lapor Etam: <strong class="text-blue-700 font-extrabold">Saluran Aspirasi Warga Kukar</strong></span>
                <span class="text-gray-300">•</span>
                <!-- Duplicate for continuous smooth loop -->
                <span class="flex items-center gap-1.5"><i data-lucide="waves" class="w-3.5 h-3.5 text-cyan-600 shrink-0"></i> TMA Mahakam Dermaga Kumala: <strong class="text-emerald-700 font-extrabold">4.10 M (Aman / Hijau)</strong></span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5"><i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-600 shrink-0"></i> Saluran Siaga 24 Jam: <strong class="text-rose-700 font-extrabold">Damkar 113 / Call Center 112</strong></span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5"><i data-lucide="shopping-basket" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i> Pasar Tangga Arung: <strong class="text-gray-900 font-extrabold">Pantauan Sembako Stabil</strong></span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5"><i data-lucide="megaphone" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i> Lapor Etam: <strong class="text-blue-700 font-extrabold">Saluran Aspirasi Warga Kukar</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- Section: Featured Jual Cepat Warga -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 reveal-blur-spring">
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-brand-gold"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Jual Beli Lokal Kilat</span>
            </div>
            <h2 class="text-2xl font-extrabold text-brand-black mt-1">Jual Cepat Warga Tenggarong</h2>
        </div>
        <a href="{{ route('quick-sales.index') }}" class="text-xs font-bold text-brand-black hover:text-brand-gold-dark flex items-center space-x-1 group">
            <span>Lihat Semua Listing</span>
            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    @if($quickSales->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-xs text-gray-500">
        Belum ada barang jual cepat yang dipublikasikan saat ini.
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 reveal-stagger">
        @foreach($quickSales as $qs)
        <div class="content-card modern-hover-card relative flex flex-col justify-between group rounded-2xl shadow-subtle hover:shadow-xl bg-white border border-gray-200 overflow-hidden cursor-pointer">
            <!-- Entire Card Clickable Link -->
            <a href="{{ route('quick-sales.show', $qs->slug) }}" class="absolute inset-0 z-0" aria-label="{{ $qs->title }}"></a>

            <div class="relative aspect-video bg-gray-100 overflow-hidden rounded-t-2xl pointer-events-none">
                @if($qs->primaryMedia())
                <img src="{{ Storage::url($qs->primaryMedia()->path) }}" alt="{{ $qs->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <i data-lucide="tag" class="w-8 h-8"></i>
                </div>
                @endif
                <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded-full bg-brand-black/80 backdrop-blur-sm text-white text-[10px] font-bold">
                    {{ $qs->category }}
                </span>
                <span class="absolute bottom-2 right-2 px-2.5 py-0.5 rounded-full bg-brand-gold text-brand-black text-[10px] font-black">
                    {{ $qs->condition }}
                </span>
            </div>

            <div class="p-4 flex-1 flex flex-col justify-between relative z-10 pointer-events-none">
                <div>
                    <div class="text-sm font-extrabold text-brand-black group-hover:text-brand-gold-dark transition-colors line-clamp-1">
                        {{ $qs->title }}
                    </div>
                    <div class="text-base font-black text-brand-gold-dark mt-1">
                        {{ $qs->formatted_price }}
                    </div>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $qs->description }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                    <span class="flex items-center space-x-1">
                        <i data-lucide="map-pin" class="w-3 h-3 text-gray-400"></i>
                        <span>{{ $qs->location_name }}</span>
                    </span>
                    <a href="https://wa.me/{{ $qs->contact_whatsapp }}?text=Halo,%20saya%20tertarik%20dengan%20listing%20Jual%20Cepat%20Habar%20Etam:%20{{ urlencode($qs->title) }}" target="_blank" class="pointer-events-auto font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center space-x-1 relative z-20">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>


<!-- Section: 2 Columns Split (Bursa Kerja & Event Lokal) -->
<section class="bg-gray-50 border-y border-gray-200 py-12 reveal-blur-spring">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Left: Lowongan Kerja Terbaru -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Karir & Peluang</span>
                        <h2 class="text-xl font-extrabold text-brand-black">Bursa Kerja Lokal</h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-blue-700 hover:underline">Semua Lowongan &rarr;</a>
                </div>

                <div class="space-y-3 reveal-stagger">
                    @forelse($jobVacancies as $job)
                    <a href="{{ route('jobs.show', $job->slug) }}" class="modern-hover-card block bg-white border border-gray-200 hover:border-blue-400 rounded-xl p-4 shadow-subtle hover:shadow-md transition-all group cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-800 border border-blue-200 uppercase">
                                    {{ $job->employment_type }}
                                </span>
                                <h3 class="text-sm font-bold text-brand-black group-hover:text-blue-700 transition-colors mt-1.5">
                                    {{ $job->title }}
                                </h3>
                                <div class="text-xs font-semibold text-gray-700 mt-0.5">{{ $job->company }}</div>
                            </div>
                            <div class="text-right text-xs font-bold text-emerald-700">
                                {{ $job->salary_range ?: 'Gaji Kompetitif' }}
                            </div>
                        </div>
                        <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                            <span><i data-lucide="map-pin" class="w-3 h-3 inline mr-1 text-gray-400"></i>{{ $job->location }}</span>
                            <span>Batas: {{ $job->deadline ? $job->deadline->format('d M Y') : 'Segera' }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-xs text-gray-500">
                        Belum ada lowongan kerja aktif saat ini.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Agenda & Event Budaya -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Kegiatan & Wisata</span>
                        <h2 class="text-xl font-extrabold text-brand-black">Agenda Event Mendatang</h2>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-purple-700 hover:underline">Semua Event &rarr;</a>
                </div>

                <div class="space-y-3 reveal-stagger">
                    @forelse($events as $event)
                    <a href="{{ route('events.show', $event->slug) }}" class="modern-hover-card block bg-white border border-gray-200 hover:border-purple-400 rounded-xl p-4 shadow-subtle hover:shadow-md transition-all group cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 rounded-xl bg-purple-50 border border-purple-200 flex flex-col items-center justify-center flex-shrink-0 text-purple-900 shadow-xs group-hover:bg-purple-100 transition-colors">
                                <span class="text-[10px] font-bold uppercase">{{ $event->start_date->format('M') }}</span>
                                <span class="text-lg font-black leading-none">{{ $event->start_date->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-800">
                                    {{ $event->category }}
                                </span>
                                <h3 class="text-sm font-bold text-brand-black group-hover:text-purple-700 transition-colors mt-1 truncate">
                                    {{ $event->title }}
                                </h3>
                                <div class="text-xs text-gray-500 mt-1 flex items-center space-x-2">
                                    <span><i data-lucide="map-pin" class="w-3 h-3 inline mr-1 text-gray-400"></i>{{ $event->location_name }}</span>
                                    <span>•</span>
                                    <span>{{ $event->start_time ?: 'WITA' }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="bg-white rounded-lg border border-gray-200 p-6 text-center text-xs text-gray-500">
                        Belum ada jadwal event mendatang.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section: Lapor Etam & Redaksi Live Broadcast -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 reveal-skew-spring" data-velocity-skew="0.08">
    <div class="bg-brand-black text-white rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-2xl border border-gray-800">

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-gray-800">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-red-600 text-white text-[10px] font-extrabold uppercase tracking-wider animate-pulse">Lapor Etam Live</span>
                    <span class="text-xs text-gray-400">Kerjasama Redaksi Studio PT Surya Citra Media (SCM)</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white mt-1.5">
                    Pengaduan Warga & Agenda Siaran Langsung
                </h2>
                <p class="text-xs text-gray-400 mt-1 max-w-xl">
                    Laporan warga yang terverifikasi diteruskan ke instansi terkait dan diliput langsung untuk percepatan penyelesaian masalah publik di Kutai Kartanegara.
                </p>
            </div>

            <a href="{{ route('reports.create') }}" class="btn-gold py-3 px-6 font-bold text-sm shadow-gold-glow flex-shrink-0 self-start lg:self-auto rounded-xl">
                <i data-lucide="shield-alert" class="w-4 h-4 mr-2 inline"></i>
                <span>Kirim Pengaduan Sekarang</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 reveal-stagger">
            @foreach($featuredReports as $rep)
            <a href="{{ route('reports.show', $rep->ticket_number) }}" class="modern-hover-card block bg-brand-black-soft border border-gray-800 rounded-2xl p-4 flex flex-col justify-between hover:border-brand-gold/40 group cursor-pointer transition-all">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-mono text-xs font-bold text-brand-gold">{{ $rep->ticket_number }}</span>
                        @if($rep->status === 'live_agenda')
                        <span class="badge-live text-[10px]">MASUK AGENDA LIVE</span>
                        @elseif($rep->status === 'resolved')
                        <span class="badge-resolved text-[10px]">SELESAI</span>
                        @else
                        <span class="badge-editorial text-[10px]">DIPROSES REDAKSI</span>
                        @endif
                    </div>
                    <h4 class="text-sm font-bold text-white group-hover:text-brand-gold transition-colors line-clamp-2">
                        {{ $rep->title }}
                    </h4>
                    <p class="text-xs text-gray-400 mt-1.5 line-clamp-2">{{ $rep->description }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-800/80 flex items-center justify-between text-[11px] text-gray-400">
                    <span class="truncate max-w-[180px]"><i data-lucide="map-pin" class="w-3 h-3 inline mr-1 text-brand-gold"></i>{{ $rep->address }}</span>
                    <span class="text-brand-gold group-hover:underline font-semibold flex items-center gap-1">
                        <span>Pantau</span>
                        <i data-lucide="arrow-right" class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>

<!-- Section: Smart City Snapshot (Pasar Tangga Arung & Emergency Dial) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 reveal-blur-spring">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left 2 Cols: Harga Pangan Pasar Tangga Arung -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-subtle">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Pangan & Komoditas</span>
                    <h3 class="text-lg font-bold text-brand-black">Pantauan Harga Pasar Tangga Arung Hari Ini</h3>
                </div>
                <a href="{{ route('smart-city.market-prices') }}" class="text-xs font-bold text-teal-700 hover:underline">Detail Histori &rarr;</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 reveal-stagger">
                @foreach($marketPrices as $price)
                <a href="{{ route('smart-city.market-prices') }}" class="modern-hover-card block p-3 bg-gray-50 border border-gray-100 rounded-xl hover:border-teal-300 hover:bg-teal-50/40 transition-colors group cursor-pointer">
                    <div class="text-[11px] font-medium text-gray-500 truncate">{{ $price->commodity_name }}</div>
                    <div class="text-sm font-extrabold text-brand-black group-hover:text-teal-800 mt-0.5 transition-colors">
                        Rp {{ number_format($price->price, 0, ',', '.') }}
                        <span class="text-[10px] font-normal text-gray-500">/ {{ $price->unit }}</span>
                    </div>
                    @if($price->price_difference > 0)
                    <div class="text-[10px] font-bold text-rose-600 flex items-center space-x-0.5 mt-0.5">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>Naik Rp {{ number_format($price->price_difference, 0, ',', '.') }}</span>
                    </div>
                    @elseif($price->price_difference < 0)
                        <div class="text-[10px] font-bold text-emerald-600 flex items-center space-x-0.5 mt-0.5">
                        <i data-lucide="trending-down" class="w-3 h-3"></i>
                        <span>Turun Rp {{ number_format(abs($price->price_difference), 0, ',', '.') }}</span>
            </div>
            @else
            <div class="text-[10px] font-medium text-gray-400 mt-0.5">Stabil</div>
            @endif
            </a>
            @endforeach
        </div>
    </div>

    <!-- Right 1 Col: Emergency Speed Dial -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-subtle flex flex-col justify-between">
        <div>
            <div class="flex items-center space-x-2 text-rose-600 mb-1">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Layanan Darurat 24 Jam</span>
            </div>
            <h3 class="text-lg font-bold text-brand-black">Kontak Darurat Tenggarong</h3>
            <p class="text-xs text-gray-500 mt-1">Simpan nomor darurat medis, pemadam kebakaran, dan kepolisian Kukar.</p>

            <div class="space-y-2.5 mt-4 reveal-stagger">
                @foreach($emergencyContacts as $contact)
                <a href="tel:{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" class="modern-hover-card flex items-center justify-between p-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs hover:border-rose-300 hover:bg-rose-50/50 transition-colors group cursor-pointer">
                    <div class="truncate mr-2">
                        <div class="font-bold text-brand-black group-hover:text-rose-700 transition-colors truncate">{{ $contact->name }}</div>
                        <div class="text-[10px] text-gray-500 font-mono">{{ $contact->phone }}</div>
                    </div>
                    <span class="btn-black group-hover:bg-rose-600 py-1 px-2.5 text-[11px] font-bold flex-shrink-0 rounded-lg transition-colors flex items-center gap-1">
                        <i data-lucide="phone" class="w-3 h-3"></i>
                        <span>Panggil</span>
                    </span>
                </a>
                @endforeach
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-gray-100">
            <a href="{{ route('smart-city.emergency') }}" class="text-xs font-bold text-brand-black hover:text-brand-gold-dark flex items-center justify-center space-x-1">
                <span>Lihat Semua Kontak Darurat</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>

    </div>
</section>


@endsection