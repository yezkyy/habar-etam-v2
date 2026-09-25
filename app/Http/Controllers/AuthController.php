<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Verification;
use App\Models\AuditLog;
use App\Services\NikVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected NikVerificationService $nikService;

    public function __construct(NikVerificationService $nikService)
    {
        $this->nikService = $nikService;
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('member.profile');
        }

        return view('auth.login', ['initialMode' => 'login']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isSuspended()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda sedang disuspensi oleh admin. Hubungi redaksi untuk informasi lebih lanjut.',
                ]);
            }

            AuditLog::record('user_login', 'User', $user->id);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('member.profile'))->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'Kombinasi email dan password tidak sesuai.',
        ]);
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('member.profile');
        }

        return view('auth.login', ['initialMode' => 'register']);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $nikAnalysis = $this->nikService->analyze($validated['nik'], $validated['date_of_birth']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
            'status' => 'pending', // Account starts as pending until verified by admin
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'display_name' => $validated['name'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'] ?? null,
            'district' => $validated['district'] ?? ($nikAnalysis['district_name'] ?? 'Tenggarong'),
        ]);

        Verification::create([
            'user_id' => $user->id,
            'nik_encrypted' => $nikAnalysis['nik_encrypted'],
            'nik_hash' => $nikAnalysis['nik_hash'],
            'date_of_birth' => $validated['date_of_birth'],
            'nik_region_valid' => $nikAnalysis['nik_region_valid'],
            'birth_date_valid' => $nikAnalysis['birth_date_valid'],
            'status' => 'pending',
        ]);

        AuditLog::record('user_registered', 'User', $user->id, [
            'district' => $nikAnalysis['district_name'],
            'is_kukar' => $nikAnalysis['nik_region_valid'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.profile')->with('success', 'Pendaftaran berhasil! Akun Anda kini dalam tahap verifikasi identitas warga oleh tim redaksi.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::record('user_logout', 'User', Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
