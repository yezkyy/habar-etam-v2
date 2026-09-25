<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use App\Models\QuickSale;
use App\Models\MarketPrice;
use App\Models\JobVacancy;
use App\Models\Event;
use App\Models\EnvironmentPoint;
use App\Models\EmergencyContact;
use App\Models\CulturalDestination;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Districts in Kutai Kartanegara for filtering
     */
    protected array $districts = [
        'Tenggarong', 'Tenggarong Seberang', 'Loa Kulu', 'Loa Janan',
        'Samboja', 'Samboja Barat', 'Muara Jawa', 'Sanga-Sanga',
        'Anggana', 'Muara Badak', 'Marangkayu', 'Sebulu',
        'Muara Kaman', 'Kota Bangun', 'Kota Bangun Darat', 'Muara Muntai',
        'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
    ];

    /**
     * Display the Export Dashboard
     */
    public function index(Request $request)
    {
        $registry = $this->getDatasetRegistry();
        $selectedCategory = $request->query('category', 'all');
        $searchQuery = trim($request->query('q', ''));
        $datePreset = $request->query('date_preset', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $selectedDistrict = $request->query('district');

        // Calculate counts and date ranges
        $datasets = [];
        $totalSystemRows = 0;

        foreach ($registry as $key => $meta) {
            $count = ($meta['count_query'])($startDate, $endDate, $selectedDistrict);
            $totalSystemRows += $count;

            $meta['key'] = $key;
            $meta['current_count'] = $count;
            $meta['formatted_count'] = number_format($count, 0, ',', '.');
            $meta['estimated_size'] = $this->formatEstimatedSize($count, count($meta['columns']));

            $datasets[$key] = $meta;
        }

        // Global stats summary
        $globalStats = [
            'total_datasets' => count($datasets),
            'total_records' => number_format($totalSystemRows, 0, ',', '.'),
            'last_export' => AuditLog::where('action', 'data_exported')->latest('created_at')->first()?->created_at?->diffForHumans() ?? 'Belum ada catatan',
            'export_count_today' => AuditLog::where('action', 'data_exported')->whereDate('created_at', today())->count(),
        ];

        // Recent exports log for admin history
        $recentExports = AuditLog::with('user')
            ->where('action', 'data_exported')
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('admin.export.index', [
            'datasets' => $datasets,
            'globalStats' => $globalStats,
            'recentExports' => $recentExports,
            'districts' => $this->districts,
            'selectedCategory' => $selectedCategory,
            'searchQuery' => $searchQuery,
            'datePreset' => $datePreset,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedDistrict' => $selectedDistrict,
        ]);
    }

    /**
     * Return instant preview data for slide-over drawer / modal
     */
    public function preview(Request $request, string $type): JsonResponse
    {
        $registry = $this->getDatasetRegistry();

        if (!isset($registry[$type])) {
            return response()->json([
                'success' => false,
                'message' => 'Dataset tidak ditemukan.'
            ], 404);
        }

        $meta = $registry[$type];
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $district = $request->query('district');

        $totalCount = ($meta['count_query'])($startDate, $endDate, $district);
        $sampleRows = ($meta['sample_query'])($startDate, $endDate, $district, 5);

        return response()->json([
            'success' => true,
            'key' => $type,
            'title' => $meta['title'],
            'category_name' => $meta['category_name'],
            'columns' => $meta['columns'],
            'total_rows' => $totalCount,
            'formatted_total' => number_format($totalCount, 0, ',', '.'),
            'sample_data' => $sampleRows,
            'filename' => "habar_etam_{$type}_" . date('Ymd_His') . ".csv",
        ]);
    }

    /**
     * Download formatted CSV stream with UTF-8 BOM, metadata, and chunked DB reads
     */
    public function download(Request $request, string $type): StreamedResponse
    {
        $registry = $this->getDatasetRegistry();

        if (!isset($registry[$type])) {
            abort(404, 'Dataset ekspor tidak ditemukan.');
        }

        $meta = $registry[$type];
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $district = $request->query('district');
        $delimiter = $request->query('delimiter', ',') === ';' ? ';' : ',';
        $includeMeta = $request->boolean('include_metadata', true);

        // Record Audit Log
        AuditLog::record('data_exported', 'Export', null, [
            'dataset' => $type,
            'dataset_name' => $meta['title'],
            'delimiter' => $delimiter,
            'filters' => array_filter([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'district' => $district,
            ]),
        ]);

        $timestamp = date('Ymd_His');
        $filename = "habar_etam_export_{$type}_{$timestamp}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($meta, $type, $startDate, $endDate, $district, $delimiter, $includeMeta) {
            $handle = fopen('php://output', 'w');

            // 1. Output UTF-8 BOM so Excel & Sheets open Indonesian UTF-8 without garbled characters
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            $totalMatching = ($meta['count_query'])($startDate, $endDate, $district);
            $user = auth()->user();

            // 2. Output Professional Metadata Header Block if requested
            if ($includeMeta) {
                fputcsv($handle, ['# =========================================================================='], $delimiter);
                fputcsv($handle, ['# LAPORAN EKSPOR RESMI - HABAR ETAM KUTAI KARTANEGARA'], $delimiter);
                fputcsv($handle, ["# DATASET: {$meta['title']} ({$meta['category_name']})"], $delimiter);
                fputcsv($handle, ["# TANGGAL UNDUH: " . Carbon::now()->isoFormat('dddd, D MMMM Y - HH:mm:ss') . " WITA"], $delimiter);
                fputcsv($handle, ["# OPERATOR: " . ($user ? "{$user->name} ({$user->email})" : 'Sistem Administrator')], $delimiter);
                fputcsv($handle, ["# TOTAL REKAP: " . number_format($totalMatching, 0, ',', '.') . " Baris Data"], $delimiter);
                if ($startDate || $endDate || $district) {
                    $filterParts = [];
                    if ($startDate) $filterParts[] = "Dari: {$startDate}";
                    if ($endDate) $filterParts[] = "Sampai: {$endDate}";
                    if ($district) $filterParts[] = "Kecamatan: {$district}";
                    fputcsv($handle, ["# FILTER AKTIF: " . implode(' | ', $filterParts)], $delimiter);
                }
                fputcsv($handle, ['# PERLINDUNGAN DATA: Data sensitif NIK & Keamanan diproteksi sesuai UU PDP No. 27/2022'], $delimiter);
                fputcsv($handle, ['# =========================================================================='], $delimiter);
                // Blank separator row
                fputcsv($handle, [], $delimiter);
            }

            // 3. Output Column Headers
            fputcsv($handle, $meta['columns'], $delimiter);

            // 4. Stream data chunks from DB with proper cell formatting
            ($meta['stream_query'])($handle, $delimiter, $startDate, $endDate, $district);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Comprehensive registry of all 10 datasets across Habar Etam
     */
    protected function getDatasetRegistry(): array
    {
        return [
            // 1. LAPOR ETAM
            'reports' => [
                'title' => 'Lapor Etam (Aduan Warga)',
                'category_key' => 'pelayanan',
                'category_name' => 'Pelayanan Publik',
                'icon' => 'megaphone',
                'color' => 'rose',
                'description' => 'Rekapitulasi nomor tiket pengaduan infrastruktur & fasilitas umum warga se-Kukar beserta status penanganan.',
                'columns' => [
                    'Nomor Tiket',
                    'Kategori Masalah',
                    'Judul Pengaduan',
                    'Nama Pelapor',
                    'Kontak Pelapor',
                    'Kecamatan',
                    'Alamat / Titik Lokasi',
                    'Status Workflow',
                    'Tayang Live Redaksi',
                    'Petugas Moderator',
                    'Ringkasan Redaksi',
                    'Tanggal Masuk (WITA)',
                    'Tanggal Ditindak (WITA)',
                    'Tanggal Selesai (WITA)',
                    'Catatan Penanganan',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = Report::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location_district', $district);
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = Report::with(['user', 'moderator']);
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location_district', $district);
                    return $q->latest('created_at')->limit($limit)->get()->map(function ($r) {
                        return [
                            $r->ticket_number,
                            $r->category,
                            $r->title,
                            $r->user->name ?? 'Warga (Anonim)',
                            $r->user->phone ?? '-',
                            $r->location_district ?? '-',
                            $r->address ?? '-',
                            $r->status_label,
                            $r->is_featured_live ? 'Ya (Live Studio)' : 'Tidak',
                            $r->moderator->name ?? '-',
                            $r->editorial_summary ?? '-',
                            $r->created_at->format('Y-m-d H:i'),
                            $r->moderated_at ? $r->moderated_at->format('Y-m-d H:i') : '-',
                            $r->resolved_at ? $r->resolved_at->format('Y-m-d H:i') : '-',
                            $r->admin_notes ?? '-',
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = Report::with(['user', 'moderator']);
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location_district', $district);

                    $q->latest('created_at')->chunk(100, function ($items) use ($handle, $delimiter) {
                        foreach ($items as $r) {
                            fputcsv($handle, [
                                $r->ticket_number,
                                $r->category,
                                $r->title,
                                $r->user->name ?? 'Warga (Anonim)',
                                $r->user->phone ?? '-',
                                $r->location_district ?? '-',
                                $r->address ?? '-',
                                $r->status_label,
                                $r->is_featured_live ? 'Ya (Live Studio)' : 'Tidak',
                                $r->moderator->name ?? '-',
                                $r->editorial_summary ?? '-',
                                $r->created_at->format('Y-m-d H:i'),
                                $r->moderated_at ? $r->moderated_at->format('Y-m-d H:i') : '-',
                                $r->resolved_at ? $r->resolved_at->format('Y-m-d H:i') : '-',
                                $r->admin_notes ?? '-',
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 2. ANGGOTA & PENGGUNA
            'members' => [
                'title' => 'Data Anggota & Pengguna',
                'category_key' => 'pelayanan',
                'category_name' => 'Pelayanan Publik',
                'icon' => 'users',
                'color' => 'blue',
                'description' => 'Daftar akun warga terdaftar, nomor kontak, kecamatan domisili, dan status verifikasi NIK.',
                'columns' => [
                    'ID Pengguna',
                    'Nama Lengkap',
                    'Email Akun',
                    'Nomor Telepon / WA',
                    'Peran (Role)',
                    'Status Akun',
                    'Status Verifikasi NIK',
                    'Kecamatan Domisili',
                    'Kelurahan / Desa',
                    'Tanggal Lahir',
                    'Tanggal Bergabung (WITA)',
                    'Status Email Terverifikasi',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = User::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) {
                        $q->whereHas('profile', function ($p) use ($district) {
                            $p->where('district', $district);
                        });
                    }
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = User::with(['profile', 'verification']);
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) {
                        $q->whereHas('profile', function ($p) use ($district) {
                            $p->where('district', $district);
                        });
                    }
                    return $q->latest('created_at')->limit($limit)->get()->map(function ($u) {
                        $verStatus = match ($u->status) {
                            'verified' => 'Terverifikasi (NIK Valid)',
                            'pending' => 'Menunggu Verifikasi',
                            'rejected' => 'Verifikasi Ditolak',
                            default => 'Belum Mengajukan',
                        };
                        return [
                            $u->id,
                            $u->name,
                            $u->email,
                            $u->phone ?? '-',
                            strtoupper($u->role),
                            ucfirst($u->status ?? 'active'),
                            $verStatus,
                            $u->profile->district ?? '-',
                            $u->profile->village ?? '-',
                            $u->profile?->date_of_birth ? $u->profile->date_of_birth->format('Y-m-d') : '-',
                            $u->created_at->format('Y-m-d H:i'),
                            $u->email_verified_at ? 'Sudah Verifikasi' : 'Belum Verifikasi',
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = User::with(['profile', 'verification']);
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) {
                        $q->whereHas('profile', function ($p) use ($district) {
                            $p->where('district', $district);
                        });
                    }

                    $q->latest('created_at')->chunk(100, function ($users) use ($handle, $delimiter) {
                        foreach ($users as $u) {
                            $verStatus = match ($u->status) {
                                'verified' => 'Terverifikasi (NIK Valid)',
                                'pending' => 'Menunggu Verifikasi',
                                'rejected' => 'Verifikasi Ditolak',
                                default => 'Belum Mengajukan',
                            };
                            fputcsv($handle, [
                                $u->id,
                                $u->name,
                                $u->email,
                                $u->phone ?? '-',
                                strtoupper($u->role),
                                ucfirst($u->status ?? 'active'),
                                $verStatus,
                                $u->profile->district ?? '-',
                                $u->profile->village ?? '-',
                                $u->profile?->date_of_birth ? $u->profile->date_of_birth->format('Y-m-d') : '-',
                                $u->created_at->format('Y-m-d H:i'),
                                $u->email_verified_at ? 'Sudah Verifikasi' : 'Belum Verifikasi',
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 3. HARGA PANGAN PASAR
            'market_prices' => [
                'title' => 'Harga Pangan & Sembako',
                'category_key' => 'ekonomi',
                'category_name' => 'Ekonomi & Pasar',
                'icon' => 'shopping-cart',
                'color' => 'emerald',
                'description' => 'Data historis harga komoditas pangan dan sembako di pasar-pasar induk Kabupaten Kutai Kartanegara.',
                'columns' => [
                    'ID Pencatatan',
                    'Nama Pasar',
                    'Nama Komoditas',
                    'Kategori Pangan',
                    'Harga Terkini (Rp)',
                    'Harga Sebelumnya (Rp)',
                    'Perubahan Selisih (Rp)',
                    'Tren Pergerakan',
                    'Satuan Ukuran',
                    'Tanggal Pencatatan',
                    'Petugas Surveyor',
                    'Catatan Lapangan',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = MarketPrice::query();
                    if ($start) $q->whereDate('recorded_date', '>=', $start);
                    if ($end) $q->whereDate('recorded_date', '<=', $end);
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = MarketPrice::with('creator');
                    if ($start) $q->whereDate('recorded_date', '>=', $start);
                    if ($end) $q->whereDate('recorded_date', '<=', $end);
                    return $q->latest('recorded_date')->limit($limit)->get()->map(function ($p) {
                        $diff = $p->price_difference;
                        $trend = $diff > 0 ? 'Naik' : ($diff < 0 ? 'Turun' : 'Stabil');
                        return [
                            $p->id,
                            $p->market_name,
                            $p->commodity_name,
                            $p->category ?? 'Sembako',
                            $p->price,
                            $p->previous_price ?? 0,
                            $diff,
                            $trend,
                            $p->unit,
                            $p->recorded_date->format('Y-m-d'),
                            $p->creator->name ?? 'Dinas Ketahanan Pangan',
                            $p->notes ?? '-',
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = MarketPrice::with('creator');
                    if ($start) $q->whereDate('recorded_date', '>=', $start);
                    if ($end) $q->whereDate('recorded_date', '<=', $end);

                    $q->latest('recorded_date')->chunk(100, function ($prices) use ($handle, $delimiter) {
                        foreach ($prices as $p) {
                            $diff = $p->price_difference;
                            $trend = $diff > 0 ? 'Naik' : ($diff < 0 ? 'Turun' : 'Stabil');
                            fputcsv($handle, [
                                $p->id,
                                $p->market_name,
                                $p->commodity_name,
                                $p->category ?? 'Sembako',
                                $p->price,
                                $p->previous_price ?? 0,
                                $diff,
                                $trend,
                                $p->unit,
                                $p->recorded_date->format('Y-m-d'),
                                $p->creator->name ?? 'Dinas Ketahanan Pangan',
                                $p->notes ?? '-',
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 4. BURSA KERJA
            'jobs' => [
                'title' => 'Bursa Kerja & Loker',
                'category_key' => 'ekonomi',
                'category_name' => 'Ekonomi & Pasar',
                'icon' => 'briefcase',
                'color' => 'cyan',
                'description' => 'Daftar lowongan kerja lokal, nama perusahaan, kualifikasi, rentang gaji, dan batas akhir pendaftaran.',
                'columns' => [
                    'ID Loker',
                    'Posisi Pekerjaan',
                    'Perusahaan / Instansi',
                    'Tipe Pekerjaan',
                    'Lokasi Penempatan',
                    'Rentang Gaji',
                    'Batas Akhir Lamaran',
                    'Kontak / Email HRD',
                    'Status Lowongan',
                    'Tanggal Diterbitkan',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = JobVacancy::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location', 'LIKE', "%{$district}%");
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = JobVacancy::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location', 'LIKE', "%{$district}%");
                    return $q->latest('created_at')->limit($limit)->get()->map(function ($j) {
                        return [
                            $j->id,
                            $j->title,
                            $j->company,
                            $j->employment_type,
                            $j->location,
                            $j->salary_range ?? '-',
                            $j->deadline ? $j->deadline->format('Y-m-d') : 'Tanpa Batas',
                            $j->contact_phone ?? '-',
                            ucfirst($j->status ?? 'active'),
                            $j->created_at->format('Y-m-d H:i'),
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = JobVacancy::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location', 'LIKE', "%{$district}%");

                    $q->latest('created_at')->chunk(100, function ($jobs) use ($handle, $delimiter) {
                        foreach ($jobs as $j) {
                            fputcsv($handle, [
                                $j->id,
                                $j->title,
                                $j->company,
                                $j->employment_type,
                                $j->location,
                                $j->salary_range ?? '-',
                                $j->deadline ? $j->deadline->format('Y-m-d') : 'Tanpa Batas',
                                $j->contact_phone ?? '-',
                                ucfirst($j->status ?? 'active'),
                                $j->created_at->format('Y-m-d H:i'),
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 5. JUAL CEPAT (CLASSIFIEDS)
            'quick_sales' => [
                'title' => 'Jual Cepat (Pasar Warga)',
                'category_key' => 'ekonomi',
                'category_name' => 'Ekonomi & Pasar',
                'icon' => 'tag',
                'color' => 'amber',
                'description' => 'Listing jual-beli barang bekas/baru warga, harga jual, kondisi barang, dan informasi kontak penjual.',
                'columns' => [
                    'ID Iklan',
                    'Judul Barang',
                    'Kategori Barang',
                    'Harga (Rp)',
                    'Kondisi Barang',
                    'Wilayah / Lokasi',
                    'Kontak Penjual',
                    'Status Listing',
                    'Tanggal Publikasi',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = QuickSale::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location_name', 'LIKE', "%{$district}%");
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = QuickSale::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location_name', 'LIKE', "%{$district}%");
                    return $q->latest('created_at')->limit($limit)->get()->map(function ($i) {
                        return [
                            $i->id,
                            $i->title,
                            $i->category,
                            $i->price,
                            ucfirst($i->condition ?? 'Bekas'),
                            $i->location_name ?? '-',
                            $i->contact_phone ?? '-',
                            ucfirst($i->status ?? 'published'),
                            $i->published_at ? $i->published_at->format('Y-m-d H:i') : $i->created_at->format('Y-m-d H:i'),
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = QuickSale::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    if ($district) $q->where('location_name', 'LIKE', "%{$district}%");

                    $q->latest('created_at')->chunk(100, function ($items) use ($handle, $delimiter) {
                        foreach ($items as $i) {
                            fputcsv($handle, [
                                $i->id,
                                $i->title,
                                $i->category,
                                $i->price,
                                ucfirst($i->condition ?? 'Bekas'),
                                $i->location_name ?? '-',
                                $i->contact_phone ?? '-',
                                ucfirst($i->status ?? 'published'),
                                $i->published_at ? $i->published_at->format('Y-m-d H:i') : $i->created_at->format('Y-m-d H:i'),
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 6. AGENDA & ACARA
            'events' => [
                'title' => 'Agenda & Acara Daerah',
                'category_key' => 'smart_city',
                'category_name' => 'Smart City & Wilayah',
                'icon' => 'calendar',
                'color' => 'purple',
                'description' => 'Jadwal festival budaya, agenda olahraga, kegiatan pemerintahan, dan pameran UMKM se-Kukar.',
                'columns' => [
                    'ID Acara',
                    'Nama Acara',
                    'Kategori Kegiatan',
                    'Penyelenggara',
                    'Tanggal Mulai',
                    'Tanggal Selesai',
                    'Waktu / Jam',
                    'Nama Lokasi',
                    'Alamat Lengkap',
                    'Kontak Panitia',
                    'Status Publikasi',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = Event::query();
                    if ($start) $q->whereDate('start_date', '>=', $start);
                    if ($end) $q->whereDate('start_date', '<=', $end);
                    if ($district) $q->where('location_address', 'LIKE', "%{$district}%");
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = Event::query();
                    if ($start) $q->whereDate('start_date', '>=', $start);
                    if ($end) $q->whereDate('start_date', '<=', $end);
                    if ($district) $q->where('location_address', 'LIKE', "%{$district}%");
                    return $q->latest('start_date')->limit($limit)->get()->map(function ($e) {
                        return [
                            $e->id,
                            $e->title,
                            $e->category,
                            $e->organizer ?? '-',
                            $e->start_date ? $e->start_date->format('Y-m-d') : '-',
                            $e->end_date ? $e->end_date->format('Y-m-d') : '-',
                            $e->start_time ?? '08:00 WITA',
                            $e->location_name ?? '-',
                            $e->location_address ?? '-',
                            $e->contact_phone ?? '-',
                            ucfirst($e->status ?? 'published'),
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = Event::query();
                    if ($start) $q->whereDate('start_date', '>=', $start);
                    if ($end) $q->whereDate('start_date', '<=', $end);
                    if ($district) $q->where('location_address', 'LIKE', "%{$district}%");

                    $q->latest('start_date')->chunk(100, function ($events) use ($handle, $delimiter) {
                        foreach ($events as $e) {
                            fputcsv($handle, [
                                $e->id,
                                $e->title,
                                $e->category,
                                $e->organizer ?? '-',
                                $e->start_date ? $e->start_date->format('Y-m-d') : '-',
                                $e->end_date ? $e->end_date->format('Y-m-d') : '-',
                                $e->start_time ?? '08:00 WITA',
                                $e->location_name ?? '-',
                                $e->location_address ?? '-',
                                $e->contact_phone ?? '-',
                                ucfirst($e->status ?? 'published'),
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 7. TITIK PANTAU LINGKUNGAN & TPS
            'environment' => [
                'title' => 'Lingkungan & Titik TPS',
                'category_key' => 'smart_city',
                'category_name' => 'Smart City & Wilayah',
                'icon' => 'leaf',
                'color' => 'teal',
                'description' => 'Titik pantau Tempat Pembuangan Sampah (TPS), status kebersihan, dan peringatan lingkungan hidup.',
                'columns' => [
                    'ID Titik',
                    'Nama Titik / Fasilitas',
                    'Tipe Pantauan',
                    'Tingkat Urgensi / Kondisi',
                    'Kecamatan',
                    'Nama Lokasi',
                    'Deskripsi Pantauan',
                    'Sumber / Instansi',
                    'Petugas Pemutakhir',
                    'Tanggal Pembaruan',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = EnvironmentPoint::query();
                    if ($start) $q->whereDate('updated_at', '>=', $start);
                    if ($end) $q->whereDate('updated_at', '<=', $end);
                    if ($district) $q->where('location_district', $district);
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = EnvironmentPoint::with('updater');
                    if ($start) $q->whereDate('updated_at', '>=', $start);
                    if ($end) $q->whereDate('updated_at', '<=', $end);
                    if ($district) $q->where('location_district', $district);
                    return $q->latest('updated_at')->limit($limit)->get()->map(function ($env) {
                        return [
                            $env->id,
                            $env->title,
                            strtoupper($env->info_type ?? 'TPS'),
                            ucfirst($env->severity ?? 'Normal'),
                            $env->location_district ?? '-',
                            $env->location_name ?? '-',
                            $env->description ?? '-',
                            $env->source ?? 'DLHK Kukar',
                            $env->updater->name ?? 'Petugas Lingkungan',
                            $env->updated_at->format('Y-m-d H:i'),
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = EnvironmentPoint::with('updater');
                    if ($start) $q->whereDate('updated_at', '>=', $start);
                    if ($end) $q->whereDate('updated_at', '<=', $end);
                    if ($district) $q->where('location_district', $district);

                    $q->latest('updated_at')->chunk(100, function ($points) use ($handle, $delimiter) {
                        foreach ($points as $env) {
                            fputcsv($handle, [
                                $env->id,
                                $env->title,
                                strtoupper($env->info_type ?? 'TPS'),
                                ucfirst($env->severity ?? 'Normal'),
                                $env->location_district ?? '-',
                                $env->location_name ?? '-',
                                $env->description ?? '-',
                                $env->source ?? 'DLHK Kukar',
                                $env->updater->name ?? 'Petugas Lingkungan',
                                $env->updated_at->format('Y-m-d H:i'),
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 8. KONTAK DARURAT
            'emergency' => [
                'title' => 'Kontak Darurat Siaga 24 Jam',
                'category_key' => 'smart_city',
                'category_name' => 'Smart City & Wilayah',
                'icon' => 'phone-call',
                'color' => 'red',
                'description' => 'Direktori hotline darurat Damkar, RSUD/Ambulans, Polres, BPBD/SAR, PLN, dan PDAM di Kukar.',
                'columns' => [
                    'ID Kontak',
                    'Nama Posko / Instansi',
                    'Kategori Layanan',
                    'Nomor Telepon Hotline',
                    'Nomor WhatsApp Darurat',
                    'Kecamatan Wilayah',
                    'Alamat Markas / Posko',
                    'Status Siaga Operasional',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = EmergencyContact::query();
                    if ($district) $q->where('location_district', $district);
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = EmergencyContact::query();
                    if ($district) $q->where('location_district', $district);
                    return $q->orderBy('sort_order')->limit($limit)->get()->map(function ($em) {
                        return [
                            $em->id,
                            $em->name,
                            strtoupper(str_replace('_', ' ', $em->category)),
                            $em->phone ?? '-',
                            $em->whatsapp ?? '-',
                            $em->location_district ?? 'Semua Kecamatan',
                            $em->address ?? '-',
                            $em->is_active ? 'Siaga 24 Jam (Aktif)' : 'Nonaktif',
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = EmergencyContact::query();
                    if ($district) $q->where('location_district', $district);

                    $q->orderBy('sort_order')->chunk(100, function ($contacts) use ($handle, $delimiter) {
                        foreach ($contacts as $em) {
                            fputcsv($handle, [
                                $em->id,
                                $em->name,
                                strtoupper(str_replace('_', ' ', $em->category)),
                                $em->phone ?? '-',
                                $em->whatsapp ?? '-',
                                $em->location_district ?? 'Semua Kecamatan',
                                $em->address ?? '-',
                                $em->is_active ? 'Siaga 24 Jam (Aktif)' : 'Nonaktif',
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 9. CAGAR BUDAYA & WISATA
            'culture' => [
                'title' => 'Cagar Budaya & Destinasi',
                'category_key' => 'smart_city',
                'category_name' => 'Smart City & Wilayah',
                'icon' => 'landmark',
                'color' => 'yellow',
                'description' => 'Data cagar budaya, museum, keraton, situs sejarah, dan destinasi wisata adat Kutai Kartanegara.',
                'columns' => [
                    'ID Destinasi',
                    'Nama Cagar Budaya / Wisata',
                    'Kategori Budaya',
                    'Kecamatan',
                    'Alamat Lengkap',
                    'Jam Operasional & HTM',
                    'Deskripsi Sejarah Singkat',
                    'Status Kelola',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = CulturalDestination::query();
                    if ($district) $q->where('location_district', $district);
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = CulturalDestination::query();
                    if ($district) $q->where('location_district', $district);
                    return $q->latest('created_at')->limit($limit)->get()->map(function ($c) {
                        return [
                            $c->id,
                            $c->title,
                            ucfirst($c->category ?? 'Cagar Budaya'),
                            $c->location_district ?? '-',
                            $c->address ?? '-',
                            $c->operating_info ?? '-',
                            $c->historical_context ?? '-',
                            ucfirst($c->status ?? 'published'),
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = CulturalDestination::query();
                    if ($district) $q->where('location_district', $district);

                    $q->latest('created_at')->chunk(100, function ($destinations) use ($handle, $delimiter) {
                        foreach ($destinations as $c) {
                            fputcsv($handle, [
                                $c->id,
                                $c->title,
                                ucfirst($c->category ?? 'Cagar Budaya'),
                                $c->location_district ?? '-',
                                $c->address ?? '-',
                                $c->operating_info ?? '-',
                                $c->historical_context ?? '-',
                                ucfirst($c->status ?? 'published'),
                            ], $delimiter);
                        }
                    });
                },
            ],

            // 10. AUDIT LOG SISTEM
            'audit_logs' => [
                'title' => 'Log Audit & Aktivitas Sistem',
                'category_key' => 'sistem',
                'category_name' => 'Audit & Keamanan',
                'icon' => 'shield-check',
                'color' => 'slate',
                'description' => 'Rekam jejak seluruh aktivitas administratif, ekspor data, verifikasi akun, dan tindakan redaksi.',
                'columns' => [
                    'ID Log',
                    'Tanggal & Jam (WITA)',
                    'Nama Operator',
                    'Email Operator',
                    'Role Operator',
                    'Tindakan (Action)',
                    'Tipe Target',
                    'ID Target',
                    'Alamat IP',
                    'User Agent / Perangkat',
                    'Detail Metadata JSON',
                ],
                'count_query' => function ($start, $end, $district) {
                    $q = AuditLog::query();
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    return $q->count();
                },
                'sample_query' => function ($start, $end, $district, $limit = 5) {
                    $q = AuditLog::with('user');
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);
                    return $q->latest('created_at')->limit($limit)->get()->map(function ($log) {
                        return [
                            $log->id,
                            $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-',
                            $log->user->name ?? 'Sistem / Anonim',
                            $log->user->email ?? '-',
                            strtoupper($log->user->role ?? 'SYSTEM'),
                            $log->action,
                            $log->target_type ?? '-',
                            $log->target_id ?? '-',
                            $log->ip_address ?? '-',
                            $log->user_agent ?? '-',
                            $log->metadata ? json_encode($log->metadata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '-',
                        ];
                    })->toArray();
                },
                'stream_query' => function ($handle, $delimiter, $start, $end, $district) {
                    $q = AuditLog::with('user');
                    if ($start) $q->whereDate('created_at', '>=', $start);
                    if ($end) $q->whereDate('created_at', '<=', $end);

                    $q->latest('created_at')->chunk(100, function ($logs) use ($handle, $delimiter) {
                        foreach ($logs as $log) {
                            fputcsv($handle, [
                                $log->id,
                                $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-',
                                $log->user->name ?? 'Sistem / Anonim',
                                $log->user->email ?? '-',
                                strtoupper($log->user->role ?? 'SYSTEM'),
                                $log->action,
                                $log->target_type ?? '-',
                                $log->target_id ?? '-',
                                $log->ip_address ?? '-',
                                $log->user_agent ?? '-',
                                $log->metadata ? json_encode($log->metadata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '-',
                            ], $delimiter);
                        }
                    });
                },
            ],
        ];
    }

    /**
     * Helper to estimate file size based on row count and column count
     */
    protected function formatEstimatedSize(int $rows, int $cols): string
    {
        if ($rows === 0) {
            return '~0 KB';
        }
        $avgRowBytes = $cols * 28; // Estimate average 28 bytes per column cell
        $totalBytes = ($rows * $avgRowBytes) + 400; // Header & BOM

        if ($totalBytes < 1024) {
            return "< 1 KB";
        } elseif ($totalBytes < 1024 * 1024) {
            return '~' . round($totalBytes / 1024, 1) . ' KB';
        } else {
            return '~' . round($totalBytes / (1024 * 1024), 2) . ' MB';
        }
    }
}
