@extends('layouts.public')

@section('title', 'Kontak Darurat 24 Jam — Habar Etam Tenggarong & Kukar')
@section('meta_description', 'Pusat panggilan darurat terpadu 24 jam Kutai Kartanegara. Nomor penting IGD RSUD AM Parikesit, Pemadam Kebakaran, Polres Kukar 110, BPBD, PLN, dan PDAM.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- High Impact Emergency Command Center Hero Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-red-950 to-stone-950 text-white rounded-3xl p-6 sm:p-10 mb-8 shadow-2xl border border-red-500/30 relative overflow-hidden reveal-blur-spring">
        <!-- Subtle Glow & Alert Ambient Orbs -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl pointer-events-none animate-ambient-glow"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-500/20 border border-rose-400/40 text-rose-300 text-xs font-bold uppercase tracking-wider mb-3.5 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <span>Pusat Siaga Tanggap Darurat • 24 Jam Terintegrasi</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-md">
                    Kontak & Panggilan Darurat <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-rose-300 to-yellow-200">Kukar</span>
                </h1>
                <p class="text-xs sm:text-sm text-red-100/90 mt-2.5 leading-relaxed font-normal max-w-xl">
                    Akses cepat sambungan telepon dan WhatsApp untuk situasi kritis: gawat darurat medis, pemadam kebakaran, kepolisian, evakuasi bencana BPBD, hingga gangguan listrik dan air bersih.
                </p>

                <!-- Status Pill Indicator -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 mt-5 pt-1 text-xs">
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-red-100 flex items-center gap-2 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span><strong>{{ $totalContacts ?? $contacts->count() }}</strong> Posko Layanan Siaga</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-red-100 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Cakupan 18 Kecamatan Kukar</span>
                    </span>
                </div>
            </div>

            <!-- Quick Hotline Dial Strip Box -->
            <div class="bg-black/60 backdrop-blur-md border border-red-500/40 rounded-3xl p-5 text-xs text-white max-w-sm w-full shadow-2xl shrink-0">
                <div class="flex items-center justify-between border-b border-red-800/60 pb-3 mb-3">
                    <span class="font-black text-xs uppercase tracking-wider text-amber-300 flex items-center gap-2">
                        <i data-lucide="phone-forwarded" class="w-4 h-4 text-amber-400"></i>
                        <span>Hotline Vital 1-Klik</span>
                    </span>
                    <span class="text-[10px] bg-rose-600/30 text-rose-300 px-2 py-0.5 rounded-full border border-rose-500/30 font-bold">Bebas Pulsa / Cepat</span>
                </div>
                <div class="space-y-2">
                    <a href="tel:110" class="flex items-center justify-between p-2 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-colors group">
                        <span class="text-gray-300 font-medium flex items-center gap-2">
                            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-blue-400"></i>
                            <span>Polres Kukar</span>
                        </span>
                        <strong class="font-mono font-black text-amber-300 text-sm group-hover:scale-105 transition-transform">110</strong>
                    </a>
                    <a href="tel:0541661113" class="flex items-center justify-between p-2 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-colors group">
                        <span class="text-gray-300 font-medium flex items-center gap-2">
                            <i data-lucide="flame" class="w-3.5 h-3.5 text-rose-400"></i>
                            <span>Damkar Kukar</span>
                        </span>
                        <strong class="font-mono font-black text-amber-300 text-xs group-hover:scale-105 transition-transform">(0541) 661113</strong>
                    </a>
                    <a href="tel:0541661013" class="flex items-center justify-between p-2 rounded-xl bg-white/5 hover:bg-white/15 border border-white/10 transition-colors group">
                        <span class="text-gray-300 font-medium flex items-center gap-2">
                            <i data-lucide="heart-pulse" class="w-3.5 h-3.5 text-emerald-400"></i>
                            <span>IGD RSUD Parikesit</span>
                        </span>
                        <strong class="font-mono font-black text-amber-300 text-xs group-hover:scale-105 transition-transform">(0541) 661013</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Discovery Scroll Bar -->
    @php
        $categories = [
            ['name' => 'Semua Layanan', 'value' => '', 'icon' => 'layout-grid', 'countKey' => 'all'],
            ['name' => 'Medis & Ambulans', 'value' => 'medis', 'icon' => 'heart-pulse', 'countKey' => 'medis'],
            ['name' => 'Pemadam Kebakaran', 'value' => 'damkar', 'icon' => 'flame', 'countKey' => 'damkar'],
            ['name' => 'Kepolisian (110)', 'value' => 'polisi', 'icon' => 'shield-alert', 'countKey' => 'polisi'],
            ['name' => 'SAR & BPBD', 'value' => 'sar_bpbd', 'icon' => 'life-buoy', 'countKey' => 'sar_bpbd'],
            ['name' => 'Utilitas (PLN & PDAM)', 'value' => 'utilitas', 'icon' => 'zap', 'countKey' => 'utilitas'],
        ];
        $currentCat = request('category', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Arrow -->
            <button type="button" id="emgCatScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-rose-50 hover:border-rose-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Pills Container -->
            <div id="emgCatScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($currentCat === $cat['value']) || (empty($currentCat) && empty($cat['value']));
                        $queryParam = request()->except(['category']);
                        if (!empty($cat['value'])) {
                            $queryParam['category'] = $cat['value'];
                        }
                        $catUrl = route('smart-city.emergency', $queryParam);
                        $count = $categoryCounts[$cat['countKey']] ?? 0;
                    @endphp
                    <a href="{{ $catUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-red-950 to-stone-900 text-rose-300 border-red-900 shadow-md ring-2 ring-rose-500/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-rose-400/70 hover:bg-rose-50/60 hover:text-rose-950 shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-rose-500/20 text-rose-300' : 'bg-gray-100 group-hover/item:bg-rose-100 text-gray-600 group-hover/item:text-rose-900' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $cat['name'] }}</span>
                        @if($count > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isActive ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-gray-100 text-gray-500' }}">
                                {{ $count }}
                            </span>
                        @endif
                        @if($isActive && !empty($cat['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Arrow -->
            <button type="button" id="emgCatScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-rose-50 hover:border-rose-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="relative z-30 bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 mb-8 shadow-subtle reveal-blur-spring">
        <form method="GET" action="{{ route('smart-city.emergency') }}" class="flex flex-col sm:flex-row items-center gap-3 text-xs">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Ketik nama layanan, instansi, atau nomor (RSUD, Damkar, 110, BPBD, PLN, PDAM, Ambulans)..." 
                       class="form-input pl-10 pr-8 text-xs py-2.5 rounded-xl border-gray-200 focus:border-rose-500 focus:ring-rose-500/20 w-full">
                @if(request('q'))
                    <a href="{{ route('smart-city.emergency', request()->except('q')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-black py-2.5 px-6 text-xs font-bold rounded-xl flex-1 sm:flex-initial flex items-center justify-center gap-1.5 shadow-sm hover:bg-gray-800 transition-all">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Cari Layanan</span>
                </button>
                @if(request()->anyFilled(['q', 'category']))
                    <a href="{{ route('smart-city.emergency') }}" class="btn-outline py-2.5 px-3.5 text-xs rounded-xl border-gray-200 hover:bg-gray-100 transition-colors" title="Reset Pencarian">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-gray-600"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Section Heading -->
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg sm:text-xl font-black text-brand-black tracking-tight flex items-center gap-2">
                <span>Daftar Layanan Kontak Darurat</span>
                @if(request('category'))
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800">
                        {{ request('category') }}
                    </span>
                @endif
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Menampilkan {{ $contacts->count() }} posko dan saluran siaga aktif di wilayah Kutai Kartanegara.</p>
        </div>
    </div>

    <!-- Emergency Contacts Grid -->
    @if($contacts->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200/80 p-12 text-center shadow-subtle my-6 reveal-blur-spring">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 mx-auto mb-4">
                <i data-lucide="phone-off" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-brand-black">Tidak Ada Kontak Darurat yang Cocok</h3>
            <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                Kata kunci pencarian tidak ditemukan. Silakan reset filter pencarian untuk melihat seluruh daftar nomor darurat.
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('smart-city.emergency') }}" class="btn-outline text-xs py-2 px-5 rounded-xl font-bold">
                    Lihat Semua Kontak Darurat
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($contacts as $item)
                @php
                    $categoryTheme = match ($item->category) {
                        'rumah_sakit', 'ambulans', 'puskesmas' => [
                            'badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                            'cover' => 'from-emerald-700 via-teal-900 to-slate-950',
                            'icon' => 'heart-pulse',
                            'border' => 'hover:border-emerald-400',
                        ],
                        'damkar' => [
                            'badge' => 'bg-rose-100 text-rose-900 border-rose-300',
                            'cover' => 'from-rose-700 via-red-900 to-slate-950',
                            'icon' => 'flame',
                            'border' => 'hover:border-rose-400',
                        ],
                        'polisi' => [
                            'badge' => 'bg-blue-100 text-blue-900 border-blue-300',
                            'cover' => 'from-blue-700 via-indigo-900 to-slate-950',
                            'icon' => 'shield-alert',
                            'border' => 'hover:border-blue-400',
                        ],
                        'sar_bpbd', 'posko_bencana' => [
                            'badge' => 'bg-amber-100 text-amber-900 border-amber-300',
                            'cover' => 'from-amber-700 via-orange-900 to-slate-950',
                            'icon' => 'life-buoy',
                            'border' => 'hover:border-amber-400',
                        ],
                        'pdam' => [
                            'badge' => 'bg-cyan-100 text-cyan-900 border-cyan-300',
                            'cover' => 'from-cyan-700 via-sky-900 to-slate-950',
                            'icon' => 'droplet',
                            'border' => 'hover:border-cyan-400',
                        ],
                        'pln' => [
                            'badge' => 'bg-yellow-100 text-yellow-900 border-yellow-300',
                            'cover' => 'from-yellow-600 via-amber-900 to-slate-950',
                            'icon' => 'zap',
                            'border' => 'hover:border-yellow-400',
                        ],
                        default => [
                            'badge' => 'bg-rose-100 text-rose-900 border-rose-300',
                            'cover' => 'from-rose-700 via-red-900 to-slate-950',
                            'icon' => 'phone-call',
                            'border' => 'hover:border-rose-400',
                        ],
                    };
                @endphp

                <div class="modern-hover-card bg-white rounded-3xl border border-gray-200/90 shadow-subtle hover:shadow-xl {{ $categoryTheme['border'] }} flex flex-col justify-between overflow-hidden group transition-all duration-300">
                    
                    <div>
                        <!-- Facility Photo / Procedural Cover -->
                        <div class="relative h-44 w-full bg-gradient-to-br {{ $categoryTheme['cover'] }} overflow-hidden">
                            @if($item->image_url && !str_contains($item->image_url, 'rsud.jpg'))
                                <img src="{{ $item->image_url }}" 
                                     alt="{{ $item->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 brightness-90">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/20"></div>
                            @else
                                <!-- Procedural Motif Pattern -->
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                <div class="absolute right-4 bottom-2 text-white/10 group-hover:text-white/20 transition-colors pointer-events-none">
                                    <i data-lucide="{{ $categoryTheme['icon'] }}" class="w-24 h-24 stroke-[1]"></i>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>
                            @endif

                            <!-- Top Badges Overlay -->
                            <div class="absolute top-3 inset-x-3 flex items-center justify-between gap-2 z-10">
                                <!-- 24H Live Pulse Pill -->
                                <div class="px-2.5 py-1 rounded-xl bg-rose-600/90 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-wider shadow-lg flex items-center gap-1.5 border border-rose-400/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                    <span>Siaga 24 Jam</span>
                                </div>

                                <!-- District Pill -->
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-xl shadow-md border backdrop-blur-md bg-black/60 text-amber-300 border-white/20 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    <span>{{ $item->location_district ?: 'Kukar' }}</span>
                                </span>
                            </div>

                            <!-- Bottom Service Tag -->
                            <div class="absolute bottom-3 left-3 z-10">
                                <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full shadow-md border backdrop-blur-md {{ $categoryTheme['badge'] }}">
                                    {{ strtoupper(str_replace('_', ' ', $item->category)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Content Body -->
                        <div class="p-5">
                            <h2 class="text-base font-black text-brand-black group-hover:text-rose-700 transition-colors line-clamp-2 leading-snug">
                                {{ $item->name }}
                            </h2>

                            <p class="text-xs text-gray-600 mt-2 line-clamp-2 leading-relaxed">
                                {{ $item->description }}
                            </p>

                            <!-- Address Snippet with Map Link -->
                            @if($item->address)
                                <div class="mt-4 pt-3.5 border-t border-gray-100/90 flex items-start justify-between gap-2 text-xs text-gray-600 bg-gray-50/70 p-3 rounded-2xl border border-gray-100">
                                    <div class="flex items-start gap-2 min-w-0">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500 shrink-0 mt-0.5"></i>
                                        <span class="text-[11px] leading-relaxed line-clamp-2">{{ $item->address }}</span>
                                    </div>
                                    <a href="https://maps.google.com/?q={{ urlencode($item->name . ' ' . $item->address . ' Kukar') }}" 
                                       target="_blank" 
                                       class="p-1 text-gray-400 hover:text-rose-600 shrink-0 transition-colors" 
                                       title="Buka Peta Google Maps">
                                        <i data-lucide="navigation" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Direct Emergency Actions -->
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center gap-2">
                        <!-- Big Call Button -->
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $item->phone) }}" 
                           class="btn-shimmer-effect flex-1 py-2.5 px-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-black shadow-md shadow-rose-600/25 flex items-center justify-center gap-2 transition-transform hover:scale-[1.02] active:scale-95">
                            <i data-lucide="phone-call" class="w-4 h-4 text-amber-300"></i>
                            <span class="truncate">Panggil {{ $item->phone }}</span>
                        </a>

                        <!-- WhatsApp Action -->
                        @if($item->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->whatsapp) }}?text=Halo%20{{ urlencode($item->name) }},%20saya%20memerlukan%20informasi%20bantuan%20darurat." 
                               target="_blank" 
                               class="p-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold transition-all shadow-sm hover:scale-105 active:scale-95 flex items-center justify-center shrink-0" 
                               title="Hubungi WhatsApp">
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                            </a>
                        @endif

                        <!-- Copy Number Action with Toast -->
                        <button type="button" 
                                onclick="copyEmergencyNumber('{{ $item->phone }}', '{{ addslashes($item->name) }}')" 
                                class="p-2.5 rounded-2xl border border-gray-200 bg-white hover:bg-gray-100 text-gray-600 hover:text-brand-black transition-colors shrink-0" 
                                title="Salin Nomor">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- Civic Emergency Protocol Guides -->
    <div class="mt-8 bg-gradient-to-r from-stone-900 via-gray-900 to-red-950 text-white rounded-3xl p-6 sm:p-8 border border-red-500/20 shadow-xl reveal-blur-spring">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-rose-600/30 text-rose-300 border border-rose-500/40 flex items-center justify-center shrink-0">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-white">Panduan Singkat Tanggap Darurat Warga Kukar</h3>
                <p class="text-xs text-gray-400">Langkah awal yang tepat sebelum bantuan petugas tiba di lokasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <div class="font-extrabold text-amber-300 flex items-center gap-2 mb-1.5">
                    <i data-lucide="heart-pulse" class="w-4 h-4 text-emerald-400"></i>
                    <span>Kedaruratan Medis</span>
                </div>
                <p class="text-gray-300 leading-relaxed text-[11px]">
                    Pastikan posisi korban aman. Jangan memindahkan korban trauma tulang leher/punggung tanpa keahlian medis. Segera panggil IGD RSUD Parikesit.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <div class="font-extrabold text-amber-300 flex items-center gap-2 mb-1.5">
                    <i data-lucide="flame" class="w-4 h-4 text-rose-400"></i>
                    <span>Bahaya Kebakaran</span>
                </div>
                <p class="text-gray-300 leading-relaxed text-[11px]">
                    Segera putus saklar utama listrik rumah (MCB). Evakuasi anggota keluarga ke titik terbuka dan hubungi Posko Damkar terdekat tanpa menunda.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <div class="font-extrabold text-amber-300 flex items-center gap-2 mb-1.5">
                    <i data-lucide="life-buoy" class="w-4 h-4 text-blue-400"></i>
                    <span>Banjir & Cuaca Ekstrem</span>
                </div>
                <p class="text-gray-300 leading-relaxed text-[11px]">
                    Amankan berkas penting dalam wadah kedap air. Pantau debit luapan Sungai Mahakam dan laporkan pohon tumbang/longsor ke BPBD Kukar.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Copy Emergency Number Script with SweetAlert Toast -->
<script>
function copyEmergencyNumber(number, name) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(number).then(() => {
            if (typeof window.showToast === 'function') {
                window.showToast('success', 'Nomor ' + number + ' (' + name + ') berhasil disalin!');
            } else {
                alert('Nomor ' + number + ' berhasil disalin!');
            }
        });
    } else {
        prompt('Salin nomor darurat:', number);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('emgCatScrollContainer');
    const btnLeft = document.getElementById('emgCatScrollLeft');
    const btnRight = document.getElementById('emgCatScrollRight');

    if (container && btnLeft && btnRight) {
        const updateScrollButtons = () => {
            btnLeft.disabled = container.scrollLeft <= 5;
            btnRight.disabled = (container.scrollLeft + container.clientWidth) >= (container.scrollWidth - 5);
        };

        btnLeft.addEventListener('click', () => {
            container.scrollBy({ left: -260, behavior: 'smooth' });
        });

        btnRight.addEventListener('click', () => {
            container.scrollBy({ left: 260, behavior: 'smooth' });
        });

        container.addEventListener('scroll', updateScrollButtons, { passive: true });
        window.addEventListener('resize', updateScrollButtons);
        setTimeout(updateScrollButtons, 100);

        // Drag to scroll
        let isDown = false;
        let startX, scrollLeftVal;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.classList.add('active');
            startX = e.pageX - container.offsetLeft;
            scrollLeftVal = container.scrollLeft;
        });

        container.addEventListener('mouseleave', () => { isDown = false; });
        container.addEventListener('mouseup', () => { isDown = false; });
        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.6;
            container.scrollLeft = scrollLeftVal - walk;
        });
    }

    if (window.initIcons) {
        window.initIcons();
    }
});
</script>
@endsection
