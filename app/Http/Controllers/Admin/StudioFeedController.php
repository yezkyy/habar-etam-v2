<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\QuickSale;
use App\Models\Event;
use App\Models\JobVacancy;
use App\Models\MarketPrice;
use App\Models\CulturalDestination;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudioFeedController extends Controller
{
    /**
     * Build the unified real-time broadcast feed pool from all content sources.
     */
    private function buildFeedPool(): array
    {
        // 1. Lapor Etam (Prioritize live agenda, processing editorial, and verified/investigating)
        $reportsQuery = Report::with(['media', 'user'])
            ->whereIn('status', ['live_agenda', 'processing_editorial', 'pending_verification', 'investigating', 'verified'])
            ->latest('updated_at');

        $wpm = (int) \App\Models\SystemSetting::get('studio_reading_wpm', 130);
        if ($wpm <= 0) $wpm = 130;

        $allReports = $reportsQuery->get()->map(function ($r) use ($wpm) {
            $script = $r->editorial_summary ?: "LAPOR ETAM KUKAR: {$r->title}. Dilaporkan berlokasi di {$r->address}, Kecamatan {$r->location_district}. Kategori pengaduan: {$r->category}. Status penanganan saat ini: {$r->status_label}. Tim redaksi Habar Etam SCM terus memantau tindak lanjut dinas terkait.";
            $words = str_word_count(strip_tags($script));
            $estSeconds = max(5, round(($words / $wpm) * 60));

            $firstImage = null;
            if ($r->media && $r->media->count() > 0) {
                $firstMedia = $r->media->first();
                $firstImage = $firstMedia->file_path ? (str_starts_with($firstMedia->file_path, 'http') ? $firstMedia->file_path : asset('storage/' . $firstMedia->file_path)) : null;
            }

            return [
                'id' => $r->id,
                'source' => 'Lapor Etam',
                'source_code' => 'reports',
                'badge_color' => $r->is_featured_live ? 'bg-rose-100 text-rose-800 border-rose-200' : 'bg-blue-100 text-blue-900 border-blue-200',
                'badge_icon' => 'alert-triangle',
                'title' => "[{$r->ticket_number}] {$r->title}",
                'subtitle' => "Kec. {$r->location_district} • {$r->address}",
                'description' => $r->description,
                'editorial_script' => $script,
                'word_count' => $words,
                'reading_seconds' => $estSeconds,
                'is_featured_live' => (bool) $r->is_featured_live,
                'date' => $r->updated_at ?? $r->created_at,
                'type' => 'reports',
                'district' => $r->location_district,
                'image' => $firstImage,
                'media_count' => $r->media ? $r->media->count() : 0,
                'detail_url' => route('admin.reports.show', $r->id),
            ];
        });

        // 2. Events / Agenda Acara Kukar
        $eventsQuery = Event::where('status', 'published')
            ->whereDate('start_date', '>=', Carbon::today()->subDays(2))
            ->orderBy('start_date', 'asc');

        $allEvents = $eventsQuery->get()->map(function ($e) use ($wpm) {
            $dateStr = $e->start_date ? $e->start_date->translatedFormat('l, d F Y') : 'Waktu Segera Hadir';
            $script = "AGENDA ACARA KUKAR: {$e->title} dijadwalkan berlangsung pada {$dateStr} bertempat di {$e->location_name}, Kecamatan {$e->location_district}. Kegiatan diselenggarakan oleh {$e->organizer}. Warga diundang berpartisipasi.";
            $words = str_word_count(strip_tags($script));
            $estSeconds = max(5, round(($words / $wpm) * 60));

            $image = $e->photo ? (str_starts_with($e->photo, 'http') ? $e->photo : asset('storage/' . $e->photo)) : null;

            return [
                'id' => $e->id,
                'source' => 'Agenda Event',
                'source_code' => 'events',
                'badge_color' => 'bg-purple-100 text-purple-900 border-purple-200',
                'badge_icon' => 'calendar',
                'title' => $e->title,
                'subtitle' => "Penyelenggara: {$e->organizer} • {$e->location_name} (Kec. {$e->location_district})",
                'description' => $e->description,
                'editorial_script' => $script,
                'word_count' => $words,
                'reading_seconds' => $estSeconds,
                'is_featured_live' => false,
                'date' => $e->start_date ?? $e->created_at,
                'type' => 'events',
                'district' => $e->location_district,
                'image' => $image,
                'media_count' => $image ? 1 : 0,
                'detail_url' => route('events.show', $e->slug),
            ];
        });

        // 3. Market Prices / Pantauan Harga Pangan
        $pricesQuery = MarketPrice::latest('recorded_date')->latest('id')->take(15);
        $allPrices = $pricesQuery->get()->map(function ($p) use ($wpm) {
            $formattedPrice = "Rp " . number_format($p->price, 0, ',', '.');
            $diffText = "";
            if ($p->previous_price) {
                $diff = $p->price - $p->previous_price;
                if ($diff > 0) {
                    $diffText = " (Naik Rp " . number_format($diff, 0, ',', '.') . " dari sebelumnya Rp " . number_format($p->previous_price, 0, ',', '.') . ")";
                } elseif ($diff < 0) {
                    $diffText = " (Turun Rp " . number_format(abs($diff), 0, ',', '.') . " dari sebelumnya Rp " . number_format($p->previous_price, 0, ',', '.') . ")";
                } else {
                    $diffText = " (Harga Stabil)";
                }
            }

            $dateStr = $p->recorded_date ? $p->recorded_date->translatedFormat('d F Y') : 'Hari Ini';
            $script = "INFO HARGA PANGAN KUKAR ({$dateStr}): Komoditas {$p->commodity_name} di {$p->market_name} diperdagangkan seharga {$formattedPrice} per {$p->unit}{$diffText}. {$p->notes}";
            $words = str_word_count(strip_tags($script));
            $estSeconds = max(5, round(($words / $wpm) * 60));

            return [
                'id' => $p->id,
                'source' => 'Harga Pangan',
                'source_code' => 'market',
                'badge_color' => 'bg-emerald-100 text-emerald-900 border-emerald-200',
                'badge_icon' => 'shopping-cart',
                'title' => "{$p->commodity_name} — {$formattedPrice} / {$p->unit}",
                'subtitle' => "Pasar: {$p->market_name} • Pantauan: {$dateStr}",
                'description' => $p->notes ?: 'Data resmi pantauan berkala Dinas Perdagangan / Pengelola Pasar Kukar.',
                'editorial_script' => $script,
                'word_count' => $words,
                'reading_seconds' => $estSeconds,
                'is_featured_live' => false,
                'date' => $p->recorded_date ?? $p->created_at,
                'type' => 'market',
                'district' => 'Kutai Kartanegara',
                'image' => null,
                'media_count' => 0,
                'detail_url' => route('admin.smart-city.prices.index', ['q' => $p->commodity_name]),
            ];
        });

        // 4. Jual Cepat Warga
        $quickSalesQuery = QuickSale::where('status', 'published')
            ->latest('published_at')
            ->take(12);

        $allSales = $quickSalesQuery->get()->map(function ($qs) use ($wpm) {
            $script = "SEKILAS JUAL CEPAT ETAM: {$qs->title} ditawarkan dengan harga {$qs->formatted_price}. Kondisi barang: {$qs->condition}, berlokasi di {$qs->location_name} (Kec. {$qs->location_district}). Warga yang berminat dapat menghubungi kontak: {$qs->contact_phone}.";
            $words = str_word_count(strip_tags($script));
            $estSeconds = max(5, round(($words / $wpm) * 60));

            $image = $qs->photo ? (str_starts_with($qs->photo, 'http') ? $qs->photo : asset('storage/' . $qs->photo)) : null;

            return [
                'id' => $qs->id,
                'source' => 'Jual Cepat',
                'source_code' => 'quick_sales',
                'badge_color' => 'bg-amber-100 text-amber-900 border-amber-200',
                'badge_icon' => 'tag',
                'title' => "{$qs->title} — {$qs->formatted_price}",
                'subtitle' => "Kondisi: {$qs->condition} • Lokasi: {$qs->location_name} (Kec. {$qs->location_district})",
                'description' => $qs->description,
                'editorial_script' => $script,
                'word_count' => $words,
                'reading_seconds' => $estSeconds,
                'is_featured_live' => false,
                'date' => $qs->published_at ?? $qs->created_at,
                'type' => 'quick_sales',
                'district' => $qs->location_district,
                'image' => $image,
                'media_count' => $image ? 1 : 0,
                'detail_url' => route('quick-sales.show', $qs->slug),
            ];
        });

        // 5. Bursa Kerja Kukar
        $jobsQuery = JobVacancy::where('status', 'published')
            ->latest('published_at')
            ->take(10);

        $allJobs = $jobsQuery->get()->map(function ($job) use ($wpm) {
            $salary = $job->salary_range ?: 'Gaji Kompetitif';
            $script = "BURSA KERJA KUKAR: {$job->company} membuka lowongan posisi {$job->title} untuk penempatan {$job->location}. Tipe pekerjaan: {$job->employment_type} dengan estimasi salary {$salary}. Kontak lamaran: {$job->contact_email} atau {$job->contact_phone}.";
            $words = str_word_count(strip_tags($script));
            $estSeconds = max(5, round(($words / $wpm) * 60));

            $image = $job->photo ? (str_starts_with($job->photo, 'http') ? $job->photo : asset('storage/' . $job->photo)) : null;

            return [
                'id' => $job->id,
                'source' => 'Bursa Kerja',
                'source_code' => 'jobs',
                'badge_color' => 'bg-cyan-100 text-cyan-900 border-cyan-200',
                'badge_icon' => 'briefcase',
                'title' => "{$job->title} — {$job->company}",
                'subtitle' => "Lokasi: {$job->location} • Tipe: {$job->employment_type} • {$salary}",
                'description' => $job->description,
                'editorial_script' => $script,
                'word_count' => $words,
                'reading_seconds' => $estSeconds,
                'is_featured_live' => false,
                'date' => $job->published_at ?? $job->created_at,
                'type' => 'jobs',
                'district' => $job->location,
                'image' => $image,
                'media_count' => $image ? 1 : 0,
                'detail_url' => route('jobs.show', $job->slug),
            ];
        });

        // 6. Budaya & Pariwisata Kukar
        $cultureQuery = CulturalDestination::where('status', 'published')
            ->latest('updated_at')
            ->take(8);

        $allCulture = $cultureQuery->get()->map(function ($c) use ($wpm) {
            $script = "KHASANAH BUDAYA KUKAR: {$c->title}, destinasi cagar budaya dan wisata di {$c->address}, Kecamatan {$c->location_district}. Jam buka: {$c->operating_info}. {$c->description}";
            $words = str_word_count(strip_tags($script));
            $estSeconds = max(5, round(($words / $wpm) * 60));

            $image = $c->cover_image ? (str_starts_with($c->cover_image, 'http') ? $c->cover_image : asset('storage/' . $c->cover_image)) : null;

            return [
                'id' => $c->id,
                'source' => 'Cagar Budaya',
                'source_code' => 'culture',
                'badge_color' => 'bg-yellow-100 text-yellow-900 border-yellow-200',
                'badge_icon' => 'landmark',
                'title' => $c->title,
                'subtitle' => "Kategori: " . ucfirst(str_replace('_', ' ', $c->category)) . " • Kec. {$c->location_district}",
                'description' => $c->description,
                'editorial_script' => $script,
                'word_count' => $words,
                'reading_seconds' => $estSeconds,
                'is_featured_live' => false,
                'date' => $c->updated_at ?? $c->created_at,
                'type' => 'culture',
                'district' => $c->location_district,
                'image' => $image,
                'media_count' => $image ? 1 : 0,
                'detail_url' => route('smart-city.culture.show', $c->slug),
            ];
        });

        return [
            'reports' => $allReports,
            'events' => $allEvents,
            'market' => $allPrices,
            'quick_sales' => $allSales,
            'jobs' => $allJobs,
            'culture' => $allCulture,
        ];
    }

    public function index(Request $request)
    {
        $feedType = $request->input('type', 'all');
        $search = trim($request->input('q', ''));

        $pool = $this->buildFeedPool();

        // Merge all sources
        $allFeedPool = collect()
            ->concat($pool['reports'])
            ->concat($pool['events'])
            ->concat($pool['market'])
            ->concat($pool['quick_sales'])
            ->concat($pool['jobs'])
            ->concat($pool['culture']);

        // Stats calculation
        $stats = [
            'total_items' => $allFeedPool->count(),
            'live_count' => $pool['reports']->where('is_featured_live', true)->count(),
            'reports_count' => $pool['reports']->count(),
            'events_count' => $pool['events']->count(),
            'market_count' => $pool['market']->count(),
            'sales_count' => $pool['quick_sales']->count(),
            'jobs_count' => $pool['jobs']->count(),
            'culture_count' => $pool['culture']->count(),
        ];

        // Filter by feedType
        if ($feedType === 'live') {
            $feedItems = $allFeedPool->where('is_featured_live', true);
        } elseif (isset($pool[$feedType])) {
            $feedItems = $pool[$feedType];
        } else {
            $feedItems = $allFeedPool;
        }

        // Apply search filter if query is present
        if ($search !== '') {
            $feedItems = $feedItems->filter(function ($item) use ($search) {
                return Str::contains(Str::lower($item['title']), Str::lower($search))
                    || Str::contains(Str::lower($item['editorial_script']), Str::lower($search))
                    || Str::contains(Str::lower($item['subtitle']), Str::lower($search))
                    || Str::contains(Str::lower($item['description']), Str::lower($search))
                    || Str::contains(Str::lower($item['district'] ?? ''), Str::lower($search));
            });
        }

        // Sort: is_featured_live first, then latest date
        $feedItems = $feedItems->sort(function ($a, $b) {
            if ($a['is_featured_live'] && !$b['is_featured_live']) {
                return -1;
            }
            if (!$a['is_featured_live'] && $b['is_featured_live']) {
                return 1;
            }
            return $b['date'] <=> $a['date'];
        })->values();

        // Separate Live Rundown Items for sticky live bar
        $liveRundownItems = $pool['reports']->where('is_featured_live', true)->values();

        AuditLog::record('studio_feed_accessed', 'StudioFeed', null, [
            'filter' => $feedType,
            'search' => $search,
        ]);

        return view('admin.studio.feed', [
            'feedItems' => $feedItems,
            'feedType' => $feedType,
            'searchQuery' => $search,
            'stats' => $stats,
            'liveRundownItems' => $liveRundownItems,
        ]);
    }

    /**
     * Dedicated Fullscreen Broadcast Teleprompter Page (Opened in New Browser Tab).
     */
    public function teleprompter(Request $request)
    {
        $mode = $request->input('mode', 'all'); // 'live', 'all', 'reports', 'events', 'market', etc.
        $targetType = $request->input('type');
        $targetId = $request->input('id');
        $search = trim($request->input('q', ''));

        $pool = $this->buildFeedPool();

        $allFeedPool = collect()
            ->concat($pool['reports'])
            ->concat($pool['events'])
            ->concat($pool['market'])
            ->concat($pool['quick_sales'])
            ->concat($pool['jobs'])
            ->concat($pool['culture']);

        $playlist = collect();

        // If specific item is requested
        if ($targetType && $targetId) {
            $selectedItem = $allFeedPool->first(function ($item) use ($targetType, $targetId) {
                return $item['type'] === $targetType && (string) $item['id'] === (string) $targetId;
            });

            if ($selectedItem) {
                // Put selected item first, then follow with other relevant items
                $otherItems = $allFeedPool->filter(function ($item) use ($targetType, $targetId) {
                    return !($item['type'] === $targetType && (string) $item['id'] === (string) $targetId);
                });
                $playlist = collect([$selectedItem])->concat($otherItems);
            }
        }

        if ($playlist->isEmpty()) {
            if ($mode === 'live') {
                $playlist = $pool['reports']->where('is_featured_live', true)->values();
                // If no live item, fallback to all reports
                if ($playlist->isEmpty()) {
                    $playlist = $pool['reports']->values();
                }
            } elseif (isset($pool[$mode])) {
                $playlist = $pool[$mode];
            } else {
                $playlist = $allFeedPool->sort(function ($a, $b) {
                    if ($a['is_featured_live'] && !$b['is_featured_live']) {
                        return -1;
                    }
                    if (!$a['is_featured_live'] && $b['is_featured_live']) {
                        return 1;
                    }
                    return $b['date'] <=> $a['date'];
                })->values();
            }
        }

        if ($search !== '') {
            $playlist = $playlist->filter(function ($item) use ($search) {
                return Str::contains(Str::lower($item['title']), Str::lower($search))
                    || Str::contains(Str::lower($item['editorial_script']), Str::lower($search))
                    || Str::contains(Str::lower($item['subtitle']), Str::lower($search))
                    || Str::contains(Str::lower($item['district'] ?? ''), Str::lower($search));
            })->values();
        }

        AuditLog::record('studio_teleprompter_launched', 'Teleprompter', null, [
            'mode' => $mode,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'count' => $playlist->count(),
        ]);

        return view('admin.studio.teleprompter', [
            'playlist' => $playlist->values(),
            'mode' => $mode,
            'targetType' => $targetType,
            'targetId' => $targetId,
        ]);
    }

    public function toggleLiveAgenda(Request $request, Report $report)
    {
        $newFlag = !$report->is_featured_live;
        $report->update([
            'is_featured_live' => $newFlag,
            'status' => $newFlag ? 'live_agenda' : $report->status,
        ]);

        AuditLog::record('studio_feed_live_toggled', 'Report', $report->id, [
            'ticket' => $report->ticket_number,
            'featured' => $newFlag,
        ]);

        $msg = $newFlag
            ? "Laporan [{$report->ticket_number}] berhasil dimasukkan ke Live Agenda Siaran Studio PT SCM."
            : "Laporan [{$report->ticket_number}] dikeluarkan dari agenda siaran live.";

        return back()->with('success', $msg);
    }

    public function updateScript(Request $request, Report $report)
    {
        $validated = $request->validate([
            'editorial_summary' => ['required', 'string', 'max:2000'],
        ]);

        $report->update([
            'editorial_summary' => $validated['editorial_summary'],
        ]);

        AuditLog::record('studio_script_updated', 'Report', $report->id, [
            'ticket' => $report->ticket_number,
        ]);

        return back()->with('success', "Naskah siaran untuk [{$report->ticket_number}] berhasil diperbarui.");
    }
}
