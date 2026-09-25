@extends('layouts.public')

@section('title', 'Produk & Jasa UMKM Kukar — Habar Etam')
@section('meta_description', 'Direktori produk, jasa, usaha mikro dan UMKM lokal di Tenggarong & Kutai Kartanegara. Temukan layanan percetakan, kerajinan, kuliner, dan jasa terpercaya.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- UMKM Hub Hero Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-brand-black to-slate-900 text-white rounded-3xl p-6 sm:p-8 mb-8 border border-emerald-500/20 shadow-2xl relative overflow-hidden reveal-blur-spring">
        <!-- Ambient Blur Circles -->
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Direktori Bisnis • UMKM & Jasa Warga Kukar</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Produk, Layanan & Usaha Lokal
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2 leading-relaxed">
                    Dukung perputaran roda ekonomi daerah dengan menggunakan produk, kerajinan tangan, percetakan, bengkel, serta aneka jasa terpercaya dari pelaku usaha di Tenggarong dan sekitarnya.
                </p>

                <!-- Quick Stats Badges -->
                <div class="flex flex-wrap items-center gap-3 mt-4 pt-2 text-xs">
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span><strong>{{ $businesses->total() }}</strong> Usaha Terdaftar</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Produk Unggulan Kukar</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Tenggarong & Wilayah Kukar</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('businesses.create') }}" class="btn-gold py-3.5 px-6 font-black text-sm shadow-gold-glow flex items-center justify-center gap-2 rounded-2xl hover:scale-[1.02] transition-transform">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Daftarkan Usaha Anda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Category Interactive Discovery Navigation Bar -->
    @php
        $bizCategories = [
            ['name' => 'Semua Kategori', 'value' => '', 'icon' => 'layout-grid'],
            ['name' => 'Kuliner & Olahan', 'value' => 'Kuliner & Olahan', 'icon' => 'utensils'],
            ['name' => 'Jasa Kreatif & Percetakan', 'value' => 'Jasa Kreatif & Percetakan', 'icon' => 'printer'],
            ['name' => 'Jasa Teknik & Fabrikasi', 'value' => 'Jasa Teknik & Fabrikasi', 'icon' => 'wrench'],
            ['name' => 'Kerajinan & Kriya Khas', 'value' => 'Kerajinan & Kriya Khas', 'icon' => 'gem'],
            ['name' => 'Fashion & Pakaian', 'value' => 'Fashion & Pakaian', 'icon' => 'shirt'],
            ['name' => 'Toko & Retail', 'value' => 'Toko & Retail', 'icon' => 'shopping-bag'],
            ['name' => 'Jasa Profesional', 'value' => 'Jasa Profesional', 'icon' => 'briefcase'],
        ];
        $currentCat = request('category', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Button -->
            <button type="button" id="bizScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Category Pills -->
            <div id="bizScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($bizCategories as $c)
                    @php
                        $isActive = ($currentCat === $c['value']) || (empty($currentCat) && empty($c['value']));
                        $queryParam = request()->except(['category', 'page']);
                        if (!empty($c['value'])) {
                            $queryParam['category'] = $c['value'];
                        }
                        $catUrl = route('businesses.index', $queryParam);
                    @endphp
                    <a href="{{ $catUrl }}" 
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
            <button type="button" id="bizScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Search & Advanced Filter Toolbar -->
    <div class="bg-white/95 backdrop-blur-sm border border-gray-200/90 rounded-3xl p-5 sm:p-6 mb-6 shadow-subtle reveal-blur-spring relative z-30 overflow-visible transition-all">
        <form method="GET" action="{{ route('businesses.index') }}" id="bizFilterForm" class="relative z-30">
            
            <!-- Hidden Category to preserve filter if searching -->
            @if(request()->filled('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <!-- 1. Search Input Field -->
                <div class="md:col-span-6 lg:col-span-6">
                    <label for="search-biz-input" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                        <span>Cari Usaha, Jasa, atau Produk</span>
                    </label>
                    <div class="relative group/search">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within/search:text-brand-gold-dark transition-colors">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               id="search-biz-input" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Contoh: Percetakan banner, Bengkel motor, Kriya manik, Catering..." 
                               class="form-input pl-10 pr-9 text-xs py-3 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-medium">
                        
                        @if(request()->filled('q'))
                            <a href="{{ route('businesses.index', request()->except(['q', 'page'])) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition-colors"
                               title="Hapus kata kunci">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- 2. Category Filter -->
                <div class="md:col-span-4 lg:col-span-4">
                    <label for="biz-category-select" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Kategori Usaha</span>
                    </label>
                    <div class="relative">
                        <select id="biz-category-select" 
                                name="category" 
                                onchange="document.getElementById('bizFilterForm').submit()" 
                                class="form-input text-xs py-3 pl-3 pr-8 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-semibold text-gray-800 cursor-pointer">
                            <option value="">Semua Kategori Usaha</option>
                            <option value="Kuliner & Olahan" {{ request('category') == 'Kuliner & Olahan' ? 'selected' : '' }}>Kuliner & Olahan</option>
                            <option value="Jasa Kreatif & Percetakan" {{ request('category') == 'Jasa Kreatif & Percetakan' ? 'selected' : '' }}>Jasa Kreatif & Percetakan</option>
                            <option value="Jasa Teknik & Fabrikasi" {{ request('category') == 'Jasa Teknik & Fabrikasi' ? 'selected' : '' }}>Jasa Teknik & Fabrikasi</option>
                            <option value="Kerajinan & Kriya Khas" {{ request('category') == 'Kerajinan & Kriya Khas' ? 'selected' : '' }}>Kerajinan & Kriya Khas</option>
                            <option value="Fashion & Pakaian" {{ request('category') == 'Fashion & Pakaian' ? 'selected' : '' }}>Fashion & Pakaian</option>
                            <option value="Toko & Retail" {{ request('category') == 'Toko & Retail' ? 'selected' : '' }}>Toko & Retail</option>
                            <option value="Jasa Profesional" {{ request('category') == 'Jasa Profesional' ? 'selected' : '' }}>Jasa Profesional</option>
                        </select>
                    </div>
                </div>

                <!-- 3. Action Buttons -->
                <div class="md:col-span-2 lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="btn-black flex-1 py-3 px-4 text-xs font-bold rounded-2xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all hover:scale-[1.01]">
                        <i data-lucide="filter" class="w-4 h-4 text-brand-gold"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->anyFilled(['q', 'category']))
                        <a href="{{ route('businesses.index') }}" class="btn-outline py-3 px-3 text-xs rounded-2xl text-rose-600 border-rose-200 hover:bg-rose-50 hover:border-rose-300 transition-colors shrink-0" title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Popular Tags -->
            <div class="mt-3.5 pt-3.5 border-t border-gray-100 flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                <span class="font-semibold text-gray-600 flex items-center gap-1 shrink-0">
                    <i data-lucide="flame" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Cari Cepat:</span>
                </span>
                @php
                    $popularBizKeywords = ['Percetakan', 'Bengkel', 'Sablon', 'Katering', 'Kriya Anyam', 'Laundry', 'Konveksi', 'Fotografi'];
                @endphp
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach($popularBizKeywords as $kw)
                        @php
                            $isCurrentQuery = request('q') === $kw;
                            $kwUrl = route('businesses.index', array_merge(request()->except(['page']), ['q' => $kw]));
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
                <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span><strong>{{ $businesses->total() }}</strong> Profil Usaha Ditemukan</span>
            </span>

            <!-- Removable Filter Tag: Category -->
            @if(request()->filled('category'))
                <a href="{{ route('businesses.index', request()->except(['category', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 font-bold hover:bg-emerald-100 transition-colors shadow-2xs group"
                   title="Hapus filter kategori">
                    <span>Kategori: <strong>{{ request('category') }}</strong></span>
                    <i data-lucide="x" class="w-3 h-3 text-emerald-700 group-hover:text-rose-600"></i>
                </a>
            @endif

            <!-- Removable Filter Tag: Query -->
            @if(request()->filled('q'))
                <a href="{{ route('businesses.index', request()->except(['q', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 font-bold hover:bg-amber-100 transition-colors shadow-2xs group"
                   title="Hapus kata kunci">
                    <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                    <i data-lucide="x" class="w-3 h-3 text-amber-700 group-hover:text-rose-600"></i>
                </a>
            @endif
        </div>

        @if(request()->anyFilled(['q', 'category']))
            <a href="{{ route('businesses.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1.5 self-start sm:self-auto shrink-0">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                <span>Reset Semua Filter</span>
            </a>
        @endif
    </div>

    <!-- Businesses Grid -->
    @if($businesses->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200 p-12 sm:p-16 text-center shadow-subtle reveal-blur-spring">
            <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="store" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-black">Belum Ada Usaha yang Sesuai</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5 max-w-md mx-auto">
                Tidak ada data usaha atau jasa yang sesuai dengan kriteria pencarian Anda. Coba reset filter atau daftarkan usaha Anda sekarang.
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('businesses.index') }}" class="btn-outline text-xs py-2.5 px-4 rounded-xl font-bold">
                    Reset Filter
                </a>
                <a href="{{ route('businesses.create') }}" class="btn-gold text-xs py-2.5 px-5 rounded-xl font-bold">
                    Daftarkan Usaha Baru
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 reveal-stagger">
            @foreach($businesses as $biz)
                <div class="content-card modern-hover-card relative flex flex-col justify-between group rounded-3xl shadow-subtle hover:shadow-2xl bg-white border border-gray-200/90 overflow-hidden cursor-pointer transition-all duration-300">
                    
                    <!-- Entire Card Clickable Link -->
                    <a href="{{ route('businesses.show', $biz->slug) }}" class="absolute inset-0 z-10" aria-label="{{ $biz->name }}"></a>

                    <div>
                        <!-- Top Image Media Container -->
                        <div class="h-44 w-full relative overflow-hidden bg-slate-900 shrink-0">
                            <img src="{{ $biz->photo_url }}" 
                                 alt="{{ $biz->name }}" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

                            <!-- Top Badges Overlay -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 z-10">
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-xl bg-emerald-600/90 backdrop-blur-md text-white border border-emerald-400/40 uppercase tracking-wider shadow-sm truncate max-w-[150px]">
                                    {{ $biz->category }}
                                </span>

                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md text-white/90 border border-white/20 flex items-center gap-1 shadow-sm">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-rose-400"></i>
                                    <span>{{ $biz->location_district }}</span>
                                </span>
                            </div>

                            <!-- Bottom Image Overlay: Business Initial & Name -->
                            <div class="absolute bottom-3 left-3 right-3 flex items-center gap-2.5 z-10">
                                <div class="w-9 h-9 rounded-xl bg-white text-emerald-900 font-black text-xs flex items-center justify-center shadow-md shrink-0 border border-white/80">
                                    {{ strtoupper(substr($biz->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-white truncate drop-shadow-sm">{{ $biz->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="p-5">
                            <!-- Short Description -->
                            <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">
                                {{ $biz->description }}
                            </p>

                            <!-- Address & Hours Snippet -->
                            <div class="mt-3.5 pt-3 border-t border-gray-100 text-[11px] text-gray-500 space-y-1.5">
                                <p class="flex items-center gap-1.5 truncate">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                    <span class="truncate">{{ $biz->address }}</span>
                                </p>
                                @if($biz->operating_hours)
                                    <p class="flex items-center gap-1.5 truncate text-emerald-700 font-medium">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                        <span class="truncate">{{ $biz->operating_hours }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Direct WhatsApp + Detail CTA -->
                    <div class="px-5 pb-5 pt-0 flex items-center justify-between gap-2 relative z-20">
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $biz->phone_whatsapp);
                            if (str_starts_with($cleanPhone, '08')) {
                                $cleanPhone = '628' . substr($cleanPhone, 2);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($biz->name) }},%20saya%20melihat%20profil%20usaha%20Anda%20di%20portal%20*Habar%20Etam*.%20Bisa%20info%20lebih%20lanjut%20mengenai%20produk%2Fjasa%20Anda%3F" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            <span>Chat WA</span>
                        </a>

                        <a href="{{ route('businesses.show', $biz->slug) }}" 
                           class="px-3 py-1.5 rounded-xl bg-brand-black group-hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-1 shadow-2xs">
                            <span>Profil</span>
                            <i data-lucide="arrow-right" class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $businesses->links('partials.pagination') }}
        </div>
    @endif

    <!-- Local Economic Support Banner -->
    <div class="mt-16 bg-[#FBF9F4] border border-[#EBE3D3] rounded-3xl p-6 sm:p-8 reveal-blur-spring">
        <div class="flex items-center gap-2 text-brand-gold-dark font-bold text-xs uppercase tracking-wider mb-2">
            <i data-lucide="heart-handshake" class="w-4 h-4 text-emerald-600"></i>
            <span>Gerakan Bangga & Beli Produk Lokal Kukar</span>
        </div>
        <h2 class="text-xl font-extrabold text-brand-black">Keuntungan Menggunakan Jasa & Produk Lokal</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6 text-xs text-gray-600">
            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 font-black">
                    <i data-lucide="coins" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Putar Ekonomi Daerah</h3>
                    <p class="leading-relaxed">Setiap transaksi membantu memperkuat perputaran roda usaha mikro dan membuka lapangan kerja warga setempat.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 font-black">
                    <i data-lucide="message-square-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Komunikasi & Custom Cepat</h3>
                    <p class="leading-relaxed">Mudah berdiskusi, survei lokasi, konsultasi kebutuhan custom, hingga negosiasi langsung dengan pelaku usaha.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 font-black">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Hemat Biaya Ongkir</h3>
                    <p class="leading-relaxed">Pesanan dapat diambil langsung di tempat (COD) atau dikirim via kurir lokal dengan ongkir yang jauh lebih hemat.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bizContainer = document.getElementById('bizScrollContainer');
        const leftBtn = document.getElementById('bizScrollLeft');
        const rightBtn = document.getElementById('bizScrollRight');

        if (bizContainer && leftBtn && rightBtn) {
            const updateArrows = () => {
                const maxScrollLeft = bizContainer.scrollWidth - bizContainer.clientWidth;
                const canScroll = maxScrollLeft > 10;
                
                if (!canScroll) {
                    leftBtn.style.opacity = '0';
                    rightBtn.style.opacity = '0';
                    return;
                }

                leftBtn.disabled = bizContainer.scrollLeft <= 5;
                rightBtn.disabled = bizContainer.scrollLeft >= maxScrollLeft - 5;
                
                leftBtn.style.opacity = bizContainer.scrollLeft > 10 ? '1' : '0';
                rightBtn.style.opacity = bizContainer.scrollLeft < maxScrollLeft - 10 ? '1' : '0';
            };

            leftBtn.addEventListener('click', () => {
                bizContainer.scrollBy({ left: -240, behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                bizContainer.scrollBy({ left: 240, behavior: 'smooth' });
            });

            bizContainer.addEventListener('scroll', updateArrows, { passive: true });

            // Drag-to-scroll
            let isDown = false;
            let startX;
            let scrollLeftPos;
            let isDragging = false;

            bizContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                isDragging = false;
                startX = e.pageX - bizContainer.offsetLeft;
                scrollLeftPos = bizContainer.scrollLeft;
            });

            bizContainer.addEventListener('mouseleave', () => { isDown = false; });
            bizContainer.addEventListener('mouseup', () => { isDown = false; });

            bizContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                const x = e.pageX - bizContainer.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 5) isDragging = true;
                bizContainer.scrollLeft = scrollLeftPos - walk;
            });

            bizContainer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        isDragging = false;
                    }
                });
            });

            // Wheel horizontal scroll
            bizContainer.addEventListener('wheel', (e) => {
                if (Math.abs(e.deltaX) < Math.abs(e.deltaY)) {
                    bizContainer.scrollLeft += e.deltaY * 0.75;
                    e.preventDefault();
                }
            }, { passive: false });

            // Auto scroll active pill into view
            const activePill = bizContainer.querySelector('.ring-brand-gold\\/30');
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
