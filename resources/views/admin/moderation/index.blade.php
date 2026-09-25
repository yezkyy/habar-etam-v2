@extends('layouts.admin')

@section('title', 'Moderasi Konten Warga - Admin Habar Etam')
@section('page_title', 'Pusat Moderasi Submission Konten')

@section('content')
<div class="space-y-6 pb-12" x-data="{
    rejectModalOpen: false,
    rejectType: '',
    rejectId: null,
    rejectTitle: '',
    rejectionReason: '',
    approveModalOpen: false,
    approveType: '',
    approveId: null,
    approveTitle: '',
    approveNote: 'Disetujui untuk publikasi publik.',
    deleteModalOpen: false,
    deleteType: '',
    deleteId: null,
    deleteTitle: '',
    
    openReject(type, id, title) {
        this.rejectType = type;
        this.rejectId = id;
        this.rejectTitle = title;
        this.rejectionReason = '';
        this.rejectModalOpen = true;
    },
    openApprove(type, id, title) {
        this.approveType = type;
        this.approveId = id;
        this.approveTitle = title;
        this.approveNote = 'Disetujui untuk publikasi publik.';
        this.approveModalOpen = true;
    },
    openDelete(type, id, title) {
        this.deleteType = type;
        this.deleteId = id;
        this.deleteTitle = title;
        this.deleteModalOpen = true;
    },
    setPresetReason(reason) {
        this.rejectionReason = reason;
    }
}">

    <!-- Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-6 sm:p-7 rounded-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-gold-500/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-32 h-32 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
        
        <div class="relative z-10 space-y-1.5">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-400 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gold-400 font-bold">Konten & Moderasi</span>
                <span>/</span>
                <span class="text-gray-300">Pusat Moderasi</span>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    Pusat Moderasi Konten Warga
                </h1>
                @if($stats['pending'] > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-400 text-black shadow-sm animate-pulse">
                        {{ $stats['pending'] }} Perlu Tindakan
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Tinjau kelayakan, kurasi keaslian informasi, dan validasi submission warga se-Kutai Kartanegara sebelum tayang live di portal publik.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => $status]) }}" 
               class="h-9 px-3.5 rounded-xl text-xs font-semibold text-white hover:text-black bg-white/10 hover:bg-gold-500 border border-white/15 transition-all inline-flex items-center gap-2 backdrop-blur-xs">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span>Muat Ulang</span>
            </a>
        </div>
    </div>

    <!-- 4 Interactive Overview Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- 1. Total Submissions -->
        <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'all', 'q' => request('q')]) }}" 
           class="bg-white p-5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'all' ? 'border-gold-500 ring-2 ring-gold-500/20 bg-amber-50/20' : 'border-gray-100 hover:border-gray-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-500 block uppercase tracking-wider">Total Konten</span>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 block font-mono">{{ number_format($stats['total']) }}</span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="layers" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-semibold text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                <span>Semua 6 Modul Warga</span>
            </div>
        </a>

        <!-- 2. Menunggu Review (Pending) -->
        <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'pending', 'q' => request('q')]) }}" 
           class="bg-white p-5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20 bg-amber-50/40' : 'border-gray-100 hover:border-amber-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-amber-700 block uppercase tracking-wider">Menunggu Review</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-600 mt-1 block font-mono">{{ number_format($stats['pending']) }}</span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-amber-700">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Belum Tayang ke Publik</span>
            </div>
        </a>

        <!-- 3. Tayang / Published -->
        <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'published', 'q' => request('q')]) }}" 
           class="bg-white p-5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'published' ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/40' : 'border-gray-100 hover:border-emerald-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-emerald-700 block uppercase tracking-wider">Telah Tayang</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1 block font-mono">{{ number_format($stats['published']) }}</span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Aktif di Portal Publik</span>
            </div>
        </a>

        <!-- 4. Ditolak / Rejected -->
        <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'rejected', 'q' => request('q')]) }}" 
           class="bg-white p-5 rounded-2xl border transition-all relative overflow-hidden group shadow-sm hover:shadow-md {{ $status === 'rejected' ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/40' : 'border-gray-100 hover:border-rose-200' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-rose-700 block uppercase tracking-wider">Ditolak / Ditarik</span>
                    <span class="text-2xl sm:text-3xl font-black text-rose-600 mt-1 block font-mono">{{ number_format($stats['rejected']) }}</span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shadow-2xs group-hover:scale-105 transition-transform">
                    <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-rose-700">
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span>
                <span>Tersimpan di Audit Log</span>
            </div>
        </a>
    </div>

    <!-- Category Ribbon Selector -->
    <div class="bg-white p-2 sm:p-2.5 rounded-2xl border border-gray-100 shadow-xs flex items-center gap-2 overflow-x-auto">
        <!-- 1. Semua -->
        <a href="{{ route('admin.moderation.index', ['type' => 'all', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'all' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="inbox" class="w-3.5 h-3.5"></i>
            <span>Semua Modul</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'all' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['all'] }}</span>
        </a>

        <!-- 2. Jual Cepat -->
        <a href="{{ route('admin.moderation.index', ['type' => 'quick_sale', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'quick_sale' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="tag" class="w-3.5 h-3.5 text-amber-500"></i>
            <span>Jual Cepat</span>
            @if($pendingCounts['quick_sale'] > 0)
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black bg-amber-400 text-black">{{ $pendingCounts['quick_sale'] }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'quick_sale' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['quick_sale'] }}</span>
            @endif
        </a>

        <!-- 3. Bursa Kerja -->
        <a href="{{ route('admin.moderation.index', ['type' => 'job', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'job' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-500"></i>
            <span>Bursa Kerja</span>
            @if($pendingCounts['job'] > 0)
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black bg-blue-500 text-white">{{ $pendingCounts['job'] }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'job' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['job'] }}</span>
            @endif
        </a>

        <!-- 4. Produk & UMKM -->
        <a href="{{ route('admin.moderation.index', ['type' => 'business', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'business' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-500"></i>
            <span>Produk & UMKM</span>
            @if($pendingCounts['business'] > 0)
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black bg-emerald-500 text-white">{{ $pendingCounts['business'] }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'business' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['business'] }}</span>
            @endif
        </a>

        <!-- 5. Kuliner -->
        <a href="{{ route('admin.moderation.index', ['type' => 'culinary', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'culinary' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="utensils" class="w-3.5 h-3.5 text-rose-500"></i>
            <span>Kuliner Khas</span>
            @if($pendingCounts['culinary'] > 0)
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black bg-rose-500 text-white">{{ $pendingCounts['culinary'] }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'culinary' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['culinary'] }}</span>
            @endif
        </a>

        <!-- 6. Event -->
        <a href="{{ route('admin.moderation.index', ['type' => 'event', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'event' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-500"></i>
            <span>Event & Agenda</span>
            @if($pendingCounts['event'] > 0)
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black bg-purple-500 text-white">{{ $pendingCounts['event'] }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'event' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['event'] }}</span>
            @endif
        </a>

        <!-- 7. Komunitas -->
        <a href="{{ route('admin.moderation.index', ['type' => 'community', 'status' => $status, 'q' => request('q'), 'sort' => $sort]) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $type == 'community' ? 'bg-gradient-to-r from-brand-gold to-amber-500 text-brand-black shadow-sm font-black' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="users-2" class="w-3.5 h-3.5 text-indigo-500"></i>
            <span>Klub & Komunitas</span>
            @if($pendingCounts['community'] > 0)
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black bg-indigo-500 text-white">{{ $pendingCounts['community'] }}</span>
            @else
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ $type == 'community' ? 'bg-black/20 text-black' : 'bg-gray-100 text-gray-700' }}">{{ $totalCounts['community'] }}</span>
            @endif
        </a>
    </div>

    <!-- Search, Status & Sorting Filter Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.moderation.index') }}" 
              method="GET" 
              x-data="adminLiveFilter" 
              @change="$el.submit()" 
              class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="status" value="{{ $status }}">

            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Input with Live Debounce -->
                <div class="relative flex-1 min-w-[260px] flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari judul konten, nama pengirim, kecamatan..." 
                           class="admin-search-input pl-10"
                           x-on:input.debounce.450ms="$el.form.submit()">
                </div>

                <!-- Sort Order Select -->
                <div class="w-48">
                    <select name="sort" 
                            class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-gray-50 hover:bg-white focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all cursor-pointer shadow-2xs">
                        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Terbaru Diajukan</option>
                        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Terlama Diajukan</option>
                        <option value="title_asc" {{ $sort == 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                        <option value="author_asc" {{ $sort == 'author_asc' ? 'selected' : '' }}>Nama Pengirim (A - Z)</option>
                    </select>
                </div>

                @if(request()->filled('q') || request()->filled('sort'))
                    <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => $status]) }}" 
                       class="h-10 px-3.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 transition-colors inline-flex items-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>

            <!-- Status Tabs with Badges -->
            <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl border border-gray-200/60 shrink-0 self-start lg:self-auto overflow-x-auto">
                <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'all', 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'all' ? 'bg-black text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Semua
                </a>
                <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'pending', 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5 {{ $status == 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'text-amber-700 hover:bg-amber-100/60' }}">
                    <span>Menunggu Review</span>
                    @if($stats['pending'] > 0)
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                    @endif
                </a>
                <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'published', 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'published' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:bg-emerald-100/60' }}">
                    Tayang
                </a>
                <a href="{{ route('admin.moderation.index', ['type' => $type, 'status' => 'rejected', 'q' => request('q'), 'sort' => $sort]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $status == 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'text-rose-700 hover:bg-rose-100/60' }}">
                    Ditolak
                </a>
            </div>
        </form>
    </div>

    <!-- Submissions Master Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 border-b border-gray-100 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="px-6 py-4">Konten & Modul</th>
                        <th class="px-6 py-4">Pengirim Warga</th>
                        <th class="px-6 py-4">Parameter Kunci</th>
                        <th class="px-6 py-4">Tanggal & Status</th>
                        <th class="px-6 py-4 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-amber-50/20 transition-colors group">
                            <!-- 1. Konten & Modul -->
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3.5 max-w-md">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 border border-gray-200 shrink-0 overflow-hidden flex items-center justify-center relative shadow-2xs">
                                        @if($sub['photo_url'])
                                            <img src="{{ $sub['photo_url'] }}" alt="{{ $sub['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-50 {{ $sub['type_color'] }}">
                                                <i data-lucide="{{ $sub['type_icon'] }}" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 flex-wrap mb-1">
                                            @php
                                                $badgeStyle = match($sub['type']) {
                                                    'job' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'business' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'culinary' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                    'event' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    'community' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'quick_sale' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-bold border {{ $badgeStyle }}">
                                                <i data-lucide="{{ $sub['type_icon'] }}" class="w-3 h-3"></i>
                                                {{ $sub['type_label'] }}
                                            </span>
                                            @if($sub['meta_secondary'])
                                                <span class="text-[11px] text-gray-500 truncate">&bull; {{ $sub['meta_secondary'] }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('admin.moderation.show', ['type' => $sub['type'], 'id' => $sub['id']]) }}" 
                                           class="font-bold text-gray-900 group-hover:text-gold-600 transition-colors text-sm line-clamp-1 block">
                                            {{ $sub['title'] }}
                                        </a>
                                        @if($sub['district'])
                                            <p class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5">
                                                <i data-lucide="map-pin" class="w-3 h-3 text-gold-600 shrink-0"></i>
                                                <span class="truncate">{{ $sub['district'] }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Pengirim Warga -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-2xl bg-amber-100 text-amber-900 border border-amber-200 flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($sub['author_name'] ?? 'W', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-gray-900 block text-xs">{{ $sub['author_name'] }}</span>
                                            @if($sub['author_is_verified'])
                                                <span title="NIK Warga Terverifikasi" class="text-emerald-600">
                                                    <i data-lucide="check-check" class="w-3.5 h-3.5"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-0.5">
                                            @if($sub['author_phone'])
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sub['author_phone']) }}" 
                                                   target="_blank" 
                                                   class="text-emerald-700 hover:text-emerald-800 flex items-center gap-0.5 font-mono font-semibold">
                                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $sub['author_phone'] }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">{{ $sub['author_email'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 3. Parameter Kunci -->
                            <td class="px-6 py-4">
                                <div class="text-[11px] space-y-1">
                                    @if($sub['type'] === 'quick_sale' && isset($sub['raw']->price))
                                        <div class="font-mono font-bold text-amber-700 text-xs">
                                            Rp {{ number_format($sub['raw']->price, 0, ',', '.') }}
                                        </div>
                                        <span class="text-gray-500 block">Kondisi: {{ $sub['raw']->condition ?? 'Bekas' }}</span>
                                    @elseif($sub['type'] === 'job' && isset($sub['raw']->salary_range))
                                        <div class="font-bold text-blue-700 text-xs">
                                            {{ $sub['raw']->salary_range }}
                                        </div>
                                        <span class="text-gray-500 block">{{ $sub['raw']->employment_type ?? 'Purna Waktu' }}</span>
                                    @elseif($sub['type'] === 'culinary' && isset($sub['raw']->price_range))
                                        <div class="font-bold text-rose-700 text-xs">
                                            {{ $sub['raw']->price_range }}
                                        </div>
                                        <span class="text-gray-500 block">{{ $sub['raw']->operating_hours ?? 'Buka Setiap Hari' }}</span>
                                    @elseif($sub['type'] === 'event' && isset($sub['raw']->start_date))
                                        <div class="font-bold text-purple-700 text-xs">
                                            {{ $sub['raw']->start_date->format('d M Y') }}
                                        </div>
                                        <span class="text-gray-500 block">{{ $sub['raw']->start_time ? substr($sub['raw']->start_time, 0, 5) . ' WITA' : 'Sesuai Jadwal' }}</span>
                                    @elseif($sub['type'] === 'community' && isset($sub['raw']->activity_schedule))
                                        <div class="font-bold text-indigo-700 text-xs">
                                            {{ $sub['raw']->activity_schedule }}
                                        </div>
                                        <span class="text-gray-500 block">{{ $sub['raw']->base_location ?? 'Kutai Kartanegara' }}</span>
                                    @else
                                        <div class="font-bold text-gray-800 text-xs">
                                            {{ $sub['meta_secondary'] ?: 'Standar Publik' }}
                                        </div>
                                        <span class="text-gray-500 block">{{ $sub['district'] ?: 'Seluruh Kukar' }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 4. Tanggal & Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1.5">
                                    @if($sub['status'] === 'published')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Tayang
                                        </span>
                                    @elseif($sub['status'] === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 animate-spin"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Ditolak
                                        </span>
                                    @endif

                                    <div class="text-[11px] text-gray-500 font-mono flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                        <span>{{ $sub['created_at']->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- 5. Aksi Cepat & Detail -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <!-- Direct Detail Review Link -->
                                    <a href="{{ route('admin.moderation.show', ['type' => $sub['type'], 'id' => $sub['id']]) }}" 
                                       title="Tinjau Lengkap"
                                       class="px-3.5 py-1.5 rounded-xl text-xs font-black {{ $sub['status'] === 'pending' ? 'bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black shadow-sm' : 'bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-200/80' }} transition-all inline-flex items-center gap-1 cursor-pointer">
                                        <span>Tinjau</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>

                                    @if($sub['status'] === 'pending' || $sub['status'] === 'rejected')
                                        <!-- Quick Approve Button -->
                                        <button type="button" 
                                                @click="openApprove('{{ $sub['type'] }}', {{ $sub['id'] }}, '{{ addslashes($sub['title']) }}')"
                                                title="Setujui Langsung"
                                                class="w-8 h-8 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 transition-all flex items-center justify-center cursor-pointer shadow-2xs">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </button>
                                    @endif

                                    @if($sub['status'] === 'pending' || $sub['status'] === 'published')
                                        <!-- Quick Reject Button -->
                                        <button type="button" 
                                                @click="openReject('{{ $sub['type'] }}', {{ $sub['id'] }}, '{{ addslashes($sub['title']) }}')"
                                                title="Tolak / Tarik Konten"
                                                class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 transition-all flex items-center justify-center cursor-pointer shadow-2xs">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            @click="openDelete('{{ $sub['type'] }}', {{ $sub['id'] }}, '{{ addslashes($sub['title']) }}')"
                                            title="Hapus Konten dari Sistem"
                                            class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-rose-100 text-gray-400 hover:text-rose-700 border border-gray-200 transition-all flex items-center justify-center cursor-pointer">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center mx-auto text-gray-400 shadow-inner">
                                        <i data-lucide="inbox" class="w-7 h-7"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Tidak ada submission ditemukan</h3>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Tidak ada data konten yang cocok dengan kombinasi filter modul dan status yang dipilih saat ini.
                                    </p>
                                    <div class="pt-2">
                                        <a href="{{ route('admin.moderation.index') }}" class="px-4 py-2 rounded-xl text-xs font-black bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black transition-colors inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Semua Filter
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Tailwind Pagination Footer -->
        @if($submissions->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $submissions->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: QUICK APPROVE MODAL -->
    <div x-show="approveModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl relative border border-gray-100"
             @click.outside="approveModalOpen = false">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-2xs">
                        <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">Setujui & Publikasikan</h3>
                        <p class="text-xs text-gray-500">Konten akan langsung live di portal publik</p>
                    </div>
                </div>
                <button type="button" @click="approveModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200/80 text-xs">
                <span class="text-gray-400 block mb-0.5 text-[11px] font-semibold uppercase">Judul Konten:</span>
                <span class="font-bold text-gray-900 line-clamp-2" x-text="approveTitle"></span>
            </div>

            <form :action="'{{ url('/admin/moderasi') }}/' + approveType + '/' + approveId + '/approve'" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 block">Catatan Persetujuan (Opsional)</label>
                    <input type="text" name="note" x-model="approveNote" class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs bg-gray-50 focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-gray-100">
                    <button type="button" @click="approveModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition-all inline-flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i> Setujui Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: QUICK REJECT MODAL WITH SMART PRESETS -->
    <div x-show="rejectModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 space-y-5 shadow-2xl relative border border-gray-100"
             @click.outside="rejectModalOpen = false">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-2xs">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">Tolak & Minta Perbaikan Konten</h3>
                        <p class="text-xs text-gray-500">Pilih preset alasan atau tulis catatan spesifik</p>
                    </div>
                </div>
                <button type="button" @click="rejectModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200/80 text-xs">
                <span class="text-gray-400 block mb-0.5 text-[11px] font-semibold uppercase">Submission Konten:</span>
                <span class="font-bold text-gray-900 line-clamp-2" x-text="rejectTitle"></span>
            </div>

            <!-- Smart Preset Chips -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-700 block">Pilih Alasan Cepat (1-Klik):</label>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" 
                            @click="setPresetReason('Informasi atau rincian deskripsi kurang jelas dan tidak lengkap.')" 
                            class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-900 transition-colors cursor-pointer">
                        📝 Data Tidak Lengkap
                    </button>
                    <button type="button" 
                            @click="setPresetReason('Foto/media buram, tidak relevan, atau tidak sesuai dengan produk/layanan yang ditawarkan.')" 
                            class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-900 transition-colors cursor-pointer">
                        📷 Foto Buram / Tidak Sesuai
                    </button>
                    <button type="button" 
                            @click="setPresetReason('Nomor kontak WhatsApp/telepon tidak aktif atau tidak dapat dihubungi.')" 
                            class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-900 transition-colors cursor-pointer">
                        📞 Kontak Tidak Valid
                    </button>
                    <button type="button" 
                            @click="setPresetReason('Konten terindikasi melanggar norma sosial, ketentuan hukum, atau kebijakan Habar Etam.')" 
                            class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-900 transition-colors cursor-pointer">
                        ⚠️ Melanggar Kebijakan
                    </button>
                    <button type="button" 
                            @click="setPresetReason('Terindikasi penipuan (fraud), spam, atau duplikasi dari postingan lain.')" 
                            class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-900 transition-colors cursor-pointer">
                        🚫 Indikasi Spam / Penipuan
                    </button>
                </div>
            </div>

            <form :action="'{{ url('/admin/moderasi') }}/' + rejectType + '/' + rejectId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 block">Alasan Penolakan untuk Warga <span class="text-rose-500">*</span></label>
                    <textarea name="rejection_reason" 
                              x-model="rejectionReason" 
                              rows="3" 
                              required 
                              placeholder="Ketik catatan penolakan spesifik agar pengirim mengetahui apa yang perlu diperbaiki..." 
                              class="w-full p-3 rounded-2xl border border-gray-200 text-xs bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 outline-none leading-relaxed transition-all"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-gray-100">
                    <button type="button" @click="rejectModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-md transition-all inline-flex items-center gap-1.5">
                        <i data-lucide="x" class="w-4 h-4"></i> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: DELETE CONFIRMATION MODAL -->
    <div x-show="deleteModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl relative border border-gray-100"
             @click.outside="deleteModalOpen = false">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-2xs">
                        <i data-lucide="trash-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">Hapus Konten Submission</h3>
                        <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <button type="button" @click="deleteModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200/80 text-xs">
                <span class="text-gray-400 block mb-0.5 text-[11px] font-semibold uppercase">Konten yang akan dihapus:</span>
                <span class="font-bold text-gray-900 line-clamp-2" x-text="deleteTitle"></span>
            </div>

            <form :action="'{{ url('/admin/moderasi') }}/' + deleteType + '/' + deleteId" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')

                <p class="text-xs text-gray-600 leading-relaxed">
                    Seluruh data submission, media gambar, dan log moderasi akan dihapus secara permanen dari database sistem.
                </p>

                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-gray-100">
                    <button type="button" @click="deleteModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 text-white shadow-md transition-all inline-flex items-center gap-1.5">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
