@extends('layouts.admin')

@section('title', 'Edit Titik Pantau: ' . $point->title . ' — Smart City Kukar')
@section('page_title', 'Smart City: Edit Titik Pantau Lingkungan')

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
                <span class="text-brand-gold font-semibold">Edit #{{ $point->id }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-cyan-500/20 border border-cyan-500/30 text-cyan-300">
                        <i data-lucide="edit-3" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Edit Titik Pantau: {{ $point->title }}</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Perbarui nilai kondisi sensor, tingkat status bahaya, rekomendasi keselamatan warga, atau titik koordinat GPS.
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

    <!-- Main Edit Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.smart-city.environment.update', $point->id) }}" 
              method="POST" 
              x-data="{
                  severity: '{{ old('severity', $point->severity) }}'
              }" 
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas Parameter & Tingkat Status -->
            <div class="space-y-4">
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
                               value="{{ old('title', $point->title) }}" 
                               required 
                               placeholder="Contoh: Tinggi Muka Air (TMA) Sungai Mahakam — Pos Pantau Dermaga Pulau Kumala" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Jenis Informasi -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Jenis Parameter Telemetri <span class="text-rose-500">*</span></label>
                        <select name="info_type" 
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            <option value="water_level" {{ old('info_type', $point->info_type) === 'water_level' ? 'selected' : '' }}>Tinggi Air Sungai (TMA Sungai Mahakam)</option>
                            <option value="air_quality" {{ old('info_type', $point->info_type) === 'air_quality' ? 'selected' : '' }}>Kualitas Udara (ISPU / Indeks Polusi)</option>
                            <option value="flood_alert" {{ in_array(old('info_type', $point->info_type), ['flood_alert', 'flood_point']) ? 'selected' : '' }}>Titik Genangan Air / Peringatan Banjir Pasang</option>
                            <option value="hotspot" {{ in_array(old('info_type', $point->info_type), ['hotspot', 'karhutla']) ? 'selected' : '' }}>Titik Panas (Hotspot / Karhutla)</option>
                            <option value="weather" {{ old('info_type', $point->info_type) === 'weather' ? 'selected' : '' }}>Peringatan Cuaca Ekstrem (BMKG)</option>
                            <option value="other" {{ old('info_type', $point->info_type) === 'other' ? 'selected' : '' }}>Infrastruktur & Lingkungan Lainnya</option>
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
                               value="{{ old('status_condition', $point->status_condition) }}" 
                               placeholder="Misal: TMA: 4.10 M (Normal) atau ISPU: 38 (Baik)" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-mono font-bold transition-all shadow-2xs">
                    </div>

                    <!-- Sumber Data / Instansi Pengawas -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Sumber Data / Instansi Resmi <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="source" 
                               value="{{ old('source', $point->source) }}" 
                               required 
                               placeholder="Contoh: BPBD Kukar / BWS Kalimantan IV / BMKG" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section 2: Lokasi & Koordinat Geografis -->
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
                               value="{{ old('location_name', $point->location_name) }}" 
                               required 
                               placeholder="Contoh: Pos Pantau Dermaga Pulau Kumala Tenggarong" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Kecamatan -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Kecamatan Wilayah <span class="text-rose-500">*</span></label>
                        <select name="location_district" 
                                data-search-limit="50"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            @foreach($districts as $dst)
                                <option value="{{ $dst }}" {{ old('location_district', $point->location_district) === $dst ? 'selected' : '' }}>
                                    Kec. {{ $dst }}
                                </option>
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
                               value="{{ old('latitude', $point->latitude) }}" 
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
                               value="{{ old('longitude', $point->longitude) }}" 
                               step="0.0000001"
                               placeholder="116.9790000" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section 3: Uraian & Rekomendasi Warga -->
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
                              rows="4" 
                              required 
                              placeholder="Jelaskan kondisi debit air, tren kenaikan/penurunan, imbauan kepada nelayan dan kapal tongkang, serta langkah pencegahan warga..." 
                              class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs leading-relaxed">{{ old('description', $point->description) }}</textarea>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-gray-100">
                <div class="text-xs text-gray-400 font-medium">
                    Terakhir diperbarui: <span class="font-bold text-gray-700">{{ $point->updated_at ? $point->updated_at->translatedFormat('d F Y, H:i') : '-' }}</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.smart-city.environment.index') }}" 
                       class="w-full sm:w-auto h-11 px-6 rounded-2xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                        Batal & Kembali
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto h-11 px-7 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-2xl shadow-lg shadow-brand-gold/25 transition-all inline-flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Perubahan Titik Pantau</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
