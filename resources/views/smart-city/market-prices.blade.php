@extends('layouts.public')

@section('title', 'Harga Pangan & Pasar Tradisional Kukar — Habar Etam')
@section('meta_description', 'Pantauan harga harian komoditas pangan pokok, sembako, cabai, beras mayas, daging, dan ikan segar Mahakam di Pasar Tangga Arung & Pasar Mangkurawang Tenggarong.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Editorial Market Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-slate-900 to-emerald-950 text-white rounded-3xl p-6 sm:p-10 mb-8 shadow-2xl border border-emerald-500/20 relative overflow-hidden reveal-blur-spring">
        <!-- Subtle Glow & Mesh Orbs -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none animate-ambient-glow"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3.5 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Smart City Kukar • Informasi Pangan Terbuka</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-md">
                    Pantauan Harga Pangan & <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-300 to-amber-300">Pasar Rakyat</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2.5 leading-relaxed font-normal max-w-xl">
                    Informasi transparansi harga komoditas kebutuhan pokok, bumbu dapur, ikan air tawar Mahakam, dan sayur mayur hasil pertanian lokal Kutai Kartanegara.
                </p>

                <!-- Quick Macro Summary Badges -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 mt-5 pt-1 text-xs">
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-emerald-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="store" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Pasar: <strong>{{ $selectedMarket }}</strong></span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-teal-400"></i>
                        <span>{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-2 backdrop-blur-sm">
                        <i data-lucide="shopping-basket" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span><strong>{{ $prices->count() }}</strong> Komoditas Terpantau</span>
                    </span>
                </div>
            </div>

            <!-- Market Selector Tabs in Hero -->
            <div class="bg-black/60 backdrop-blur-md border border-white/15 rounded-3xl p-4 text-xs text-white max-w-sm w-full shadow-2xl shrink-0">
                <span class="font-extrabold text-amber-300 block mb-2.5 flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-400"></i>
                    <span>Pilih Pasar Tradisional:</span>
                </span>
                <div class="flex flex-col gap-2">
                    @foreach($availableMarkets as $m)
                        @php
                            $isMarketActive = ($selectedMarket === $m);
                        @endphp
                        <a href="{{ route('smart-city.market-prices', ['market' => $m, 'date' => $selectedDate]) }}" 
                           class="flex items-center justify-between p-3 rounded-2xl border transition-all {{ $isMarketActive ? 'bg-gradient-to-r from-emerald-950 via-teal-900 to-emerald-900 border-emerald-400/50 text-white font-black shadow-md ring-2 ring-emerald-400/30' : 'bg-white/5 border-white/10 text-gray-300 hover:bg-white/10 hover:text-white font-semibold' }}">
                            <div class="flex items-center gap-2.5 truncate">
                                <i data-lucide="store" class="w-4 h-4 {{ $isMarketActive ? 'text-brand-gold' : 'text-gray-400' }} shrink-0"></i>
                                <span class="truncate">{{ $m }}</span>
                            </div>
                            @if($isMarketActive)
                                <span class="w-2 h-2 rounded-full bg-brand-gold animate-pulse shrink-0"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Category Discovery Scroll Bar -->
    @php
        $categories = [
            ['name' => 'Semua Komoditas', 'value' => '', 'icon' => 'layout-grid'],
            ['name' => 'Bumbu Dapur', 'value' => 'bumbu_dapur', 'icon' => 'flame'],
            ['name' => 'Sembako & Beras', 'value' => 'sembako', 'icon' => 'package'],
            ['name' => 'Daging & Ikan', 'value' => 'daging_ikan', 'icon' => 'fish'],
            ['name' => 'Sayur Mayur', 'value' => 'sayur_mayur', 'icon' => 'leaf'],
            ['name' => 'Telur & Susu', 'value' => 'telur_susu', 'icon' => 'egg'],
        ];
        $currentCat = request('category', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Arrow -->
            <button type="button" id="mktCatScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-emerald-50 hover:border-emerald-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Pills Container -->
            <div id="mktCatScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($categories as $cat)
                    @php
                        $isActive = ($currentCat === $cat['value']) || (empty($currentCat) && empty($cat['value']));
                        $queryParam = request()->except(['category']);
                        if (!empty($cat['value'])) {
                            $queryParam['category'] = $cat['value'];
                        }
                        $catUrl = route('smart-city.market-prices', $queryParam);
                        $count = !empty($cat['value']) ? ($categoryCounts[$cat['value']] ?? 0) : ($totalCommodities ?? $prices->count());
                    @endphp
                    <a href="{{ $catUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-slate-900 to-emerald-950 text-emerald-300 border-emerald-900 shadow-md ring-2 ring-emerald-500/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-emerald-400/70 hover:bg-emerald-50/60 hover:text-emerald-950 shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-emerald-500/20 text-emerald-300' : 'bg-gray-100 group-hover/item:bg-emerald-100 text-gray-600 group-hover/item:text-emerald-900' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $cat['name'] }}</span>
                        @if($count > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $isActive ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-gray-100 text-gray-500' }}">
                                {{ $count }}
                            </span>
                        @endif
                        @if($isActive && !empty($cat['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Arrow -->
            <button type="button" id="mktCatScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-emerald-50 hover:border-emerald-300 transition-all opacity-0 group-hover:opacity-100 disabled:opacity-0 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Search & Date Filter Bar -->
    <div class="relative z-30 bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 mb-8 shadow-subtle reveal-blur-spring">
        <form method="GET" action="{{ route('smart-city.market-prices') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <input type="hidden" name="market" value="{{ $selectedMarket }}">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <!-- Keyword Search -->
            <div class="sm:col-span-6">
                <label class="form-label mb-1.5 text-xs text-gray-600 font-bold flex items-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>Cari Komoditas Pangan</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Ketik cabai, beras mayas, ikan haruan, bawang, daging..." 
                           class="form-input text-xs pl-3.5 pr-8 py-2.5 rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500/20">
                    @if(request('q'))
                        <a href="{{ route('smart-city.market-prices', request()->except('q')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Date Picker -->
            <div class="sm:col-span-4">
                <label class="form-label mb-1.5 text-xs text-gray-600 font-bold flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span>Pilih Tanggal Pantauan</span>
                </label>
                <input type="date" 
                       name="date" 
                       value="{{ $selectedDate }}" 
                       class="form-input text-xs py-2.5 rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500/20">
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="btn-black flex-1 py-2.5 px-4 text-xs font-bold rounded-xl shadow-xs hover:bg-gray-800 transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->anyFilled(['q', 'category', 'date']))
                    <a href="{{ route('smart-city.market-prices', ['market' => $selectedMarket]) }}" class="btn-outline py-2.5 px-3 text-xs rounded-xl border-gray-200 hover:bg-gray-100 transition-colors" title="Reset Filter">
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
                <span>Daftar Harga Komoditas</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                    {{ $selectedMarket }}
                </span>
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Pencatatan resmi tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}.</p>
        </div>
    </div>

    <!-- Prices Grid -->
    @if($prices->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200/80 p-12 text-center shadow-subtle my-6 reveal-blur-spring">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 mx-auto mb-4">
                <i data-lucide="shopping-bag" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-brand-black">Belum Ada Catatan Harga Pangan</h3>
            <p class="text-xs text-gray-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                Tidak ada data komoditas untuk pasar dan tanggal yang dipilih. Silakan coba pilih tanggal lain atau reset filter.
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('smart-city.market-prices', ['market' => $selectedMarket]) }}" class="btn-outline text-xs py-2 px-5 rounded-xl font-bold">
                    Lihat Data Tanggal Terbaru
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($prices as $price)
                @php
                    $categoryTheme = match ($price->category) {
                        'bumbu_dapur' => ['badge' => 'bg-amber-100 text-amber-900 border-amber-300', 'icon' => 'flame', 'border' => 'hover:border-amber-400'],
                        'sembako' => ['badge' => 'bg-yellow-100 text-yellow-900 border-yellow-300', 'icon' => 'package', 'border' => 'hover:border-yellow-400'],
                        'daging_ikan' => ['badge' => 'bg-cyan-100 text-cyan-900 border-cyan-300', 'icon' => 'fish', 'border' => 'hover:border-cyan-400'],
                        'sayur_mayur' => ['badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300', 'icon' => 'leaf', 'border' => 'hover:border-emerald-400'],
                        'telur_susu' => ['badge' => 'bg-rose-100 text-rose-900 border-rose-300', 'icon' => 'egg', 'border' => 'hover:border-rose-400'],
                        default => ['badge' => 'bg-gray-100 text-gray-900 border-gray-300', 'icon' => 'shopping-bag', 'border' => 'hover:border-emerald-400'],
                    };
                @endphp

                <div class="modern-hover-card bg-white rounded-3xl border border-gray-200/90 shadow-subtle hover:shadow-xl {{ $categoryTheme['border'] }} p-5 sm:p-6 flex flex-col justify-between group transition-all duration-300">
                    
                    <div>
                        <!-- Card Top Badges -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-xl shadow-2xs border {{ $categoryTheme['badge'] }} flex items-center gap-1.5">
                                <i data-lucide="{{ $categoryTheme['icon'] }}" class="w-3 h-3"></i>
                                <span>{{ strtoupper(str_replace('_', ' ', $price->category)) }}</span>
                            </span>
                            
                            <span class="text-[11px] font-semibold text-gray-400 flex items-center gap-1">
                                <i data-lucide="store" class="w-3 h-3"></i>
                                <span>{{ $price->market_name }}</span>
                            </span>
                        </div>

                        <!-- Commodity Title -->
                        <h3 class="text-base font-black text-brand-black group-hover:text-emerald-700 transition-colors leading-snug">
                            {{ $price->commodity_name }}
                        </h3>

                        <!-- Big Price Display -->
                        <div class="mt-3.5 flex items-baseline gap-1.5">
                            <span class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">
                                Rp {{ number_format($price->price, 0, ',', '.') }}
                            </span>
                            <span class="text-xs font-bold text-gray-500">/ {{ $price->unit }}</span>
                        </div>

                        <!-- Price Delta / Trend Indicator -->
                        <div class="mt-3">
                            @if($price->price_difference > 0)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-700 text-xs font-bold shadow-2xs">
                                    <i data-lucide="trending-up" class="w-3.5 h-3.5 text-rose-600"></i>
                                    <span>Naik Rp {{ number_format($price->price_difference, 0, ',', '.') }}</span>
                                </div>
                            @elseif($price->price_difference < 0)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-bold shadow-2xs">
                                    <i data-lucide="trending-down" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    <span>Turun Rp {{ number_format(abs($price->price_difference), 0, ',', '.') }}</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-50 border border-gray-200 text-gray-500 text-xs font-medium">
                                    <i data-lucide="minus" class="w-3.5 h-3.5 text-gray-400"></i>
                                    <span>Harga Stabil</span>
                                </div>
                            @endif
                        </div>

                        <!-- Field Notes / Origin -->
                        @if($price->notes)
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-start gap-2 text-xs text-gray-600 bg-gray-50/70 p-2.5 rounded-2xl border border-gray-100">
                                <i data-lucide="info" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <span class="text-[11px] leading-relaxed text-gray-600">{{ $price->notes }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="mt-5 pt-3.5 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                        <span>Pencatatan Harian</span>
                        <button type="button" 
                                onclick="sharePriceInfo('{{ addslashes($price->commodity_name) }}', '{{ number_format($price->price, 0, ',', '.') }}', '{{ $price->unit }}', '{{ $price->market_name }}')" 
                                class="p-1.5 text-gray-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors flex items-center gap-1 font-semibold"
                                title="Bagikan Info Harga">
                            <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                            <span>Bagikan</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    <!-- Civic Price Transparency Notice Box -->
    <div class="mt-8 bg-gradient-to-r from-gray-900 via-slate-900 to-emerald-950 text-white rounded-3xl p-6 sm:p-8 border border-emerald-500/20 shadow-xl reveal-blur-spring">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600/30 text-emerald-300 border border-emerald-500/40 flex items-center justify-center shrink-0">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-white">Transparansi Data & Survei Pasar Kukar</h3>
                    <p class="text-xs text-gray-300 mt-1 max-w-2xl leading-relaxed">
                        Data harga pangan dicatat secara berkala langsung dari pedagang di Pasar Tangga Arung & Pasar Mangkurawang Tenggarong untuk perlindungan konsumen dan stabilitas ketahanan pangan daerah.
                    </p>
                </div>
            </div>
            <div class="shrink-0 flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold">
                    Terbuka & Akurat
                </span>
            </div>
        </div>
    </div>

</div>

<!-- Interactive Category Drag & Scroll + Share Script -->
<script>
function sharePriceInfo(commodity, price, unit, market) {
    const text = `Update Harga Pangan Kukar:\n${commodity}: Rp ${price} / ${unit}\nLokasi: ${market}\nSumber: Habar Etam Smart City`;
    if (navigator.share) {
        navigator.share({
            title: 'Info Harga ' + commodity + ' — Habar Etam',
            text: text,
            url: window.location.href
        }).catch(() => {});
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            if (typeof window.showToast === 'function') {
                window.showToast('success', 'Info harga ' + commodity + ' berhasil disalin!');
            } else {
                alert('Info harga berhasil disalin!');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('mktCatScrollContainer');
    const btnLeft = document.getElementById('mktCatScrollLeft');
    const btnRight = document.getElementById('mktCatScrollRight');

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
