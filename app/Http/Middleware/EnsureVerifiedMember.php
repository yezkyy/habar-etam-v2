<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVerifiedMember
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Aksi ini membutuhkan akun warga yang terverifikasi.');
        }

        $user = auth()->user();

        if ($user->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda sedang dinonaktifkan.');
        }

        if (!$user->isVerified() && !$user->isAdmin()) {
            return redirect()->route('member.profile')->with('warning', 'Fitur ini membutuhkan status Warga Terverifikasi. Verifikasi Anda saat ini berstatus ' . ($user->status === 'pending' ? 'menunggu persetujuan admin redaksi' : 'ditolak') . '.');
        }

        return $next($request);
    }
}
