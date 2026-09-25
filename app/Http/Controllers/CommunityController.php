<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommunityRequest;
use App\Models\Community;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Community::with('user')->where('status', 'published');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('interest_category', 'like', "%{$search}%")
                  ->orWhere('base_location', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('interest_category', $request->input('category'));
        }

        $totalCommunities = Community::where('status', 'published')->count();

        $categoryCounts = Community::where('status', 'published')
            ->selectRaw('interest_category, count(*) as total')
            ->groupBy('interest_category')
            ->pluck('total', 'interest_category')
            ->toArray();

        $communities = $query->latest('published_at')->paginate(9)->withQueryString();

        return view('communities.index', compact('communities', 'totalCommunities', 'categoryCounts'));
    }

    public function show(string $slug)
    {
        $community = Community::with('user')->where('slug', $slug)->firstOrFail();

        if ($community->status !== 'published') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $community->user_id)) {
                abort(404);
            }
        }

        $otherCommunities = Community::where('status', 'published')
            ->where('id', '!=', $community->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('communities.show', compact('community', 'otherCommunities'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(CommunityRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['name']) . '-' . Str::lower(Str::random(6));

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('communities', 'public');
        }

        $community = Community::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => $slug,
            'interest_category' => $validated['interest_category'],
            'description' => $validated['description'],
            'activity_schedule' => $validated['activity_schedule'] ?? null,
            'base_location' => $validated['base_location'],
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_phone' => $validated['contact_phone'],
            'social_media' => $validated['social_media'] ?? null,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        AuditLog::record('community_created', 'Community', $community->id);

        return redirect()->route('communities.show', $community->slug)
            ->with('success', 'Direktori komunitas berhasil dikirim! Menunggu moderasi redaksi.');
    }

    public function edit(Community $community)
    {
        if (Auth::id() !== $community->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('communities.edit', compact('community'));
    }

    public function update(CommunityRequest $request, Community $community)
    {
        if (Auth::id() !== $community->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'interest_category' => $validated['interest_category'],
            'description' => $validated['description'],
            'activity_schedule' => $validated['activity_schedule'] ?? null,
            'base_location' => $validated['base_location'],
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_phone' => $validated['contact_phone'],
            'social_media' => $validated['social_media'] ?? null,
            'status' => 'pending',
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('communities', 'public');
        }

        $community->update($data);
        AuditLog::record('community_updated', 'Community', $community->id);

        return redirect()->route('communities.show', $community->slug)
            ->with('success', 'Perubahan informasi komunitas berhasil disimpan dan menunggu verifikasi.');
    }

    public function destroy(Community $community)
    {
        if (Auth::id() !== $community->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $community->delete();
        AuditLog::record('community_deleted', 'Community', $community->id);

        return redirect()->route('communities.index')->with('success', 'Komunitas berhasil dihapus.');
    }
}
