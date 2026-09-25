@extends('layouts.public')

@section('title', 'Daftarkan Usaha / Jasa — Habar Etam')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Header & Navigation -->
    <div class="mb-6 reveal-blur-spring">
        <a href="{{ route('businesses.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-black flex items-center gap-1 mb-2 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Produk & Jasa</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-2">
            <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600"></i>
            <span>Pendaftaran Usaha & Jasa Kukar</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Daftarkan Produk & Jasa Usaha Baru</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Dukung perputaran ekonomi lokal dengan mempublikasikan profil bisnis Anda kepada ribuan warga Kukar secara gratis.</p>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring relative">
        <form method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Business Name -->
            <div>
                <label for="name" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Nama Usaha / Brand / Penyedia Jasa <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Mahakam Digital Printing / Bengkel Las Berkah">
                @error('name') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Photo Upload Field -->
            <div>
                <label for="photo" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="image" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Foto Produk / Workshop / Toko (Opsional)</span>
                </label>
                <input type="file" 
                       id="photo" 
                       name="photo" 
                       accept="image/*" 
                       class="form-input rounded-2xl py-2.5 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5MB). Jika dikosongkan, gambar ilustrasi kategori otomatis dipasang.</p>
                @error('photo') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Category & District -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="tag" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Kategori Usaha <span class="text-rose-500">*</span></span>
                    </label>
                    <div class="relative">
                        <select id="category" name="category" required class="form-input rounded-2xl py-3 text-xs font-semibold">
                            <option value="Kuliner & Olahan" {{ old('category') == 'Kuliner & Olahan' ? 'selected' : '' }}>Kuliner & Olahan</option>
                            <option value="Jasa Kreatif & Percetakan" {{ old('category') == 'Jasa Kreatif & Percetakan' ? 'selected' : '' }}>Jasa Kreatif & Percetakan</option>
                            <option value="Jasa Teknik & Fabrikasi" {{ old('category') == 'Jasa Teknik & Fabrikasi' ? 'selected' : '' }}>Jasa Teknik & Fabrikasi</option>
                            <option value="Kerajinan & Kriya Khas" {{ old('category') == 'Kerajinan & Kriya Khas' ? 'selected' : '' }}>Kerajinan & Kriya Khas</option>
                            <option value="Fashion & Pakaian" {{ old('category') == 'Fashion & Pakaian' ? 'selected' : '' }}>Fashion & Pakaian</option>
                            <option value="Toko & Retail" {{ old('category') == 'Toko & Retail' ? 'selected' : '' }}>Toko & Retail</option>
                            <option value="Jasa Profesional" {{ old('category') == 'Jasa Profesional' ? 'selected' : '' }}>Jasa Profesional</option>
                        </select>
                    </div>
                    @error('category') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="location_district" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500"></i>
                        <span>Kecamatan <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="location_district" 
                           name="location_district" 
                           value="{{ old('location_district', 'Tenggarong') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="Contoh: Tenggarong / Tenggarong Seberang / Loa Kulu">
                    @error('location_district') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="map" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Alamat Lengkap Workshop / Toko / Lokasi Usaha <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="address" 
                       name="address" 
                       value="{{ old('address') }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Jl. Pesut No. 88, Kel. Timbau, Tenggarong">
                @error('address') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Phone & Instagram -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone_whatsapp" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Nomor WhatsApp Bisnis <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="phone_whatsapp" 
                           name="phone_whatsapp" 
                           value="{{ old('phone_whatsapp', auth()->user()->phone ?? '') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="08123456789">
                    @error('phone_whatsapp') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="instagram" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="instagram" class="w-3.5 h-3.5 text-pink-600"></i>
                        <span>Akun Instagram (Opsional)</span>
                    </label>
                    <input type="text" 
                           id="instagram" 
                           name="instagram" 
                           value="{{ old('instagram') }}" 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="@nama.usaha">
                </div>
            </div>

            <!-- Operating Hours -->
            <div>
                <label for="operating_hours" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Jam Operasional (Opsional)</span>
                </label>
                <input type="text" 
                       id="operating_hours" 
                       name="operating_hours" 
                       value="{{ old('operating_hours') }}" 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Senin - Sabtu: 08.00 - 18.00 WITA">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Deskripsi Produk, Daftar Layanan & Keunggulan <span class="text-rose-500">*</span></span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          class="form-input rounded-2xl p-3.5 text-xs leading-relaxed" 
                          placeholder="Jelaskan produk unggulan, daftar jenis jasa yang dilayani, kisaran harga/tarif, keunggulan, serta portfolio singkat usaha Anda...">{{ old('description') }}</textarea>
                @error('description') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('businesses.index') }}" class="btn-outline text-xs py-3 px-5 rounded-2xl font-bold">Batal</a>
                <button type="submit" class="btn-gold text-xs py-3 px-7 rounded-2xl font-black shadow-gold-glow flex items-center gap-2 hover:scale-[1.02] transition-transform">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Daftarkan Usaha Sekarang</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
