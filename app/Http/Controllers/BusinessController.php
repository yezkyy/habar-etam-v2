<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessRequest;
use App\Models\Business;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with('user')->where('status', 'published');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('location_district', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $businesses = $query->latest('published_at')->paginate(8)->withQueryString();

        return view('businesses.index', compact('businesses'));
    }

    public function show(string $slug)
    {
        $business = Business::with('user')->where('slug', $slug)->firstOrFail();

        if ($business->status !== 'published') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $business->user_id)) {
                abort(404);
            }
        }

        $relatedBusinesses = Business::where('status', 'published')
            ->where('id', '!=', $business->id)
            ->where('category', $business->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('businesses.show', compact('business', 'relatedBusinesses'));
    }

    public function create()
    {
        return view('businesses.create');
    }

    public function store(BusinessRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['name']) . '-' . Str::lower(Str::random(6));

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('businesses', 'public');
        }

        $business = Business::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'location_district' => $validated['location_district'],
            'phone_whatsapp' => $validated['phone_whatsapp'],
            'instagram' => $validated['instagram'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        AuditLog::record('business_created', 'Business', $business->id);

        return redirect()->route('businesses.show', $business->slug)
            ->with('success', 'Profil usaha/jasa berhasil dikirim! Menunggu persetujuan moderasi redaksi.');
    }

    public function edit(Business $business)
    {
        if (Auth::id() !== $business->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('businesses.edit', compact('business'));
    }

    public function update(BusinessRequest $request, Business $business)
    {
        if (Auth::id() !== $business->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'location_district' => $validated['location_district'],
            'phone_whatsapp' => $validated['phone_whatsapp'],
            'instagram' => $validated['instagram'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
            'status' => 'pending',
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('businesses', 'public');
        }

        $business->update($data);
        AuditLog::record('business_updated', 'Business', $business->id);

        return redirect()->route('businesses.show', $business->slug)
            ->with('success', 'Perubahan data usaha berhasil disimpan dan menunggu verifikasi redaksi.');
    }

    public function destroy(Business $business)
    {
        if (Auth::id() !== $business->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $business->delete();
        AuditLog::record('business_deleted', 'Business', $business->id);

        return redirect()->route('businesses.index')->with('success', 'Usaha berhasil dihapus.');
    }
}
