<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Report;
use App\Models\ReportMedia;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['media', 'user'])
            ->whereIn('status', ['pending_verification', 'processing_editorial', 'live_agenda', 'resolved']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('district')) {
            $query->where('location_district', $request->input('district'));
        }

        $reports = $query->latest('created_at')->paginate(9)->withQueryString();

        // Statistics for header summary
        $stats = [
            'total' => Report::count(),
            'live' => Report::where('status', 'live_agenda')->count(),
            'processing' => Report::where('status', 'processing_editorial')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
            'pending' => Report::where('status', 'pending_verification')->count(),
        ];

        // Category counts for horizon tabs
        $categoryCounts = [
            'Jalan & Jembatan' => Report::where('category', 'Jalan & Jembatan')->count(),
            'Drainase & Banjir' => Report::where('category', 'Drainase & Banjir')->count(),
            'Lampu & Penerangan' => Report::where('category', 'Lampu & Penerangan')->count(),
            'Sampah & Kebersihan' => Report::where('category', 'Sampah & Kebersihan')->count(),
            'Fasilitas Publik' => Report::where('category', 'Fasilitas Publik')->count(),
            'Ketertiban Umum' => Report::where('category', 'Ketertiban Umum')->count(),
        ];

        // All mapped reports for the interactive GIS Incident Map
        $allMappedReports = Report::with(['media:id,report_id,path,media_type'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereIn('status', ['pending_verification', 'processing_editorial', 'live_agenda', 'resolved'])
            ->latest()
            ->get()
            ->map(function ($rep) {
                $firstImg = $rep->media->firstWhere('media_type', 'image');
                return [
                    'id' => $rep->id,
                    'ticket_number' => $rep->ticket_number,
                    'title' => $rep->title,
                    'category' => $rep->category,
                    'status' => $rep->status,
                    'status_label' => $rep->status_label,
                    'workflow_step' => $rep->workflow_step,
                    'address' => $rep->address,
                    'location_district' => $rep->location_district,
                    'latitude' => (float) $rep->latitude,
                    'longitude' => (float) $rep->longitude,
                    'created_ago' => $rep->created_at->diffForHumans(),
                    'image_url' => $firstImg ? asset('storage/' . $firstImg->path) : null,
                ];
            });

        $availableDistricts = [
            'Tenggarong',
            'Tenggarong Seberang',
            'Loa Kulu',
            'Loa Janan',
            'Samboja',
            'Muara Jawa',
            'Sangasanga',
            'Anggana',
            'Muara Badak',
            'Marang Kayu',
            'Sebulu',
            'Muara Kaman',
            'Kota Bangun',
            'Kenohan',
            'Kembang Janggut',
            'Tabang',
            'Muara Wis',
            'Muara Muntai',
        ];

        return view('reports.index', compact('reports', 'stats', 'categoryCounts', 'allMappedReports', 'availableDistricts'));
    }

    public function show(string $ticketNumber)
    {
        $report = Report::with(['media', 'user'])->where('ticket_number', $ticketNumber)->firstOrFail();

        $recentUpdates = Report::where('id', '!=', $report->id)
            ->whereIn('status', ['live_agenda', 'processing_editorial', 'resolved'])
            ->latest()
            ->take(3)
            ->get();

        return view('reports.show', compact('report', 'recentUpdates'));
    }

    public function create()
    {
        // Require verified member status
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk mengirim pengaduan Lapor Etam.');
        }

        if (!Auth::user()->isVerified() && !Auth::user()->isAdmin()) {
            return redirect()->route('member.profile')->with('warning', 'Pengaduan Lapor Etam hanya dapat dikirim oleh Warga Terverifikasi. Status verifikasi Anda saat ini masih menunggu peninjauan admin.');
        }

        return view('reports.create');
    }

    public function store(ReportRequest $request)
    {
        $validated = $request->validated();

        // Generate unique ticket number: ETAM-YYYYMM-XXXX
        $countThisMonth = Report::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
        $ticketNumber = 'ETAM-' . now()->format('Ym') . '-' . str_pad($countThisMonth, 3, '0', STR_PAD_LEFT);

        $report = Report::create([
            'user_id' => Auth::id(),
            'ticket_number' => $ticketNumber,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'location_district' => $validated['location_district'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'status' => 'pending_verification',
        ]);

        if ($request->hasFile('evidence_files')) {
            foreach ($request->file('evidence_files') as $file) {
                $mime = $file->getMimeType();
                $type = str_contains($mime, 'video') ? 'video' : 'image';
                $path = $file->store('reports', 'public');

                ReportMedia::create([
                    'report_id' => $report->id,
                    'path' => $path,
                    'media_type' => $type,
                ]);
            }
        }

        AuditLog::record('report_submitted', 'Report', $report->id, [
            'ticket' => $ticketNumber,
            'category' => $validated['category'],
        ]);

        return redirect()->route('reports.show', $report->ticket_number)
            ->with('success', "Pengaduan berhasil dikirim dengan nomor tiket {$ticketNumber}. Tim redaksi akan memverifikasi laporan Anda.");
    }

    public function myReports()
    {
        $reports = Auth::user()->reports()->with('media')->latest()->paginate(10);
        return view('reports.my-reports', compact('reports'));
    }
}
