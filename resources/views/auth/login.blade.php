@extends('layouts.auth')

@php
$hasRegisterErrors = $errors->hasAny(['name', 'nik', 'date_of_birth', 'district', 'address', 'password_confirmation', 'phone']) || old('nik');
$currentMode = $hasRegisterErrors ? 'register' : ($initialMode ?? 'login');
@endphp

@section('title', $currentMode === 'register' ? 'Pendaftaran Warga Kukar' : 'Masuk Akun Warga')

@section('content')
<div id="authContainer" data-lenis-prevent class="h-screen w-screen relative overflow-hidden bg-white flex flex-col {{ $currentMode === 'register' ? 'mode-register' : 'mode-login' }}">

    <!-- ================================================================= -->
    <!-- MOBILE VIEW HEADER & TOGGLER (< lg screens) -->
    <!-- ================================================================= -->
    <div class="lg:hidden shrink-0 bg-[#0c1017] text-white px-4 py-3 border-b border-white/10 flex items-center justify-between z-20">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('assets/logo-habar-etam.png') }}" alt="Habar Etam" class="h-7 w-auto">
            <div>
                <span class="block text-xs font-black text-white leading-tight">HABAR ETAM</span>
                <span class="block text-[8px] font-bold text-brand-gold uppercase tracking-wider">Portal Warga Kukar</span>
            </div>
        </a>
        <div class="flex items-center gap-2">
            <div class="flex items-center p-0.5 bg-white/10 rounded-xl">
                <button type="button"
                    id="mobBtnLogin"
                    onclick="switchAuthMode('login')"
                    class="py-1 px-3 rounded-lg text-xs font-bold transition-all flex items-center gap-1 {{ $currentMode === 'login' ? 'bg-brand-gold text-brand-black shadow-sm' : 'text-gray-300' }}">
                    <i data-lucide="log-in" class="w-3 h-3"></i>
                    <span>Masuk</span>
                </button>
                <button type="button"
                    id="mobBtnRegister"
                    onclick="switchAuthMode('register')"
                    class="py-1 px-3 rounded-lg text-xs font-bold transition-all flex items-center gap-1 {{ $currentMode === 'register' ? 'bg-brand-gold text-brand-black shadow-sm' : 'text-gray-300' }}">
                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                    <span>Daftar</span>
                </button>
            </div>
            <a href="{{ route('home') }}" class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white" title="Ke Beranda">
                <i data-lucide="home" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- DESKTOP SLIDING VISUAL BANNER (Slides smoothly across 50% left/right) -->
    <!-- ================================================================= -->
    <div id="slidingOverlay"
        class="hidden lg:block absolute top-0 left-0 w-1/2 h-full z-30 transition-transform duration-700 ease-in-out shadow-2xl overflow-hidden {{ $currentMode === 'register' ? 'translate-x-full' : 'translate-x-0' }}">

        <!-- Double Width Canvas (Left Half = Login Visual, Right Half = Register Visual) -->
        <div id="overlayInner"
            class="w-[200%] h-full flex transition-transform duration-700 ease-in-out {{ $currentMode === 'register' ? '-translate-x-1/2' : 'translate-x-0' }}">

            <!-- 1. LEFT VISUAL (Visible on Left when Login mode is active) -->
            <div class="w-1/2 h-full relative overflow-hidden bg-gradient-to-br from-slate-950 via-brand-black to-stone-900 text-white p-10 xl:p-14 flex flex-col justify-between select-none">
                <!-- Background Scenic Image & Gradients -->
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80"
                        alt="Kutai Kartanegara Landscape"
                        class="w-full h-full object-cover opacity-35 scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-brand-black/80 to-slate-950/75"></div>
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-brand-gold/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
                </div>

                <!-- Top Brand Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/logo-habar-etam.png') }}" alt="Habar Etam" class="h-10 w-auto">
                        <div>
                            <span class="block text-sm font-black text-white tracking-tight">HABAR ETAM</span>
                            <span class="block text-[10px] font-bold text-brand-gold uppercase tracking-wider">Portal Digital Warga Kukar</span>
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-xs font-bold text-gray-200 hover:text-white transition-all backdrop-blur-md">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                <!-- Center Content Hero -->
                <div class="relative z-10 my-auto py-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 backdrop-blur-md text-brand-gold text-xs font-black uppercase tracking-wider mb-4">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Pusat Layanan & Informasi Warga</span>
                    </div>

                    <h2 class="text-3xl xl:text-4xl font-black text-white tracking-tight leading-tight">
                        Selamat Datang di <span class="text-brand-gold">Habar Etam</span>
                    </h2>
                    <p class="text-sm text-gray-300 mt-3 leading-relaxed max-w-lg">
                        Satu pintu terintegrasi untuk aspirasi <strong>Lapor Etam</strong>, bursa lowongan kerja terverifikasi, promosi UMKM, dan informasi publik Kutai Kartanegara.
                    </p>

                    <!-- Feature Badges -->
                    <div class="mt-8 space-y-3 max-w-md">
                        <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-3.5 backdrop-blur-sm">
                            <div class="w-9 h-9 rounded-xl bg-brand-gold/20 text-brand-gold flex items-center justify-center shrink-0">
                                <i data-lucide="megaphone" class="w-4 h-4"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-white">Lapor Etam Terpadu</h4>
                                <p class="text-gray-400 text-[11px]">Sampaikan laporan warga dan pantau progres penanganan secara transparan.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-3.5 backdrop-blur-sm">
                            <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="briefcase" class="w-4 h-4"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-white">Bursa Karir Lokal Kukar</h4>
                                <p class="text-gray-400 text-[11px]">Lowongan kerja resmi dari perusahaan dan pelaku usaha terverifikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Switcher CTA -->
                <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-bold text-white">Belum terdaftar sebagai akun warga?</span>
                        <span class="text-[11px] text-gray-400">Pendaftaran mudah dengan validasi NIK terenkripsi.</span>
                    </div>
                    <button type="button"
                        onclick="switchAuthMode('register')"
                        class="px-5 py-2.5 rounded-2xl bg-brand-gold hover:bg-brand-gold-dark text-brand-black text-xs font-black transition-all flex items-center gap-2 shadow-gold-glow hover:scale-105">
                        <span>Daftar Akun Baru</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- 2. RIGHT VISUAL (Visible on Right when Register mode is active) -->
            <div class="w-1/2 h-full relative overflow-hidden bg-gradient-to-br from-stone-900 via-brand-black to-slate-950 text-white p-10 xl:p-14 flex flex-col justify-between select-none">
                <!-- Background Scenic Image & Gradients -->
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80"
                        alt="Kukar Digital Network"
                        class="w-full h-full object-cover opacity-30 scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-brand-black/80 to-slate-950/75"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
                </div>

                <!-- Top Brand Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/logo-habar-etam.png') }}" alt="Habar Etam" class="h-10 w-auto">
                        <div>
                            <span class="block text-sm font-black text-white tracking-tight">HABAR ETAM</span>
                            <span class="block text-[10px] font-bold text-brand-gold uppercase tracking-wider">Portal Digital Warga Kukar</span>
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 text-xs font-bold text-gray-200 hover:text-white transition-all backdrop-blur-md">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-brand-gold"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                <!-- Center Content Hero -->
                <div class="relative z-10 my-auto py-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-400/20 backdrop-blur-md text-emerald-400 text-xs font-black uppercase tracking-wider mb-4">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        <span>Validasi Kependudukan Resmi Kukar</span>
                    </div>

                    <h2 class="text-3xl xl:text-4xl font-black text-white tracking-tight leading-tight">
                        Bergabung Menjadi <span class="text-brand-gold">Warga Terverifikasi</span>
                    </h2>
                    <p class="text-sm text-gray-300 mt-3 leading-relaxed max-w-lg">
                        Mendukung partisipasi aktif warga Kutai Kartanegara dalam ruang digital yang sehat, aman, dan bermanfaat bagi kemajuan daerah.
                    </p>

                    <!-- Feature Badges -->
                    <div class="mt-8 space-y-3 max-w-md">
                        <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-3.5 backdrop-blur-sm">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-white">Privasi NIK Terenkripsi Aman</h4>
                                <p class="text-gray-400 text-[11px]">Nomor NIK dienkripsi berstandar tinggi dan tidak pernah dipublikasikan.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-3.5 backdrop-blur-sm">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                                <i data-lucide="store" class="w-4 h-4"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-white">Promosi Produk & Kuliner UMKM</h4>
                                <p class="text-gray-400 text-[11px]">Pasarkan usaha, jasa, dan direktori kuliner lokal tanpa dipungut biaya.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action Switcher CTA -->
                <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-bold text-white">Sudah memiliki akun terdaftar?</span>
                        <span class="text-[11px] text-gray-400">Masuk untuk mengelola profil dan layanan Anda.</span>
                    </div>
                    <button type="button"
                        onclick="switchAuthMode('login')"
                        class="px-5 py-2.5 rounded-2xl bg-white hover:bg-gray-100 text-brand-black text-xs font-black transition-all flex items-center gap-2 shadow-lg hover:scale-105">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Masuk ke Akun</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ================================================================= -->
    <!-- UNCONSTRAINED FULL-HEIGHT SPLIT FORM SLOTS (Left & Right 50%) -->
    <!-- ================================================================= -->
    <div class="w-full flex-1 min-h-0 flex flex-col lg:flex-row overflow-hidden relative">

        <!-- SLOT 1 (LEFT 50%): REGISTER FORM -->
        <div id="registerSlot"
            data-lenis-prevent
            class="w-full lg:w-1/2 h-full min-h-0 overflow-y-auto overscroll-contain px-6 py-6 sm:px-10 lg:px-12 xl:px-16 {{ $currentMode === 'register' ? 'flex pointer-events-auto opacity-100' : 'hidden lg:flex pointer-events-none opacity-0' }} flex-col transition-opacity duration-300">

            <div class="max-w-md w-full mx-auto min-h-full flex flex-col justify-between py-2">
                <div>
                    <!-- Inner Brand & Mode Indicator for Register -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Form Pendaftaran Warga</span>
                        </div>
                        <span class="text-xs text-gray-400 font-semibold">18 Kecamatan Kukar</span>
                    </div>

                    <div class="mb-5">
                        <h2 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Daftar Akun Warga</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Lengkapi data kependudukan Kutai Kartanegara untuk validasi identitas.</p>
                    </div>

                    @include('partials.alert')

                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="reg_name" class="block text-xs font-bold text-gray-700 mb-1">
                                Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                id="reg_name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="form-input rounded-2xl py-2.5 px-3.5 text-xs w-full border-gray-200 focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 bg-gray-50/50"
                                placeholder="Contoh: Budi Santoso">
                            @error('name') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="reg_email" class="block text-xs font-bold text-gray-700 mb-1">
                                    Email Aktif <span class="text-rose-500">*</span>
                                </label>
                                <input type="email"
                                    id="reg_email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="form-input rounded-2xl py-2.5 px-3.5 text-xs w-full border-gray-200 focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 bg-gray-50/50"
                                    placeholder="nama@email.com">
                                @error('email') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="reg_phone" class="block text-xs font-bold text-gray-700 mb-1">
                                    No. WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                    id="reg_phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    class="form-input rounded-2xl py-2.5 px-3.5 text-xs w-full border-gray-200 focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 bg-gray-50/50"
                                    placeholder="08123456789">
                                @error('phone') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- NIK & Kependudukan Section Box -->
                        <div class="p-3.5 bg-amber-50/80 border border-amber-200/90 rounded-2xl space-y-2.5 shadow-2xs">
                            <div class="flex items-center justify-between text-xs font-bold text-amber-900">
                                <span class="flex items-center gap-1.5">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                                    Validasi NIK Kukar
                                </span>
                                <span class="text-[10px] bg-amber-200/70 text-amber-900 px-2 py-0.5 rounded-lg font-bold">Wajib KTP Kukar</span>
                            </div>

                            <div>
                                <input type="text"
                                    id="reg_nik"
                                    name="nik"
                                    value="{{ old('nik') }}"
                                    maxlength="16"
                                    required
                                    class="form-input font-mono tracking-wider rounded-xl py-2 px-3 text-xs bg-white w-full border-amber-200 focus:border-brand-gold focus:ring-brand-gold/20"
                                    placeholder="16 Digit NIK (6402xxxxxxxxxxxx)">
                                @error('nik') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div>
                                    <label for="reg_dob" class="block text-[11px] font-bold text-amber-950 mb-1">
                                        Tanggal Lahir <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="modern-date-picker-wrapper relative flex items-center">
                                        <input type="text"
                                            id="reg_dob"
                                            name="date_of_birth"
                                            value="{{ old('date_of_birth') }}"
                                            placeholder="Pilih Tanggal Lahir..."
                                            required
                                            class="modern-date-input form-input rounded-xl sm:rounded-2xl border-gray-200/90 bg-gray-50/60 hover:bg-white focus:bg-white text-left font-semibold text-gray-800 placeholder-gray-400 transition-all shadow-2xs hover:border-brand-gold/60 focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15">
                                        <div class="absolute right-3.5 pointer-events-none text-gray-400 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-amber-600/80">
                                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                                <line x1="16" x2="16" y1="2" y2="6" />
                                                <line x1="8" x2="8" y1="2" y2="6" />
                                                <line x1="3" x2="21" y1="10" y2="10" />
                                                <path d="M8 14h.01" />
                                                <path d="M12 14h.01" />
                                                <path d="M16 14h.01" />
                                                <path d="M8 18h.01" />
                                                <path d="M12 18h.01" />
                                                <path d="M16 18h.01" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('date_of_birth') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="reg_district" class="block text-[11px] font-bold text-amber-950 mb-1">
                                        Kecamatan Kukar <span class="text-rose-500">*</span>
                                    </label>
                                    <select id="reg_district" name="district" required data-searchable="true" data-search-limit="3" class="form-input rounded-xl py-1.5 px-2 text-xs bg-white w-full border-amber-200 font-semibold">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        <option value="Tenggarong" {{ old('district') == 'Tenggarong' ? 'selected' : '' }}>Tenggarong</option>
                                        <option value="Tenggarong Seberang" {{ old('district') == 'Tenggarong Seberang' ? 'selected' : '' }}>Tenggarong Seberang</option>
                                        <option value="Loa Kulu" {{ old('district') == 'Loa Kulu' ? 'selected' : '' }}>Loa Kulu</option>
                                        <option value="Loa Janan" {{ old('district') == 'Loa Janan' ? 'selected' : '' }}>Loa Janan</option>
                                        <option value="Samboja" {{ old('district') == 'Samboja' ? 'selected' : '' }}>Samboja</option>
                                        <option value="Samboja Barat" {{ old('district') == 'Samboja Barat' ? 'selected' : '' }}>Samboja Barat</option>
                                        <option value="Muara Jawa" {{ old('district') == 'Muara Jawa' ? 'selected' : '' }}>Muara Jawa</option>
                                        <option value="Sangasanga" {{ old('district') == 'Sangasanga' ? 'selected' : '' }}>Sangasanga</option>
                                        <option value="Anggana" {{ old('district') == 'Anggana' ? 'selected' : '' }}>Anggana</option>
                                        <option value="Muara Badak" {{ old('district') == 'Muara Badak' ? 'selected' : '' }}>Muara Badak</option>
                                        <option value="Marang Kayu" {{ old('district') == 'Marang Kayu' ? 'selected' : '' }}>Marang Kayu</option>
                                        <option value="Sebulu" {{ old('district') == 'Sebulu' ? 'selected' : '' }}>Sebulu</option>
                                        <option value="Muara Kaman" {{ old('district') == 'Muara Kaman' ? 'selected' : '' }}>Muara Kaman</option>
                                        <option value="Kota Bangun" {{ old('district') == 'Kota Bangun' ? 'selected' : '' }}>Kota Bangun</option>
                                        <option value="Kota Bangun Darat" {{ old('district') == 'Kota Bangun Darat' ? 'selected' : '' }}>Kota Bangun Darat</option>
                                        <option value="Kenohan" {{ old('district') == 'Kenohan' ? 'selected' : '' }}>Kenohan</option>
                                        <option value="Kembang Janggut" {{ old('district') == 'Kembang Janggut' ? 'selected' : '' }}>Kembang Janggut</option>
                                        <option value="Tabang" {{ old('district') == 'Tabang' ? 'selected' : '' }}>Tabang</option>
                                        <option value="Muara Wis" {{ old('district') == 'Muara Wis' ? 'selected' : '' }}>Muara Wis</option>
                                        <option value="Muara Muntai" {{ old('district') == 'Muara Muntai' ? 'selected' : '' }}>Muara Muntai</option>
                                    </select>
                                    @error('district') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Passwords -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="reg_password" class="block text-xs font-bold text-gray-700 mb-1">
                                    Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <input type="password"
                                        id="reg_password"
                                        name="password"
                                        required
                                        class="form-input rounded-xl sm:rounded-2xl py-2.5 sm:py-3 pl-3.5 pr-10 text-xs text-gray-900 font-semibold w-full border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all"
                                        placeholder="Min. 8 karakter">
                                    <button type="button"
                                        onclick="togglePasswordVisibility('reg_password', this)"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer focus:outline-none">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                @error('password') <p class="form-error text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="reg_password_confirmation" class="block text-xs font-bold text-gray-700 mb-1">
                                    Konfirmasi Password <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <input type="password"
                                        id="reg_password_confirmation"
                                        name="password_confirmation"
                                        required
                                        class="form-input rounded-xl sm:rounded-2xl py-2.5 sm:py-3 pl-3.5 pr-10 text-xs text-gray-900 font-semibold w-full border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all"
                                        placeholder="Ulangi password">
                                    <button type="button"
                                        onclick="togglePasswordVisibility('reg_password_confirmation', this)"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer focus:outline-none">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn-gold w-full py-3 px-5 rounded-2xl text-xs font-black shadow-gold-glow transition-all flex items-center justify-center gap-2 hover:scale-[1.01]">
                                <i data-lucide="user-check" class="w-4 h-4"></i>
                                <span>Daftar Sebagai Warga Kukar</span>
                            </button>
                        </div>
                    </form>

                    <!-- Footer Switcher Link -->
                    <div class="mt-4 pt-3 text-center text-xs text-gray-500 border-t border-gray-100">
                        <span>Sudah memiliki akun warga?</span>
                        <button type="button"
                            onclick="switchAuthMode('login')"
                            class="font-extrabold text-brand-black hover:text-brand-gold-dark ml-1 underline decoration-brand-gold decoration-2">
                            Masuk di Sini
                        </button>
                    </div>
                </div>

                <!-- Bottom Copyright / Disclaimers -->
                <div class="text-center text-[11px] text-gray-400 pt-4 pb-2 border-t border-gray-100 hidden sm:block">
                    <span>&copy; {{ date('Y') }} Habar Etam • Kutai Kartanegara</span>
                </div>
            </div>
        </div>

        <!-- SLOT 2 (RIGHT 50%): LOGIN FORM -->
        <div id="loginSlot"
            data-lenis-prevent
            class="w-full lg:w-1/2 h-full min-h-0 overflow-y-auto overscroll-contain px-6 py-6 sm:px-10 lg:px-12 xl:px-16 {{ $currentMode === 'login' ? 'flex pointer-events-auto opacity-100' : 'hidden lg:flex pointer-events-none opacity-0' }} flex-col transition-opacity duration-300">

            <div class="max-w-md w-full mx-auto min-h-full flex flex-col justify-between py-2">
                <div>
                    <!-- Inner Brand & Mode Indicator for Login -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-brand-gold-dark"></i>
                            <span>Autentikasi Warga</span>
                        </div>
                        <span class="text-xs text-gray-400 font-semibold">Kutai Kartanegara</span>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-2xl sm:text-3xl font-black text-brand-black tracking-tight">Masuk ke Akun Anda</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Gunakan alamat email dan kata sandi akun warga yang telah terdaftar.</p>
                    </div>

                    @include('partials.alert')

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="login_email" class="block text-xs font-bold text-gray-700 mb-1.5">
                                Alamat Email Warga
                            </label>
                            <div class="relative">
                                <input type="email"
                                    id="login_email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="form-input rounded-2xl py-3 px-3.5 text-xs w-full border-gray-200 focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 bg-gray-50/50"
                                    placeholder="nama@email.com">
                            </div>
                            @error('email')
                            <p class="form-error text-xs text-rose-600 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="login_password" class="block text-xs font-bold text-gray-700">
                                    Kata Sandi
                                </label>
                            </div>
                            <div class="relative flex items-center">
                                <input type="password"
                                    id="login_password"
                                    name="password"
                                    required
                                    class="form-input rounded-xl sm:rounded-2xl py-3 pl-3.5 pr-10 text-xs text-gray-900 font-semibold w-full border-gray-200 bg-gray-50/50 hover:bg-white focus:bg-white focus:border-brand-gold focus:ring-4 focus:ring-brand-gold/15 transition-all"
                                    placeholder="••••••••">
                                <button type="button"
                                    onclick="togglePasswordVisibility('login_password', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 cursor-pointer focus:outline-none">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                            @error('password')
                            <p class="form-error text-xs text-rose-600 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="rounded-lg border-gray-300 text-brand-black focus:ring-brand-gold w-4 h-4">
                                <span class="text-gray-600 text-xs font-semibold">Ingat Saya di Perangkat Ini</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-3">
                            <button type="submit" class="btn-black w-full py-3.5 px-6 rounded-2xl text-xs font-black shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 hover:scale-[1.01]">
                                <i data-lucide="log-in" class="w-4 h-4 text-brand-gold"></i>
                                <span>Masuk ke Akun Sekarang</span>
                            </button>
                        </div>
                    </form>

                    <!-- Footer Switcher Link -->
                    <div class="mt-6 pt-4 text-center text-xs text-gray-500 border-t border-gray-100">
                        <span>Belum memiliki akun warga Kukar?</span>
                        <button type="button"
                            onclick="switchAuthMode('register')"
                            class="font-extrabold text-brand-black hover:text-brand-gold-dark ml-1 underline decoration-brand-gold decoration-2">
                            Daftar di Sini
                        </button>
                    </div>
                </div>

                <!-- Bottom Copyright / Disclaimers -->
                <div class="text-center text-[11px] text-gray-400 pt-4 pb-2 border-t border-gray-100 hidden sm:block">
                    <span>&copy; {{ date('Y') }} Habar Etam • Kutai Kartanegara</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        if (window.togglePasswordVisibility) {
            window.togglePasswordVisibility(inputId, btn);
            return;
        }
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const iconName = isPassword ? 'eye-off' : 'eye';
        if (btn) {
            btn.innerHTML = `<i data-lucide="${iconName}" class="w-4 h-4 ${isPassword ? 'text-amber-600' : 'text-gray-400'}"></i>`;
            if (window.initIcons) window.initIcons();
        }
    }

    function switchAuthMode(mode) {
        const authContainer = document.getElementById('authContainer');
        const slidingOverlay = document.getElementById('slidingOverlay');
        const overlayInner = document.getElementById('overlayInner');
        const registerSlot = document.getElementById('registerSlot');
        const loginSlot = document.getElementById('loginSlot');
        const mobBtnLogin = document.getElementById('mobBtnLogin');
        const mobBtnRegister = document.getElementById('mobBtnRegister');

        if (mode === 'register') {
            // Container state
            authContainer.classList.remove('mode-login');
            authContainer.classList.add('mode-register');

            // Desktop Slide Animation: Overlay moves smoothly to Right (covering login slot)
            if (slidingOverlay) {
                slidingOverlay.classList.remove('translate-x-0');
                slidingOverlay.classList.add('translate-x-full');
            }
            if (overlayInner) {
                overlayInner.classList.remove('translate-x-0');
                overlayInner.classList.add('-translate-x-1/2');
            }

            // Mobile & Desktop Slots toggle
            if (registerSlot) {
                registerSlot.classList.remove('hidden', 'pointer-events-none', 'opacity-0');
                registerSlot.classList.add('flex', 'pointer-events-auto', 'opacity-100');
            }
            if (loginSlot) {
                loginSlot.classList.remove('pointer-events-auto', 'opacity-100');
                loginSlot.classList.add('hidden', 'lg:flex', 'pointer-events-none', 'opacity-0');
            }
            if (mobBtnLogin) mobBtnLogin.className = 'py-1 px-3 rounded-lg text-xs font-bold transition-all flex items-center gap-1 text-gray-300';
            if (mobBtnRegister) mobBtnRegister.className = 'py-1 px-3 rounded-lg text-xs font-bold transition-all flex items-center gap-1 bg-brand-gold text-brand-black shadow-sm';

            // Push state without page reload
            window.history.pushState({
                mode: 'register'
            }, '', '/daftar');
            document.title = 'Pendaftaran Warga Kukar — Habar Etam';
        } else {
            // Container state
            authContainer.classList.remove('mode-register');
            authContainer.classList.add('mode-login');

            // Desktop Slide Animation: Overlay moves smoothly to Left (covering register slot)
            if (slidingOverlay) {
                slidingOverlay.classList.remove('translate-x-full');
                slidingOverlay.classList.add('translate-x-0');
            }
            if (overlayInner) {
                overlayInner.classList.remove('-translate-x-1/2');
                overlayInner.classList.add('translate-x-0');
            }

            // Mobile & Desktop Slots toggle
            if (loginSlot) {
                loginSlot.classList.remove('hidden', 'pointer-events-none', 'opacity-0');
                loginSlot.classList.add('flex', 'pointer-events-auto', 'opacity-100');
            }
            if (registerSlot) {
                registerSlot.classList.remove('pointer-events-auto', 'opacity-100');
                registerSlot.classList.add('hidden', 'lg:flex', 'pointer-events-none', 'opacity-0');
            }
            if (mobBtnLogin) mobBtnLogin.className = 'py-1 px-3 rounded-lg text-xs font-bold transition-all flex items-center gap-1 bg-brand-gold text-brand-black shadow-sm';
            if (mobBtnRegister) mobBtnRegister.className = 'py-1 px-3 rounded-lg text-xs font-bold transition-all flex items-center gap-1 text-gray-300';

            // Push state without page reload
            window.history.pushState({
                mode: 'login'
            }, '', '/masuk');
            document.title = 'Masuk Akun Warga — Habar Etam';
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const initial = "{{ $currentMode }}";
        if (initial === 'register') {
            switchAuthMode('register');
        } else {
            switchAuthMode('login');
        }

        window.addEventListener('popstate', () => {
            if (window.location.pathname.includes('daftar')) {
                switchAuthMode('register');
            } else {
                switchAuthMode('login');
            }
        });

        if (window.lucide) {
            window.lucide.createIcons();
        }
    });
</script>
@endpush