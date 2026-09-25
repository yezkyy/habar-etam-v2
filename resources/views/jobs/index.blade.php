@extends('layouts.public')

@section('title', 'Bursa Kerja Lokal Tenggarong & Kukar — Habar Etam')
@section('meta_description', 'Pusat lowongan kerja dan karir lokal di Tenggarong dan wilayah Kutai Kartanegara. Temukan loker purna waktu, paruh waktu, kontrak, dan magang terpercaya.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Career Hub Hero Header Banner -->
    <div class="bg-gradient-to-r from-gray-950 via-brand-black to-slate-900 text-white rounded-3xl p-6 sm:p-8 mb-8 border border-blue-500/20 shadow-2xl relative overflow-hidden reveal-blur-spring">
        <!-- Subtle Glow Ambient Background -->
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/30 text-blue-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-400"></i>
                    <span>Bursa Karir Warga • Info Loker Terverifikasi Kukar</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Lowongan Kerja & Peluang Karir Lokal
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 mt-2 leading-relaxed">
                    Temukan kesempatan berkarir di berbagai perusahaan, toko, UMKM, instansi, dan penyedia jasa terpercaya di wilayah Tenggarong dan sekitarnya.
                </p>

                <!-- Quick Stats Badges -->
                <div class="flex flex-wrap items-center gap-3 mt-4 pt-2 text-xs">
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-400"></i>
                        <span><strong>{{ $vacancies->total() }}</strong> Lowongan Aktif</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="building-2" class="w-3.5 h-3.5 text-blue-400"></i>
                        <span>Perusahaan & UMKM Lokal</span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-white/5 border border-white/10 text-gray-200 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                        <span>Wilayah Kutai Kartanegara</span>
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ route('jobs.create') }}" class="btn-gold py-3.5 px-6 font-black text-sm shadow-gold-glow flex items-center justify-center gap-2 rounded-2xl hover:scale-[1.02] transition-transform">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    <span>Pasang Loker Gratis</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Employment Type Interactive Navigation Discovery Bar -->
    @php
        $jobTypes = [
            ['name' => 'Semua Tipe', 'value' => '', 'icon' => 'layout-grid'],
            ['name' => 'Purna Waktu', 'value' => 'Purna Waktu', 'icon' => 'clock'],
            ['name' => 'Paruh Waktu', 'value' => 'Paruh Waktu', 'icon' => 'hourglass'],
            ['name' => 'Kontrak', 'value' => 'Kontrak', 'icon' => 'file-text'],
            ['name' => 'Magang', 'value' => 'Magang', 'icon' => 'graduation-cap'],
            ['name' => 'Freelance / Harian', 'value' => 'Freelance', 'icon' => 'laptop'],
        ];
        $currentType = request('type', '');
    @endphp

    <div class="mb-6 relative group reveal-blur-spring">
        <div class="relative flex items-center">
            <!-- Left Scroll Button -->
            <button type="button" id="jobScrollLeft" aria-label="Scroll Kiri" class="hidden md:flex absolute -left-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </button>

            <!-- Scrollable Job Type Pills -->
            <div id="jobScrollContainer" 
                 class="flex items-center gap-2.5 overflow-x-auto py-2 px-1 scroll-smooth scrollbar-none no-scrollbar w-full cursor-grab active:cursor-grabbing select-none"
                 style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($jobTypes as $t)
                    @php
                        $isActive = ($currentType === $t['value']) || (empty($currentType) && empty($t['value']));
                        $queryParam = request()->except(['type', 'page']);
                        if (!empty($t['value'])) {
                            $queryParam['type'] = $t['value'];
                        }
                        $typeUrl = route('jobs.index', $queryParam);
                    @endphp
                    <a href="{{ $typeUrl }}" 
                       draggable="false"
                       class="group/item shrink-0 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 flex items-center gap-2.5 border select-none {{ $isActive ? 'bg-gradient-to-r from-gray-950 via-brand-black to-stone-900 text-brand-gold border-brand-black shadow-md ring-2 ring-brand-gold/30 scale-[1.02]' : 'bg-white text-gray-700 border-gray-200 hover:border-brand-gold/70 hover:bg-amber-50/60 hover:text-brand-black shadow-2xs' }}">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isActive ? 'bg-brand-gold/20 text-brand-gold' : 'bg-gray-100 group-hover/item:bg-amber-100 text-gray-600 group-hover/item:text-brand-black' }} transition-colors pointer-events-none">
                            <i data-lucide="{{ $t['icon'] }}" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="pointer-events-none">{{ $t['name'] }}</span>
                        @if($isActive && !empty($t['value']))
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold animate-pulse pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Right Scroll Button -->
            <button type="button" id="jobScrollRight" aria-label="Scroll Kanan" class="hidden md:flex absolute -right-3.5 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-gray-200 items-center justify-center text-gray-700 hover:text-black hover:bg-amber-50 hover:border-amber-300 transition-all opacity-0 group-hover:opacity-100 pointer-events-auto">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- Search & Advanced Filter Toolbar -->
    <div class="bg-white/95 backdrop-blur-sm border border-gray-200/90 rounded-3xl p-5 sm:p-6 mb-6 shadow-subtle reveal-blur-spring relative z-30 overflow-visible transition-all">
        <form method="GET" action="{{ route('jobs.index') }}" id="jobFilterForm" class="relative z-30">
            
            <!-- Hidden Type to preserve type selection -->
            @if(request()->filled('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <!-- 1. Search Input Field -->
                <div class="md:col-span-6 lg:col-span-6">
                    <label for="search-job-input" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                        <span>Cari Lowongan / Posisi / Perusahaan</span>
                    </label>
                    <div class="relative group/search">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within/search:text-brand-gold-dark transition-colors">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               id="search-job-input" 
                               name="q" 
                               value="{{ request('q') }}" 
                               placeholder="Contoh: Staff Admin, Kasir, Barista, Mekanik, Driver..." 
                               class="form-input pl-10 pr-9 text-xs py-3 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-medium">
                        
                        @if(request()->filled('q'))
                            <a href="{{ route('jobs.index', request()->except(['q', 'page'])) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500 transition-colors"
                               title="Hapus kata kunci">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- 2. Employment Type Filter -->
                <div class="md:col-span-4 lg:col-span-4">
                    <label for="job-type-select" class="flex items-center gap-1.5 text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-500"></i>
                        <span>Tipe Pekerjaan</span>
                    </label>
                    <div class="relative">
                        <select id="job-type-select" 
                                name="type" 
                                onchange="document.getElementById('jobFilterForm').submit()" 
                                class="form-input text-xs py-3 pl-3 pr-8 rounded-2xl border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all shadow-2xs font-semibold text-gray-800 cursor-pointer">
                            <option value="">Semua Tipe Pekerjaan</option>
                            <option value="Purna Waktu" {{ request('type') == 'Purna Waktu' ? 'selected' : '' }}>Purna Waktu (Full Time)</option>
                            <option value="Paruh Waktu" {{ request('type') == 'Paruh Waktu' ? 'selected' : '' }}>Paruh Waktu (Part Time)</option>
                            <option value="Kontrak" {{ request('type') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang" {{ request('type') == 'Magang' ? 'selected' : '' }}>Magang (Internship)</option>
                            <option value="Freelance" {{ request('type') == 'Freelance' ? 'selected' : '' }}>Freelance / Harian</option>
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
                        <a href="{{ route('jobs.index') }}" class="btn-outline py-3 px-3 text-xs rounded-2xl text-rose-600 border-rose-200 hover:bg-rose-50 hover:border-rose-300 transition-colors shrink-0" title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Popular Job Search Tags -->
            <div class="mt-3.5 pt-3.5 border-t border-gray-100 flex flex-wrap items-center gap-2 text-[11px] text-gray-500">
                <span class="font-semibold text-gray-600 flex items-center gap-1 shrink-0">
                    <i data-lucide="flame" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Posisi Populer:</span>
                </span>
                @php
                    $popularJobKeywords = ['Staff Admin', 'Kasir', 'Barista', 'Driver', 'Mekanik', 'Desainer', 'Guru', 'Security'];
                @endphp
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach($popularJobKeywords as $kw)
                        @php
                            $isCurrentQuery = request('q') === $kw;
                            $kwUrl = route('jobs.index', array_merge(request()->except(['page']), ['q' => $kw]));
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
                <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-600"></i>
                <span><strong>{{ $vacancies->total() }}</strong> Lowongan Ditemukan</span>
            </span>

            <!-- Removable Filter Tag: Type -->
            @if(request()->filled('type'))
                <a href="{{ route('jobs.index', request()->except(['type', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 font-bold hover:bg-blue-100 transition-colors shadow-2xs group"
                   title="Hapus filter tipe">
                    <span>Tipe: <strong>{{ request('type') }}</strong></span>
                    <i data-lucide="x" class="w-3 h-3 text-blue-700 group-hover:text-rose-600"></i>
                </a>
            @endif

            <!-- Removable Filter Tag: Query -->
            @if(request()->filled('q'))
                <a href="{{ route('jobs.index', request()->except(['q', 'page'])) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 font-bold hover:bg-amber-100 transition-colors shadow-2xs group"
                   title="Hapus kata kunci">
                    <span>Kata Kunci: "<strong>{{ request('q') }}</strong>"</span>
                    <i data-lucide="x" class="w-3 h-3 text-amber-700 group-hover:text-rose-600"></i>
                </a>
            @endif
        </div>

        @if(request()->anyFilled(['q', 'type']))
            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1.5 self-start sm:self-auto shrink-0">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                <span>Reset Semua Filter</span>
            </a>
        @endif
    </div>

    <!-- Vacancies Grid -->
    @if($vacancies->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200 p-12 sm:p-16 text-center shadow-subtle reveal-blur-spring">
            <div class="w-16 h-16 rounded-full bg-blue-50 border border-blue-200 text-blue-700 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="briefcase" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-brand-black">Belum Ada Lowongan yang Cocok</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5 max-w-md mx-auto">
                Tidak ada lowongan kerja yang sesuai dengan kriteria atau kata kunci yang Anda masukkan. Coba ubah kata kunci atau pasang loker baru.
            </p>
            <div class="flex items-center justify-center gap-3 mt-6">
                <a href="{{ route('jobs.index') }}" class="btn-outline text-xs py-2.5 px-4 rounded-xl font-bold">
                    Reset Filter Pencarian
                </a>
                <a href="{{ route('jobs.create') }}" class="btn-gold text-xs py-2.5 px-5 rounded-xl font-bold">
                    Pasang Lowongan Baru
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 reveal-stagger">
            @foreach($vacancies as $job)
                <div class="content-card modern-hover-card relative flex flex-col justify-between group rounded-3xl shadow-subtle hover:shadow-2xl bg-white border border-gray-200/90 overflow-hidden cursor-pointer transition-all duration-300">
                    
                    <!-- Entire Card Clickable Link -->
                    <a href="{{ route('jobs.show', $job->slug) }}" class="absolute inset-0 z-10" aria-label="{{ $job->title }}"></a>

                    <div>
                        <!-- Top Image Media Container -->
                        <div class="h-44 w-full relative overflow-hidden bg-slate-900 shrink-0">
                            <img src="{{ $job->photo_url }}" 
                                 alt="{{ $job->title }}" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

                            <!-- Top Badges Overlay -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 z-10">
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-xl bg-blue-600/90 backdrop-blur-md text-white border border-blue-400/40 uppercase tracking-wider shadow-sm">
                                    {{ $job->employment_type }}
                                </span>

                                @if($job->deadline)
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md text-white/90 border border-white/20 flex items-center gap-1 shadow-sm">
                                        <i data-lucide="calendar" class="w-3 h-3 text-amber-400"></i>
                                        <span>{{ $job->deadline->format('d M') }}</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Bottom Image Overlay: Company Initial & Name -->
                            <div class="absolute bottom-3 left-3 right-3 flex items-center gap-2.5 z-10">
                                <div class="w-9 h-9 rounded-xl bg-white text-blue-900 font-black text-xs flex items-center justify-center shadow-md shrink-0 border border-white/80">
                                    {{ strtoupper(substr($job->company, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-white truncate drop-shadow-sm">{{ $job->company }}</p>
                                    <p class="text-[10px] text-gray-300 truncate flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-2.5 h-2.5 text-rose-400 shrink-0"></i>
                                        <span class="truncate">{{ $job->location }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body Content -->
                        <div class="p-5">
                            <!-- Job Title -->
                            <h3 class="text-sm font-extrabold text-brand-black group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                {{ $job->title }}
                            </h3>

                            <!-- Salary Range Pill -->
                            <div class="mt-2.5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs font-extrabold">
                                    <i data-lucide="wallet" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    <span>{{ $job->salary_range ?: 'Gaji Kompetitif' }}</span>
                                </span>
                            </div>

                            <!-- Short Description -->
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">
                                {{ $job->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer: Action Button -->
                    <div class="px-5 pb-5 pt-0 flex items-center justify-between text-[11px] text-gray-500 relative z-20">
                        <span class="text-[11px] text-gray-400 font-medium">
                            {{ $job->created_at->diffForHumans() }}
                        </span>
                        
                        <span class="px-3.5 py-1.5 rounded-xl bg-brand-black group-hover:bg-blue-600 text-white text-xs font-bold transition-colors flex items-center gap-1 shadow-2xs">
                            <span>Lamar Loker</span>
                            <i data-lucide="arrow-right" class="w-3 h-3 group-hover:translate-x-0.5 transition-transform"></i>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $vacancies->links('partials.pagination') }}
        </div>
    @endif

    <!-- Career Guidelines / Panduan Pelamar -->
    <div class="mt-16 bg-[#FBF9F4] border border-[#EBE3D3] rounded-3xl p-6 sm:p-8 reveal-blur-spring">
        <div class="flex items-center gap-2 text-brand-gold-dark font-bold text-xs uppercase tracking-wider mb-2">
            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
            <span>Panduan & Etika Melamar Kerja Warga Tenggarong</span>
        </div>
        <h2 class="text-xl font-extrabold text-brand-black">Tips Sukses & Aman Melamar Pekerjaan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6 text-xs text-gray-600">
            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 font-black">
                    1
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Siapkan CV & Kontak yang Aktif</h3>
                    <p class="leading-relaxed">Pastikan CV mencantumkan pengalaman yang relevan serta nomor WhatsApp dan email aktif agar mudah dihubungi HRD.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 font-black">
                    2
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Waspada Pungutan Biaya (Gratis)</h3>
                    <p class="leading-relaxed">Proses rekrutmen kerja resmi tidak pernah memungut biaya apapun (bebas biaya admin/seragam/tiket).</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 font-black">
                    3
                </div>
                <div>
                    <h3 class="font-bold text-brand-black text-sm mb-1">Gunakan Bahasa yang Sopan</h3>
                    <p class="leading-relaxed">Saat menghubungi kontak WhatsApp atau mengirim email lamaran, perkenalkan diri dengan ramah dan profesional.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const jobContainer = document.getElementById('jobScrollContainer');
        const leftBtn = document.getElementById('jobScrollLeft');
        const rightBtn = document.getElementById('jobScrollRight');

        if (jobContainer && leftBtn && rightBtn) {
            const updateArrows = () => {
                const maxScrollLeft = jobContainer.scrollWidth - jobContainer.clientWidth;
                const canScroll = maxScrollLeft > 10;
                
                if (!canScroll) {
                    leftBtn.style.opacity = '0';
                    rightBtn.style.opacity = '0';
                    return;
                }

                leftBtn.disabled = jobContainer.scrollLeft <= 5;
                rightBtn.disabled = jobContainer.scrollLeft >= maxScrollLeft - 5;
                
                leftBtn.style.opacity = jobContainer.scrollLeft > 10 ? '1' : '0';
                rightBtn.style.opacity = jobContainer.scrollLeft < maxScrollLeft - 10 ? '1' : '0';
            };

            leftBtn.addEventListener('click', () => {
                jobContainer.scrollBy({ left: -240, behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                jobContainer.scrollBy({ left: 240, behavior: 'smooth' });
            });

            jobContainer.addEventListener('scroll', updateArrows, { passive: true });

            // Drag-to-scroll
            let isDown = false;
            let startX;
            let scrollLeftPos;
            let isDragging = false;

            jobContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                isDragging = false;
                startX = e.pageX - jobContainer.offsetLeft;
                scrollLeftPos = jobContainer.scrollLeft;
            });

            jobContainer.addEventListener('mouseleave', () => { isDown = false; });
            jobContainer.addEventListener('mouseup', () => { isDown = false; });

            jobContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                const x = e.pageX - jobContainer.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 5) isDragging = true;
                jobContainer.scrollLeft = scrollLeftPos - walk;
            });

            jobContainer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        isDragging = false;
                    }
                });
            });

            // Wheel horizontal scroll
            jobContainer.addEventListener('wheel', (e) => {
                if (Math.abs(e.deltaX) < Math.abs(e.deltaY)) {
                    jobContainer.scrollLeft += e.deltaY * 0.75;
                    e.preventDefault();
                }
            }, { passive: false });

            // Auto scroll active pill into view
            const activePill = jobContainer.querySelector('.ring-brand-gold\\/30');
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

