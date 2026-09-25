@extends('layouts.public')

@section('title', 'Kirim Pengaduan Warga — Lapor Etam')
@section('meta_description', 'Formulir pengaduan infrastruktur dan layanan publik resmi warga Kutai Kartanegara.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .report-tile-osm .leaflet-tile-pane {
        filter: contrast(104%) brightness(99%) saturate(88%) hue-rotate(-2deg);
    }
    #picker-map {
        min-height: 280px;
        z-index: 10;
        cursor: crosshair;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-red-700 transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-3.5 h-3.5"></i>
            <span>Beranda</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <a href="{{ route('reports.index') }}" class="hover:text-red-700 transition-colors flex items-center gap-1">
            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-red-600"></i>
            <span>Lapor Etam</span>
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400"></i>
        <span class="text-red-900 font-bold bg-red-50 px-2 py-0.5 rounded-md border border-red-200">Form Pengaduan Baru</span>
    </nav>

    <!-- Header Intro Card -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-[#181111] to-red-950 text-white p-6 sm:p-8 shadow-card border border-red-500/20 mb-8">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-red-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-900/60 border border-red-400/40 text-red-300 backdrop-blur-md">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-red-400"></i>
                    <span>Formulir Pengaduan Resmi Warga</span>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-white">Sampaikan Aspirasi & Keluhan Fasilitas Publik</h1>
            <p class="text-xs sm:text-sm text-red-100/80 mt-2 max-w-2xl leading-relaxed">
                Tuliskan kronologi dan kendala secara rinci disertai bukti foto/video dan titik peta. Laporan Anda langsung diteruskan ke tim redaksi Habar Etam dan OPD terkait di Kutai Kartanegara.
            </p>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-10 shadow-card">
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. Category Selector -->
            <div>
                <label for="category" class="block text-xs font-black uppercase tracking-wider text-slate-800 mb-2">
                    Kategori Permasalahan <span class="text-red-500">*</span>
                </label>
                <select id="category" name="category" required class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black">
                    <option value="">-- Pilih Kategori Masalah --</option>
                    <option value="Jalan & Jembatan" {{ old('category') == 'Jalan & Jembatan' ? 'selected' : '' }}>Jalan & Jembatan (Lubang, Amblas, Rambu Rusak)</option>
                    <option value="Drainase & Banjir" {{ old('category') == 'Drainase & Banjir' ? 'selected' : '' }}>Drainase & Banjir (Parit Buntu, Genangan Air)</option>
                    <option value="Lampu & Penerangan" {{ old('category') == 'Lampu & Penerangan' ? 'selected' : '' }}>Lampu & Penerangan Jalan (PJU Mati, Kabel Putus)</option>
                    <option value="Sampah & Kebersihan" {{ old('category') == 'Sampah & Kebersihan' ? 'selected' : '' }}>Sampah & Kebersihan (TPS Liar, Pengangkutan)</option>
                    <option value="Fasilitas Publik" {{ old('category') == 'Fasilitas Publik' ? 'selected' : '' }}>Fasilitas Publik & Pohon Rindang Tumbang</option>
                    <option value="Ketertiban Umum" {{ old('category') == 'Ketertiban Umum' ? 'selected' : '' }}>Ketertiban Umum & Gangguan Lingkungan</option>
                </select>
                @error('category') <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 2. Problem Title -->
            <div>
                <label for="title" class="block text-xs font-black uppercase tracking-wider text-slate-800 mb-2">
                    Judul Ringkas Pengaduan <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       required 
                       class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black" 
                       placeholder="Contoh: Aspal Jalan Amblas dan Lubang Dalam di Dekat Simpang Tiga Pesut">
                @error('title') <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 3. District & Address Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location_district" class="block text-xs font-black uppercase tracking-wider text-slate-800 mb-2">
                        Kecamatan <span class="text-red-500">*</span>
                    </label>
                    <select id="location_district" name="location_district" required class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black">
                        <option value="Tenggarong" {{ old('location_district') == 'Tenggarong' ? 'selected' : '' }}>Tenggarong</option>
                        <option value="Tenggarong Seberang" {{ old('location_district') == 'Tenggarong Seberang' ? 'selected' : '' }}>Tenggarong Seberang</option>
                        <option value="Loa Kulu" {{ old('location_district') == 'Loa Kulu' ? 'selected' : '' }}>Loa Kulu</option>
                        <option value="Loa Janan" {{ old('location_district') == 'Loa Janan' ? 'selected' : '' }}>Loa Janan</option>
                        <option value="Samboja" {{ old('location_district') == 'Samboja' ? 'selected' : '' }}>Samboja</option>
                        <option value="Muara Jawa" {{ old('location_district') == 'Muara Jawa' ? 'selected' : '' }}>Muara Jawa</option>
                        <option value="Sangasanga" {{ old('location_district') == 'Sangasanga' ? 'selected' : '' }}>Sangasanga</option>
                        <option value="Anggana" {{ old('location_district') == 'Anggana' ? 'selected' : '' }}>Anggana</option>
                        <option value="Muara Badak" {{ old('location_district') == 'Muara Badak' ? 'selected' : '' }}>Muara Badak</option>
                        <option value="Marang Kayu" {{ old('location_district') == 'Marang Kayu' ? 'selected' : '' }}>Marang Kayu</option>
                        <option value="Sebulu" {{ old('location_district') == 'Sebulu' ? 'selected' : '' }}>Sebulu</option>
                        <option value="Muara Kaman" {{ old('location_district') == 'Muara Kaman' ? 'selected' : '' }}>Muara Kaman</option>
                        <option value="Kota Bangun" {{ old('location_district') == 'Kota Bangun' ? 'selected' : '' }}>Kota Bangun</option>
                        <option value="Kenohan" {{ old('location_district') == 'Kenohan' ? 'selected' : '' }}>Kenohan</option>
                        <option value="Kembang Janggut" {{ old('location_district') == 'Kembang Janggut' ? 'selected' : '' }}>Kembang Janggut</option>
                        <option value="Tabang" {{ old('location_district') == 'Tabang' ? 'selected' : '' }}>Tabang</option>
                        <option value="Muara Wis" {{ old('location_district') == 'Muara Wis' ? 'selected' : '' }}>Muara Wis</option>
                        <option value="Muara Muntai" {{ old('location_district') == 'Muara Muntai' ? 'selected' : '' }}>Muara Muntai</option>
                    </select>
                </div>

                <div>
                    <label for="address" class="block text-xs font-black uppercase tracking-wider text-slate-800 mb-2">
                        Alamat / Patokan Jelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address') }}" 
                           required 
                           class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black" 
                           placeholder="Contoh: Jl. KH Akhmad Muksin RT 05 dekat dermaga">
                    @error('address') <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- 4. Interactive GIS Point Picker -->
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-800">
                        Tentukan Titik Peta (Klik atau Geser Pin)
                    </label>
                    <span class="text-[11px] text-gray-400">Otomatis mencatat koordinat GPS</span>
                </div>
                
                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner">
                    <div id="picker-map" class="h-64 w-full report-tile-osm z-10"></div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                    <div>
                        <span class="text-[11px] text-gray-500 font-semibold">Latitude GPS:</span>
                        <input type="text" id="latitude" name="latitude" value="{{ old('latitude', '-0.4300000') }}" readonly class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold mt-1 text-slate-700">
                    </div>
                    <div>
                        <span class="text-[11px] text-gray-500 font-semibold">Longitude GPS:</span>
                        <input type="text" id="longitude" name="longitude" value="{{ old('longitude', '116.9850000') }}" readonly class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold mt-1 text-slate-700">
                    </div>
                </div>
            </div>

            <!-- 5. Problem Description -->
            <div>
                <label for="description" class="block text-xs font-black uppercase tracking-wider text-slate-800 mb-2">
                    Kronologi & Uraian Masalah (Min. 30 karakter) <span class="text-red-500">*</span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="5" 
                          required 
                          class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs leading-relaxed focus:bg-white focus:ring-2 focus:ring-red-500 text-brand-black" 
                          placeholder="Ceritakan secara mendalam kondisi masalah di lapangan, sejak kapan terjadi, dampak bagi mobilitas warga sekitar, serta harapan penanganan...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- 6. File Attachments -->
            <div>
                <label for="evidence_files" class="block text-xs font-black uppercase tracking-wider text-slate-800 mb-2">
                    Lampiran Bukti Foto / Video (Maksimal 5 File)
                </label>
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-gray-50/70 hover:bg-white hover:border-red-400 transition-all text-center">
                    <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                    <input type="file" 
                           id="evidence_files" 
                           name="evidence_files[]" 
                           multiple 
                           accept="image/*,video/*" 
                           class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-2">Format yang didukung: JPG, PNG, WEBP, MP4. Maksimal 20 MB per file.</p>
                </div>
                @error('evidence_files') <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Privacy Guarantee Alert -->
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 text-xs text-amber-950 flex items-start gap-3">
                <i data-lucide="shield-check" class="w-5 h-5 text-amber-700 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="font-black text-amber-900">Jaminan Privasi & Perlindungan Data:</strong> 
                    Nomor Induk Kependudukan (NIK) dan kontak telepon Anda dijamin kerahasiaannya. Laporan yang tampil ke publik hanya memuat nama inisial pelapor demi kenyamanan dan keamanan warga.
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('reports.index') }}" class="px-5 py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-700 bg-white hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-red-800 hover:bg-red-900 text-white font-extrabold text-xs shadow-lg transition-transform hover:scale-105 flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Pengaduan Warga</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof L !== 'undefined') {
            const initialLat = parseFloat(document.getElementById('latitude').value) || -0.4300000;
            const initialLng = parseFloat(document.getElementById('longitude').value) || 116.9850000;

            const map = L.map('picker-map', {
                center: [initialLat, initialLng],
                zoom: 14,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors • Habar Etam'
            }).addTo(map);

            const customMarkerHtml = `
                <div class="relative w-8 h-8 flex items-center justify-center">
                    <div style="width:28px; height:28px; background:#b91c1c; border-radius:10px; border:2.5px solid #ffffff; box-shadow:0 4px 8px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:#ffffff;">
                        <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                </div>
            `;

            const customIcon = L.divIcon({
                html: customMarkerHtml,
                className: 'picker-marker',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            let marker = L.marker([initialLat, initialLng], { draggable: true, icon: customIcon }).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('latitude').value = lat.toFixed(7);
                document.getElementById('longitude').value = lng.toFixed(7);
            }

            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });
        }
    });
</script>
@endpush
