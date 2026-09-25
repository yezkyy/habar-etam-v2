@extends('layouts.public')

@section('title', 'Daftarkan Event / Kegiatan Baru — Habar Etam')
@section('meta_description', 'Publikasikan agenda kegiatan komunitas, pentas musik, festival adat, atau acara olahraga Anda kepada warga Kutai Kartanegara.')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <div class="mb-6">
        <a href="{{ route('events.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-black inline-flex items-center gap-1.5 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Kalender Event</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200/80 text-indigo-700 text-[11px] font-extrabold uppercase tracking-wider mb-2">
            <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
            <span>Publikasi Agenda Warga Kukar</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Daftarkan Agenda Event Baru</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1 leading-relaxed">
            Publikasikan pesta adat, konser musik, agenda komunitas, atau pameran Anda secara gratis untuk menjangkau masyarakat Kutai Kartanegara.
        </p>
    </div>

    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring">
        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Nama Kegiatan -->
            <div>
                <label for="title" class="form-label text-xs text-gray-700 font-bold flex items-center justify-between">
                    <span>Nama Kegiatan / Event <span class="text-rose-500">*</span></span>
                    <span class="text-[11px] font-normal text-gray-400">Maks. 200 karakter</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="form-input text-xs sm:text-sm" placeholder="Contoh: Festival Erau Adat Kutai & Pelas Benua 2026">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kategori -->
                <div>
                    <label for="category" class="form-label text-xs text-gray-700 font-bold">Kategori Acara <span class="text-rose-500">*</span></label>
                    <select id="category" name="category" required class="form-input text-xs sm:text-sm">
                        <option value="Budaya & Adat" {{ old('category') == 'Budaya & Adat' ? 'selected' : '' }}>Budaya & Adat</option>
                        <option value="Musik & Komunitas" {{ old('category') == 'Musik & Komunitas' ? 'selected' : '' }}>Musik & Komunitas</option>
                        <option value="Olahraga & Hobi" {{ old('category') == 'Olahraga & Hobi' ? 'selected' : '' }}>Olahraga & Hobi</option>
                        <option value="Edukasi & Seminar" {{ old('category') == 'Edukasi & Seminar' ? 'selected' : '' }}>Edukasi & Seminar</option>
                    </select>
                    @error('category') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <!-- Penyelenggara -->
                <div>
                    <label for="organizer" class="form-label text-xs text-gray-700 font-bold">Penyelenggara / Panitia <span class="text-rose-500">*</span></label>
                    <input type="text" id="organizer" name="organizer" value="{{ old('organizer') }}" required class="form-input text-xs sm:text-sm" placeholder="Contoh: Sanggar Seni Seluang Mas Kukar">
                    @error('organizer') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tanggal & Waktu -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="form-label text-xs text-gray-700 font-bold">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="form-input text-xs sm:text-sm">
                    @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="end_date" class="form-label text-xs text-gray-700 font-bold">Tanggal Selesai (Opsional)</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-input text-xs sm:text-sm">
                    @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="start_time" class="form-label text-xs text-gray-700 font-bold">Waktu Pelaksanaan</label>
                    <input type="text" id="start_time" name="start_time" value="{{ old('start_time') }}" class="form-input text-xs sm:text-sm" placeholder="08.30 WITA - Selesai">
                    @error('start_time') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Venue & Kontak -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location_name" class="form-label text-xs text-gray-700 font-bold">Nama Tempat / Venue <span class="text-rose-500">*</span></label>
                    <input type="text" id="location_name" name="location_name" value="{{ old('location_name') }}" required class="form-input text-xs sm:text-sm" placeholder="Contoh: Halaman Museum Mulawarman Tenggarong">
                    @error('location_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_phone" class="form-label text-xs text-gray-700 font-bold">WhatsApp Narahubung</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" class="form-input text-xs sm:text-sm" placeholder="08123456789">
                    @error('contact_phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Alamat Lokasi -->
            <div>
                <label for="location_address" class="form-label text-xs text-gray-700 font-bold">Alamat / Patokan Lokasi</label>
                <input type="text" id="location_address" name="location_address" value="{{ old('location_address') }}" class="form-input text-xs sm:text-sm" placeholder="Jl. Diponegoro No. 1, Panji, Tenggarong">
                @error('location_address') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Poster Gambar Banner -->
            <div>
                <label for="poster_image" class="form-label text-xs text-gray-700 font-bold flex items-center justify-between">
                    <span>Poster Acara / Banner (Opsional)</span>
                    <span class="text-[11px] text-gray-400 font-normal">PNG, JPG, WEBP maks. 5MB</span>
                </label>
                <div class="mt-1 flex items-center justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-2xl hover:border-indigo-400 transition-colors bg-gray-50/50">
                    <div class="space-y-1 text-center">
                        <i data-lucide="image-plus" class="mx-auto h-10 w-10 text-gray-400"></i>
                        <div class="flex text-xs text-gray-600 justify-center">
                            <label for="poster_image" class="relative cursor-pointer rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                <span>Unggah poster acara</span>
                                <input id="poster_image" name="poster_image" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">atau seret file ke sini</p>
                        </div>
                        <p class="text-[11px] text-gray-400">Format vertikal / horizontal resolusi jernih</p>
                    </div>
                </div>
                @error('poster_image') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi Lengkap -->
            <div>
                <label for="description" class="form-label text-xs text-gray-700 font-bold">Deskripsi Lengkap Acara <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="form-input text-xs sm:text-sm" placeholder="Jelaskan rangkaian acara, bintang tamu (guest star), ketentuan tiket (gratis / berbayar), dan informasi penting lainnya bagi pengunjung...">{{ old('description') }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('events.index') }}" class="btn-outline text-xs py-2.5 px-5 rounded-xl font-bold">
                    Batal
                </a>
                <button type="submit" class="btn-shimmer-effect px-6 py-2.5 rounded-xl bg-brand-gold hover:bg-brand-gold-dark text-black text-xs font-black shadow-md transition-transform hover:scale-105 active:scale-95">
                    Kirim Agenda Acara
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
