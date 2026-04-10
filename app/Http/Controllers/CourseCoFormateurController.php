<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseCoFormateurController extends Controller
{
    public function store(Request $request, Course $course)
    {
        if (! Schema::hasTable('course_co_formateurs')) {
            return back()->withErrors(['formateur_user_id' => 'Module co-formateur non initialise.']);
        }

        if ((int) $course->formateur_user_id !== (int) optional($request->user())->id) {
            abort(403);
        }

        $validated = $request->validate([
            'formateur_user_id' => ['required', 'exists:users,id'],
        ]);

        $candidate = User::findOrFail($validated['formateur_user_id']);
        if (! $candidate->isEnseignant()) {
            return back()->withErrors(['formateur_user_id' => 'Cet utilisateur n est pas formateur.']);
        }

        $course->coFormateurs()->syncWithoutDetaching([$candidate->id]);

        return back()->with('success_content', 'Co-formateur ajoute.');
    }

    public function destroy(Request $request, Course $course, User $formateur)
    {
        if ((int) $course->formateur_user_id !== (int) optional($request->user())->id) {
            abort(403);
        }

        $course->coFormateurs()->detach($formateur->id);

        return back()->with('success_content', 'Co-formateur retire.');
    }
}
