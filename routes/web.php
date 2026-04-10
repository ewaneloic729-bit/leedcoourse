<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\DevoirSubmissionController;
use App\Http\Controllers\EnseignantEvaluationController;
use App\Http\Controllers\EnseignantLearnerController;
use App\Http\Controllers\EnseignantContentController;
use App\Http\Controllers\EnseignantAnnouncementController;
use App\Http\Controllers\ApprenantEvaluationController;
use App\Http\Controllers\EnseignantGradebookController;
use App\Http\Controllers\SubmissionFileController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\LessonProgressController;
use App\Http\Controllers\EnseignantAnalyticsController;
use App\Http\Controllers\EnseignantQuestionBankController;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CourseCoFormateurController;
use App\Http\Controllers\ManualGradingController;
use App\Http\Controllers\ApprenantCourseController;
use App\Http\Controllers\ApprenantLessonController;
use App\Http\Controllers\CourseCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\PlatformCommunication;
use App\Models\PlatformSetting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Schema::hasColumn('courses', 'is_available')) {
        $availableCourses = Course::with('formateur')->where('is_available', true)->latest()->take(6)->get();
    } else {
        $availableCourses = Course::latest()->take(6)->get();
    }

    $platformCommunication = class_exists(PlatformCommunication::class)
        ? PlatformCommunication::where('is_published', true)->latest('published_at')->latest('id')->first()
        : null;
    $registrationsOpen = class_exists(PlatformSetting::class)
        ? PlatformSetting::bool('registrations_open', true)
        : true;

    return view('welcome', compact('availableCourses', 'platformCommunication', 'registrationsOpen'));
});

Route::get('/lang/{locale}', function (Request $request, string $locale) {
    if (! in_array($locale, ['fr', 'en'], true)) {
        $locale = 'fr';
    }

    $request->session()->put('locale', $locale);

    $next = $request->query('next');
    $redirect = is_string($next) && str_starts_with($next, '/')
        ? redirect($next)
        : redirect()->back();

    return $redirect->withCookie(cookie()->forever('app_locale', $locale));
})->name('locale.switch');

Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalog.index');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'permission:manage-courses'])->group(function () {
    Route::get('/admin/courses/create', [CourseController::class, 'create'])
        ->name('admin.courses.create');
    Route::post('/admin/courses', [CourseController::class, 'store'])
        ->name('admin.courses.store');
});

Route::middleware(['auth', 'role:eleve'])->group(function () {
    Route::get('/eleve/dashboard', [DashboardController::class, 'eleve'])
        ->name('dashboard.eleve');
    Route::get('/apprenant/mes-cours', [ApprenantCourseController::class, 'index'])
        ->name('apprenant.courses.index');
    Route::get('/apprenant/cours/{course}', [ApprenantCourseController::class, 'show'])
        ->name('apprenant.courses.show');
    Route::post('/apprenant/devoirs', [DevoirSubmissionController::class, 'store'])
        ->name('devoirs.store');
    Route::post('/apprenant/cours/inscription', [CourseEnrollmentController::class, 'store'])
        ->name('apprenant.enrollments.store');
    Route::post('/apprenant/commentaires', [CourseCommentController::class, 'store'])
        ->name('apprenant.comments.store');
    Route::post('/apprenant/lecons/{lesson}/complete', [LessonProgressController::class, 'complete'])
        ->name('apprenant.lessons.complete');
    Route::get('/apprenant/lecons/{lesson}', [ApprenantLessonController::class, 'show'])
        ->name('apprenant.lessons.show');
    Route::get('/apprenant/evaluations', [ApprenantEvaluationController::class, 'index'])
        ->name('apprenant.evaluations.index');
    Route::post('/apprenant/evaluations/{evaluation}/start', [ApprenantEvaluationController::class, 'start'])
        ->name('apprenant.evaluations.start');
    Route::post('/apprenant/evaluations/{evaluation}/submit', [ApprenantEvaluationController::class, 'submit'])
        ->name('apprenant.evaluations.submit');
});

