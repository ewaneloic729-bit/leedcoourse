<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q'));
        $category = trim((string) $request->query('category'));
        $level = trim((string) $request->query('level'));

        $base = Course::query()->with('formateur');

        if (Schema::hasColumn('courses', 'is_available')) {
            $base->where('is_available', true);
        }

        if (Schema::hasColumn('courses', 'publication_status')) {
            $base->where(function ($q) {
                $q->whereNull('publication_status')->orWhere('publication_status', 'published');
            });
        }

        if ($query !== '') {
            $base->where(function ($q) use ($query) {
                $q->where('title', 'like', '%'.$query.'%')
                    ->orWhere('description', 'like', '%'.$query.'%')
                    ->orWhere('category', 'like', '%'.$query.'%')
                    ->orWhere('level', 'like', '%'.$query.'%');
            });
        }

        if ($category !== '') {
            $base->where('category', $category);
        }

        if ($level !== '') {
            $base->where('level', $level);
        }

        $courses = (clone $base)->latest()->paginate(12)->withQueryString();

        $categories = Course::query()
            ->when(Schema::hasColumn('courses', 'is_available'), function ($q) {
                $q->where('is_available', true);
            })
            ->pluck('category')
            ->filter()
            ->unique()
            ->values();

        $levels = Course::query()
            ->when(Schema::hasColumn('courses', 'is_available'), function ($q) {
                $q->where('is_available', true);
            })
            ->pluck('level')
            ->filter()
            ->unique()
            ->values();

        $enrollmentStatusByCourse = collect();
        if ($request->user() && $request->user()->isEleve() && Schema::hasTable('course_enrollments')) {
            CourseEnrollment::rejectExpiredPending();
            $enrollmentStatusByCourse = CourseEnrollment::where('eleve_user_id', $request->user()->id)
                ->latest('id')
                ->get()
                ->keyBy('course_id');
        }

        return view('catalog.index', [
            'courses' => $courses,
            'categories' => $categories,
            'levels' => $levels,
            'query' => $query,
            'selectedCategory' => $category,
            'selectedLevel' => $level,
            'enrollmentStatusByCourse' => $enrollmentStatusByCourse,
        ]);
    }
}
