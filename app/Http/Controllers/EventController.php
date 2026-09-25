<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('user')->where('status', 'published');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $today = Carbon::today();
        if ($request->filled('time_filter')) {
            $timeFilter = $request->input('time_filter');
            if ($timeFilter === 'this_month') {
                $query->whereMonth('start_date', $today->month)
                      ->whereYear('start_date', $today->year);
            } elseif ($timeFilter === 'upcoming') {
                $query->where(function ($q) use ($today) {
                    $q->whereDate('start_date', '>=', $today)
                      ->orWhere(function ($sub) use ($today) {
                          $sub->whereNotNull('end_date')
                              ->whereDate('end_date', '>=', $today);
                      });
                });
            } elseif ($timeFilter === 'past') {
                $query->where(function ($q) use ($today) {
                    $q->whereDate('start_date', '<', $today)
                      ->where(function ($sub) use ($today) {
                          $sub->whereNull('end_date')
                              ->orWhereDate('end_date', '<', $today);
                      });
                });
            }
        }

        // Stats counts
        $totalPublished = Event::where('status', 'published')->count();
        $thisMonthCount = Event::where('status', 'published')
            ->whereMonth('start_date', $today->month)
            ->whereYear('start_date', $today->year)
            ->count();
        $upcomingCount = Event::where('status', 'published')
            ->whereDate('start_date', '>=', $today)
            ->count();

        // Spotlight upcoming headline event for default view
        $spotlightEvent = null;
        if (!$request->filled('q') && !$request->filled('category') && !$request->filled('time_filter')) {
            $spotlightEvent = Event::with('user')
                ->where('status', 'published')
                ->whereDate('start_date', '>=', $today)
                ->orderBy('start_date', 'asc')
                ->first();
        }

        // Category counts
        $categoryCounts = Event::where('status', 'published')
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Order by start_date upcoming
        $events = $query->orderBy('start_date', 'asc')->paginate(9)->withQueryString();

        return view('events.index', compact(
            'events', 
            'totalPublished', 
            'thisMonthCount', 
            'upcomingCount', 
            'spotlightEvent',
            'categoryCounts'
        ));
    }

    public function show(string $slug)
    {
        $event = Event::with('user')->where('slug', $slug)->firstOrFail();

        if ($event->status !== 'published') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $event->user_id)) {
                abort(404);
            }
        }

        $upcomingEvents = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->whereDate('start_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'upcomingEvents'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(EventRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));

        $posterPath = null;
        if ($request->hasFile('poster_image')) {
            $posterPath = $request->file('poster_image')->store('events', 'public');
        }

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'organizer' => $validated['organizer'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'location_name' => $validated['location_name'],
            'location_address' => $validated['location_address'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'description' => $validated['description'],
            'contact_phone' => $validated['contact_phone'] ?? null,
            'poster_image' => $posterPath,
            'status' => 'pending',
        ]);

        AuditLog::record('event_created', 'Event', $event->id);

        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Agenda event berhasil dikirim! Menunggu persetujuan moderasi redaksi.');
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('events.edit', compact('event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'organizer' => $validated['organizer'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'start_time' => $validated['start_time'] ?? null,
            'location_name' => $validated['location_name'],
            'location_address' => $validated['location_address'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'description' => $validated['description'],
            'contact_phone' => $validated['contact_phone'] ?? null,
            'status' => 'pending',
        ];

        if ($request->hasFile('poster_image')) {
            $data['poster_image'] = $request->file('poster_image')->store('events', 'public');
        }

        $event->update($data);
        AuditLog::record('event_updated', 'Event', $event->id);

        return redirect()->route('events.show', $event->slug)
            ->with('success', 'Perubahan informasi event berhasil disimpan dan menunggu review.');
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $event->delete();
        AuditLog::record('event_deleted', 'Event', $event->id);

        return redirect()->route('events.index')->with('success', 'Agenda event berhasil dihapus.');
    }
}
