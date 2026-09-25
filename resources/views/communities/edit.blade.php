@extends('layouts.public')

@section('title', 'Edit Komunitas: ' . $community->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <div class="mb-6">
        <a href="{{ route('communities.show', $community->slug) }}" class="text-xs font-bold text-gray-500 hover:text-brand-black inline-flex items-center gap-1.5 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Detail Komunitas</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Edit Data Komunitas</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi pengurus, jadwal kegiatan, atau deskripsi komunitas.</p>
    </div>

    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring">
        <form method="POST" action="{{ route('communities.update', $community->id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="form-label text-xs text-gray-700 font-bold">Nama Komunitas / Klub <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $community->name) }}" required class="form-input text-xs sm:text-sm">
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="interest_category" class="form-label text-xs text-gray-700 font-bold">Bidang Minat <span class="text-rose-500">*</span></label>
                    <select id="interest_category" name="interest_category" required class="form-input text-xs sm:text-sm">
                        <option value="Seni & Budaya" {{ old('interest_category', $community->interest_category) == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                        <option value="Hobi & Kreatif" {{ old('interest_category', $community->interest_category) == 'Hobi & Kreatif' ? 'selected' : '' }}>Hobi & Kreatif</option>
                        <option value="Olahraga" {{ old('interest_category', $community->interest_category) == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                        <option value="Sosial Kemanusiaan" {{ old('interest_category', $community->interest_category) == 'Sosial Kemanusiaan' ? 'selected' : '' }}>Sosial Kemanusiaan</option>
                    </select>
                    @error('interest_category') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="base_location" class="form-label text-xs text-gray-700 font-bold">Markas / Tempat Kumpul Rutin <span class="text-rose-500">*</span></label>
                    <input type="text" id="base_location" name="base_location" value="{{ old('base_location', $community->base_location) }}" required class="form-input text-xs sm:text-sm">
                    @error('base_location') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="contact_person" class="form-label text-xs text-gray-700 font-bold">Nama Narahubung / PIC</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $community->contact_person) }}" class="form-input text-xs sm:text-sm">
                    @error('contact_person') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_phone" class="form-label text-xs text-gray-700 font-bold">WhatsApp Narahubung <span class="text-rose-500">*</span></label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $community->contact_phone) }}" required class="form-input text-xs sm:text-sm">
                    @error('contact_phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="activity_schedule" class="form-label text-xs text-gray-700 font-bold">Jadwal Kegiatan Rutin</label>
                    <input type="text" id="activity_schedule" name="activity_schedule" value="{{ old('activity_schedule', $community->activity_schedule) }}" class="form-input text-xs sm:text-sm">
                    @error('activity_schedule') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="social_media" class="form-label text-xs text-gray-700 font-bold">Media Sosial / Link</label>
                    <input type="text" id="social_media" name="social_media" value="{{ old('social_media', $community->social_media) }}" class="form-input text-xs sm:text-sm">
                    @error('social_media') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="photo" class="form-label text-xs text-gray-700 font-bold">Foto / Logo Komunitas (Opsional)</label>
                @if($community->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $community->photo) }}" alt="{{ $community->name }}" class="w-32 h-20 object-cover rounded-xl border border-gray-200">
                    </div>
                @endif
                <input type="file" id="photo" name="photo" accept="image/*" class="form-input text-xs sm:text-sm">
                @error('photo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label text-xs text-gray-700 font-bold">Deskripsi Komunitas & Cara Bergabung <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="form-input text-xs sm:text-sm">{{ old('description', $community->description) }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('communities.show', $community->slug) }}" class="btn-outline text-xs py-2.5 px-5 rounded-xl font-bold">Batal</a>
                <button type="submit" class="btn-shimmer-effect px-6 py-2.5 rounded-xl bg-brand-gold hover:bg-brand-gold-dark text-black text-xs font-black shadow-md transition-transform hover:scale-105 active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
