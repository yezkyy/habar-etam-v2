@extends('layouts.public')

@section('title', $item->title . ' — Jual Cepat Habar Etam Tenggarong')
@section('meta_description', Str::limit(strip_tags($item->description), 160))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-200 reveal-blur-spring">
        <nav class="flex items-center space-x-2 text-xs text-gray-500 overflow-x-auto">
            <a href="{{ route('home') }}" class="hover:text-brand-black transition-colors flex items-center gap-1">
                <i data-lucide="home" class="w-3.5 h-3.5"></i>
                <span>Beranda</span>
            </a>
            <span>/</span>
            <a href="{{ route('quick-sales.index') }}" class="hover:text-brand-black transition-colors">Jual Cepat</a>
            <span>/</span>
            <a href="{{ route('quick-sales.index', ['category' => $item->category]) }}" class="hover:text-brand-gold-dark font-medium transition-colors">{{ $item->category }}</a>
            <span>/</span>
            <span class="text-brand-black font-bold truncate max-w-[200px] sm:max-w-xs">{{ $item->title }}</span>
        </nav>

        <!-- Share Actions -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan listing berhasil disalin ke clipboard!');" class="btn-outline text-xs py-1.5 px-3 rounded-xl flex items-center gap-1.5 hover:border-brand-gold">
                <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                <span>Salin Tautan</span>
            </button>
            <a href="https://api.whatsapp.com/send?text={{ urlencode('Cek barang jual cepat ini di Habar Etam: ' . $item->title . ' - ' . url()->current()) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-transparent text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                <span>Share WA</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Image Gallery & Rich Specifications (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Interactive Photo Gallery -->
            <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-subtle p-3.5 sm:p-4 reveal-blur-spring">
                <!-- Main Display Image -->
                <div class="aspect-video bg-gray-100 rounded-2xl overflow-hidden relative flex items-center justify-center group">
                    @if($item->primaryMedia())
                        <img id="main-product-image" src="{{ Storage::url($item->primaryMedia()->path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-all duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center text-gray-400 p-8">
                            <i data-lucide="image" class="w-16 h-16 text-gray-300 mb-2"></i>
                            <span class="text-xs">Foto belum diunggah oleh penjual</span>
                        </div>
                    @endif

                    <!-- Sold Status Overlay -->
                    @if($item->isSold())
                        <div class="absolute inset-0 bg-brand-black/80 backdrop-blur-xs flex flex-col items-center justify-center text-white">
                            <span class="font-black text-xl sm:text-2xl tracking-widest uppercase bg-rose-600 px-6 py-2 rounded-2xl shadow-xl border border-rose-400/40">
                                BARANG SUDAH TERJUAL
                            </span>
                            <p class="text-xs text-gray-300 mt-2">Listing ini sudah laku dan tidak lagi menerima penawaran.</p>
                        </div>
                    @endif

                    <!-- Zoom or Fullscreen Hint -->
                    <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-[11px] font-mono flex items-center gap-1.5 pointer-events-none">
                        <i data-lucide="camera" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>{{ $item->media->count() ?: 1 }} Foto</span>
                    </div>
                </div>

                <!-- Thumbnails Strip -->
                @if($item->media->count() > 1)
                    <div class="flex items-center gap-3 mt-3 overflow-x-auto pb-1 scrollbar-none">
                        @foreach($item->media as $idx => $media)
                            @php $mediaUrl = Storage::url($media->path); @endphp
                            <button type="button" 
                                onclick="switchProductImage('{{ $mediaUrl }}', this)" 
                                class="thumb-btn w-20 h-20 rounded-xl overflow-hidden border-2 {{ $idx === 0 ? 'border-brand-gold ring-2 ring-brand-gold/30' : 'border-gray-200 opacity-70 hover:opacity-100 hover:border-gray-400' }} shrink-0 focus:outline-none transition-all duration-200">
                                <img src="{{ $mediaUrl }}" alt="Thumbnail {{ $idx + 1 }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Key Specifications Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 reveal-stagger">
                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-subtle flex flex-col justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Kondisi Barang</span>
                    <div class="text-xs sm:text-sm font-extrabold text-brand-black mt-1 flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-4 h-4 text-brand-gold shrink-0"></i>
                        <span>{{ $item->condition }}</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-subtle flex flex-col justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Kategori</span>
                    <div class="text-xs sm:text-sm font-extrabold text-brand-black mt-1 flex items-center gap-1.5">
                        <i data-lucide="tag" class="w-4 h-4 text-blue-600 shrink-0"></i>
                        <span class="truncate">{{ $item->category }}</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-subtle flex flex-col justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Area COD</span>
                    <div class="text-xs sm:text-sm font-extrabold text-brand-black mt-1 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-rose-500 shrink-0"></i>
                        <span class="truncate">{{ $item->location_name }}</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-subtle flex flex-col justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Waktu Tayang</span>
                    <div class="text-xs sm:text-sm font-extrabold text-brand-black mt-1 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span class="truncate">{{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Full Product Description Card -->
            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring">
                <div class="flex items-center gap-2 pb-4 mb-4 border-b border-gray-100">
                    <i data-lucide="file-text" class="w-4 h-4 text-brand-gold-dark"></i>
                    <h2 class="text-sm font-black uppercase tracking-wider text-brand-black">Deskripsi & Kelengkapan Barang</h2>
                </div>

                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line prose prose-sm max-w-none">
                    {{ $item->description }}
                </div>
            </div>

            <!-- COD Safety Advice -->
            <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-3xl p-5 sm:p-6 text-xs text-emerald-950 flex items-start gap-4 reveal-blur-spring">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-emerald-900 text-sm mb-1">Jual Beli Aman Antar Warga Tenggarong</h4>
                    <p class="leading-relaxed text-emerald-800">
                        Disarankan bertemu langsung di tempat umum (seperti area Taman Kota Raja, Kedaton, atau kafe). Cek barang secara teliti sebelum melakukan pembayaran. Jangan mentransfer uang muka (DP).
                    </p>
                </div>
            </div>

        </div>

        <!-- Right: Sticky Price, Seller Profile & Contact Actions (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Sticky Summary Card -->
            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-7 shadow-xl sticky top-24 reveal-blur-spring">
                
                <!-- Category & Condition Badges -->
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-800 flex items-center gap-1.5">
                        <i data-lucide="folder" class="w-3 h-3 text-gray-500"></i>
                        <span>{{ $item->category }}</span>
                    </span>
                    <span class="text-[11px] font-extrabold px-3 py-1 rounded-full {{ str_starts_with($item->condition, 'Baru') ? 'bg-emerald-100 text-emerald-900 border border-emerald-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                        {{ $item->condition }}
                    </span>
                </div>

                <!-- Product Title -->
                <h1 class="text-xl sm:text-2xl font-black text-brand-black leading-snug tracking-tight">
                    {{ $item->title }}
                </h1>

                <!-- Price Box Highlight -->
                <div class="mt-4 p-4 rounded-2xl bg-gradient-to-br from-amber-50/80 via-white to-amber-50/40 border border-amber-200/80 shadow-xs">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Harga Penawaran Warga</span>
                    <div class="text-3xl sm:text-4xl font-black text-brand-gold-dark mt-1 flex items-baseline gap-2">
                        <span>{{ $item->formatted_price }}</span>
                        @if(!$item->isSold())
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-brand-gold/20 text-brand-black border border-brand-gold/40">Bebas Nego</span>
                        @endif
                    </div>
                </div>

                <!-- Location & Listing Info -->
                <div class="mt-5 space-y-2.5 text-xs text-gray-600 border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Titik COD:</span>
                        <span class="font-bold text-brand-black flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500"></i>
                            <span>{{ $item->location_name }}</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Kode Listing:</span>
                        <span class="font-mono font-bold text-gray-700">#QS-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Status Barang:</span>
                        @if($item->isSold())
                            <span class="font-extrabold text-rose-600 uppercase">Terjual</span>
                        @else
                            <span class="font-extrabold text-emerald-600 uppercase flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Tersedia (Aktif)</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Primary Action Buttons -->
                @if(!$item->isSold())
                    <div class="mt-6 space-y-3">
                        <!-- Direct WhatsApp Contact -->
                        <a href="https://wa.me/{{ $item->contact_whatsapp }}?text=Halo,%20saya%20tertarik%20dengan%20listing%20Jual%20Cepat%20Habar%20Etam:%20{{ urlencode($item->title) }}%20(Harga:%20{{ urlencode($item->formatted_price) }}).%20Apakah%20barang%20masih%20tersedia?" target="_blank" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                            <i data-lucide="message-circle" class="w-5 h-5"></i>
                            <span>Hubungi Penjual (WhatsApp)</span>
                        </a>

                        <!-- Direct Phone Call -->
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $item->contact_phone) }}" class="btn-outline w-full py-3 text-xs font-bold rounded-2xl flex items-center justify-center gap-2 hover:border-gray-400">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-500"></i>
                            <span>Panggil Telepon: {{ $item->contact_phone }}</span>
                        </a>
                    </div>
                @else
                    <div class="mt-6 p-4 rounded-2xl bg-gray-100 text-center text-xs text-gray-600 font-bold border border-gray-200">
                        <i data-lucide="check-circle" class="w-5 h-5 text-gray-400 mx-auto mb-1"></i>
                        Barang ini telah laku terjual.
                    </div>
                @endif

                <!-- Seller Profile Card -->
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-3">Informasi Penjual</span>
                    <div class="flex items-center gap-3.5 bg-gray-50/80 p-3.5 rounded-2xl border border-gray-100">
                        <div class="w-12 h-12 rounded-2xl bg-brand-black text-brand-gold font-black flex items-center justify-center shrink-0 text-base shadow-xs">
                            {{ substr($item->user->name ?? 'W', 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-black text-brand-black truncate">{{ $item->user->name ?? 'Warga Kukar' }}</span>
                                @if($item->user && $item->user->isVerified())
                                    <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[9px] font-bold flex items-center gap-0.5" title="Warga Terverifikasi">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                        <span>Terverifikasi</span>
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-gray-400"></i>
                                <span>Warga {{ $item->user->profile->district ?? 'Tenggarong, Kutai Kartanegara' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Owner / Admin Controls -->
                @auth
                    @if(auth()->id() === $item->user_id || auth()->user()->isAdmin())
                        <div class="mt-6 pt-5 border-t border-amber-200/60 bg-amber-50/50 -mx-6 -mb-6 p-5 rounded-b-3xl">
                            <div class="text-xs font-black text-brand-black mb-2 flex items-center gap-1.5">
                                <i data-lucide="settings-2" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                                <span>Menu Kelola Listing Anda:</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if(!$item->isSold())
                                    <form method="POST" action="{{ route('quick-sales.mark-sold', $item->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Tandai barang ini sebagai sudah terjual?')" class="btn-black text-xs py-2 px-3.5 rounded-xl font-bold">
                                            Tandai Terjual
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('quick-sales.edit', $item->id) }}" class="btn-outline text-xs py-2 px-3.5 rounded-xl font-bold bg-white">
                                    Edit Listing
                                </a>
                                <form method="POST" action="{{ route('quick-sales.destroy', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus listing barang ini secara permanen?')" class="btn-danger text-xs py-2 px-3.5 rounded-xl font-bold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth

            </div>

        </div>

    </div>

    <!-- Related Items in Same Category -->
    @if($relatedItems->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-gray-200 reveal-blur-spring">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-gold-dark">Rekomendasi Lainnya</span>
                    <h3 class="text-xl font-extrabold text-brand-black mt-0.5">Barang Lain di Kategori {{ $item->category }}</h3>
                </div>
                <a href="{{ route('quick-sales.index', ['category' => $item->category]) }}" class="text-xs font-bold text-brand-black hover:text-brand-gold-dark flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 reveal-stagger">
                @foreach($relatedItems as $rel)
                    <div class="content-card modern-hover-card relative flex flex-col justify-between group rounded-3xl shadow-subtle hover:shadow-xl bg-white border border-gray-200/90 overflow-hidden cursor-pointer transition-all">
                        <a href="{{ route('quick-sales.show', $rel->slug) }}" class="absolute inset-0 z-0" aria-label="{{ $rel->title }}"></a>
                        
                        <div class="aspect-video bg-gray-100 overflow-hidden relative pointer-events-none">
                            @if($rel->primaryMedia())
                                <img src="{{ Storage::url($rel->primaryMedia()->path) }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                    <i data-lucide="tag" class="w-6 h-6 text-gray-300"></i>
                                </div>
                            @endif
                            <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-brand-gold text-brand-black text-[10px] font-black">
                                {{ $rel->condition }}
                            </span>
                        </div>
                        
                        <div class="p-4 pointer-events-none">
                            <h4 class="text-xs font-bold text-brand-black group-hover:text-brand-gold-dark transition-colors truncate">{{ $rel->title }}</h4>
                            <div class="text-sm font-black text-brand-gold-dark mt-1">{{ $rel->formatted_price }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-gray-400"></i>
                                <span>{{ $rel->location_name }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- Image Switcher Script -->
<script>
function switchProductImage(url, btnElement) {
    const mainImg = document.getElementById('main-product-image');
    if (!mainImg) return;
    
    mainImg.style.opacity = '0.3';
    setTimeout(() => {
        mainImg.src = url;
        mainImg.style.opacity = '1';
    }, 120);

    document.querySelectorAll('.thumb-btn').forEach(btn => {
        btn.classList.remove('border-brand-gold', 'ring-2', 'ring-brand-gold/30');
        btn.classList.add('border-gray-200', 'opacity-70');
    });

    btnElement.classList.remove('border-gray-200', 'opacity-70');
    btnElement.classList.add('border-brand-gold', 'ring-2', 'ring-brand-gold/30');
}
</script>
@endsection
