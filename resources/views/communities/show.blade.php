@extends('layouts.public')

@section('title', $community->name . ' — Direktori Komunitas Kukar')
@section('meta_description', Str::limit(strip_tags($community->description), 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-brand-black transition-colors">Beranda</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <a href="{{ route('communities.index') }}" class="hover:text-brand-black transition-colors">Klub & Komunitas</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <span class="text-brand-black font-bold truncate max-w-xs sm:max-w-md">{{ $community->name }}</span>
    </nav>

    @php
        // Category Palette
        $catColors = [
            'Seni & Budaya' => ['badge' => 'bg-amber-100 text-amber-900 border-amber-300', 'gradient' => 'from-amber-600 via-amber-800 to-amber-950', 'icon' => 'landmark'],
            'Hobi & Kreatif' => ['badge' => 'bg-indigo-100 text-indigo-900 border-indigo-300', 'gradient' => 'from-indigo-600 via-indigo-800 to-slate-950', 'icon' => 'camera'],
            'Olahraga' => ['badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300', 'gradient' => 'from-emerald-600 via-teal-800 to-slate-950', 'icon' => 'trophy'],
            'Sosial Kemanusiaan' => ['badge' => 'bg-rose-100 text-rose-900 border-rose-300', 'gradient' => 'from-rose-600 via-pink-800 to-slate-950', 'icon' => 'users-2'],
        ];
        $theme = $catColors[$community->interest_category] ?? ['badge' => 'bg-teal-100 text-teal-900 border-teal-300', 'gradient' => 'from-teal-600 via-slate-800 to-slate-950', 'icon' => 'users-2'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Details (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white border border-gray-200/90 rounded-3xl overflow-hidden shadow-subtle reveal-blur-spring">
                
                <!-- Hero Visual Banner Cover -->
                <div class="relative h-64 sm:h-72 w-full bg-gradient-to-br {{ $theme['gradient'] }} overflow-hidden">
                    @if($community->photo)
                        <img src="{{ asset('storage/' . $community->photo) }}" 
                             alt="{{ $community->name }}" 
                             class="w-full h-full object-cover brightness-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/20"></div>
                    @else
                        <!-- Cultural Geometric Pattern Overlay -->
                        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1.5px,transparent_1.5px)] [background-size:20px_20px]"></div>
                        <div class="absolute right-6 bottom-4 text-white/10 pointer-events-none">
                            <i data-lucide="{{ $theme['icon'] }}" class="w-36 h-36 stroke-[1]"></i>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/10"></div>
                    @endif

                    <!-- Top Badges Overlay -->
                    <div class="absolute top-4 inset-x-4 sm:inset-x-6 flex items-center justify-between gap-2 z-10">
                        <a href="{{ route('communities.index', ['category' => $community->interest_category]) }}" class="text-xs font-black px-3 py-1 rounded-xl shadow-md border backdrop-blur-md transition-transform hover:scale-105 {{ $theme['badge'] }}">
                            {{ $community->interest_category }}
                        </a>

                        <div class="px-3 py-1 rounded-xl bg-black/60 backdrop-blur-md border border-white/25 text-white flex items-center gap-1.5 shadow-lg text-xs font-bold">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-400"></i>
                            <span>{{ $community->base_location }}</span>
                        </div>
                    </div>

                    <!-- Bottom Title on Banner -->
                    <div class="absolute bottom-4 inset-x-4 sm:inset-x-6 z-10 text-white">
                        <div class="text-xs text-teal-300 font-semibold mb-1 flex items-center gap-1.5">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Direktori Resmi Komunitas Warga Kukar</span>
                        </div>
                        <h1 class="text-xl sm:text-3xl font-black tracking-tight leading-tight drop-shadow-md">
                            {{ $community->name }}
                        </h1>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="p-6 sm:p-8">
                    
                    <!-- 3-Pill Highlights Box -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 p-4 sm:p-5 rounded-2xl bg-gray-50 border border-gray-200/80 text-xs text-gray-800 mb-8 shadow-xs">
                        <!-- Basis Lokasi -->
                        <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-white border border-gray-100 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase text-gray-400">Tempat Kumpul</div>
                                <div class="font-extrabold text-sm text-brand-black truncate" title="{{ $community->base_location }}">{{ $community->base_location }}</div>
                            </div>
                        </div>

                        <!-- Jadwal Rutin -->
                        <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-white border border-gray-100 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase text-gray-400">Jadwal Rutin</div>
                                <div class="font-extrabold text-sm text-brand-black truncate" title="{{ $community->activity_schedule ?: 'Fleksibel / Sesuai Agenda' }}">
                                    {{ $community->activity_schedule ?: 'Fleksibel / Agenda' }}
                                </div>
                            </div>
                        </div>

                        <!-- Narahubung -->
                        <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-white border border-gray-100 shadow-2xs">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center shrink-0">
                                <i data-lucide="user-check" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold uppercase text-gray-400">Pengurus / PIC</div>
                                <div class="font-extrabold text-sm text-brand-black truncate" title="{{ $community->contact_person ?: 'Pengurus Komunitas' }}">
                                    {{ $community->contact_person ?: 'Pengurus' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="space-y-4">
                        <h2 class="text-base font-black text-brand-black tracking-tight flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4 text-teal-600"></i>
                            <span>Tentang Komunitas & Rangkaian Kegiatan</span>
                        </h2>
                        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50/50 p-5 rounded-2xl border border-gray-100">
                            {{ $community->description }}
                        </div>
                    </div>

                    <!-- Media Sosial Details -->
                    @if($community->social_media)
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 flex items-center gap-1.5">
                                <i data-lucide="share-2" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>Kanal Informasi & Media Sosial</span>
                            </h3>
                            <div class="p-4 rounded-2xl bg-teal-50/60 border border-teal-200/80 flex items-center justify-between gap-4">
                                <div class="text-xs text-gray-800 font-bold flex items-center gap-2">
                                    <i data-lucide="globe" class="w-4 h-4 text-teal-600"></i>
                                    <span>{{ $community->social_media }}</span>
                                </div>
                                <span class="text-[11px] text-teal-700 font-semibold">Media Resmi</span>
                            </div>
                        </div>
                    @endif

                    <!-- Action Bar: Share & Connect -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 font-medium">Bagikan Profil Komunitas:</span>
                            <button type="button" 
                                    onclick="shareCommunity('{{ addslashes($community->name) }}', window.location.href)" 
                                    class="p-2.5 rounded-xl border border-gray-200 hover:bg-gray-100 text-gray-700 transition-colors" 
                                    title="Bagikan Tautan">
                                <i data-lucide="share-2" class="w-4 h-4"></i>
                            </button>
                            <a href="https://wa.me/?text={{ urlencode('Cek profil komunitas: ' . $community->name . ' di Habar Etam Kukar: ' . route('communities.show', $community->slug)) }}" 
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
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                        <i data-lucide="users-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-brand-black">Gabung Bersama Kami</h3>
                        <div class="text-xs text-gray-500">{{ $community->interest_category }}</div>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs text-gray-600 space-y-2">
                    @if($community->contact_person)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Pengurus / PIC</span>
                            <span class="font-bold text-gray-800">{{ $community->contact_person }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Diposting Oleh</span>
                        <span class="font-bold text-gray-800">{{ $community->user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Terdaftar Sejak</span>
                        <span class="font-medium text-gray-600">{{ $community->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                @if($community->contact_phone)
                    <div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $community->contact_phone) }}?text=Halo%20{{ urlencode($community->contact_person ?: 'Pengurus ' . $community->name) }},%20saya%20tertarik%20bergabung%20dengan%20komunitas%20{{ urlencode($community->name) }}%20yang%20saya%20lihat%20di%20Habar%20Etam." 
                           target="_blank" 
                           class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2 transition-transform hover:scale-[1.02] active:scale-95">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span>Hubungi Pengurus (WhatsApp)</span>
                        </a>
                    </div>
                @endif

                @auth
                    @if(auth()->id() === $community->user_id || auth()->user()->isAdmin())
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-xs font-bold text-gray-700 mb-2">Aksi Pengelola:</div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('communities.edit', $community->id) }}" class="btn-outline flex-1 text-xs py-2 px-3 rounded-xl font-bold flex items-center justify-center gap-1.5">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('communities.destroy', $community->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus direktori komunitas ini?')" class="btn-danger text-xs py-2 px-3 rounded-xl font-bold flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Other Communities Recommendation -->
            @if(isset($otherCommunities) && $otherCommunities->isNotEmpty())
                <div class="bg-white border border-gray-200/90 rounded-3xl p-6 shadow-subtle space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-teal-600"></i>
                        <span>Komunitas Lainnya</span>
                    </h3>

                    <div class="space-y-3">
                        @foreach($otherCommunities as $other)
                            <a href="{{ route('communities.show', $other->slug) }}" class="block p-3 rounded-2xl border border-gray-100 hover:border-teal-300 hover:bg-teal-50/50 transition-all group">
                                <div class="flex items-center justify-between gap-2 text-[10px] text-gray-500 mb-1">
                                    <span class="font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full">{{ $other->interest_category }}</span>
                                    <span class="truncate">{{ $other->base_location }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-brand-black group-hover:text-teal-700 transition-colors line-clamp-1">
                                    {{ $other->name }}
                                </h4>
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
function shareCommunity(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title + ' — Direktori Komunitas Habar Etam',
            text: 'Cek komunitas: ' + title + ' di Habar Etam Kukar!',
            url: url
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            alert('Tautan profil komunitas berhasil disalin!');
        }).catch(() => {
            prompt('Salin tautan ini:', url);
        });
    }
}
</script>
@endsection
