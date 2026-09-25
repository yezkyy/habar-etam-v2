@extends('layouts.admin')

@section('title', 'Kelola Laporan #' . $report->ticket_number . ' - Admin Habar Etam')
@section('page_title', 'Tinjau & Tindak Lanjut Laporan Warga')

@section('content')
<div class="space-y-6 pb-12" x-data="{ activeLightboxImg: null }">

    <!-- Top Breadcrumbs & Action Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-600 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.reports.index') }}" class="hover:text-gold-600 transition-colors">Lapor Etam</a>
            <span>/</span>
            <span class="text-gray-900 font-bold font-mono">#{{ $report->ticket_number }}</span>
        </div>

        <div class="flex items-center gap-2">
            @if(\Illuminate\Support\Facades\Route::has('reports.show'))
                <a href="{{ route('reports.show', $report->ticket_number) }}" 
                   target="_blank" 
                   class="h-9 px-3.5 rounded-xl text-xs font-bold text-emerald-700 hover:text-white bg-emerald-50 hover:bg-emerald-600 border border-emerald-200 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Buka Halaman Publik</span>
                </a>
            @endif

            <a href="{{ route('admin.reports.index') }}" 
               class="h-9 px-3.5 rounded-xl text-xs font-bold bg-gray-900 hover:bg-gold-500 text-white hover:text-black transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- 4-Step Visual Workflow Progress Bar -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-7">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-1.5">
                <i data-lucide="git-commit" class="w-4 h-4 text-gold-600"></i> Alur Penanganan Aduan Redaksi PT SCM
            </span>
            @if($report->is_featured_live || $report->status === 'live_agenda')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500 text-white animate-pulse flex items-center gap-1 shadow-2xs">
                    <i data-lucide="radio" class="w-3 h-3"></i> Masuk Agenda Siaran Live Studio
                </span>
            @endif
        </div>

        @php
            $step = $report->workflow_step;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <!-- Step 1 -->
            <div class="p-3.5 rounded-2xl border transition-all {{ $step >= 1 ? 'bg-amber-50/50 border-amber-300 ring-2 ring-amber-500/10' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs {{ $step >= 1 ? 'bg-amber-500 text-white shadow-2xs' : 'bg-gray-200 text-gray-600' }}">
                        1
                    </div>
                    <span class="font-bold text-xs {{ $step >= 1 ? 'text-amber-900' : 'text-gray-500' }}">Verifikasi Awal</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1 pl-9">Validasi identitas pelapor & data laporan.</p>
            </div>

            <!-- Step 2 -->
            <div class="p-3.5 rounded-2xl border transition-all {{ $step >= 2 ? 'bg-blue-50/50 border-blue-300 ring-2 ring-blue-500/10' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs {{ $step >= 2 ? 'bg-blue-600 text-white shadow-2xs' : 'bg-gray-200 text-gray-600' }}">
                        2
                    </div>
                    <span class="font-bold text-xs {{ $step >= 2 ? 'text-blue-900' : 'text-gray-500' }}">Telaah Redaksi</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1 pl-9">Investigasi & konfirmasi lapangan SCM.</p>
            </div>

            <!-- Step 3 -->
            <div class="p-3.5 rounded-2xl border transition-all {{ $step >= 3 ? 'bg-purple-50/50 border-purple-300 ring-2 ring-purple-500/10' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs {{ $step >= 3 ? 'bg-purple-600 text-white shadow-2xs' : 'bg-gray-200 text-gray-600' }}">
                        3
                    </div>
                    <span class="font-bold text-xs {{ $step >= 3 ? 'text-purple-900' : 'text-gray-500' }}">Agenda Live / OPD</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1 pl-9">Disuarakan on-air & diteruskan ke dinas.</p>
            </div>

            <!-- Step 4 -->
            <div class="p-3.5 rounded-2xl border transition-all {{ $step >= 4 ? 'bg-emerald-50/50 border-emerald-300 ring-2 ring-emerald-500/10' : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs {{ $step >= 4 ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-gray-200 text-gray-600' }}">
                        4
                    </div>
                    <span class="font-bold text-xs {{ $step >= 4 ? 'text-emerald-900' : 'text-gray-500' }}">Tuntas / Selesai</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1 pl-9">Masalah ditangani OPD terkait Kukar.</p>
            </div>
        </div>

        @if($report->status === 'rejected')
            <div class="mt-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 flex items-center gap-2.5">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span class="font-bold">Laporan ini berstatus Ditolak / Dibatalkan (Spam atau informasi tidak valid).</span>
            </div>
        @endif
    </div>

    <!-- Main Two-Column Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- LEFT 2 COLUMNS: DETAIL LAPORAN & BUKTI -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Hero Detail Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between pb-6 border-b border-gray-100 gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs font-black text-amber-800 bg-amber-100 px-3 py-1 rounded-xl border border-amber-200">
                                #{{ $report->ticket_number }}
                            </span>
                            @php
                                $catStyle = match($report->category) {
                                    'infrastruktur' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'kebersihan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'pelayanan_publik' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'keamanan' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'lingkungan' => 'bg-teal-50 text-teal-700 border-teal-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-bold border capitalize {{ $catStyle }}">
                                {{ str_replace('_', ' ', $report->category) }}
                            </span>
                        </div>

                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">
                            {{ $report->title }}
                        </h2>

                        <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i>
                                Dikirim {{ $report->created_at->translatedFormat('d F Y, H:i') }} WITA
                            </span>
                            @if($report->resolved_at)
                                <span class="flex items-center gap-1 text-emerald-700 font-bold">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    Tuntas {{ $report->resolved_at->format('d/m/Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Status Pill -->
                    <div class="shrink-0">
                        @if($report->status == 'resolved')
                            <div class="px-3.5 py-2 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs">
                                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                                <span>SELESAI / TUNTAS</span>
                            </div>
                        @elseif($report->status == 'live_agenda')
                            <div class="px-3.5 py-2 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs animate-pulse">
                                <i data-lucide="radio" class="w-4 h-4 text-rose-600"></i>
                                <span>AGENDA LIVE SCM</span>
                            </div>
                        @elseif($report->status == 'processing_editorial')
                            <div class="px-3.5 py-2 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs">
                                <i data-lucide="newspaper" class="w-4 h-4 text-blue-600"></i>
                                <span>TELAAH REDAKSI</span>
                            </div>
                        @elseif($report->status == 'pending_verification')
                            <div class="px-3.5 py-2 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 font-black text-xs inline-flex items-center gap-2 shadow-2xs">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                                <span>MENUNGGU VERIF</span>
                            </div>
                        @else
                            <div class="px-3.5 py-2 rounded-2xl bg-gray-100 border border-gray-200 text-gray-700 font-black text-xs inline-flex items-center gap-2">
                                <i data-lucide="x-circle" class="w-4 h-4 text-gray-500"></i>
                                <span>DITOLAK</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Location & GPS Box -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50/80 p-4.5 rounded-2xl border border-gray-100 text-xs">
                    <div>
                        <span class="text-gray-400 block mb-1 font-semibold uppercase text-[11px] flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gold-600"></i> Kecamatan Wilayah
                        </span>
                        <span class="font-bold text-gray-900 text-sm">
                            Kec. {{ $report->location_district ?: ($report->district ?? 'Kutai Kartanegara') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-400 block mb-1 font-semibold uppercase text-[11px] flex items-center gap-1.5">
                            <i data-lucide="navigation" class="w-3.5 h-3.5 text-gold-600"></i> Alamat / Patokan Lokasi
                        </span>
                        <span class="font-medium text-gray-900 leading-relaxed block">
                            {{ $report->address ?: '-' }}
                        </span>
                    </div>

                    @if($report->latitude && $report->longitude)
                        <div class="sm:col-span-2 pt-2 border-t border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-gray-500">Koordinat GPS:</span>
                                <span class="font-mono font-bold text-gray-800 bg-white px-2 py-0.5 rounded-md border border-gray-200">{{ $report->latitude }}, {{ $report->longitude }}</span>
                            </div>
                            <a href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}" 
                               target="_blank" 
                               class="text-xs font-bold text-gold-600 hover:text-gold-700 inline-flex items-center gap-1">
                                <span>Buka Google Maps</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Problem Description -->
                <div class="space-y-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                        <i data-lucide="align-left" class="w-4 h-4 text-gold-600"></i>
                        <span>Uraian Masalah Pengaduan Warga</span>
                    </h3>
                    <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 text-xs sm:text-sm text-gray-800 whitespace-pre-line leading-relaxed font-sans">
                        {{ $report->description }}
                    </div>
                </div>

                <!-- Media Photo Gallery -->
                @if($report->media && $report->media->count() > 0)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                                <i data-lucide="image" class="w-4 h-4 text-gold-600"></i>
                                <span>Foto / Bukti Lapangan ({{ $report->media->count() }})</span>
                            </h3>
                            <span class="text-[11px] text-gray-400">Klik untuk zoom foto</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach($report->media as $med)
                                <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 relative group cursor-pointer shadow-2xs"
                                     @click="activeLightboxImg = '{{ asset('storage/' . $med->file_path) }}'">
                                    <img src="{{ asset('storage/' . $med->file_path) }}" alt="Bukti Laporan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Editorial Summary (if available) -->
                @if($report->editorial_summary)
                    <div class="p-5 bg-blue-50/70 border border-blue-200 rounded-2xl space-y-2 text-xs">
                        <div class="flex items-center gap-2 font-bold text-blue-900">
                            <i data-lucide="newspaper" class="w-4 h-4 text-blue-600"></i>
                            <span>Ringkasan Investigasi Redaksi PT SCM:</span>
                        </div>
                        <p class="text-blue-800 leading-relaxed pl-6 font-medium">{{ $report->editorial_summary }}</p>
                    </div>
                @endif

                <!-- Admin Internal Notes (if available) -->
                @if($report->admin_notes)
                    <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl space-y-1 text-xs">
                        <div class="flex items-center gap-2 font-bold text-amber-900">
                            <i data-lucide="file-text" class="w-4 h-4 text-amber-600"></i>
                            <span>Catatan Disposisi Internal / Koordinasi OPD:</span>
                        </div>
                        <p class="text-amber-800 leading-relaxed pl-6">{{ $report->admin_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Audit Trail & Update History -->
            @if(isset($logs) && $logs->count() > 0)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-7 space-y-4">
                    <h3 class="text-sm font-black text-gray-900 flex items-center gap-2">
                        <i data-lucide="history" class="w-4 h-4 text-gold-600"></i>
                        <span>Riwayat Penanganan & Audit Redaksi ({{ $logs->count() }})</span>
                    </h3>

                    <div class="space-y-3 pt-2">
                        @foreach($logs as $log)
                            @php
                                $meta = $log->metadata ?? ($log->details ?? []);
                            @endphp
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-xs space-y-1.5">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900">{{ $log->user->name ?? 'Staf Redaksi' }}</span>
                                        <span class="text-gray-400">&bull;</span>
                                        <span class="text-[11px] font-mono text-gray-500">{{ $log->created_at->format('d M Y, H:i') }} WITA</span>
                                    </div>
                                    <div>
                                        <span class="px-2.5 py-0.5 rounded-full bg-gray-200 text-gray-800 font-bold text-[10px] uppercase font-mono">
                                            {{ $log->action }}
                                        </span>
                                    </div>
                                </div>
                                @if(is_array($meta) && count($meta) > 0)
                                    <p class="text-gray-700 text-xs leading-relaxed pl-2 border-l-2 border-gold-500 font-mono mt-1">
                                        @if(isset($meta['from']) && isset($meta['to']))
                                            Status: <span class="font-bold">{{ strtoupper($meta['from']) }}</span> → <span class="font-bold text-gold-600">{{ strtoupper($meta['to']) }}</span>
                                        @endif
                                        @if(isset($meta['notes']) && $meta['notes'])
                                            &bull; Catatan: "{{ $meta['notes'] }}"
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- RIGHT 1 COLUMN: IDENTITAS PELAPOR & CONTROL CENTER -->
        <div class="space-y-6">

            <!-- Reporter Identity Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Identitas Pelapor</span>
                    @if(($report->user->status ?? '') === 'verified')
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
                        {{ strtoupper(substr($report->user->name ?? 'W', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $report->user->name ?? 'Warga Kukar' }}</h4>
                        <p class="text-xs text-gray-500 truncate">{{ $report->user->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-2 text-xs border-t border-gray-100">
                    @if($report->user->phone ?? false)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Nomor HP/WA:</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->user->phone) }}" 
                               target="_blank" 
                               class="text-emerald-700 hover:text-emerald-800 font-mono font-bold flex items-center gap-1">
                                <i data-lucide="phone" class="w-3 h-3"></i> {{ $report->user->phone }}
                            </a>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Wilayah Profil:</span>
                        <span class="text-gray-900 font-medium">Kec. {{ $report->user->profile->district ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Terdaftar Sejak:</span>
                        <span class="text-gray-700 font-mono text-[11px]">{{ $report->user->created_at ? $report->user->created_at->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    @if(isset($report->user->id))
                        <a href="{{ route('admin.members.show', $report->user->id) }}" 
                           class="w-full py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 border border-gray-200 text-gray-800 text-xs font-bold transition-colors inline-flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-gold-600"></i>
                            <span>Buka Profil Lengkap Warga</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Control Center: Status Update & Broadcast Form -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-gray-900 flex items-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 text-gold-600"></i>
                    <span>Tindak Lanjut & Status Laporan</span>
                </h3>

                <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Status Selection -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 block">Ubah Status Alur <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs font-bold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs cursor-pointer">
                            <option value="pending_verification" {{ $report->status == 'pending_verification' ? 'selected' : '' }}>1. Menunggu Verifikasi</option>
                            <option value="processing_editorial" {{ $report->status == 'processing_editorial' ? 'selected' : '' }}>2. Diproses Redaksi PT SCM</option>
                            <option value="live_agenda" {{ $report->status == 'live_agenda' ? 'selected' : '' }}>3. Masuk Agenda Siaran Live Studio</option>
                            <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>4. Selesai / Teratasi (Disposisi OPD)</option>
                            <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak (Spam / Tidak Valid)</option>
                        </select>
                    </div>

                    <!-- Live On-Air Checkbox -->
                    <div class="p-3 bg-rose-50/70 border border-rose-200 rounded-2xl">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" 
                                   name="is_featured_live" 
                                   value="1" 
                                   {{ $report->is_featured_live ? 'checked' : '' }} 
                                   class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 border-gray-300 cursor-pointer">
                            <span class="text-xs font-black text-rose-900 flex items-center gap-1.5">
                                <i data-lucide="radio" class="w-3.5 h-3.5 text-rose-600"></i> Siarkan di Live Agenda Studio Redaksi
                            </span>
                        </label>
                    </div>

                    <!-- Editorial Summary -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 block">Ringkasan Redaksi SCM (Publik / On-Air)</label>
                        <textarea name="editorial_summary" 
                                  rows="3" 
                                  placeholder="Tulis ringkasan hasil investigasi / bulletin siaran..." 
                                  class="w-full p-3 rounded-2xl border border-gray-200 text-xs bg-gray-50 focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all">{{ $report->editorial_summary }}</textarea>
                    </div>

                    <!-- Internal Admin Notes -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 block">Catatan Disposisi OPD / Internal Dinas</label>
                        <input type="text" 
                               name="admin_notes" 
                               value="{{ $report->admin_notes }}" 
                               placeholder="Contoh: Diteruskan ke Dinas PU Kukar tgl 21 Sep..." 
                               class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs bg-gray-50 focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black font-black text-xs shadow-md transition-all inline-flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4 stroke-[2.5]"></i>
                            <span>Simpan Pembaruan Status</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SOP Guidelines Card -->
            <div class="bg-gray-50/80 rounded-3xl border border-gray-100 p-5 text-xs text-gray-600 space-y-3">
                <span class="font-bold text-gray-800 uppercase tracking-wider text-[11px] block flex items-center gap-1.5">
                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-gold-600"></i>
                    <span>SOP Penanganan Aduan Lapor Etam</span>
                </span>
                <ul class="space-y-2 text-[11px] leading-relaxed">
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Verifikasi keabsahan titik lokasi & keaslian foto bukti.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Konfirmasi pelapor melalui WhatsApp jika data kurang jelas.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Disposisi cepat ke dinas/OPD terkait maksimal 1x24 jam.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Gunakan status Live Agenda untuk isu krusial publik.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    <!-- LIGHTBOX MODAL FOR FULL RESOLUTION REPORT PHOTOS -->
    <div x-show="activeLightboxImg" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
         @click="activeLightboxImg = null"
         @keydown.escape.window="activeLightboxImg = null">
        <div class="max-w-4xl max-h-[90vh] relative">
            <button type="button" @click="activeLightboxImg = null" class="absolute -top-10 right-0 text-white hover:text-gold-400 flex items-center gap-1 text-xs font-bold">
                <i data-lucide="x" class="w-5 h-5"></i> Tutup (ESC)
            </button>
            <img :src="activeLightboxImg" alt="Bukti Laporan Penuh" class="max-w-full max-h-[85vh] rounded-2xl object-contain border border-white/20 shadow-2xl">
        </div>
    </div>

</div>
@endsection
