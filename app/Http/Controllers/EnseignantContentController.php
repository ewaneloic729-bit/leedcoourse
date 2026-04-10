<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EnseignantContentController extends Controller
{
    private function canManageCourse(Request $request, Course $course): bool
    {
        $userId = (int) optional($request->user())->id;
        if ($userId === 0) {
            return false;
        }

        if ((int) $course->formateur_user_id === $userId) {
            return true;
        }

        if (Schema::hasTable('course_co_formateurs')) {
            return $course->coFormateurs()->where('users.id', $userId)->exists();
        }

        return false;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $courses = Course::query()
            ->when(Schema::hasColumn('courses', 'formateur_user_id') && $user, function ($query) use ($user) {
                $query->where('formateur_user_id', $user->id);
            })
            ->latest()
            ->get();

        $selectedCourseId = (int) $request->query('course_id', optional($courses->first())->id);
        $selectedCourse = $courses->firstWhere('id', $selectedCourseId);

        $chapters = collect();
        if ($selectedCourse && Schema::hasTable('course_chapters')) {
            $chapters = CourseChapter::with('lessons')
                ->where('course_id', $selectedCourse->id)
                ->orderBy('position')
                ->get();
        }

        return view('dashboards.enseignant-content', [
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'chapters' => $chapters,
            'formateurCandidates' => User::where('role', User::ROLE_ENSEIGNANT)->orderBy('name')->get(),
            'setupMissing' => ! Schema::hasTable('course_chapters') || ! Schema::hasTable('course_lessons'),
        ]);
    }

    public function storeChapter(Request $request)
    {
        if (! Schema::hasTable('course_chapters')) {
            return back()->withErrors(['title' => 'Module de contenu non initialise. Lancez les migrations.']);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $course = Course::findOrFail($validated['course_id']);
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        CourseChapter::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'position' => $validated['position'] ?? 1,
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('success_content', 'Chapitre ajoute avec succes.');
    }

    public function storeLesson(Request $request)
    {
        if (! Schema::hasTable('course_lessons')) {
            return back()->withErrors(['title' => 'Module de contenu non initialise. Lancez les migrations.']);
        }

        $validated = $request->validate([
            'course_chapter_id' => ['required', 'exists:course_chapters,id'],
            'title' => ['required', 'string', 'max:255'],
            'lesson_type' => ['nullable', 'in:text,video,pdf'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:1200'],
            'video_resource' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:204800'],
            'pdf_resource' => ['nullable', 'file', 'mimes:pdf', 'max:30720'],
            'position' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $chapter = CourseChapter::findOrFail($validated['course_chapter_id']);
        $course = Course::findOrFail($chapter->course_id);
        $hasLessonType = Schema::hasColumn('course_lessons', 'lesson_type');
        $hasVideoUrl = Schema::hasColumn('course_lessons', 'video_url');
        $hasPdfPath = Schema::hasColumn('course_lessons', 'pdf_path');

        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        $lessonType = $hasLessonType ? ($validated['lesson_type'] ?? 'text') : 'text';

        $sanitizedVideoInput = $this->sanitizeVideoReference($validated['video_url'] ?? null);
        if ($hasVideoUrl && $lessonType === 'video' && ! empty($validated['video_url']) && ! $sanitizedVideoInput) {
            return back()->withErrors(['video_url' => 'Le lien video est invalide. Seuls les liens HTTP/HTTPS sont acceptes.'])->withInput();
        }

        if ($hasVideoUrl && $lessonType === 'video' && empty($sanitizedVideoInput)) {
            if (! $request->hasFile('video_resource')) {
                return back()->withErrors(['video_url' => 'Veuillez renseigner un lien video ou televerser un fichier video.']);
            }
        }
        if ($hasPdfPath && $lessonType === 'pdf' && ! $request->hasFile('pdf_resource')) {
            return back()->withErrors(['pdf_resource' => 'Veuillez envoyer un fichier PDF pour une lecon de type PDF.']);
        }

        $videoValue = $sanitizedVideoInput;
        if ($hasVideoUrl && $request->hasFile('video_resource')) {
            $videoValue = $request->file('video_resource')->store('lessons/videos', 'public');
        }

        $pdfPath = null;
        if ($hasPdfPath && $request->hasFile('pdf_resource')) {
            $pdfPath = $request->file('pdf_resource')->store('lessons/resources', 'public');
        }

        $payload = [
            'course_chapter_id' => $chapter->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'position' => $validated['position'] ?? 1,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($hasLessonType) {
            $payload['lesson_type'] = $lessonType;
        }
        if ($hasVideoUrl) {
            $payload['video_url'] = $lessonType === 'video' ? $videoValue : null;
        }
        if ($hasPdfPath) {
            $payload['pdf_path'] = $lessonType === 'pdf' ? $pdfPath : null;
        }

        CourseLesson::create($payload);

        return back()->with('success_content', 'Lecon ajoutee avec succes.');
    }

    public function updateChapter(Request $request, CourseChapter $chapter)
    {
        $course = Course::findOrFail($chapter->course_id);
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $chapter->update([
            'title' => $validated['title'],
            'position' => $validated['position'] ?? $chapter->position,
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('success_content', 'Chapitre mis a jour.');
    }

    public function destroyChapter(Request $request, CourseChapter $chapter)
    {
        $course = Course::findOrFail($chapter->course_id);
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        $chapter->delete();

        return back()->with('success_content', 'Chapitre supprime.');
    }

    public function updateLesson(Request $request, CourseLesson $lesson)
    {
        $chapter = CourseChapter::findOrFail($lesson->course_chapter_id);
        $course = Course::findOrFail($chapter->course_id);
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'lesson_type' => ['nullable', 'in:text,video,pdf'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'string', 'max:1200'],
            'video_resource' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:204800'],
            'pdf_resource' => ['nullable', 'file', 'mimes:pdf', 'max:30720'],
            'remove_pdf' => ['nullable', 'boolean'],
            'remove_video' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $hasLessonType = Schema::hasColumn('course_lessons', 'lesson_type');
        $hasVideoUrl = Schema::hasColumn('course_lessons', 'video_url');
        $hasPdfPath = Schema::hasColumn('course_lessons', 'pdf_path');
        $lessonType = $hasLessonType ? ($validated['lesson_type'] ?? ($lesson->lesson_type ?? 'text')) : 'text';
        $sanitizedVideoInput = $this->sanitizeVideoReference($validated['video_url'] ?? null);

        if ($hasVideoUrl && $lessonType === 'video' && ! empty($validated['video_url']) && ! $sanitizedVideoInput) {
            return back()->withErrors(['video_url' => 'Le lien video est invalide. Seuls les liens HTTP/HTTPS sont acceptes.'])->withInput();
        }
        if ($hasVideoUrl && $lessonType === 'video' && empty($sanitizedVideoInput) && ! $request->hasFile('video_resource') && empty($lesson->video_url)) {
            return back()->withErrors(['video_url' => 'Veuillez renseigner un lien video ou televerser un fichier video.']);
        }
        if ($hasPdfPath && $lessonType === 'pdf' && ! $request->hasFile('pdf_resource') && empty($lesson->pdf_path)) {
            return back()->withErrors(['pdf_resource' => 'Veuillez envoyer un fichier PDF pour une lecon de type PDF.']);
        }

        if ($hasVideoUrl && $request->boolean('remove_video') && ! empty($lesson->video_url) && ! filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($lesson->video_url);
            $lesson->video_url = null;
        }

        if ($hasVideoUrl && $request->hasFile('video_resource')) {
            if (! empty($lesson->video_url) && ! filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            $lesson->video_url = $request->file('video_resource')->store('lessons/videos', 'public');
        }

        if ($hasPdfPath && $request->boolean('remove_pdf') && $lesson->pdf_path) {
            Storage::disk('public')->delete($lesson->pdf_path);
            $lesson->pdf_path = null;
        }

        if ($hasPdfPath && $request->hasFile('pdf_resource')) {
            if ($lesson->pdf_path) {
                Storage::disk('public')->delete($lesson->pdf_path);
            }
            $lesson->pdf_path = $request->file('pdf_resource')->store('lessons/resources', 'public');
        }

        $lesson->title = $validated['title'];
        if ($hasLessonType) {
            $lesson->lesson_type = $lessonType;
        }
        $lesson->content = $validated['content'] ?? null;
        if ($hasVideoUrl) {
            if ($lessonType === 'video') {
                if (! $request->hasFile('video_resource') && $sanitizedVideoInput) {
                    if (! empty($lesson->video_url) && ! filter_var($lesson->video_url, FILTER_VALIDATE_URL) && $lesson->video_url !== $sanitizedVideoInput) {
                        Storage::disk('public')->delete($lesson->video_url);
                    }
                    $lesson->video_url = $sanitizedVideoInput;
                }
            } else {
                if (! empty($lesson->video_url) && ! filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($lesson->video_url);
                }
                $lesson->video_url = null;
            }
        }
        if ($hasPdfPath && $lessonType !== 'pdf' && $lesson->pdf_path) {
            Storage::disk('public')->delete($lesson->pdf_path);
            $lesson->pdf_path = null;
        }
        $lesson->position = $validated['position'] ?? $lesson->position;
        $lesson->is_published = $request->boolean('is_published');
        $lesson->save();

        return back()->with('success_content', 'Lecon mise a jour.');
    }

    public function updateCourseThumbnail(Request $request, Course $course)
    {
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        $validated = $request->validate([
            'course_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (! empty($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->image = $request->file('course_image')->store('courses', 'public');
        $course->save();

        return back()->with('success_content', 'Miniature du cours mise a jour.');
    }

    public function destroyLesson(Request $request, CourseLesson $lesson)
    {
        $chapter = CourseChapter::findOrFail($lesson->course_chapter_id);
        $course = Course::findOrFail($chapter->course_id);
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        if (! empty($lesson->video_url) && ! filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($lesson->video_url);
        }
        if (! empty($lesson->pdf_path)) {
            Storage::disk('public')->delete($lesson->pdf_path);
        }

        $lesson->delete();

        return back()->with('success_content', 'Lecon supprimee.');
    }

    public function updateCourseStatus(Request $request, Course $course)
    {
        if (! $this->canManageCourse($request, $course)) {
            abort(403);
        }

        $validated = $request->validate([
            'publication_status' => ['required', 'in:draft,review,published'],
        ]);

        if (Schema::hasColumn('courses', 'publication_status')) {
            $course->publication_status = $validated['publication_status'];
        }

        if (Schema::hasColumn('courses', 'is_available')) {
            $course->is_available = $validated['publication_status'] === 'published';
        }

        $course->save();

        return back()->with('success_content', 'Statut du cours mis a jour.');
    }

    private function sanitizeVideoReference(?string $input): ?string
    {
        $value = trim((string) $input);
        if ($value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
            if (! in_array($scheme, ['http', 'https'], true)) {
                return null;
            }

            return $value;
        }

        if (str_contains($value, '..')) {
            return null;
        }

        if (! preg_match('/^lessons\/videos\/[A-Za-z0-9_\/\.-]+$/', $value)) {
            return null;
        }

        return $value;
    }
}
