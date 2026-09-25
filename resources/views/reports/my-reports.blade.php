@extends('layouts.public')

@section('title', 'Pengaduan Saya — Lapor Etam')
@section('meta_description', 'Riwayat pengaduan dan pemantauan tiket keluhan warga Anda di Kutai Kartanegara.')

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
        <span class="text-red-900 font-bold bg-red-50 px-2 py-0.5 rounded-md border border-red-200">Pengaduan Saya</span>
    </nav>

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-card">
        <div>
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-red-700 mb-1">
                <i data-lucide="user-check" class="w-4 h-4"></i>
                <span>Dasbor Pengaduan Warga</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-brand-black">Riwayat Pengaduan Saya</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Pantau perkembangan tindak lanjut, liputan studio, dan solusi lapangan dari aduan yang Anda kirimkan.</p>
        </div>

        <a href="{{ route('reports.create') }}" class="btn-gold text-xs py-3 px-5 font-bold shadow-gold-glow inline-flex items-center justify-center gap-2 rounded-2xl shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Kirim Pengaduan Baru</span>
        </a>
    </div>

    @if($reports->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center shadow-subtle max-w-lg mx-auto">
            <div class="w-16 h-16 bg-red-50 text-red-700 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100">
                <i data-lucide="file-question" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-black text-brand-black">Belum Ada Pengaduan yang Dikirim</h3>
            <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                Anda belum pernah mengirimkan laporan kendala fasilitas umum. Bantu perbaiki lingkungan Anda dengan mengirim aduan pertama.
            </p>
            <div class="mt-6">
                <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-800 text-white text-xs font-bold hover:bg-red-900 transition-colors shadow-xs">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Kirim Pengaduan Pertama</span>
                </a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reports as $rep)
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 sm:p-6 shadow-subtle hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                    <div class="space-y-2 max-w-3xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-xs font-extrabold text-red-700 bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-lg">
                                {{ $rep->ticket_number }}
                            </span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg bg-gray-100 text-gray-700">
                                {{ $rep->category }}
                            </span>
                            
                            @if($rep->status === 'live_agenda')
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-800 border border-purple-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600 animate-pulse"></span>
                                    <span>AGENDA LIVE STUDIO</span>
                                </span>
                            @elseif($rep->status === 'resolved')
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i>
                                    <span>SELESAI DITANGANI</span>
                                </span>
                            @elseif($rep->status === 'processing_editorial')
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-0.5 rounded-lg bg-blue-100 text-blue-800 border border-blue-300">
                                    <i data-lucide="refresh-cw" class="w-3 h-3 text-blue-600"></i>
                                    <span>DIPROSES REDAKSI</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-extrabold px-2.5 py-0.5 rounded-lg bg-amber-100 text-amber-800 border border-amber-300">
                                    <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                    <span>MENUNGGU VERIFIKASI</span>
                                </span>
                            @endif
                        </div>

                        <h3 class="text-base font-black text-brand-black group-hover:text-red-700 transition-colors leading-snug">
                            <a href="{{ route('reports.show', $rep->ticket_number) }}">{{ $rep->title }}</a>
                        </h3>
                        
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i>
                                <span>{{ $rep->address }} ({{ $rep->location_district }})</span>
                            </span>
                            <span>•</span>
                            <span>{{ $rep->created_at->format('d M Y, H:i') }} WITA</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('reports.show', $rep->ticket_number) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition-colors">
                            <span>Lihat Progres</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $reports->links('partials.pagination') }}
        </div>
    @endif

</div>
@endsection
