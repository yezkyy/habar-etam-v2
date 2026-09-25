@extends('layouts.public')

@section('title', 'Jual Cepat Warga — Marketplace Lokal Tenggarong & Kukar')
@section('meta_description', 'Bursa Jual Cepat barang bekas dan baru berkualitas dari warga Tenggarong & Kutai Kartanegara. HP, gadget, laptop, motor, perabot, dan elektronik langsung COD tanpa perantara.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Marketplace Hero Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-brand-black to-stone-900 text-white rounded-3xl p-6 sm:p-8 mb-8 border border-amber-500/20 shadow-2xl relative overflow-hidden reveal-blur-spring">
        <!-- Subtle Glow Background -->
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-rose-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/30 text-brand-gold text-xs font-bold uppercase tracking-wider mb-3">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5 text-amber-400"></i>
                    <span>Marketplace Warga • Bebas Biaya Admin & Perantara</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Jual Cepat & Beli Kilat Antar Warga
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2 leading-relaxed">
                    Jual barang pribadi Anda dengan cepat, temukan penawaran terbaik dari tetangga sekitar Tenggarong & Kukar, lalu transaksi langsung via WhatsApp & COD aman.
                </p>

                <!-- Quick Stats Badges -->
                <div class="flex flex-wrap items-center gap-3 mt-4 pt-2 text-xs">
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span><strong>{{ $totalActive ?? $items->total() }}</strong> Listing Aktif</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span><strong>{{ $totalSold ?? 0 }}</strong> Barang Terjual</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Area Kutai Kartanegara</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('quick-sales.create') }}" class="btn-gold py-3.5 px-6 font-black text-sm shadow-gold-glow flex items-center justify-center gap-2 rounded-2xl hover:scale-[1.02] transition-transform">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Pasang Jual Cepat Gratis</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Interactive Category Navigation Discovery Bar -->
    @php
        $categories = [
            ['name' => 'Semua Kategori', 'value' => '', 'icon' => 'layout-grid'],
            ['name' => 'Gadget & HP', 'value' => 'Gadget & HP', 'icon' => 'smartphone'],
            ['name' => 'Komputer & Laptop', 'value' => 'Komputer & Laptop', 'icon' => 'laptop'],
            ['name' => 'Kendaraan & Motor', 'value' => 'Kendaraan & Motor', 'icon' => 'car'],
            ['name' => 'Elektronik Rumah', 'value' => 'Elektronik Rumah', 'icon' => 'tv'],
            ['name' => 'Perabot Rumah', 'value' => 'Perabot Rumah', 'icon' => 'armchair'],
            ['name' => 'Hobi & Koleksi', 'value' => 'Hobi & Koleksi', 'icon' => 'gamepad-2'],
            ['name' => 'Lainnya', 'value' => 'Lainnya', 'icon' => 'package'],
        ];
        $currentCat = request('category', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <!-- Category Navigation Wrapper with Scroll Controls -->
        <div class="relative flex items-center">
            <!-- Left Scroll Arrow Button -->
            <button type="button" id="catScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Category Pills (Hidden Scrollbar + Drag/Wheel Support) -->
            <div id="categoryScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($currentCat === $cat['value']) || (empty($currentCat) && empty($cat['value']));
                        $queryParam = request()->except(['category', 'page']);
                        if (!empty($cat['value'])) {
                            $queryParam['category'] = $cat['value'];
                        }
                        $catUrl = route('quick-sales.index', $queryParam);
                    @endphp
                    <a href="{{ $catUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-brand-black to-stone-900 text-brand-gold border-brand-black shadow-md ring-2 ring-brand-gold/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-brand-gold/70 hover:bg-amber-50/60 hover:text-brand-black shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-brand-gold/20 text-brand-gold' : 'bg-gray-100 group-hover/item:bg-amber-100 text-gray-600 group-hover/item:text-brand-black' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $cat['name'] }}</span>
                        @if($isActive && !empty($cat['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Arrow Button -->
            <button type="button" id="catScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Search & Advanced Filter Toolbar -->
    <div class="bg-white/95 backdrop-blur-sm border border-gray-200/90 rounded-3xl p-5 sm:p-6 mb-6 shadow-subtle reveal-blur-spring relative z-30 overflow-visible transition-all">
        <form method="GET" action="{{ route('quick-sales.index') }}" id="quickSaleFilterForm" class="relative z-30">
            
            <!-- Hidden Category to preserve category selection -->
            @if(request()->filled('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <!-- 1. Search Input Field -->
                <div class="md:col-span-5 lg:col-span-5">
                    <label for="search-input" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                        <span>Kata Kunci Pencarian</span>
                    </label>
                    <div class="relative group/search">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within/search:text-brand-gold-dark transition-colors">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               id="search-input" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Cari iPhone, Scoopy, Sofa, Kamera, Laptop..." 
                               class="form-input pl-10 pr-9 text-xs py-3 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-medium">
                        
                        @if(request()->filled('q'))
                            <a href="{{ route('quick-sales.index', request()->except(['q', 'page'])) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition-colors"
                               title="Hapus kata kunci">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- 2. Condition Filter -->
                <div class="md:col-span-4 lg:col-span-3">
                    <label for="condition-select" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500"></i>
                        <span>Kondisi Barang</span>
                    </label>
                    <div class="relative">
                        <select id="condition-select" 
                                name="condition" 
                                onchange="document.getElementById('quickSaleFilterForm').submit()" 
                                class="form-input text-xs py-3 pl-3 pr-8 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-semibold text-gray-800 cursor-pointer">
                            <option value="">Semua Kondisi Barang</option>
                            <option value="Baru" {{ request('condition') == 'Baru' ? 'selected' : '' }}>Baru (Segel / Brand New)</option>
                            <option value="Bekas - Seperti Baru" {{ request('condition') == 'Bekas - Seperti Baru' ? 'selected' : '' }}>Bekas - Seperti Baru (Mulus)</option>
                            <option value="Bekas - Normal/Bagus" {{ request('condition') == 'Bekas - Normal/Bagus' ? 'selected' : '' }}>Bekas - Normal / Bagus</option>
                            <option value="Bekas - Apa Adanya" {{ request('condition') == 'Bekas - Apa Adanya' ? 'selected' : '' }}>Bekas - Apa Adanya (Minus)</option>
                        </select>
                    </div>
                </div>

                <!-- 3. Sort By Filter -->
                <div class="md:col-span-3 lg:col-span-2">
                    <label for="sort-select" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="arrow-up-down" class="w-3.5 h-3.5 text-blue-500"></i>
                        <span>Urutan Listing</span>
                    </label>
                    <div class="relative">
                        <select id="sort-select" 
                                name="sort" 
                                onchange="document.getElementById('quickSaleFilterForm').submit()" 
                                class="form-input text-xs py-3 pl-3 pr-8 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-semibold text-gray-800 cursor-pointer">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru Dipublikasikan</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah &rarr; Tertinggi</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi &rarr; Terendah</option>
                        </select>
                    </div>
                </div>

                <!-- 4. Action Buttons (Submit & Clear) -->
                <div class="md:col-span-12 lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="btn-black flex-1 py-3 px-4 text-xs font-bold rounded-2xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all hover:scale-[1.01]">
                        <i data-lucide="filter" class="w-4 h-4 text-brand-gold"></i>
                        <span>Terapkan</span>
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'condition', 'sort', 'location']))
                        <a href="{{ route('quick-sales.index') }}" class="btn-outline py-3 px-3 text-xs rounded-2xl text-rose-600 border-rose-200 hover:bg-rose-50 hover:border-rose-300 transition-colors shrink-0" title="Reset Semua Filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Popular Keyword Suggestion Tags -->
            <div class="mt-3.5 pt-3.5 border-t border-gray-100 flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                <span class="font-semibold text-gray-600 flex items-center gap-1 shrink-0">
                    <i data-lucide="flame" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Sering Dicari:</span>
                </span>
                @php
                    $popularKeywords = ['iPhone', 'Scoopy', 'Vario', 'Laptop', 'Sofa', 'Kamera', 'Sepeda', 'PS5'];
                @endphp
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach($popularKeywords as $keyword)
                        @php
                            $isCurrentQuery = request('q') === $keyword;
                            $kwUrl = route('quick-sales.index', array_merge(request()->except(['page']), ['q' => $keyword]));
                        @endphp
                        <a href="{{ $kwUrl }}" 
                           class="px-2.5 py-1 rounded-xl transition-all duration-150 {{ $isCurrentQuery ? 'bg-amber-400 text-black font-bold shadow-2xs' : 'bg-gray-100 hover:bg-amber-100/70 text-gray-700 hover:text-black' }}">
                            {{ $keyword }}
                        </a>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    <!-- Active Filters Badge Summary Strip -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 text-xs text-gray-600">
        <!-- Results Count & Active Category Badge -->
        <div class="flex items-center flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-gray-200 text-gray-800 font-bold shadow-2xs">
                <i data-lucide="package" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                <span><strong>{{ $items->total() }}</strong> Listing Ditemukan</span>
            </span>

            <!-- Removable Filter Tag: Category -->
            @if(request()->filled('category'))
                <a href="{{ route('quick-sales.index', request()->except(['category', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 font-bold hover:bg-amber-100 transition-colors shadow-2xs group"
                   title="Hapus filter kategori">
                    <span>Kategori: <strong>{{ request('category') }}</strong></span>
                    <i data-lucide="x" class="w-3 h-3 text-amber-700 group-hover:text-rose-600"></i>
                </a>
            @endif

            <!-- Removable Filter Tag: Query -->
            @if(request()->filled('q'))
                <a href="{{ route('quick-sales.index', request()->except(['q', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 font-bold hover:bg-blue-100 transition-colors shadow-2xs group"
                   title="Hapus filter pencarian">
                    <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                    <i data-lucide="x" class="w-3 h-3 text-blue-700 group-hover:text-rose-600"></i>
                </a>
            @endif

            <!-- Removable Filter Tag: Condition -->
            @if(request()->filled('condition'))
                <a href="{{ route('quick-sales.index', request()->except(['condition', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 font-bold hover:bg-emerald-100 transition-colors shadow-2xs group"
                   title="Hapus filter kondisi">
                    <span>Kondisi: <strong>{{ request('condition') }}</strong></span>
                    <i data-lucide="x" class="w-3 h-3 text-emerald-700 group-hover:text-rose-600"></i>
                </a>
            @endif

            <!-- Removable Filter Tag: Sort -->
            @if(request()->filled('sort') && request('sort') !== 'latest')
                @php
                    $sortLabels = [
                        'price_low' => 'Harga Terendah',
                        'price_high' => 'Harga Tertinggi',
                    ];
                @endphp
                <a href="{{ route('quick-sales.index', request()->except(['sort', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-50 border border-purple-200 text-purple-900 font-bold hover:bg-purple-100 transition-colors shadow-2xs group"
                   title="Reset urutan">
                    <span>Urutan: <strong>{{ $sortLabels[request('sort')] ?? request('sort') }}</strong></span>
                    <i data-lucide="x" class="w-3 h-3 text-purple-700 group-hover:text-rose-600"></i>
                </a>
            @endif
        </div>

        <!-- Global Reset Button if any filter active -->
        @if(request()->anyFilled(['q', 'category', 'condition', 'sort', 'location']))
            <a href="{{ route('quick-sales.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1.5 self-start sm:self-auto shrink-0">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                <span>Reset Semua Filter</span>
            </a>
        @endif
    </div>

    <!-- Items Grid -->
    @if($items->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200 p-12 sm:p-16 text-center shadow-subtle reveal-blur-spring">
            <div class="w-16 h-16 rounded-full bg-amber-50 border border-amber-200 text-brand-gold-dark flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-search" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-black">Belum Ada Barang yang Cocok</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5 max-w-md mx-auto">
                Tidak ada listing barang yang sesuai dengan kata kunci atau filter yang Anda pilih. Coba gunakan kata kunci lain atau reset filter.
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('quick-sales.index') }}" class="btn-outline text-xs py-2.5 px-4 rounded-xl font-bold">
                    Reset Filter Pencarian
                </a>
                <a href="{{ route('quick-sales.create') }}" class="btn-gold text-xs py-2.5 px-5 rounded-xl font-bold">
                    Pasang Jual Cepat Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal-stagger">
            @foreach($items as $item)
                <div class="content-card modern-hover-card relative flex flex-col justify-between group rounded-3xl shadow-subtle hover:shadow-2xl bg-white border border-gray-200/90 overflow-hidden cursor-pointer transition-all duration-300">
                    
                    <!-- Entire Card Clickable Link -->
                    <a href="{{ route('quick-sales.show', $item->slug) }}" class="absolute inset-0 z-0" aria-label="{{ $item->title }}"></a>

                    <!-- Product Image Box -->
                    <div class="relative aspect-video bg-gray-100 overflow-hidden rounded-t-3xl pointer-events-none">
                        @if($item->primaryMedia())
                            <img src="{{ Storage::url($item->primaryMedia()->path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <i data-lucide="image" class="w-10 h-10 text-gray-300 mb-1"></i>
                                <span class="text-[10px] text-gray-400 font-medium">Foto Belum Tersedia</span>
                            </div>
                        @endif
                        
                        <!-- Category Badge (Top Left) -->
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-brand-black/80 backdrop-blur-md text-white text-[10px] font-bold shadow-sm">
                            {{ $item->category }}
                        </span>

                        <!-- Photo Count Badge (Top Right) -->
                        @if($item->media->count() > 1)
                            <span class="absolute top-3 right-3 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-md text-white text-[10px] font-mono font-bold flex items-center gap-1">
                                <i data-lucide="camera" class="w-3 h-3"></i>
                                <span>{{ $item->media->count() }}</span>
                            </span>
                        @endif

                        <!-- Sold Status or Condition Badge -->
                        @if($item->isSold())
                            <div class="absolute inset-0 bg-brand-black/75 backdrop-blur-[2px] flex flex-col items-center justify-center text-white">
                                <span class="font-black text-sm tracking-widest uppercase bg-rose-600 px-3 py-1 rounded-lg shadow-md">
                                    TERJUAL
                                </span>
                            </div>
                        @else
                            <span class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-full {{ str_starts_with($item->condition, 'Baru') ? 'bg-emerald-500 text-white' : 'bg-brand-gold text-brand-black' }} text-[10px] font-black shadow-sm">
                                {{ $item->condition }}
                            </span>
                        @endif
                    </div>

                    <!-- Card Content Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between relative z-10 pointer-events-none">
                        <div>
                            <!-- Title -->
                            <h3 class="text-sm font-extrabold text-brand-black group-hover:text-brand-gold-dark transition-colors line-clamp-2 leading-snug">
                                {{ $item->title }}
                            </h3>

                            <!-- Price Highlight -->
                            <div class="text-base font-black text-brand-gold-dark mt-2 flex items-baseline gap-1">
                                <span>{{ $item->formatted_price }}</span>
                                @if(!$item->isSold())
                                    <span class="text-[10px] font-semibold text-gray-400">/ Nego COD</span>
                                @endif
                            </div>

                            <!-- Short Description -->
                            <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $item->description }}
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div class="mt-4 pt-3 border-t border-gray-100 flex flex-col gap-2">
                            <!-- Location and Time -->
                            <div class="flex items-center justify-between text-[11px] text-gray-500">
                                <span class="truncate flex items-center gap-1 max-w-[140px]" title="{{ $item->location_name }}">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-rose-500 shrink-0"></i>
                                    <span class="truncate">{{ $item->location_name }}</span>
                                </span>
                                <span class="text-[10px] text-gray-400 shrink-0">
                                    {{ $item->published_at ? $item->published_at->diffForHumans() : $item->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Action / Seller Info Row -->
                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-center gap-1.5 truncate">
                                    <div class="w-5 h-5 rounded-full bg-brand-black text-brand-gold text-[10px] font-bold flex items-center justify-center shrink-0">
                                        {{ substr($item->user->name ?? 'W', 0, 1) }}
                                    </div>
                                    <span class="text-[11px] font-semibold text-gray-700 truncate max-w-[90px]">
                                        {{ $item->user->name ?? 'Warga Kukar' }}
                                    </span>
                                </div>

                                @if(!$item->isSold())
                                    <a href="https://wa.me/{{ $item->contact_whatsapp }}?text=Halo,%20saya%20tertarik%20dengan%20listing%20Jual%20Cepat%20Habar%20Etam:%20{{ urlencode($item->title) }}%20(Harga:%20{{ urlencode($item->formatted_price) }})." target="_blank" class="pointer-events-auto px-3 py-1 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-transparent text-[11px] font-bold transition-all flex items-center gap-1 shadow-2xs relative z-20">
                                        <i data-lucide="message-circle" class="w-3 h-3"></i>
                                        <span>Chat WA</span>
                                    </a>
                                @else
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Terjual</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $items->links('partials.pagination') }}
        </div>
    @endif

    <!-- Marketplace Safety Guidelines (Tips COD Aman Kukar) -->
    <div class="mt-16 bg-[#FBF9F4] border border-[#EBE3D3] rounded-3xl p-6 sm:p-8 reveal-blur-spring">
        <div class="flex items-center gap-2 text-brand-gold-dark font-bold text-xs uppercase tracking-wider mb-2">
            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
            <span>Panduan Keamanan Transaksi COD Warga Tenggarong</span>
        </div>
        <h2 class="text-xl font-extrabold text-brand-black">Tips Jual Beli Aman & Nyaman Antar Warga</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6 text-xs text-gray-600">
            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 font-black">
                    1
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Cek Kondisi Fisik & Fungsi</h3>
                    <p class="leading-relaxed">Periksa kelengkapan, tes fungsional, dan pastikan kondisi barang sesuai dengan deskripsi sebelum menyerahkan uang.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 font-black">
                    2
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Pilih Lokasi Ramai di Tenggarong</h3>
                    <p class="leading-relaxed">Sepakati titik COD di area publik yang ramai, seperti Taman Kota Raja, Dermaga Pulau Kumala, Kedaton, atau kafe sekitar.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center shrink-0 font-black">
                    3
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Hindari Transfer DP di Awal</h3>
                    <p class="leading-relaxed">Jangan pernah mentransfer uang muka atau DP sebelum bertemu langsung dan memeriksa barang bersama penjual.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const catContainer = document.getElementById('categoryScrollContainer');
        const leftBtn = document.getElementById('catScrollLeft');
        const rightBtn = document.getElementById('catScrollRight');

        if (catContainer && leftBtn && rightBtn) {
            const updateArrows = () => {
                const maxScrollLeft = catContainer.scrollWidth - catContainer.clientWidth;
                const canScroll = maxScrollLeft > 10;
                
                if (!canScroll) {
                    leftBtn.style.opacity = '0';
                    rightBtn.style.opacity = '0';
                    return;
                }

                leftBtn.disabled = catContainer.scrollLeft <= 5;
                rightBtn.disabled = catContainer.scrollLeft >= maxScrollLeft - 5;
                
                leftBtn.style.opacity = catContainer.scrollLeft > 10 ? '1' : '0';
                rightBtn.style.opacity = catContainer.scrollLeft < maxScrollLeft - 10 ? '1' : '0';
            };

            leftBtn.addEventListener('click', () => {
                catContainer.scrollBy({ left: -240, behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                catContainer.scrollBy({ left: 240, behavior: 'smooth' });
            });

            catContainer.addEventListener('scroll', updateArrows, { passive: true });

            // Mouse Drag-to-Scroll Support
            let isDown = false;
            let startX;
            let scrollLeftPos;
            let isDragging = false;

            catContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                isDragging = false;
                startX = e.pageX - catContainer.offsetLeft;
                scrollLeftPos = catContainer.scrollLeft;
            });

            catContainer.addEventListener('mouseleave', () => {
                isDown = false;
            });

            catContainer.addEventListener('mouseup', () => {
                isDown = false;
            });

            catContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                const x = e.pageX - catContainer.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 5) {
                    isDragging = true;
                }
                catContainer.scrollLeft = scrollLeftPos - walk;
            });

            // Prevent link navigation if dragging occurred
            catContainer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        isDragging = false;
                    }
                });
            });

            // Mouse Wheel Horizontal Scroll Support
            catContainer.addEventListener('wheel', (e) => {
                if (Math.abs(e.deltaX) < Math.abs(e.deltaY)) {
                    // Convert vertical wheel to horizontal scroll inside category container
                    catContainer.scrollLeft += e.deltaY * 0.75;
                    e.preventDefault();
                }
            }, { passive: false });
            
            // Auto scroll active category into view
            const activePill = catContainer.querySelector('.ring-brand-gold\\/30');
            if (activePill) {
                activePill.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }

            // Initial check
            setTimeout(updateArrows, 150);
            window.addEventListener('resize', updateArrows);
        }

        if (window.initIcons) {
            window.initIcons();
        }
    });
</script>
@endpush

