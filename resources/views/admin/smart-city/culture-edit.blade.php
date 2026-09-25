@extends('layouts.admin')

@section('title', 'Edit Destinasi: ' . $destination->title . ' — Smart City Kukar')
@section('page_title', 'Smart City: Edit Destinasi Budaya & Wisata')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-amber-950 to-slate-950 text-white p-6 sm:p-7 rounded-3xl shadow-lg relative overflow-hidden border border-amber-500/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-64 -top-12 w-48 h-48 bg-yellow-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-amber-200/80">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-300 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-amber-200/60">Smart City</span>
                <span>/</span>
                <a href="{{ route('admin.smart-city.culture.index') }}" class="hover:text-amber-300 transition-colors">Budaya & Wisata</a>
                <span>/</span>
                <span class="text-amber-400 font-semibold">Edit Destinasi</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-300">
                        <i data-lucide="edit-3" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span class="truncate max-w-xl">Edit: {{ $destination->title }}</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Perbarui deskripsi, koordinat lokasi, status tayang, jadwal operasional, dan galeri foto cagar budaya ini.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2.5 self-start lg:self-center">
            @if($destination->status === 'published')
                <a href="{{ route('smart-city.culture.show', $destination->slug) }}" 
                   target="_blank"
                   class="h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer">
                    <i data-lucide="external-link" class="w-4 h-4 text-amber-300"></i>
                    <span>Pratinjau Publik</span>
                </a>
            @endif
            <a href="{{ route('admin.smart-city.culture.index') }}" 
               class="h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Error Summary Alerts -->
    @if($errors->any())
        <div class="p-5 bg-rose-50 border border-rose-200 rounded-3xl text-xs text-rose-800 space-y-2 shadow-2xs">
            <div class="flex items-center gap-2.5 font-bold text-rose-900 text-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                <span>Mohon perbaiki isian form berikut:</span>
            </div>
            <ul class="list-disc pl-7 space-y-1 text-xs">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Edit Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.smart-city.culture.update', $destination->id) }}" 
              method="POST" 
              enctype="multipart/form-data"
              x-data="{
                  category: '{{ old('category', in_array($destination->category, ['kesultanan', 'museum_sejarah', 'wisata_alam', 'festival_adat', 'kuliner_tradisi']) ? $destination->category : 'lainnya') }}',
                  customCategory: '{{ old('custom_category', !in_array($destination->category, ['kesultanan', 'museum_sejarah', 'wisata_alam', 'festival_adat', 'kuliner_tradisi']) ? $destination->category : '') }}',
                  status: '{{ old('status', $destination->status) }}',
                  locationDistrict: '{{ old('location_district', in_array($destination->location_district, $districts) ? $destination->location_district : 'lainnya') }}',
                  customDistrict: '{{ old('custom_district', !in_array($destination->location_district, $districts) ? $destination->location_district : '') }}',
                  hasExistingImage: {{ $destination->cover_image ? 'true' : 'false' }},
                  removePhoto: false,
                  imagePreview: null,
                  handleFileChange(e) {
                      const file = e.target.files[0];
                      if (file) {
                          const reader = new FileReader();
                          reader.onload = (ev) => {
                              this.imagePreview = ev.target.result;
                              this.removePhoto = false;
                          };
                          reader.readAsDataURL(file);
                      } else {
                          this.imagePreview = null;
                      }
                  }
              }" 
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Meta info banner -->
            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100 flex flex-wrap items-center justify-between gap-3 text-xs text-amber-950">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-amber-200 text-amber-900"><i data-lucide="info" class="w-4 h-4"></i></span>
                    <span>Slug Permanen: <code class="font-bold bg-white px-2 py-0.5 rounded-md border border-amber-200">{{ $destination->slug }}</code></span>
                </div>
                <div class="text-[11px] text-amber-800">
                    Terdaftar pada: <strong>{{ $destination->created_at->translatedFormat('d F Y, H:i') }} WITA</strong>
                </div>
            </div>

            <!-- Section 1: Informasi Utama -->
            <div class="space-y-4 pt-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
                    <span>Informasi Utama Destinasi</span>
                </h3>

                <!-- Nama Objek -->
                <div class="space-y-1.5">
                    <label for="title" class="block text-xs font-bold text-gray-700">
                        Nama Destinasi / Objek Budaya & Wisata <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title"
                           value="{{ old('title', $destination->title) }}"
                           required
                           placeholder="Contoh: Museum Mulawarman, Kedaton Kesultanan Kutai..." 
                           class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-semibold">
                </div>

                <!-- Grid: Kategori & Status Publikasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label for="category" class="block text-xs font-bold text-gray-700">
                            Kategori Warisan & Wisata <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" 
                                id="category" 
                                x-model="category"
                                required 
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                            <option value="kesultanan">Kesultanan & Cagar Keraton</option>
                            <option value="museum_sejarah">Museum & Situs Sejarah</option>
                            <option value="wisata_alam">Wisata Alam & Rekreasi</option>
                            <option value="festival_adat">Festival Seni & Adat Tradisi</option>
                            <option value="kuliner_tradisi">Warisan Kuliner Tradisi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <p class="text-[11px] text-gray-400">Pilih rumpun klasifikasi objek budaya / wisata.</p>
                    </div>

                    <!-- Status Publikasi -->
                    <div class="space-y-1.5">
                        <label for="status" class="block text-xs font-bold text-gray-700">
                            Status Publikasi <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                x-model="status"
                                required 
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                            <option value="published">Tayang Publik (Tampil di Portal Smart City)</option>
                            <option value="draft">Draft / Arsip Internal (Hanya Terlihat di Admin)</option>
                        </select>
                        <p class="text-[11px] text-gray-400">Atur visibilitas data bagi masyarakat umum.</p>
                    </div>
                </div>

                <!-- Custom Category Input if 'lainnya' -->
                <div x-show="category === 'lainnya'" x-cloak class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-1.5 animate-fadeIn">
                    <label for="custom_category" class="block text-xs font-bold text-amber-950">
                        Sebutkan Kategori Khusus <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="custom_category" 
                           id="custom_category"
                           x-model="customCategory"
                           placeholder="Contoh: Sanggar Seni, Kerajinan Anyaman, Wisata Religi..." 
                           class="w-full px-4 py-2 rounded-xl border border-amber-200 text-xs text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                </div>
            </div>

            <!-- Section 2: Lokasi & Jam Operasional -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-blue-600"></i>
                    <span>Lokasi Wilayah & Aksesibilitas</span>
                </h3>

                <!-- Grid Kecamatan & Jam Buka -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kecamatan -->
                    <div class="space-y-1.5">
                        <label for="location_district" class="block text-xs font-bold text-gray-700">
                            Wilayah Kecamatan <span class="text-rose-500">*</span>
                        </label>
                        <select name="location_district" 
                                id="location_district" 
                                x-model="locationDistrict"
                                required 
                                class="w-full px-3.5 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                            @foreach($districts as $dName)
                                <option value="{{ $dName }}">Kecamatan {{ $dName }}</option>
                            @endforeach
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Jam Buka & Tiket -->
                    <div class="space-y-1.5">
                        <label for="operating_info" class="block text-xs font-bold text-gray-700">
                            Jam Operasional & Info Tiket
                        </label>
                        <input type="text" 
                               name="operating_info" 
                               id="operating_info"
                               value="{{ old('operating_info', $destination->operating_info) }}"
                               placeholder="Contoh: Setiap Hari (08.00 - 16.00 WITA) | Tiket: Rp 10.000" 
                               class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                    </div>
                </div>

                <!-- Custom District Input if 'lainnya' -->
                <div x-show="locationDistrict === 'lainnya'" x-cloak class="p-4 rounded-2xl bg-blue-50/50 border border-blue-200 space-y-1.5 animate-fadeIn">
                    <label for="custom_district" class="block text-xs font-bold text-blue-950">
                        Nama Kecamatan / Lokasi Khusus <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="custom_district" 
                           id="custom_district"
                           x-model="customDistrict"
                           placeholder="Masukkan nama kecamatan di wilayah Kukar..." 
                           class="w-full px-4 py-2 rounded-xl border border-blue-200 text-xs text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Alamat Lengkap -->
                <div class="space-y-1.5">
                    <label for="address" class="block text-xs font-bold text-gray-700">
                        Alamat Lengkap & Petunjuk Akses <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="address" 
                           id="address"
                           value="{{ old('address', $destination->address) }}"
                           required
                           placeholder="Contoh: Jl. Tepian Pandan No. 1, Kelurahan Panji, Tenggarong" 
                           class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                </div>

                <!-- Koordinat GPS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="latitude" class="block text-xs font-bold text-gray-700">
                            Latitude (Garis Lintang)
                        </label>
                        <input type="number" 
                               step="0.0000001" 
                               name="latitude" 
                               id="latitude"
                               value="{{ old('latitude', $destination->latitude) }}"
                               placeholder="-0.413611" 
                               class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-mono">
                    </div>
                    <div class="space-y-1.5">
                        <label for="longitude" class="block text-xs font-bold text-gray-700">
                            Longitude (Garis Bujur)
                        </label>
                        <input type="number" 
                               step="0.0000001" 
                               name="longitude" 
                               id="longitude"
                               value="{{ old('longitude', $destination->longitude) }}"
                               placeholder="116.991389" 
                               class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-mono">
                    </div>
                </div>

                @if($destination->latitude && $destination->longitude)
                    <div class="pt-1">
                        <a href="https://maps.google.com/?q={{ $destination->latitude }},{{ $destination->longitude }}" 
                           target="_blank" 
                           class="text-xs text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center gap-1.5">
                            <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                            <span>Buka titik koordinat saat ini di Google Maps</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Section 3: Foto Cover & Gambar Utama -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-emerald-600"></i>
                    <span>Foto Cover Destinasi</span>
                </h3>

                <!-- Current / Preview Image Display -->
                <div class="space-y-3">
                    @if($destination->cover_image)
                        <div x-show="hasExistingImage && !removePhoto && !imagePreview" class="p-4 rounded-2xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ str_starts_with($destination->cover_image, 'http') ? $destination->cover_image : asset('storage/' . $destination->cover_image) }}" 
                                     alt="{{ $destination->title }}" 
                                     class="w-24 h-16 object-cover rounded-xl border border-gray-200 shadow-xs">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">Foto Cover Tersimpan</h4>
                                    <p class="text-[11px] text-gray-400">File aktif saat ini di server.</p>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="removePhoto = true" 
                                    class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-all inline-flex items-center gap-1.5 border border-rose-200 cursor-pointer">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Hapus Foto</span>
                            </button>
                        </div>
                    @endif

                    <!-- Hidden input for photo removal -->
                    <input type="hidden" name="remove_photo" :value="removePhoto ? '1' : '0'">

                    <div x-show="removePhoto" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 flex items-center justify-between">
                        <span>Foto akan dihapus saat form disimpan.</span>
                        <button type="button" @click="removePhoto = false" class="text-rose-900 font-bold underline hover:no-underline">Batal Hapus</button>
                    </div>

                    <!-- Upload Input Card -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 hover:border-amber-400 rounded-3xl cursor-pointer bg-gray-50/50 hover:bg-amber-50/30 transition-all max-w-md w-full">
                            <div class="space-y-1 text-center">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-amber-500 mx-auto"></i>
                                <div class="text-xs font-semibold text-gray-700">
                                    <span class="text-amber-700 hover:underline font-bold">Pilih foto baru dari perangkat</span>
                                </div>
                                <p class="text-[11px] text-gray-400">PNG, JPG, JPEG, WEBP hingga 5MB</p>
                            </div>
                            <input type="file" name="photo" accept="image/*" @change="handleFileChange" class="hidden">
                        </label>

                        <!-- Live Preview Card for Newly Selected File -->
                        <div x-show="imagePreview" x-cloak class="relative w-40 h-28 rounded-2xl overflow-hidden border border-amber-200 shadow-md group">
                            <img :src="imagePreview" alt="Preview Cover" class="w-full h-full object-cover">
                            <button type="button" 
                                    @click="imagePreview = null; $el.closest('form').querySelector('input[name=photo]').value = ''" 
                                    class="absolute top-2 right-2 p-1 rounded-lg bg-rose-600 text-white shadow-xs hover:bg-rose-700 transition-colors">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Uraian & Nilai Sejarah -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4 text-purple-600"></i>
                    <span>Deskripsi & Narasi Sejarah</span>
                </h3>

                <!-- Uraian / Daya Tarik Wisata -->
                <div class="space-y-1.5">
                    <label for="description" class="block text-xs font-bold text-gray-700">
                        Uraian & Daya Tarik Wisata <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              required 
                              placeholder="Jelaskan daya tarik destinasi, fasilitas yang tersedia, keunikan arsitektur, dan rute akses..." 
                              class="w-full p-4 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all leading-relaxed">{{ old('description', $destination->description) }}</textarea>
                </div>

                <!-- Konteks Sejarah & Nilai Budaya -->
                <div class="space-y-1.5">
                    <label for="historical_context" class="block text-xs font-bold text-gray-700">
                        Konteks Sejarah & Makna Tradisi (Opsional)
                    </label>
                    <textarea name="historical_context" 
                              id="historical_context" 
                              rows="3" 
                              placeholder="Tuliskan latar belakang sejarah kesultanan, filosofi adat, atau kronologi berdirinya situs..." 
                              class="w-full p-4 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all leading-relaxed">{{ old('historical_context', $destination->historical_context) }}</textarea>
                    <p class="text-[11px] text-gray-400">Informasi ini akan ditampilkan pada tab khazanah sejarah di portal publik.</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.smart-city.culture.index') }}" 
                   class="w-full sm:w-auto px-6 py-2.5 rounded-2xl text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all text-center cursor-pointer">
                    Batal
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-7 py-2.5 rounded-2xl text-xs font-extrabold bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-gray-950 shadow-lg shadow-amber-500/20 transition-all cursor-pointer flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
