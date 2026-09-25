@extends('layouts.public')

@section('title', 'Rekomendasikan Kuliner — Habar Etam')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Header & Navigation -->
    <div class="mb-6 reveal-blur-spring">
        <a href="{{ route('culinary.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-black flex items-center gap-1 mb-2 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Kuliner</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold uppercase tracking-wider mb-2">
            <i data-lucide="utensils" class="w-3.5 h-3.5 text-amber-600"></i>
            <span>Rekomendasi Kuliner Warga</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Rekomendasikan Tempat Kuliner</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Bagikan tempat makan khas Kutai, warung legendaris, kedai kopi, atau cafe asyik di Tenggarong & Kukar.</p>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring relative">
        <form method="POST" action="{{ route('culinary.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="utensils" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Nama Tempat Makan / Cafe / Warung <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Rumah Makan Tepian Pandan (Gence Ruan)">
                @error('name') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Photo Upload Field -->
            <div>
                <label for="photo" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="image" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Foto Tempat Makan / Makanan Khas (Opsional)</span>
                </label>
                <input type="file" 
                       id="photo" 
                       name="photo" 
                       accept="image/*" 
                       class="form-input rounded-2xl py-2.5 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5MB). Jika dikosongkan, gambar ilustrasi kuliner otomatis ditampilkan.</p>
                @error('photo') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Type & Price Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="culinary_type" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>Jenis Kuliner <span class="text-rose-500">*</span></span>
                    </label>
                    <div class="relative">
                        <select id="culinary_type" name="culinary_type" required class="form-input rounded-2xl py-3 text-xs font-semibold">
                            <option value="Kuliner Tradisional Kutai" {{ old('culinary_type') == 'Kuliner Tradisional Kutai' ? 'selected' : '' }}>Kuliner Tradisional Kutai</option>
                            <option value="Sarapan Pagi & Jajanan" {{ old('culinary_type') == 'Sarapan Pagi & Jajanan' ? 'selected' : '' }}>Sarapan Pagi & Jajanan</option>
                            <option value="Cafe & Santai Sore" {{ old('culinary_type') == 'Cafe & Santai Sore' ? 'selected' : '' }}>Cafe & Santai Sore</option>
                            <option value="Rumah Makan & Seafood" {{ old('culinary_type') == 'Rumah Makan & Seafood' ? 'selected' : '' }}>Rumah Makan & Seafood</option>
                        </select>
                    </div>
                    @error('culinary_type') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="price_range" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="wallet" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Rentang Estimasi Harga <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="price_range" 
                           name="price_range" 
                           value="{{ old('price_range', 'Rp 15.000 - Rp 45.000') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="Contoh: Rp 15.000 - Rp 50.000 / porsi">
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500"></i>
                    <span>Alamat Lengkap Lokasi <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="address" 
                       name="address" 
                       value="{{ old('address') }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Jl. Wolter Monginsidi, Kel. Timbau, Tenggarong">
                @error('address') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- District & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location_district" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="map" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span>Kecamatan <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="location_district" 
                           name="location_district" 
                           value="{{ old('location_district', 'Tenggarong') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs">
                </div>

                <div>
                    <label for="phone_whatsapp" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>WhatsApp / Telepon Pemesanan (Opsional)</span>
                    </label>
                    <input type="text" 
                           id="phone_whatsapp" 
                           name="phone_whatsapp" 
                           value="{{ old('phone_whatsapp') }}" 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="08123456789">
                </div>
            </div>

            <!-- Operating Hours -->
            <div>
                <label for="operating_hours" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Jam Operasional Buka (Opsional)</span>
                </label>
                <input type="text" 
                       id="operating_hours" 
                       name="operating_hours" 
                       value="{{ old('operating_hours') }}" 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Setiap Hari: 10.00 - 22.00 WITA">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Deskripsi, Menu Andalan & Keunikan Rasa <span class="text-rose-500">*</span></span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          class="form-input rounded-2xl p-3.5 text-xs leading-relaxed" 
                          placeholder="Ceritakan keistimewaan rasa, menu wajib coba, suasana tempat makan, kebersihan, dan kisaran porsi..."></textarea>
                @error('description') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('culinary.index') }}" class="btn-outline text-xs py-3 px-5 rounded-2xl font-bold">Batal</a>
                <button type="submit" class="btn-gold text-xs py-3 px-7 rounded-2xl font-black shadow-gold-glow flex items-center gap-2 hover:scale-[1.02] transition-transform">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Rekomendasi Kuliner</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
