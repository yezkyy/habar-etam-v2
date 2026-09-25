@extends('layouts.admin')

@section('title', 'Manajemen Anggota Warga Kukar - Admin Habar Etam')
@section('page_title', 'Manajemen & Direktori Warga')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Overview Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-gray-900 via-gray-800 to-black text-white p-6 sm:p-7 rounded-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-32 h-32 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-gold-500/20 text-gold-400 border border-gold-500/30 mb-2">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-gold-400"></i> Database Kependudukan & Warga Kukar
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                Direktori Anggota Warga
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-1 max-w-2xl leading-relaxed">
                Kelola status verifikasi NIK KTP, pantau keaktifan kontribusi warga, serta tangani status penangguhan akun secara terpusat.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2.5 sm:self-start lg:self-center">
            <a href="{{ route('admin.verifications.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gold-500 hover:bg-gold-400 text-black font-bold text-xs rounded-xl transition-all shadow-sm">
                <i data-lucide="scan-face" class="w-4 h-4"></i> Antrean Verifikasi NIK ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('admin.export.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl border border-white/15 transition-all backdrop-blur-xs">
                <i data-lucide="download" class="w-4 h-4 text-gold-400"></i> Export Data
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Warga -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Total Warga</span>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 block">{{ number_format($stats['total']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Terdaftar di database sistem
            </div>
        </div>

        <!-- Terverifikasi -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Warga Terverifikasi</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1 block">{{ number_format($stats['verified']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-emerald-700">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> NIK KTP Kukar tervalidasi
            </div>
        </div>

        <!-- Pending Review -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Menunggu Review NIK</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-600 mt-1 block">{{ number_format($stats['pending']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="clock-4" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-amber-700">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span> Butuh approval dokumen
            </div>
        </div>

        <!-- Kontributor Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Kontributor Aktif</span>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 block">{{ number_format($stats['active_contributors']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-gold-500 text-black flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="sparkles" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-gold-500"></span> Pengirim laporan & konten
            </div>
        </div>
    </div>

    <!-- Status Tabs Navigation -->
    <div class="bg-white p-2 rounded-2xl border border-gray-100 shadow-xs flex flex-wrap items-center gap-1">
        @php
            $currentStatus = request('status', '');
        @endphp
        
        <a href="{{ route('admin.members.index', array_merge(request()->except(['status', 'page']))) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === '' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <span>Semua Warga</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === '' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $counts['all'] }}</span>
        </a>

        <a href="{{ route('admin.members.index', array_merge(request()->except(['status', 'page']), ['status' => 'verified'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'verified' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            <span>Terverifikasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'verified' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $counts['verified'] }}</span>
        </a>

        <a href="{{ route('admin.members.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-700' }}">
            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
            <span>Menunggu NIK</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">{{ $counts['pending'] }}</span>
        </a>

        <a href="{{ route('admin.members.index', array_merge(request()->except(['status', 'page']), ['status' => 'suspended'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'suspended' ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i data-lucide="ban" class="w-3.5 h-3.5"></i>
            <span>Suspended</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'suspended' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $counts['suspended'] }}</span>
        </a>

        <a href="{{ route('admin.members.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 hover:bg-rose-50 hover:text-rose-700' }}">
            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
            <span>Ditolak</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'rejected' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.members.index') }}" method="GET" x-data="adminLiveFilter" @change="$el.submit()" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Input with Icon -->
                <div class="relative flex-1 min-w-[260px] flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari nama warga, email, WhatsApp, NIK, kelurahan..." 
                           class="admin-search-input"
                           x-on:input.debounce.450ms="$el.form.submit()">
                </div>

                <!-- District Filter -->
                <div class="min-w-[180px]">
                    <select name="district" class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs">
                        <option value="">Semua Kecamatan (Kukar)</option>
                        @foreach($districts as $d)
                            <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>Kec. {{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="min-w-[170px]">
                    <select name="sort" class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru Terdaftar</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Paling Lama</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama Warga (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Warga (Z-A)</option>
                        <option value="contributions_desc" {{ request('sort') == 'contributions_desc' ? 'selected' : '' }}>Kontribusi Terbanyak</option>
                        <option value="reports_desc" {{ request('sort') == 'reports_desc' ? 'selected' : '' }}>Laporan Terbanyak</option>
                    </select>
                </div>

                <button type="submit" class="h-10 px-5 bg-black hover:bg-gold-500 hover:text-black text-white text-xs font-bold rounded-xl shadow-sm transition-all inline-flex items-center gap-1.5 shrink-0">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan Filter
                </button>

                @if(request()->hasAny(['q', 'status', 'district', 'sort']))
                    <a href="{{ route('admin.members.index') }}" class="h-10 px-3.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors inline-flex items-center gap-1">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Members Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 border-b border-gray-100 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Profil & Akun Warga</th>
                        <th class="px-6 py-4">Kontak & WhatsApp</th>
                        <th class="px-6 py-4">Domisili & NIK Kukar</th>
                        <th class="px-6 py-4">Status Akun</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $m)
                        <tr class="hover:bg-amber-50/20 transition-colors group">
                            <!-- Profil & Identitas -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3.5">
                                    <div class="relative shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-900 border border-amber-300/60 flex items-center justify-center font-bold text-sm shadow-xs">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                        @if($m->status === 'verified')
                                            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 text-white rounded-full flex items-center justify-center ring-2 ring-white shadow-xs" title="Warga Terverifikasi">
                                                <i data-lucide="check" class="w-2.5 h-2.5 stroke-[3]"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.members.show', $m->id) }}" class="font-bold text-gray-900 hover:text-gold-600 text-sm transition-colors block">
                                            {{ $m->name }}
                                        </a>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-400 mt-0.5">
                                            <span class="font-mono text-gray-400">#ID-{{ str_pad($m->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            @if($m->profile && $m->profile->display_name && $m->profile->display_name !== $m->name)
                                                <span>&bull;</span>
                                                <span class="text-gray-500">"{{ $m->profile->display_name }}"</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kontak & WhatsApp -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <a href="mailto:{{ $m->email }}" class="text-gray-700 hover:text-black font-medium text-xs flex items-center gap-1.5 transition-colors" title="Kirim Email">
                                        <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400"></i>
                                        <span>{{ $m->email }}</span>
                                    </a>
                                    @if($m->phone)
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $m->phone);
                                            if (str_starts_with($cleanPhone, '0')) {
                                                $cleanPhone = '62' . substr($cleanPhone, 1);
                                            }
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-500 font-mono text-[11px] flex items-center gap-1">
                                                <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                                                {{ $m->phone }}
                                            </span>
                                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors" title="Hubungi via WhatsApp">
                                                <i data-lucide="message-circle" class="w-2.5 h-2.5"></i> WA
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px] italic">No telp belum diisi</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Domisili & NIK Kukar -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-gold-600"></i>
                                        Kec. {{ $m->profile->district ?? ($m->verification->district ?? 'Belum Diisi') }}
                                    </span>
                                    <div class="text-[11px] text-gray-500 flex items-center gap-1 font-mono">
                                        <i data-lucide="credit-card" class="w-3 h-3 text-gray-400"></i>
                                        @if($m->verification && $m->verification->masked_nik)
                                            <span class="font-bold text-gray-700">{{ $m->verification->masked_nik }}</span>
                                        @else
                                            <span class="text-gray-400 italic">Belum unggah KTP</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Status Akun -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($m->status === 'verified')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        Terverifikasi
                                    </span>
                                @elseif($m->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <i data-lucide="clock-4" class="w-3.5 h-3.5 text-amber-600"></i>
                                        Menunggu NIK
                                    </span>
                                @elseif($m->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60 shadow-2xs">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200 shadow-2xs">
                                        <i data-lucide="ban" class="w-3.5 h-3.5 text-gray-600"></i>
                                        {{ ucfirst($m->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Cepat -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <!-- Quick Change Status Button -->
                                    <button type="button" 
                                            onclick="openStatusModal({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ addslashes($m->email) }}', '{{ $m->status }}')"
                                            class="h-9 px-3 rounded-xl text-xs font-bold text-gray-700 hover:text-brand-black bg-gray-100 hover:bg-brand-gold border border-gray-200/80 hover:border-brand-gold inline-flex items-center gap-1.5 transition-all shadow-2xs group/act cursor-pointer" 
                                            title="Ubah Status Cepat">
                                        <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-gray-500 group-hover/act:text-brand-black transition-colors"></i>
                                        <span>Status</span>
                                    </button>

                                    <!-- Detail Button -->
                                    <a href="{{ route('admin.members.show', $m->id) }}" 
                                       class="h-9 px-4 rounded-xl text-xs font-bold bg-brand-black hover:bg-brand-gold text-white hover:text-brand-black transition-all duration-150 inline-flex items-center gap-1.5 shadow-2xs group/btn cursor-pointer">
                                        <span>Detail</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-brand-gold group-hover/btn:text-brand-black group-hover/btn:translate-x-0.5 transition-all"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 mb-4 shadow-inner">
                                        <i data-lucide="users" class="w-8 h-8 text-gray-300"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Data Warga Tidak Ditemukan</h3>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        Tidak ada anggota warga yang cocok dengan parameter pencarian atau filter yang Anda terapkan.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.members.index') }}" class="px-4 py-2 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-xl transition-all inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
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

        {{ $members->links() }}
    </div>

    <!-- High-End Quick Status Update Modal -->
    <div id="member-status-modal" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-md hidden items-center justify-center p-4">
        
        <!-- Backdrop click zone -->
        <div onclick="closeStatusModal()" class="fixed inset-0"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 max-w-lg w-full overflow-hidden z-10 animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-black text-white p-6 relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-gold-500/10 rounded-full blur-xl pointer-events-none"></div>
                
                <div class="flex items-start justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3.5">
                        <div id="modal-member-avatar" class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-gold-500 text-black flex items-center justify-center font-black text-lg shadow-sm border border-gold-300 shrink-0">
                            W
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-white/10 text-gold-400 border border-white/15">Ubah Status Akun</span>
                                <span id="modal-member-id" class="text-[11px] font-mono text-gray-400">#ID-0000</span>
                            </div>
                            <h3 id="modal-member-name" class="text-base font-bold text-white mt-1 leading-tight">Nama Warga</h3>
                            <p id="modal-member-email" class="text-xs text-gray-400 mt-0.5 flex items-center gap-1 font-mono"></p>
                        </div>
                    </div>
                    <button type="button" onclick="closeStatusModal()" class="text-gray-400 hover:text-white p-2 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Form -->
            <form id="member-status-form" method="POST" action="" class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="selected-status-input" name="status" value="verified">

                <!-- Interactive Status Selection Grid -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2.5">
                        Pilih Status Keabsahan Akun
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        
                        <!-- Option: Verified -->
                        <div onclick="selectModalStatus('verified')" 
                             id="status-card-verified"
                             class="status-option-card p-3.5 rounded-2xl border-2 border-gray-100 hover:border-emerald-300 bg-white hover:bg-emerald-50/30 cursor-pointer transition-all flex items-start gap-3 relative group">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 group-hover:text-emerald-800">Verified</span>
                                    <span class="status-check-indicator w-4 h-4 rounded-full bg-emerald-500 text-white hidden items-center justify-center text-[10px]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 stroke-[3]"></i>
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Warga Sah KTP Kukar, akses penuh sistem.</p>
                            </div>
                        </div>

                        <!-- Option: Pending -->
                        <div onclick="selectModalStatus('pending')" 
                             id="status-card-pending"
                             class="status-option-card p-3.5 rounded-2xl border-2 border-gray-100 hover:border-amber-300 bg-white hover:bg-amber-50/30 cursor-pointer transition-all flex items-start gap-3 relative group">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 group-hover:text-amber-800">Pending NIK</span>
                                    <span class="status-check-indicator w-4 h-4 rounded-full bg-amber-500 text-white hidden items-center justify-center text-[10px]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 stroke-[3]"></i>
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Menunggu review dokumen KTP warga.</p>
                            </div>
                        </div>

                        <!-- Option: Suspended -->
                        <div onclick="selectModalStatus('suspended')" 
                             id="status-card-suspended"
                             class="status-option-card p-3.5 rounded-2xl border-2 border-gray-100 hover:border-gray-400 bg-white hover:bg-gray-50 cursor-pointer transition-all flex items-start gap-3 relative group">
                            <div class="w-8 h-8 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="ban" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900">Suspended</span>
                                    <span class="status-check-indicator w-4 h-4 rounded-full bg-gray-800 text-white hidden items-center justify-center text-[10px]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 stroke-[3]"></i>
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Akses fitur warga dibekukan sementara.</p>
                            </div>
                        </div>

                        <!-- Option: Rejected -->
                        <div onclick="selectModalStatus('rejected')" 
                             id="status-card-rejected"
                             class="status-option-card p-3.5 rounded-2xl border-2 border-gray-100 hover:border-rose-300 bg-white hover:bg-rose-50/30 cursor-pointer transition-all flex items-start gap-3 relative group">
                            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 group-hover:text-rose-800">Rejected</span>
                                    <span class="status-check-indicator w-4 h-4 rounded-full bg-rose-500 text-white hidden items-center justify-center text-[10px]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 stroke-[3]"></i>
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">Dokumen ditolak / data tidak valid.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Reason Field with Quick Tags -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Alasan Pembaruan Status (Jejak Audit Log)
                    </label>
                    <input type="text" 
                           id="modal-member-reason" 
                           name="reason" 
                           placeholder="Tulis alasan perubahan status ini..." 
                           class="w-full h-11 px-4 rounded-xl border border-gray-200 text-xs bg-gray-50 hover:bg-white focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs">
                    
                    <!-- Quick Tag Chips -->
                    <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                        <span class="text-[10px] font-semibold text-gray-400">Pilih Cepat:</span>
                        <button type="button" onclick="setQuickReason('Dokumen KTP valid & terverifikasi resmi')" class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 hover:bg-gold-100 text-gray-700 hover:text-black transition-colors">
                            ✓ KTP Sah
                        </button>
                        <button type="button" onclick="setQuickReason('NIK tidak cocok dengan data Disdukcapil Kukar')" class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-800 transition-colors">
                            ✗ NIK Tidak Cocok
                        </button>
                        <button type="button" onclick="setQuickReason('Pelanggaran ketentuan konten berulang')" class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-800 transition-colors">
                            ⚠ Pelanggaran Aturan
                        </button>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-brand-gold/25 inline-flex items-center gap-2 active:scale-95 cursor-pointer">
                        <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                        <span>Simpan Status</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function selectModalStatus(statusKey) {
        document.getElementById('selected-status-input').value = statusKey;

        // Reset all cards
        document.querySelectorAll('.status-option-card').forEach(card => {
            card.classList.remove('border-emerald-500', 'bg-emerald-50/50', 'ring-2', 'ring-emerald-500/20',
                                   'border-amber-500', 'bg-amber-50/50', 'ring-amber-500/20',
                                   'border-gray-800', 'bg-gray-100', 'ring-gray-800/20',
                                   'border-rose-500', 'bg-rose-50/50', 'ring-rose-500/20');
            card.classList.add('border-gray-100');
            const check = card.querySelector('.status-check-indicator');
            if (check) {
                check.classList.add('hidden');
                check.classList.remove('flex');
            }
        });

        // Activate selected card
        const targetCard = document.getElementById('status-card-' + statusKey);
        if (targetCard) {
            targetCard.classList.remove('border-gray-100');
            const check = targetCard.querySelector('.status-check-indicator');
            if (check) {
                check.classList.remove('hidden');
                check.classList.add('flex');
            }

            if (statusKey === 'verified') {
                targetCard.classList.add('border-emerald-500', 'bg-emerald-50/50', 'ring-2', 'ring-emerald-500/20');
            } else if (statusKey === 'pending') {
                targetCard.classList.add('border-amber-500', 'bg-amber-50/50', 'ring-2', 'ring-amber-500/20');
            } else if (statusKey === 'suspended') {
                targetCard.classList.add('border-gray-800', 'bg-gray-100', 'ring-2', 'ring-gray-800/20');
            } else if (statusKey === 'rejected') {
                targetCard.classList.add('border-rose-500', 'bg-rose-50/50', 'ring-2', 'ring-rose-500/20');
            }
        }
    }

    function setQuickReason(text) {
        const reasonInput = document.getElementById('modal-member-reason');
        if (reasonInput) {
            reasonInput.value = text;
            reasonInput.focus();
        }
    }

    function openStatusModal(id, name, email, status) {
        const modal = document.getElementById('member-status-modal');
        const form = document.getElementById('member-status-form');
        const nameEl = document.getElementById('modal-member-name');
        const emailEl = document.getElementById('modal-member-email');
        const idEl = document.getElementById('modal-member-id');
        const avatarEl = document.getElementById('modal-member-avatar');
        
        if (form && modal) {
            form.action = "{{ url('/admin/warga') }}/" + id + "/status";
            if (nameEl) nameEl.textContent = name;
            if (emailEl) emailEl.innerHTML = `<i data-lucide="mail" class="w-3 h-3 inline text-gray-400"></i> ${email}`;
            if (idEl) idEl.textContent = '#ID-' + String(id).padStart(4, '0');
            if (avatarEl) avatarEl.textContent = (name && name.length > 0) ? name.charAt(0).toUpperCase() : 'W';
            
            selectModalStatus(status || 'verified');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            if (window.lucide) {
                window.lucide.createIcons();
            }
        }
    }

    function closeStatusModal() {
        const modal = document.getElementById('member-status-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endpush
@endsection
