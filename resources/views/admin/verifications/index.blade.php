@extends('layouts.admin')

@section('title', 'Verifikasi NIK Warga Kukar - Admin Habar Etam')
@section('page_title', 'Antrean Verifikasi Kependudukan NIK Kukar')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-gray-900 via-gray-800 to-black text-white p-6 sm:p-7 rounded-3xl shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-32 h-32 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-gold-500/20 text-gold-400 border border-gold-500/30 mb-2">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-gold-400"></i> Validasi Identitas Kependudukan Kukar
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                Antrean & Riwayat Verifikasi NIK
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-1 max-w-2xl leading-relaxed">
                Tinjau dan validasi berkas NIK KTP warga Kutai Kartanegara sebelum memberikan hak akses penuh dan centang biru terverifikasi di aplikasi.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 sm:self-start lg:self-center">
            <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl border border-white/15 transition-all backdrop-blur-xs">
                <i data-lucide="users" class="w-4 h-4 text-gold-400"></i> Direktori Warga
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Permohonan -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Total Permohonan</span>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 block">{{ number_format($stats['total']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="credit-card" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span> Pengajuan tercatat di sistem
            </div>
        </div>

        <!-- Butuh Review (Pending) -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Menunggu Review</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-600 mt-1 block">{{ number_format($stats['pending']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="clock-4" class="w-6 h-6 text-amber-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-amber-700">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Butuh tindakan admin
            </div>
        </div>

        <!-- Disetujui (Approved) -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Tervalidasi (Sah)</span>
                    <span class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1 block">{{ number_format($stats['approved']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="shield-check" class="w-6 h-6 text-emerald-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-emerald-700">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> NIK KTP Kukar tervalidasi
            </div>
        </div>

        <!-- Ditolak (Rejected) -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Berkas Ditolak</span>
                    <span class="text-2xl sm:text-3xl font-black text-rose-600 mt-1 block">{{ number_format($stats['rejected']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                    <i data-lucide="x-circle" class="w-6 h-6 text-rose-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-rose-700">
                <span class="inline-block w-2 h-2 rounded-full bg-rose-500"></span> Perlu perbaikan berkas
            </div>
        </div>
    </div>

    <!-- Status Tabs Navigation -->
    <div class="bg-white p-2 rounded-2xl border border-gray-100 shadow-xs flex flex-wrap items-center gap-1">
        @php
            $currentStatus = request('status', '');
        @endphp
        
        <a href="{{ route('admin.verifications.index', array_merge(request()->except(['status', 'page']))) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === '' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <span>Semua Antrean</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === '' ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700' }}">{{ $counts['all'] }}</span>
        </a>

        <a href="{{ route('admin.verifications.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-700' }}">
            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
            <span>Menunggu Review</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">{{ $counts['pending'] }}</span>
        </a>

        <a href="{{ route('admin.verifications.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
            <span>Disetujui</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $counts['approved'] }}</span>
        </a>

        <a href="{{ route('admin.verifications.index', array_merge(request()->except(['status', 'page']), ['status' => 'rejected'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 hover:bg-rose-50 hover:text-rose-700' }}">
            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
            <span>Ditolak</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'rejected' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.verifications.index') }}" method="GET" x-data="adminLiveFilter" @change="$el.submit()" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
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
                           placeholder="Cari nama warga, masked NIK, email, no WhatsApp..." 
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

                <button type="submit" class="h-10 px-5 bg-black hover:bg-gold-500 hover:text-black text-white text-xs font-bold rounded-xl shadow-sm transition-all inline-flex items-center gap-1.5 shrink-0">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan Filter
                </button>

                @if(request()->hasAny(['q', 'status', 'district']))
                    <a href="{{ route('admin.verifications.index') }}" class="h-10 px-3.5 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors inline-flex items-center gap-1">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Verifications Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 border-b border-gray-100 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Pemohon & Kontak</th>
                        <th class="px-6 py-4">Masked NIK</th>
                        <th class="px-6 py-4">Kecamatan Domisili</th>
                        <th class="px-6 py-4">Status Pengajuan</th>
                        <th class="px-6 py-4">Waktu Pengajuan</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($verifications as $v)
                        <tr class="hover:bg-amber-50/20 transition-colors group">
                            <!-- Pemohon & Kontak -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-900 border border-amber-300/60 flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                                        {{ strtoupper(substr($v->user->name ?? 'W', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block text-sm group-hover:text-gold-600 transition-colors">
                                            {{ $v->user->name ?? 'User #' . $v->user_id }}
                                        </span>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-0.5">
                                            <span>{{ $v->user->email ?? '-' }}</span>
                                            @if($v->user && $v->user->phone)
                                                <span>&bull;</span>
                                                <span class="font-mono text-gray-400">{{ $v->user->phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Masked NIK -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-200/70 font-mono font-bold text-gray-900 text-xs shadow-2xs">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5 text-gold-600"></i>
                                    <span>{{ $v->masked_nik }}</span>
                                </div>
                            </td>

                            <!-- Domisili -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-gold-600"></i>
                                    Kec. {{ $v->district ?? ($v->user->profile->district ?? 'Belum Diisi') }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($v->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        Disetujui
                                    </span>
                                @elseif($v->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                        <i data-lucide="clock-4" class="w-3.5 h-3.5 text-amber-600"></i>
                                        Butuh Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal Diajukan -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono text-[11px]">
                                <div>{{ $v->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400">{{ $v->created_at->diffForHumans() }}</div>
                            </td>

                            <!-- Tindakan -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.verifications.show', $v->id) }}" 
                                   class="h-9 px-4 rounded-xl text-xs font-bold {{ $v->status === 'pending' ? 'bg-black hover:bg-gold-500 hover:text-black text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }} transition-all duration-150 inline-flex items-center gap-1.5 shadow-2xs group/btn">
                                    <span>{{ $v->status === 'pending' ? 'Periksa Berkas' : 'Detail Review' }}</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-gold-400 group-hover/btn:text-black group-hover/btn:translate-x-0.5 transition-all"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 mb-4 shadow-inner">
                                        <i data-lucide="shield-check" class="w-8 h-8 text-gray-300"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">Tidak Ada Antrean Verifikasi</h3>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        Tidak ada berkas verifikasi NIK warga yang cocok dengan parameter filter saat ini.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.verifications.index') }}" class="px-4 py-2 bg-gray-900 text-white hover:bg-gold-500 hover:text-black text-xs font-bold rounded-xl transition-all inline-flex items-center gap-1.5">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $verifications->links() }}
    </div>
</div>
@endsection
