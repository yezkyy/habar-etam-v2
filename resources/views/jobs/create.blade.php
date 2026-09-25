@extends('layouts.public')

@section('title', 'Pasang Lowongan Kerja — Habar Etam')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Header & Navigation -->
    <div class="mb-6 reveal-blur-spring">
        <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-gray-500 hover:text-brand-black flex items-center gap-1 mb-2 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Bursa Kerja</span>
        </a>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold uppercase tracking-wider mb-2">
            <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-blue-600"></i>
            <span>Formulir Publikasi Loker</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Pasang Lowongan Kerja Baru</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Publikasikan kebutuhan tenaga kerja usaha/perusahaan Anda langsung kepada pencari kerja lokal Kukar secara gratis.</p>
    </div>

    <!-- Form Card Container -->
    <div class="bg-white border border-gray-200/90 rounded-3xl p-6 sm:p-8 shadow-subtle reveal-blur-spring relative">
        <form method="POST" action="{{ route('jobs.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Job Title -->
            <div>
                <label for="title" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span>Posisi / Jabatan Pekerjaan <span class="text-rose-500">*</span></span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       required 
                       class="form-input rounded-2xl py-3 text-xs" 
                       placeholder="Contoh: Barista, Kasir Toko, Staff Administrasi, Driver Ekspedisi">
                @error('title') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Photo Upload Field -->
            <div>
                <label for="photo" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="image" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span>Foto Tempat Kerja / Banner Lowongan (Opsional)</span>
                </label>
                <input type="file" 
                       id="photo" 
                       name="photo" 
                       accept="image/*" 
                       class="form-input rounded-2xl py-2.5 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5MB). Jika dikosongkan, banner ilustrasi relevan otomatis ditampilkan.</p>
                @error('photo') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Company & Employment Type -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="company" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="building-2" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span>Nama Usaha / Perusahaan <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="company" 
                           name="company" 
                           value="{{ old('company') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="Contoh: Kedai Kopi Tepian / PT Borneo Sejahtera">
                    @error('company') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="employment_type" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span>Tipe Pekerjaan <span class="text-rose-500">*</span></span>
                    </label>
                    <div class="relative">
                        <select id="employment_type" name="employment_type" required class="form-input rounded-2xl py-3 text-xs font-semibold">
                            <option value="Purna Waktu" {{ old('employment_type') == 'Purna Waktu' ? 'selected' : '' }}>Purna Waktu (Full Time)</option>
                            <option value="Paruh Waktu" {{ old('employment_type') == 'Paruh Waktu' ? 'selected' : '' }}>Paruh Waktu (Part Time)</option>
                            <option value="Kontrak" {{ old('employment_type') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang" {{ old('employment_type') == 'Magang' ? 'selected' : '' }}>Magang (Internship)</option>
                            <option value="Freelance" {{ old('employment_type') == 'Freelance' ? 'selected' : '' }}>Freelance / Harian</option>
                        </select>
                    </div>
                    @error('employment_type') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Location & Salary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500"></i>
                        <span>Lokasi Penempatan <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location', 'Tenggarong') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="Contoh: Tenggarong Kota / Loa Janan / Muara Badak">
                    @error('location') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="salary_range" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="wallet" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Estimasi Gaji / Upah (Opsional)</span>
                    </label>
                    <input type="text" 
                           id="salary_range" 
                           name="salary_range" 
                           value="{{ old('salary_range') }}" 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="Contoh: Rp 2.500.000 - Rp 3.500.000 / Negosiasi">
                </div>
            </div>

            <!-- Contacts: Phone, Email, Deadline -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="contact_phone" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>WhatsApp / Telepon <span class="text-rose-500">*</span></span>
                    </label>
                    <input type="text" 
                           id="contact_phone" 
                           name="contact_phone" 
                           value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" 
                           required 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="08123456789">
                    @error('contact_phone') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_email" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>Email Lamaran (Opsional)</span>
                    </label>
                    <input type="email" 
                           id="contact_email" 
                           name="contact_email" 
                           value="{{ old('contact_email') }}" 
                           class="form-input rounded-2xl py-3 text-xs" 
                           placeholder="hrd@perusahaan.com">
                </div>

                <div>
                    <label for="deadline" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-purple-600"></i>
                        <span>Batas Akhir Lamaran</span>
                    </label>
                    <input type="date" 
                           id="deadline" 
                           name="deadline" 
                           value="{{ old('deadline') }}" 
                           class="form-input rounded-2xl py-3 text-xs">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span>Deskripsi Pekerjaan & Tanggung Jawab <span class="text-rose-500">*</span></span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          class="form-input rounded-2xl p-3.5 text-xs leading-relaxed" 
                          placeholder="Jelaskan gambaran pekerjaan, tanggung jawab utama, jam kerja, serta benefit atau fasilitas yang didapatkan...">{{ old('description') }}</textarea>
                @error('description') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Requirements -->
            <div>
                <label for="requirements" class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    <i data-lucide="check-square" class="w-3.5 h-3.5 text-amber-600"></i>
                    <span>Syarat & Kualifikasi Pelamar (Opsional)</span>
                </label>
                <textarea id="requirements" 
                          name="requirements" 
                          rows="4" 
                          class="form-input rounded-2xl p-3.5 text-xs leading-relaxed" 
                          placeholder="Contoh:
- Pendidikan minimal SMA / SMK Sederajat
- Usia maksimal 28 tahun
- Memiliki pengalaman di bidang terkait min. 1 tahun
- Jujur, disiplin, dan mampu bekerja dalam tim">{{ old('requirements') }}</textarea>
            </div>

            <!-- Submit Controls -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('jobs.index') }}" class="btn-outline text-xs py-3 px-5 rounded-2xl font-bold">Batal</a>
                <button type="submit" class="btn-gold text-xs py-3 px-7 rounded-2xl font-black shadow-gold-glow flex items-center gap-2 hover:scale-[1.02] transition-transform">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Lowongan Kerja</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
