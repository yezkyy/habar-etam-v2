<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Business;
use App\Models\CulinaryPlace;
use App\Models\Event;
use App\Models\Community;
use App\Models\QuickSale;
use App\Models\ModerationLog;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    protected array $modelMap = [
        'job' => JobVacancy::class,
        'business' => Business::class,
        'culinary' => CulinaryPlace::class,
        'event' => Event::class,
        'community' => Community::class,
        'quick_sale' => QuickSale::class,
    ];

    public function index(Request $request)
    {
        $type = $request->input('type', 'all');
        $status = $request->input('status', 'all');
        $search = strtolower(trim($request->input('q', '')));
        $sort = $request->input('sort', 'newest');

        $submissions = collect();

        // 1. Bursa Kerja
        if ($type === 'all' || $type === 'job') {
            $query = JobVacancy::with(['user.verification']);
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $jobs = $query->get()->map(fn($item) => $this->formatItem($item, 'job', 'Bursa Kerja', $item->title));
            $submissions = $submissions->concat($jobs);
        }

        // 2. Produk & Jasa
        if ($type === 'all' || $type === 'business') {
            $query = Business::with(['user.verification']);
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $biz = $query->get()->map(fn($item) => $this->formatItem($item, 'business', 'Produk & Jasa', $item->name));
            $submissions = $submissions->concat($biz);
        }

        // 3. Kuliner Khas
        if ($type === 'all' || $type === 'culinary') {
            $query = CulinaryPlace::with(['user.verification']);
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $cul = $query->get()->map(fn($item) => $this->formatItem($item, 'culinary', 'Kuliner', $item->name));
            $submissions = $submissions->concat($cul);
        }

        // 4. Event & Agenda
        if ($type === 'all' || $type === 'event') {
            $query = Event::with(['user.verification']);
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $evt = $query->get()->map(fn($item) => $this->formatItem($item, 'event', 'Event & Kegiatan', $item->title));
            $submissions = $submissions->concat($evt);
        }

        // 5. Klub & Komunitas
        if ($type === 'all' || $type === 'community') {
            $query = Community::with(['user.verification']);
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $com = $query->get()->map(fn($item) => $this->formatItem($item, 'community', 'Klub & Komunitas', $item->name));
            $submissions = $submissions->concat($com);
        }

        // 6. Jual Cepat
        if ($type === 'all' || $type === 'quick_sale') {
            $query = QuickSale::with(['user.verification', 'media']);
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $qs = $query->get()->map(fn($item) => $this->formatItem($item, 'quick_sale', 'Jual Cepat', $item->title));
            $submissions = $submissions->concat($qs);
        }

        // Search Filter
        if (!empty($search)) {
            $submissions = $submissions->filter(function($item) use ($search) {
                return str_contains(strtolower($item['title'] ?? ''), $search)
                    || str_contains(strtolower($item['author_name'] ?? ''), $search)
                    || str_contains(strtolower($item['author_email'] ?? ''), $search)
                    || str_contains(strtolower($item['type_label'] ?? ''), $search)
                    || str_contains(strtolower($item['district'] ?? ''), $search)
                    || str_contains(strtolower($item['meta_secondary'] ?? ''), $search);
            })->values();
        }

        // Sorting
        $submissions = match ($sort) {
            'oldest' => $submissions->sortBy('created_at')->values(),
            'title_asc' => $submissions->sortBy(fn($i) => strtolower($i['title']))->values(),
            'author_asc' => $submissions->sortBy(fn($i) => strtolower($i['author_name']))->values(),
            default => $submissions->sortByDesc('created_at')->values(),
        };

        // Pagination
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $submissions->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedSubmissions = new LengthAwarePaginator(
            $currentItems,
            $submissions->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        // Stats calculation across models
        $stats = [
            'total' => JobVacancy::count() + Business::count() + CulinaryPlace::count() + Event::count() + Community::count() + QuickSale::count(),
            'pending' => JobVacancy::where('status', 'pending')->count()
                + Business::where('status', 'pending')->count()
                + CulinaryPlace::where('status', 'pending')->count()
                + Event::where('status', 'pending')->count()
                + Community::where('status', 'pending')->count()
                + QuickSale::where('status', 'pending')->count(),
            'published' => JobVacancy::where('status', 'published')->count()
                + Business::where('status', 'published')->count()
                + CulinaryPlace::where('status', 'published')->count()
                + Event::where('status', 'published')->count()
                + Community::where('status', 'published')->count()
                + QuickSale::where('status', 'published')->count(),
            'rejected' => JobVacancy::where('status', 'rejected')->count()
                + Business::where('status', 'rejected')->count()
                + CulinaryPlace::where('status', 'rejected')->count()
                + Event::where('status', 'rejected')->count()
                + Community::where('status', 'rejected')->count()
                + QuickSale::where('status', 'rejected')->count(),
        ];

        // Counts for category tabs
        $pendingCounts = [
            'all' => $stats['pending'],
            'job' => JobVacancy::where('status', 'pending')->count(),
            'business' => Business::where('status', 'pending')->count(),
            'culinary' => CulinaryPlace::where('status', 'pending')->count(),
            'event' => Event::where('status', 'pending')->count(),
            'community' => Community::where('status', 'pending')->count(),
            'quick_sale' => QuickSale::where('status', 'pending')->count(),
        ];

        $totalCounts = [
            'all' => $stats['total'],
            'job' => JobVacancy::count(),
            'business' => Business::count(),
            'culinary' => CulinaryPlace::count(),
            'event' => Event::count(),
            'community' => Community::count(),
            'quick_sale' => QuickSale::count(),
        ];

        return view('admin.moderation.index', [
            'submissions' => $paginatedSubmissions,
            'type' => $type,
            'status' => $status,
            'sort' => $sort,
            'stats' => $stats,
            'pendingCounts' => $pendingCounts,
            'totalCounts' => $totalCounts,
        ]);
    }

    public function show(string $type, int $id)
    {
        $modelClass = $this->modelMap[$type] ?? null;
        if (!$modelClass) {
            abort(404, 'Jenis modul konten tidak ditemukan.');
        }

        $item = $modelClass::with(['user.profile', 'user.verification', 'moderator'])->findOrFail($id);
        if ($type === 'quick_sale') {
            $item->load('media');
        }

        $logs = ModerationLog::where('moderatable_type', $modelClass)
            ->where('moderatable_id', $id)
            ->with('moderator')
            ->latest()
            ->get();

        $typeMeta = $this->getTypeMetadata($type);

        return view('admin.moderation.show', compact('item', 'type', 'logs', 'typeMeta'));
    }

    public function approve(Request $request, string $type, int $id)
    {
        $modelClass = $this->modelMap[$type] ?? null;
        if (!$modelClass) {
            abort(404, 'Jenis modul konten tidak ditemukan.');
        }

        $item = $modelClass::findOrFail($id);
        $fromStatus = $item->status;
        $note = $request->input('note', 'Disetujui untuk publikasi publik.');

        $updateData = [
            'status' => 'published',
            'rejection_reason' => null,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'published_at' => now(),
        ];

        if ($type === 'quick_sale' && empty($item->expires_at)) {
            $updateData['expires_at'] = now()->addDays(30);
        }

        $item->update($updateData);

        ModerationLog::create([
            'user_id' => Auth::id(),
            'moderatable_type' => $modelClass,
            'moderatable_id' => $id,
            'from_status' => $fromStatus,
            'to_status' => 'published',
            'note' => $note,
        ]);

        AuditLog::record('submission_approved', class_basename($modelClass), $id, [
            'type' => $type,
            'title' => $item->title ?? $item->name,
            'note' => $note,
        ]);

        return redirect()->back()
            ->with('success', 'Konten "' . ($item->title ?? $item->name) . '" berhasil disetujui dan telah dipublikasikan ke website publik.');
    }

    public function reject(Request $request, string $type, int $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan konten wajib diisi agar pengirim memahami perbaikan yang diperlukan.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $modelClass = $this->modelMap[$type] ?? null;
        if (!$modelClass) {
            abort(404, 'Jenis modul konten tidak ditemukan.');
        }

        $item = $modelClass::findOrFail($id);
        $fromStatus = $item->status;
        $reason = $request->input('rejection_reason');

        $item->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
        ]);

        ModerationLog::create([
            'user_id' => Auth::id(),
            'moderatable_type' => $modelClass,
            'moderatable_id' => $id,
            'from_status' => $fromStatus,
            'to_status' => 'rejected',
            'note' => $reason,
        ]);

        AuditLog::record('submission_rejected', class_basename($modelClass), $id, [
            'type' => $type,
            'title' => $item->title ?? $item->name,
            'reason' => $reason,
        ]);

        return redirect()->back()
            ->with('success', 'Konten "' . ($item->title ?? $item->name) . '" telah ditolak dengan catatan alasan tersimpan.');
    }

    public function destroy(Request $request, string $type, int $id)
    {
        $modelClass = $this->modelMap[$type] ?? null;
        if (!$modelClass) {
            abort(404, 'Jenis modul konten tidak ditemukan.');
        }

        $item = $modelClass::findOrFail($id);
        $title = $item->title ?? $item->name;

        // Delete related logs
        ModerationLog::where('moderatable_type', $modelClass)->where('moderatable_id', $id)->delete();

        // Delete QuickSale media if any
        if ($type === 'quick_sale' && method_exists($item, 'media')) {
            $item->media()->delete();
        }

        $item->delete();

        AuditLog::record('submission_deleted', class_basename($modelClass), $id, [
            'type' => $type,
            'title' => $title,
        ]);

        return redirect()->route('admin.moderation.index', ['type' => $type])
            ->with('success', 'Submission konten "' . $title . '" berhasil dihapus dari sistem.');
    }

    protected function formatItem($item, string $type, string $typeLabel, string $title): array
    {
        $metaSecondary = '';
        $district = '';
        $photoUrl = null;
        $publicRoute = null;

        switch ($type) {
            case 'job':
                $metaSecondary = $item->company ?? 'Perusahaan';
                $district = $item->location ?? '';
                $photoUrl = $item->photo_url ?? null;
                $publicRoute = route('jobs.show', $item->slug ?? $item->id);
                break;
            case 'business':
                $metaSecondary = $item->category ?? 'Usaha';
                $district = $item->location_district ?? '';
                $photoUrl = $item->photo_url ?? null;
                $publicRoute = route('businesses.show', $item->slug ?? $item->id);
                break;
            case 'culinary':
                $metaSecondary = $item->culinary_type ?? 'Kuliner';
                $district = $item->location_district ?? '';
                $photoUrl = $item->photo_url ?? null;
                $publicRoute = route('culinary.show', $item->slug ?? $item->id);
                break;
            case 'event':
                $metaSecondary = $item->category ?? ($item->organizer ?? 'Event');
                $district = $item->location_name ?? '';
                $photoUrl = $item->poster_image ? asset('storage/' . $item->poster_image) : null;
                $publicRoute = route('events.show', $item->slug ?? $item->id);
                break;
            case 'community':
                $metaSecondary = $item->interest_category ?? 'Komunitas';
                $district = $item->base_location ?? '';
                $photoUrl = $item->photo ? asset('storage/' . $item->photo) : null;
                $publicRoute = route('communities.show', $item->slug ?? $item->id);
                break;
            case 'quick_sale':
                $metaSecondary = $item->category ?? 'Barang';
                $district = $item->location_name ?? '';
                $primaryMedia = $item->primaryMedia();
                $photoUrl = $primaryMedia ? asset('storage/' . $primaryMedia->file_path) : null;
                $publicRoute = route('quick-sales.show', $item->slug ?? $item->id);
                break;
        }

        $typeMeta = $this->getTypeMetadata($type);

        return [
            'id' => $item->id,
            'type' => $type,
            'type_label' => $typeLabel,
            'type_icon' => $typeMeta['icon'],
            'type_color' => $typeMeta['color'],
            'type_badge_bg' => $typeMeta['badge_bg'],
            'title' => $title,
            'meta_secondary' => $metaSecondary,
            'district' => $district,
            'photo_url' => $photoUrl,
            'public_url' => $publicRoute,
            'author_name' => $item->user->name ?? 'Warga Kutai Kartanegara',
            'author_email' => $item->user->email ?? '-',
            'author_phone' => $item->user->phone ?? ($item->phone_whatsapp ?? ($item->contact_phone ?? null)),
            'author_is_verified' => ($item->user->status ?? '') === 'verified',
            'created_at' => $item->created_at,
            'status' => $item->status,
            'rejection_reason' => $item->rejection_reason ?? null,
            'slug' => $item->slug ?? null,
            'raw' => $item,
        ];
    }

    protected function getTypeMetadata(string $type): array
    {
        return match ($type) {
            'job' => [
                'name' => 'Bursa Kerja',
                'icon' => 'briefcase',
                'color' => 'text-blue-500',
                'badge_bg' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                'border_color' => 'border-blue-500/30',
                'public_prefix' => '/bursa-kerja/',
            ],
            'business' => [
                'name' => 'Produk & Jasa',
                'icon' => 'store',
                'color' => 'text-emerald-500',
                'badge_bg' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                'border_color' => 'border-emerald-500/30',
                'public_prefix' => '/produk-jasa/',
            ],
            'culinary' => [
                'name' => 'Kuliner Khas',
                'icon' => 'utensils',
                'color' => 'text-rose-500',
                'badge_bg' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                'border_color' => 'border-rose-500/30',
                'public_prefix' => '/kuliner/',
            ],
            'event' => [
                'name' => 'Event & Agenda',
                'icon' => 'calendar',
                'color' => 'text-purple-500',
                'badge_bg' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                'border_color' => 'border-purple-500/30',
                'public_prefix' => '/event/',
            ],
            'community' => [
                'name' => 'Klub & Komunitas',
                'icon' => 'users-2',
                'color' => 'text-indigo-500',
                'badge_bg' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                'border_color' => 'border-indigo-500/30',
                'public_prefix' => '/komunitas/',
            ],
            'quick_sale' => [
                'name' => 'Jual Cepat Warga',
                'icon' => 'tag',
                'color' => 'text-amber-500',
                'badge_bg' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                'border_color' => 'border-amber-500/30',
                'public_prefix' => '/jual-cepat/',
            ],
            default => [
                'name' => 'Konten Warga',
                'icon' => 'inbox',
                'color' => 'text-brand-gold',
                'badge_bg' => 'bg-brand-gold/10 text-brand-gold border-brand-gold/20',
                'border_color' => 'border-brand-gold/30',
                'public_prefix' => '/',
            ],
        };
    }
}
