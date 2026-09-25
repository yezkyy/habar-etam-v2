@extends('layouts.admin')

@section('title', 'Admin Dashboard - Habar Etam')
@section('page_title', 'Ringkasan & Analitik Redaksi')

@section('content')
<div class="space-y-8">
    <!-- Top Welcome & Live Metric Highlights -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0c1017] via-[#141a24] to-[#0c1017] border border-gold-500/30 text-white p-6 sm:p-8 shadow-2xl">
        <!-- Background Ambient Glow Effects -->
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-gold-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -top-12 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-gold-500/20 text-gold-400 border border-gold-500/30 shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-gold-400 animate-ping"></span>
                    <span class="tracking-wide">KUKAR SMART REGION 24/7 &bull; REDAKSI TERPADU</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-sm">
                    Pusat Kendali Redaksi Habar Etam
                </h2>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    Monitoring real-time aktivitas warga, kurasi pengaduan publik terverifikasi, dan moderasi konten se-Kutai Kartanegara.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('admin.studio.feed') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-400 to-gold-500 hover:from-gold-300 hover:to-gold-400 text-black text-xs sm:text-sm font-black shadow-lg hover:shadow-gold-500/25 transition-all inline-flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i data-lucide="radio" class="w-4 h-4 text-black"></i> Studio Live On-Air
                </a>
                <a href="{{ route('admin.reports.map') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-bold border border-white/20 hover:border-white/30 transition-all inline-flex items-center gap-2 backdrop-blur-md">
                    <i data-lucide="map" class="w-4 h-4 text-gold-400"></i> Peta Titik Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Counter Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Pending Verifications -->
        <a href="{{ route('admin.verifications.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gold-300 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Verifikasi NIK</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-100 transition-all">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ $pendingVerificationsCount }}</span>
                    <span class="text-xs font-medium text-amber-600 block mt-0.5">Menunggu Tindakan</span>
                </div>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200">
                    Prioritas
                </span>
            </div>
        </a>

        <!-- 2. Pending UGC Moderation -->
        <a href="{{ route('admin.moderation.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gold-300 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Moderasi Konten</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-100 transition-all">
                    <i data-lucide="file-check" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ $pendingUgcCount }}</span>
                    <span class="text-xs font-medium text-blue-600 block mt-0.5">Submission UGC</span>
                </div>
                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded-lg border border-blue-200">
                    Kurasi
                </span>
            </div>
        </a>

        <!-- 3. Active Reports -->
        <a href="{{ route('admin.reports.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gold-300 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Laporan Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-rose-100 transition-all">
                    <i data-lucide="megaphone" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ $activeReportsCount }}</span>
                    <span class="text-xs font-medium text-rose-600 block mt-0.5">Pengaduan Publik</span>
                </div>
                <span class="text-[11px] font-bold text-rose-700 bg-rose-50 px-2 py-1 rounded-lg border border-rose-200">
                    Lapor Etam
                </span>
            </div>
        </a>

        <!-- 4. Registered Members -->
        <a href="{{ route('admin.members.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gold-300 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Total Warga</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-100 transition-all">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ $totalMembersCount }}</span>
                    <span class="text-xs font-medium text-emerald-600 block mt-0.5">Akun Terdaftar</span>
                </div>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-200">
                    Kukar
                </span>
            </div>
        </a>
    </div>

    <!-- ========================================================================= -->
    <!-- CHART SECTION 1: Main Multi-Line Activity Trend Chart (7 Days) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-gray-100 gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-gold-500 animate-ping"></div>
                    <h3 class="text-base font-bold text-gray-900">Tren Aktivitas Redaksi & Warga (7 Hari Terakhir)</h3>
                </div>
                <p class="text-xs text-gray-400 mt-1">Perbandingan volume Laporan Warga, Submission Konten UGC, dan Pendaftaran Akun Baru</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="inline-flex items-center gap-1.5 text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span> Lapor Etam
                </span>
                <span class="inline-flex items-center gap-1.5 text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span> Konten Warga
                </span>
                <span class="inline-flex items-center gap-1.5 text-gray-600">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Warga Baru
                </span>
            </div>
        </div>

        <div class="pt-5 relative h-72 sm:h-80 w-full">
            <canvas id="mainTrendChart"></canvas>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- CHART SECTION 2: Doughnut UGC & Status Breakdown Grid -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart 2: UGC Distribution (Doughnut) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="pie-chart" class="w-4 h-4 text-gold-600"></i>
                            Distribusi Kategori Konten Warga (UGC)
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Komposisi kanal bursa kerja, UMKM, kuliner, event & jual cepat</p>
                    </div>
                    <a href="{{ route('admin.moderation.index') }}" class="text-xs font-semibold text-gold-600 hover:text-gold-700">Moderasi &rarr;</a>
                </div>

                <div class="relative h-64 sm:h-72 w-full pt-4">
                    <canvas id="ugcDistributionChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 pt-4 border-t border-gray-100 text-center text-xs">
                <div class="p-2 rounded-xl bg-gray-50">
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Jual Cepat</span>
                    <span class="font-black text-gray-900 text-sm">{{ $ugcDistribution['quick_sale'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50">
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Bursa Kerja</span>
                    <span class="font-black text-gray-900 text-sm">{{ $ugcDistribution['job'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50">
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">UMKM</span>
                    <span class="font-black text-gray-900 text-sm">{{ $ugcDistribution['business'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50">
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Kuliner</span>
                    <span class="font-black text-gray-900 text-sm">{{ $ugcDistribution['culinary'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50">
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Event</span>
                    <span class="font-black text-gray-900 text-sm">{{ $ugcDistribution['event'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50">
                    <span class="text-[10px] text-gray-400 block uppercase font-bold">Komunitas</span>
                    <span class="font-black text-gray-900 text-sm">{{ $ugcDistribution['community'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Chart 3: Report Status Breakdown (Bar / Polar) -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="bar-chart-2" class="w-4 h-4 text-rose-600"></i>
                            Status Alur Pengaduan Lapor Etam
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Alur tahapan dari verifikasi hingga penyelesaian</p>
                    </div>
                    <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-gold-600 hover:text-gold-700">Semua Laporan &rarr;</a>
                </div>

                <div class="relative h-64 sm:h-72 w-full pt-4">
                    <canvas id="reportStatusChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 pt-4 border-t border-gray-100 text-center text-xs">
                <div class="p-2 rounded-xl bg-amber-50 text-amber-900 border border-amber-100">
                    <span class="text-[10px] font-bold block uppercase">Pending NIK</span>
                    <span class="font-black text-sm">{{ $reportStatusBreakdown['pending'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-blue-50 text-blue-900 border border-blue-100">
                    <span class="text-[10px] font-bold block uppercase">Editorial</span>
                    <span class="font-black text-sm">{{ $reportStatusBreakdown['processing'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-rose-50 text-rose-900 border border-rose-100">
                    <span class="text-[10px] font-bold block uppercase">Live On-Air</span>
                    <span class="font-black text-sm">{{ $reportStatusBreakdown['live'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-900 border border-emerald-100">
                    <span class="text-[10px] font-bold block uppercase">Selesai</span>
                    <span class="font-black text-sm">{{ $reportStatusBreakdown['resolved'] ?? 0 }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-100 text-gray-700 border border-gray-200">
                    <span class="text-[10px] font-bold block uppercase">Ditolak</span>
                    <span class="font-black text-sm">{{ $reportStatusBreakdown['rejected'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- CHART SECTION 3: District Demographics (Horizontal Bar) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-emerald-600"></i>
                    Sebaran Warga Terdaftar Berdasarkan Kecamatan Kukar
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Top kecamatan dengan partisipasi akun terverifikasi tertinggi</p>
            </div>
            <a href="{{ route('admin.members.index') }}" class="text-xs font-semibold text-gold-600 hover:text-gold-700">Data Warga &rarr;</a>
        </div>

        <div class="relative h-64 sm:h-72 w-full pt-4">
            <canvas id="districtChart"></canvas>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- Priority Tables Grid -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- 1. Pending Verifications -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Antrean Verifikasi NIK</h3>
                            <p class="text-[11px] text-gray-400">Wajib diperiksa sebelum laporan warga disiarkan</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.verifications.index') }}" class="text-xs font-semibold text-gold-600 hover:text-gold-700">Lihat Semua &rarr;</a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($pendingVerifications as $verif)
                        <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-gray-50/80 transition-colors">
                            <div class="space-y-1">
                                <span class="font-bold text-sm text-gray-900 block">{{ $verif->user->name ?? 'Warga' }}</span>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span class="inline-flex items-center gap-1 font-medium text-gray-700">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-gold-500"></i>
                                        Kec. {{ $verif->district ?? ($verif->user->profile->district ?? '-') }}
                                    </span>
                                    <span>&bull;</span>
                                    <span class="text-gray-400">{{ $verif->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.verifications.show', $verif->id) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-amber-50 hover:bg-gold-500 hover:text-black text-amber-900 transition-all border border-amber-200 hover:border-gold-500">
                                Review NIK
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-400">
                            <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500 mx-auto mb-2 opacity-80"></i>
                            Tidak ada antrean verifikasi NIK saat ini. Semua data mutakhir.
                        </div>
                    @endforelse
                </div>
            </div>
            @if($pendingVerifications->isNotEmpty())
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ route('admin.verifications.index') }}" class="text-xs font-semibold text-gold-600 hover:underline">Kelola antrean verifikasi warga &rarr;</a>
            </div>
            @endif
        </div>

        <!-- 2. Recent Urgent Reports -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Laporan Warga Perlu Tindakan</h3>
                            <p class="text-[11px] text-gray-400">Laporan Lapor Etam terbaru yang membutuhkan kurasi</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-gold-600 hover:text-gold-700">Lihat Semua &rarr;</a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($recentReports as $rep)
                        <div class="p-4 sm:p-5 flex items-center justify-between hover:bg-gray-50/80 transition-colors">
                            <div class="space-y-1 max-w-[70%]">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-bold text-gold-600">{{ $rep->tracking_code }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ $rep->status == 'pending_verification' ? 'bg-amber-100 text-amber-800' : ($rep->status == 'processing_editorial' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $rep->status_label }}
                                    </span>
                                </div>
                                <span class="font-bold text-xs text-gray-900 block truncate">{{ $rep->title }}</span>
                                <span class="text-[11px] text-gray-400 block">{{ $rep->district }} &bull; {{ $rep->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('admin.reports.show', $rep->id) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-rose-50 hover:bg-gold-500 hover:text-black text-rose-900 transition-all border border-rose-200 hover:border-gold-500">
                                Proses
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-gray-400">
                            <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500 mx-auto mb-2 opacity-80"></i>
                            Tidak ada pengaduan aktif yang membutuhkan tindakan.
                        </div>
                    @endforelse
                </div>
            </div>
            @if($recentReports->isNotEmpty())
            <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-gold-600 hover:underline">Buka seluruh daftar laporan publik &rarr;</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Audit Stream -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center font-bold">
                    <i data-lucide="history" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Aktivitas Sistem & Log Audit</h3>
                    <p class="text-xs text-gray-400">Jejak rekaman tindakan pengurus redaksi dan sistem</p>
                </div>
            </div>
            <a href="{{ route('admin.activity') }}" class="text-xs font-semibold text-gold-600 hover:text-gold-700">Semua Aktivitas &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Waktu</th>
                        <th class="px-5 py-3 font-semibold">User</th>
                        <th class="px-5 py-3 font-semibold">Aksi</th>
                        <th class="px-5 py-3 font-semibold">Modul</th>
                        <th class="px-5 py-3 font-semibold">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentActivities as $act)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">{{ $act->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-3.5 font-bold text-gray-900 whitespace-nowrap">{{ $act->user->name ?? 'System' }}</td>
                            <td class="px-5 py-3.5 text-gray-700 font-mono">
                                <span class="px-2 py-0.5 rounded bg-gray-100 font-medium">{{ $act->action }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">{{ $act->model_type ? class_basename($act->model_type) : '-' }}</td>
                            <td class="px-5 py-3.5 text-gray-400 font-mono">{{ $act->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada riwayat aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js is not loaded yet');
        return;
    }

    // Common Chart defaults
    Chart.defaults.font.family = 'Plus Jakarta Sans, sans-serif';
    Chart.defaults.color = '#64748b';

    // 1. Multi-Line Main Trend Chart
    const trendCtx = document.getElementById('mainTrendChart');
    if (trendCtx) {
        const labels = @json($trendLabels ?? []);
        const reportsData = @json($trendReports ?? []);
        const ugcData = @json($trendUgc ?? []);
        const usersData = @json($trendUsers ?? []);

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Lapor Etam',
                        data: reportsData,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f43f5e',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Konten UGC',
                        data: ugcData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f59e0b',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Warga Baru',
                        data: usersData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#d4af37',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10,
                        boxPadding: 4,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(226, 232, 240, 0.6)',
                        },
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // 2. UGC Distribution Doughnut Chart
    const ugcCtx = document.getElementById('ugcDistributionChart');
    if (ugcCtx) {
        const ugcDist = @json($ugcDistribution ?? []);
        const ugcLabels = ['Jual Cepat', 'Bursa Kerja', 'UMKM', 'Kuliner', 'Event', 'Komunitas'];
        const ugcValues = [
            ugcDist.quick_sale || 0,
            ugcDist.job || 0,
            ugcDist.business || 0,
            ugcDist.culinary || 0,
            ugcDist.event || 0,
            ugcDist.community || 0
        ];

        new Chart(ugcCtx, {
            type: 'doughnut',
            data: {
                labels: ugcLabels,
                datasets: [{
                    data: ugcValues,
                    backgroundColor: [
                        '#d4af37', // Gold (Jual Cepat)
                        '#3b82f6', // Blue (Kerja)
                        '#10b981', // Green (UMKM)
                        '#f97316', // Orange (Kuliner)
                        '#8b5cf6', // Purple (Event)
                        '#06b6d4'  // Cyan (Komunitas)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            font: { size: 11, weight: '500' },
                            padding: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#d4af37',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                cutout: '65%'
            }
        });
    }

    // 3. Report Status Bar Chart
    const reportCtx = document.getElementById('reportStatusChart');
    if (reportCtx) {
        const repStatus = @json($reportStatusBreakdown ?? []);
        const repLabels = ['Pending NIK', 'Proses Editorial', 'Siaran On-Air', 'Selesai', 'Ditolak'];
        const repValues = [
            repStatus.pending || 0,
            repStatus.processing || 0,
            repStatus.live || 0,
            repStatus.resolved || 0,
            repStatus.rejected || 0
        ];

        new Chart(reportCtx, {
            type: 'bar',
            data: {
                labels: repLabels,
                datasets: [{
                    label: 'Jumlah Pengaduan',
                    data: repValues,
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.85)', // Amber
                        'rgba(59, 130, 246, 0.85)',  // Blue
                        'rgba(244, 63, 94, 0.85)',   // Rose
                        'rgba(16, 185, 129, 0.85)',  // Emerald
                        'rgba(156, 163, 175, 0.85)'  // Gray
                    ],
                    borderRadius: 8,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#d4af37',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { stepSize: 1, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // 4. District Registration Horizontal Bar Chart
    const districtCtx = document.getElementById('districtChart');
    if (districtCtx) {
        const distLabels = @json($districtLabels ?? []);
        const distValues = @json($districtCounts ?? []);

        new Chart(districtCtx, {
            type: 'bar',
            data: {
                labels: distLabels,
                datasets: [{
                    label: 'Warga Terdaftar',
                    data: distValues,
                    backgroundColor: 'rgba(212, 175, 55, 0.85)',
                    hoverBackgroundColor: '#d4af37',
                    borderRadius: 8,
                    borderWidth: 0,
                    barThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#18181b',
                        titleColor: '#d4af37',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { stepSize: 1, font: { size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' } }
                    }
                }
            }
        });
    }
});
</script>
@endsection

