<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobVacancyRequest;
use App\Models\JobVacancy;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobVacancyController extends Controller
{
    public function index(Request $request)
    {
        $query = JobVacancy::with('user')->where('status', 'published');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->input('type'));
        }

        $vacancies = $query->latest('published_at')->paginate(8)->withQueryString();

        return view('jobs.index', compact('vacancies'));
    }

    public function show(string $slug)
    {
        $job = JobVacancy::with('user')->where('slug', $slug)->firstOrFail();

        // If not published, only owner or admin can view
        if ($job->status !== 'published') {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $job->user_id)) {
                abort(404);
            }
        }

        $relatedJobs = JobVacancy::where('status', 'published')
            ->where('id', '!=', $job->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(JobVacancyRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('jobs', 'public');
        }

        $job = JobVacancy::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'company' => $validated['company'],
            'photo' => $photoPath,
            'employment_type' => $validated['employment_type'],
            'location' => $validated['location'],
            'salary_range' => $validated['salary_range'] ?? null,
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,
            'status' => 'pending', // Sent to moderation queue
        ]);

        AuditLog::record('job_created', 'JobVacancy', $job->id);

        return redirect()->route('jobs.show', $job->slug)
            ->with('success', 'Lowongan kerja berhasil dikirim! Submission Anda sedang dalam tahap moderasi redaksi.');
    }

    public function edit(JobVacancy $job)
    {
        if (Auth::id() !== $job->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('jobs.edit', compact('job'));
    }

    public function update(JobVacancyRequest $request, JobVacancy $job)
    {
        if (Auth::id() !== $job->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validated();
        
        $data = [
            'title' => $validated['title'],
            'company' => $validated['company'],
            'employment_type' => $validated['employment_type'],
            'location' => $validated['location'],
            'salary_range' => $validated['salary_range'] ?? null,
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,
            'status' => 'pending', // Re-enter moderation on edit
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('jobs', 'public');
        }

        $job->update($data);

        AuditLog::record('job_updated', 'JobVacancy', $job->id);

        return redirect()->route('jobs.show', $job->slug)
            ->with('success', 'Perubahan lowongan kerja berhasil disimpan dan menunggu review ulang redaksi.');
    }

    public function destroy(JobVacancy $job)
    {
        if (Auth::id() !== $job->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $job->delete();
        AuditLog::record('job_deleted', 'JobVacancy', $job->id);

        return redirect()->route('jobs.index')->with('success', 'Lowongan kerja berhasil dihapus.');
    }
}
