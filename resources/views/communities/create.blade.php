@extends('layouts.public')

@section('title', 'Daftarkan Komunitas Baru — Habar Etam')
@section('meta_description', 'Ajak warga Tenggarong & Kukar bergabung dan berkolaborasi dalam komunitas atau klub hobi Anda.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <div class="mb-6">
        <a href="{{ route('communities.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-black inline-flex items-center gap-1.5 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Direktori Komunitas</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-200/80 text-teal-700 text-[11px] font-extrabold uppercase tracking-wider mb-2">
            <i data-lucide="users-2" class="w-3.5 h-3.5"></i>
            <span>Jejaring Komunitas Warga</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Daftarkan Komunitas Baru</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1 leading-relaxed">
            Ajak warga Tenggarong & Kutai Kartanegara bergabung, berkolaborasi, dan berpartisipasi dalam komunitas Anda secara gratis.
        </p>
    </div>

    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring">
        <form method="POST" action="{{ route('communities.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Nama Komunitas -->
            <div>
                <label for="name" class="form-label text-xs text-gray-700 font-bold flex items-center justify-between">
                    <span>Nama Komunitas / Klub <span class="text-rose-500">*</span></span>
                    <span class="text-[11px] font-normal text-gray-400">Maks. 200 karakter</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input text-xs sm:text-sm" placeholder="Contoh: Mahakam Lens (Komunitas Fotografi Tenggarong)">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Bidang Minat -->
                <div>
                    <label for="interest_category" class="form-label text-xs text-gray-700 font-bold">Bidang Minat <span class="text-rose-500">*</span></label>
                    <select id="interest_category" name="interest_category" required class="form-input text-xs sm:text-sm">
                        <option value="Seni & Budaya" {{ old('interest_category') == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                        <option value="Hobi & Kreatif" {{ old('interest_category') == 'Hobi & Kreatif' ? 'selected' : '' }}>Hobi & Kreatif</option>
                        <option value="Olahraga" {{ old('interest_category') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Sosial Kemanusiaan" {{ old('interest_category') == 'Sosial Kemanusiaan' ? 'selected' : '' }}>Sosial Kemanusiaan</option>
                    </select>
                    @error('interest_category') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <!-- Markas Lokasi -->
                <div>
                    <label for="base_location" class="form-label text-xs text-gray-700 font-bold">Markas / Tempat Kumpul Rutin <span class="text-rose-500">*</span></label>
                    <input type="text" id="base_location" name="base_location" value="{{ old('base_location', 'Tenggarong') }}" required class="form-input text-xs sm:text-sm" placeholder="Contoh: Creative Hub / Tepian Mahakam">
                    @error('base_location') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- PIC / Kontak -->
                <div>
                    <label for="contact_person" class="form-label text-xs text-gray-700 font-bold">Nama Narahubung / PIC</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" class="form-input text-xs sm:text-sm" placeholder="Contoh: Budi Santoso (Ketua)">
                    @error('contact_person') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="contact_phone" class="form-label text-xs text-gray-700 font-bold">WhatsApp Narahubung <span class="text-rose-500">*</span></label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required class="form-input text-xs sm:text-sm" placeholder="08123456789">
                    @error('contact_phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Jadwal Kegiatan -->
                <div>
                    <label for="activity_schedule" class="form-label text-xs text-gray-700 font-bold">Jadwal Kegiatan Rutin</label>
                    <input type="text" id="activity_schedule" name="activity_schedule" value="{{ old('activity_schedule') }}" class="form-input text-xs sm:text-sm" placeholder="Contoh: Setiap Minggu Sore (16.00 WITA)">
                    @error('activity_schedule') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <!-- Media Sosial -->
                <div>
                    <label for="social_media" class="form-label text-xs text-gray-700 font-bold">Media Sosial / Link</label>
                    <input type="text" id="social_media" name="social_media" value="{{ old('social_media') }}" class="form-input text-xs sm:text-sm" placeholder="Instagram: @mahakam_lens">
                    @error('social_media') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Foto / Banner Komunitas -->
            <div>
                <label for="photo" class="form-label text-xs text-gray-700 font-bold flex items-center justify-between">
                    <span>Foto / Banner Komunitas (Opsional)</span>
                    <span class="text-[11px] text-gray-400 font-normal">PNG, JPG, WEBP maks. 5MB</span>
                </label>
                <div class="mt-1 flex items-center justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-2xl hover:border-teal-400 transition-colors bg-gray-50/50">
                    <div class="space-y-1 text-center">
                        <i data-lucide="image-plus" class="mx-auto h-10 w-10 text-gray-400"></i>
                        <div class="flex text-xs text-gray-600 justify-center">
                            <label for="photo" class="relative cursor-pointer rounded-md font-bold text-teal-600 hover:text-teal-500 focus-within:outline-none">
                                <span>Unggah foto kegiatan / logo</span>
                                <input id="photo" name="photo" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">atau seret file ke sini</p>
                        </div>
                        <p class="text-[11px] text-gray-400">Rasio mendatar disarankan untuk banner kartu</p>
                    </div>
                </div>
                @error('photo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="form-label text-xs text-gray-700 font-bold">Deskripsi Komunitas & Cara Bergabung <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="form-input text-xs sm:text-sm" placeholder="Ceritakan visi komunitas, agenda rutin, benefit bagi anggota, dan syarat bergabung...">{{ old('description') }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('communities.index') }}" class="btn-outline text-xs py-2.5 px-5 rounded-xl font-bold">Batal</a>
                <button type="submit" class="btn-shimmer-effect px-6 py-2.5 rounded-xl bg-brand-gold hover:bg-brand-gold-dark text-black text-xs font-black shadow-md transition-transform hover:scale-105 active:scale-95">
                    Daftarkan Komunitas
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
