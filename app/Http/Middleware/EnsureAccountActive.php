<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda sedang disuspensi oleh admin.');
        }

        return $next($request);
    }
}
