<?php

namespace App\Http\Controllers;

use App\Http\Requests\CulinaryPlaceRequest;
use App\Models\CulinaryPlace;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CulinaryPlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = CulinaryPlace::with('user')->where('status', 'published');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('culinary_type', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('culinary_type', $request->input('type'));
        }

        $culinaryPlaces = $query->latest('published_at')->paginate(8)->withQueryString();

        return view('culinary.index', compact('culinaryPlaces'));
    }

    public function show(string $slug)
    {
        $place = CulinaryPlace::with('user')->where('slug', $slug)->firstOrFail();

        if ($place->status !== 'published') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $place->user_id)) {
                abort(404);
            }
        }

        $relatedPlaces = CulinaryPlace::where('status', 'published')
            ->where('id', '!=', $place->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('culinary.show', compact('place', 'relatedPlaces'));
    }

    public function create()
    {
        return view('culinary.create');
    }

    public function store(CulinaryPlaceRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['name']) . '-' . Str::lower(Str::random(6));

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('culinary', 'public');
        }

        $place = CulinaryPlace::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => $slug,
            'culinary_type' => $validated['culinary_type'],
            'price_range' => $validated['price_range'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'location_district' => $validated['location_district'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'phone_whatsapp' => $validated['phone_whatsapp'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        AuditLog::record('culinary_created', 'CulinaryPlace', $place->id);

        return redirect()->route('culinary.show', $place->slug)
            ->with('success', 'Rekomendasi kuliner berhasil dikirim! Menunggu moderasi redaksi.');
    }

    public function edit(CulinaryPlace $culinary)
    {
        if (Auth::id() !== $culinary->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('culinary.edit', ['place' => $culinary]);
    }

    public function update(CulinaryPlaceRequest $request, CulinaryPlace $culinary)
    {
        if (Auth::id() !== $culinary->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'culinary_type' => $validated['culinary_type'],
            'price_range' => $validated['price_range'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'location_district' => $validated['location_district'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'phone_whatsapp' => $validated['phone_whatsapp'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
            'status' => 'pending',
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('culinary', 'public');
        }

        $culinary->update($data);
        AuditLog::record('culinary_updated', 'CulinaryPlace', $culinary->id);

        return redirect()->route('culinary.show', $culinary->slug)
            ->with('success', 'Perubahan data kuliner berhasil disimpan dan menunggu review redaksi.');
    }

    public function destroy(CulinaryPlace $culinary)
    {
        if (Auth::id() !== $culinary->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $culinary->delete();
        AuditLog::record('culinary_deleted', 'CulinaryPlace', $culinary->id);

        return redirect()->route('culinary.index')->with('success', 'Data kuliner berhasil dihapus.');
    }
}
