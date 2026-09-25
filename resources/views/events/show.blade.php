@extends('layouts.public')

@section('title', $event->title . ' — Agenda Event & Kegiatan Kukar')
@section('meta_description', Str::limit(strip_tags($event->description), 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-brand-black transition-colors">Beranda</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <a href="{{ route('events.index') }}" class="hover:text-brand-black transition-colors">Event & Agenda</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <span class="text-brand-black font-bold truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
    </nav>

    @php
        $startDate = $event->start_date;
        $endDate = $event->end_date;
        $today = Carbon\Carbon::today();
        $isOngoing = $today->betweenIncluded($startDate, $endDate ?? $startDate);
        $isPast = ($endDate ? $endDate->lt($today) : $startDate->lt($today));
        $daysRemaining = $today->diffInDays($startDate, false);

        // Category Palette
        $catColors = [
            'Budaya & Adat' => ['badge' => 'bg-amber-100 text-amber-900 border-amber-300', 'gradient' => 'from-amber-600 via-amber-800 to-amber-950', 'icon' => 'landmark'],
            'Musik & Komunitas' => ['badge' => 'bg-purple-100 text-purple-900 border-purple-300', 'gradient' => 'from-purple-600 via-indigo-800 to-slate-950', 'icon' => 'music-2'],
            'Olahraga & Hobi' => ['badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300', 'gradient' => 'from-emerald-600 via-teal-800 to-slate-950', 'icon' => 'trophy'],
            'Edukasi & Seminar' => ['badge' => 'bg-blue-100 text-blue-900 border-blue-300', 'gradient' => 'from-blue-600 via-sky-800 to-slate-950', 'icon' => 'graduation-cap'],
        ];
        $theme = $catColors[$event->category] ?? ['badge' => 'bg-indigo-100 text-indigo-900 border-indigo-300', 'gradient' => 'from-indigo-600 via-slate-800 to-slate-950', 'icon' => 'calendar'];

        // Google Calendar Link generator
        $calTitle = urlencode($event->title . ' — Habar Etam Kukar');
        $calDetails = urlencode($event->description . "\n\nInfo Lengkap: " . route('events.show', $event->slug));
        $calLocation = urlencode($event->location_name . ($event->location_address ? ', ' . $event->location_address : ''));
        $calStart = $startDate->format('Ymd');
        $calEnd = ($endDate ? $endDate->copy()->addDay() : $startDate->copy()->addDay())->format('Ymd');
        $gCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$calTitle}&dates={$calStart}/{$calEnd}&details={$calDetails}&location={$calLocation}";
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Event Details (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white border border-gray-200/90 rounded-3xl overflow-hidden shadow-subtle reveal-blur-spring">
                
                <!-- Hero Visual Banner Cover -->
                <div class="relative h-64 sm:h-80 w-full bg-gradient-to-br {{ $theme['gradient'] }} overflow-hidden">
                    @if($event->poster_image)
                        <img src="{{ asset('storage/' . $event->poster_image) }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-cover brightness-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/20"></div>
                    @else
                        <!-- Cultural Pattern Overlay -->
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1.5px,transparent_1.5px)] [background-size:20px_20px]"></div>
                        <div class="absolute right-6 bottom-4 text-white/10 pointer-events-none">
                            <i data-lucide="{{ $theme['icon'] }}" class="w-36 h-36 stroke-[1]"></i>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/10"></div>
                    @endif

                    <!-- Top Banner Badges -->
                    <div class="absolute top-4 inset-x-4 sm:inset-x-6 flex items-center justify-between gap-2 z-10">
                        <a href="{{ route('events.index', ['category' => $event->category]) }}" class="text-xs font-black px-3 py-1 rounded-xl shadow-md border backdrop-blur-md transition-transform hover:scale-105 {{ $theme['badge'] }}">
                            {{ $event->category }}
                        </a>

                        @if($isOngoing)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-500 text-white text-xs font-black shadow-md animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-white"></span>
                                <span>Sedang Berlangsung</span>
                            </span>
                        @elseif($isPast)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-900/90 text-gray-300 text-xs font-bold border border-white/20">
                                <i data-lucide="check" class="w-3.5 h-3.5 text-gray-400"></i>
                                <span>Telah Selesai</span>
                            </span>
                        @elseif($daysRemaining >= 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-black/70 backdrop-blur-md text-amber-300 text-xs font-black border border-amber-400/40">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span>{{ $daysRemaining == 0 ? 'Hari Ini!' : ($daysRemaining . ' Hari Lagi') }}</span>
                            </span>
                        @endif
                    </div>

                    <!-- Bottom Title on Banner -->
                    <div class="absolute bottom-4 inset-x-4 sm:inset-x-6 z-10 text-white">
                        <div class="text-xs text-amber-300 font-semibold mb-1">Penyelenggara: {{ $event->organizer }}</div>
                        <h1 class="text-xl sm:text-3xl font-black tracking-tight leading-tight drop-shadow-md">
                            {{ $event->title }}
                        </h1>
                    </div>
                </div>

                <!-- Event Details Content -->
                <div class="p-6 sm:p-8">
                    
                    <!-- 3-Pill Date, Time, Venue Box -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 p-4 sm:p-5 rounded-2xl bg-gray-50 border border-gray-200/80 text-xs text-gray-800 mb-8 shadow-xs">
                        <!-- Date -->
                        <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-white border border-gray-100 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase text-gray-400">Tanggal Acara</div>
                                <div class="font-extrabold text-sm text-brand-black">
                                    {{ $startDate->translatedFormat('d M Y') }}
                                    @if($endDate && $endDate != $startDate)
                                        <div class="text-xs text-gray-500 font-medium">s/d {{ $endDate->translatedFormat('d M Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-white border border-gray-100 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase text-gray-400">Waktu / Sesi</div>
                                <div class="font-extrabold text-sm text-brand-black">{{ $event->start_time ?: 'WITA' }}</div>
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-white border border-gray-100 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase text-gray-400">Lokasi / Venue</div>
                                <div class="font-extrabold text-sm text-brand-black truncate" title="{{ $event->location_name }}">{{ $event->location_name }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="space-y-4">
                        <h2 class="text-base font-black text-brand-black tracking-tight flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-indigo-600"></i>
                            <span>Deskripsi & Rangkaian Acara</span>
                        </h2>
                        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50/50 p-5 rounded-2xl border border-gray-100">
                            {{ $event->description }}
                        </div>
                    </div>

                    <!-- Location Address Details -->
                    @if($event->location_address)
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500"></i>
                                <span>Alamat Lengkap Lokasi</span>
                            </h3>
                            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="text-xs text-gray-800 font-medium">
                                    <strong class="text-brand-black">{{ $event->location_name }}</strong><br>
                                    {{ $event->location_address }}
                                </div>
                                <a href="https://maps.google.com/?q={{ urlencode($event->location_name . ' ' . $event->location_address . ' Tenggarong Kukar') }}" 
                                   target="_blank" 
                                   class="btn-outline text-xs py-2 px-4 rounded-xl shrink-0 flex items-center justify-center gap-1.5 hover:bg-white transition-colors">
                                    <i data-lucide="navigation" class="w-3.5 h-3.5 text-indigo-600"></i>
                                    <span>Petunjuk Arah Google Maps</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Action Bar: Add to Calendar & Share -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                        <a href="{{ $gCalUrl }}" target="_blank" class="btn-outline py-2.5 px-4 rounded-xl text-xs font-bold text-gray-700 hover:text-indigo-600 hover:border-indigo-300 flex items-center gap-2 transition-all">
                            <i data-lucide="calendar-plus" class="w-4 h-4 text-indigo-600"></i>
                            <span>+ Simpan ke Google Kalender</span>
                        </a>

                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 font-medium">Bagikan:</span>
                            <button type="button" 
                                    onclick="shareEvent('{{ addslashes($event->title) }}', window.location.href)" 
                                    class="p-2.5 rounded-xl border border-gray-200 hover:bg-gray-100 text-gray-700 transition-colors" 
                                    title="Bagikan Tautan">
                                <i data-lucide="share-2" class="w-4 h-4"></i>
                            </button>
                            <a href="https://wa.me/?text={{ urlencode('Cek agenda acara: ' . $event->title . ' di Habar Etam: ' . route('events.show', $event->slug)) }}" 
                               target="_blank" 
                               class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100 transition-colors" 
                               title="Bagikan ke WhatsApp">
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Right: Organizer & Contacts (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white border border-gray-200/90 rounded-3xl p-6 shadow-subtle space-y-5 reveal-blur-spring">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-brand-black">Penyelenggara Acara</h3>
                        <div class="text-xs text-gray-500">{{ $event->organizer }}</div>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs text-gray-600 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Diposting Oleh</span>
                        <span class="font-bold text-gray-800">{{ $event->user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Terdaftar Sejak</span>
                        <span class="font-medium text-gray-600">{{ $event->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                @if($event->contact_phone)
                    <div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $event->contact_phone) }}?text=Halo%20Panitia%20{{ urlencode($event->organizer) }},%20saya%20ingin%20bertanya%20mengenai%20event%20{{ urlencode($event->title) }}%20di%20Habar%20Etam." 
                           target="_blank" 
                           class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 transition-transform hover:scale-[1.02] active:scale-95">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Hubungi Panitia (WhatsApp)</span>
                        </a>
                    </div>
                @endif

                @auth
                    @if(auth()->id() === $event->user_id || auth()->user()->isAdmin())
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-xs font-bold text-gray-700 mb-2">Aksi Pengelola:</div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('events.edit', $event->id) }}" class="btn-outline flex-1 text-xs py-2 px-3 rounded-xl font-bold flex items-center justify-center gap-1.5">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('events.destroy', $event->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda event ini?')" class="btn-danger text-xs py-2 px-3 rounded-xl font-bold flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Other Upcoming Events Recommendation -->
            @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
                <div class="bg-white border border-gray-200/90 rounded-3xl p-6 shadow-subtle space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Agenda Lainnya</span>
                    </h3>

                    <div class="space-y-3">
                        @foreach($upcomingEvents as $other)
                            <a href="{{ route('events.show', $other->slug) }}" class="block p-3 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/50 transition-all group">
                                <div class="flex items-center justify-between gap-2 text-[10px] text-gray-500 mb-1">
                                    <span class="font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">{{ $other->start_date->format('d M Y') }}</span>
                                    <span class="truncate">{{ $other->category }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-brand-black group-hover:text-indigo-700 transition-colors line-clamp-1">
                                    {{ $other->title }}
                                </h4>
                                <p class="text-[11px] text-gray-500 mt-0.5 truncate">{{ $other->location_name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>

<!-- Share Script -->
<script>
function shareEvent(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title + ' — Habar Etam Kukar',
            text: 'Cek agenda kegiatan: ' + title + ' di Habar Etam!',
            url: url
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Tautan agenda kegiatan berhasil disalin!');
        }).catch(() => {
            prompt('Salin tautan event ini:', url);
        });
    }
}
</script>
@endsection
