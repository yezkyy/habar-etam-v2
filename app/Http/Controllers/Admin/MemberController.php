<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = User::where('role', 'member');

        // Status Counts for Tab Badges
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'verified' => (clone $baseQuery)->where('status', 'verified')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'suspended' => (clone $baseQuery)->where('status', 'suspended')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        // Overall Stats for Dashboard Summary Cards
        $stats = [
            'total' => $counts['all'],
            'verified' => $counts['verified'],
            'pending' => $counts['pending'],
            'active_contributors' => (clone $baseQuery)->has('reports')
                ->orWhere(function($q) {
                    $q->where('role', 'member')->has('jobVacancies');
                })
                ->orWhere(function($q) {
                    $q->where('role', 'member')->has('businesses');
                })
                ->orWhere(function($q) {
                    $q->where('role', 'member')->has('quickSales');
                })->count(),
        ];

        $query = User::with(['profile', 'verification'])->where('role', 'member');

        // Search Filter
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('display_name', 'like', "%{$search}%")
                         ->orWhere('district', 'like', "%{$search}%")
                         ->orWhere('village', 'like', "%{$search}%");
                  })
                  ->orWhereHas('verification', function($vq) use ($search) {
                      $vq->where('masked_nik', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // District Filter
        if ($request->filled('district')) {
            $district = $request->input('district');
            $query->whereHas('profile', function($q) use ($district) {
                $q->where('district', $district);
            });
        }

        // Relation counts
        $query->withCount([
            'jobVacancies',
            'businesses',
            'culinaryPlaces',
            'events',
            'communities',
            'quickSales',
            'reports'
        ]);

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'reports_desc' => $query->orderByDesc('reports_count'),
            'contributions_desc' => $query->orderByRaw('(reports_count + quick_sales_count + job_vacancies_count + businesses_count) DESC'),
            default => $query->latest(),
        };

        $members = $query->paginate(15)->withQueryString();

        // Distinct Districts in Kukar from DB or Defaults
        $dbDistricts = UserProfile::whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->pluck('district')
            ->toArray();

        $defaultDistricts = [
            'Anggana', 'Kembang Janggut', 'Kenohan', 'Kota Bangun', 'Kota Bangun Darat',
            'Loa Janan', 'Loa Kulu', 'Marangkayu', 'Muara Badak', 'Muara Jawa',
            'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Samboja', 'Samboja Barat',
            'Sebulu', 'Tabang', 'Tenggarong', 'Tenggarong Seberang'
        ];

        $districts = array_values(array_unique(array_filter(array_merge($defaultDistricts, $dbDistricts))));
        sort($districts);

        return view('admin.members.index', compact('members', 'stats', 'counts', 'districts'));
    }

    public function show(User $member)
    {
        $member->load([
            'profile', 
            'verification.verifier', 
            'jobVacancies', 
            'businesses', 
            'culinaryPlaces', 
            'events', 
            'communities', 
            'quickSales.media', 
            'reports'
        ]);

        $auditLogs = AuditLog::where(function($q) use ($member) {
            $q->where('user_id', $member->id)
              ->orWhere(function($sq) use ($member) {
                  $sq->where('target_type', 'User')->where('target_id', $member->id);
              });
        })->latest('created_at')->take(10)->get();

        return view('admin.members.show', compact('member', 'auditLogs'));
    }

    public function toggleStatus(Request $request, User $member)
    {
        $request->validate([
            'status' => ['required', 'in:verified,pending,rejected,suspended'],
        ]);

        $newStatus = $request->input('status');
        $oldStatus = $member->status;

        $member->update(['status' => $newStatus]);

        AuditLog::record('member_status_changed', 'User', $member->id, [
            'from' => $oldStatus,
            'to' => $newStatus,
            'reason' => $request->input('reason', 'Pembaruan status oleh administrator'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status {$member->name} berhasil diperbarui menjadi: " . strtoupper($newStatus),
                'new_status' => $newStatus
            ]);
        }

        return back()->with('success', "Status anggota {$member->name} berhasil diubah menjadi: " . strtoupper($newStatus));
    }
}
