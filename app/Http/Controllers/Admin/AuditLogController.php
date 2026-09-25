<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    /**
     * Display the Forensic Audit Logs Dashboard
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // 1. Search Query (Actor, Email, Action, IP, Target)
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('target_type', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Action Filter
        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        // 3. Target Type Filter
        if ($request->filled('target_type') && $request->target_type !== 'all') {
            $query->where('target_type', $request->target_type);
        }

        // 4. Date Range Filters
        $datePreset = $request->query('date_preset', 'all');
        if ($datePreset === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($datePreset === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($datePreset === 'last_7') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($datePreset === 'this_month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($datePreset === 'custom') {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
        }

        // 5. Distinct actions & targets for filter dropdowns
        $availableActions = AuditLog::distinct()->pluck('action')->filter()->values();
        $availableTargets = AuditLog::distinct()->pluck('target_type')->filter()->values();

        // 6. Top forensic metric cards
        $metrics = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', today())->count(),
            'unique_operators' => AuditLog::whereNotNull('user_id')->distinct('user_id')->count(),
            'unique_ips' => AuditLog::distinct('ip_address')->count(),
        ];

        // 7. Paginated results with query string preservation
        $logs = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('admin.system.audit-logs', [
            'logs' => $logs,
            'metrics' => $metrics,
            'availableActions' => $availableActions,
            'availableTargets' => $availableTargets,
            'currentAction' => $request->query('action', 'all'),
            'currentTarget' => $request->query('target_type', 'all'),
            'datePreset' => $datePreset,
            'startDate' => $request->query('start_date', ''),
            'endDate' => $request->query('end_date', ''),
            'searchQuery' => $request->query('q', ''),
        ]);
    }
}
