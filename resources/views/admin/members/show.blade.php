@extends('layouts.admin')

@section('title', 'Detail Profil Warga: ' . $member->name . ' - Admin Habar Etam')
@section('page_title', 'Detail Profil & Aktivitas Warga')

@section('content')
<div class="space-y-6 pb-12" x-data="{ activeTab: 'reports' }">

    <!-- Top Breadcrumbs & Back Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-600 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.members.index') }}" class="hover:text-gold-600 transition-colors">Manajemen Warga</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">#ID-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }} ({{ $member->name }})</span>
        </div>

        <div class="flex items-center gap-2">
            @if($member->phone)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $member->phone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" 
                   class="h-9 px-3.5 rounded-xl text-xs font-bold bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-500 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                    <span>WhatsApp</span>
                </a>
            @endif

            <a href="mailto:{{ $member->email }}" 
               class="h-9 px-3.5 rounded-xl text-xs font-bold bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-500"></i>
                <span>Kirim Email</span>
            </a>

            <a href="{{ route('admin.members.index') }}" 
               class="h-9 px-3.5 rounded-xl text-xs font-bold bg-gray-900 hover:bg-gold-500 text-white hover:text-black transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Hero Card: Member Identity & Status Modifier -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Top Cover Banner -->
        <div class="h-32 sm:h-36 bg-gradient-to-r from-gray-950 via-gray-900 to-black relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-gold-500/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute left-1/4 top-0 w-40 h-40 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
        </div>

        <!-- Profile & Action Header Body -->
        <div class="p-6 sm:p-8 bg-white relative">
            <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                
                <!-- Avatar & Identity Details -->
                <div class="flex flex-col sm:flex-row items-start gap-5 min-w-0 flex-1">
                    <!-- Profile Avatar -->
                    <div class="relative shrink-0 -mt-16 sm:-mt-20">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-br from-amber-300 via-gold-400 to-amber-500 text-black border-4 border-white shadow-xl flex items-center justify-center font-black text-3xl sm:text-4xl">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        @if($member->status === 'verified')
                            <span class="absolute bottom-0 right-0 w-7 h-7 bg-emerald-500 text-white rounded-full flex items-center justify-center ring-4 ring-white shadow-md" title="Warga Terverifikasi KTP Kukar">
                                <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                            </span>
                        @endif
                    </div>

                    <!-- Name & Subtext -->
                    <div class="space-y-2.5 min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">{{ $member->name }}</h1>
                            <span class="font-mono text-xs font-bold text-gray-500 px-2.5 py-0.5 rounded-lg bg-gray-100 border border-gray-200">
                                #ID-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            
                            @if($member->status === 'verified')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Warga Terverifikasi
                                </span>
                            @elseif($member->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Pending NIK
                                </span>
                            @elseif($member->status === 'rejected')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                    <i data-lucide="ban" class="w-3.5 h-3.5"></i> {{ ucfirst($member->status) }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                <span class="text-gray-800 font-medium">{{ $member->email }}</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                <span class="text-gray-800 font-mono">{{ $member->phone ?? 'Belum diisi' }}</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gold-600 shrink-0"></i>
                                <span class="text-gray-800 font-medium">
                                    Kec. {{ $member->profile->district ?? ($member->verification->district ?? 'Kutai Kartanegara') }}
                                    @if($member->profile && $member->profile->village)
                                        , Kel. {{ $member->profile->village }}
                                    @endif
                                </span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                                <span>Bergabung: {{ $member->created_at->translatedFormat('d F Y') }} ({{ $member->created_at->diffForHumans() }})</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Status Modifier Form Box -->
                <div class="bg-gray-50/90 p-4 sm:p-5 rounded-2xl border border-gray-200/80 shadow-2xs w-full lg:w-auto lg:min-w-[340px] shrink-0">
                    <form action="{{ route('admin.members.status', $member->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-black uppercase tracking-wider text-gray-700 flex items-center gap-1.5">
                                <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-gold-600"></i> Ubah Status Akun
                            </label>
                            <span class="text-[10px] text-gray-400 font-medium">Audit tercatat</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <select name="status" class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs font-bold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs">
                                <option value="verified" {{ $member->status === 'verified' ? 'selected' : '' }}>Verified (Warga Sah)</option>
                                <option value="pending" {{ $member->status === 'pending' ? 'selected' : '' }}>Pending (Review NIK)</option>
                                <option value="rejected" {{ $member->status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                                <option value="suspended" {{ $member->status === 'suspended' ? 'selected' : '' }}>Suspended (Dibekukan)</option>
                            </select>

                            <button type="submit" class="h-10 px-4 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-xl shadow transition-all inline-flex items-center justify-center gap-1.5 cursor-pointer">
                                <i data-lucide="check" class="w-3.5 h-3.5 stroke-[2.5]"></i> Update Status
                            </button>
                        </div>

                        <input type="text" name="reason" placeholder="Alasan perubahan status (opsional)..." class="w-full h-9 px-3 rounded-xl border border-gray-200 text-xs bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all placeholder-gray-400">
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- NIK & Identity Verification Trust Box -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-7">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4 text-gold-500"></i> Informasi Kependudukan & Validasi NIK Kukar
            </h2>
            @if($member->verification)
                <a href="{{ route('admin.verifications.show', $member->verification->id) }}" class="text-xs font-bold text-gold-600 hover:text-gold-700 inline-flex items-center gap-1 transition-colors">
                    <span>Lihat Dokumen Verifikasi</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            @endif
        </div>

        @if($member->verification)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Masked NIK</span>
                    <span class="text-base font-bold font-mono text-gray-900 mt-1 block flex items-center gap-1.5">
                        <i data-lucide="credit-card" class="w-4 h-4 text-gold-600"></i>
                        {{ $member->verification->masked_nik }}
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Kecamatan Domisili</span>
                    <span class="text-base font-bold text-gray-900 mt-1 block flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-gold-600"></i>
                        Kec. {{ $member->verification->district ?? ($member->profile->district ?? 'Kutai Kartanegara') }}
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Status Validasi</span>
                    <span class="text-base font-bold uppercase mt-1 block {{ $member->verification->status === 'approved' ? 'text-emerald-600' : ($member->verification->status === 'pending' ? 'text-amber-600' : 'text-rose-600') }}">
                        {{ $member->verification->status }}
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Waktu Verifikasi</span>
                    <span class="text-sm font-bold text-gray-900 mt-1 block font-mono">
                        {{ $member->verification->verified_at ? $member->verification->verified_at->translatedFormat('d M Y, H:i') : 'Menunggu Review' }}
                    </span>
                    @if($member->verification->verifier)
                        <span class="text-[10px] text-gray-400 block mt-0.5">Oleh: {{ $member->verification->verifier->name }}</span>
                    @endif
                </div>
            </div>

            @if($member->verification->rejection_reason)
                <div class="mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-2xl flex items-start gap-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="font-bold">Alasan Penolakan Berkas:</strong>
                        <p class="mt-0.5 leading-relaxed">{{ $member->verification->rejection_reason }}</p>
                    </div>
                </div>
            @endif
        @else
            <div class="p-5 bg-amber-50/70 border border-amber-200 text-amber-900 text-xs rounded-2xl flex items-center gap-3">
                <i data-lucide="info" class="w-5 h-5 text-amber-600 shrink-0"></i>
                <div>
                    <strong class="font-bold">Belum Ada Pengajuan Verifikasi NIK</strong>
                    <p class="text-[11px] text-amber-700 mt-0.5">Warga ini belum mengunggah dokumen KTP untuk verifikasi identitas kependudukan Kutai Kartanegara.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Member Activity & Contributions Explorer -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <!-- Navigation Tabs -->
        <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between gap-4 flex-wrap bg-gray-50/50">
            <div>
                <h3 class="text-base font-black text-gray-900">Riwayat & Kontribusi Warga</h3>
                <p class="text-xs text-gray-500 mt-0.5">Seluruh jejak aktivitas pengiriman laporan, konten bisnis, dan submission warga di ekosistem Habar Etam.</p>
            </div>

            <!-- Tab Pills -->
            <div class="flex flex-wrap items-center gap-1.5 bg-white p-1.5 rounded-2xl border border-gray-200/70 shadow-2xs">
                <button type="button" 
                        @click="activeTab = 'reports'" 
                        :class="activeTab === 'reports' ? 'bg-black text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="megaphone" class="w-3.5 h-3.5"></i>
                    <span>Lapor Etam</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'reports' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">{{ $member->reports->count() }}</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'quick_sales'" 
                        :class="activeTab === 'quick_sales' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    <span>Jual Cepat</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'quick_sales' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">{{ $member->quickSales->count() }}</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'jobs'" 
                        :class="activeTab === 'jobs' ? 'bg-purple-600 text-white shadow-sm' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i>
                    <span>Loker</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'jobs' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">{{ $member->jobVacancies->count() }}</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'businesses'" 
                        :class="activeTab === 'businesses' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="store" class="w-3.5 h-3.5"></i>
                    <span>UMKM & Kuliner</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'businesses' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">{{ $member->businesses->count() + $member->culinaryPlaces->count() }}</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'events_community'" 
                        :class="activeTab === 'events_community' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 hover:bg-rose-50 hover:text-rose-700'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>Event & Komunitas</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="activeTab === 'events_community' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'">{{ $member->events->count() + $member->communities->count() }}</span>
                </button>

                <button type="button" 
                        @click="activeTab = 'audit'" 
                        :class="activeTab === 'audit' ? 'bg-gray-900 text-gold-400 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    <span>Log Audit</span>
                </button>
            </div>
        </div>

        <!-- Tab Content Panes -->
        <div class="p-6 sm:p-7">
            
            <!-- 1. Tab Reports -->
            <div x-show="activeTab === 'reports'" x-cloak class="space-y-3">
                @forelse($member->reports as $rep)
                    <div class="p-4 rounded-2xl bg-gray-50/70 hover:bg-amber-50/20 border border-gray-200/70 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-gold-600 bg-gold-50 px-2 py-0.5 rounded-md border border-gold-200">
                                    {{ $rep->tracking_code }}
                                </span>
                                <span class="text-xs text-gray-400">&bull;</span>
                                <span class="text-xs text-gray-500 font-medium">Kategori: {{ ucfirst($rep->category) }}</span>
                                <span class="text-xs text-gray-400">&bull;</span>
                                <span class="text-[11px] text-gray-400">{{ $rep->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $rep->title }}</h4>
                            <p class="text-xs text-gray-500 line-clamp-1">{{ $rep->description }}</p>
                        </div>

                        <div class="flex items-center gap-3 shrink-0 self-start sm:self-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $rep->status === 'resolved' || $rep->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($rep->status === 'pending_verification' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $rep->status_label ?? ucfirst(str_replace('_', ' ', $rep->status)) }}
                            </span>
                            <a href="{{ route('admin.reports.show', $rep->id) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-black hover:bg-gold-500 hover:text-black text-white transition-all inline-flex items-center gap-1">
                                <span>Detail Laporan</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 text-center text-gray-400 text-xs flex flex-col items-center justify-center gap-2.5">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 flex items-center justify-center text-gray-400 shadow-2xs">
                            <i data-lucide="megaphone-off" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <span class="font-medium text-gray-500">Warga ini belum pernah mengirimkan laporan pengaduan (Lapor Etam).</span>
                    </div>
                @endforelse
            </div>

            <!-- 2. Tab Quick Sales -->
            <div x-show="activeTab === 'quick_sales'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($member->quickSales as $qs)
                    <div class="p-4 rounded-2xl bg-gray-50 hover:bg-white border border-gray-200/80 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-200 text-gray-700 uppercase">
                                    {{ $qs->category ?? 'Jual Cepat' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $qs->status === 'approved' || $qs->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($qs->status === 'sold' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($qs->status) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $qs->title }}</h4>
                            <span class="text-base font-black text-gold-600 font-mono mt-1 block">
                                Rp {{ number_format($qs->price, 0, ',', '.') }}
                            </span>
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $qs->description }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
                            <span>{{ $qs->created_at->translatedFormat('d M Y') }}</span>
                            <span class="font-medium text-gray-700">Kec. {{ $qs->district ?? '-' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 text-center text-gray-400 text-xs flex flex-col items-center justify-center gap-2.5">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 flex items-center justify-center text-gray-400 shadow-2xs">
                            <i data-lucide="shopping-bag" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <span class="font-medium text-gray-500">Belum ada listing produk jual cepat yang dibuat oleh warga ini.</span>
                    </div>
                @endforelse
            </div>

            <!-- 3. Tab Job Vacancies -->
            <div x-show="activeTab === 'jobs'" x-cloak class="space-y-3">
                @forelse($member->jobVacancies as $job)
                    <div class="p-4 rounded-2xl bg-gray-50 hover:bg-white border border-gray-200/80 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200">
                                    {{ $job->employment_type ?? 'Full Time' }}
                                </span>
                                <span class="text-xs text-gray-400">&bull;</span>
                                <span class="text-xs text-gray-600 font-semibold">{{ $job->company ?? ($job->company_name ?? '-') }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 mt-1">{{ $job->title }}</h4>
                            <p class="text-xs text-gray-500 font-mono mt-0.5">Gaji: {{ $job->salary_range ?? 'Kompetitif' }} &bull; Lokasi: {{ $job->location }}</p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $job->status === 'approved' || $job->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($job->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                @empty
                    <div class="py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 text-center text-gray-400 text-xs flex flex-col items-center justify-center gap-2.5">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 flex items-center justify-center text-gray-400 shadow-2xs">
                            <i data-lucide="briefcase" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <span class="font-medium text-gray-500">Warga ini belum memposting lowongan kerja (Bursa Kerja).</span>
                    </div>
                @endforelse
            </div>

            <!-- 4. Tab Businesses & Culinary -->
            <div x-show="activeTab === 'businesses'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($member->businesses as $biz)
                    <div class="p-4 rounded-2xl bg-gray-50 hover:bg-white border border-gray-200/80 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 uppercase">
                                UMKM & Toko
                            </span>
                            <span class="text-[11px] font-bold text-gray-400">{{ $biz->category }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">{{ $biz->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $biz->address }}</p>
                        @if($biz->phone_whatsapp ?? $biz->phone)
                            <div class="mt-3 text-xs text-gray-600 font-mono flex items-center gap-1">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i> {{ $biz->phone_whatsapp ?? $biz->phone }}
                            </div>
                        @endif
                    </div>
                @empty
                    @if($member->culinaryPlaces->isEmpty())
                        <div class="col-span-full py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 text-center text-gray-400 text-xs flex flex-col items-center justify-center gap-2.5">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 flex items-center justify-center text-gray-400 shadow-2xs">
                                <i data-lucide="store" class="w-6 h-6 text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-500">Belum ada listing UMKM atau Tempat Kuliner dari warga ini.</span>
                        </div>
                    @endif
                @endforelse

                @foreach($member->culinaryPlaces as $cul)
                    <div class="p-4 rounded-2xl bg-gray-50 hover:bg-white border border-gray-200/80 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-50 text-orange-700 uppercase">
                                Kuliner Kukar
                            </span>
                            <span class="text-[11px] font-bold text-gray-400">{{ $cul->culinary_type ?? ($cul->category ?? 'Kuliner') }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">{{ $cul->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $cul->address }}</p>
                    </div>
                @endforeach
            </div>

            <!-- 5. Tab Events & Communities -->
            <div x-show="activeTab === 'events_community'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($member->events as $ev)
                    <div class="p-4 rounded-2xl bg-gray-50 hover:bg-white border border-gray-200/80 transition-all">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 uppercase inline-block mb-2">
                            Event / Agenda
                        </span>
                        <h4 class="text-sm font-bold text-gray-900">{{ $ev->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1 font-mono">Tanggal: {{ $ev->start_date ? $ev->start_date->translatedFormat('d M Y') : '-' }} &bull; {{ $ev->location_name ?? ($ev->location ?? '-') }}</p>
                    </div>
                @empty
                    @if($member->communities->isEmpty())
                        <div class="col-span-full py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 text-center text-gray-400 text-xs flex flex-col items-center justify-center gap-2.5">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 flex items-center justify-center text-gray-400 shadow-2xs">
                                <i data-lucide="calendar" class="w-6 h-6 text-gray-400"></i>
                            </div>
                            <span class="font-medium text-gray-500">Belum ada submission Event atau Komunitas warga.</span>
                        </div>
                    @endif
                @endforelse

                @foreach($member->communities as $com)
                    <div class="p-4 rounded-2xl bg-gray-50 hover:bg-white border border-gray-200/80 transition-all">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-cyan-50 text-cyan-700 uppercase inline-block mb-2">
                            Komunitas
                        </span>
                        <h4 class="text-sm font-bold text-gray-900">{{ $com->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $com->description }}</p>
                    </div>
                @endforeach
            </div>

            <!-- 6. Tab Audit Logs -->
            <div x-show="activeTab === 'audit'" x-cloak class="space-y-3">
                @forelse($auditLogs as $log)
                    @php
                        $meta = $log->metadata ?? ($log->details ?? []);
                    @endphp
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200/70 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 uppercase font-mono text-[11px] px-2 py-0.5 rounded bg-gray-200 text-gray-800">
                                    {{ $log->action }}
                                </span>
                                <span class="text-gray-400 font-mono">{{ $log->created_at->translatedFormat('d M Y, H:i:s') }}</span>
                            </div>
                            @if(is_array($meta) && count($meta) > 0)
                                <p class="text-gray-600 font-mono text-[11px]">
                                    @if(isset($meta['from']) && isset($meta['to']))
                                        Perubahan Status: <span class="font-bold">{{ strtoupper($meta['from']) }}</span> → <span class="font-bold text-gold-600">{{ strtoupper($meta['to']) }}</span>
                                    @endif
                                    @if(isset($meta['reason']))
                                        &bull; Alasan: "{{ $meta['reason'] }}"
                                    @endif
                                </p>
                            @endif
                        </div>

                        <span class="text-[11px] text-gray-400 font-mono self-start sm:self-center">
                            IP: {{ $log->ip_address ?? '127.0.0.1' }}
                        </span>
                    </div>
                @empty
                    <div class="py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200 text-center text-gray-400 text-xs flex flex-col items-center justify-center gap-2.5">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200/80 flex items-center justify-center text-gray-400 shadow-2xs">
                            <i data-lucide="file-text" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <span class="font-medium text-gray-500">Belum ada catatan log aktivitas untuk akun warga ini.</span>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>
@endsection
