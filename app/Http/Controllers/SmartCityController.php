<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use App\Models\MarketPrice;
use App\Models\EnvironmentPoint;
use App\Models\CulturalDestination;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SmartCityController extends Controller
{
    public function emergency(Request $request)
    {
        $query = EmergencyContact::where('is_active', true);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $cat = $request->input('category');
            if ($cat === 'medis') {
                $query->whereIn('category', ['rumah_sakit', 'ambulans', 'puskesmas']);
            } elseif ($cat === 'utilitas') {
                $query->whereIn('category', ['pdam', 'pln']);
            } elseif ($cat === 'sar_bpbd') {
                $query->whereIn('category', ['sar_bpbd', 'posko_bencana']);
            } else {
                $query->where('category', $cat);
            }
        }

        $totalContacts = EmergencyContact::where('is_active', true)->count();

        $categoryCounts = [
            'all' => $totalContacts,
            'medis' => EmergencyContact::where('is_active', true)->whereIn('category', ['rumah_sakit', 'ambulans', 'puskesmas'])->count(),
            'damkar' => EmergencyContact::where('is_active', true)->where('category', 'damkar')->count(),
            'polisi' => EmergencyContact::where('is_active', true)->where('category', 'polisi')->count(),
            'sar_bpbd' => EmergencyContact::where('is_active', true)->whereIn('category', ['sar_bpbd', 'posko_bencana'])->count(),
            'utilitas' => EmergencyContact::where('is_active', true)->whereIn('category', ['pdam', 'pln'])->count(),
        ];

        $contacts = $query->orderBy('sort_order')->get();

        return view('smart-city.emergency', compact('contacts', 'totalContacts', 'categoryCounts'));
    }


    public function marketPrices(Request $request)
    {
        $selectedMarket = $request->input('market', 'Pasar Tangga Arung');
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        $availableMarkets = MarketPrice::distinct()->pluck('market_name');
        
        $query = MarketPrice::where('market_name', $selectedMarket)
            ->whereDate('recorded_date', $selectedDate);

        // If no records on selected date and no manual date specified, fallback to latest available date
        $existsOnDate = (clone $query)->exists();
        if (!$existsOnDate && !$request->filled('date')) {
            $latestDate = MarketPrice::where('market_name', $selectedMarket)->latest('recorded_date')->value('recorded_date');
            if ($latestDate) {
                $selectedDate = Carbon::parse($latestDate)->format('Y-m-d');
                $query = MarketPrice::where('market_name', $selectedMarket)
                    ->whereDate('recorded_date', $selectedDate);
            }
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('commodity_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $prices = $query->orderBy('commodity_name')->get();

        // Category counts for the selected market & date
        $categoryCounts = MarketPrice::where('market_name', $selectedMarket)
            ->whereDate('recorded_date', $selectedDate)
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $totalCommodities = MarketPrice::where('market_name', $selectedMarket)
            ->whereDate('recorded_date', $selectedDate)
            ->count();

        // Recent historical dates
        $recentDates = MarketPrice::where('market_name', $selectedMarket)
            ->distinct()
            ->latest('recorded_date')
            ->take(5)
            ->pluck('recorded_date');

        return view('smart-city.market-prices', compact(
            'prices', 
            'selectedMarket', 
            'selectedDate', 
            'availableMarkets', 
            'recentDates',
            'categoryCounts',
            'totalCommodities'
        ));
    }

    public function environment(Request $request)
    {
        $query = EnvironmentPoint::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('status_condition', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('info_type', $request->input('type'));
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->input('severity'));
        }

        if ($request->filled('district')) {
            $query->where('location_district', $request->input('district'));
        }

        $points = $query->latest()->get();

        // Specific collections for telemetry widgets
        $waterLevels = EnvironmentPoint::where('info_type', 'water_level')->latest()->get();
        $alerts = EnvironmentPoint::whereIn('info_type', ['flood_alert', 'weather_alert', 'landslide_prone'])
            ->latest()
            ->get();
        $infrastructure = EnvironmentPoint::whereIn('info_type', ['road_damage', 'infrastructure'])
            ->latest()
            ->get();

        $allPoints = EnvironmentPoint::whereNotNull('latitude')->whereNotNull('longitude')->get();

        // Metrics & Filters
        $totalPoints = EnvironmentPoint::count();
        $waterLevelCount = EnvironmentPoint::where('info_type', 'water_level')->count();
        $alertCount = EnvironmentPoint::whereIn('info_type', ['flood_alert', 'weather_alert', 'landslide_prone'])->count();
        $infraCount = EnvironmentPoint::whereIn('info_type', ['road_damage', 'infrastructure'])->count();
        $warningCount = EnvironmentPoint::whereIn('severity', ['warning', 'danger'])->count();

        $availableDistricts = EnvironmentPoint::whereNotNull('location_district')
            ->distinct()
            ->orderBy('location_district')
            ->pluck('location_district');

        return view('smart-city.environment', compact(
            'points',
            'waterLevels',
            'alerts',
            'infrastructure',
            'allPoints',
            'totalPoints',
            'waterLevelCount',
            'alertCount',
            'infraCount',
            'warningCount',
            'availableDistricts'
        ));
    }

    public function culture(Request $request)
    {
        $query = CulturalDestination::where('status', 'published');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('district')) {
            $query->where('location_district', $request->input('district'));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%")
                  ->orWhere('historical_context', 'like', "%{$search}%");
            });
        }

        $totalDestinations = CulturalDestination::where('status', 'published')->count();
        
        $categoryCounts = [
            'all' => $totalDestinations,
            'kesultanan' => CulturalDestination::where('status', 'published')->where('category', 'kesultanan')->count(),
            'museum_sejarah' => CulturalDestination::where('status', 'published')->where('category', 'museum_sejarah')->count(),
            'wisata_alam' => CulturalDestination::where('status', 'published')->where('category', 'wisata_alam')->count(),
            'festival_adat' => CulturalDestination::where('status', 'published')->where('category', 'festival_adat')->count(),
            'kuliner_tradisi' => CulturalDestination::where('status', 'published')->where('category', 'kuliner_tradisi')->count(),
        ];

        $availableDistricts = CulturalDestination::where('status', 'published')
            ->whereNotNull('location_district')
            ->distinct()
            ->orderBy('location_district')
            ->pluck('location_district');

        $allLocations = CulturalDestination::where('status', 'published')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'title', 'slug', 'category', 'location_district', 'address', 'operating_info', 'latitude', 'longitude']);

        $destinations = $query->latest()->paginate(9)->withQueryString();

        return view('smart-city.culture', compact(
            'destinations',
            'totalDestinations',
            'categoryCounts',
            'availableDistricts',
            'allLocations'
        ));
    }

    public function cultureShow(string $slug)
    {
        $destination = CulturalDestination::where('slug', $slug)->firstOrFail();
        
        $otherDestinations = CulturalDestination::where('status', 'published')
            ->where('id', '!=', $destination->id)
            ->take(3)
            ->get();

        return view('smart-city.culture-show', compact('destination', 'otherDestinations'));
    }
}
