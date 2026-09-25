<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['profile', 'verification']);

        // Load member's submissions count & latest items
        $jobCount = $user->jobVacancies()->count();
        $businessCount = $user->businesses()->count();
        $culinaryCount = $user->culinaryPlaces()->count();
        $eventCount = $user->events()->count();
        $communityCount = $user->communities()->count();
        $quickSaleCount = $user->quickSales()->count();
        $reportCount = $user->reports()->count();

        $recentQuickSales = $user->quickSales()->with('media')->latest()->take(3)->get();
        $recentReports = $user->reports()->latest()->take(3)->get();

        return view('member.profile', compact(
            'user',
            'jobCount',
            'businessCount',
            'culinaryCount',
            'eventCount',
            'communityCount',
            'quickSaleCount',
            'reportCount',
            'recentQuickSales',
            'recentReports'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'display_name' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
            'address' => ['nullable', 'string', 'max:500'],
            'district' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'avatar.max' => 'Ukuran avatar foto maksimal 2 MB.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        if (!empty($validated['new_password'])) {
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        }

        $profileData = [
            'display_name' => $validated['display_name'] ?? $validated['name'],
            'bio' => $validated['bio'] ?? null,
            'address' => $validated['address'] ?? null,
            'district' => $validated['district'] ?? null,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $profileData['avatar'] = $path;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        AuditLog::record('profile_updated', 'User', $user->id);

        return redirect()->route('member.profile')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
