<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verification;
use App\Models\QuickSale;
use App\Models\JobVacancy;
use App\Models\Business;
use App\Models\CulinaryPlace;
use App\Models\Event;
use App\Models\Community;
use App\Models\Report;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Priority Counters for Admin work queue
        $pendingVerificationsCount = Verification::where('status', 'pending')->count();
        
        $pendingUgcCount = JobVacancy::where('status', 'pending')->count()
            + Business::where('status', 'pending')->count()
            + CulinaryPlace::where('status', 'pending')->count()
            + Event::where('status', 'pending')->count()
            + Community::where('status', 'pending')->count();

        $pendingQuickSalesCount = QuickSale::where('status', 'pending')->count();
        $activeReportsCount = Report::whereIn('status', ['pending_verification', 'processing_editorial'])->count();
        $totalMembersCount = User::where('role', 'member')->count();
        $verifiedMembersCount = User::where('role', 'member')->where('status', 'verified')->count();
        $resolvedReportsCount = Report::where('status', 'resolved')->count();

        // 2. Priority items needing attention
        $pendingVerifications = Verification::with('user.profile')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with('user')
            ->whereIn('status', ['pending_verification', 'processing_editorial', 'live_agenda'])
            ->latest()
            ->take(5)
            ->get();

        $pendingQuickSales = QuickSale::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // 3. Recent system activity logs
        $recentActivities = AuditLog::with('user')->latest()->take(10)->get();

        // 4. Time Series Trend Data (Last 7 Days) for Chart.js
        $trendLabels = [];
        $trendReports = [];
        $trendUgc = [];
        $trendUsers = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedLabel = $date->translatedFormat('d M');
            $trendLabels[] = $formattedLabel;

            $reportsDayCount = Report::whereDate('created_at', $date)->count();
            $ugcDayCount = QuickSale::whereDate('created_at', $date)->count()
                + JobVacancy::whereDate('created_at', $date)->count()
                + Business::whereDate('created_at', $date)->count()
                + CulinaryPlace::whereDate('created_at', $date)->count()
                + Event::whereDate('created_at', $date)->count()
                + Community::whereDate('created_at', $date)->count();
            $usersDayCount = User::whereDate('created_at', $date)->count();

            $trendReports[] = $reportsDayCount;
            $trendUgc[] = $ugcDayCount;
            $trendUsers[] = $usersDayCount;
        }

        // 5. UGC Distribution by Category
        $ugcDistribution = [
            'quick_sale' => QuickSale::count(),
            'job' => JobVacancy::count(),
            'business' => Business::count(),
            'culinary' => CulinaryPlace::count(),
            'event' => Event::count(),
            'community' => Community::count(),
        ];

        // 6. Report Status Breakdown
        $reportStatusBreakdown = [
            'pending' => Report::where('status', 'pending_verification')->count(),
            'processing' => Report::where('status', 'processing_editorial')->count(),
            'live' => Report::where('status', 'live_agenda')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];

        // 7. District distribution (Top 6 Districts in Kukar)
        $districtStats = DB::table('user_profiles')
            ->select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->groupBy('district')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $districtLabels = $districtStats->pluck('district')->toArray();
        $districtCounts = $districtStats->pluck('total')->toArray();

        return view('admin.dashboard', compact(
            'pendingVerificationsCount',
            'pendingUgcCount',
            'pendingQuickSalesCount',
            'activeReportsCount',
            'totalMembersCount',
            'verifiedMembersCount',
            'resolvedReportsCount',
            'pendingVerifications',
            'recentReports',
            'pendingQuickSales',
            'recentActivities',
            'trendLabels',
            'trendReports',
            'trendUgc',
            'trendUsers',
            'ugcDistribution',
            'reportStatusBreakdown',
            'districtLabels',
            'districtCounts'
        ));
    }

    public function activity(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('target_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('target_type')) {
            $query->where('target_type', $request->input('target_type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Stats
        $totalActivitiesCount = AuditLog::count();
        $todayActivitiesCount = AuditLog::whereDate('created_at', today())->count();
        $uniqueUsersCount = AuditLog::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        $moderationCount = AuditLog::where(function($q) {
            $q->where('action', 'like', '%approve%')
              ->orWhere('action', 'like', '%reject%')
              ->orWhere('action', 'like', '%status%')
              ->orWhere('action', 'like', '%update%');
        })->count();

        // Distinct lists for filters
        $availableActions = AuditLog::select('action')->distinct()->pluck('action');
        $availableTargets = AuditLog::select('target_type')->whereNotNull('target_type')->distinct()->pluck('target_type');

        $activities = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('admin.activity', compact(
            'activities',
            'totalActivitiesCount',
            'todayActivitiesCount',
            'uniqueUsersCount',
            'moderationCount',
            'availableActions',
            'availableTargets'
        ));
    }
}
