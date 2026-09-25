@extends('layouts.admin')

@section('title', 'Moderasi Konten: ' . ($item->title ?? $item->name) . ' - Admin Habar Etam')
@section('page_title', 'Tinjauan & Keputusan Moderasi Konten')

@section('content')
<div class="space-y-6 pb-12" x-data="{
    rejectModalOpen: false,
    rejectionReason: '{{ addslashes($item->rejection_reason ?? '') }}',
    approveModalOpen: false,
    approveNote: 'Disetujui untuk publikasi publik.',
    deleteModalOpen: false,
    activeLightboxImg: null,
    
    setPresetReason(reason) {
        this.rejectionReason = reason;
    }
}">

    <!-- Top Breadcrumbs & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-600 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.moderation.index') }}" class="hover:text-gold-600 transition-colors">Konten & Moderasi</a>
            <span>/</span>
            <a href="{{ route('admin.moderation.index', ['type' => $type]) }}" class="hover:text-gold-600 transition-colors">{{ $typeMeta['name'] }}</a>
            <span>/</span>
            <span class="text-gray-900 font-bold truncate max-w-[240px]">{{ $item->title ?? $item->name }}</span>
        </div>

        <div class="flex items-center gap-2">
            @if($item->isPublished())
                @php
                    $publicRouteName = match($type) {
                        'job' => 'jobs.show',
                        'business' => 'businesses.show',
                        'culinary' => 'culinary.show',
                        'event' => 'events.show',
                        'community' => 'communities.show',
                        'quick_sale' => 'quick-sales.show',
                        default => null
                    };
                @endphp
                @if($publicRouteName && \Illuminate\Support\Facades\Route::has($publicRouteName))
                    <a href="{{ route($publicRouteName, $item->slug ?? $item->id) }}" 
                       target="_blank" 
                       class="h-9 px-3.5 rounded-xl text-xs font-bold text-emerald-700 hover:text-white bg-emerald-50 hover:bg-emerald-600 border border-emerald-200 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span>Buka di Website</span>
                    </a>
                @endif
            @endif

            <a href="{{ route('admin.moderation.index', ['type' => $type]) }}" 
               class="h-9 px-3.5 rounded-xl text-xs font-bold bg-gray-900 hover:bg-gold-500 text-white hover:text-black transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- LEFT 2 COLUMNS: CONTENT REVIEW & MEDIA -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Hero Content Overview Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between pb-6 border-b border-gray-100 gap-4">
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            @php
                                $badgeStyle = match($type) {
                                    'job' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'business' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'culinary' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'event' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'community' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'quick_sale' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $badgeStyle }}">
                                <i data-lucide="{{ $typeMeta['icon'] }}" class="w-3.5 h-3.5"></i>
                                {{ $typeMeta['name'] }}
                            </span>
                            @if(isset($item->category) || isset($item->culinary_type) || isset($item->interest_category) || isset($item->employment_type))
                                <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                    {{ $item->category ?? ($item->culinary_type ?? ($item->interest_category ?? ($item->employment_type ?? ''))) }}
                                </span>
                            @endif
                        </div>

                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">
                            {{ $item->title ?? $item->name }}
                        </h2>

                        <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i>
                                Diajukan {{ $item->created_at->translatedFormat('d F Y, H:i') }} WITA
                            </span>
                            @if($item->published_at)
                                <span class="flex items-center gap-1 text-emerald-700 font-bold">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    Tayang {{ $item->published_at->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Status Indicator Badge -->
                    <div class="shrink-0">
                        @if($item->status === 'published')
                            <div class="px-3.5 py-2 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                <span>PUBLISHED</span>
                            </div>
                        @elseif($item->status === 'pending')
                            <div class="px-3.5 py-2 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs animate-pulse">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                                <span>MENUNGGU REVIEW</span>
                            </div>
                        @else
                            <div class="px-3.5 py-2 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs">
                                <i data-lucide="x-circle" class="w-4 h-4 text-rose-600"></i>
                                <span>DITOLAK / REVISI</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rejection Notice if rejected -->
                @if($item->status === 'rejected' && $item->rejection_reason)
                    <div class="p-4 bg-rose-50 rounded-2xl border border-rose-200 text-xs text-rose-800 space-y-1">
                        <div class="flex items-center gap-2 font-black text-rose-700">
                            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            <span>Catatan Alasan Penolakan:</span>
                        </div>
                        <p class="leading-relaxed pl-6 text-rose-900 font-medium">{{ $item->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Visual Media Gallery / Photos -->
                @if($type === 'quick_sale' && $item->media && $item->media->count() > 0)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                                <i data-lucide="image" class="w-4 h-4 text-amber-600"></i>
                                <span>Foto Bukti Barang ({{ $item->media->count() }})</span>
                            </h3>
                            <span class="text-[11px] text-gray-400">Klik foto untuk memperbesar</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach($item->media as $med)
                                <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 relative group cursor-pointer shadow-2xs"
                                     @click="activeLightboxImg = '{{ asset('storage/' . $med->file_path) }}'">
                                    <img src="{{ asset('storage/' . $med->file_path) }}" alt="Foto Barang" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @if($med->is_primary)
                                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-brand-gold text-brand-black font-black text-[9px] shadow-sm">
                                            Foto Utama
                                        </span>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($type === 'event' && $item->poster_image)
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                            <i data-lucide="image" class="w-4 h-4 text-purple-600"></i>
                            <span>Poster Resmi Acara</span>
                        </h3>
                        <div class="max-w-md rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 relative group cursor-pointer shadow-2xs"
                             @click="activeLightboxImg = '{{ asset('storage/' . $item->poster_image) }}'">
                            <img src="{{ asset('storage/' . $item->poster_image) }}" alt="Poster Event" class="w-full h-auto max-h-96 object-contain group-hover:scale-102 transition-transform">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                <i data-lucide="zoom-in" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                @elseif(isset($item->photo) && $item->photo)
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                            <i data-lucide="image" class="w-4 h-4 text-gold-600"></i>
                            <span>Foto Unggahan Warga</span>
                        </h3>
                        <div class="max-w-md rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 relative group cursor-pointer shadow-2xs"
                             @click="activeLightboxImg = '{{ Str::startsWith($item->photo, 'http') ? $item->photo : asset('storage/' . $item->photo) }}'">
                            <img src="{{ Str::startsWith($item->photo, 'http') ? $item->photo : asset('storage/' . $item->photo) }}" alt="Foto Konten" class="w-full h-auto max-h-80 object-cover group-hover:scale-102 transition-transform">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                <i data-lucide="zoom-in" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Key Attributes Grid Tailored by Module Type -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                        <i data-lucide="list-checks" class="w-4 h-4 text-gold-600"></i>
                        <span>Parameter & Spesifikasi Konten</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        
                        <!-- Common: Lokasi / Kecamatan -->
                        @if(isset($item->district) || isset($item->location_district) || isset($item->location) || isset($item->location_name) || isset($item->base_location))
                            <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gold-600"></i> Wilayah / Lokasi
                                </span>
                                <span class="font-bold text-gray-900 text-xs block">
                                    {{ $item->district ?? ($item->location_district ?? ($item->location ?? ($item->location_name ?? ($item->base_location ?? '-')))) }}
                                </span>
                                @if(isset($item->address) || isset($item->location_address))
                                    <span class="text-[11px] text-gray-500 block mt-1">
                                        {{ $item->address ?? $item->location_address }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Module 1: Job Vacancy Specifics -->
                        @if($type === 'job')
                            @if(isset($item->company))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="building" class="w-3.5 h-3.5 text-blue-600"></i> Nama Perusahaan / Instansi
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->company }}</span>
                                </div>
                            @endif
                            @if(isset($item->salary_range))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-blue-600"></i> Estimasi Rentang Gaji
                                    </span>
                                    <span class="font-bold text-blue-700 text-xs block font-mono">{{ $item->salary_range }}</span>
                                </div>
                            @endif
                            @if(isset($item->deadline))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-600"></i> Batas Lamaran (Deadline)
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->deadline->format('d F Y') }}</span>
                                </div>
                            @endif
                        @endif

                        <!-- Module 2: Business & UMKM Specifics -->
                        @if($type === 'business')
                            @if(isset($item->category))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="tag" class="w-3.5 h-3.5 text-emerald-600"></i> Kategori Bisnis
                                    </span>
                                    <span class="font-bold text-emerald-700 text-xs block">{{ $item->category }}</span>
                                </div>
                            @endif
                            @if(isset($item->operating_hours))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-600"></i> Jam Operasional
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->operating_hours }}</span>
                                </div>
                            @endif
                            @if(isset($item->instagram))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="instagram" class="w-3.5 h-3.5 text-rose-600"></i> Instagram Bisnis
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->instagram }}</span>
                                </div>
                            @endif
                        @endif

                        <!-- Module 3: Culinary Place Specifics -->
                        @if($type === 'culinary')
                            @if(isset($item->culinary_type))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="utensils" class="w-3.5 h-3.5 text-rose-600"></i> Jenis Sajian Kuliner
                                    </span>
                                    <span class="font-bold text-rose-700 text-xs block">{{ $item->culinary_type }}</span>
                                </div>
                            @endif
                            @if(isset($item->price_range))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-rose-600"></i> Kisaran Harga Menu
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->price_range }}</span>
                                </div>
                            @endif
                            @if(isset($item->operating_hours))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-rose-600"></i> Jam Buka
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->operating_hours }}</span>
                                </div>
                            @endif
                        @endif

                        <!-- Module 4: Event & Kegiatan Specifics -->
                        @if($type === 'event')
                            @if(isset($item->organizer))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="users" class="w-3.5 h-3.5 text-purple-600"></i> Penyelenggara Acara
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->organizer }}</span>
                                </div>
                            @endif
                            @if(isset($item->start_date))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-600"></i> Jadwal Pelaksanaan
                                    </span>
                                    <span class="font-bold text-purple-700 text-xs block">
                                        {{ $item->start_date->format('d M Y') }}
                                        @if($item->end_date && $item->end_date != $item->start_date)
                                            - {{ $item->end_date->format('d M Y') }}
                                        @endif
                                    </span>
                                    @if($item->start_time)
                                        <span class="text-[11px] text-gray-500 block mt-0.5">Pukul {{ substr($item->start_time, 0, 5) }} WITA</span>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <!-- Module 5: Community Specifics -->
                        @if($type === 'community')
                            @if(isset($item->interest_category))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="compass" class="w-3.5 h-3.5 text-indigo-600"></i> Bidang Peminatan
                                    </span>
                                    <span class="font-bold text-indigo-700 text-xs block">{{ $item->interest_category }}</span>
                                </div>
                            @endif
                            @if(isset($item->activity_schedule))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-indigo-600"></i> Jadwal Rutin
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->activity_schedule }}</span>
                                </div>
                            @endif
                        @endif

                        <!-- Module 6: Quick Sale Specifics -->
                        @if($type === 'quick_sale')
                            @if(isset($item->price))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-amber-600"></i> Harga Penawaran Warga
                                    </span>
                                    <span class="font-mono font-black text-amber-700 text-sm block">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                            @if(isset($item->condition))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="check-square" class="w-3.5 h-3.5 text-amber-600"></i> Kondisi Barang
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->condition }}</span>
                                </div>
                            @endif
                            @if(isset($item->expires_at))
                                <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                    <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                        <i data-lucide="timer" class="w-3.5 h-3.5 text-amber-600"></i> Batas Kedaluwarsa Iklan
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs block">{{ $item->expires_at->format('d M Y') }}</span>
                                </div>
                            @endif
                        @endif

                        <!-- Direct Contact Person Phone -->
                        @if(isset($item->contact_phone) || isset($item->phone_whatsapp) || isset($item->contact_whatsapp) || isset($item->contact_person))
                            <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
                                <span class="text-[11px] font-semibold text-gray-400 block mb-1 flex items-center gap-1.5 uppercase">
                                    <i data-lucide="phone-call" class="w-3.5 h-3.5 text-emerald-600"></i> Kontak Langsung
                                </span>
                                @php
                                    $contactNum = $item->phone_whatsapp ?? ($item->contact_whatsapp ?? ($item->contact_phone ?? ''));
                                @endphp
                                @if($contactNum)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactNum) }}" 
                                       target="_blank" 
                                       class="font-mono font-bold text-emerald-700 hover:text-emerald-800 text-xs inline-flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3 h-3"></i> {{ $contactNum }}
                                    </a>
                                @endif
                                @if(isset($item->contact_person))
                                    <span class="text-[11px] text-gray-500 block mt-0.5">a.n. {{ $item->contact_person }}</span>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Description & Body -->
                <div class="space-y-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                        <i data-lucide="align-left" class="w-4 h-4 text-gold-600"></i>
                        <span>Deskripsi Lengkap / Rincian</span>
                    </h3>
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 text-xs sm:text-sm text-gray-800 whitespace-pre-line leading-relaxed font-sans">
                        {{ $item->description ?: 'Tidak ada deskripsi rinci yang disertakan oleh pengirim.' }}
                    </div>
                </div>

                <!-- Requirements (for Job Vacancy) -->
                @if($type === 'job' && !empty($item->requirements))
                    <div class="space-y-2.5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-blue-600"></i>
                            <span>Kualifikasi & Persyaratan Pelamar</span>
                        </h3>
                        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 text-xs sm:text-sm text-gray-800 whitespace-pre-line leading-relaxed font-sans">
                            {{ $item->requirements }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Moderation History & Audit Trail -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-7 space-y-4">
                <h3 class="text-sm font-black text-gray-900 flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-gold-600"></i>
                    <span>Riwayat Audit & Catatan Moderasi ({{ $logs->count() }})</span>
                </h3>

                @if($logs->count() > 0)
                    <div class="space-y-3 pt-2">
                        @foreach($logs as $log)
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-xs space-y-1.5">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900">{{ $log->moderator->name ?? 'Staf Admin' }}</span>
                                        <span class="text-gray-400">&bull;</span>
                                        <span class="text-[11px] font-mono text-gray-500">{{ $log->created_at->format('d M Y, H:i') }} WITA</span>
                                    </div>
                                    <div>
                                        @if($log->to_status === 'published')
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Disetujui</span>
                                        @elseif($log->to_status === 'rejected')
                                            <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px]">Ditolak</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-gray-200 text-gray-800 font-bold text-[10px]">{{ $log->to_status }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($log->note)
                                    <p class="text-gray-700 text-xs leading-relaxed pl-2 border-l-2 border-gold-500 font-medium mt-1">
                                        "{{ $log->note }}"
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic py-2">
                        Belum ada riwayat audit moderasi sebelumnya pada submission ini.
                    </p>
                @endif
            </div>

        </div>

        <!-- RIGHT 1 COLUMN: AUTHOR IDENTITY & DECISION ACTION HUB -->
        <div class="space-y-6">

            <!-- Author Identity Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Identitas Pengirim</span>
                    @if(($item->user->status ?? '') === 'verified')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i data-lucide="check-check" class="w-3 h-3"></i> NIK Valid
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            <i data-lucide="clock" class="w-3 h-3"></i> Belum Verif
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-900 border border-amber-200 flex items-center justify-center font-black text-lg shrink-0 shadow-2xs">
                        {{ strtoupper(substr($item->user->name ?? 'W', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $item->user->name ?? 'Warga Kutai Kartanegara' }}</h4>
                        <p class="text-xs text-gray-500 truncate">{{ $item->user->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-2 text-xs border-t border-gray-100">
                    @if($item->user->phone ?? false)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Nomor HP/WA:</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->user->phone) }}" 
                               target="_blank" 
                               class="text-emerald-700 hover:text-emerald-800 font-mono font-bold flex items-center gap-1">
                                <i data-lucide="phone" class="w-3 h-3"></i> {{ $item->user->phone }}
                            </a>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Bergabung:</span>
                        <span class="text-gray-800 font-mono text-[11px]">{{ $item->user->created_at ? $item->user->created_at->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Status Akun:</span>
                        <span class="text-gray-900 font-bold capitalize">{{ $item->user->status ?? 'Active' }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    @if(isset($item->user->id))
                        <a href="{{ route('admin.members.show', $item->user->id) }}" 
                           class="w-full py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-800 text-xs font-bold transition-colors inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-gold-600"></i>
                            <span>Buka Detail Profil Warga</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Moderation Decision Action Panel -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-gray-900 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-gold-600"></i>
                    <span>Keputusan Moderasi</span>
                </h3>

                <p class="text-xs text-gray-500 leading-relaxed">
                    Setujui untuk menayangkan konten ke portal publik atau tolak dengan menyertakan instruksi perbaikan untuk warga pengirim.
                </p>

                <div class="space-y-2.5 pt-2">
                    @if($item->status === 'pending' || $item->status === 'rejected')
                        <!-- Button Approve -->
                        <button type="button" 
                                @click="approveModalOpen = true" 
                                class="w-full py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-md transition-all inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Setujui & Publikasikan</span>
                        </button>
                    @endif

                    @if($item->status === 'pending' || $item->status === 'published')
                        <!-- Button Reject -->
                        <button type="button" 
                                @click="rejectModalOpen = true" 
                                class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs shadow-md transition-all inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            <span>{{ $item->status === 'published' ? 'Tarik & Tolak Konten' : 'Tolak Submission' }}</span>
                        </button>
                    @endif

                    <!-- Delete Button -->
                    <button type="button" 
                            @click="deleteModalOpen = true" 
                            class="w-full py-2.5 rounded-2xl bg-gray-50 hover:bg-rose-50 text-gray-600 hover:text-rose-700 border border-gray-200 text-xs font-bold transition-all inline-flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Hapus Konten Permanen</span>
                    </button>
                </div>
            </div>

            <!-- Quality & Safety Checklist -->
            <div class="bg-gray-50/80 rounded-3xl border border-gray-100 p-5 text-xs text-gray-600 space-y-3">
                <span class="font-bold text-gray-800 uppercase tracking-wider text-[11px] block flex items-center gap-1.5">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-gold-600"></i>
                    <span>Panduan Kurasi Redaksi</span>
                </span>
                <ul class="space-y-2 text-[11px] leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Pastikan nomor WhatsApp aktif & dapat dihubungi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Bebas dari unsur penipuan, judi online, atau SARA.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Relevan dengan wilayah administratif Kab. Kutai Kartanegara.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Foto otentik dan bukan hasil tangkapan layar buram.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    <!-- MODAL 1: APPROVAL CONFIRMATION MODAL -->
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
                        <h3 class="text-sm font-black text-gray-900">Konfirmasi Publikasi</h3>
                        <p class="text-xs text-gray-500">Konten akan langsung live di portal publik</p>
                    </div>
                </div>
                <button type="button" @click="approveModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200/80 text-xs">
                <span class="text-gray-400 block mb-0.5 text-[11px] font-semibold uppercase">Judul Konten:</span>
                <span class="font-bold text-gray-900 line-clamp-2">{{ $item->title ?? $item->name }}</span>
            </div>

            <form action="{{ route('admin.moderation.approve', ['type' => $type, 'id' => $item->id]) }}" method="POST" class="space-y-4">
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
                        <i data-lucide="check" class="w-4 h-4"></i> Setujui & Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: REJECTION PRESET MODAL -->
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
                        <p class="text-xs text-gray-500">Pilih alasan cepat atau tulis catatan kustom</p>
                    </div>
                </div>
                <button type="button" @click="rejectModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Smart Preset Chips -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-700 block">Preset Alasan Cepat (1-Klik):</label>
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

            <form action="{{ route('admin.moderation.reject', ['type' => $type, 'id' => $item->id]) }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 block">Alasan Penolakan untuk Warga <span class="text-rose-500">*</span></label>
                    <textarea name="rejection_reason" 
                              x-model="rejectionReason" 
                              rows="3" 
                              required 
                              placeholder="Ketik rincian penolakan spesifik agar pengirim mengetahui bagian mana yang perlu diperbaiki..." 
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
                <span class="font-bold text-gray-900 line-clamp-2">{{ $item->title ?? $item->name }}</span>
            </div>

            <form action="{{ route('admin.moderation.destroy', ['type' => $type, 'id' => $item->id]) }}" method="POST" class="space-y-4">
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

    <!-- LIGHTBOX MODAL FOR FULL RESOLUTION IMAGES -->
    <div x-show="activeLightboxImg" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
         @click="activeLightboxImg = null"
         @keydown.escape.window="activeLightboxImg = null">
        <div class="max-w-4xl max-h-[90vh] relative">
            <button type="button" @click="activeLightboxImg = null" class="absolute -top-10 right-0 text-white hover:text-gold-400 flex items-center gap-1 text-xs font-bold">
                <i data-lucide="x" class="w-5 h-5"></i> Tutup (ESC)
            </button>
            <img :src="activeLightboxImg" alt="Pratinjau Foto" class="max-w-full max-h-[85vh] rounded-2xl object-contain border border-white/20 shadow-2xl">
        </div>
    </div>

</div>
@endsection
