<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Verification::query();

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        $stats = [
            'total' => $counts['all'],
            'pending' => $counts['pending'],
            'approved' => $counts['approved'],
            'rejected' => $counts['rejected'],
        ];

        $query = Verification::with(['user.profile', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        } else {
            // Default show pending first
            $query->orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')");
        }

        if ($request->filled('district')) {
            $district = $request->input('district');
            $query->where(function($q) use ($district) {
                $q->where('district', $district)
                  ->orWhereHas('user.profile', function($pq) use ($district) {
                      $pq->where('district', $district);
                  });
            });
        }

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function($q) use ($search) {
                $q->where('masked_nik', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $verifications = $query->latest('created_at')->paginate(15)->withQueryString();

        $districts = [
            'Anggana', 'Kembang Janggut', 'Kenohan', 'Kota Bangun', 'Kota Bangun Darat',
            'Loa Janan', 'Loa Kulu', 'Marangkayu', 'Muara Badak', 'Muara Jawa',
            'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Samboja', 'Samboja Barat',
            'Sebulu', 'Tabang', 'Tenggarong', 'Tenggarong Seberang'
        ];

        return view('admin.verifications.index', compact('verifications', 'stats', 'counts', 'districts'));
    }

    public function show(Verification $verification)
    {
        $verification->load(['user.profile', 'verifier']);
        return view('admin.verifications.show', compact('verification'));
    }

    public function approve(Request $request, Verification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $verification->user->update([
            'status' => 'verified',
        ]);

        AuditLog::record('member_verified_approved', 'Verification', $verification->id, [
            'user_id' => $verification->user_id,
            'user_name' => $verification->user->name,
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Verifikasi warga untuk {$verification->user->name} berhasil disetujui.");
    }

    public function reject(Request $request, Verification $verification)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan verifikasi wajib diisi agar warga memahami penyebabnya.',
        ]);

        $reason = $request->input('rejection_reason');

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $verification->user->update([
            'status' => 'rejected',
        ]);

        AuditLog::record('member_verified_rejected', 'Verification', $verification->id, [
            'user_id' => $verification->user_id,
            'user_name' => $verification->user->name,
            'reason' => $reason,
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Verifikasi warga untuk {$verification->user->name} ditolak dengan alasan tercatat.");
    }
}
