<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Halaman khusus admin.'], 403);
            }
            return redirect()->route('login')->with('error', 'Akses dibatasi. Silakan login sebagai administrator.');
        }

        if (auth()->user()->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda sedang dinonaktifkan.');
        }

        return $next($request);
    }
}
