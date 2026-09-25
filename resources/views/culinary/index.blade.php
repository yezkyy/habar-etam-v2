@extends('layouts.public')

@section('title', 'Kuliner Khas & Tempat Makan Tenggarong — Habar Etam')
@section('meta_description', 'Direktori kuliner lokal, makanan khas Kutai (Gence Ruan, Sambal Raja), warung makan legendaris, cafe, dan kedai kopi di Tenggarong & Kutai Kartanegara.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Culinary Hub Hero Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-brand-black to-stone-900 text-white rounded-3xl p-6 sm:p-8 mb-8 border border-amber-500/20 shadow-2xl relative overflow-hidden reveal-blur-spring">
        <!-- Ambient Warm Blur Circles -->
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-400/30 text-amber-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <i data-lucide="utensils" class="w-3.5 h-3.5 text-amber-400"></i>
                    <span>Jelajah Rasa Nusantara • Kuliner Khas Kutai Kartanegara</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Kuliner Lokal & Tempat Makan
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2 leading-relaxed">
                    Nikmati kekayaan cita rasa warisan Kutai, olahan ikan air tawar Mahakam (Gence Ruan, Patin, Baung), kedai kopi santai tepian sungai, hingga aneka kuliner kekinian warga Tenggarong.
                </p>

                <!-- Quick Stats Badges -->
                <div class="flex flex-wrap items-center gap-3 mt-4 pt-2 text-xs">
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span><strong>{{ $culinaryPlaces->total() }}</strong> Tempat Makan Terdaftar</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="flame" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Cita Rasa Khas Kutai</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Tenggarong & Tepian Mahakam</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('culinary.create') }}" class="btn-gold py-3.5 px-6 font-black text-sm shadow-gold-glow flex items-center justify-center gap-2 rounded-2xl hover:scale-[1.02] transition-transform">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Rekomendasikan Kuliner</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Culinary Type Interactive Discovery Navigation Bar -->
    @php
        $culinaryTypes = [
            ['name' => 'Semua Jenis', 'value' => '', 'icon' => 'layout-grid'],
            ['name' => 'Kuliner Tradisional Kutai', 'value' => 'Kuliner Tradisional Kutai', 'icon' => 'flame'],
            ['name' => 'Sarapan Pagi & Jajanan', 'value' => 'Sarapan Pagi & Jajanan', 'icon' => 'coffee'],
            ['name' => 'Cafe & Santai Sore', 'value' => 'Cafe & Santai Sore', 'icon' => 'cup-soda'],
            ['name' => 'Rumah Makan & Seafood', 'value' => 'Rumah Makan & Seafood', 'icon' => 'utensils'],
        ];
        $currentType = request('type', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Button -->
            <button type="button" id="culScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Category Pills -->
            <div id="culScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($culinaryTypes as $c)
                    @php
                        $isActive = ($currentType === $c['value']) || (empty($currentType) && empty($c['value']));
                        $queryParam = request()->except(['type', 'page']);
                        if (!empty($c['value'])) {
                            $queryParam['type'] = $c['value'];
                        }
                        $typeUrl = route('culinary.index', $queryParam);
                    @endphp
                    <a href="{{ $typeUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-brand-black to-stone-900 text-brand-gold border-brand-black shadow-md ring-2 ring-brand-gold/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-brand-gold/70 hover:bg-amber-50/60 hover:text-brand-black shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-brand-gold/20 text-brand-gold' : 'bg-gray-100 group-hover/item:bg-amber-100 text-gray-600 group-hover/item:text-brand-black' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $c['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $c['name'] }}</span>
                        @if($isActive && !empty($c['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Button -->
            <button type="button" id="culScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Search & Advanced Filter Toolbar -->
    <div class="bg-white/95 backdrop-blur-sm border border-gray-200/90 rounded-3xl p-5 sm:p-6 mb-6 shadow-subtle reveal-blur-spring relative z-30 overflow-visible transition-all">
        <form method="GET" action="{{ route('culinary.index') }}" id="culFilterForm" class="relative z-30">
            
            <!-- Hidden Type to preserve filter if searching -->
            @if(request()->filled('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <!-- 1. Search Input Field -->
                <div class="md:col-span-6 lg:col-span-6">
                    <label for="search-cul-input" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                        <span>Cari Tempat Makan / Menu Favorit</span>
                    </label>
                    <div class="relative group/search">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within/search:text-brand-gold-dark transition-colors">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               id="search-cul-input" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Contoh: Gence Ruan, Sambal Raja, Nasi Bekepor, Cafe Tepian..." 
                               class="form-input pl-10 pr-9 text-xs py-3 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-medium">
                        
                        @if(request()->filled('q'))
                            <a href="{{ route('culinary.index', request()->except(['q', 'page'])) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition-colors"
                               title="Hapus kata kunci">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- 2. Culinary Type Filter -->
                <div class="md:col-span-4 lg:col-span-4">
                    <label for="cul-type-select" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="utensils" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>Jenis Kuliner</span>
                    </label>
                    <div class="relative">
                        <select id="cul-type-select" 
                                name="type" 
                                onchange="document.getElementById('culFilterForm').submit()" 
                                class="form-input text-xs py-3 pl-3 pr-8 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-semibold text-gray-800 cursor-pointer">
                            <option value="">Semua Jenis Kuliner</option>
                            <option value="Kuliner Tradisional Kutai" {{ request('type') == 'Kuliner Tradisional Kutai' ? 'selected' : '' }}>Kuliner Tradisional Kutai</option>
                            <option value="Sarapan Pagi & Jajanan" {{ request('type') == 'Sarapan Pagi & Jajanan' ? 'selected' : '' }}>Sarapan Pagi & Jajanan</option>
                            <option value="Cafe & Santai Sore" {{ request('type') == 'Cafe & Santai Sore' ? 'selected' : '' }}>Cafe & Santai Sore</option>
                            <option value="Rumah Makan & Seafood" {{ request('type') == 'Rumah Makan & Seafood' ? 'selected' : '' }}>Rumah Makan & Seafood</option>
                        </select>
                    </div>
                </div>

                <!-- 3. Action Buttons -->
                <div class="md:col-span-2 lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="btn-black flex-1 py-3 px-4 text-xs font-bold rounded-2xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all hover:scale-[1.01]">
                        <i data-lucide="filter" class="w-4 h-4 text-brand-gold"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['q', 'type']))
                        <a href="{{ route('culinary.index') }}" class="btn-outline py-3 px-3 text-xs rounded-2xl text-rose-600 border-rose-200 hover:bg-rose-50 hover:border-rose-300 transition-colors shrink-0" title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Popular Culinary Search Tags -->
            <div class="mt-3.5 pt-3.5 border-t border-gray-100 flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                <span class="font-semibold text-gray-600 flex items-center gap-1 shrink-0">
                    <i data-lucide="flame" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Menu Populer:</span>
                </span>
                @php
                    $popularCulinaryKeywords = ['Gence Ruan', 'Sambal Raja', 'Sayur Asam Kutai', 'Nasi Bekepor', 'Kedai Kopi', 'Pisang Gapit', 'Ikan Patin Bakar', 'Coto Makassar'];
                @endphp
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach($popularCulinaryKeywords as $kw)
                        @php
                            $isCurrentQuery = request('q') === $kw;
                            $kwUrl = route('culinary.index', array_merge(request()->except(['page']), ['q' => $kw]));
                        @endphp
                        <a href="{{ $kwUrl }}" 
                           class="px-2.5 py-1 rounded-xl transition-all duration-150 {{ $isCurrentQuery ? 'bg-amber-400 text-black font-bold shadow-2xs' : 'bg-gray-100 hover:bg-amber-100/70 text-gray-700 hover:text-black' }}">
                            {{ $kw }}
                        </a>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    <!-- Active Filters Badge Summary Strip -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 text-xs text-gray-600">
        <div class="flex items-center flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-gray-200 text-gray-800 font-bold shadow-2xs">
                <i data-lucide="utensils" class="w-3.5 h-3.5 text-amber-600"></i>
                <span><strong>{{ $culinaryPlaces->total() }}</strong> Tempat Kuliner Ditemukan</span>
            </span>

            <!-- Removable Filter Tag: Type -->
            @if(request()->filled('type'))
                <a href="{{ route('culinary.index', request()->except(['type', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 font-bold hover:bg-amber-100 transition-colors shadow-2xs group"
                   title="Hapus filter jenis">
                    <span>Jenis: <strong>{{ request('type') }}</strong></span>
                    <i data-lucide="x" class="w-3 h-3 text-amber-700 group-hover:text-rose-600"></i>
                </a>
            @endif

            <!-- Removable Filter Tag: Query -->
            @if(request()->filled('q'))
                <a href="{{ route('culinary.index', request()->except(['q', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 font-bold hover:bg-rose-100 transition-colors shadow-2xs group"
                   title="Hapus kata kunci">
                    <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                    <i data-lucide="x" class="w-3 h-3 text-rose-700 group-hover:text-rose-600"></i>
                </a>
            @endif
        </div>

        @if(request()->anyFilled(['q', 'type']))
            <a href="{{ route('culinary.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1.5 self-start sm:self-auto shrink-0">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                <span>Reset Semua Filter</span>
            </a>
        @endif
    </div>

    <!-- Culinary Grid -->
    @if($culinaryPlaces->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200 p-12 sm:p-16 text-center shadow-subtle reveal-blur-spring">
            <div class="w-16 h-16 rounded-full bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="utensils" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-black">Belum Ada Tempat Kuliner yang Sesuai</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5 max-w-md mx-auto">
                Tidak ada data kuliner atau tempat makan yang cocok dengan kriteria pencarian Anda. Jadilah yang pertama merekomendasikannya!
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('culinary.index') }}" class="btn-outline text-xs py-2.5 px-4 rounded-xl font-bold">
                    Reset Filter
                </a>
                <a href="{{ route('culinary.create') }}" class="btn-gold text-xs py-2.5 px-5 rounded-xl font-bold">
                    Rekomendasikan Kuliner Baru
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 reveal-stagger">
            @foreach($culinaryPlaces as $place)
                <div class="content-card modern-hover-card relative flex flex-col justify-between group rounded-3xl shadow-subtle hover:shadow-2xl bg-white border border-gray-200/90 overflow-hidden cursor-pointer transition-all duration-300">
                    
                    <!-- Entire Card Clickable Link -->
                    <a href="{{ route('culinary.show', $place->slug) }}" class="absolute inset-0 z-10" aria-label="{{ $place->name }}"></a>

                    <div>
                        <!-- Top Image Media Container -->
                        <div class="h-48 w-full relative overflow-hidden bg-slate-900 shrink-0">
                            <img src="{{ $place->photo_url }}" 
                                 alt="{{ $place->name }}" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

                            <!-- Top Badges Overlay -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 z-10">
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-xl bg-rose-600/90 backdrop-blur-md text-white border border-rose-400/40 uppercase tracking-wider shadow-sm truncate max-w-[150px]">
                                    {{ $place->culinary_type }}
                                </span>

                                <span class="text-[10px] font-black px-2.5 py-1 rounded-xl bg-amber-500 text-stone-950 shadow-sm">
                                    {{ $place->price_range }}
                                </span>
                            </div>

                            <!-- Bottom Image Overlay: Place Initial & Name -->
                            <div class="absolute bottom-3 left-3 right-3 flex items-center gap-2.5 z-10">
                                <div class="w-9 h-9 rounded-xl bg-white text-amber-900 font-black text-xs flex items-center justify-center shadow-md shrink-0 border border-white/80">
                                    {{ strtoupper(substr($place->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-white truncate drop-shadow-sm">{{ $place->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="p-5">
                            <!-- Short Description -->
                            <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">
                                {{ $place->description }}
                            </p>

                            <!-- Address & Hours Snippet -->
                            <div class="mt-3.5 pt-3 border-t border-gray-100 text-[11px] text-gray-500 space-y-1.5">
                                <p class="flex items-center gap-1.5 truncate">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500 shrink-0"></i>
                                    <span class="truncate">{{ $place->address }}</span>
                                </p>
                                @if($place->operating_hours)
                                    <p class="flex items-center gap-1.5 truncate text-amber-800 font-medium">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                                        <span class="truncate">{{ $place->operating_hours }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Direct WhatsApp / Reservation + Detail CTA -->
                    <div class="px-5 pb-5 pt-0 flex items-center justify-between gap-2 relative z-20">
                        @if($place->phone_whatsapp)
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $place->phone_whatsapp);
                                if (str_starts_with($cleanPhone, '08')) {
                                    $cleanPhone = '628' . substr($cleanPhone, 2);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($place->name) }},%20saya%20melihat%20info%20kuliner%20Anda%20di%20portal%20*Habar%20Etam*.%20Bisa%20info%20menu%20dan%20reservasi%20meja%3F" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                <span>Pesan</span>
                            </a>
                        @else
                            <span class="text-[11px] text-gray-400 font-medium flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-gray-400"></i>
                                <span>Tenggarong</span>
                            </span>
                        @endif

                        <a href="{{ route('culinary.show', $place->slug) }}" 
                           class="px-3 py-1.5 rounded-xl bg-brand-black group-hover:bg-amber-700 text-white text-xs font-bold transition-colors flex items-center gap-1 shadow-2xs">
                            <span>Detail & Peta</span>
                            <i data-lucide="arrow-right" class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $culinaryPlaces->links('partials.pagination') }}
        </div>
    @endif

    <!-- Kutai Gastronomy Heritage Highlight Banner -->
    <div class="mt-16 bg-[#FDF8EE] border border-[#ECD9B8] rounded-3xl p-6 sm:p-8 reveal-blur-spring">
        <div class="flex items-center gap-2 text-amber-800 font-bold text-xs uppercase tracking-wider mb-2">
            <i data-lucide="sparkles" class="w-4 h-4 text-amber-600"></i>
            <span>Khazanah Warisan Kuliner Khas Kutai Kartanegara</span>
        </div>
        <h2 class="text-xl font-extrabold text-brand-black">Menu Wajib Coba Saat Berkunjung ke Tenggarong</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6 text-xs text-gray-700">
            <div class="bg-white p-4 rounded-2xl border border-amber-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 font-black">
                    <i data-lucide="flame" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Gence Ruan</h3>
                    <p class="leading-relaxed">Ikan haruan (gabus) bakar asap yang disiram bumbu gence kaya rempah pedas gurih manis khas Kutai.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-rose-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center shrink-0 font-black">
                    <i data-lucide="crown" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Sambal Raja</h3>
                    <p class="leading-relaxed">Sambal legendaris istana kesultanan dengan racikan terong, kacang panjang, telur puyuh, dan jeruk kesturi.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-emerald-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 font-black">
                    <i data-lucide="soup" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Sayur Asam Kutai</h3>
                    <p class="leading-relaxed">Olahan kepala ikan patin/baung segar dengan kuah asam keladi, kangkung, dan rempah asam Jawa yang menyegarkan.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const culContainer = document.getElementById('culScrollContainer');
        const leftBtn = document.getElementById('culScrollLeft');
        const rightBtn = document.getElementById('culScrollRight');

        if (culContainer && leftBtn && rightBtn) {
            const updateArrows = () => {
                const maxScrollLeft = culContainer.scrollWidth - culContainer.clientWidth;
                const canScroll = maxScrollLeft > 10;
                
                if (!canScroll) {
                    leftBtn.style.opacity = '0';
                    rightBtn.style.opacity = '0';
                    return;
                }

                leftBtn.disabled = culContainer.scrollLeft <= 5;
                rightBtn.disabled = culContainer.scrollLeft >= maxScrollLeft - 5;
                
                leftBtn.style.opacity = culContainer.scrollLeft > 10 ? '1' : '0';
                rightBtn.style.opacity = culContainer.scrollLeft < maxScrollLeft - 10 ? '1' : '0';
            };

            leftBtn.addEventListener('click', () => {
                culContainer.scrollBy({ left: -240, behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                culContainer.scrollBy({ left: 240, behavior: 'smooth' });
            });

            culContainer.addEventListener('scroll', updateArrows, { passive: true });

            // Drag-to-scroll
            let isDown = false;
            let startX;
            let scrollLeftPos;
            let isDragging = false;

            culContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                isDragging = false;
                startX = e.pageX - culContainer.offsetLeft;
                scrollLeftPos = culContainer.scrollLeft;
            });

            culContainer.addEventListener('mouseleave', () => { isDown = false; });
            culContainer.addEventListener('mouseup', () => { isDown = false; });

            culContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                const x = e.pageX - culContainer.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 5) isDragging = true;
                culContainer.scrollLeft = scrollLeftPos - walk;
            });

            culContainer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        isDragging = false;
                    }
                });
            });

            // Wheel horizontal scroll
            culContainer.addEventListener('wheel', (e) => {
                if (Math.abs(e.deltaX) < Math.abs(e.deltaY)) {
                    culContainer.scrollLeft += e.deltaY * 0.75;
                    e.preventDefault();
                }
            }, { passive: false });

            // Auto scroll active pill into view
            const activePill = culContainer.querySelector('.ring-brand-gold\\/30');
            if (activePill) {
                activePill.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }

            setTimeout(updateArrows, 150);
            window.addEventListener('resize', updateArrows);
        }

        if (window.initIcons) {
            window.initIcons();
        }
    });
</script>
@endpush
