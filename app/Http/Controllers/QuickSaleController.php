<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuickSaleRequest;
use App\Models\QuickSale;
use App\Models\QuickSaleMedia;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuickSaleController extends Controller
{
    public function index(Request $request)
    {
        $query = QuickSale::with(['user.profile', 'media'])
            ->whereIn('status', ['published', 'sold']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        if ($request->filled('location')) {
            $query->where('location_name', 'like', '%' . $request->input('location') . '%');
        }

        if ($request->filled('sort')) {
            if ($request->input('sort') === 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->input('sort') === 'price_high') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest('published_at');
            }
        } else {
            // Prioritize published over sold, then latest
            $query->orderByRaw("FIELD(status, 'published', 'sold')")->latest('published_at');
        }

        $items = $query->paginate(8)->withQueryString();

        $totalActive = QuickSale::where('status', 'published')->count();
        $totalSold = QuickSale::where('status', 'sold')->count();

        return view('quick-sales.index', compact('items', 'totalActive', 'totalSold'));
    }

    public function show(string $slug)
    {
        $item = QuickSale::with(['user.profile', 'media'])->where('slug', $slug)->firstOrFail();

        if ($item->status !== 'published' && $item->status !== 'sold') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $item->user_id)) {
                abort(404);
            }
        }

        $relatedItems = QuickSale::with('media')
            ->where('status', 'published')
            ->where('id', '!=', $item->id)
            ->where('category', $item->category)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('quick-sales.show', compact('item', 'relatedItems'));
    }

    public function create()
    {
        return view('quick-sales.create');
    }

    public function store(QuickSaleRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));

        // Sanitize whatsapp phone (remove non-digits, ensure 62 prefix)
        $cleanWa = preg_replace('/[^0-9]/', '', $validated['contact_whatsapp']);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }

        $quickSale = QuickSale::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'description' => $validated['description'],
            'location_name' => $validated['location_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_whatsapp' => $cleanWa,
            'status' => 'pending', // Sent to moderation queue
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('quick_sales', 'public');
                QuickSaleMedia::create([
                    'quick_sale_id' => $quickSale->id,
                    'path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        AuditLog::record('quick_sale_created', 'QuickSale', $quickSale->id);

        return redirect()->route('quick-sales.show', $quickSale->slug)
            ->with('success', 'Barang Jual Cepat berhasil didaftarkan! Listing Anda sedang ditinjau oleh tim redaksi.');
    }

    public function edit(QuickSale $quickSale)
    {
        if (Auth::id() !== $quickSale->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $quickSale->load('media');
        return view('quick-sales.edit', ['item' => $quickSale]);
    }

    public function update(QuickSaleRequest $request, QuickSale $quickSale)
    {
        if (Auth::id() !== $quickSale->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();

        $cleanWa = preg_replace('/[^0-9]/', '', $validated['contact_whatsapp']);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }

        $quickSale->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'description' => $validated['description'],
            'location_name' => $validated['location_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_whatsapp' => $cleanWa,
            'status' => 'pending', // Re-moderation on edit
        ]);

        if ($request->hasFile('photos')) {
            // Add new media
            $maxOrder = $quickSale->media()->max('sort_order') ?? -1;
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('quick_sales', 'public');
                QuickSaleMedia::create([
                    'quick_sale_id' => $quickSale->id,
                    'path' => $path,
                    'is_primary' => false,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        AuditLog::record('quick_sale_updated', 'QuickSale', $quickSale->id);

        return redirect()->route('quick-sales.show', $quickSale->slug)
            ->with('success', 'Perubahan barang Jual Cepat disimpan dan menunggu review ulang.');
    }

    public function markSold(QuickSale $quickSale)
    {
        if (Auth::id() !== $quickSale->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $quickSale->update(['status' => 'sold']);
        AuditLog::record('quick_sale_marked_sold', 'QuickSale', $quickSale->id);

        return back()->with('success', 'Status barang berhasil ditandai sebagai TERJUAL.');
    }

    public function destroy(QuickSale $quickSale)
    {
        if (Auth::id() !== $quickSale->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $quickSale->delete();
        AuditLog::record('quick_sale_deleted', 'QuickSale', $quickSale->id);

        return redirect()->route('quick-sales.index')->with('success', 'Listing Jual Cepat berhasil dihapus.');
    }
}
