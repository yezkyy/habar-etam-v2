@extends('layouts.public')

@section('title', 'Pasang Iklan Jual Cepat — Habar Etam')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <div class="mb-6">
        <a href="{{ route('quick-sales.index') }}" class="text-xs font-semibold text-gray-500 hover:text-brand-black flex items-center space-x-1 mb-2">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Jual Cepat</span>
        </a>
        <h1 class="text-2xl font-black text-brand-black">Pasang Iklan Jual Cepat</h1>
        <p class="text-xs text-gray-500 mt-1">Jual barang Anda secara cepat langsung ke warga Tenggarong & Kutai Kartanegara.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 sm:p-8 shadow-subtle">
        <form method="POST" action="{{ route('quick-sales.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="form-label">Judul Barang</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="form-input" placeholder="Contoh: iPhone 13 128GB Midnight Lengkap Box">
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="form-label">Kategori Barang</label>
                    <select id="category" name="category" required class="form-input">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Gadget & HP" {{ old('category') == 'Gadget & HP' ? 'selected' : '' }}>Gadget & HP</option>
                        <option value="Komputer & Laptop" {{ old('category') == 'Komputer & Laptop' ? 'selected' : '' }}>Komputer & Laptop</option>
                        <option value="Kendaraan & Motor" {{ old('category') == 'Kendaraan & Motor' ? 'selected' : '' }}>Kendaraan & Motor</option>
                        <option value="Elektronik Rumah" {{ old('category') == 'Elektronik Rumah' ? 'selected' : '' }}>Elektronik Rumah</option>
                        <option value="Perabot Rumah" {{ old('category') == 'Perabot Rumah' ? 'selected' : '' }}>Perabot Rumah</option>
                        <option value="Hobi & Koleksi" {{ old('category') == 'Hobi & Koleksi' ? 'selected' : '' }}>Hobi & Koleksi</option>
                        <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="condition" class="form-label">Kondisi</label>
                    <select id="condition" name="condition" required class="form-input">
                        <option value="Bekas - Seperti Baru" {{ old('condition') == 'Bekas - Seperti Baru' ? 'selected' : '' }}>Bekas - Seperti Baru</option>
                        <option value="Baru" {{ old('condition') == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Bekas - Normal/Bagus" {{ old('condition') == 'Bekas - Normal/Bagus' ? 'selected' : '' }}>Bekas - Normal/Bagus</option>
                        <option value="Bekas - Apa Adanya" {{ old('condition') == 'Bekas - Apa Adanya' ? 'selected' : '' }}>Bekas - Apa Adanya</option>
                    </select>
                    @error('condition') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="form-label">Harga (Rupiah)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" required min="1000" class="form-input" placeholder="Contoh: 1500000">
                    @error('price') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="location_name" class="form-label">Lokasi COD / Wilayah</label>
                    <input type="text" id="location_name" name="location_name" value="{{ old('location_name', 'Tenggarong') }}" required class="form-input" placeholder="Contoh: Timbau / Jl. Pesut">
                    @error('location_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="contact_phone" class="form-label">Nomor Telepon</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}" required class="form-input" placeholder="08123456789">
                    @error('contact_phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_whatsapp" class="form-label">Nomor WhatsApp (Aktif)</label>
                    <input type="text" id="contact_whatsapp" name="contact_whatsapp" value="{{ old('contact_whatsapp', auth()->user()->phone) }}" required class="form-input" placeholder="08123456789">
                    @error('contact_whatsapp') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="form-label">Deskripsi & Kelengkapan</label>
                <textarea id="description" name="description" rows="5" required class="form-input" placeholder="Jelaskan kondisi barang, kelengkapan minus, pemakaian, dan alasan dijual...">{{ old('description') }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="photos" class="form-label">Foto Barang (Maksimal 5 Foto)</label>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="form-input text-xs text-gray-500">
                <p class="text-[11px] text-gray-500 mt-1">Format: JPG, PNG, WEBP. Maksimal 5 MB per foto.</p>
                @error('photos') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('quick-sales.index') }}" class="btn-outline text-xs py-2.5 px-4">Batal</a>
                <button type="submit" class="btn-gold text-xs py-2.5 px-6 font-bold shadow-sm">
                    Kirim Listing Jual Cepat
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
