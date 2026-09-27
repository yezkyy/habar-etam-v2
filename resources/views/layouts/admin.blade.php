<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin CMS') — {{ \App\Models\SystemSetting::get('site_name', 'Habar Etam') }} Redaksi</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logo-habar-etam.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @php
    $isProduction = app()->environment('production');
    $manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
    @endphp

    @if ($isProduction && file_exists($manifestPath))
    @php
    $manifest = json_decode(file_get_contents($manifestPath), true);
    @endphp
    <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
    <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @else
    @viteReactRefresh
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @endif
    @stack('styles')
</head>

<body class="bg-gray-100 text-brand-black min-h-screen flex font-sans antialiased selection:bg-brand-gold selection:text-black">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-overlay" onclick="toggleAdminSidebar()" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300"></div>

    @php
    $pendingVerifCount = \App\Models\Verification::where('status', 'pending')->count();
    $activeReportsCount = \App\Models\Report::whereIn('status', ['pending_verification', 'processing_editorial'])->count();
    $pendingUgcCount = (\App\Models\JobVacancy::where('status', 'pending')->count())
    + (\App\Models\Business::where('status', 'pending')->count())
    + (\App\Models\CulinaryPlace::where('status', 'pending')->count())
    + (\App\Models\Event::where('status', 'pending')->count())
    + (\App\Models\Community::where('status', 'pending')->count())
    + (\App\Models\QuickSale::where('status', 'pending')->count());

    // Helper to check active state for category dropdown auto-expansion
    $isDashboardActive = request()->routeIs(['admin.dashboard', 'admin.activity']);
    $isWargaActive = request()->routeIs(['admin.members.*', 'admin.verifications.*']);
    $isKontenActive = request()->routeIs('admin.moderation.*');
    $isReportsActive = request()->routeIs(['admin.reports.*']);
    $isSmartCityActive = request()->routeIs(['admin.smart-city.*']);
    $isStudioActive = request()->routeIs(['admin.studio.*', 'admin.export.*']);
    $isSystemActive = request()->routeIs(['admin.audit.*', 'admin.settings.*']);
    @endphp

    <!-- Admin Left Sidebar (Fixed / Sticky High-End Dark Gold Theme) -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0c1017] border-r border-white/10 text-white flex flex-col transition-all duration-300 transform -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen shrink-0 shadow-2xl">

        <!-- 1. Sidebar Brand Header -->
        <div class="h-20 flex items-center justify-between px-5 border-b border-white/10 bg-[#0c1017]/95 backdrop-blur-md shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('assets/logo-habar-etam.png') }}" alt="Habar Etam" class="h-9 w-auto brightness-110 group-hover:scale-105 transition-transform">
                <div class="leading-none">
                    <div class="flex items-center gap-1.5">
                        <span class="block text-xs font-black text-white tracking-wider">CMS REDAKSI</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="Sistem Aktif"></span>
                    </div>
                    <span class="text-[10px] text-brand-gold font-bold uppercase tracking-widest mt-0.5 block">Habar Etam Kukar</span>
                </div>
            </a>

            <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- 2. Sidebar Menu Quick Search Filter -->
        <div class="px-4 pt-3 pb-2 shrink-0">
            <div class="relative flex items-center">
                <i data-lucide="search" class="w-3.5 h-3.5 text-brand-gold absolute left-3 pointer-events-none transition-colors"></i>
                <input type="text"
                    id="sidebar-search-input"
                    oninput="filterSidebarMenu(this.value)"
                    placeholder="Cari menu (Ctrl+K)..."
                    autocomplete="off"
                    spellcheck="false"
                    class="sidebar-search-input w-full text-xs text-white placeholder-gray-400">
                <button type="button"
                    id="sidebar-search-clear"
                    onclick="clearSidebarSearch()"
                    class="hidden absolute right-2.5 p-1 rounded-md text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </button>
            </div>
        </div>

        <!-- 3. Sidebar Navigation Accordion List -->
        <div class="flex-1 overflow-y-auto py-2 px-3 space-y-2 text-xs font-semibold custom-scrollbar" id="sidebar-accordion-container">

            <!-- CATEGORY 1: DASHBOARD -->
            <div class="sidebar-category-group" data-category="dashboard">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-dashboard')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isDashboardActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isDashboardActive ? 'bg-brand-gold text-brand-black' : 'bg-white/5 text-gray-300 group-hover:bg-white/10 group-hover:text-white' }} transition-colors">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        </div>
                        <span>Dashboard</span>
                    </div>
                    <i data-lucide="chevron-down" id="chevron-cat-dashboard" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isDashboardActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                </button>

                <div id="cat-dashboard" class="sidebar-accordion-content {{ $isDashboardActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i>
                        <span>Dashboard Utama</span>
                    </a>
                    <a href="{{ route('admin.activity') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.activity') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                        <span>Aktivitas Terbaru</span>
                    </a>
                </div>
            </div>

            <!-- CATEGORY 2: WARGA & NIK VERIFIKASI -->
            <div class="sidebar-category-group" data-category="warga">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-warga')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isWargaActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isWargaActive ? 'bg-brand-gold text-brand-black' : 'bg-white/5 text-gray-300 group-hover:bg-white/10 group-hover:text-white' }} transition-colors">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <span>Warga & Verifikasi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($pendingVerifCount > 0)
                        <span class="px-1.5 py-0.5 rounded-md bg-amber-500 text-black font-extrabold text-[10px] animate-pulse">{{ $pendingVerifCount }}</span>
                        @endif
                        <i data-lucide="chevron-down" id="chevron-cat-warga" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isWargaActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                    </div>
                </button>

                <div id="cat-warga" class="sidebar-accordion-content {{ $isWargaActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.members.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.members.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        <span>Data Member Warga</span>
                    </a>
                    <a href="{{ route('admin.verifications.index') }}"
                        class="sidebar-nav-item flex items-center justify-between px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.verifications.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                            <span>Verifikasi NIK Warga</span>
                        </div>
                        @if($pendingVerifCount > 0)
                        <span class="px-1.5 py-0.2 rounded bg-amber-400 text-black text-[9px] font-black">{{ $pendingVerifCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- CATEGORY 3: KONTEN WARGA & MODERASI -->
            <div class="sidebar-category-group" data-category="konten">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-konten')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isKontenActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isKontenActive ? 'bg-brand-gold text-brand-black' : 'bg-white/5 text-gray-300 group-hover:bg-white/10 group-hover:text-white' }} transition-colors">
                            <i data-lucide="check-square" class="w-4 h-4"></i>
                        </div>
                        <span>Konten & Moderasi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($pendingUgcCount > 0)
                        <span class="px-1.5 py-0.5 rounded-md bg-blue-500 text-white font-extrabold text-[10px]">{{ $pendingUgcCount }}</span>
                        @endif
                        <i data-lucide="chevron-down" id="chevron-cat-konten" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isKontenActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                    </div>
                </button>

                <div id="cat-konten" class="sidebar-accordion-content {{ $isKontenActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.moderation.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.moderation.*') && (!request('type') || request('type') === 'all') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="inbox" class="w-3.5 h-3.5"></i>
                        <span>Semua Submission (Inbox)</span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['type' => 'job']) }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request('type') === 'job' ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-400"></i>
                        <span>Bursa Kerja</span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['type' => 'business']) }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request('type') === 'business' ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>Produk & UMKM</span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['type' => 'culinary']) }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request('type') === 'culinary' ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="utensils" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Kuliner Khas</span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['type' => 'event']) }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request('type') === 'event' ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-400"></i>
                        <span>Event & Agenda</span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['type' => 'community']) }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request('type') === 'community' ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="users-2" class="w-3.5 h-3.5 text-indigo-400"></i>
                        <span>Klub & Komunitas</span>
                    </a>
                    <a href="{{ route('admin.moderation.index', ['type' => 'quick_sale']) }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request('type') === 'quick_sale' ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Jual Cepat Warga</span>
                    </a>
                </div>
            </div>

            <!-- CATEGORY 4: LAPOR ETAM -->
            <div class="sidebar-category-group" data-category="reports">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-reports')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isReportsActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isReportsActive ? 'bg-rose-600 text-white' : 'bg-white/5 text-rose-400 group-hover:bg-rose-600/20' }} transition-colors">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                        </div>
                        <span>Lapor Etam</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($activeReportsCount > 0)
                        <span class="px-1.5 py-0.5 rounded-md bg-rose-600 text-white font-extrabold text-[10px] animate-pulse">{{ $activeReportsCount }}</span>
                        @endif
                        <i data-lucide="chevron-down" id="chevron-cat-reports" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isReportsActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                    </div>
                </button>

                <div id="cat-reports" class="sidebar-accordion-content {{ $isReportsActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.reports.index') }}"
                        class="sidebar-nav-item flex items-center justify-between px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.reports.index') || request()->routeIs('admin.reports.show') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <div class="flex items-center gap-2">
                            <i data-lucide="list-filter" class="w-3.5 h-3.5"></i>
                            <span>Daftar Pengaduan</span>
                        </div>
                        @if($activeReportsCount > 0)
                        <span class="px-1.5 py-0.2 rounded bg-rose-500 text-white text-[9px] font-black">{{ $activeReportsCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.reports.map') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.reports.map') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="map" class="w-3.5 h-3.5 text-cyan-400"></i>
                        <span>Peta GIS Pengaduan</span>
                    </a>
                </div>
            </div>

            <!-- CATEGORY 5: SMART CITY KUKAR -->
            <div class="sidebar-category-group" data-category="smartcity">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-smartcity')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isSmartCityActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isSmartCityActive ? 'bg-brand-gold text-brand-black' : 'bg-white/5 text-gray-300 group-hover:bg-white/10 group-hover:text-white' }} transition-colors">
                            <i data-lucide="landmark" class="w-4 h-4"></i>
                        </div>
                        <span>Smart City Kukar</span>
                    </div>
                    <i data-lucide="chevron-down" id="chevron-cat-smartcity" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isSmartCityActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                </button>

                <div id="cat-smartcity" class="sidebar-accordion-content {{ $isSmartCityActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.smart-city.emergency.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.smart-city.emergency.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="phone-call" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Kontak Darurat 24 Jam</span>
                    </a>
                    <a href="{{ route('admin.smart-city.prices.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.smart-city.prices.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5 text-cyan-400"></i>
                        <span>Harga Pangan Pasar</span>
                    </a>
                    <a href="{{ route('admin.smart-city.environment.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.smart-city.environment.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="waves" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span>Lingkungan & TMA Mahakam</span>
                    </a>
                    <a href="{{ route('admin.smart-city.culture.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all {{ request()->routeIs('admin.smart-city.culture.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="building" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>Budaya & Cagar Wisata</span>
                    </a>
                </div>
            </div>

            <!-- CATEGORY 6: REDAKSI & STUDIO SCM -->
            <div class="sidebar-category-group" data-category="studio">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-studio')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isStudioActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isStudioActive ? 'bg-brand-gold text-brand-black' : 'bg-white/5 text-gray-300 group-hover:bg-white/10 group-hover:text-white' }} transition-colors">
                            <i data-lucide="tv" class="w-4 h-4"></i>
                        </div>
                        <span>Redaksi & Studio</span>
                    </div>
                    <i data-lucide="chevron-down" id="chevron-cat-studio" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isStudioActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                </button>

                <div id="cat-studio" class="sidebar-accordion-content {{ $isStudioActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.studio.feed') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.studio.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="radio" class="w-3.5 h-3.5 text-purple-400"></i>
                        <span>Feed Studio Live SCM</span>
                    </a>
                    <a href="{{ route('admin.export.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.export.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="download" class="w-3.5 h-3.5 text-blue-400"></i>
                        <span>Export Data & Laporan</span>
                    </a>
                </div>
            </div>

            <!-- CATEGORY 7: SISTEM & AUDIT -->
            <div class="sidebar-category-group" data-category="system">
                <button type="button"
                    onclick="toggleSidebarCategory('cat-system')"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl transition-all {{ $isSystemActive ? 'bg-white/10 text-brand-gold font-black' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isSystemActive ? 'bg-brand-gold text-brand-black' : 'bg-white/5 text-gray-300 group-hover:bg-white/10 group-hover:text-white' }} transition-colors">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                        </div>
                        <span>Sistem & Audit</span>
                    </div>
                    <i data-lucide="chevron-down" id="chevron-cat-system" class="w-4 h-4 text-gray-400 transition-transform duration-200 {{ $isSystemActive ? 'rotate-180 text-brand-gold' : '' }}"></i>
                </button>

                <div id="cat-system" class="sidebar-accordion-content {{ $isSystemActive ? 'is-open' : '' }} pl-9 pr-1 pt-1 space-y-1">
                    <a href="{{ route('admin.audit.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.audit.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                        <span>Audit Log Aktivitas</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="sidebar-nav-item flex items-center gap-2 px-3 py-2 rounded-lg transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-brand-gold text-brand-black font-bold shadow-xs' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- 4. Sidebar Bottom Profile Card & Logout Action -->
        <div class="p-3.5 border-t border-white/10 bg-[#080b10] shrink-0">
            <div class="flex items-center justify-between p-2 rounded-2xl bg-white/5 border border-white/10">
                <div class="flex items-center gap-2.5 truncate">
                    <div class="w-8 h-8 rounded-xl bg-brand-gold text-brand-black font-black flex items-center justify-center text-xs shadow-xs shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <span class="block text-xs font-bold text-white truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-brand-gold/90 font-semibold block">Redaksi Administrator</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Keluar dari Panel Admin">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Admin Content Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Sticky Top Workspace Bar -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200/90 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-xs">

            <!-- Left: Mobile Trigger & Breadcrumb -->
            <div class="flex items-center gap-3 sm:gap-4">
                <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors" aria-label="Buka Menu Sidebar">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-400 font-semibold">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Admin</a>
                        <span>/</span>
                        <span class="text-gray-900 font-bold">@yield('page_title', 'Panel Kontrol')</span>
                    </div>
                </div>
            </div>

            <!-- Right: Quick Actions & Indicators -->
            <div class="flex items-center gap-2.5 sm:gap-3.5 text-xs font-semibold">

                <!-- Quick Public Link -->
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 hover:border-brand-gold bg-gray-50 hover:bg-amber-50/50 text-gray-700 hover:text-black transition-all shadow-2xs font-bold">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                    <span class="hidden sm:inline">Website Publik</span>
                </a>

                <!-- Notification Quick Stack -->
                @if($pendingVerifCount > 0 || $activeReportsCount > 0)
                <div class="hidden sm:flex items-center gap-2">
                    @if($pendingVerifCount > 0)
                    <a href="{{ route('admin.verifications.index') }}" class="px-2.5 py-1 rounded-xl bg-amber-100 border border-amber-300 text-amber-900 text-[11px] font-bold flex items-center gap-1 hover:bg-amber-200 transition-colors">
                        <i data-lucide="shield-alert" class="w-3 h-3 text-amber-700"></i>
                        <span>{{ $pendingVerifCount }} NIK</span>
                    </a>
                    @endif
                    @if($activeReportsCount > 0)
                    <a href="{{ route('admin.reports.index') }}" class="px-2.5 py-1 rounded-xl bg-rose-100 border border-rose-300 text-rose-900 text-[11px] font-bold flex items-center gap-1 hover:bg-rose-200 transition-colors">
                        <i data-lucide="megaphone" class="w-3 h-3 text-rose-700"></i>
                        <span>{{ $activeReportsCount }} Lapor</span>
                    </a>
                    @endif
                </div>
                @endif

                <!-- User Dropdown Pill -->
                <div class="flex items-center gap-2 pl-2 border-l border-gray-200">
                    <div class="w-7 h-7 rounded-xl bg-brand-black text-brand-gold flex items-center justify-center font-black text-xs shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden md:inline font-bold text-gray-900 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Admin Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50/70">
            <div class="max-w-7xl mx-auto space-y-6">
                @include('partials.alert')
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Sidebar Accordion Dropdown Engine
        function toggleSidebarCategory(categoryId) {
            const content = document.getElementById(categoryId);
            const chevron = document.getElementById('chevron-' + categoryId);
            if (!content) return;

            const isOpen = content.classList.contains('is-open');

            if (isOpen) {
                content.classList.remove('is-open');
                if (chevron) chevron.classList.remove('rotate-180', 'text-brand-gold');
            } else {
                content.classList.add('is-open');
                if (chevron) chevron.classList.add('rotate-180', 'text-brand-gold');
            }
        }
        window.toggleSidebarCategory = toggleSidebarCategory;

        // Mobile Sidebar Slide-in Drawer Toggle
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');

            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                if (overlay) overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }
        }
        window.toggleAdminSidebar = toggleAdminSidebar;

        // Instant In-Sidebar Menu Quick Filter
        function filterSidebarMenu(query) {
            const q = query.toLowerCase().trim();
            const groups = document.querySelectorAll('.sidebar-category-group');
            const clearBtn = document.getElementById('sidebar-search-clear');
            let totalMatches = 0;

            if (clearBtn) {
                if (q) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            groups.forEach(group => {
                const items = group.querySelectorAll('.sidebar-nav-item');
                let hasMatch = false;

                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (!q || text.includes(q)) {
                        item.style.display = 'flex';
                        hasMatch = true;
                        totalMatches++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                const content = group.querySelector('.sidebar-accordion-content');
                const chevron = group.querySelector('[id^="chevron-"]');

                if (q) {
                    if (hasMatch) {
                        group.style.display = 'block';
                        if (content) content.classList.add('is-open');
                        if (chevron) chevron.classList.add('rotate-180', 'text-brand-gold');
                    } else {
                        group.style.display = 'none';
                    }
                } else {
                    group.style.display = 'block';
                    items.forEach(item => item.style.display = 'flex');
                }
            });

            // Empty state feedback
            let emptyState = document.getElementById('sidebar-search-empty');
            const container = document.getElementById('sidebar-accordion-container');
            if (q && totalMatches === 0) {
                if (!emptyState && container) {
                    emptyState = document.createElement('div');
                    emptyState.id = 'sidebar-search-empty';
                    emptyState.className = 'py-8 px-4 text-center text-gray-400 text-xs flex flex-col items-center gap-2';
                    emptyState.innerHTML = '<span class="font-bold text-gray-300">Menu tidak ditemukan</span><span class="text-[11px] text-gray-500">Coba kata kunci lain</span>';
                    container.appendChild(emptyState);
                }
            } else if (emptyState) {
                emptyState.remove();
            }
        }
        window.filterSidebarMenu = filterSidebarMenu;

        function clearSidebarSearch() {
            const input = document.getElementById('sidebar-search-input');
            if (input) {
                input.value = '';
                filterSidebarMenu('');
                input.focus();
            }
        }
        window.clearSidebarSearch = clearSidebarSearch;

        // Global Shortcut Ctrl+K / Cmd+K to focus sidebar search
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('sidebar-search-input');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });

        // Ensure Lucide icons are initialized across all loaded admin views
        if (window.initIcons) {
            window.initIcons();
        } else if (window.lucide && window.lucide.createIcons) {
            window.lucide.createIcons();
        }
    </script>
    @stack('scripts')
</body>

</html>