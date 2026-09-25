@extends('layouts.public')

@section('title', $business->name . ' — Produk & Jasa Kukar (Habar Etam)')
@section('meta_description', Str::limit(strip_tags($business->description), 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-medium text-gray-500 mb-6 reveal-blur-spring">
        <a href="{{ route('home') }}" class="hover:text-brand-black transition-colors flex items-center gap-1">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <a href="{{ route('businesses.index') }}" class="hover:text-brand-black transition-colors flex items-center gap-1">
            <i data-lucide="store" class="w-3.5 h-3.5"></i>
            <span>Produk & Jasa</span>
        </a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <span class="text-brand-black font-bold truncate max-w-xs sm:max-w-md">{{ $business->name }}</span>
    </nav>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Details (8 Cols) -->
        <div class="lg:col-span-8 space-y-6 reveal-blur-spring">
            
            <!-- Business Main Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl shadow-subtle relative overflow-hidden">
                <!-- Hero Photo Banner -->
                <div class="h-64 sm:h-72 w-full relative overflow-hidden bg-slate-900">
                    <img src="{{ $business->photo_url }}" 
                         alt="{{ $business->name }}" 
                         class="w-full h-full object-cover">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>

                    <!-- Top Badges Overlay -->
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between gap-2 z-10">
                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-600/90 backdrop-blur-md text-white border border-emerald-400/40 uppercase tracking-wider shadow-sm">
                            {{ $business->category }}
                        </span>

                        <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-black/60 backdrop-blur-md text-white/90 border border-white/20 flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                            <span>{{ $business->location_district }}</span>
                        </span>
                    </div>

                    <!-- Bottom Info Overlay -->
                    <div class="absolute bottom-4 left-4 right-4 flex items-end gap-3.5 z-10">
                        <div class="w-14 h-14 rounded-2xl bg-white text-emerald-900 font-black text-xl flex items-center justify-center shadow-lg shrink-0 border-2 border-white">
                            {{ strtoupper(substr($business->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1 text-white">
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight leading-tight drop-shadow-md">
                                {{ $business->name }}
                            </h1>
                            <div class="text-xs sm:text-sm font-semibold text-gray-200 flex items-center gap-1.5 mt-1 drop-shadow-sm">
                                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-300"></i>
                                <span>{{ $business->address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <!-- Operating Hours & Location Banner -->
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-emerald-50/70 via-teal-50/50 to-gray-50 border border-emerald-200/70 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-[11px] text-emerald-800 font-bold uppercase tracking-wider">Jam Operasional:</div>
                                <div class="text-sm sm:text-base font-black text-emerald-950">
                                    {{ $business->operating_hours ?: 'Buka Setiap Hari (Konfirmasi via WhatsApp)' }}
                                </div>
                            </div>
                        </div>

                        <div class="sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-emerald-200/60">
                            <div class="text-[11px] text-emerald-800 font-bold uppercase tracking-wider flex sm:justify-end items-center gap-1">
                                <i data-lucide="map" class="w-3.5 h-3.5 text-emerald-700"></i>
                                <span>Wilayah:</span>
                            </div>
                            <div class="text-sm font-black text-emerald-950 mt-0.5">
                                Kec. {{ $business->location_district }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description & Services -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle">
                <div class="flex items-center gap-2 text-brand-black font-extrabold text-base mb-4 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="store" class="w-4 h-4"></i>
                    </div>
                    <span>Tentang Usaha & Layanan yang Disediakan</span>
                </div>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line space-y-2">
                    {{ $business->description }}
                </div>
            </div>

            <!-- Address & Location Detail -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle">
                <div class="flex items-center gap-2 text-brand-black font-extrabold text-base mb-4 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <span>Alamat & Lokasi Usaha</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-gray-700">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200/70">
                        <div class="text-gray-400 font-bold uppercase text-[10px] mb-1">Alamat Lengkap:</div>
                        <div class="font-semibold text-gray-900 leading-relaxed">{{ $business->address }}</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200/70">
                        <div class="text-gray-400 font-bold uppercase text-[10px] mb-1">Kecamatan:</div>
                        <div class="font-semibold text-gray-900 leading-relaxed">Kecamatan {{ $business->location_district }}, Kutai Kartanegara</div>
                    </div>
                </div>
            </div>

            <!-- Related Businesses -->
            @if(isset($relatedBusinesses) && $relatedBusinesses->isNotEmpty())
                <div class="pt-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-brand-black flex items-center gap-2">
                            <i data-lucide="sparkles" class="w-5 h-5 text-amber-500"></i>
                            <span>Usaha Serupa di Kategori {{ $business->category }}</span>
                        </h2>
                        <a href="{{ route('businesses.index', ['category' => $business->category]) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                            <span>Lihat Semua</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($relatedBusinesses as $rel)
                            <a href="{{ route('businesses.show', $rel->slug) }}" class="rounded-2xl bg-white border border-gray-200/90 hover:border-emerald-500/70 shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between overflow-hidden">
                                <div class="h-28 w-full relative overflow-hidden bg-slate-900">
                                    <img src="{{ $rel->photo_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <span class="absolute top-2 left-2 text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-600/90 text-white uppercase">
                                        {{ $rel->category }}
                                    </span>
                                </div>
                                <div class="p-3.5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-xs font-black text-brand-black group-hover:text-emerald-700 mt-1 line-clamp-2 transition-colors">
                                            {{ $rel->name }}
                                        </h4>
                                        <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $rel->description }}</p>
                                    </div>
                                    <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400">
                                        <span class="truncate">{{ $rel->location_district }}</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400 group-hover:text-emerald-700 group-hover:translate-x-0.5 transition-transform"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right: Contact & Info (4 Cols Sticky) -->
        <div class="lg:col-span-4 space-y-6 sticky top-24 reveal-blur-spring">
            
            <!-- Direct Contact Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 shadow-subtle relative overflow-hidden">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-sm font-black text-brand-black uppercase tracking-wider">Hubungi Pelaku Usaha</h3>
                </div>

                <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                    Tertarik dengan produk atau ingin memesan jasa ini? Hubungi langsung via WhatsApp untuk respon cepat dan negosiasi.
                </p>

                <div class="space-y-2.5">
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $business->phone_whatsapp);
                        if (str_starts_with($cleanPhone, '08')) {
                            $cleanPhone = '628' . substr($cleanPhone, 2);
                        }
                    @endphp

                    <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($business->name) }},%20saya%20tertarik%20dengan%20produk%2Fjasa%20Anda%20yang%20tercantum%20di%20portal%20*Habar%20Etam*.%20Bisa%20info%20katalog%2Fpricelist%20lebih%20lanjut%3F" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-2xl font-black text-xs shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition-all hover:scale-[1.01]">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span>Chat WhatsApp Bisnis</span>
                    </a>

                    <a href="tel:{{ preg_replace('/[^0-9]/', '', $business->phone_whatsapp) }}" 
                       class="w-full py-3 px-4 rounded-2xl border border-gray-200 hover:border-gray-300 bg-gray-50 hover:bg-white text-gray-800 text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-2xs">
                        <i data-lucide="phone" class="w-4 h-4 text-emerald-600"></i>
                        <span>Telepon: {{ $business->phone_whatsapp }}</span>
                    </a>

                    @if($business->instagram)
                        @php
                            $igUsername = ltrim($business->instagram, '@');
                        @endphp
                        <a href="https://instagram.com/{{ $igUsername }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-full py-3 px-4 rounded-2xl border border-pink-200 hover:border-pink-300 bg-pink-50/50 hover:bg-pink-50 text-pink-700 text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-2xs">
                            <i data-lucide="instagram" class="w-4 h-4 text-pink-600"></i>
                            <span class="truncate">Instagram: {{ $business->instagram }}</span>
                        </a>
                    @endif
                </div>

                <!-- Employer Metadata Card -->
                <div class="mt-5 pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Pemilik Usaha:</span>
                        <span class="font-bold text-gray-800">{{ $business->user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Status Listing:</span>
                        <span class="font-bold text-emerald-700 flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Terverifikasi Redaksi</span>
                        </span>
                    </div>
                </div>

                <!-- Owner / Admin Controls -->
                @auth
                    @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                        <div class="mt-5 pt-4 border-t border-gray-200">
                            <div class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                                <i data-lucide="settings-2" class="w-3.5 h-3.5 text-gray-500"></i>
                                <span>Kelola Profil Usaha Anda:</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('businesses.edit', $business->id) }}" class="btn-outline flex-1 text-xs py-2 px-3 rounded-xl font-bold flex items-center justify-center gap-1.5">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit Profil</span>
                                </a>
                                <form method="POST" action="{{ route('businesses.destroy', $business->id) }}" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profil usaha ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger w-full text-xs py-2 px-3 rounded-xl font-bold flex items-center justify-center gap-1.5">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Share Business Card -->
            <div class="bg-white border border-gray-200/90 rounded-3xl p-5 shadow-subtle flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-gray-700 font-bold">
                    <i data-lucide="share-2" class="w-4 h-4 text-emerald-600"></i>
                    <span>Bagikan Profil Usaha</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="https://wa.me/?text={{ urlencode('Profil Usaha & Layanan ' . $business->name . ' (' . $business->category . ' - Kukar): ' . url()->current()) }}" 
                       target="_blank" 
                       class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors flex items-center justify-center shadow-2xs" 
                       title="Bagikan ke WhatsApp">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                    </a>
                    <button type="button" 
                            onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan profil usaha berhasil disalin!');" 
                            class="w-8 h-8 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors flex items-center justify-center shadow-2xs" 
                            title="Salin Tautan">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
