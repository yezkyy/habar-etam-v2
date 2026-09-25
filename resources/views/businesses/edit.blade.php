@extends('layouts.public')

@section('title', 'Edit Usaha: ' . $business->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Header & Navigation -->
    <div class="mb-6 reveal-blur-spring">
        <a href="{{ route('businesses.show', $business->slug) }}" class="text-xs font-bold text-gray-500 hover:text-brand-black flex items-center gap-1 mb-2 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Detail Usaha</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold uppercase tracking-wider mb-2">
            <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i>
            <span>Edit Data Profil Usaha</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Edit Profil Usaha</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi kontak, alamat, jam buka, atau layanan usaha Anda.</p>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring relative">
        <form method="POST" action="{{ route('businesses.update', $business->id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Business Name -->
            <div>
                <label for="name" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="store" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Nama Usaha / Brand / Penyedia Jasa <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $business->name) }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs">
                @error('name') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Photo Upload Field -->
            <div>
                <label for="photo" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="image" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Ganti Foto Produk / Workshop (Opsional)</span>
                </label>
                @if($business->photo)
                    <div class="mb-2 flex items-center gap-3 p-2 bg-gray-50 rounded-xl border border-gray-200">
                        <img src="{{ $business->photo_url }}" alt="{{ $business->name }}" class="w-16 h-12 rounded-lg object-cover">
                        <span class="text-xs text-gray-500">Foto profil usaha saat ini</span>
                    </div>
                @endif
                <input type="file" 
                       id="photo" 
                       name="photo" 
                       accept="image/*" 
                       class="form-input rounded-2xl py-2.5 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto usaha.</p>
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
                            <option value="Kuliner & Olahan" {{ old('category', $business->category) == 'Kuliner & Olahan' ? 'selected' : '' }}>Kuliner & Olahan</option>
                            <option value="Jasa Kreatif & Percetakan" {{ old('category', $business->category) == 'Jasa Kreatif & Percetakan' ? 'selected' : '' }}>Jasa Kreatif & Percetakan</option>
                            <option value="Jasa Teknik & Fabrikasi" {{ old('category', $business->category) == 'Jasa Teknik & Fabrikasi' ? 'selected' : '' }}>Jasa Teknik & Fabrikasi</option>
                            <option value="Kerajinan & Kriya Khas" {{ old('category', $business->category) == 'Kerajinan & Kriya Khas' ? 'selected' : '' }}>Kerajinan & Kriya Khas</option>
                            <option value="Fashion & Pakaian" {{ old('category', $business->category) == 'Fashion & Pakaian' ? 'selected' : '' }}>Fashion & Pakaian</option>
                            <option value="Toko & Retail" {{ old('category', $business->category) == 'Toko & Retail' ? 'selected' : '' }}>Toko & Retail</option>
                            <option value="Jasa Profesional" {{ old('category', $business->category) == 'Jasa Profesional' ? 'selected' : '' }}>Jasa Profesional</option>
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
                           value="{{ old('location_district', $business->location_district) }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs">
                    @error('location_district') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="map" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Alamat Lengkap Usaha <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="address" 
                       name="address" 
                       value="{{ old('address', $business->address) }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs">
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
                       value="{{ old('phone_whatsapp', $business->phone_whatsapp) }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs">
                    @error('phone_whatsapp') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="instagram" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="instagram" class="w-3.5 h-3.5 text-pink-600"></i>
                        <span>Akun Instagram</span>
                    </label>
                    <input type="text" 
                       id="instagram" 
                       name="instagram" 
                       value="{{ old('instagram', $business->instagram) }}" 
                       class="form-input rounded-2xl py-3 text-xs">
                </div>
            </div>

            <!-- Operating Hours -->
            <div>
                <label for="operating_hours" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Jam Operasional</span>
                </label>
                <input type="text" 
                       id="operating_hours" 
                       name="operating_hours" 
                       value="{{ old('operating_hours', $business->operating_hours) }}" 
                       class="form-input rounded-2xl py-3 text-xs">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>Deskripsi Usaha & Layanan <span class="text-rose-500">*</span></span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          class="form-input rounded-2xl p-3.5 text-xs leading-relaxed">{{ old('description', $business->description) }}</textarea>
                @error('description') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('businesses.show', $business->slug) }}" class="btn-outline text-xs py-3 px-5 rounded-2xl font-bold">Batal</a>
                <button type="submit" class="btn-gold text-xs py-3 px-7 rounded-2xl font-black shadow-gold-glow flex items-center gap-2 hover:scale-[1.02] transition-transform">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
