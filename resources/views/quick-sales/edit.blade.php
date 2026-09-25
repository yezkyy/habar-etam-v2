@extends('layouts.public')

@section('title', 'Edit Iklan: ' . $item->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <div class="mb-6">
        <a href="{{ route('quick-sales.show', $item->slug) }}" class="text-xs font-semibold text-gray-500 hover:text-brand-black flex items-center space-x-1 mb-2">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Detail Barang</span>
        </a>
        <h1 class="text-2xl font-black text-brand-black">Edit Listing Jual Cepat</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 sm:p-8 shadow-subtle">
        <form method="POST" action="{{ route('quick-sales.update', $item->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="form-label">Judul Barang</label>
                <input type="text" id="title" name="title" value="{{ old('title', $item->title) }}" required class="form-input">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="form-label">Kategori Barang</label>
                    <select id="category" name="category" required class="form-input">
                        <option value="Gadget & HP" {{ old('category', $item->category) == 'Gadget & HP' ? 'selected' : '' }}>Gadget & HP</option>
                        <option value="Komputer & Laptop" {{ old('category', $item->category) == 'Komputer & Laptop' ? 'selected' : '' }}>Komputer & Laptop</option>
                        <option value="Kendaraan & Motor" {{ old('category', $item->category) == 'Kendaraan & Motor' ? 'selected' : '' }}>Kendaraan & Motor</option>
                        <option value="Elektronik Rumah" {{ old('category', $item->category) == 'Elektronik Rumah' ? 'selected' : '' }}>Elektronik Rumah</option>
                        <option value="Perabot Rumah" {{ old('category', $item->category) == 'Perabot Rumah' ? 'selected' : '' }}>Perabot Rumah</option>
                        <option value="Hobi & Koleksi" {{ old('category', $item->category) == 'Hobi & Koleksi' ? 'selected' : '' }}>Hobi & Koleksi</option>
                        <option value="Lainnya" {{ old('category', $item->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="condition" class="form-label">Kondisi</label>
                    <select id="condition" name="condition" required class="form-input">
                        <option value="Bekas - Seperti Baru" {{ old('condition', $item->condition) == 'Bekas - Seperti Baru' ? 'selected' : '' }}>Bekas - Seperti Baru</option>
                        <option value="Baru" {{ old('condition', $item->condition) == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Bekas - Normal/Bagus" {{ old('condition', $item->condition) == 'Bekas - Normal/Bagus' ? 'selected' : '' }}>Bekas - Normal/Bagus</option>
                        <option value="Bekas - Apa Adanya" {{ old('condition', $item->condition) == 'Bekas - Apa Adanya' ? 'selected' : '' }}>Bekas - Apa Adanya</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="form-label">Harga (Rupiah)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $item->price) }}" required class="form-input">
                </div>

                <div>
                    <label for="location_name" class="form-label">Lokasi COD / Wilayah</label>
                    <input type="text" id="location_name" name="location_name" value="{{ old('location_name', $item->location_name) }}" required class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="contact_phone" class="form-label">Nomor Telepon</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $item->contact_phone) }}" required class="form-input">
                </div>

                <div>
                    <label for="contact_whatsapp" class="form-label">Nomor WhatsApp</label>
                    <input type="text" id="contact_whatsapp" name="contact_whatsapp" value="{{ old('contact_whatsapp', $item->contact_whatsapp) }}" required class="form-input">
                </div>
            </div>

            <div>
                <label for="description" class="form-label">Deskripsi Lengkap</label>
                <textarea id="description" name="description" rows="5" required class="form-input">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label for="photos" class="form-label">Tambah Foto Baru (Opsional)</label>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="form-input text-xs text-gray-500">
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('quick-sales.show', $item->slug) }}" class="btn-outline text-xs py-2.5 px-4">Batal</a>
                <button type="submit" class="btn-gold text-xs py-2.5 px-6 font-bold shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
