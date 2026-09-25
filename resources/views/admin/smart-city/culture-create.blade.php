@extends('layouts.admin')

@section('title', 'Tambah Destinasi Budaya & Wisata — Smart City Kukar')
@section('page_title', 'Smart City: Tambah Destinasi Budaya & Wisata')

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
                <span class="text-amber-400 font-semibold">Tambah Destinasi Baru</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-amber-300">
                        <i data-lucide="plus-circle" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Daftarkan Cagar Budaya & Objek Wisata Kukar</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Tambahkan data situs sejarah, cagar budaya kesultanan, desa adat, atau objek wisata alam untuk direktori resmi Smart City Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('admin.smart-city.culture.index') }}" 
               class="h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition-all inline-flex items-center gap-2 border border-white/15 cursor-pointer">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Direktori</span>
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

    <!-- Main Create Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.smart-city.culture.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              x-data="{
                  title: '{{ old('title', '') }}',
                  category: '{{ old('category', 'kesultanan') }}',
                  customCategory: '{{ old('custom_category', '') }}',
                  status: '{{ old('status', 'published') }}',
                  locationDistrict: '{{ old('location_district', 'Tenggarong') }}',
                  customDistrict: '{{ old('custom_district', '') }}',
                  address: '{{ old('address', '') }}',
                  operatingInfo: '{{ old('operating_info', '') }}',
                  latitude: '{{ old('latitude', '') }}',
                  longitude: '{{ old('longitude', '') }}',
                  description: '{{ old('description', '') }}',
                  historicalContext: '{{ old('historical_context', '') }}',
                  imagePreview: null,
                  handleFileChange(e) {
                      const file = e.target.files[0];
                      if (file) {
                          const reader = new FileReader();
                          reader.onload = (ev) => {
                              this.imagePreview = ev.target.result;
                          };
                          reader.readAsDataURL(file);
                      } else {
                          this.imagePreview = null;
                      }
                  },
                  applyPreset(preset) {
                      this.title = preset.title;
                      this.category = preset.category;
                      this.locationDistrict = preset.locationDistrict;
                      this.address = preset.address;
                      this.operatingInfo = preset.operatingInfo;
                      this.latitude = preset.lat;
                      this.longitude = preset.lng;
                      this.description = preset.description;
                      this.historicalContext = preset.historicalContext;
                  }
              }" 
              class="space-y-6">
            @csrf

            <!-- Section 1: Template Cepat Cagar Budaya & Wisata Kukar -->
            <div class="space-y-2 p-4 sm:p-5 rounded-2xl bg-amber-50/50 border border-amber-100">
                <span class="text-[11px] font-bold text-amber-950 flex items-center gap-1.5">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Pilih Template Ikon Cagar Budaya & Wisata Kukar:</span>
                </span>
                <div class="flex flex-wrap gap-2 pt-1">
                    <button type="button" 
                            @click="applyPreset({
                                title: 'Museum Mulawarman (Kedaton Kutai Kartanegara)',
                                category: 'kesultanan',
                                locationDistrict: 'Tenggarong',
                                address: 'Jl. Tepian Pandan No. 1, Kel. Panji, Tenggarong',
                                operatingInfo: 'Selasa - Minggu (08.30 - 15.30 WITA) | Tiket: Rp 10.000',
                                lat: '-0.413611',
                                lng: '116.991389',
                                description: 'Museum Mulawarman merupakan bekas istana Kesultanan Kutai Kartanegara Ing Martadipura yang dibangun pada masa Sultan A.M. Parikesit pada tahun 1932. Menyimpan lebih dari 5.000 koleksi benda bersejarah, singgasana emas, patung Lembuswana, perhiasan kerajaan, keramik kuno Dinasti Ming, dan prasasti Yupa.',
                                historicalContext: 'Pusat pemerintahan Kesultanan Kutai Kartanegara abad ke-19 hingga pertengahan abad ke-20 sebelum diserahkan kepada pemerintah Republik Indonesia sebagai cagar budaya nasional.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-semibold transition-all cursor-pointer inline-flex items-center gap-1.5">
                        <i data-lucide="landmark" class="w-3.5 h-3.5 text-amber-700"></i>
                        <span>Museum Mulawarman</span>
                    </button>

                    <button type="button" 
                            @click="applyPreset({
                                title: 'Pulau Kumala Tenggarong',
                                category: 'wisata_alam',
                                locationDistrict: 'Tenggarong',
                                address: 'Pulau Kumala, Aliran Sungai Mahakam, Tenggarong (Akses Jembatan Repo-Repo)',
                                operatingInfo: 'Setiap Hari (08.00 - 18.00 WITA) | Tiket: Rp 10.000',
                                lat: '-0.427800',
                                lng: '116.985500',
                                description: 'Pulau delta seluas 73 hektar di tengah aliran Sungai Mahakam yang disulap menjadi destinasi rekreasi keluarga modern dengan ikon patung raksasa Lembuswana berlapis perunggu, Sky Tower setinggi 75 meter, kereta mini, dan panorama matahari terbenam Sungai Mahakam.',
                                historicalContext: 'Dalam mitologi rakyat Kutai, Pulau Kumala dipercaya sebagai tempat kemunculan satwa sakti Lembuswana dari kedalaman Sungai Mahakam.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-semibold transition-all cursor-pointer inline-flex items-center gap-1.5">
                        <i data-lucide="trees" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Pulau Kumala</span>
                    </button>

                    <button type="button" 
                            @click="applyPreset({
                                title: 'Desa Budaya Adat Dayak Lekaq Kidau',
                                category: 'festival_adat',
                                locationDistrict: 'Sebulu',
                                address: 'Desa Lekaq Kidau, Kecamatan Sebulu, Kutai Kartanegara',
                                operatingInfo: 'Setiap Hari (Konfirmasi Pengelola Adat) | Tiket: Donasi Budaya',
                                lat: '-0.246800',
                                lng: '117.025100',
                                description: 'Desa wisata adat suku Dayak Kenyah yang mempertahankan keaslian Rumah Lamin Adat panjang berukir kayu ulin, seni tari kancet papatai, kerajinan manik-manik tradisional, dan tradisi telinga panjang para tetua adat.',
                                historicalContext: 'Pusat pelestarian kebudayaan Dayak Kenyah di wilayah pesisir pedalaman Mahakam yang diakui sebagai destinasi cagar budaya berbasis kearifan lokal.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-semibold transition-all cursor-pointer inline-flex items-center gap-1.5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-rose-600"></i>
                        <span>Desa Adat Lekaq Kidau</span>
                    </button>

                    <button type="button" 
                            @click="applyPreset({
                                title: 'Kompleks Pemakaman Raja-Raja Kesultanan Kutai',
                                category: 'kesultanan',
                                locationDistrict: 'Tenggarong',
                                address: 'Kompleks Pemakaman Keraton, Samping Museum Mulawarman, Tenggarong',
                                operatingInfo: 'Setiap Hari (07.00 - 17.30 WITA) | Tiket: Gratis / Infaq Ziarah',
                                lat: '-0.414100',
                                lng: '116.992200',
                                description: 'Situs pemakaman para Sultan dan kerabat Kesultanan Kutai Kartanegara Ing Martadipura yang telah wafat, termasuk makam Sultan Aji Muhammad Sulaiman dan Sultan Aji Muhammad Muslihuddin.',
                                historicalContext: 'Situs sakral yang menjadi pusat ritual keagamaan dan ziarah kesultanan menjelang perhelatan akbar Erau Pelas Benua setiap tahunnya.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-semibold transition-all cursor-pointer inline-flex items-center gap-1.5">
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>Makam Raja-Raja Kutai</span>
                    </button>
                </div>
            </div>

            <!-- Section 2: Informasi Utama -->
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
                           x-model="title"
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
                        <p class="text-[11px] text-gray-400">Atur ketersediaan akses data untuk masyarakat.</p>
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

            <!-- Section 3: Lokasi & Jam Operasional -->
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
                               x-model="operatingInfo"
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
                           x-model="address"
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
                               x-model="latitude"
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
                               x-model="longitude"
                               placeholder="116.991389" 
                               class="w-full px-4 py-2.5 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all font-mono">
                    </div>
                </div>
            </div>

            <!-- Section 4: Foto Cover & Gambar Utama -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-emerald-600"></i>
                    <span>Foto Cover Destinasi</span>
                </h3>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">
                        Upload Foto Utama (Format JPG, PNG, WEBP - Maks. 5MB)
                    </label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 hover:border-amber-400 rounded-3xl cursor-pointer bg-gray-50/50 hover:bg-amber-50/30 transition-all max-w-md w-full">
                            <div class="space-y-1 text-center">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-amber-500 mx-auto"></i>
                                <div class="text-xs font-semibold text-gray-700">
                                    <span class="text-amber-700 hover:underline font-bold">Pilih foto dari perangkat</span>
                                </div>
                                <p class="text-[11px] text-gray-400">PNG, JPG, JPEG, WEBP hingga 5MB</p>
                            </div>
                            <input type="file" name="photo" accept="image/*" @change="handleFileChange" class="hidden">
                        </label>

                        <!-- Live Preview Card -->
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

            <!-- Section 5: Uraian & Nilai Sejarah -->
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
                              x-model="description"
                              required 
                              placeholder="Jelaskan daya tarik destinasi, fasilitas yang tersedia, keunikan arsitektur, dan rute akses..." 
                              class="w-full p-4 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all leading-relaxed"></textarea>
                </div>

                <!-- Konteks Sejarah & Nilai Budaya -->
                <div class="space-y-1.5">
                    <label for="historical_context" class="block text-xs font-bold text-gray-700">
                        Konteks Sejarah & Makna Tradisi (Opsional)
                    </label>
                    <textarea name="historical_context" 
                              id="historical_context" 
                              rows="3" 
                              x-model="historicalContext"
                              placeholder="Tuliskan latar belakang sejarah kesultanan, filosofi adat, atau kronologi berdirinya situs..." 
                              class="w-full p-4 rounded-2xl border border-gray-200 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all leading-relaxed"></textarea>
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
                    <span>Simpan Destinasi Budaya</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
