<?php

namespace App\Http\Controllers;

use App\Models\DevoirSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionFileController extends Controller
{
    public function original(Request $request, DevoirSubmission $submission)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $isOwnerEleve = $user->isEleve() && (int) $submission->eleve_user_id === (int) $user->id;
        $course = $submission->course;
        $isCourseFormateur = $user->isEnseignant() && optional($course)->formateur_user_id === $user->id;
        $isCoFormateur = $user->isEnseignant() && $course && method_exists($course, 'coFormateurs') && $course->coFormateurs()->where('users.id', $user->id)->exists();

        if (! $isOwnerEleve && ! $isCourseFormateur && ! $isCoFormateur && ! $user->isSuperadmin()) {
            abort(403);
        }

        return Storage::disk('public')->download($submission->pdf_path);
    }

    public function corrected(Request $request, DevoirSubmission $submission)
    {
        $user = $request->user();

        if (! $user || ! $submission->corrected_pdf_path) {
            abort(404);
        }

        $isOwnerEleve = $user->isEleve() && (int) $submission->eleve_user_id === (int) $user->id;
        $course = $submission->course;
        $isCourseFormateur = $user->isEnseignant() && optional($course)->formateur_user_id === $user->id;
        $isCoFormateur = $user->isEnseignant() && $course && method_exists($course, 'coFormateurs') && $course->coFormateurs()->where('users.id', $user->id)->exists();

        if (! $isOwnerEleve && ! $isCourseFormateur && ! $isCoFormateur && ! $user->isSuperadmin()) {
            abort(403);
        }

        return Storage::disk('public')->download($submission->corrected_pdf_path);
    }
}
