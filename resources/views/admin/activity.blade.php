@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem - Admin Habar Etam')
@section('page_title', 'Jejak Aktivitas & Log Audit')

@section('content')
<div class="space-y-6">
    <!-- Top Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0c1017] via-[#141a24] to-[#0c1017] border border-gold-500/30 text-white p-6 sm:p-7 shadow-2xl">
        <!-- Background Ambient Glow Effects -->
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-gold-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black bg-gold-500/20 text-gold-400 border border-gold-500/30">
                    <span class="w-2 h-2 rounded-full bg-gold-400 animate-ping"></span> FORENSIK DIGITAL & AUDIT TRAIL
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">Rekam Jejak & Mutasi Sistem</h2>
                <p class="text-xs sm:text-sm text-gray-300">Transparansi dan audit log menyeluruh atas aksi verifikasi, moderasi konten, mutasi status pengaduan, dan aktivitas staf redaksi.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('admin.activity') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all inline-flex items-center gap-2 backdrop-blur-md">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-gold-400"></i> Refresh Data
                </a>
                <a href="{{ route('admin.export.index') }}" class="px-4 py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-black text-xs font-bold shadow-md transition-all inline-flex items-center gap-2">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Export Log
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Stat Counter Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Activities -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-gold-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Total Jejak Log</span>
                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="database" class="w-5 h-5 text-gray-800"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ number_format($totalActivitiesCount) }}</span>
                    <span class="text-xs font-medium text-gray-500 block mt-0.5">Semua entri audit</span>
                </div>
                <span class="text-[11px] font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded-lg">
                    All-Time
                </span>
            </div>
        </div>

        <!-- Card 2: Today's Volume -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-gold-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Aktivitas Hari Ini</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-gold-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="clock" class="w-5 h-5 text-gold-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ number_format($todayActivitiesCount) }}</span>
                    <span class="text-xs font-medium text-amber-600 block mt-0.5">Jejak hari ini</span>
                </div>
                <span class="text-[11px] font-bold text-amber-800 bg-amber-50 px-2 py-1 rounded-lg border border-amber-200">
                    Hari Ini
                </span>
            </div>
        </div>

        <!-- Card 3: Active Staff Users -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-gold-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Staf & Admin Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="user-check" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ number_format($uniqueUsersCount) }}</span>
                    <span class="text-xs font-medium text-blue-600 block mt-0.5">Aktor teridentifikasi</span>
                </div>
                <span class="text-[11px] font-bold text-blue-800 bg-blue-50 px-2 py-1 rounded-lg border border-blue-200">
                    Pengguna
                </span>
            </div>
        </div>

        <!-- Card 4: Moderation Actions -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-gold-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold tracking-wider text-gray-500 uppercase">Aksi Moderasi</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="shield-alert" class="w-5 h-5 text-emerald-600"></i>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-3xl font-black text-gray-900">{{ number_format($moderationCount) }}</span>
                    <span class="text-xs font-medium text-emerald-600 block mt-0.5">Approve / Reject / Status</span>
                </div>
                <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-200">
                    Keputusan
                </span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.activity') }}" method="GET" x-data="adminLiveFilter" @change="$el.submit()" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                <!-- Search Keyword -->
                <div class="sm:col-span-2 lg:col-span-4 relative flex items-center">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 pointer-events-none z-10"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari aktor, email, aksi, IP, atau target..." 
                           class="admin-search-input"
                           x-on:input.debounce.450ms="$el.form.submit()">
                </div>

                <!-- Action Filter -->
                <div class="lg:col-span-2">
                    <select name="action" class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs">
                        <option value="">Semua Tipe Aksi</option>
                        @foreach($availableActions as $actName)
                            <option value="{{ $actName }}" {{ request('action') == $actName ? 'selected' : '' }}>{{ $actName }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Target Model Filter -->
                <div class="lg:col-span-2">
                    <select name="target_type" class="w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs">
                        <option value="">Semua Modul</option>
                        @foreach($availableTargets as $targetClass)
                            <option value="{{ $targetClass }}" {{ request('target_type') == $targetClass ? 'selected' : '' }}>
                                {{ class_basename($targetClass) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range From -->
                <div class="lg:col-span-2 relative flex items-center">
                    <input type="text" 
                           name="date_from" 
                           data-datepicker
                           value="{{ request('date_from') }}" 
                           placeholder="Dari Tanggal..." 
                           class="modern-date-input w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs cursor-pointer">
                </div>

                <!-- Date Range To -->
                <div class="lg:col-span-2 relative flex items-center">
                    <input type="text" 
                           name="date_to" 
                           data-datepicker
                           value="{{ request('date_to') }}" 
                           placeholder="Sampai Tanggal..." 
                           class="modern-date-input w-full h-10 px-3.5 rounded-xl border border-gray-200 text-xs font-semibold bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all shadow-2xs cursor-pointer">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs">
                <span class="text-gray-400 font-medium">
                    Menampilkan <strong class="text-gray-800">{{ $activities->total() }}</strong> catatan audit log
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['q', 'action', 'target_type', 'date_from', 'date_to']))
                        <a href="{{ route('admin.activity') }}" class="h-9 px-3 rounded-xl text-xs font-semibold text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors inline-flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="h-9 px-5 bg-black hover:bg-gold-500 hover:text-black text-white text-xs font-bold rounded-xl shadow transition-all inline-flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/80 text-gray-500 border-b border-gray-100 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-4 font-bold">Waktu & Timestamp</th>
                        <th class="px-6 py-4 font-bold">Aktor / Pengguna</th>
                        <th class="px-6 py-4 font-bold">Aksi Sistem</th>
                        <th class="px-6 py-4 font-bold">Entitas Modul</th>
                        <th class="px-6 py-4 font-bold">Metadata / Detail</th>
                        <th class="px-6 py-4 font-bold">IP & Perangkat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $act)
                        @php
                            $actionLower = strtolower($act->action ?? '');
                            $isApprove = str_contains($actionLower, 'approve') || str_contains($actionLower, 'verify') || str_contains($actionLower, 'publish');
                            $isReject = str_contains($actionLower, 'reject') || str_contains($actionLower, 'deny') || str_contains($actionLower, 'suspend');
                            $isUpdate = str_contains($actionLower, 'update') || str_contains($actionLower, 'status') || str_contains($actionLower, 'edit');
                            $isDelete = str_contains($actionLower, 'delete') || str_contains($actionLower, 'destroy');
                            $isCreate = str_contains($actionLower, 'create') || str_contains($actionLower, 'store');
                            
                            $badgeColor = 'bg-gray-100 text-gray-700 border-gray-200';
                            $iconName = 'activity';
                            if ($isApprove) {
                                $badgeColor = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                $iconName = 'check-circle';
                            } elseif ($isReject) {
                                $badgeColor = 'bg-rose-50 text-rose-800 border-rose-200';
                                $iconName = 'x-circle';
                            } elseif ($isUpdate) {
                                $badgeColor = 'bg-blue-50 text-blue-800 border-blue-200';
                                $iconName = 'edit-3';
                            } elseif ($isDelete) {
                                $badgeColor = 'bg-amber-50 text-amber-800 border-amber-200';
                                $iconName = 'trash-2';
                            } elseif ($isCreate) {
                                $badgeColor = 'bg-purple-50 text-purple-800 border-purple-200';
                                $iconName = 'plus-circle';
                            }

                            $metadataArray = is_array($act->metadata) ? $act->metadata : (json_decode($act->metadata, true) ?? []);
                            $metadataJson = json_encode($metadataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <!-- 1. Timestamp -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900 block text-xs">{{ $act->created_at->diffForHumans() }}</span>
                                <span class="font-mono text-[11px] text-gray-400 block mt-0.5" title="{{ $act->created_at->format('Y-m-d H:i:s') }}">
                                    {{ $act->created_at->format('d M Y, H:i:s') }}
                                </span>
                            </td>

                            <!-- 2. Actor / User -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-gold-600 to-gold-400 text-black font-black text-xs flex items-center justify-center shadow-xs shrink-0">
                                        {{ strtoupper(substr($act->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block text-xs">{{ $act->user->name ?? 'Sistem Otomatis' }}</span>
                                        <span class="text-[11px] text-gray-400 block">{{ $act->user->email ?? 'System Engine' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- 3. Action -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $badgeColor }}">
                                    <i data-lucide="{{ $iconName }}" class="w-3.5 h-3.5"></i>
                                    <span>{{ $act->action }}</span>
                                </span>
                            </td>

                            <!-- 4. Target Entity -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($act->target_type)
                                    <span class="font-bold text-gray-800 text-xs block">
                                        {{ class_basename($act->target_type) }}
                                    </span>
                                    <span class="font-mono text-[11px] text-gray-400 block">
                                        ID #{{ $act->target_id ?? '-' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs font-medium">-</span>
                                @endif
                            </td>

                            <!-- 5. Metadata Payload -->
                            <td class="px-6 py-4">
                                @if(!empty($metadataArray))
                                    <button type="button" 
                                            onclick='openPayloadModal(@json($act->action), @json($act->user->name ?? "System"), @json($act->created_at->format("d M Y H:i:s")), @json($metadataJson))'
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gold-500 hover:text-black text-gray-700 text-[11px] font-semibold transition-all border border-gray-200">
                                        <i data-lucide="code" class="w-3.5 h-3.5"></i>
                                        <span>{{ count($metadataArray) }} Atribut Data</span>
                                    </button>
                                @else
                                    <span class="text-gray-400 text-[11px]">Tidak ada payload</span>
                                @endif
                            </td>

                            <!-- 6. IP & Network -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-0.5">
                                    <span class="font-mono text-xs font-semibold text-gray-800 flex items-center gap-1">
                                        <i data-lucide="shield" class="w-3 h-3 text-gold-600"></i>
                                        {{ $act->ip_address ?: '127.0.0.1' }}
                                    </span>
                                    @if($act->user_agent)
                                        <span class="text-[10px] text-gray-400 block truncate max-w-[150px]" title="{{ $act->user_agent }}">
                                            {{ Str::limit($act->user_agent, 24) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="font-bold text-gray-700 text-sm">Tidak Ada Rekaman Aktivitas</p>
                                    <p class="text-xs text-gray-400">Belum ada riwayat aktivitas yang sesuai dengan kriteria filter pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ========================================================================= -->
<!-- Payload Inspector Modal -->
<!-- ========================================================================= -->
<div id="payload-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-xs">
    <div class="bg-[#0c1017] border border-white/10 text-white rounded-3xl max-w-2xl w-full p-6 space-y-4 shadow-2xl relative overflow-hidden">
        <!-- Glow accent -->
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-gold-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center justify-between pb-3 border-b border-white/10 relative z-10">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gold-500/20 text-gold-400 border border-gold-500/30">
                    <i data-lucide="file-json" class="w-3 h-3"></i> JSON INSPECTOR
                </div>
                <h3 class="text-sm font-bold text-white mt-1" id="modal-title">Detail Payload Aksi</h3>
            </div>
            <button type="button" onclick="closePayloadModal()" class="p-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="space-y-2 text-xs relative z-10">
            <div class="flex items-center justify-between text-[11px] text-gray-400">
                <span id="modal-actor-info">Aktor: -</span>
                <span id="modal-time-info">Waktu: -</span>
            </div>
            <div class="relative">
                <pre id="modal-json-content" class="bg-[#141a24] border border-white/10 rounded-2xl p-4 text-xs font-mono text-emerald-400 overflow-x-auto max-h-80 scrollbar-thin select-all leading-relaxed"></pre>
                <button type="button" onclick="copyModalJson()" class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-gold-500 hover:text-black text-gray-300 text-[10px] font-bold transition-all border border-white/10 inline-flex items-center gap-1">
                    <i data-lucide="copy" class="w-3 h-3"></i> <span id="copy-btn-text">Salin JSON</span>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-end pt-2 border-t border-white/10 relative z-10">
            <button type="button" onclick="closePayloadModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-white transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
let currentRawJson = '';

function openPayloadModal(action, actor, time, jsonStr) {
    currentRawJson = jsonStr;
    document.getElementById('modal-title').textContent = 'Detail Payload: ' + action;
    document.getElementById('modal-actor-info').textContent = 'Aktor: ' + actor;
    document.getElementById('modal-time-info').textContent = 'Waktu: ' + time;
    document.getElementById('modal-json-content').textContent = jsonStr;
    document.getElementById('copy-btn-text').textContent = 'Salin JSON';
    document.getElementById('payload-modal').classList.remove('hidden');
    if (window.lucide) {
        window.lucide.createIcons();
    }
}

function closePayloadModal() {
    document.getElementById('payload-modal').classList.add('hidden');
}

function copyModalJson() {
    if (!currentRawJson) return;
    navigator.clipboard.writeText(currentRawJson).then(() => {
        document.getElementById('copy-btn-text').textContent = 'Tersalin!';
        setTimeout(() => {
            document.getElementById('copy-btn-text').textContent = 'Salin JSON';
        }, 2000);
    });
}
</script>
@endsection

