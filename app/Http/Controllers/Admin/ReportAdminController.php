<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\AuditLog;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $category = $request->input('category', 'all');
        $district = $request->input('district', 'all');
        $sort = $request->input('sort', 'newest');
        $search = strtolower(trim($request->input('q', '')));

        $baseQuery = Report::query();

        // 5 Stats Cards Calculation
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending_verification')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing_editorial')->count(),
            'live' => (clone $baseQuery)->where(function($q) {
                $q->where('status', 'live_agenda')->orWhere('is_featured_live', true);
            })->count(),
            'resolved' => (clone $baseQuery)->where('status', 'resolved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        // Status counts for tabs
        $statusCounts = [
            'all' => $stats['total'],
            'pending_verification' => $stats['pending'],
            'processing_editorial' => $stats['processing'],
            'live_agenda' => (clone $baseQuery)->where('status', 'live_agenda')->count(),
            'resolved' => $stats['resolved'],
            'rejected' => $stats['rejected'],
        ];

        // Category counts
        $categoryCounts = [
            'all' => $stats['total'],
            'infrastruktur' => (clone $baseQuery)->where('category', 'infrastruktur')->count(),
            'kebersihan' => (clone $baseQuery)->where('category', 'kebersihan')->count(),
            'pelayanan_publik' => (clone $baseQuery)->where('category', 'pelayanan_publik')->count(),
            'keamanan' => (clone $baseQuery)->where('category', 'keamanan')->count(),
            'lingkungan' => (clone $baseQuery)->where('category', 'lingkungan')->count(),
            'lainnya' => (clone $baseQuery)->where('category', 'lainnya')->count(),
        ];

        // Query Builder
        $query = Report::with(['user.profile', 'user.verification', 'media', 'moderator']);

        // Status Filter
        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        // Category Filter
        if ($category !== 'all' && !empty($category)) {
            $query->where('category', $category);
        }

        // District Filter
        if ($district !== 'all' && !empty($district)) {
            $query->where(function($q) use ($district) {
                $q->where('location_district', $district)
                  ->orWhere('address', 'like', "%{$district}%");
            });
        }

        // Live Search Query
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        match ($sort) {
            'oldest' => $query->oldest('created_at'),
            'ticket_asc' => $query->orderBy('ticket_number', 'asc'),
            'ticket_desc' => $query->orderBy('ticket_number', 'desc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            default => $query->latest('created_at'),
        };

        $reports = $query->paginate(15)->withQueryString();

        // Distinct Districts
        $defaultDistricts = [
            'Anggana', 'Kembang Janggut', 'Kenohan', 'Kota Bangun', 'Kota Bangun Darat',
            'Loa Janan', 'Loa Kulu', 'Marangkayu', 'Muara Badak', 'Muara Jawa',
            'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Samboja', 'Samboja Barat',
            'Sebulu', 'Tabang', 'Tenggarong', 'Tenggarong Seberang'
        ];
        $dbDistricts = Report::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_filter(array_merge($defaultDistricts, $dbDistricts))));
        sort($districts);

        return view('admin.reports.index', [
            'reports' => $reports,
            'stats' => $stats,
            'statusCounts' => $statusCounts,
            'categoryCounts' => $categoryCounts,
            'status' => $status,
            'category' => $category,
            'district' => $district,
            'sort' => $sort,
            'districts' => $districts,
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['user.profile', 'user.verification', 'media', 'moderator']);
        
        $logs = AuditLog::where('target_type', 'Report')
            ->where('target_id', $report->id)
            ->with('user')
            ->latest('created_at')
            ->get();

        return view('admin.reports.show', compact('report', 'logs'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => ['required', 'in:pending_verification,processing_editorial,live_agenda,resolved,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'editorial_summary' => ['nullable', 'string', 'max:2000'],
            'is_featured_live' => ['nullable', 'boolean'],
        ]);

        $oldStatus = $report->status;
        $newStatus = $request->input('status');

        $updateData = [
            'status' => $newStatus,
            'admin_notes' => $request->input('admin_notes'),
            'editorial_summary' => $request->input('editorial_summary'),
            'is_featured_live' => $request->boolean('is_featured_live'),
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
        ];

        if ($newStatus === 'resolved' && !$report->resolved_at) {
            $updateData['resolved_at'] = now();
        }

        $report->update($updateData);

        AuditLog::record('report_status_updated', 'Report', $report->id, [
            'ticket' => $report->ticket_number,
            'from' => $oldStatus,
            'to' => $newStatus,
            'notes' => $request->input('admin_notes'),
            'live_flag' => $request->boolean('is_featured_live'),
        ]);

        return redirect()->route('admin.reports.show', $report->id)
            ->with('success', "Status pengaduan tiket #{$report->ticket_number} berhasil diperbarui.");
    }

    public function map()
    {
        $reports = Report::with(['user.profile', 'user.verification', 'media', 'moderator'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest('created_at')
            ->get();

        $stats = [
            'total_mapped' => $reports->count(),
            'pending' => $reports->where('status', 'pending_verification')->count(),
            'processing' => $reports->where('status', 'processing_editorial')->count(),
            'live' => $reports->where(function($r) {
                return $r->status === 'live_agenda' || (bool)$r->is_featured_live;
            })->count(),
            'resolved' => $reports->where('status', 'resolved')->count(),
            'rejected' => $reports->where('status', 'rejected')->count(),
        ];

        // Format payload for frontend Leaflet rendering
        $mapData = $reports->map(function ($rep) {
            $firstMedia = $rep->media->first();
            $mediaUrl = null;
            if ($firstMedia) {
                $filePath = $firstMedia->path ?? $firstMedia->file_path ?? null;
                if ($filePath) {
                    $mediaUrl = str_starts_with($filePath, 'http') ? $filePath : asset('storage/' . $filePath);
                }
            }

            return [
                'id' => $rep->id,
                'ticket_number' => $rep->ticket_number,
                'title' => $rep->title,
                'category' => $rep->category,
                'category_label' => match($rep->category) {
                    'infrastruktur' => 'Infrastruktur Jalan',
                    'kebersihan' => 'Kebersihan & Sampah',
                    'pelayanan_publik' => 'Pelayanan Publik',
                    'keamanan' => 'Keamanan & Ketertiban',
                    'lingkungan' => 'Lingkungan Hidup',
                    'lainnya' => 'Lainnya',
                    default => ucfirst(str_replace('_', ' ', $rep->category)),
                },
                'description' => \Illuminate\Support\Str::limit(strip_tags($rep->description), 160),
                'address' => $rep->address ?: '-',
                'district' => $rep->location_district ?: 'Tenggarong',
                'latitude' => (float) $rep->latitude,
                'longitude' => (float) $rep->longitude,
                'status' => $rep->status,
                'status_label' => $rep->status_label,
                'is_featured_live' => (bool) $rep->is_featured_live,
                'created_at_formatted' => $rep->created_at->translatedFormat('d M Y, H:i'),
                'created_at_diff' => $rep->created_at->diffForHumans(),
                'author_name' => $rep->user?->name ?? 'Warga Kutai Kartanegara',
                'author_phone' => $rep->user?->phone,
                'author_avatar' => $rep->user?->profile?->avatar_url ?? null,
                'media_url' => $mediaUrl,
                'media_count' => $rep->media->count(),
                'admin_url' => route('admin.reports.show', $rep->id),
            ];
        });

        // Districts list from reports and defaults
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Sebulu', 'Anggana', 'Sangasanga', 'Marangkayu'
        ];
        $dbDistricts = $reports->pluck('location_district')->filter()->unique()->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.reports.map', compact('reports', 'stats', 'mapData', 'districts'));
    }
}
