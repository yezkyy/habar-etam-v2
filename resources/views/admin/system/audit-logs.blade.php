@extends('layouts.admin')

@section('title', 'Log Audit Keamanan & Forensik - Admin Habar Etam')
@section('page_title', 'Audit Log Keamanan & Jejak Forensik Sistem')

@section('content')
<div class="space-y-8" x-data="auditLogsDashboard()">

    <!-- 1. Hero Header & Forensic Stats Overview -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 p-6 sm:p-8 text-white border border-gray-800 shadow-xl">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-gold/20 text-brand-gold border border-brand-gold/30">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                        Rekam Jejak Forensik Real-Time
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-emerald-300 border border-white/15">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-400"></i>
                        Immutability & Integrity Secured
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Audit Log Aktivitas & Jejak Forensik
                </h2>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    Seluruh rekam jejak intervensi administratif, moderasi pengaduan warga, konfigurasi sistem, dan ekspor dataset tercatat secara deterministik untuk akuntabilitas operasional.
                </p>
            </div>

            <!-- Stats Highlight Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Total Rekaman</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ number_format($metrics['total_logs'], 0, ',', '.') }}</span>
                    <span class="text-[10px] text-brand-gold font-bold">Log Terindeks</span>
                </div>
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Hari Ini</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ number_format($metrics['today_logs'], 0, ',', '.') }}</span>
                    <span class="text-[10px] text-emerald-400 font-bold">Aktivitas Baru</span>
                </div>
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Operator Aktif</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $metrics['unique_operators'] }}</span>
                    <span class="text-[10px] text-blue-400 font-medium">Akun Administrator</span>
                </div>
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3.5 border border-white/10 text-center">
                    <span class="block text-[11px] font-medium text-gray-400">Alamat IP</span>
                    <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $metrics['unique_ips'] }}</span>
                    <span class="text-[10px] text-purple-400 font-medium">Unique Host</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Filter & Multi-Criteria Search Toolbar -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-5 sm:p-6 space-y-4">
        <form action="{{ route('admin.audit.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                <!-- Search Input with bulletproof flex addon container and clear button -->
                <div class="flex items-center flex-1 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                    <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ $searchQuery }}" 
                           placeholder="Cari nama operator, email, tipe aksi, entitas, atau alamat IP..." 
                           class="w-full bg-transparent py-2.5 pr-2 pl-1 text-xs font-medium text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                    @if($searchQuery)
                        <a href="{{ route('admin.audit.index') }}" class="pr-3.5 pl-1 flex items-center text-gray-400 hover:text-gray-600 transition-colors" title="Hapus filter pencarian">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

                <!-- Action Filter Dropdown -->
                <div class="w-full lg:w-48 shrink-0">
                    <select name="action" class="w-full text-xs font-semibold text-gray-900 rounded-2xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                        <option value="all">Semua Tipe Aksi</option>
                        @foreach($availableActions as $act)
                            <option value="{{ $act }}" {{ $currentAction === $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Target Entity Filter Dropdown -->
                <div class="w-full lg:w-44 shrink-0">
                    <select name="target_type" class="w-full text-xs font-semibold text-gray-900 rounded-2xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                        <option value="all">Semua Entitas</option>
                        @foreach($availableTargets as $t)
                            <option value="{{ $t }}" {{ $currentTarget === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range Preset -->
                <div class="w-full lg:w-44 shrink-0">
                    <select name="date_preset" class="w-full text-xs font-semibold text-gray-900 rounded-2xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold">
                        <option value="all" {{ $datePreset === 'all' ? 'selected' : '' }}>Sepanjang Waktu</option>
                        <option value="today" {{ $datePreset === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="yesterday" {{ $datePreset === 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                        <option value="last_7" {{ $datePreset === 'last_7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="this_month" {{ $datePreset === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>

                <!-- Buttons: Submit & Reset -->
                <div class="flex items-center gap-2 shrink-0">
                    <button type="submit" class="px-5 py-2.5 rounded-2xl bg-gray-950 hover:bg-brand-gold hover:text-brand-black text-white text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer shadow-xs active:scale-95">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Terapkan</span>
                    </button>

                    @if($searchQuery || $currentAction !== 'all' || $currentTarget !== 'all' || $datePreset !== 'all')
                        <a href="{{ route('admin.audit.index') }}" class="px-3.5 py-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition-all inline-flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.export.download', 'audit_logs') }}" class="px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 border border-emerald-200 text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span>Ekspor CSV</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- 3. Audit Logs Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3.5 px-4">Waktu (WITA)</th>
                        <th class="py-3.5 px-4">Operator / Aktor</th>
                        <th class="py-3.5 px-4">Tindakan Sistem</th>
                        <th class="py-3.5 px-4">Target Entitas</th>
                        <th class="py-3.5 px-4">Alamat IP & Jaringan</th>
                        <th class="py-3.5 px-4 text-right">Inspeksi Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($logs as $log)
                        @php
                            $actionStyle = match($log->action) {
                                'data_exported' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'settings_updated' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'cache_cleared' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'report_moderated', 'verification_approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'verification_rejected', 'report_rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
                                'live_toggled', 'script_updated' => 'bg-purple-100 text-purple-800 border-purple-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <!-- Timestamp -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900 block font-mono text-[11px]">{{ $log->created_at->format('d M Y - H:i:s') }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $log->created_at->diffForHumans() }}</span>
                            </td>

                            <!-- Operator Actor -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-xl bg-brand-gold text-brand-black font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block truncate max-w-[160px]">{{ $log->user->name ?? 'System Internal' }}</span>
                                        <span class="text-gray-400 text-[10px] block truncate max-w-[160px]">{{ $log->user->email ?? 'Automated Daemon' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-xl text-[11px] font-mono font-bold border inline-block {{ $actionStyle }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Target Entity -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($log->target_type)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-gray-100 text-gray-800 font-semibold text-[11px]">
                                        <span>{{ class_basename($log->target_type) }}</span>
                                        @if($log->target_id)
                                            <span class="text-gray-400">#{{ $log->target_id }}</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-400 font-mono text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Network Metadata -->
                            <td class="py-3.5 px-4 whitespace-nowrap font-mono text-[11px] text-gray-600">
                                <div class="space-y-0.5">
                                    <span class="font-semibold text-gray-800 block">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                    <span class="text-[10px] text-gray-400 block truncate max-w-[200px]" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 28) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Payload Inspection Action -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                @if(!empty($log->metadata))
                                    <button type="button" 
                                            @click="inspectPayload('{{ $log->id }}', '{{ $log->action }}', {{ json_encode($log->metadata) }})" 
                                            class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-brand-gold hover:text-brand-black text-gray-700 text-[11px] font-bold transition-all inline-flex items-center gap-1 cursor-pointer">
                                        <i data-lucide="code" class="w-3.5 h-3.5"></i>
                                        <span>Lihat Payload</span>
                                    </button>
                                @else
                                    <span class="text-gray-300 text-[11px] italic">Kosong</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-gray-400 bg-gray-50/50">
                                <i data-lucide="shield-check" class="w-10 h-10 mx-auto text-gray-300 mb-2"></i>
                                <h4 class="text-sm font-bold text-gray-700">Tidak Ada Rekaman Audit Log</h4>
                                <p class="text-xs text-gray-400 mt-1">Tidak ditemukan log aktivitas yang sesuai dengan parameter pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- 4. Slide-Over Payload Inspection Drawer / Modal -->
    <div x-show="payloadModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-hidden flex justify-end bg-gray-950/60 backdrop-blur-xs"
         @keydown.escape.window="payloadModalOpen = false">
        
        <div x-show="payloadModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             @click.outside="payloadModalOpen = false"
             class="w-full max-w-xl bg-white h-full shadow-2xl flex flex-col justify-between">
            
            <!-- Drawer Header -->
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="space-y-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-gold/20 text-brand-black" x-text="'Log ID #' + inspectedLogId"></span>
                    <h3 class="text-base font-black text-gray-900" x-text="'Inspeksi Metadata: ' + inspectedAction"></h3>
                </div>
                <button type="button" @click="payloadModalOpen = false" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Drawer Content (JSON Formatted View) -->
            <div class="p-6 overflow-y-auto flex-1 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                        <i data-lucide="file-json" class="w-4 h-4 text-brand-gold"></i>
                        <span>JSON Payload Schema:</span>
                    </span>
                    <button type="button" 
                            @click="copyPayloadJson()" 
                            class="px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10px] font-bold transition-colors inline-flex items-center gap-1 cursor-pointer">
                        <i data-lucide="copy" class="w-3 h-3"></i>
                        <span x-text="copied ? 'Tersalin!' : 'Salin JSON'"></span>
                    </button>
                </div>

                <pre class="p-4 rounded-2xl bg-gray-950 text-emerald-400 font-mono text-xs overflow-x-auto border border-gray-800 leading-relaxed max-h-[480px] scrollbar-thin"><code x-text="JSON.stringify(inspectedPayload, null, 2)"></code></pre>
            </div>

            <!-- Drawer Footer -->
            <div class="p-6 border-t border-gray-100 bg-gray-50 flex items-center justify-end">
                <button type="button" @click="payloadModalOpen = false" class="px-5 py-2.5 rounded-2xl bg-gray-950 hover:bg-brand-gold hover:text-brand-black text-white text-xs font-bold transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function auditLogsDashboard() {
    return {
        payloadModalOpen: false,
        inspectedLogId: '',
        inspectedAction: '',
        inspectedPayload: {},
        copied: false,

        inspectPayload(id, action, payload) {
            this.inspectedLogId = id;
            this.inspectedAction = action;
            this.inspectedPayload = payload;
            this.copied = false;
            this.payloadModalOpen = true;

            this.$nextTick(() => {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });
        },

        copyPayloadJson() {
            navigator.clipboard.writeText(JSON.stringify(this.inspectedPayload, null, 2)).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
    }
}
</script>
@endsection
