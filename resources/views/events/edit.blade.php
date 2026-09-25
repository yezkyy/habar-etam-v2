@extends('layouts.public')

@section('title', 'Edit Event: ' . $event->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <div class="mb-6">
        <a href="{{ route('events.show', $event->slug) }}" class="text-xs font-bold text-gray-500 hover:text-brand-black inline-flex items-center gap-1.5 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Detail Event</span>
        </a>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Edit Agenda Acara</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui jadwal, lokasi, atau deskripsi rangkaian acara.</p>
    </div>

    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring">
        <form method="POST" action="{{ route('events.update', $event->id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="form-label text-xs text-gray-700 font-bold">Nama Kegiatan / Event <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required class="form-input text-xs sm:text-sm">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="form-label text-xs text-gray-700 font-bold">Kategori Event <span class="text-rose-500">*</span></label>
                    <select id="category" name="category" required class="form-input text-xs sm:text-sm">
                        <option value="Budaya & Adat" {{ old('category', $event->category) == 'Budaya & Adat' ? 'selected' : '' }}>Budaya & Adat</option>
                        <option value="Musik & Komunitas" {{ old('category', $event->category) == 'Musik & Komunitas' ? 'selected' : '' }}>Musik & Komunitas</option>
                        <option value="Olahraga & Hobi" {{ old('category', $event->category) == 'Olahraga & Hobi' ? 'selected' : '' }}>Olahraga & Hobi</option>
                        <option value="Edukasi & Seminar" {{ old('category', $event->category) == 'Edukasi & Seminar' ? 'selected' : '' }}>Edukasi & Seminar</option>
                    </select>
                    @error('category') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="organizer" class="form-label text-xs text-gray-700 font-bold">Penyelenggara / Panitia <span class="text-rose-500">*</span></label>
                    <input type="text" id="organizer" name="organizer" value="{{ old('organizer', $event->organizer) }}" required class="form-input text-xs sm:text-sm">
                    @error('organizer') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="form-label text-xs text-gray-700 font-bold">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required class="form-input text-xs sm:text-sm">
                    @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="end_date" class="form-label text-xs text-gray-700 font-bold">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '') }}" class="form-input text-xs sm:text-sm">
                    @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="start_time" class="form-label text-xs text-gray-700 font-bold">Waktu Pelaksanaan</label>
                    <input type="text" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}" class="form-input text-xs sm:text-sm">
                    @error('start_time') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location_name" class="form-label text-xs text-gray-700 font-bold">Nama Tempat / Venue <span class="text-rose-500">*</span></label>
                    <input type="text" id="location_name" name="location_name" value="{{ old('location_name', $event->location_name) }}" required class="form-input text-xs sm:text-sm">
                    @error('location_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_phone" class="form-label text-xs text-gray-700 font-bold">WhatsApp Narahubung</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $event->contact_phone) }}" class="form-input text-xs sm:text-sm">
                    @error('contact_phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="location_address" class="form-label text-xs text-gray-700 font-bold">Alamat / Patokan Lokasi</label>
                <input type="text" id="location_address" name="location_address" value="{{ old('location_address', $event->location_address) }}" class="form-input text-xs sm:text-sm">
                @error('location_address') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label text-xs text-gray-700 font-bold">Deskripsi Lengkap Acara <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="form-input text-xs sm:text-sm">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('events.show', $event->slug) }}" class="btn-outline text-xs py-2.5 px-5 rounded-xl font-bold">Batal</a>
                <button type="submit" class="btn-shimmer-effect px-6 py-2.5 rounded-xl bg-brand-gold hover:bg-brand-gold-dark text-black text-xs font-black shadow-md transition-transform hover:scale-105 active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
