<?php

namespace App\Http\Controllers;

use App\Models\QuickSale;
use App\Models\JobVacancy;
use App\Models\Event;
use App\Models\CulinaryPlace;
use App\Models\Business;
use App\Models\Report;
use App\Models\MarketPrice;
use App\Models\EnvironmentPoint;
use App\Models\EmergencyContact;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Live Environment & Water Level Status for top alert ticker
        $waterStatus = EnvironmentPoint::where('info_type', 'water_level')->latest()->first();
        $criticalAlert = EnvironmentPoint::whereIn('severity', ['warning', 'danger'])->latest()->first();

        // 2. Featured Jual Cepat (Published)
        $quickSales = QuickSale::with(['user', 'media'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(4)
            ->get();

        // 3. Latest Job Openings (Published)
        $jobVacancies = JobVacancy::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        // 4. Upcoming Events (Published & not expired)
        $events = Event::where('status', 'published')
            ->whereDate('start_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        // 5. Culinary & Business Highlights
        $culinaryHighlights = CulinaryPlace::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $businessHighlights = Business::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        // 6. Lapor Etam Updates (Processing, Live, or Resolved)
        $featuredReports = Report::whereIn('status', ['live_agenda', 'processing_editorial', 'resolved'])
            ->with(['media', 'user'])
            ->orderByRaw("FIELD(status, 'live_agenda', 'processing_editorial', 'resolved')")
            ->latest('updated_at')
            ->take(3)
            ->get();

        // 7. Smart City: Emergency & Market Commodity Snapshot
        $emergencyContacts = EmergencyContact::where('is_active', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $marketPrices = MarketPrice::whereDate('recorded_date', Carbon::today())
            ->take(6)
            ->get();

        if ($marketPrices->isEmpty()) {
            $marketPrices = MarketPrice::latest('recorded_date')
                ->take(6)
                ->get();
        }

        return view('home', compact(
            'waterStatus',
            'criticalAlert',
            'quickSales',
            'jobVacancies',
            'events',
            'culinaryHighlights',
            'businessHighlights',
            'featuredReports',
            'emergencyContacts',
            'marketPrices'
        ));
    }
}
