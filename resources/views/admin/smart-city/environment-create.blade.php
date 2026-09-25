@extends('layouts.admin')

@section('title', 'Tambah Titik Pantau Lingkungan — Smart City Kukar')
@section('page_title', 'Smart City: Tambah Titik Pantau Lingkungan')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-slate-900 to-cyan-950 text-white p-6 sm:p-7 rounded-3xl shadow-lg relative overflow-hidden border border-cyan-500/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-64 -top-12 w-48 h-48 bg-brand-gold/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-400">Smart City</span>
                <span>/</span>
                <a href="{{ route('admin.smart-city.environment.index') }}" class="hover:text-brand-gold transition-colors">Lingkungan & TMA</a>
                <span>/</span>
                <span class="text-brand-gold font-semibold">Tambah Titik Baru</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-cyan-500/20 border border-cyan-500/30 text-cyan-300">
                        <i data-lucide="plus-circle" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Tambah Titik Pantau Telemetri Lingkungan</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Daftarkan pos pantau sensor TMA Sungai Mahakam, stasiun ISPU udara, atau peringatan daerah rawan genangan banjir.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('admin.smart-city.environment.index') }}" 
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
        <form action="{{ route('admin.smart-city.environment.store') }}" 
              method="POST" 
              x-data="{
                  title: '{{ old('title', '') }}',
                  infoType: '{{ old('info_type', 'water_level') }}',
                  severity: '{{ old('severity', 'normal') }}',
                  statusCondition: '{{ old('status_condition', '') }}',
                  source: '{{ old('source', 'BWS Kalimantan IV / BPBD Kukar') }}',
                  locationName: '{{ old('location_name', '') }}',
                  locationDistrict: '{{ old('location_district', 'Tenggarong') }}',
                  latitude: '{{ old('latitude', '') }}',
                  longitude: '{{ old('longitude', '') }}',
                  description: '{{ old('description', '') }}',
                  applyPreset(preset) {
                      this.title = preset.title;
                      this.infoType = preset.infoType;
                      this.severity = preset.severity;
                      this.statusCondition = preset.statusCondition;
                      this.source = preset.source;
                      this.locationName = preset.locationName;
                      this.locationDistrict = preset.locationDistrict;
                      this.latitude = preset.lat;
                      this.longitude = preset.lng;
                      this.description = preset.description;
                  }
              }" 
              class="space-y-6">
            @csrf

            <!-- Section 1: Template Cepat Titik Lingkungan Kukar -->
            <div class="space-y-2 p-4 sm:p-5 rounded-2xl bg-cyan-50/50 border border-cyan-100">
                <span class="text-[11px] font-bold text-cyan-950 flex items-center gap-1.5">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                    <span>Pilih Template Stasiun / Pos Pantau Umum Kukar:</span>
                </span>
                <div class="flex flex-wrap gap-2 pt-1">
                    <button type="button" 
                            @click="applyPreset({
                                title: 'Pos Pantau TMA Sungai Mahakam — Dermaga Pulau Kumala',
                                infoType: 'water_level',
                                severity: 'normal',
                                statusCondition: 'TMA: 4.10 M (Normal)',
                                source: 'Balai Wilayah Sungai (BWS) Kalimantan IV',
                                locationName: 'Dermaga Feri Pulau Kumala',
                                locationDistrict: 'Tenggarong',
                                lat: -0.4350000,
                                lng: 116.9790000,
                                description: 'Tinggi muka air Sungai Mahakam terpantau stabil pada level 4.10 meter. Aliran air sungai normal dan aktivitas penyeberangan aman.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-cyan-100 text-gray-700 hover:text-cyan-950 border border-cyan-200 text-xs font-semibold transition-all cursor-pointer shadow-2xs">
                        + Pos TMA Dermaga Kumala
                    </button>

                    <button type="button" 
                            @click="applyPreset({
                                title: 'Stasiun ISPU Kualitas Udara — Kawasan Perkantoran Bukit Biru',
                                infoType: 'air_quality',
                                severity: 'normal',
                                statusCondition: 'ISPU: 38 (Kategori: BAIK / Hijau)',
                                source: 'Dinas Lingkungan Hidup & Kehutanan (DLHK) Kukar',
                                locationName: 'Kompleks Kantor Bupati Bukit Biru',
                                locationDistrict: 'Tenggarong',
                                lat: -0.4485000,
                                lng: 116.9650000,
                                description: 'Indeks Standar Pencemar Udara (ISPU) berada dalam kategori BAIK. Udara sangat layak untuk aktivitas luar ruang warga.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-cyan-100 text-gray-700 hover:text-cyan-950 border border-cyan-200 text-xs font-semibold transition-all cursor-pointer shadow-2xs">
                        + Stasiun ISPU DLHK
                    </button>

                    <button type="button" 
                            @click="applyPreset({
                                title: 'Peringatan Waspada Titik Genangan Pasang Sungai — Kawasan Loa Ipuh',
                                infoType: 'flood_alert',
                                severity: 'warning',
                                statusCondition: 'Status: Waspada Pasang Air (Malam Hari)',
                                source: 'Badan Penanggulangan Bencana Daerah (BPBD) Kukar',
                                locationName: 'Bantaran Sungai Loa Ipuh',
                                locationDistrict: 'Tenggarong',
                                lat: -0.4220000,
                                lng: 116.9850000,
                                description: 'Diperkirakan terjadi kenaikan air pasang sungai yang menahan drainase perkotaan. Warga diimbau mengamankan barang berharga.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-cyan-100 text-gray-700 hover:text-cyan-950 border border-cyan-200 text-xs font-semibold transition-all cursor-pointer shadow-2xs">
                        + Genangan Pasang Loa Ipuh
                    </button>

                    <button type="button" 
                            @click="applyPreset({
                                title: 'Pos Pantau Siaga Karhutla & Titik Panas — Samboja',
                                infoType: 'hotspot',
                                severity: 'normal',
                                statusCondition: 'Hotspot: 0 Titik Panas (Nihil)',
                                source: 'Manggala Agni Daops Kalimantan / BPBD Kukar',
                                locationName: 'Pos Siaga Karhutla Samboja',
                                locationDistrict: 'Samboja',
                                lat: -1.0250000,
                                lng: 117.0200000,
                                description: 'Pantauan satelit mendeteksi nihil titik panas karhutla di wilayah semak belukar dan perkebunan Samboja.'
                            })"
                            class="px-3 py-1.5 rounded-xl bg-white hover:bg-cyan-100 text-gray-700 hover:text-cyan-950 border border-cyan-200 text-xs font-semibold transition-all cursor-pointer shadow-2xs">
                        + Siaga Karhutla Samboja
                    </button>
                </div>
            </div>

            <!-- Section 2: Identitas Parameter & Tingkat Status -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-cyan-50 text-cyan-600">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Identitas Titik Pantau & Parameter</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Judul Titik Pantau -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Nama Titik Sensor / Judul Pemantauan <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-gray-400 font-normal">Wajib diisi</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               x-model="title"
                               required 
                               placeholder="Contoh: Tinggi Muka Air (TMA) Sungai Mahakam — Pos Pantau Dermaga Pulau Kumala" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Jenis Informasi -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Jenis Parameter Telemetri <span class="text-rose-500">*</span></label>
                        <select name="info_type" 
                                x-model="infoType"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            <option value="water_level">Tinggi Air Sungai (TMA Sungai Mahakam)</option>
                            <option value="air_quality">Kualitas Udara (ISPU / Indeks Polusi)</option>
                            <option value="flood_alert">Titik Genangan Air / Peringatan Banjir Pasang</option>
                            <option value="hotspot">Titik Panas (Hotspot / Karhutla)</option>
                            <option value="weather">Peringatan Cuaca Ekstrem (BMKG)</option>
                            <option value="other">Infrastruktur & Lingkungan Lainnya</option>
                        </select>
                    </div>

                    <!-- Tingkat Status (Severity) -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Tingkat Status Bahaya (Severity) <span class="text-rose-500">*</span></label>
                        <select name="severity" 
                                x-model="severity"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-bold transition-all shadow-2xs"
                                :class="{ 'text-emerald-700 bg-emerald-50/50': severity === 'normal', 'text-amber-700 bg-amber-50/50': severity === 'warning', 'text-rose-700 bg-rose-50/50': severity === 'danger' }">
                            <option value="normal">Normal (Aman / Kondisi Terkendali)</option>
                            <option value="warning">Waspada (Siaga 2 / Perhatian Khusus)</option>
                            <option value="danger">Bahaya (Siaga 1 / Kritis & Evakuasi)</option>
                        </select>
                    </div>

                    <!-- Nilai / Kondisi Saat Ini -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Nilai Pembacaan Sensor / Kondisi Saat Ini</span>
                            <span class="text-[11px] text-cyan-700 font-bold">Tampil mencolok pada portal</span>
                        </label>
                        <input type="text" 
                               name="status_condition" 
                               x-model="statusCondition"
                               placeholder="Misal: TMA: 4.10 M (Normal) atau ISPU: 38 (Baik)" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-mono font-bold transition-all shadow-2xs">
                    </div>

                    <!-- Sumber Data / Instansi Pengawas -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Sumber Data / Instansi Resmi <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="source" 
                               x-model="source"
                               required 
                               placeholder="Contoh: BPBD Kukar / BWS Kalimantan IV / BMKG" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section 3: Lokasi & Koordinat Geografis -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Lokasi Wilayah & Koordinat GPS</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lokasi -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Nama Lokasi / Patokan Landmark <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="location_name" 
                               x-model="locationName"
                               required 
                               placeholder="Contoh: Pos Pantau Dermaga Pulau Kumala Tenggarong" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Kecamatan -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Kecamatan Wilayah <span class="text-rose-500">*</span></label>
                        <select name="location_district" 
                                x-model="locationDistrict"
                                data-search-limit="50"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            @foreach($districts as $dst)
                                <option value="{{ $dst }}">Kec. {{ $dst }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Latitude -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Latitude (Garis Lintang)</span>
                            <span class="text-[11px] text-gray-400">Contoh: -0.4350000</span>
                        </label>
                        <input type="number" 
                               name="latitude" 
                               x-model="latitude"
                               step="0.0000001"
                               placeholder="-0.4350000" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none transition-all shadow-2xs">
                    </div>

                    <!-- Longitude -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Longitude (Garis Bujur)</span>
                            <span class="text-[11px] text-gray-400">Contoh: 116.9790000</span>
                        </label>
                        <input type="number" 
                               name="longitude" 
                               x-model="longitude"
                               step="0.0000001"
                               placeholder="116.9790000" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section 4: Uraian & Rekomendasi Warga -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-purple-50 text-purple-600">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Uraian & Imbauan Keselamatan Warga</h3>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                        <span>Penjelasan Kondisi Lapangan & Panduan Tindakan <span class="text-rose-500">*</span></span>
                        <span class="text-[11px] text-gray-400 font-normal">Informasi keselamatan untuk publik</span>
                    </label>
                    <textarea name="description" 
                              x-model="description"
                              rows="4" 
                              required 
                              placeholder="Jelaskan kondisi debit air, tren kenaikan/penurunan, imbauan kepada nelayan dan kapal tongkang, serta langkah pencegahan warga..." 
                              class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.smart-city.environment.index') }}" 
                   class="w-full sm:w-auto h-11 px-6 rounded-2xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                    Batal & Kembali
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto h-11 px-7 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-2xl shadow-lg shadow-brand-gold/25 transition-all inline-flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Titik Pantau</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
