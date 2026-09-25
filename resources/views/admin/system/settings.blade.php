@extends('layouts.admin')

@section('title', 'Pengaturan Operasional Sistem - Admin Habar Etam')
@section('page_title', 'Konfigurasi Sistem & Pengaturan Redaksi')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'general' }">

    <!-- 1. Hero Header & Quick Server Status -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 p-6 sm:p-8 text-white border border-gray-800 shadow-xl">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-brand-gold/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-brand-gold/20 text-brand-gold border border-brand-gold/30">
                        <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                        Pusat Konfigurasi Platform
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-emerald-300 border border-white/15">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        {{ $systemInfo['app_env'] }} &bull; PHP {{ $systemInfo['php_version'] }}
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Pengaturan & Parameter Operasional
                </h2>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    Kelola identitas publik platform Habar Etam, nomor hotline pengaduan redaksi, integrasi live broadcast studio PT SCM, serta parameter keamanan sistem.
                </p>
            </div>

            <!-- Quick Maintenance / Cache Action -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST" onsubmit="return confirm('Bersihkan seluruh cache aplikasi dan view terkompilasi?')">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all border border-white/15 backdrop-blur-md inline-flex items-center gap-2 cursor-pointer shadow-xs active:scale-95">
                        <i data-lucide="refresh-cw" class="w-4 h-4 text-brand-gold"></i>
                        <span>Bersihkan Cache</span>
                    </button>
                </form>

                <a href="{{ route('admin.audit.index') }}" class="px-4 py-2.5 rounded-2xl bg-brand-gold hover:bg-yellow-400 text-brand-black text-xs font-black transition-all inline-flex items-center gap-2 cursor-pointer shadow-xs shadow-brand-gold/20 active:scale-95">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Log Audit Sistem</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Settings Container with Tabs -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
        
        <!-- Tab Navigation Bar -->
        <div class="border-b border-gray-100 bg-gray-50/70 p-3.5 sm:p-4 flex items-center gap-2 overflow-x-auto scrollbar-none">
            <button type="button" 
                    @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'bg-gray-950 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60 font-medium'"
                    class="px-4 py-2.5 rounded-2xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="layout" class="w-4 h-4"></i>
                <span>Identitas & Metadata</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'contact'" 
                    :class="activeTab === 'contact' ? 'bg-gray-950 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60 font-medium'"
                    class="px-4 py-2.5 rounded-2xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>Hotline & Kontak Redaksi</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'studio'" 
                    :class="activeTab === 'studio' ? 'bg-gray-950 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60 font-medium'"
                    class="px-4 py-2.5 rounded-2xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="tv" class="w-4 h-4"></i>
                <span>Studio & Broadcaster SCM</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'policy'" 
                    :class="activeTab === 'policy' ? 'bg-gray-950 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60 font-medium'"
                    class="px-4 py-2.5 rounded-2xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="lock" class="w-4 h-4"></i>
                <span>Kebijakan & Keamanan</span>
            </button>

            <button type="button" 
                    @click="activeTab = 'diagnostics'" 
                    :class="activeTab === 'diagnostics' ? 'bg-gray-950 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60 font-medium'"
                    class="px-4 py-2.5 rounded-2xl text-xs whitespace-nowrap transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="cpu" class="w-4 h-4"></i>
                <span>Diagnostik Server</span>
            </button>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- TAB 1: IDENTITAS & METADATA -->
            <div x-show="activeTab === 'general'" x-cloak class="space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Identitas Publik Platform</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Nama situs, tagline promosi, dan deskripsi SEO publik platform Habar Etam.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Site Name -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="globe" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Nama Situs / Platform</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="globe" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="text" 
                                   name="site_name" 
                                   required 
                                   value="{{ old('site_name', $settings['site_name'] ?? 'Habar Etam') }}" 
                                   placeholder="Contoh: Habar Etam"
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                        <p class="text-[11px] text-gray-400">Ditampilkan pada judul browser, header portal, dan email notifikasi.</p>
                    </div>

                    <!-- Site Tagline -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Tagline Resmi Platform</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="sparkles" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="text" 
                                   name="site_tagline" 
                                   required 
                                   value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Pusat Informasi & Wadah Aspirasi Warga Kutai Kartanegara') }}" 
                                   placeholder="Slogan resmi platform..."
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                        <p class="text-[11px] text-gray-400">Slogan resmi yang menggambarkan visi platform di Kabupaten Kutai Kartanegara.</p>
                    </div>

                    <!-- Site Description -->
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Deskripsi Ringkas SEO (Meta Description)</span>
                        </label>
                        <div class="flex items-start rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 pt-3 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="file-text" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <textarea name="site_description" 
                                      rows="3" 
                                      placeholder="Deskripsi singkat portal untuk mesin pencari Google dan OpenGraph sosial media..."
                                      class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-medium text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0 resize-y">{{ old('site_description', $settings['site_description'] ?? 'Platform terintegrasi Smart City & Lapor Etam Kabupaten Kutai Kartanegara yang menghadirkan harga pangan pasar, lowongan kerja, agenda budaya, serta aduan warga langsung ditindaklanjuti.') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: HOTLINE & KONTAK REDAKSI -->
            <div x-show="activeTab === 'contact'" x-cloak class="space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Kontak Resmi & Alamat Kantor Redaksi</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Informasi saluran komunikasi langsung antara warga dan tim redaksi Habar Etam.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Email Resmi Redaksi</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="mail" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="email" 
                                   name="contact_email" 
                                   required 
                                   value="{{ old('contact_email', $settings['contact_email'] ?? 'redaksi@habaretam.id') }}" 
                                   placeholder="redaksi@habaretam.id"
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Telepon Kantor Redaksi</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="phone" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="text" 
                                   name="contact_phone" 
                                   required 
                                   value="{{ old('contact_phone', $settings['contact_phone'] ?? '(0541) 661-098') }}" 
                                   placeholder="(0541) 661-098"
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-500"></i>
                            <span>WhatsApp Hotline Pengaduan</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-emerald-500 shrink-0">
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                            </div>
                            <input type="text" 
                                   name="contact_whatsapp" 
                                   required 
                                   value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '0811-5800-888') }}" 
                                   placeholder="0811-5800-888"
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Alamat Fisik Kantor Redaksi</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-start rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 pt-3 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <textarea name="office_address" 
                                      rows="3" 
                                      required 
                                      placeholder="Alamat lengkap gedung kantor redaksi..."
                                      class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-medium text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0 resize-y">{{ old('office_address', $settings['office_address'] ?? "Gedung Pusat Informasi SCM, Jl. Jenderal Sudirman No. 12, Tenggarong, Kutai Kartanegara, Kalimantan Timur 75511") }}</textarea>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Jam Operasional Layanan</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="text" 
                                   name="office_hours" 
                                   value="{{ old('office_hours', $settings['office_hours'] ?? 'Senin - Jumat: 08:00 - 16:30 WITA') }}" 
                                   placeholder="Contoh: Senin - Jumat: 08:00 - 16:30 WITA"
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                        <p class="text-[11px] text-gray-400">Jam pelayanan tiket aduan dan kantor sekretariat.</p>
                    </div>
                </div>
            </div>

            <!-- TAB 3: STUDIO SIARAN & TELEPROMPTER SCM -->
            <div x-show="activeTab === 'studio'" x-cloak class="space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Integrasi Studio Broadcast PT SCM</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Parameter rundown tayang live, teleprompter siaran, dan teks pengumuman berjalan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Partner Name -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="tv" class="w-3.5 h-3.5 text-purple-500"></i>
                            <span>Nama Broadcaster / Studio Partner</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-purple-500 shrink-0">
                                <i data-lucide="tv" class="w-4 h-4"></i>
                            </div>
                            <input type="text" 
                                   name="studio_partner_name" 
                                   value="{{ old('studio_partner_name', $settings['studio_partner_name'] ?? 'PT Surya Citra Media (SCM) Studio Kukar') }}" 
                                   placeholder="Nama studio rekanan..."
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0">
                        </div>
                        <p class="text-[11px] text-gray-400">Tertera pada header Teleprompter dan banner siaran live.</p>
                    </div>

                    <!-- WPM -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="gauge" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Kecepatan Baca Standar (Words Per Minute / WPM)</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-blue-500 shrink-0">
                                <i data-lucide="gauge" class="w-4 h-4"></i>
                            </div>
                            <input type="number" 
                                   name="studio_reading_wpm" 
                                   min="80" 
                                   max="250" 
                                   value="{{ old('studio_reading_wpm', $settings['studio_reading_wpm'] ?? 130) }}" 
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 border-0 focus:outline-none focus:ring-0">
                        </div>
                        <p class="text-[11px] text-gray-400">Standar penyiar TV Indonesia rata-rata 120 - 140 WPM.</p>
                    </div>

                    <!-- Announcement Ticker -->
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="radio" class="w-3.5 h-3.5 text-rose-500"></i>
                            <span>Pengumuman Ticker Berjalan (Broadcast Announcement)</span>
                        </label>
                        <div class="flex items-start rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 pt-3 flex items-center justify-center text-rose-500 shrink-0">
                                <i data-lucide="radio" class="w-4 h-4"></i>
                            </div>
                            <textarea name="studio_ticker_announcement" 
                                      rows="2" 
                                      placeholder="Teks berjalan untuk siaran darurat atau pengumuman penting redaksi..."
                                      class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-medium text-gray-900 placeholder:text-gray-400 border-0 focus:outline-none focus:ring-0 resize-y">{{ old('studio_ticker_announcement', $settings['studio_ticker_announcement'] ?? 'SIARAN LANGSUNG: Pemantauan Ketinggian Sungai Mahakam dan Stabilisasi Harga Pangan Pasar Induk Tangga Arung Tenggarong.') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: KEBIJAKAN & KEAMANAN -->
            <div x-show="activeTab === 'policy'" x-cloak class="space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Kebijakan Operasional & Limitasi Platform</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Konfigurasi batas waktu penanganan aduan, kapasitas berkas, dan moderasi warga.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Max Upload -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="file-up" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Batas Maksimum Ukuran Upload Berkas (MB)</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="file-up" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="number" 
                                   name="max_upload_size_mb" 
                                   min="1" 
                                   max="50" 
                                   value="{{ old('max_upload_size_mb', $settings['max_upload_size_mb'] ?? 10) }}" 
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 border-0 focus:outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- SLA Days -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-brand-gold"></i>
                            <span>Target Waktu Penyelesaian Aduan (Hari)</span>
                        </label>
                        <div class="flex items-center rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-white focus-within:bg-white focus-within:border-brand-gold focus-within:ring-2 focus-within:ring-brand-gold/30 transition-all shadow-2xs">
                            <div class="pl-3.5 pr-2 flex items-center justify-center text-gray-400 shrink-0">
                                <i data-lucide="calendar-check" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <input type="number" 
                                   name="auto_resolve_days" 
                                   min="1" 
                                   max="90" 
                                   value="{{ old('auto_resolve_days', $settings['auto_resolve_days'] ?? 14) }}" 
                                   class="w-full bg-transparent py-2.5 pr-3.5 pl-1 text-xs font-semibold text-gray-900 border-0 focus:outline-none focus:ring-0">
                        </div>
                        <p class="text-[11px] text-gray-400">Standar Service Level Agreement (SLA) tindak lanjut Lapor Etam.</p>
                    </div>

                    <!-- Public UGC Toggle -->
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                        <div class="space-y-0.5">
                            <span class="block text-xs font-bold text-gray-900">Izinkan Partisipasi Konten Warga (UGC)</span>
                            <span class="block text-[11px] text-gray-500">Warga terdaftar dapat mengirimkan bursa kerja & listing jual cepat.</span>
                        </div>
                        <input type="hidden" name="allow_public_ugc" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_public_ugc" value="1" {{ ($settings['allow_public_ugc'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-gold"></div>
                        </label>
                    </div>

                    <!-- NIK Verification Toggle -->
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                        <div class="space-y-0.5">
                            <span class="block text-xs font-bold text-gray-900">Wajibkan Verifikasi NIK untuk Lapor Etam</span>
                            <span class="block text-[11px] text-gray-500">Hanya akun dengan NIK terverifikasi yang dapat membuat tiket aduan resmi.</span>
                        </div>
                        <input type="hidden" name="require_nik_verification" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="require_nik_verification" value="1" {{ ($settings['require_nik_verification'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-gold"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- TAB 5: DIAGNOSTIK SERVER -->
            <div x-show="activeTab === 'diagnostics'" x-cloak class="space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Spesifikasi Server & Lingkungan Eksekusi</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Informasi teknis runtime PHP, database, driver cache, dan konfigurasi memori.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Versi PHP</span>
                        <p class="text-sm font-bold text-gray-900">{{ $systemInfo['php_version'] }} ({{ $systemInfo['server_os'] }})</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Versi Laravel Framework</span>
                        <p class="text-sm font-bold text-gray-900">v{{ $systemInfo['laravel_version'] }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Zona Waktu Aplikasi</span>
                        <p class="text-sm font-bold text-gray-900">{{ $systemInfo['app_timezone'] }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Koneksi Database</span>
                        <p class="text-sm font-bold text-gray-900">{{ strtoupper($systemInfo['database_driver']) }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Driver Cache & Session</span>
                        <p class="text-sm font-bold text-gray-900">{{ ucfirst($systemInfo['cache_driver']) }} / {{ ucfirst($systemInfo['session_driver']) }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Batas Memori PHP</span>
                        <p class="text-sm font-bold text-gray-900">{{ $systemInfo['memory_limit'] }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Upload Max Filesize</span>
                        <p class="text-sm font-bold text-gray-900">{{ $systemInfo['upload_max_filesize'] }} (Post: {{ $systemInfo['post_max_size'] }})</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Max Execution Time</span>
                        <p class="text-sm font-bold text-gray-900">{{ $systemInfo['max_execution_time'] }}</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Mode Debugging</span>
                        <p class="text-sm font-bold text-gray-900">{{ $systemInfo['app_debug'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Save Action Button Footer -->
            <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-gray-400 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-brand-gold"></i>
                    <span>Perubahan konfigurasi akan langsung tersimpan dan tercatat di Audit Log sistem.</span>
                </span>

                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 rounded-2xl bg-brand-gold text-brand-black hover:bg-yellow-400 font-black text-xs transition-all shadow-xs shadow-brand-gold/30 inline-flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Simpan Seluruh Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
