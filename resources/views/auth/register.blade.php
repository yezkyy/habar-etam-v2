@extends('layouts.auth')

@section('title', 'Pendaftaran Warga Kukar')
@section('header_heading', 'Registrasi Akun Warga')
@section('header_subheading', 'Daftarkan identitas kependudukan Kutai Kartanegara Anda')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="form-label">Nama Lengkap (Sesuai KTP)</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="form-input" placeholder="Contoh: Budi Santoso">
        @error('name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label for="email" class="form-label">Email Aktif</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="nama@email.com">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="phone" class="form-label">No. WhatsApp / HP</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required class="form-input" placeholder="08123456789">
            @error('phone')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- NIK & Date of Birth Validation Section -->
    <div class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-md space-y-3">
        <div class="flex items-center space-x-1.5 text-xs font-bold text-amber-900">
            <i data-lucide="shield" class="w-4 h-4 text-brand-gold-dark"></i>
            <span>Validasi Kependudukan Kukar</span>
        </div>

        <div>
            <label for="nik" class="form-label text-amber-950">Nomor Induk Kependudukan (NIK)</label>
            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" required class="form-input font-mono tracking-wider" placeholder="16 digit angka (6402xxxxxxxxxxxx)">
            <p class="text-[11px] text-gray-500 mt-1">NIK Anda akan dienkripsi secara aman dan tidak ditampilkan publik.</p>
            @error('nik')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="date_of_birth" class="form-label text-amber-950">Tanggal Lahir</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="form-input">
                @error('date_of_birth')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="district" class="form-label text-amber-950">Kecamatan di Kukar</label>
                <select id="district" name="district" class="form-input">
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
                @error('district')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <label for="address" class="form-label">Alamat Domisili</label>
        <input type="text" id="address" name="address" value="{{ old('address') }}" class="form-input" placeholder="Jl. Pesut No. 12, RT 04">
        @error('address')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required class="form-input" placeholder="Min. 8 karakter">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="Ulangi password">
        </div>
    </div>

    <div class="pt-2">
        <button type="submit" class="btn-gold w-full py-2.5 shadow-sm text-sm font-bold">
            Daftar Sebagai Warga
        </button>
    </div>
</form>

<div class="mt-6 border-t border-gray-100 pt-5 text-center text-xs text-gray-600">
    <span>Sudah memiliki akun warga?</span>
    <a href="{{ route('login') }}" class="font-bold text-brand-black hover:text-brand-gold-dark ml-1 underline decoration-brand-gold decoration-2">
        Masuk di Sini
    </a>
</div>
@endsection
