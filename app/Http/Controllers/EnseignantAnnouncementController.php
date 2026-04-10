<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnseignantAnnouncementController extends Controller
{
    public function store(Request $request)
    {
        if (! Schema::hasTable('course_announcements')) {
            return back()->withErrors(['title' => 'Le module des annonces n est pas initialise. Lancez les migrations.']);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $course = Course::findOrFail($validated['course_id']);
        $userId = (int) optional($request->user())->id;
        $isOwner = (int) $course->formateur_user_id === $userId;
        $isCoFormateur = Schema::hasTable('course_co_formateurs')
            ? $course->coFormateurs()->where('users.id', $userId)->exists()
            : false;
        if (! $isOwner && ! $isCoFormateur) {
            abort(403);
        }

        CourseAnnouncement::create([
            'course_id' => $course->id,
            'formateur_user_id' => optional($request->user())->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        return back()->with('success_announcement', 'Annonce publiee avec succes.');
    }

    public function update(Request $request, CourseAnnouncement $announcement)
    {
        if ((int) $announcement->formateur_user_id !== (int) optional($request->user())->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        return back()->with('success_announcement', 'Annonce mise a jour.');
    }

    public function destroy(Request $request, CourseAnnouncement $announcement)
    {
        if ((int) $announcement->formateur_user_id !== (int) optional($request->user())->id) {
            abort(403);
        }

        $announcement->delete();

        return back()->with('success_announcement', 'Annonce supprimee.');
    }
}