Route::middleware(['auth', 'role:enseignant'])->group(function () {
    Route::get('/enseignant/dashboard', [DashboardController::class, 'enseignant'])
        ->name('dashboard.enseignant');
    Route::get('/enseignant/courses/create', [CourseController::class, 'create'])
        ->name('enseignant.courses.create');
    Route::post('/enseignant/courses', [CourseController::class, 'store'])
        ->name('enseignant.courses.store');
    Route::post('/enseignant/devoirs/{submission}/correction', [DevoirSubmissionController::class, 'update'])
        ->name('devoirs.update');
    Route::get('/enseignant/evaluations', [EnseignantEvaluationController::class, 'index'])
        ->name('enseignant.evaluations.index');
    Route::post('/enseignant/evaluations', [EnseignantEvaluationController::class, 'store'])
        ->name('enseignant.evaluations.store');
    Route::put('/enseignant/evaluations/{evaluation}', [EnseignantEvaluationController::class, 'update'])
        ->name('enseignant.evaluations.update');
    Route::delete('/enseignant/evaluations/{evaluation}', [EnseignantEvaluationController::class, 'destroy'])
        ->name('enseignant.evaluations.destroy');
    Route::patch('/enseignant/evaluations/{evaluation}/publish', [EnseignantEvaluationController::class, 'publish'])
        ->name('enseignant.evaluations.publish');
    Route::post('/enseignant/evaluations/{evaluation}/questions', [EnseignantEvaluationController::class, 'storeQuestion'])
        ->name('enseignant.evaluations.questions.store');
    Route::post('/enseignant/evaluations/attempts/{attempt}/manual-grade', [ManualGradingController::class, 'grade'])
        ->name('enseignant.evaluations.attempts.manual-grade');
    Route::get('/enseignant/apprenants', [EnseignantLearnerController::class, 'index'])
        ->name('enseignant.apprenants.index');
    Route::get('/enseignant/analytics', [EnseignantAnalyticsController::class, 'index'])
        ->name('enseignant.analytics.index');
    Route::get('/enseignant/contenus', [EnseignantContentController::class, 'index'])
        ->name('enseignant.content.index');
    Route::patch('/enseignant/contenus/cours/{course}/status', [EnseignantContentController::class, 'updateCourseStatus'])
        ->name('enseignant.content.course-status');
    Route::post('/enseignant/contenus/cours/{course}/thumbnail', [EnseignantContentController::class, 'updateCourseThumbnail'])
        ->name('enseignant.content.course-thumbnail');
    Route::post('/enseignant/contenus/chapitres', [EnseignantContentController::class, 'storeChapter'])
        ->name('enseignant.content.chapters.store');
    Route::put('/enseignant/contenus/chapitres/{chapter}', [EnseignantContentController::class, 'updateChapter'])
        ->name('enseignant.content.chapters.update');
    Route::delete('/enseignant/contenus/chapitres/{chapter}', [EnseignantContentController::class, 'destroyChapter'])
        ->name('enseignant.content.chapters.destroy');
    Route::post('/enseignant/contenus/lecons', [EnseignantContentController::class, 'storeLesson'])
        ->name('enseignant.content.lessons.store');
    Route::put('/enseignant/contenus/lecons/{lesson}', [EnseignantContentController::class, 'updateLesson'])
        ->name('enseignant.content.lessons.update');
    Route::delete('/enseignant/contenus/lecons/{lesson}', [EnseignantContentController::class, 'destroyLesson'])
        ->name('enseignant.content.lessons.destroy');
    Route::post('/enseignant/annonces', [EnseignantAnnouncementController::class, 'store'])
        ->name('enseignant.announcements.store');
    Route::put('/enseignant/annonces/{announcement}', [EnseignantAnnouncementController::class, 'update'])
        ->name('enseignant.announcements.update');
    Route::delete('/enseignant/annonces/{announcement}', [EnseignantAnnouncementController::class, 'destroy'])
        ->name('enseignant.announcements.destroy');
    Route::post('/enseignant/cours/{course}/co-formateurs', [CourseCoFormateurController::class, 'store'])
        ->name('enseignant.coformateurs.store');
    Route::delete('/enseignant/cours/{course}/co-formateurs/{formateur}', [CourseCoFormateurController::class, 'destroy'])
        ->name('enseignant.coformateurs.destroy');
    Route::get('/enseignant/notes', [EnseignantGradebookController::class, 'index'])
        ->name('enseignant.gradebook.index');
    Route::get('/enseignant/notes/export-csv', [EnseignantGradebookController::class, 'exportCsv'])
        ->name('enseignant.gradebook.export.csv');
    Route::get('/enseignant/question-bank', [EnseignantQuestionBankController::class, 'index'])
        ->name('enseignant.question-bank.index');
    Route::post('/enseignant/question-bank', [EnseignantQuestionBankController::class, 'store'])
        ->name('enseignant.question-bank.store');
    Route::post('/enseignant/question-bank/import-csv', [EnseignantQuestionBankController::class, 'importCsv'])
        ->name('enseignant.question-bank.import-csv');
    Route::post('/enseignant/evaluations/{evaluation}/question-bank/{item}/attach', [EnseignantQuestionBankController::class, 'attachToEvaluation'])
        ->name('enseignant.question-bank.attach');
    Route::post('/enseignant/evaluation-questions/{question}/duplicate', [EnseignantQuestionBankController::class, 'duplicate'])
        ->name('enseignant.evaluation-questions.duplicate');
    Route::post('/enseignant/commentaires/{comment}/moderation', [CourseCommentController::class, 'moderate'])
        ->name('enseignant.comments.moderate');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/devoirs/{submission}/pdf', [SubmissionFileController::class, 'original'])
        ->name('devoirs.files.original');
    Route::get('/devoirs/{submission}/pdf-corrige', [SubmissionFileController::class, 'corrected'])
        ->name('devoirs.files.corrected');
    Route::get('/notifications', [InAppNotificationController::class, 'index'])
        ->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [InAppNotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::get('/activites', [ActivityLogController::class, 'index'])
        ->name('activities.index');
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');
    Route::post('/messages/open', [MessageController::class, 'openConversation'])
        ->name('messages.open');
    Route::get('/messages/{conversation}/poll', [MessageController::class, 'poll'])
        ->name('messages.poll');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])
        ->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'store'])
        ->name('messages.store');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [DashboardController::class, 'superadmin'])
        ->name('dashboard.superadmin');
    Route::post('/superadmin/utilisateurs', [DashboardController::class, 'superadminStoreUser'])
        ->name('superadmin.users.store');
    Route::delete('/superadmin/utilisateurs/{user}', [DashboardController::class, 'superadminDestroyUser'])
        ->name('superadmin.users.destroy');
    Route::patch('/superadmin/utilisateurs/{user}', [DashboardController::class, 'superadminUpdateUser'])
        ->name('superadmin.users.update');
    Route::post('/superadmin/communications', [DashboardController::class, 'superadminStoreCommunication'])
        ->name('superadmin.communications.store');
    Route::post('/superadmin/cours-vitrine', [DashboardController::class, 'superadminStorePromoCourse'])
        ->name('superadmin.promo-courses.store');
    Route::patch('/superadmin/inscriptions/{enrollment}/decision', [CourseEnrollmentController::class, 'decide'])
        ->name('superadmin.enrollments.decide');
    Route::patch('/superadmin/cours/{course}', [DashboardController::class, 'superadminUpdateCourse'])
        ->name('superadmin.courses.update');
    Route::delete('/superadmin/cours/{course}', [DashboardController::class, 'superadminDestroyCourse'])
        ->name('superadmin.courses.destroy');
    Route::patch('/superadmin/settings', [DashboardController::class, 'superadminUpdateSettings'])
        ->name('superadmin.settings.update');
});
