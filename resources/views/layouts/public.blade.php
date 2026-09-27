<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @php
    $siteName = \App\Models\SystemSetting::get('site_name', 'Habar Etam');
    $siteTagline = \App\Models\SystemSetting::get('site_tagline', 'Portal Informasi & Kontribusi Warga Tenggarong - Kutai Kartanegara');
    $siteDescription = \App\Models\SystemSetting::get('site_description', 'Portal informasi lokal terpercaya untuk warga Tenggarong dan Kutai Kartanegara. Pusat lowongan kerja lokal, direktori UMKM, kuliner khas Kutai, agenda event, jual cepat, dan pengaduan Lapor Etam.');
    @endphp

    <title>@yield('title', $siteName . ' — ' . $siteTagline)</title>
    <meta name="description" content="@yield('meta_description', $siteDescription)">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Social Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', $siteName . ' — ' . $siteTagline)">
    <meta property="og:description" content="@yield('meta_description', $siteDescription)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:image" content="@yield('meta_image', asset('assets/hero-kukar.webp'))">

    <!-- Favicon & Brand Icons -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-habar-etam.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS and JS via Vite -->
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

<body class="bg-kukar-bg text-brand-black min-h-screen flex flex-col font-sans selection:bg-brand-gold selection:text-brand-black">

    <!-- Floating Island & Capsule Pill Navbar (Fixed Floating Overlay) -->
    <header id="main-navbar-header" class="fixed top-0 inset-x-0 z-50 py-4 px-4 sm:px-6 lg:px-8 bg-transparent pointer-events-none navbar-autohide">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 pointer-events-auto">

            <!-- Left Island: Location & Official Brand -->
            <div class="bg-[#F8F4EB] border border-[#E9DFCF] rounded-2xl sm:rounded-3xl px-4 py-2.5 flex items-center gap-3 shadow-xs hover:shadow-sm transition-all shrink-0">

                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('assets/logo-habar-etam.png') }}" alt="Habar Etam" class="h-8 sm:h-9 w-auto object-contain transition-transform group-hover:scale-105">
                    <div class="hidden sm:block border-l border-[#DCD0BE] pl-2.5 text-left">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-[#8C7A60] block leading-none">Tenggarong</span>
                        <span class="text-[11px] font-black text-brand-black block leading-tight">Kutai Kartanegara</span>
                    </div>
                </a>
            </div>

            <!-- Center/Right: Floating Capsule Pill Menu Container -->
            <nav class="hidden lg:flex items-center bg-[#F8F4EB] border border-[#E9DFCF] rounded-full px-4 py-1.5 shadow-xs relative">
                <div class="flex items-center space-x-1 text-xs font-bold text-gray-800">

                    <!-- 1. Beranda -->
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-full transition-all {{ request()->routeIs('home') ? 'bg-gold-500 text-black font-extrabold shadow-xs' : 'hover:text-black hover:bg-black/5' }}">
                        Beranda
                    </a>

                    <!-- 2. Warga & Usaha (Dropdown on Click) -->
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('dropdown-warga', event)" class="dropdown-trigger px-3.5 py-2 rounded-full transition-all inline-flex items-center gap-1 {{ request()->routeIs(['quick-sales.*', 'jobs.*', 'businesses.*', 'culinary.*']) ? 'bg-gold-500 text-black font-extrabold shadow-xs' : 'hover:text-black hover:bg-black/5 text-gray-800' }}">
                            <span>Warga & Usaha</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 dropdown-icon transition-transform duration-200"></i>
                        </button>

                        <div id="dropdown-warga" class="custom-dropdown dropdown-splash absolute left-0 mt-3 w-72 bg-white border border-[#E9DFCF] rounded-2xl shadow-2xl ring-1 ring-black/5 p-2 z-50">
                            <a href="{{ route('quick-sales.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="tag" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-extrabold text-gray-900 group-hover:text-gold-600">Jual Cepat</span>
                                        <span class="text-[9px] font-black px-1.5 py-0.2 rounded-full bg-amber-400 text-black uppercase">Kilat</span>
                                    </div>
                                    <span class="text-[11px] text-gray-500 block font-normal">Jual beli barang cepat antar warga</span>
                                </div>
                            </a>

                            <a href="{{ route('jobs.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Bursa Kerja</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Lowongan karir & pekerjaan lokal</span>
                                </div>
                            </a>

                            <a href="{{ route('businesses.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="store" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Produk & UMKM</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Direktori bisnis & jasa warga Kukar</span>
                                </div>
                            </a>

                            <a href="{{ route('culinary.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="utensils" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Kuliner Khas</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Warung makan, cafe & santapan lokal</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 3. Agenda & Komunitas (Dropdown on Click) -->
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('dropdown-agenda', event)" class="dropdown-trigger px-3.5 py-2 rounded-full transition-all inline-flex items-center gap-1 {{ request()->routeIs(['events.*', 'communities.*']) ? 'bg-gold-500 text-black font-extrabold shadow-xs' : 'hover:text-black hover:bg-black/5 text-gray-800' }}">
                            <span>Agenda & Komunitas</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 dropdown-icon transition-transform duration-200"></i>
                        </button>

                        <div id="dropdown-agenda" class="custom-dropdown dropdown-splash absolute left-0 mt-3 w-64 bg-white border border-[#E9DFCF] rounded-2xl shadow-2xl ring-1 ring-black/5 p-2 z-50">
                            <a href="{{ route('events.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Event & Kegiatan</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Jadwal acara, festival & olahraga</span>
                                </div>
                            </a>

                            <a href="{{ route('communities.index') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Klub & Komunitas</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Wadah minat, hobi & pemuda</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 4. Smart City (Dropdown on Click) -->
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('dropdown-smartcity', event)" class="dropdown-trigger px-3.5 py-2 rounded-full transition-all inline-flex items-center gap-1 {{ request()->routeIs('smart-city.*') ? 'bg-gold-500 text-black font-extrabold shadow-xs' : 'hover:text-black hover:bg-black/5 text-gray-800' }}">
                            <span>Smart City</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 dropdown-icon transition-transform duration-200"></i>
                        </button>

                        <div id="dropdown-smartcity" class="custom-dropdown dropdown-splash absolute left-0 mt-3 w-72 bg-white border border-[#E9DFCF] rounded-2xl shadow-2xl ring-1 ring-black/5 p-2 z-50">
                            <a href="{{ route('smart-city.emergency') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="phone-call" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Kontak Darurat 24 Jam</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Damkar, Polisi, Medis & BPBD</span>
                                </div>
                            </a>

                            <a href="{{ route('smart-city.market-prices') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Harga Pangan & Pasar</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Pantauan sembako pasar Kukar</span>
                                </div>
                            </a>

                            <a href="{{ route('smart-city.environment') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="waves" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Lingkungan & Air Mahakam</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">TMA Sungai & sensor cuaca</span>
                                </div>
                            </a>

                            <a href="{{ route('smart-city.culture') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F8F4EB] transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i data-lucide="landmark" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold text-gray-900 block group-hover:text-gold-600">Budaya & Pariwisata</span>
                                    <span class="text-[11px] text-gray-500 block font-normal">Museum, cagar sejarah & wisata</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 5. Lapor Etam (Civic Grievance Button) -->
                    <a href="{{ route('reports.index') }}" class="px-3.5 py-2 rounded-full transition-all text-xs font-extrabold flex items-center gap-1.5 {{ request()->routeIs('reports.*') ? 'bg-rose-600 text-white shadow-xs' : 'text-rose-700 bg-rose-100/70 hover:bg-rose-200/80' }}">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Lapor Etam</span>
                    </a>

                </div>

                <div class="h-5 w-px bg-[#DCD0BE] mx-3"></div>

                <!-- Right Pill: Auth & Citizen Portal -->
                <div class="flex items-center space-x-2">
                    @auth
                    <!-- Authenticated User Menu (Dropdown on Click) -->
                    <div class="relative">
                        <button type="button" onclick="toggleDropdown('dropdown-user', event)" class="dropdown-trigger flex items-center gap-2 pl-1.5 pr-3 py-1 rounded-full bg-black-soft hover:bg-black text-white text-xs font-bold transition-all shadow-xs">
                            <div class="w-6 h-6 rounded-full bg-gold-500 text-black flex items-center justify-center font-black text-[11px]">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[90px] truncate">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isVerified())
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                            @endif
                            <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 dropdown-icon transition-transform duration-200"></i>
                        </button>

                        <div id="dropdown-user" class="custom-dropdown dropdown-splash dropdown-right absolute right-0 mt-3 w-56 bg-white border border-[#E9DFCF] rounded-2xl shadow-2xl ring-1 ring-black/5 py-2 z-50">

                            <div class="px-4 py-2 border-b border-gray-100">
                                <span class="text-xs font-bold text-gray-900 block truncate">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-gray-400 block truncate">{{ auth()->user()->email }}</span>
                            </div>
                            <a href="{{ route('member.profile') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-[#F8F4EB] hover:text-gold-700">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i> Profil & NIK Warga
                            </a>
                            <a href="{{ route('reports.my-reports') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-[#F8F4EB] hover:text-gold-700">
                                <i data-lucide="clipboard-list" class="w-4 h-4 text-gray-400"></i> Pengaduan Saya
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 text-gold-600"></i> Dashboard Redaksi
                            </a>
                            @endif
                            <div class="pt-1 mt-1 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 text-left">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-gray-700 hover:text-black px-2.5 py-1.5 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-full bg-gold-500 hover:bg-gold-600 text-black text-xs font-black shadow-xs transition-all inline-flex items-center gap-1">
                        <span>Daftar</span>
                    </a>
                    @endauth
                </div>
            </nav>

            <!-- Mobile Right Action Area -->
            <div class="lg:hidden flex items-center gap-2">
                @auth
                <a href="{{ route('member.profile') }}" class="w-10 h-10 rounded-2xl bg-[#F8F4EB] border border-[#E9DFCF] text-black flex items-center justify-center font-bold text-xs shadow-xs" title="Profil Warga">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
                @else
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-2xl bg-[#F8F4EB] border border-[#E9DFCF] text-xs font-bold text-gray-800 hover:bg-[#EDE5D8] transition-colors">
                    Masuk
                </a>
                @endauth

                <button type="button" id="mobile-menu-btn" onclick="toggleMobileMenu()" class="w-10 h-10 rounded-2xl bg-[#F8F4EB] border border-[#E9DFCF] text-gray-800 flex items-center justify-center hover:bg-[#EDE5D8] focus:outline-none transition-colors shadow-xs" aria-label="Buka Menu" aria-expanded="false">
                    <i data-lucide="menu" class="w-5 h-5 menu-open-icon"></i>
                    <i data-lucide="x" class="w-5 h-5 menu-close-icon hidden"></i>
                </button>
            </div>

        </div>

        <!-- Mobile Drawer Backdrop Overlay -->
        <div id="mobile-backdrop" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-40 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300"></div>

        <!-- Mobile Drawer Navigation (Categorized) -->
        <div id="mobile-menu" class="mobile-drawer-splash lg:hidden mt-3 max-w-7xl mx-auto bg-[#F8F4EB] border border-[#E9DFCF] rounded-3xl p-4 sm:p-5 space-y-4 shadow-2xl pointer-events-auto max-h-[calc(100vh-6.5rem)] overflow-y-auto overscroll-contain">

            <!-- Navigation Categories Accordion / Lists -->
            <div class="space-y-3">
                <a href="{{ route('home') }}" class="p-3 rounded-2xl text-xs font-extrabold flex items-center gap-2.5 {{ request()->routeIs('home') ? 'bg-gold-500 text-black shadow-xs' : 'bg-white text-gray-800 hover:bg-white/80' }}">
                    <i data-lucide="home" class="w-4 h-4"></i> Beranda
                </a>

                <!-- Cat 1: Warga & Usaha -->
                <div class="bg-white rounded-2xl p-3 border border-[#E9DFCF]/60 space-y-2">
                    <div class="text-[10px] font-black uppercase tracking-wider text-gray-400 px-1">Warga & Usaha</div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('quick-sales.index') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-amber-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="tag" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                            <span class="truncate">Jual Cepat</span>
                        </a>
                        <a href="{{ route('jobs.index') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-blue-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i>
                            <span class="truncate">Bursa Kerja</span>
                        </a>
                        <a href="{{ route('businesses.index') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-emerald-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                            <span class="truncate">Produk UMKM</span>
                        </a>
                        <a href="{{ route('culinary.index') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-rose-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="utensils" class="w-3.5 h-3.5 text-rose-600 shrink-0"></i>
                            <span class="truncate">Kuliner Khas</span>
                        </a>
                    </div>
                </div>

                <!-- Cat 2: Agenda & Komunitas -->
                <div class="bg-white rounded-2xl p-3 border border-[#E9DFCF]/60 space-y-2">
                    <div class="text-[10px] font-black uppercase tracking-wider text-gray-400 px-1">Agenda & Komunitas</div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('events.index') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-purple-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-600 shrink-0"></i>
                            <span class="truncate">Event Budaya</span>
                        </a>
                        <a href="{{ route('communities.index') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-indigo-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="users" class="w-3.5 h-3.5 text-indigo-600 shrink-0"></i>
                            <span class="truncate">Komunitas</span>
                        </a>
                    </div>
                </div>

                <!-- Cat 3: Smart City Kukar -->
                <div class="bg-white rounded-2xl p-3 border border-[#E9DFCF]/60 space-y-2">
                    <div class="text-[10px] font-black uppercase tracking-wider text-gray-400 px-1">Smart City Kukar</div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('smart-city.emergency') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-rose-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="phone-call" class="w-3.5 h-3.5 text-rose-500 shrink-0"></i>
                            <span class="truncate">Kontak Darurat</span>
                        </a>
                        <a href="{{ route('smart-city.market-prices') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-cyan-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5 text-cyan-500 shrink-0"></i>
                            <span class="truncate">Harga Pasar</span>
                        </a>
                        <a href="{{ route('smart-city.environment') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-emerald-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="waves" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                            <span class="truncate">Lingkungan & Air</span>
                        </a>
                        <a href="{{ route('smart-city.culture') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-amber-50 text-xs font-bold text-gray-800 flex items-center gap-2 transition-colors">
                            <i data-lucide="landmark" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                            <span class="truncate">Budaya & Wisata</span>
                        </a>
                    </div>
                </div>

                <!-- Cat 4: Lapor Etam -->
                <a href="{{ route('reports.index') }}" class="p-3.5 rounded-2xl text-xs font-extrabold flex items-center justify-between bg-rose-600 text-white shadow-md hover:bg-rose-700 transition-colors">
                    <span class="flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i> Saluran Pengaduan Lapor Etam
                    </span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Citizen Actions in Drawer -->
            <div class="pt-3 border-t border-[#DCD0BE] flex flex-col gap-2">
                @auth
                <a href="{{ route('member.profile') }}" class="w-full py-2.5 px-4 rounded-2xl bg-black-soft text-white text-xs font-bold text-center inline-flex items-center justify-center gap-2 hover:bg-black transition-colors">
                    <i data-lucide="user" class="w-4 h-4 text-gold-500"></i>
                    <span>Profil & NIK Warga Saya</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="w-full py-2.5 px-4 rounded-2xl bg-gold-500 text-black text-xs font-black text-center inline-flex items-center justify-center gap-2 hover:bg-gold-600 transition-colors">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Panel CMS Redaksi</span>
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 text-center text-xs font-bold text-rose-600 hover:bg-rose-100 rounded-xl transition-colors">
                        Keluar dari Akun
                    </button>
                </form>
                @else
                <a href="{{ route('register') }}" class="w-full py-2.5 rounded-2xl bg-gold-500 text-black text-xs font-black text-center shadow-xs hover:bg-gold-600 transition-colors">
                    Daftar Sebagai Warga Kukar
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Dynamic Content (Optimized with responsive padding for mobile bottom bar) -->
    <main class="flex-grow pb-24 lg:pb-0 {{ request()->routeIs('home') ? '' : 'pt-24 sm:pt-28' }}">
        @yield('content')
    </main>


    <!-- Hyperlocal Footer -->
    <footer class="bg-brand-black text-white border-t border-brand-black-soft mt-16 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-12 border-b border-gray-800">

                <!-- Brand Info -->
                <div class="lg:col-span-2 space-y-4">
                    <img src="{{ asset('assets/logo-habar-etam.png') }}" alt="{{ \App\Models\SystemSetting::get('site_name', 'Habar Etam') }}" class="h-10 w-auto brightness-110">
                    <p class="text-xs leading-relaxed text-gray-400 pr-6">
                        {{ \App\Models\SystemSetting::get('site_description', 'Habar Etam adalah portal informasi lokal dan platform kontribusi warga Tenggarong & Kutai Kartanegara. Menyajikan kabar terverifikasi, bursa kerja lokal, direktori usaha, jual cepat, dan saluran pengaduan Lapor Etam.') }}
                    </p>
                    <div class="text-xs text-gray-400 space-y-1">
                        <p class="flex items-center space-x-2">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-gold flex-shrink-0"></i>
                            <span>{{ \App\Models\SystemSetting::get('office_address', 'Jl. Wolter Monginsidi No. 12, Tenggarong, Kutai Kartanegara') }}</span>
                        </p>
                        <p class="flex items-center space-x-2">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-brand-gold flex-shrink-0"></i>
                            <span>{{ \App\Models\SystemSetting::get('contact_email', 'redaksi@habaretam.id') }}</span>
                        </p>
                        @if(\App\Models\SystemSetting::get('contact_phone'))
                        <p class="flex items-center space-x-2">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-brand-gold flex-shrink-0"></i>
                            <span>{{ \App\Models\SystemSetting::get('contact_phone', '(0541) 661-098') }}</span>
                        </p>
                        @endif
                        <p class="flex items-center space-x-2">
                            <i data-lucide="tv" class="w-3.5 h-3.5 text-brand-gold flex-shrink-0"></i>
                            <span>Mitra Redaksi: {{ \App\Models\SystemSetting::get('studio_partner_name', 'PT Surya Citra Media (SCM) Studio Hub') }}</span>
                        </p>
                    </div>
                </div>

                <!-- UGC & Warga -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-brand-gold mb-3">Kontribusi Warga</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><a href="{{ route('quick-sales.index') }}" class="hover:text-white transition-colors">Jual Cepat Warga</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">Bursa Kerja Lokal</a></li>
                        <li><a href="{{ route('businesses.index') }}" class="hover:text-white transition-colors">Produk & UMKM Kukar</a></li>
                        <li><a href="{{ route('culinary.index') }}" class="hover:text-white transition-colors">Kuliner Khas & Cafe</a></li>
                        <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Event & Agenda Budaya</a></li>
                        <li><a href="{{ route('communities.index') }}" class="hover:text-white transition-colors">Klub & Komunitas</a></li>
                    </ul>
                </div>

                <!-- Smart City -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-brand-gold mb-3">Smart City Kukar</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><a href="{{ route('smart-city.emergency') }}" class="hover:text-white transition-colors">Kontak Darurat 24 Jam</a></li>
                        <li><a href="{{ route('smart-city.market-prices') }}" class="hover:text-white transition-colors">Harga Pangan Tangga Arung</a></li>
                        <li><a href="{{ route('smart-city.environment') }}" class="hover:text-white transition-colors">Tinggi Muka Air Mahakam</a></li>
                        <li><a href="{{ route('smart-city.culture') }}" class="hover:text-white transition-colors">Kesultanan & Museum Mulawarman</a></li>
                        <li><a href="{{ route('reports.index') }}" class="hover:text-white transition-colors font-semibold text-red-400">Pengaduan Lapor Etam</a></li>
                    </ul>
                </div>

                <!-- Partisipasi -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-brand-gold mb-3">Akses Warga</h4>
                    <p class="text-xs text-gray-400 mb-3">
                        Daftar menggunakan NIK wilayah Kutai Kartanegara untuk mendapatkan status Warga Terverifikasi.
                    </p>
                    @guest
                    <a href="{{ route('register') }}" class="btn-gold text-xs py-2 w-full text-center block mb-2">Daftar Warga Baru</a>
                    <a href="{{ route('login') }}" class="btn-outline text-xs py-2 w-full text-center block bg-gray-800 text-white border-gray-700 hover:bg-gray-700">Masuk Akun</a>
                    @else
                    <a href="{{ route('member.profile') }}" class="btn-gold text-xs py-2 w-full text-center block">Profil Warga Saya</a>
                    @endguest
                </div>

            </div>

            <!-- Bottom Copyright & Disclaimer -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-3">
                <p>&copy; {{ date('Y') }} Habar Etam. Hak Cipta Dilindungi Undang-Undang. Tenggarong, Kutai Kartanegara.</p>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('sitemap') }}" class="hover:text-gray-400">Sitemap XML</a>
                    <a href="{{ route('robots') }}" class="hover:text-gray-400">Robots.txt</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Floating Capsule Bar (Visible on Mobile & Tablet < lg) -->
    <nav class="lg:hidden fixed bottom-3 inset-x-3 sm:inset-x-8 z-40 pointer-events-auto">
        <div class="bg-[#181a20]/95 backdrop-blur-xl border border-white/15 text-white shadow-2xl rounded-2xl sm:rounded-3xl p-1.5 sm:p-2 flex items-center justify-around ring-1 ring-black/20">

            <!-- 1. Beranda -->
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center py-1.5 px-2.5 rounded-xl transition-all {{ request()->routeIs('home') ? 'text-brand-gold font-black bg-white/10' : 'text-gray-400 hover:text-white' }}">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold tracking-tight mt-0.5">Beranda</span>
            </a>

            <!-- 2. Jual Cepat -->
            <a href="{{ route('quick-sales.index') }}" class="flex flex-col items-center justify-center py-1.5 px-2.5 rounded-xl transition-all {{ request()->routeIs('quick-sales.*') ? 'text-brand-gold font-black bg-white/10' : 'text-gray-400 hover:text-white' }}">
                <i data-lucide="tag" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold tracking-tight mt-0.5">Jual Cepat</span>
            </a>

            <!-- 3. Lapor Etam (Center Action Pill) -->
            <a href="{{ route('reports.index') }}" class="flex flex-col items-center justify-center -mt-5 relative group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 text-white flex items-center justify-center shadow-lg shadow-rose-600/40 border-2 border-[#181a20] group-hover:scale-110 transition-transform {{ request()->routeIs('reports.*') ? 'ring-2 ring-rose-400 ring-offset-2 ring-offset-black' : '' }}">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <span class="text-[9px] font-black tracking-tight mt-1 text-rose-400">Lapor</span>
            </a>

            <!-- 4. Smart City / Agenda -->
            <a href="{{ route('events.index') }}" class="flex flex-col items-center justify-center py-1.5 px-2.5 rounded-xl transition-all {{ request()->routeIs(['events.*', 'smart-city.*', 'communities.*']) ? 'text-brand-gold font-black bg-white/10' : 'text-gray-400 hover:text-white' }}">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold tracking-tight mt-0.5">Agenda</span>
            </a>

            <!-- 5. Akun / Masuk -->
            @auth
            <a href="{{ route('member.profile') }}" class="flex flex-col items-center justify-center py-1.5 px-2.5 rounded-xl transition-all {{ request()->routeIs('member.*') ? 'text-brand-gold font-black bg-white/10' : 'text-gray-400 hover:text-white' }}">
                <div class="w-5 h-5 rounded-full bg-brand-gold text-black flex items-center justify-center font-black text-[9px]">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="text-[9px] font-bold tracking-tight mt-0.5">Profil</span>
            </a>
            @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center py-1.5 px-2.5 rounded-xl transition-all {{ request()->routeIs(['login', 'register']) ? 'text-brand-gold font-black bg-white/10' : 'text-gray-400 hover:text-white' }}">
                <i data-lucide="user" class="w-5 h-5"></i>
                <span class="text-[9px] font-bold tracking-tight mt-0.5">Masuk</span>
            </a>
            @endauth

        </div>
    </nav>

    <script>
        function toggleDropdown(dropdownId, event) {
            if (event) {
                event.stopPropagation();
            }
            const target = document.getElementById(dropdownId);
            if (!target) return;

            const isOpen = target.classList.contains('is-open');

            // Close all dropdowns
            closeAllDropdowns();

            // If it was closed, open it now with splash spring animation
            if (!isOpen) {
                target.classList.add('is-open');
                const trigger = event?.currentTarget;
                if (trigger) {
                    const icon = trigger.querySelector('.dropdown-icon');
                    if (icon) icon.classList.add('rotate-180');
                }
            }
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                dropdown.classList.remove('is-open');
            });
            document.querySelectorAll('.dropdown-icon').forEach(icon => {
                icon.classList.remove('rotate-180');
            });
        }
        window.closeAllDropdowns = closeAllDropdowns;

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown') && !e.target.closest('.dropdown-trigger')) {
                closeAllDropdowns();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
                closeMobileMenu();
            }
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            const btn = document.getElementById('mobile-menu-btn');
            const openIcon = btn?.querySelector('.menu-open-icon');
            const closeIcon = btn?.querySelector('.menu-close-icon');

            if (!menu) return;

            const isOpen = menu.classList.contains('is-open');
            if (isOpen) {
                closeMobileMenu();
            } else {
                menu.classList.add('is-open');
                if (backdrop) {
                    backdrop.classList.remove('opacity-0', 'pointer-events-none');
                    backdrop.classList.add('opacity-100', 'pointer-events-auto');
                }
                if (btn) btn.setAttribute('aria-expanded', 'true');
                if (openIcon) openIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
            }
        }
        window.toggleMobileMenu = toggleMobileMenu;

        function closeMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            const btn = document.getElementById('mobile-menu-btn');
            const openIcon = btn?.querySelector('.menu-open-icon');
            const closeIcon = btn?.querySelector('.menu-close-icon');

            if (menu) menu.classList.remove('is-open');
            if (backdrop) {
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            }
            if (btn) btn.setAttribute('aria-expanded', 'false');
            if (openIcon) openIcon.classList.remove('hidden');
            if (closeIcon) closeIcon.classList.add('hidden');
        }
        window.closeMobileMenu = closeMobileMenu;
    </script>
    @stack('scripts')
</body>

</html>