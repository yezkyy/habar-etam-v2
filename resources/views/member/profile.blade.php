@extends('layouts.public')

@section('title', 'Profil Warga & Kontribusi — Habar Etam')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @include('partials.alert')

    <!-- Profile Header Banner & Verification Card -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8 shadow-subtle">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            
            <div class="flex items-start sm:items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-brand-black text-brand-gold font-extrabold text-2xl flex items-center justify-center flex-shrink-0 border-2 border-brand-gold">
                    @if($user->profile && $user->profile->avatar)
                        <img src="{{ Storage::url($user->profile->avatar) }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover">
                    @else
                        {{ substr($user->name, 0, 1) }}
                    @endif
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-bold text-brand-black">{{ $user->name }}</h1>
                        @if($user->isVerified())
                            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <span>Warga Terverifikasi</span>
                            </span>
                        @elseif($user->isPending())
                            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                <span>Menunggu Verifikasi Redaksi</span>
                            </span>
                        @elseif($user->status === 'rejected')
                            <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                                <span>Verifikasi Ditolak</span>
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1 flex flex-wrap items-center gap-3">
                        <span><i data-lucide="mail" class="w-3.5 h-3.5 inline mr-1 text-gray-400"></i>{{ $user->email }}</span>
                        <span><i data-lucide="phone" class="w-3.5 h-3.5 inline mr-1 text-gray-400"></i>{{ $user->phone }}</span>
                        <span><i data-lucide="map-pin" class="w-3.5 h-3.5 inline mr-1 text-gray-400"></i>{{ $user->profile->district ?? 'Kutai Kartanegara' }}</span>
                    </p>
                </div>
            </div>

            <!-- Verification & NIK Details Box -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs md:text-right">
                <div class="text-gray-500 text-[11px]">Identitas Kependudukan (NIK Terenkripsi)</div>
                <div class="font-mono font-bold text-brand-black text-sm mt-0.5">
                    {{ $user->verification ? $user->verification->masked_nik : 'Belum Terdaftar' }}
                </div>
                <div class="text-[10px] text-gray-400 mt-1">
                    Wilayah: {{ $user->profile->district ?? 'Kukar' }} • Terdaftar sejak {{ $user->created_at->format('d M Y') }}
                </div>
            </div>

        </div>

        @if($user->isPending())
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-md text-xs text-amber-900 flex items-start space-x-2">
                <i data-lucide="info" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <strong>Pemberitahuan:</strong> Akun Anda sedang dalam antrean verifikasi data NIK oleh staf redaksi Habar Etam. Anda sudah dapat memasang listing Jual Cepat dan UGC lainnya. Pengaduan Lapor Etam akan aktif setelah status terverifikasi.
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Contribution Buttons Grid -->
    <div class="mb-8">
        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Pusat Aksi & Kontribusi Warga</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('quick-sales.create') }}" class="p-4 bg-white border border-gray-200 hover:border-brand-gold hover:shadow-subtle rounded-lg text-center transition-all group">
                <i data-lucide="tag" class="w-6 h-6 mx-auto mb-2 text-brand-gold-dark group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-bold text-brand-black">Pasang Jual Cepat</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Jual Kilat Barang</div>
            </a>
            <a href="{{ route('reports.create') }}" class="p-4 bg-red-50/50 border border-red-200 hover:border-red-400 hover:shadow-subtle rounded-lg text-center transition-all group">
                <i data-lucide="alert-octagon" class="w-6 h-6 mx-auto mb-2 text-red-600 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-bold text-red-900">Kirim Pengaduan</div>
                <div class="text-[10px] text-red-600 mt-0.5">Lapor Etam</div>
            </a>
            <a href="{{ route('jobs.create') }}" class="p-4 bg-white border border-gray-200 hover:border-brand-gold hover:shadow-subtle rounded-lg text-center transition-all group">
                <i data-lucide="briefcase" class="w-6 h-6 mx-auto mb-2 text-blue-600 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-bold text-brand-black">Pasang Loker</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Bursa Kerja Lokal</div>
            </a>
            <a href="{{ route('businesses.create') }}" class="p-4 bg-white border border-gray-200 hover:border-brand-gold hover:shadow-subtle rounded-lg text-center transition-all group">
                <i data-lucide="store" class="w-6 h-6 mx-auto mb-2 text-emerald-600 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-bold text-brand-black">Daftar Usaha / UMKM</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Produk & Jasa</div>
            </a>
            <a href="{{ route('culinary.create') }}" class="p-4 bg-white border border-gray-200 hover:border-brand-gold hover:shadow-subtle rounded-lg text-center transition-all group">
                <i data-lucide="utensils" class="w-6 h-6 mx-auto mb-2 text-amber-600 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-bold text-brand-black">Info Kuliner</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Tempat Makan Khas</div>
            </a>
            <a href="{{ route('events.create') }}" class="p-4 bg-white border border-gray-200 hover:border-brand-gold hover:shadow-subtle rounded-lg text-center transition-all group">
                <i data-lucide="calendar" class="w-6 h-6 mx-auto mb-2 text-purple-600 group-hover:scale-110 transition-transform"></i>
                <div class="text-xs font-bold text-brand-black">Buat Event</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Agenda Kegiatan</div>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: My Submissions & Grievances -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- My Quick Sales Section -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-subtle">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="tag" class="w-5 h-5 text-brand-gold-dark"></i>
                        <h2 class="text-base font-bold text-brand-black">Listing Jual Cepat Saya ({{ $quickSaleCount }})</h2>
                    </div>
                    <a href="{{ route('quick-sales.create') }}" class="text-xs font-bold text-brand-black hover:text-brand-gold-dark underline">
                        + Tambah Barang
                    </a>
                </div>

                @if($recentQuickSales->isEmpty())
                    <div class="text-center py-6 text-xs text-gray-500">
                        Belum ada barang jual cepat yang Anda daftarkan.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentQuickSales as $qs)
                            <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-12 h-12 bg-gray-100 rounded flex-shrink-0 overflow-hidden flex items-center justify-center">
                                        @if($qs->primaryMedia())
                                            <img src="{{ Storage::url($qs->primaryMedia()->path) }}" alt="{{ $qs->title }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div class="truncate">
                                        <a href="{{ route('quick-sales.show', $qs->slug) }}" class="text-xs font-bold text-brand-black hover:underline truncate block">
                                            {{ $qs->title }}
                                        </a>
                                        <div class="text-[11px] text-gray-500 font-semibold">{{ $qs->formatted_price }} • <span class="capitalize">{{ $qs->condition }}</span></div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <span class="text-[10px] px-2 py-0.5 rounded font-medium {{ $qs->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($qs->status === 'sold' ? 'bg-gray-100 text-gray-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ strtoupper($qs->status) }}
                                    </span>
                                    <a href="{{ route('quick-sales.edit', $qs->id) }}" class="p-1.5 text-gray-400 hover:text-brand-black" title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- My Lapor Etam Reports Section -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-subtle">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="alert-octagon" class="w-5 h-5 text-red-600"></i>
                        <h2 class="text-base font-bold text-brand-black">Pengaduan Lapor Etam Saya ({{ $reportCount }})</h2>
                    </div>
                    <a href="{{ route('reports.create') }}" class="text-xs font-bold text-red-700 hover:underline">
                        + Buat Pengaduan
                    </a>
                </div>

                @if($recentReports->isEmpty())
                    <div class="text-center py-6 text-xs text-gray-500">
                        Belum ada laporan pengaduan yang Anda ajukan.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentReports as $rep)
                            <div class="p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-xs font-bold text-brand-gold-dark">{{ $rep->ticket_number }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded font-semibold bg-gray-100 text-gray-800">
                                        {{ $rep->status_label }}
                                    </span>
                                </div>
                                <a href="{{ route('reports.show', $rep->ticket_number) }}" class="text-xs font-bold text-brand-black hover:underline block mt-1">
                                    {{ $rep->title }}
                                </a>
                                <p class="text-[11px] text-gray-500 mt-1 line-clamp-1">{{ $rep->address }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 1 Col: Edit Profile Settings -->
        <div>
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-subtle">
                <h3 class="text-sm font-bold text-brand-black mb-4 flex items-center space-x-2">
                    <i data-lucide="settings" class="w-4 h-4 text-gray-600"></i>
                    <span>Edit Profil Warga</span>
                </h3>

                <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="space-y-3 text-xs">
                    @csrf

                    <div>
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input text-xs">
                    </div>

                    <div>
                        <label for="phone" class="form-label">Nomor WhatsApp / HP</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="form-input text-xs">
                    </div>

                    <div>
                        <label for="display_name" class="form-label">Nama Panggilan / Alias</label>
                        <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $user->profile->display_name ?? '') }}" class="form-input text-xs">
                    </div>

                    <div>
                        <label for="district" class="form-label">Kecamatan Domisili</label>
                        <input type="text" id="district" name="district" value="{{ old('district', $user->profile->district ?? '') }}" class="form-input text-xs" placeholder="Tenggarong">
                    </div>

                    <div>
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="2" class="form-input text-xs">{{ old('address', $user->profile->address ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="bio" class="form-label">Biodata Singkat</label>
                        <textarea id="bio" name="bio" rows="2" class="form-input text-xs">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="avatar" class="form-label">Ganti Foto Profil</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="text-xs text-gray-500">
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <div class="text-[11px] font-bold text-gray-700 mb-2">Ubah Password (Opsional)</div>
                        <div class="space-y-2">
                            <input type="password" name="current_password" placeholder="Password Saat Ini" class="form-input text-xs">
                            <input type="password" name="new_password" placeholder="Password Baru" class="form-input text-xs">
                            <input type="password" name="new_password_confirmation" placeholder="Ulangi Password Baru" class="form-input text-xs">
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn-gold w-full py-2 font-bold text-xs">
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
