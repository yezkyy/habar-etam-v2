<?php

namespace App\Http\Controllers;

use App\Models\QuickSale;
use App\Models\JobVacancy;
use App\Models\Event;
use App\Models\CulinaryPlace;
use App\Models\Business;
use App\Models\Community;
use App\Models\CulturalDestination;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            route('home'),
            route('jobs.index'),
            route('businesses.index'),
            route('culinary.index'),
            route('events.index'),
            route('communities.index'),
            route('quick-sales.index'),
            route('reports.index'),
            route('smart-city.emergency'),
            route('smart-city.market-prices'),
            route('smart-city.environment'),
            route('smart-city.culture'),
        ];

        // Append UGC Published item URLs
        foreach (QuickSale::where('status', 'published')->get() as $item) {
            $urls[] = route('quick-sales.show', $item->slug);
        }
        foreach (JobVacancy::where('status', 'published')->get() as $item) {
            $urls[] = route('jobs.show', $item->slug);
        }
        foreach (Business::where('status', 'published')->get() as $item) {
            $urls[] = route('businesses.show', $item->slug);
        }
        foreach (CulinaryPlace::where('status', 'published')->get() as $item) {
            $urls[] = route('culinary.show', $item->slug);
        }
        foreach (Event::where('status', 'published')->get() as $item) {
            $urls[] = route('events.show', $item->slug);
        }
        foreach (Community::where('status', 'published')->get() as $item) {
            $urls[] = route('communities.show', $item->slug);
        }
        foreach (CulturalDestination::where('status', 'published')->get() as $item) {
            $urls[] = route('smart-city.culture.show', $item->slug);
        }

        $content = view('sitemap', compact('urls'))->render();

        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /member/\n\nSitemap: " . route('sitemap');
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
