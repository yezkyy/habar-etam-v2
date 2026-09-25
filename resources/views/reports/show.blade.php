@extends('layouts.public')

@section('title', 'Tiket ' . $report->ticket_number . ': ' . $report->title . ' — Lapor Etam')
@section('meta_description', Str::limit(strip_tags($report->description), 160))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .report-tile-osm .leaflet-tile-pane {
        filter: contrast(104%) brightness(99%) saturate(88%) hue-rotate(-2deg);
    }
    #report-map {
        min-height: 280px;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-red-700 transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <a href="{{ route('reports.index') }}" class="hover:text-red-700 transition-colors flex items-center gap-1">
            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-red-600"></i>
            <span>Lapor Etam</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-red-900 font-bold bg-red-50 px-2 py-0.5 rounded-md border border-red-200 font-mono">{{ $report->ticket_number }}</span>
    </nav>

    <!-- 4-Stage Workflow Progress Banner -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card mb-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-gray-100">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-red-700 flex items-center gap-1.5 mb-1">
                    <i data-lucide="git-commit" class="w-3.5 h-3.5"></i>
                    <span>Pelacakan Alur Penanganan Resmi</span>
                </span>
                <h2 class="text-lg font-black text-brand-black">Status & Kronologi Investigasi Kasus</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-extrabold text-red-700 bg-red-50 border border-red-200 px-3 py-1 rounded-xl shadow-xs">
                    Tiket: {{ $report->ticket_number }}
                </span>
                <button type="button" onclick="copyTicketCode('{{ $report->ticket_number }}')" class="p-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors" title="Salin Tiket">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="relative">
            <!-- Connecting Timeline Line -->
            <div class="hidden sm:block absolute top-1/2 left-10 right-10 h-1.5 bg-gray-100 -translate-y-1/2 z-0 rounded-full"></div>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 relative z-10">
                
                <!-- Step 1: Menunggu Verifikasi -->
                <div class="flex flex-col items-center text-center p-3 rounded-2xl {{ $report->workflow_step === 1 ? 'bg-amber-50/60 border border-amber-200/60' : '' }}">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs shadow-xs {{ $report->workflow_step >= 1 ? 'bg-amber-500 text-slate-950 ring-4 ring-amber-100' : 'bg-gray-200 text-gray-500' }}">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xs font-bold mt-2.5 {{ $report->workflow_step >= 1 ? 'text-slate-900' : 'text-gray-400' }}">Verifikasi Bukti</span>
                    <span class="text-[10px] text-gray-400 mt-0.5">Validasi Data & Privasi</span>
                </div>

                <!-- Step 2: Diproses Redaksi -->
                <div class="flex flex-col items-center text-center p-3 rounded-2xl {{ $report->workflow_step === 2 ? 'bg-blue-50/60 border border-blue-200/60' : '' }}">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs shadow-xs {{ $report->workflow_step >= 2 ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-gray-200 text-gray-500' }}">
                        @if($report->workflow_step > 2)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            <i data-lucide="refresh-cw" class="w-5 h-5 {{ $report->workflow_step === 2 ? 'animate-spin' : '' }}"></i>
                        @endif
                    </div>
                    <span class="text-xs font-bold mt-2.5 {{ $report->workflow_step >= 2 ? 'text-blue-900' : 'text-gray-400' }}">Diproses Redaksi</span>
                    <span class="text-[10px] text-gray-400 mt-0.5">Koordinasi OPD Kukar</span>
                </div>

                <!-- Step 3: Masuk Agenda Live -->
                <div class="flex flex-col items-center text-center p-3 rounded-2xl {{ $report->workflow_step === 3 ? 'bg-purple-50/60 border border-purple-200/60' : '' }}">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs shadow-xs {{ $report->workflow_step >= 3 ? 'bg-purple-600 text-white ring-4 ring-purple-100 animate-pulse' : 'bg-gray-200 text-gray-500' }}">
                        @if($report->workflow_step > 3)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            <i data-lucide="tv" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <span class="text-xs font-bold mt-2.5 {{ $report->workflow_step >= 3 ? 'text-purple-900' : 'text-gray-400' }}">Live Studio SCM</span>
                    <span class="text-[10px] text-gray-400 mt-0.5">Liputan & Siaran Terbuka</span>
                </div>

                <!-- Step 4: Selesai -->
                <div class="flex flex-col items-center text-center p-3 rounded-2xl {{ $report->workflow_step === 4 ? 'bg-emerald-50/60 border border-emerald-200/60' : '' }}">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs shadow-xs {{ $report->workflow_step >= 4 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-gray-200 text-gray-500' }}">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xs font-bold mt-2.5 {{ $report->workflow_step >= 4 ? 'text-emerald-900' : 'text-gray-400' }}">Tuntas Ditangani</span>
                    <span class="text-[10px] text-gray-400 mt-0.5">Perbaikan Selesai</span>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Report Details, Media & Map (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card">
                <!-- Header Meta -->
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-900 text-white shadow-xs">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-red-400"></i>
                        <span>{{ $report->category }}</span>
                    </span>

                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-semibold bg-red-50 text-red-800 border border-red-200">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-600"></i>
                        <span>{{ $report->location_district }}</span>
                    </span>

                    @if($report->status === 'live_agenda')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black bg-purple-100 text-purple-800 border border-purple-300">
                            <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                            <span>MASUK AGENDA SIARAN LIVE</span>
                        </span>
                    @elseif($report->status === 'resolved')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>STATUS: TUNTAS SELESAI</span>
                        </span>
                    @endif
                </div>

                <h1 class="text-xl sm:text-3xl font-black text-brand-black leading-tight mb-4">
                    {{ $report->title }}
                </h1>

                <!-- Story & Chronology -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-red-700 mb-3 flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Uraian & Kronologi Masalah</span>
                    </h2>
                    <div class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line font-normal">
                        {{ $report->description }}
                    </div>
                </div>

                <!-- Media Evidence Showcase -->
                @if($report->media->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-red-700 mb-4 flex items-center gap-1.5">
                            <i data-lucide="image" class="w-4 h-4"></i>
                            <span>Bukti Foto & Dokumentasi Lapangan ({{ $report->media->count() }})</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($report->media as $med)
                                @if($med->media_type === 'image')
                                    <a href="{{ Storage::url($med->path) }}" target="_blank" class="aspect-[16/10] bg-slate-900 rounded-2xl overflow-hidden border border-gray-200 shadow-sm block group relative">
                                        <img src="{{ Storage::url($med->path) }}" alt="Bukti Laporan Warga" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3 text-white text-xs font-bold gap-1.5">
                                            <i data-lucide="maximize-2" class="w-4 h-4"></i>
                                            <span>Buka Ukuran Penuh</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="aspect-[16/10] bg-gray-950 rounded-2xl overflow-hidden border border-gray-200 flex items-center justify-center text-white shadow-sm">
                                        <video controls class="w-full h-full object-cover">
                                            <source src="{{ Storage::url($med->path) }}">
                                        </video>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- GIS Location Map -->
                @if($report->latitude && $report->longitude)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xs font-extrabold uppercase tracking-wider text-red-700 flex items-center gap-1.5">
                                <i data-lucide="map" class="w-4 h-4"></i>
                                <span>Titik Lokasi Insiden (GPS)</span>
                            </h2>
                            <span class="text-xs font-mono text-gray-400">{{ $report->latitude }}, {{ $report->longitude }}</span>
                        </div>

                        <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner">
                            <div id="report-map" class="w-full h-72 report-tile-osm z-10"></div>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400">Koordinat akurat verifikasi lapangan</span>
                            <a href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-xs shadow-xs transition-colors">
                                <span>Navigasi Rute Google Maps</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Redaksi Statement & Progress Notes -->
            @if($report->editorial_summary || $report->admin_notes)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/90 rounded-3xl p-6 sm:p-8 shadow-card">
                    <div class="flex items-center gap-2 text-blue-900 font-black text-sm mb-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <i data-lucide="tv" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span>Catatan Resmi Perkembangan Redaksi & OPD</span>
                            <div class="text-[11px] text-blue-600 font-semibold">Studio SCM & Pemkab Kutai Kartanegara</div>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-blue-950 leading-relaxed whitespace-pre-line font-medium bg-white/70 p-4 rounded-2xl border border-blue-100">
                        {{ $report->editorial_summary ?: $report->admin_notes }}
                    </p>

                    @if($report->resolved_at)
                        <div class="mt-4 p-3 bg-emerald-100/80 border border-emerald-200 rounded-xl text-xs text-emerald-900 font-bold flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700 shrink-0"></i>
                            <span>Dinyatakan tuntas dan terverifikasi pada: {{ $report->resolved_at->format('d M Y, H:i') }} WITA</span>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- Right Column: Meta & Actions (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-slate-50 border border-slate-200/90 rounded-3xl p-6 shadow-card space-y-5">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 border-b border-slate-200 pb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-red-600"></i>
                    <span>Rincian Pengaduan</span>
                </h3>

                <div class="space-y-4 text-xs">
                    <!-- Address -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[11px] text-gray-400 block font-semibold">Alamat Lokasi</span>
                            <span class="font-bold text-slate-800 leading-snug">{{ $report->address }}</span>
                        </div>
                    </div>

                    <!-- District -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                            <i data-lucide="compass" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[11px] text-gray-400 block font-semibold">Kecamatan</span>
                            <span class="font-bold text-slate-800">{{ $report->location_district }}</span>
                        </div>
                    </div>

                    <!-- Created At -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[11px] text-gray-400 block font-semibold">Waktu Masuk</span>
                            <span class="font-bold text-slate-800">{{ $report->created_at->format('d M Y, H:i') }} WITA</span>
                        </div>
                    </div>

                    <!-- Reporter Status -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[11px] text-gray-400 block font-semibold">Status Pelapor</span>
                            <span class="font-bold text-emerald-800">Warga Terverifikasi</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Identitas NIK Terlindungi</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button Stack -->
                <div class="pt-4 border-t border-slate-200 space-y-2">
                    <a href="{{ route('reports.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-700 bg-white hover:bg-gray-100 transition-colors shadow-xs">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Kembali ke Lapor Etam</span>
                    </a>

                    <button type="button" 
                            onclick="copyTicketCode('{{ $report->ticket_number }}')" 
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs font-bold transition-colors shadow-xs">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                        <span>Salin Nomor Tiket</span>
                    </button>
                </div>
            </div>

            <!-- Recent Related Reports -->
            @if(isset($recentUpdates) && $recentUpdates->isNotEmpty())
                <div class="bg-white border border-gray-200/90 rounded-3xl p-6 shadow-subtle">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 mb-4 flex items-center gap-2">
                        <i data-lucide="list" class="w-4 h-4 text-red-600"></i>
                        <span>Pengaduan Terkait Lainnya</span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($recentUpdates as $other)
                            <div class="text-xs border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                                <span class="font-mono text-[10px] text-red-700 font-extrabold bg-red-50 px-1.5 py-0.5 rounded border border-red-200">{{ $other->ticket_number }}</span>
                                <a href="{{ route('reports.show', $other->ticket_number) }}" class="font-bold text-brand-black hover:text-red-700 block mt-1 leading-snug">
                                    {{ $other->title }}
                                </a>
                                <span class="text-[11px] text-gray-400 block mt-0.5">{{ $other->location_district }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($report->latitude && $report->longitude)
            if (typeof L !== 'undefined') {
                const map = L.map('report-map', {
                    center: [{{ $report->latitude }}, {{ $report->longitude }}],
                    zoom: 15,
                    scrollWheelZoom: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors • Habar Etam'
                }).addTo(map);

                const customMarkerHtml = `
                    <div class="relative w-10 h-10 flex items-center justify-center" style="cursor:pointer;">
                        <div style="position:absolute; width:100%; height:100%; background:#dc2626; opacity:0.25; border-radius:50%; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></div>
                        <div style="width:32px; height:32px; background:#b91c1c; border-radius:12px; border:2.5px solid #ffffff; box-shadow:0 8px 16px -2px rgba(185,28,28,0.4); display:flex; align-items:center; justify-content:center; color:#ffffff;">
                            <svg style="width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: customMarkerHtml,
                    className: 'report-show-marker',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -20]
                });

                L.marker([{{ $report->latitude }}, {{ $report->longitude }}], { icon: customIcon })
                    .addTo(map)
                    .bindPopup('<div style="font-family:\'Plus Jakarta Sans\',sans-serif; padding:6px 8px;"><strong>{{ addslashes($report->ticket_number) }}</strong><br><span style="font-size:11px; color:#64748b;">{{ addslashes($report->address) }}</span></div>')
                    .openPopup();
            }
        @endif
    });

    window.copyTicketCode = function(ticket) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(ticket).then(() => {
                if (window.showToast) {
                    window.showToast('success', 'Nomor Tiket Disalin', `Nomor tiket ${ticket} berhasil disalin ke clipboard.`);
                } else if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nomor Tiket Disalin',
                        text: ticket,
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
            });
        }
    };
</script>
@endpush
