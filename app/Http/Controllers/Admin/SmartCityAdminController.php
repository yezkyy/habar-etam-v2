<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use App\Models\MarketPrice;
use App\Models\EnvironmentPoint;
use App\Models\CulturalDestination;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SmartCityAdminController extends Controller
{
    // ==========================================
    // 1. KONTAK DARURAT
    // ==========================================
    public function emergencyIndex(Request $request)
    {
        $category = $request->input('category', 'all');
        $district = $request->input('district', 'all');
        $status = $request->input('status', 'all');
        $search = strtolower(trim($request->input('q', '')));

        $baseQuery = EmergencyContact::query();

        // 4 Summary Metrics
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
            'whatsapp_count' => (clone $baseQuery)->whereNotNull('whatsapp')->where('whatsapp', '!=', '')->count(),
            'priority_24h' => (clone $baseQuery)->whereIn('category', ['damkar', 'polisi', 'rumah_sakit', 'medis', 'ambulans', 'sar_bpbd', 'bpbd'])->count(),
            'districts_count' => (clone $baseQuery)->distinct('location_district')->count('location_district'),
        ];

        // Category breakdown counts
        $categoryCounts = [
            'all' => $stats['total'],
            'medis' => (clone $baseQuery)->whereIn('category', ['rumah_sakit', 'medis', 'ambulans', 'puskesmas'])->count(),
            'damkar' => (clone $baseQuery)->where('category', 'damkar')->count(),
            'polisi' => (clone $baseQuery)->where('category', 'polisi')->count(),
            'sar_bpbd' => (clone $baseQuery)->whereIn('category', ['sar_bpbd', 'bpbd', 'posko_bencana'])->count(),
            'pdam' => (clone $baseQuery)->where('category', 'pdam')->count(),
            'pln' => (clone $baseQuery)->where('category', 'pln')->count(),
            'lainnya' => (clone $baseQuery)->whereNotIn('category', ['rumah_sakit', 'medis', 'ambulans', 'puskesmas', 'damkar', 'polisi', 'sar_bpbd', 'bpbd', 'posko_bencana', 'pdam', 'pln'])->count(),
        ];

        // Query builder
        $query = EmergencyContact::query();

        // Category filter
        if ($category !== 'all' && !empty($category)) {
            if ($category === 'medis') {
                $query->whereIn('category', ['rumah_sakit', 'medis', 'ambulans', 'puskesmas']);
            } elseif ($category === 'sar_bpbd') {
                $query->whereIn('category', ['sar_bpbd', 'bpbd', 'posko_bencana']);
            } elseif ($category === 'lainnya') {
                $query->whereNotIn('category', ['rumah_sakit', 'medis', 'ambulans', 'puskesmas', 'damkar', 'polisi', 'sar_bpbd', 'bpbd', 'posko_bencana', 'pdam', 'pln']);
            } else {
                $query->where('category', $category);
            }
        }

        // District filter
        if ($district !== 'all' && !empty($district)) {
            $query->where('location_district', $district);
        }

        // Status filter
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('sort_order', 'asc')
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        // Distinct Districts list
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = EmergencyContact::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.emergency-index', [
            'contacts' => $contacts,
            'stats' => $stats,
            'categoryCounts' => $categoryCounts,
            'category' => $category,
            'district' => $district,
            'status' => $status,
            'districts' => $districts,
        ]);
    }

    public function emergencyCreate()
    {
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = EmergencyContact::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.emergency-create', compact('districts'));
    }

    public function emergencyStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:50'],
            'custom_category' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'location_district' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['category'] === 'lainnya' && !empty($validated['custom_category'])) {
            $validated['category'] = Str::slug($validated['custom_category'], '_');
        }
        unset($validated['custom_category']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('emergency', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $contact = EmergencyContact::create($validated);
        AuditLog::record('emergency_contact_created', 'EmergencyContact', $contact->id, [
            'name' => $contact->name,
            'category' => $contact->category,
            'phone' => $contact->phone,
        ]);

        return redirect()->route('admin.smart-city.emergency.index')
            ->with('success', "Kontak darurat '{$contact->name}' berhasil ditambahkan ke direktori.");
    }

    public function emergencyEdit(EmergencyContact $contact)
    {
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = EmergencyContact::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.emergency-edit', compact('contact', 'districts'));
    }

    public function emergencyUpdate(Request $request, EmergencyContact $contact)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:50'],
            'custom_category' => ['nullable', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'location_district' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($validated['category'] === 'lainnya' && !empty($validated['custom_category'])) {
            $validated['category'] = Str::slug($validated['custom_category'], '_');
        }
        unset($validated['custom_category']);

        if ($request->boolean('remove_image')) {
            if ($contact->image && !str_starts_with($contact->image, 'http') && Storage::disk('public')->exists($contact->image)) {
                Storage::disk('public')->delete($contact->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($contact->image && !str_starts_with($contact->image, 'http') && Storage::disk('public')->exists($contact->image)) {
                Storage::disk('public')->delete($contact->image);
            }
            $validated['image'] = $request->file('image')->store('emergency', 'public');
        }
        unset($validated['remove_image']);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $contact->sort_order ?? 0;
        
        $contact->update($validated);
        AuditLog::record('emergency_contact_updated', 'EmergencyContact', $contact->id, [
            'name' => $contact->name,
            'category' => $contact->category,
            'phone' => $contact->phone,
            'is_active' => $contact->is_active,
        ]);

        return redirect()->route('admin.smart-city.emergency.index')
            ->with('success', "Kontak darurat '{$contact->name}' berhasil diperbarui.");
    }

    public function emergencyDestroy(EmergencyContact $contact)
    {
        $name = $contact->name;
        if ($contact->image && !str_starts_with($contact->image, 'http') && Storage::disk('public')->exists($contact->image)) {
            Storage::disk('public')->delete($contact->image);
        }
        $contact->delete();
        AuditLog::record('emergency_contact_deleted', 'EmergencyContact', $contact->id, [
            'name' => $name,
        ]);

        return redirect()->route('admin.smart-city.emergency.index')
            ->with('success', "Kontak darurat '{$name}' berhasil dihapus dari direktori.");
    }

    // ==========================================
    // 2. HARGA PANGAN
    // ==========================================
    public function marketPricesIndex(Request $request)
    {
        $category = $request->input('category', 'all');
        $market = $request->input('market', 'all');
        $movement = $request->input('movement', 'all');
        $date = $request->input('date', '');
        $search = trim($request->input('q', ''));

        $baseQuery = MarketPrice::query();

        // 1. Calculate Summary Stats
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'markets_count' => (clone $baseQuery)->distinct('market_name')->count('market_name'),
            'up' => (clone $baseQuery)->whereNotNull('previous_price')->whereColumn('price', '>', 'previous_price')->count(),
            'down' => (clone $baseQuery)->whereNotNull('previous_price')->whereColumn('price', '<', 'previous_price')->count(),
            'stable' => (clone $baseQuery)->whereNotNull('previous_price')->whereColumn('price', '=', 'previous_price')->count(),
        ];

        // 2. Category Breakdown Counts for tabs
        $categoryCounts = [
            'all' => $stats['total'],
            'sembako' => (clone $baseQuery)->whereIn('category', ['sembako', 'beras'])->count(),
            'bumbu_dapur' => (clone $baseQuery)->whereIn('category', ['bumbu_dapur', 'cabai', 'bawang'])->count(),
            'daging_ikan' => (clone $baseQuery)->whereIn('category', ['daging_ikan', 'daging', 'ikan'])->count(),
            'sayur_mayur' => (clone $baseQuery)->whereIn('category', ['sayur_mayur', 'sayuran', 'sayur'])->count(),
            'telur_susu' => (clone $baseQuery)->whereIn('category', ['telur_susu', 'telur'])->count(),
            'lainnya' => (clone $baseQuery)->whereNotIn('category', [
                'sembako', 'beras', 'bumbu_dapur', 'cabai', 'bawang', 
                'daging_ikan', 'daging', 'ikan', 'sayur_mayur', 'sayuran', 'sayur', 'telur_susu', 'telur'
            ])->count(),
        ];

        // 3. Build Filtered Query
        $query = MarketPrice::with('creator');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('commodity_name', 'like', "%{$search}%")
                  ->orWhere('market_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category !== 'all') {
            if ($category === 'sembako') {
                $query->whereIn('category', ['sembako', 'beras']);
            } elseif ($category === 'bumbu_dapur') {
                $query->whereIn('category', ['bumbu_dapur', 'cabai', 'bawang']);
            } elseif ($category === 'daging_ikan') {
                $query->whereIn('category', ['daging_ikan', 'daging', 'ikan']);
            } elseif ($category === 'sayur_mayur') {
                $query->whereIn('category', ['sayur_mayur', 'sayuran', 'sayur']);
            } elseif ($category === 'telur_susu') {
                $query->whereIn('category', ['telur_susu', 'telur']);
            } elseif ($category === 'lainnya') {
                $query->whereNotIn('category', [
                    'sembako', 'beras', 'bumbu_dapur', 'cabai', 'bawang', 
                    'daging_ikan', 'daging', 'ikan', 'sayur_mayur', 'sayuran', 'sayur', 'telur_susu', 'telur'
                ]);
            } else {
                $query->where('category', $category);
            }
        }

        if ($market !== 'all' && !empty($market)) {
            $query->where('market_name', $market);
        }

        if (!empty($date)) {
            $query->whereDate('recorded_date', $date);
        }

        if ($movement !== 'all') {
            if ($movement === 'up') {
                $query->whereNotNull('previous_price')->whereColumn('price', '>', 'previous_price');
            } elseif ($movement === 'down') {
                $query->whereNotNull('previous_price')->whereColumn('price', '<', 'previous_price');
            } elseif ($movement === 'stable') {
                $query->whereNotNull('previous_price')->whereColumn('price', '=', 'previous_price');
            }
        }

        $prices = $query->latest('recorded_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        // Distinct Markets list merged with defaults
        $defaultMarkets = [
            'Pasar Tangga Arung, Tenggarong',
            'Pasar Mangkurawang, Tenggarong',
            'Pasar Loa Kulu',
            'Pasar Kota Bangun',
            'Pasar Muara Jawa',
            'Pasar Sebulu',
            'Pasar Samboja',
            'Pasar Muara Badak',
        ];
        $dbMarkets = MarketPrice::whereNotNull('market_name')
            ->where('market_name', '!=', '')
            ->distinct()
            ->pluck('market_name')
            ->toArray();
        $markets = array_values(array_unique(array_merge($defaultMarkets, $dbMarkets)));
        sort($markets);

        return view('admin.smart-city.prices-index', [
            'prices' => $prices,
            'markets' => $markets,
            'stats' => $stats,
            'categoryCounts' => $categoryCounts,
            'category' => $category,
            'market' => $market,
            'movement' => $movement,
            'date' => $date,
        ]);
    }

    public function marketPricesCreate()
    {
        $defaultMarkets = [
            'Pasar Tangga Arung, Tenggarong',
            'Pasar Mangkurawang, Tenggarong',
            'Pasar Loa Kulu',
            'Pasar Kota Bangun',
            'Pasar Muara Jawa',
            'Pasar Sebulu',
            'Pasar Samboja',
            'Pasar Muara Badak',
        ];
        $dbMarkets = MarketPrice::whereNotNull('market_name')
            ->where('market_name', '!=', '')
            ->distinct()
            ->pluck('market_name')
            ->toArray();
        $markets = array_values(array_unique(array_merge($defaultMarkets, $dbMarkets)));
        sort($markets);

        $standardCommodities = [
            ['name' => 'Beras Mayas Asli Kukar Super', 'category' => 'sembako', 'unit' => 'kg'],
            ['name' => 'Beras Medium Ramos', 'category' => 'sembako', 'unit' => 'kg'],
            ['name' => 'Minyak Goreng Kemasan (Minyakita)', 'category' => 'sembako', 'unit' => 'liter'],
            ['name' => 'Gula Pasir Kristal Putih', 'category' => 'sembako', 'unit' => 'kg'],
            ['name' => 'Tepung Terigu Segitiga Biru', 'category' => 'sembako', 'unit' => 'kg'],
            ['name' => 'Cabai Rawit Merah (Tiung)', 'category' => 'bumbu_dapur', 'unit' => 'kg'],
            ['name' => 'Cabai Merah Keriting', 'category' => 'bumbu_dapur', 'unit' => 'kg'],
            ['name' => 'Bawang Merah Brebes / Bima', 'category' => 'bumbu_dapur', 'unit' => 'kg'],
            ['name' => 'Bawang Putih Honan', 'category' => 'bumbu_dapur', 'unit' => 'kg'],
            ['name' => 'Daging Sapi Segar Lokal', 'category' => 'daging_ikan', 'unit' => 'kg'],
            ['name' => 'Daging Ayam Broiler Segar', 'category' => 'daging_ikan', 'unit' => 'ekor'],
            ['name' => 'Ikan Haruan (Gabus Mahakam)', 'category' => 'daging_ikan', 'unit' => 'kg'],
            ['name' => 'Ikan Patin Keramba Mahakam', 'category' => 'daging_ikan', 'unit' => 'kg'],
            ['name' => 'Ikan Nila Segar Mahakam', 'category' => 'daging_ikan', 'unit' => 'kg'],
            ['name' => 'Telur Ayam Ras Segar', 'category' => 'telur_susu', 'unit' => 'piring (30 btr)'],
            ['name' => 'Telur Ayam Kampung', 'category' => 'telur_susu', 'unit' => 'butir'],
            ['name' => 'Sayur Kangkung Lokal', 'category' => 'sayur_mayur', 'unit' => 'ikat'],
            ['name' => 'Sayur Bayam Bukit Biru', 'category' => 'sayur_mayur', 'unit' => 'ikat'],
            ['name' => 'Tomat Buah Segar', 'category' => 'sayur_mayur', 'unit' => 'kg'],
        ];

        return view('admin.smart-city.prices-create', compact('markets', 'standardCommodities'));
    }

    public function marketPricesStore(Request $request)
    {
        $validated = $request->validate([
            'market_name' => ['required', 'string', 'max:150'],
            'custom_market' => ['nullable', 'string', 'max:150'],
            'commodity_name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:50'],
            'custom_category' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'previous_price' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'recorded_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['market_name'] === 'lainnya' && !empty($validated['custom_market'])) {
            $validated['market_name'] = trim($validated['custom_market']);
        }
        unset($validated['custom_market']);

        if ($validated['category'] === 'lainnya' && !empty($validated['custom_category'])) {
            $validated['category'] = Str::slug($validated['custom_category'], '_');
        }
        unset($validated['custom_category']);

        // Auto-detect previous price if not provided
        if (!isset($validated['previous_price']) || $validated['previous_price'] === null || $validated['previous_price'] === '') {
            $previousRecord = MarketPrice::where('market_name', $validated['market_name'])
                ->where('commodity_name', $validated['commodity_name'])
                ->whereDate('recorded_date', '<', $validated['recorded_date'])
                ->latest('recorded_date')
                ->first();
            $validated['previous_price'] = $previousRecord ? $previousRecord->price : null;
        }

        $validated['created_by'] = Auth::id();

        $price = MarketPrice::create($validated);
        AuditLog::record('market_price_logged', 'MarketPrice', $price->id, [
            'commodity_name' => $price->commodity_name,
            'market_name' => $price->market_name,
            'price' => $price->price,
            'category' => $price->category,
        ]);

        return redirect()->route('admin.smart-city.prices.index')
            ->with('success', "Data harga komoditas '{$price->commodity_name}' di {$price->market_name} berhasil dicatat.");
    }

    public function marketPricesEdit(MarketPrice $price)
    {
        $defaultMarkets = [
            'Pasar Tangga Arung, Tenggarong',
            'Pasar Mangkurawang, Tenggarong',
            'Pasar Loa Kulu',
            'Pasar Kota Bangun',
            'Pasar Muara Jawa',
            'Pasar Sebulu',
            'Pasar Samboja',
            'Pasar Muara Badak',
        ];
        $dbMarkets = MarketPrice::whereNotNull('market_name')
            ->where('market_name', '!=', '')
            ->distinct()
            ->pluck('market_name')
            ->toArray();
        $markets = array_values(array_unique(array_merge($defaultMarkets, $dbMarkets)));
        sort($markets);

        return view('admin.smart-city.prices-edit', compact('price', 'markets'));
    }

    public function marketPricesUpdate(Request $request, MarketPrice $price)
    {
        $validated = $request->validate([
            'market_name' => ['required', 'string', 'max:150'],
            'custom_market' => ['nullable', 'string', 'max:150'],
            'commodity_name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:50'],
            'custom_category' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'previous_price' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'recorded_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['market_name'] === 'lainnya' && !empty($validated['custom_market'])) {
            $validated['market_name'] = trim($validated['custom_market']);
        }
        unset($validated['custom_market']);

        if ($validated['category'] === 'lainnya' && !empty($validated['custom_category'])) {
            $validated['category'] = Str::slug($validated['custom_category'], '_');
        }
        unset($validated['custom_category']);

        $price->update($validated);

        AuditLog::record('market_price_updated', 'MarketPrice', $price->id, [
            'commodity_name' => $price->commodity_name,
            'market_name' => $price->market_name,
            'price' => $price->price,
            'category' => $price->category,
        ]);

        return redirect()->route('admin.smart-city.prices.index')
            ->with('success', "Data harga komoditas '{$price->commodity_name}' berhasil diperbarui.");
    }

    public function marketPricesDestroy(MarketPrice $price)
    {
        $commodityName = $price->commodity_name;
        $marketName = $price->market_name;

        AuditLog::record('market_price_deleted', 'MarketPrice', $price->id, [
            'commodity_name' => $commodityName,
            'market_name' => $marketName,
        ]);

        $price->delete();

        return redirect()->route('admin.smart-city.prices.index')
            ->with('success', "Data harga '{$commodityName}' di {$marketName} berhasil dihapus dari sistem.");
    }

    // ==========================================
    // 3. LINGKUNGAN & INFRASTRUKTUR
    // ==========================================
    public function environmentIndex(Request $request)
    {
        $infoType = $request->input('info_type', 'all');
        $severity = $request->input('severity', 'all');
        $district = $request->input('district', 'all');
        $search = trim($request->input('q', ''));

        $baseQuery = EnvironmentPoint::query();

        // 1. Summary KPIs
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'normal' => (clone $baseQuery)->where('severity', 'normal')->count(),
            'warning' => (clone $baseQuery)->where('severity', 'warning')->count(),
            'danger' => (clone $baseQuery)->where('severity', 'danger')->count(),
            'districts_count' => (clone $baseQuery)->distinct('location_district')->count('location_district'),
        ];

        // 2. Info Type breakdown counts for pills
        $categoryCounts = [
            'all' => $stats['total'],
            'water_level' => (clone $baseQuery)->where('info_type', 'water_level')->count(),
            'air_quality' => (clone $baseQuery)->where('info_type', 'air_quality')->count(),
            'flood_alert' => (clone $baseQuery)->whereIn('info_type', ['flood_alert', 'flood_point'])->count(),
            'hotspot' => (clone $baseQuery)->whereIn('info_type', ['hotspot', 'karhutla'])->count(),
            'weather' => (clone $baseQuery)->where('info_type', 'weather')->count(),
            'other' => (clone $baseQuery)->whereNotIn('info_type', [
                'water_level', 'air_quality', 'flood_alert', 'flood_point', 'hotspot', 'karhutla', 'weather'
            ])->count(),
        ];

        // 3. Filtered query
        $query = EnvironmentPoint::with('updater');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('status_condition', 'like', "%{$search}%");
            });
        }

        if ($infoType !== 'all') {
            if ($infoType === 'flood_alert') {
                $query->whereIn('info_type', ['flood_alert', 'flood_point']);
            } elseif ($infoType === 'hotspot') {
                $query->whereIn('info_type', ['hotspot', 'karhutla']);
            } elseif ($infoType === 'other') {
                $query->whereNotIn('info_type', [
                    'water_level', 'air_quality', 'flood_alert', 'flood_point', 'hotspot', 'karhutla', 'weather'
                ]);
            } else {
                $query->where('info_type', $infoType);
            }
        }

        if ($severity !== 'all' && !empty($severity)) {
            $query->where('severity', $severity);
        }

        if ($district !== 'all' && !empty($district)) {
            $query->where('location_district', $district);
        }

        $points = $query->latest('updated_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        // Distinct Districts list merged with defaults
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = EnvironmentPoint::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.environment-index', [
            'points' => $points,
            'districts' => $districts,
            'stats' => $stats,
            'categoryCounts' => $categoryCounts,
            'infoType' => $infoType,
            'severity' => $severity,
            'district' => $district,
        ]);
    }

    public function environmentCreate()
    {
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = EnvironmentPoint::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.environment-create', compact('districts'));
    }

    public function environmentStore(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'info_type' => ['required', 'string', 'max:50'],
            'severity' => ['required', 'in:normal,warning,danger'],
            'description' => ['required', 'string', 'max:2000'],
            'location_name' => ['required', 'string', 'max:200'],
            'location_district' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'source' => ['required', 'string', 'max:150'],
            'status_condition' => ['nullable', 'string', 'max:150'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));
        $validated['updated_by'] = Auth::id();

        $point = EnvironmentPoint::create($validated);
        AuditLog::record('environment_point_created', 'EnvironmentPoint', $point->id, [
            'title' => $point->title,
            'info_type' => $point->info_type,
            'severity' => $point->severity,
            'district' => $point->location_district,
        ]);

        return redirect()->route('admin.smart-city.environment.index')
            ->with('success', "Titik pantau lingkungan '{$point->title}' berhasil didaftarkan.");
    }

    public function environmentEdit(EnvironmentPoint $point)
    {
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = EnvironmentPoint::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.environment-edit', compact('point', 'districts'));
    }

    public function environmentUpdate(Request $request, EnvironmentPoint $point)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'info_type' => ['required', 'string', 'max:50'],
            'severity' => ['required', 'in:normal,warning,danger'],
            'description' => ['required', 'string', 'max:2000'],
            'location_name' => ['required', 'string', 'max:200'],
            'location_district' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'source' => ['required', 'string', 'max:150'],
            'status_condition' => ['nullable', 'string', 'max:150'],
        ]);

        $validated['updated_by'] = Auth::id();
        $point->update($validated);

        AuditLog::record('environment_point_updated', 'EnvironmentPoint', $point->id, [
            'title' => $point->title,
            'info_type' => $point->info_type,
            'severity' => $point->severity,
            'district' => $point->location_district,
        ]);

        return redirect()->route('admin.smart-city.environment.index')
            ->with('success', "Data titik pantau '{$point->title}' berhasil diperbarui.");
    }

    public function environmentDestroy(EnvironmentPoint $point)
    {
        $title = $point->title;

        AuditLog::record('environment_point_deleted', 'EnvironmentPoint', $point->id, [
            'title' => $title,
        ]);

        $point->delete();

        return redirect()->route('admin.smart-city.environment.index')
            ->with('success', "Titik pantau '{$title}' berhasil dihapus dari sistem.");
    }

    // ==========================================
    // 4. BUDAYA & PARIWISATA
    // ==========================================
    public function cultureIndex(Request $request)
    {
        $category = $request->input('category', 'all');
        $status = $request->input('status', 'all');
        $district = $request->input('district', 'all');
        $search = trim($request->input('q', ''));

        $baseQuery = CulturalDestination::query();

        // 1. Summary KPIs
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'kesultanan_count' => (clone $baseQuery)->where('category', 'kesultanan')->count(),
            'districts_count' => (clone $baseQuery)->distinct('location_district')->count('location_district'),
        ];

        // 2. Category breakdown counts for top pill bar
        $categoryCounts = [
            'all' => $stats['total'],
            'kesultanan' => (clone $baseQuery)->where('category', 'kesultanan')->count(),
            'museum_sejarah' => (clone $baseQuery)->where('category', 'museum_sejarah')->count(),
            'wisata_alam' => (clone $baseQuery)->where('category', 'wisata_alam')->count(),
            'festival_adat' => (clone $baseQuery)->where('category', 'festival_adat')->count(),
            'kuliner_tradisi' => (clone $baseQuery)->where('category', 'kuliner_tradisi')->count(),
            'lainnya' => (clone $baseQuery)->whereNotIn('category', [
                'kesultanan', 'museum_sejarah', 'wisata_alam', 'festival_adat', 'kuliner_tradisi'
            ])->count(),
        ];

        // 3. Filtered Query
        $query = CulturalDestination::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('historical_context', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%")
                  ->orWhere('operating_info', 'like', "%{$search}%");
            });
        }

        if ($category !== 'all' && !empty($category)) {
            if ($category === 'lainnya') {
                $query->whereNotIn('category', [
                    'kesultanan', 'museum_sejarah', 'wisata_alam', 'festival_adat', 'kuliner_tradisi'
                ]);
            } else {
                $query->where('category', $category);
            }
        }

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if ($district !== 'all' && !empty($district)) {
            $query->where('location_district', $district);
        }

        $destinations = $query->latest('updated_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        // Distinct Districts list merged with defaults
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = CulturalDestination::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.culture-index', [
            'destinations' => $destinations,
            'districts' => $districts,
            'stats' => $stats,
            'categoryCounts' => $categoryCounts,
            'category' => $category,
            'status' => $status,
            'district' => $district,
        ]);
    }

    public function cultureCreate()
    {
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = CulturalDestination::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.culture-create', compact('districts'));
    }

    public function cultureStore(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:50'],
            'custom_category' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:published,draft'],
            'location_district' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'operating_info' => ['nullable', 'string', 'max:250'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string'],
            'historical_context' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($validated['category'] === 'lainnya' && !empty($validated['custom_category'])) {
            $validated['category'] = Str::slug($validated['custom_category'], '_');
        }
        unset($validated['custom_category']);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));

        if ($request->hasFile('photo')) {
            $validated['cover_image'] = $request->file('photo')->store('culture', 'public');
        }

        $dest = CulturalDestination::create($validated);
        AuditLog::record('cultural_destination_created', 'CulturalDestination', $dest->id, [
            'title' => $dest->title,
            'category' => $dest->category,
            'district' => $dest->location_district,
            'status' => $dest->status,
        ]);

        return redirect()->route('admin.smart-city.culture.index')
            ->with('success', "Destinasi budaya '{$dest->title}' berhasil didaftarkan ke direktori.");
    }

    public function cultureEdit(CulturalDestination $destination)
    {
        $defaultDistricts = [
            'Tenggarong', 'Tenggarong Seberang', 'Loa Janan', 'Loa Kulu', 'Samboja', 'Samboja Barat',
            'Muara Badak', 'Muara Jawa', 'Kota Bangun', 'Kota Bangun Darat', 'Sebulu', 'Anggana', 
            'Sangasanga', 'Marangkayu', 'Muara Kaman', 'Muara Muntai', 'Muara Wis', 'Kenohan', 'Kembang Janggut', 'Tabang'
        ];
        $dbDistricts = CulturalDestination::whereNotNull('location_district')
            ->where('location_district', '!=', '')
            ->distinct()
            ->pluck('location_district')
            ->toArray();
        $districts = array_values(array_unique(array_merge($defaultDistricts, $dbDistricts)));
        sort($districts);

        return view('admin.smart-city.culture-edit', [
            'destination' => $destination,
            'districts' => $districts,
        ]);
    }

    public function cultureUpdate(Request $request, CulturalDestination $destination)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:50'],
            'custom_category' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:published,draft'],
            'location_district' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'operating_info' => ['nullable', 'string', 'max:250'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string'],
            'historical_context' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if ($validated['category'] === 'lainnya' && !empty($validated['custom_category'])) {
            $validated['category'] = Str::slug($validated['custom_category'], '_');
        }
        unset($validated['custom_category']);

        if ($request->boolean('remove_photo')) {
            if ($destination->cover_image && !str_starts_with($destination->cover_image, 'http') && Storage::disk('public')->exists($destination->cover_image)) {
                Storage::disk('public')->delete($destination->cover_image);
            }
            $validated['cover_image'] = null;
        } elseif ($request->hasFile('photo')) {
            if ($destination->cover_image && !str_starts_with($destination->cover_image, 'http') && Storage::disk('public')->exists($destination->cover_image)) {
                Storage::disk('public')->delete($destination->cover_image);
            }
            $validated['cover_image'] = $request->file('photo')->store('culture', 'public');
        }
        unset($validated['remove_photo']);

        $destination->update($validated);

        AuditLog::record('cultural_destination_updated', 'CulturalDestination', $destination->id, [
            'title' => $destination->title,
            'category' => $destination->category,
            'district' => $destination->location_district,
            'status' => $destination->status,
        ]);

        return redirect()->route('admin.smart-city.culture.index')
            ->with('success', "Data destinasi '{$destination->title}' berhasil diperbarui.");
    }

    public function cultureDestroy(CulturalDestination $destination)
    {
        $title = $destination->title;

        if ($destination->cover_image && !str_starts_with($destination->cover_image, 'http') && Storage::disk('public')->exists($destination->cover_image)) {
            Storage::disk('public')->delete($destination->cover_image);
        }

        $destination->delete();

        AuditLog::record('cultural_destination_deleted', 'CulturalDestination', $destination->id, [
            'title' => $title,
        ]);

        return redirect()->route('admin.smart-city.culture.index')
            ->with('success', "Destinasi budaya '{$title}' berhasil dihapus dari direktori.");
    }
}
