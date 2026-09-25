@extends('layouts.admin')

@section('title', 'Tambah Kontak Darurat — Smart City Kukar')
@section('page_title', 'Smart City: Tambah Kontak Darurat')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-12">
    
    <!-- 1. Top Hero Banner -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-gray-950 via-gray-900 to-black text-white p-6 sm:p-7 rounded-3xl shadow-lg relative overflow-hidden border border-white/5">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-64 -top-12 w-48 h-48 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-gold transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-gray-400">Smart City</span>
                <span>/</span>
                <a href="{{ route('admin.smart-city.emergency.index') }}" class="hover:text-brand-gold transition-colors">Kontak Darurat</a>
                <span>/</span>
                <span class="text-brand-gold font-semibold">Tambah Kontak Baru</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2.5">
                    <span class="p-2 rounded-2xl bg-amber-500/20 border border-amber-500/30 text-brand-gold">
                        <i data-lucide="phone-plus" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </span>
                    <span>Tambah Kontak Kedaruratan Baru</span>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-300 max-w-2xl leading-relaxed">
                Daftarkan posko atau nomor darurat baru agar langsung terintegrasi dan dapat dihubungi oleh warga Kutai Kartanegara.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-2.5 self-start lg:self-center">
            <a href="{{ route('admin.smart-city.emergency.index') }}" 
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
        <form action="{{ route('admin.smart-city.emergency.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              x-data="{ 
                  selectedCategory: '{{ old('category', 'damkar') }}',
                  imagePreview: null,
                  fileName: '',
                  fileSize: '',
                  isDragging: false,
                  handleFile(file) {
                      if (!file || !file.type.startsWith('image/')) return;
                      this.fileName = file.name;
                      this.fileSize = (file.size / 1024).toFixed(1) + ' KB';
                      if (file.size > 1024 * 1024) {
                          this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                      }
                      const reader = new FileReader();
                      reader.onload = (e) => { this.imagePreview = e.target.result; };
                      reader.readAsDataURL(file);
                  },
                  clearImage() {
                      this.imagePreview = null;
                      this.fileName = '';
                      this.fileSize = '';
                      if (this.$refs.imageInput) this.$refs.imageInput.value = '';
                  }
              }" 
              class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Utama Posko -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-amber-50 text-amber-600">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Identitas Instansi & Layanan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Instansi -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Nama Instansi / Unit Posko Layanan <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-gray-400 font-normal">Wajib diisi</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Contoh: Pemadam Kebakaran (Damkar) Posko Loa Janan" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Kategori Instansi -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-700">Kategori Instansi <span class="text-rose-500">*</span></label>
                            <button type="button" 
                                    @click="
                                        selectedCategory = 'lainnya'; 
                                        const sel = $el.closest('form').querySelector('select[name=category]');
                                        if (sel) {
                                            sel.value = 'lainnya';
                                            sel.dispatchEvent(new Event('change', { bubbles: true }));
                                        }
                                        $nextTick(() => $refs.customCategoryInput?.focus());
                                    "
                                    class="text-[11px] font-bold text-amber-700 hover:text-amber-800 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-amber-600"></i>
                                <span>+ Input Kategori Baru</span>
                            </button>
                        </div>
                        <select name="category" 
                                x-model="selectedCategory" 
                                @change="selectedCategory = $event.target.value; if(selectedCategory === 'lainnya') { $nextTick(() => { $refs.customCategoryInput?.focus(); }); }"
                                data-search-limit="50"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            <option value="damkar">Damkar (Pemadam Kebakaran & Evakuasi)</option>
                            <option value="polisi">Kepolisian (Polres / Polsek / Call 110)</option>
                            <option value="rumah_sakit">Rumah Sakit / IGD RSUD</option>
                            <option value="ambulans">Layanan Ambulans Gawat Darurat</option>
                            <option value="puskesmas">Puskesmas Kecamatan (UGD Pratama)</option>
                            <option value="sar_bpbd">BPBD & SAR (Posko Siaga Bencana)</option>
                            <option value="pln">PLN (Gangguan Listrik & Trafo)</option>
                            <option value="pdam">PDAM Tirta Mahakam (Kebocoran Pipa)</option>
                            <option value="lainnya">Lainnya (Input Kategori Sendiri)</option>
                        </select>
                    </div>

                    <!-- Kecamatan Wilayah -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Kecamatan Cakupan Wilayah <span class="text-rose-500">*</span></label>
                        <select name="location_district" 
                                data-search-limit="50"
                                required 
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm bg-white focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                            @foreach($districts as $dst)
                                <option value="{{ $dst }}" {{ (old('location_district', 'Tenggarong') === $dst) ? 'selected' : '' }}>
                                    Kec. {{ $dst }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Custom Category Input (When Lainnya selected) -->
                    <div x-show="selectedCategory === 'lainnya'" 
                         x-transition 
                         class="md:col-span-2 space-y-2 p-4 sm:p-5 rounded-2xl bg-amber-50/90 border border-amber-300 shadow-xs">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-amber-950 flex items-center gap-1.5">
                                <i data-lucide="tag" class="w-4 h-4 text-amber-600"></i>
                                <span>Nama Kategori Instansi Sendiri (Kustom) <span class="text-rose-500">*</span></span>
                            </label>
                            <button type="button" 
                                    @click="
                                        selectedCategory = 'damkar';
                                        const sel = $el.closest('form').querySelector('select[name=category]');
                                        if (sel) {
                                            sel.value = 'damkar';
                                            sel.dispatchEvent(new Event('change', { bubbles: true }));
                                        }
                                    "
                                    class="text-[11px] font-bold text-gray-500 hover:text-gray-800 hover:underline inline-flex items-center gap-1 cursor-pointer">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                <span>Gunakan Kategori Pilihan</span>
                            </button>
                        </div>
                        <input type="text" 
                               name="custom_category" 
                               x-ref="customCategoryInput"
                               value="{{ old('custom_category') }}" 
                               placeholder="Ketik nama kategori baru (contoh: Relawan Bencana, Derek Mobil, Dishub)..." 
                               class="w-full px-4 py-3 rounded-2xl border border-amber-300 bg-white text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                        <p class="text-[11px] text-amber-800 font-medium">Kategori kustom ini akan otomatis tersimpan dan menjadi kategori resmi kontak ini.</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Foto / Logo Posko Instansi -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-indigo-50 text-indigo-600">
                        <i data-lucide="image" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Foto / Logo Posko Kedaruratan</h3>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-gray-700">Foto Posko / Gedung / Logo Instansi (Opsional)</label>
                        <span class="text-[11px] text-gray-400">Format: JPG, PNG, WEBP (Maksimal 5 MB)</span>
                    </div>

                    <!-- Hidden Real File Input -->
                    <input type="file" 
                           name="image" 
                           x-ref="imageInput" 
                           accept="image/png,image/jpeg,image/webp,image/jpg" 
                           @change="handleFile($event.target.files[0])" 
                           class="hidden">

                    <!-- Dropzone Container -->
                    <div @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="isDragging = false; if ($event.dataTransfer.files.length) { $refs.imageInput.files = $event.dataTransfer.files; handleFile($event.dataTransfer.files[0]); }"
                         :class="{ 'border-brand-gold bg-amber-50/50 ring-2 ring-brand-gold/30': isDragging, 'border-gray-200 bg-gray-50/60 hover:bg-gray-50 hover:border-gray-300': !isDragging }"
                         class="border-2 border-dashed rounded-3xl p-6 transition-all">
                        
                        <!-- Empty State: Dropzone Click Area -->
                        <div x-show="!imagePreview" class="text-center py-4 space-y-3">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-white border border-gray-200 shadow-xs flex items-center justify-center text-gray-400">
                                <i data-lucide="upload-cloud" class="w-7 h-7 text-brand-gold"></i>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs sm:text-sm font-bold text-gray-800">
                                    Tarik & lepaskan foto ke sini, atau 
                                    <button type="button" @click="$refs.imageInput.click()" class="text-amber-700 hover:text-amber-800 underline font-black cursor-pointer">
                                        Pilih File dari Komputer
                                    </button>
                                </p>
                                <p class="text-[11px] text-gray-400">Disarankan foto rasio 16:9 atau 4:3 dengan resolusi yang jelas.</p>
                            </div>
                        </div>

                        <!-- Live Preview State -->
                        <div x-show="imagePreview" x-cloak class="flex flex-col sm:flex-row items-center gap-5 p-2">
                            <div class="relative group shrink-0">
                                <img :src="imagePreview" 
                                     alt="Preview Foto" 
                                     class="w-32 h-24 sm:w-40 sm:h-28 object-cover rounded-2xl border border-gray-200 shadow-md">
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-black/70 backdrop-blur-sm text-[10px] font-bold text-white">
                                    Pratinjau Baru
                                </span>
                            </div>

                            <div class="flex-1 space-y-1.5 text-center sm:text-left">
                                <div class="flex items-center justify-center sm:justify-start gap-2">
                                    <i data-lucide="file-check-2" class="w-4 h-4 text-emerald-600"></i>
                                    <span class="text-xs font-bold text-gray-900 truncate max-w-xs" x-text="fileName"></span>
                                </div>
                                <p class="text-[11px] text-gray-500">Ukuran file: <span class="font-bold text-gray-700" x-text="fileSize"></span></p>
                                <p class="text-[11px] text-emerald-700 font-medium">Foto siap diunggah dan akan disimpan saat form dikirimkan.</p>

                                <div class="pt-2 flex items-center justify-center sm:justify-start gap-2">
                                    <button type="button" 
                                            @click="$refs.imageInput.click()" 
                                            class="h-8 px-3 rounded-xl bg-white border border-gray-200 hover:bg-gray-100 text-[11px] font-bold text-gray-700 transition-colors inline-flex items-center gap-1.5 cursor-pointer shadow-2xs">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                        <span>Ganti Foto</span>
                                    </button>
                                    <button type="button" 
                                            @click="clearImage()" 
                                            class="h-8 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold text-rose-700 transition-colors inline-flex items-center gap-1.5 cursor-pointer">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Section 3: Nomor Kontak Kedaruratan -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="phone-call" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Saluran Komunikasi Hotline</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nomor Telepon Hotline -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Nomor Telepon Hotline Cepat <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-gray-400 font-normal">Dapat berupa kode 110/112 atau nomor telepon kantor</span>
                        </label>
                        <input type="text" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required 
                               placeholder="Contoh: 112 / 110 / (0541) 661xxx" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-mono font-bold transition-all shadow-2xs">
                    </div>

                    <!-- Nomor WhatsApp Siaga -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 flex items-center justify-between">
                            <span>Nomor WhatsApp Siaga (Opsional)</span>
                            <span class="text-[11px] text-teal-600 font-bold">Terhubung tombol chat langsung</span>
                        </label>
                        <input type="text" 
                               name="whatsapp" 
                               value="{{ old('whatsapp') }}" 
                               placeholder="Contoh: 081255889110 atau 62812..." 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-mono font-medium transition-all shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section 4: Lokasi & Uraian Tugas -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-emerald-50 text-emerald-600">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Lokasi Kantor & Penanganan</h3>
                </div>

                <div class="space-y-4">
                    <!-- Alamat Lengkap -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Alamat Lengkap / Patokan Gedung Posko</label>
                        <input type="text" 
                               name="address" 
                               value="{{ old('address') }}" 
                               placeholder="Contoh: Jl. Wolter Monginsidi No. 84, Tenggarong (Sebelah Kantor Pos)" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs">
                    </div>

                    <!-- Uraian Layanan -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Uraian & Jenis Penanganan Kedaruratan</label>
                        <textarea name="description" 
                                  rows="3" 
                                  placeholder="Contoh: Melayani evakuasi darurat, kebakaran pemukiman/lahan, penyelamatan pohon tumbang, posko siaga 24 jam." 
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-medium transition-all shadow-2xs leading-relaxed">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 5: Pengaturan Tayang & Urutan -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <span class="p-1.5 rounded-xl bg-purple-50 text-purple-600">
                        <i data-lucide="sliders" class="w-4 h-4"></i>
                    </span>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Pengaturan Publikasi</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">
                    <!-- Urutan Tampil -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Nomor Urutan Tampil (Sort Order)</label>
                        <input type="number" 
                               name="sort_order" 
                               value="{{ old('sort_order', 0) }}" 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-brand-gold/30 focus:border-brand-gold outline-none font-mono font-bold transition-all shadow-2xs">
                        <p class="text-[11px] text-gray-400">Angka lebih kecil (0, 1, 2) akan tampil di posisi teratas.</p>
                    </div>

                    <!-- Status Aktif Tayang Switch -->
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gray-900 block">Status Publikasi Publik</span>
                            <span class="text-[11px] text-gray-500 block">Tampilkan kontak ini di halaman portal warga</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.smart-city.emergency.index') }}" 
                   class="w-full sm:w-auto h-11 px-6 rounded-2xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                    Batal & Kembali
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto h-11 px-7 bg-gradient-to-r from-brand-gold to-amber-500 hover:from-amber-400 hover:to-brand-gold text-brand-black text-xs font-black rounded-2xl shadow-lg shadow-brand-gold/25 transition-all inline-flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Kontak Darurat</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
