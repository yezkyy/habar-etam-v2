@extends('layouts.admin')

@section('title', 'Tinjauan Verifikasi NIK: ' . ($verification->user->name ?? 'Warga') . ' - Admin Habar Etam')
@section('page_title', 'Tinjauan Verifikasi NIK Kependudukan Kukar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Breadcrumbs & Back Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-600 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.verifications.index') }}" class="hover:text-gold-600 transition-colors">Antrean Verifikasi</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">Berkas #{{ $verification->id }}</span>
        </div>

        <div class="flex items-center gap-2">
            @if($verification->user && $verification->user->phone)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $verification->user->phone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer" 
                   class="h-9 px-3.5 rounded-xl text-xs font-bold bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-500 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                    <span>Hubungi WA</span>
                </a>
            @endif

            <a href="{{ route('admin.verifications.index') }}" 
               class="h-9 px-3.5 rounded-xl text-xs font-bold bg-gray-900 hover:bg-gold-500 text-white hover:text-black transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Antrean</span>
            </a>
        </div>
    </div>

    <!-- Hero Card: Verification Review Banner -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="h-24 bg-gradient-to-r from-gray-900 via-gray-800 to-black relative">
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-gold-500/10 rounded-full blur-xl pointer-events-none"></div>
        </div>

        <div class="p-6 sm:p-8 -mt-12 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-18 h-18 rounded-3xl bg-gradient-to-br from-amber-200 via-gold-300 to-amber-400 text-black border-4 border-white shadow-md flex items-center justify-center font-black text-2xl shrink-0">
                        {{ strtoupper(substr($verification->user->name ?? 'W', 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 border border-gray-200">
                                Berkas Pengajuan #{{ $verification->id }}
                            </span>
                            <span class="text-xs text-gray-400 font-mono">{{ $verification->created_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-gray-900 mt-1">{{ $verification->user->name ?? 'Warga' }}</h1>
                        <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-2">
                            <span>{{ $verification->user->email ?? '-' }}</span>
                            <span>&bull;</span>
                            <span class="font-mono">{{ $verification->user->phone ?? 'Tidak ada nomor telepon' }}</span>
                        </p>
                    </div>
                </div>

                <div class="self-start sm:self-center">
                    @if($verification->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <i data-lucide="shield-check" class="w-4 h-4"></i> Disetujui (Warga Sah)
                        </span>
                    @elseif($verification->status === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            <i data-lucide="clock-4" class="w-4 h-4"></i> Menunggu Keputusan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                            <i data-lucide="x-circle" class="w-4 h-4"></i> Permohonan Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <!-- NIK Algorithmic Validation Grid -->
            <div class="mt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i data-lucide="cpu" class="w-4 h-4 text-gold-600"></i> Analisis Otomatis NIK Kependudukan Kukar
                    </h2>
                    <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Format Terverifikasi
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Masked NIK Card -->
                    <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-200/80 shadow-2xs space-y-2">
                        <span class="text-[11px] font-bold uppercase text-gray-400 block tracking-wider">Masked NIK (Terenkripsi AES)</span>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xl font-black text-gray-900 tracking-wider">{{ $verification->masked_nik }}</span>
                            <span class="w-8 h-8 rounded-xl bg-gold-100 text-gold-700 flex items-center justify-center">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <div class="text-[11px] text-emerald-700 font-medium flex items-center gap-1 pt-1 border-t border-gray-200/60">
                            <i data-lucide="shield" class="w-3 h-3 text-emerald-600"></i>
                            <span>Kode Wilayah Provinsi Kaltim & Kab. Kutai Kartanegara (6402xx)</span>
                        </div>
                    </div>

                    <!-- Kecamatan Card -->
                    <div class="p-5 rounded-2xl bg-gray-50/80 border border-gray-200/80 shadow-2xs space-y-2">
                        <span class="text-[11px] font-bold uppercase text-gray-400 block tracking-wider">Kecamatan Asal NIK</span>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-black text-gray-900">Kec. {{ $verification->district ?? '-' }}</span>
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 pt-1 border-t border-gray-200/60 flex items-center justify-between">
                            <span>Domisili Profil Warga:</span>
                            <strong class="text-gray-800">Kec. {{ $verification->user->profile->district ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- 3 Integrity Check Indicators -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-100 text-xs flex items-center gap-2.5">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <div>
                            <strong class="font-bold text-emerald-900 block">Struktur 16-Digit</strong>
                            <span class="text-[11px] text-emerald-700">Format panjang digit sah</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-100 text-xs flex items-center gap-2.5">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <div>
                            <strong class="font-bold text-emerald-900 block">Prefix Kukar (6402)</strong>
                            <span class="text-[11px] text-emerald-700">Wilayah Kutai Kartanegara</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-100 text-xs flex items-center gap-2.5">
                        <i data-lucide="shield-alert" class="w-4 h-4 text-blue-600 shrink-0"></i>
                        <div>
                            <strong class="font-bold text-blue-900 block">Enkripsi SHA-256</strong>
                            <span class="text-[11px] text-blue-700">Anti-duplikasi data NIK</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejection Alert if already rejected -->
            @if($verification->status === 'rejected' && $verification->rejection_reason)
                <div class="mt-6 p-4 sm:p-5 bg-rose-50 border border-rose-200 rounded-2xl text-xs space-y-1">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                        <span>Alasan Penolakan Berkas:</span>
                    </div>
                    <p class="text-rose-800 leading-relaxed pl-6">{{ $verification->rejection_reason }}</p>
                </div>
            @endif

            <!-- Reviewer Meta Info if reviewed -->
            @if($verification->verifier)
                <div class="mt-6 p-4 rounded-2xl bg-gray-50 border border-gray-100 text-xs text-gray-600 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-gray-900 text-gold-400 flex items-center justify-center font-bold text-[10px]">
                            A
                        </div>
                        <span>Ditinjau oleh Admin: <strong class="text-gray-900">{{ $verification->verifier->name }}</strong></span>
                    </div>
                    <span class="font-mono text-[11px] text-gray-500">
                        Waktu Keputusan: {{ $verification->verified_at ? $verification->verified_at->translatedFormat('d F Y, H:i') : '-' }}
                    </span>
                </div>
            @endif

            <!-- Decision Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                @if($verification->status === 'pending' || $verification->status === 'rejected')
                    <form action="{{ route('admin.verifications.approve', $verification->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI verifikasi identitas NIK warga ini? Akun warga akan langsung berstatus Terverifikasi.')" 
                                class="w-full sm:w-auto h-11 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center justify-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Setujui & Terbitkan Status Sah</span>
                        </button>
                    </form>
                @endif

                @if($verification->status === 'pending' || $verification->status === 'approved')
                    <button type="button" 
                            onclick="document.getElementById('reject-modal').classList.remove('hidden')" 
                            class="h-11 px-6 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center justify-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span>Tolak Permohonan</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="reject-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-md">
    <div onclick="document.getElementById('reject-modal').classList.add('hidden')" class="fixed inset-0"></div>

    <div class="relative bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-gray-100 z-10 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Tolak Verifikasi NIK Warga</h3>
                    <p class="text-[11px] text-gray-400">{{ $verification->user->name ?? 'Warga' }}</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.verifications.reject', $verification->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Alasan Penolakan <span class="text-rose-600">*</span>
                </label>
                <textarea id="rejection-reason-textarea" 
                          name="rejection_reason" 
                          rows="4" 
                          required 
                          placeholder="Jelaskan alasan penolakan berkas secara jelas agar warga dapat memperbaikinya..." 
                          class="w-full p-3.5 rounded-xl border border-gray-200 text-xs bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 outline-none transition-all"></textarea>
                
                <!-- Quick Preset Reason Chips -->
                <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                    <span class="text-[10px] font-semibold text-gray-400">Pilih Cepat:</span>
                    <button type="button" onclick="setRejectionText('NIK tidak terdaftar di basis data kependudukan Kutai Kartanegara.')" class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-800 transition-colors">
                        NIK Bukan Kukar
                    </button>
                    <button type="button" onclick="setRejectionText('Format tanggal lahir pada NIK tidak sesuai dengan data identitas.')" class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-800 transition-colors">
                        Tanggal Lahir Beda
                    </button>
                    <button type="button" onclick="setRejectionText('Data nama lengkap tidak sesuai dengan identitas KTP yang diajukan.')" class="px-2 py-1 rounded-lg text-[10px] font-semibold bg-gray-100 hover:bg-rose-100 text-gray-700 hover:text-rose-800 transition-colors">
                        Nama Tidak Sesuai
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-md transition-all inline-flex items-center gap-1.5">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Kirim Penolakan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function setRejectionText(text) {
        const textarea = document.getElementById('rejection-reason-textarea');
        if (textarea) {
            textarea.value = text;
            textarea.focus();
        }
    }
</script>
@endpush
@endsection
