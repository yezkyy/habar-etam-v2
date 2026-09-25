@extends('layouts.admin')

@section('title', 'Edit Harga: ' . $price->commodity_name . ' — Smart City Kukar')
@section('page_title', 'Smart City: Edit Catatan Harga')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-slate-900 to-emerald-950 text-white p-6 sm:p-7 rounded-3xl shadow-lg relative overflow-hidden border border-emerald-500/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-64 -top-12 w-48 h-48 bg-brand-gold/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-400">Smart City</span>
                <span>/</span>
                <a href="{{ route('admin.smart-city.prices.index') }}" class="hover:text-brand-gold transition-colors">Harga Pangan</a>
                <span>/</span>
                <span class="text-brand-gold font-semibold">Edit #{{ $price->id }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-300">
                        <i data-lucide="edit-3" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Edit Data: {{ $price->commodity_name }} ({{ $price->market_name }})</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Perbarui angka harga, satuan per takaran, tanggal pantauan, atau catatan pasokan lapangan.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('admin.smart-city.prices.index') }}" 
               class="h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- Error Summary Alerts -->
    @if($errors->any())
        <div class="p-5 bg-rose-50 border border-rose-200 rounded-3xl text-xs text-rose-800 space-y-2 shadow-2xs">
            <div class="flex items-center gap-2.5 font-bold text-rose-900 text-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                <span>Mohon perbaiki isian form berikut:</span>
            </div>
            <ul class="list-disc pl-7 space-y-1 text-xs">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $standardCategories = ['sembako', 'beras', 'bumbu_dapur', 'cabai', 'bawang', 'daging_ikan', 'daging', 'ikan', 'sayur_mayur', 'sayuran', 'sayur', 'telur_susu', 'telur'];
        $isCustomCategory = !in_array($price->category, $standardCategories);
        $initialCategory = $isCustomCategory ? 'lainnya' : $price->category;
        $initialCustomCategory = $isCustomCategory ? $price->category : '';

        $isCustomMarket = !in_array($price->market_name, $markets);
        $initialMarket = $isCustomMarket ? 'lainnya' : $price->market_name;
        $initialCustomMarket = $isCustomMarket ? $price->market_name : '';
    @endphp

    <!-- Main Edit Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.smart-city.prices.update', $price->id) }}" 
              method="POST" 
              x-data="{
                  selectedMarket: '{{ old('market_name', $initialMarket) }}',
                  selectedCategory: '{{ old('category', $initialCategory) }}',
                  price: '{{ old('price', (int) $price->price) }}',
                  previousPrice: '{{ old('previous_price', $price->previous_price ? (int) $price->previous_price : '') }}',
                  formatRupiah(number) {
                      if (!number) return 'Rp 0';
                      return 'Rp ' + Number(number).toLocaleString('id-ID');
                  },
                  get priceDifference() {
                      if (!this.price || !this.previousPrice) return null;
                      return Number(this.price) - Number(this.previousPrice);
                  },
                  get percentageDiff() {
                      if (!this.price || !this.previousPrice || Number(this.previousPrice) === 0) return null;
                      return (((Number(this.price) - Number(this.previousPrice)) / Number(this.previousPrice)) * 100).toFixed(1);
                  }
              }" 
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Lokasi Pasar & Komoditas -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-emerald-50 text-emerald-600">
                        <i data-lucide="store" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Pasar Pantauan & Komoditas</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Pasar Pemantauan -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-700">Pasar Tradisional <span class="text-rose-500">*</span></label>
                            <button type="button" 
                                    @click="selectedMarket = 'lainnya'; $nextTick(() => $refs.customMarketInput?.focus());"
                                    class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                                <span>+ Input Pasar Baru</span>
                            </button>
                        </div>
                        <select name="market_name" 
                                x-model="selectedMarket" 
                                data-search-limit="50"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            @foreach($markets as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                            <option value="lainnya">Lainnya (Input Nama Pasar Sendiri)</option>
                        </select>
                    </div>

                    <!-- Dynamic Custom Market Input (When Lainnya selected) -->
                    <div x-show="selectedMarket === 'lainnya'" 
                         x-transition 
                         class="md:col-span-2 space-y-2 p-4 rounded-2xl bg-amber-50/90 border border-amber-300 shadow-xs">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-amber-950 flex items-center gap-1.5">
                                <i data-lucide="store" class="w-4 h-4 text-amber-600"></i>
                                <span>Nama Pasar Tradisional Baru <span class="text-rose-500">*</span></span>
                            </label>
                            <button type="button" 
                                    @click="selectedMarket = 'Pasar Tangga Arung, Tenggarong'"
                                    class="text-[11px] font-bold text-gray-500 hover:text-gray-800 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                <span>Batal & Gunakan Pilihan</span>
                            </button>
                        </div>
                        <input type="text" 
                               name="custom_market" 
                               x-ref="customMarketInput"
                               value="{{ old('custom_market', $initialCustomMarket) }}" 
                               placeholder="Contoh: Pasar Tradisional Loa Duri, Pasar Subuh Tenggarong..." 
                               class="w-full px-4 py-3 rounded-2xl border border-amber-300 bg-white text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Nama Komoditas -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Nama Bahan Pokok / Komoditas <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-gray-400 font-normal">Spesifik & jelas</span>
                        </label>
                        <input type="text" 
                               name="commodity_name" 
                               value="{{ old('commodity_name', $price->commodity_name) }}"
                               required 
                               placeholder="Contoh: Cabai Rawit Merah (Tiung)" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Kategori Komoditas -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-700">Kategori Kelompok <span class="text-rose-500">*</span></label>
                            <button type="button" 
                                    @click="selectedCategory = 'lainnya'; $nextTick(() => $refs.customCategoryInput?.focus());"
                                    class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                                <span>+ Input Kategori Baru</span>
                            </button>
                        </div>
                        <select name="category" 
                                x-model="selectedCategory" 
                                data-search-limit="50"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            <option value="sembako">Sembako & Beras (Bahan Pokok)</option>
                            <option value="bumbu_dapur">Bumbu Dapur, Cabai & Bawang</option>
                            <option value="daging_ikan">Daging & Ikan Air Tawar Mahakam</option>
                            <option value="sayur_mayur">Sayur Mayur & Holtikultura</option>
                            <option value="telur_susu">Telur & Susu</option>
                            <option value="lainnya">Lainnya (Input Kategori Sendiri)</option>
                        </select>
                    </div>

                    <!-- Dynamic Custom Category Input (When Lainnya selected) -->
                    <div x-show="selectedCategory === 'lainnya'" 
                         x-transition 
                         class="md:col-span-2 space-y-2 p-4 rounded-2xl bg-amber-50/90 border border-amber-300 shadow-xs">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-amber-950 flex items-center gap-1.5">
                                <i data-lucide="tag" class="w-4 h-4 text-amber-600"></i>
                                <span>Nama Kategori Kustom <span class="text-rose-500">*</span></span>
                            </label>
                            <button type="button" 
                                    @click="selectedCategory = 'sembako'"
                                    class="text-[11px] font-bold text-gray-500 hover:text-gray-800 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                <span>Batal & Gunakan Pilihan</span>
                            </button>
                        </div>
                        <input type="text" 
                               name="custom_category" 
                               x-ref="customCategoryInput"
                               value="{{ old('custom_category', $initialCustomCategory) }}" 
                               placeholder="Contoh: Buah-buahan Lokal, Olahan Tradisional..." 
                               class="w-full px-4 py-3 rounded-2xl border border-amber-300 bg-white text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Harga & Satuan -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-amber-50 text-amber-600">
                        <i data-lucide="coins" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Penetapan Harga & Satuan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Harga Saat Ini -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Harga Saat Ini (Rp) <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] font-mono font-bold text-emerald-700" x-text="formatRupiah(price)"></span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-xs">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   x-model="price"
                                   required 
                                   min="0" 
                                   step="100"
                                   placeholder="65000" 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm font-mono font-bold focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none transition-all shadow-2xs">
                        </div>
                    </div>

                    <!-- Satuan Berat / Ukuran -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Satuan Takaran <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-gray-400 font-normal">Misal: kg, liter</span>
                        </label>
                        <input type="text" 
                               name="unit" 
                               value="{{ old('unit', $price->unit) }}"
                               required 
                               placeholder="kg / liter / ikat / piring" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Harga Sebelumnya (Opsional) -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Harga Periode Sebelumnya (Rp)</span>
                            <span class="text-[11px] text-gray-400 font-normal">Opsional</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-xs">Rp</span>
                            <input type="number" 
                                   name="previous_price" 
                                   x-model="previousPrice"
                                   min="0" 
                                   step="100"
                                   placeholder="Contoh: 60000" 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm font-mono font-medium focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none transition-all shadow-2xs">
                        </div>
                    </div>
                </div>

                <!-- Live Dynamic Price Trend Preview Card -->
                <div x-show="priceDifference !== null" x-cloak class="p-4 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl" :class="{ 'bg-rose-100 text-rose-700': priceDifference > 0, 'bg-teal-100 text-teal-700': priceDifference < 0, 'bg-blue-100 text-blue-700': priceDifference === 0 }">
                            <i data-lucide="trending-up" x-show="priceDifference > 0" class="w-5 h-5"></i>
                            <i data-lucide="trending-down" x-show="priceDifference < 0" class="w-5 h-5"></i>
                            <i data-lucide="minus" x-show="priceDifference === 0" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-900 block">
                                Estimasi Pergerakan: 
                                <span x-show="priceDifference > 0" class="text-rose-600 font-black">NAIK Rp <span x-text="Number(Math.abs(priceDifference)).toLocaleString('id-ID')"></span> (<span x-text="percentageDiff"></span>%)</span>
                                <span x-show="priceDifference < 0" class="text-teal-600 font-black">TURUN Rp <span x-text="Number(Math.abs(priceDifference)).toLocaleString('id-ID')"></span> (<span x-text="percentageDiff"></span>%)</span>
                                <span x-show="priceDifference === 0" class="text-blue-600 font-black">STABIL (Harga Tetap)</span>
                            </span>
                            <span class="text-[11px] text-gray-500">Perbandingan antara harga saat ini dengan harga periode sebelumnya.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Waktu Pencatatan & Catatan Lapangan -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Waktu & Catatan Lapangan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Tanggal Pencatatan -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Tanggal Pencatatan Data <span class="text-rose-500">*</span></label>
                        <input type="date" 
                               name="recorded_date" 
                               value="{{ old('recorded_date', $price->recorded_date ? $price->recorded_date->format('Y-m-d') : date('Y-m-d')) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Catatan Kondisi Stok -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Catatan Pasokan / Kondisi Lapangan (Opsional)</span>
                            <span class="text-[11px] text-gray-400 font-normal">Kondisi panen/distribusi</span>
                        </label>
                        <input type="text" 
                               name="notes" 
                               value="{{ old('notes', $price->notes) }}" 
                               placeholder="Contoh: Stok melimpah hasil panen lokal Loa Kulu, pasokan distributor lancar." 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-gray-100">
                <div class="text-xs text-gray-400 font-medium">
                    Dicatat oleh: <span class="font-bold text-gray-700">{{ $price->creator->name ?? 'Admin Redaksi' }}</span> pada {{ $price->created_at->translatedFormat('d F Y, H:i') }}
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.smart-city.prices.index') }}" 
                       class="w-full sm:w-auto h-11 px-6 rounded-2xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                        Batal & Kembali
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-7 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-2xl shadow-lg shadow-brand-gold/25 transition-all inline-flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Perubahan Harga</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
