<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseComment;
use App\Models\CourseEnrollment;
use App\Models\DevoirSubmission;
use App\Models\Evaluation;
use App\Models\EvaluationAttempt;
use App\Models\InAppNotification;
use App\Models\LessonProgress;
use App\Models\PlatformCommunication;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $routeName = $user->dashboardRouteName();

        if ($routeName === 'dashboard') {
            return view('dashboard');
        }

        return redirect()->route($routeName);
    }

    public function eleve(Request $request)
    {
        $user = $request->user();
        $availableCourses = collect();
        $mySubmissions = collect();
        $announcements = collect();
        $enrolledCourses = collect();
        $completedLessonIds = collect();
        $evaluationAttempts = collect();
        $unreadInAppCount = 0;
        $myCourseComments = collect();
        $approvedCourseComments = collect();
        $enrollmentRequests = collect();
        $enrollmentStatusByCourse = collect();
        $acceptanceCelebration = null;
        $recentActivities = collect();

        if (Schema::hasColumn('courses', 'is_available')) {
            $availableCourses = Course::where('is_available', true)->latest()->take(12)->get();
        } else {
            $availableCourses = Course::latest()->take(12)->get();
        }

        if (Schema::hasTable('devoir_submissions')) {
            $mySubmissions = DevoirSubmission::with('course')
                ->where('eleve_user_id', optional($user)->id)
                ->latest()
                ->take(30)
                ->get();
        }

        if (Schema::hasTable('course_announcements')) {
            $courseIds = $availableCourses->pluck('id')->all();
            $announcements = CourseAnnouncement::where('is_published', true)
                ->when(! empty($courseIds), function ($query) use ($courseIds) {
                    $query->whereIn('course_id', $courseIds);
                })
                ->latest()
                ->take(8)
                ->get();
        }

        if (Schema::hasTable('course_enrollments')) {
            CourseEnrollment::rejectExpiredPending();
            $myEnrollments = CourseEnrollment::where('eleve_user_id', optional($user)->id)
                ->with('course')
                ->latest('id')
                ->get();

            $enrollmentRequests = $myEnrollments->take(10);
            $enrollmentStatusByCourse = $myEnrollments->keyBy('course_id');

            $enrolledCourseIds = $myEnrollments
                ->filter(function ($enrollment) {
                    return ! CourseEnrollment::usesStatusWorkflow()
                        || $enrollment->status === CourseEnrollment::STATUS_APPROVED;
                })
                ->pluck('course_id');
            $enrolledCourses = Course::with(['chapters.lessons'])
                ->whereIn('id', $enrolledCourseIds)
                ->get();
        }

        if (Schema::hasTable('lesson_progress')) {
            $completedLessonIds = LessonProgress::where('eleve_user_id', optional($user)->id)
                ->where('is_completed', true)
                ->pluck('course_lesson_id');
        }

        if (Schema::hasTable('evaluation_attempts')) {
            $evaluationAttempts = EvaluationAttempt::where('eleve_user_id', optional($user)->id)
                ->latest('submitted_at')
                ->take(40)
                ->get();
        }

        if (Schema::hasTable('in_app_notifications')) {
            $unreadInAppCount = InAppNotification::where('user_id', optional($user)->id)
                ->where('is_read', false)
                ->count();
            $acceptanceCelebration = InAppNotification::where('user_id', optional($user)->id)
                ->where('is_read', false)
                ->where('title', 'Demande acceptee')
                ->latest('id')
                ->first();
        }

        if (Schema::hasTable('course_comments')) {
            $myCourseComments = CourseComment::with('course')
                ->where('eleve_user_id', optional($user)->id)
                ->latest()
                ->take(8)
                ->get();

            $visibleCourseIds = $enrolledCourses->pluck('id')->all();
            if (empty($visibleCourseIds)) {
                $visibleCourseIds = $availableCourses->pluck('id')->all();
            }

            $approvedCourseComments = CourseComment::with(['course', 'eleve'])
                ->where('status', 'approved')
                ->when(! empty($visibleCourseIds), function ($query) use ($visibleCourseIds) {
                    $query->whereIn('course_id', $visibleCourseIds);
                })
                ->latest()
                ->take(10)
                ->get();
        }

        if (Schema::hasTable('activity_logs')) {
            $recentActivities = ActivityLog::where('user_id', optional($user)->id)
                ->latest('id')
                ->take(12)
                ->get();
        }

        $totalLessons = $enrolledCourses->flatMap(function ($course) {
            return $course->chapters->flatMap(function ($chapter) {
                return $chapter->lessons;
            });
        })->count();

        $progressPercent = $totalLessons > 0
            ? round(($completedLessonIds->count() / $totalLessons) * 100, 1)
            : 0;

        return view('dashboards.eleve', [
            'availableCourses' => $availableCourses,
            'mySubmissions' => $mySubmissions,
            'announcements' => $announcements,
            'enrolledCourses' => $enrolledCourses,
            'completedLessonIds' => $completedLessonIds,
            'evaluationAttempts' => $evaluationAttempts,
            'unreadInAppCount' => $unreadInAppCount,
            'progressPercent' => $progressPercent,
            'myCourseComments' => $myCourseComments,
            'approvedCourseComments' => $approvedCourseComments,
            'enrollmentRequests' => $enrollmentRequests,
            'enrollmentStatusByCourse' => $enrollmentStatusByCourse,
            'acceptanceCelebration' => $acceptanceCelebration,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function enseignant(Request $request)
    {
        $user = $request->user();
        $enseignant = optional($user)->enseignant;

        $hasAvailability = Schema::hasColumn('courses', 'is_available');
        $hasFormateurOwner = Schema::hasColumn('courses', 'formateur_user_id');
        $coFormateurCourseIds = collect();

        if (Schema::hasTable('course_co_formateurs') && $user) {
            $coFormateurCourseIds = $user->coFormateurCourses()->pluck('courses.id');
        }

        $courses = Course::where(function ($query) use ($user, $hasAvailability, $hasFormateurOwner, $coFormateurCourseIds) {
            if ($hasAvailability) {
                $query->where('is_available', true);
            }

            if ($hasFormateurOwner && $user) {
                if ($hasAvailability) {
                    $query->orWhere('formateur_user_id', $user->id);
                } else {
                    $query->where('formateur_user_id', $user->id);
                }
            }

            if ($coFormateurCourseIds->isNotEmpty()) {
                $query->orWhereIn('id', $coFormateurCourseIds->all());
            }

            if (! $hasAvailability && ! $hasFormateurOwner) {
                $query->whereRaw('1 = 1');
            }
        })->latest()->get();

        $courseFromQuery = $request->query('course');
        if ($courseFromQuery) {
            $request->session()->put('enseignant_active_course_id', (int) $courseFromQuery);
        }

        $activeCourseId = (int) $request->session()->get('enseignant_active_course_id');
        $activeCourse = $courses->firstWhere('id', $activeCourseId) ?: $courses->first();
        $courseIds = $courses->pluck('id')->all();

        if ($activeCourse) {
            $request->session()->put('enseignant_active_course_id', $activeCourse->id);
        }

        $devoirSubmissions = collect();
        $pendingReviews = 0;
        $devoirLearnerIds = collect();
        if (Schema::hasTable('devoir_submissions')) {
            $devoirBase = DevoirSubmission::query()
                ->when(! empty($courseIds), function ($query) use ($courseIds) {
                    $query->whereIn('course_id', $courseIds);
                });

            $pendingReviews = (clone $devoirBase)
                ->where('status', 'pending')
                ->count();

            $devoirLearnerIds = (clone $devoirBase)
                ->whereNotNull('eleve_user_id')
                ->distinct()
                ->pluck('eleve_user_id');

            $devoirSubmissions = (clone $devoirBase)
                ->with(['course', 'eleve'])
                ->latest()
                ->take(12)
                ->get();
        }

        $evaluationAttempts = collect();
        $avgEvalScore = 0.0;
        $avgEvalMax = 0.0;
        $recentEvaluationAttempts = 0;
        $evaluationLearnerIds = collect();
        if (Schema::hasTable('evaluation_attempts')) {
            $evaluationBase = EvaluationAttempt::query()->whereHas('evaluation', function ($query) use ($user) {
                $query->where('formateur_user_id', optional($user)->id);
            });

            $avgEvalScore = round((float) (clone $evaluationBase)->avg('score'), 2);
            $avgEvalMax = round((float) (clone $evaluationBase)->avg('max_score'), 2);
            $recentEvaluationAttempts = (clone $evaluationBase)
                ->where('submitted_at', '>=', now()->subDays(3))
                ->count();
            $evaluationLearnerIds = (clone $evaluationBase)
                ->whereNotNull('eleve_user_id')
                ->distinct()
                ->pluck('eleve_user_id');

            $evaluationAttempts = (clone $evaluationBase)
                ->latest('submitted_at')
                ->take(12)
                ->get();
        }

        $unpublishedEvaluations = 0;
        if (Schema::hasTable('evaluations')) {
            $unpublishedEvaluations = Evaluation::where('formateur_user_id', optional($user)->id)
                ->where('is_published', false)
                ->count();
        }

        $recentAnnouncements = collect();
        if (Schema::hasTable('course_announcements')) {
            $recentAnnouncements = CourseAnnouncement::where('formateur_user_id', optional($user)->id)
                ->latest()
                ->take(4)
                ->get();
        }

        $activeLearners = $devoirLearnerIds
            ->merge($evaluationLearnerIds)
            ->filter()
            ->unique()
            ->count();

        $stats = [
            'total_courses' => $courses->count(),
            'categories' => $courses->pluck('category')->filter()->unique()->count(),
            'latest_title' => optional($courses->first())->title,
            'active_learners' => $activeLearners,
            'pending_reviews' => $pendingReviews,
            'avg_eval_score' => $avgEvalScore,
            'avg_eval_max' => $avgEvalMax,
        ];

        $notifications = collect([
            $stats['pending_reviews'] > 0 ? $stats['pending_reviews'].' devoir(s) en attente de correction.' : null,
            $unpublishedEvaluations > 0 ? $unpublishedEvaluations.' evaluation(s) en brouillon a publier.' : null,
            $recentEvaluationAttempts > 0
                ? $recentEvaluationAttempts.' nouvelle(s) tentative(s) d evaluation recemment.'
                : null,
        ])->filter()->values();

        $unreadInAppCount = 0;
        $pendingCourseComments = collect();
        $recentCourseComments = collect();
        $pendingEnrollmentRequests = collect();
        if (Schema::hasTable('in_app_notifications')) {
            $unreadInAppCount = InAppNotification::where('user_id', optional($user)->id)
                ->where('is_read', false)
                ->count();
        }

        if (Schema::hasTable('course_enrollments')) {
            $courseIds = $courses->pluck('id')->all();
            CourseEnrollment::rejectExpiredPending($courseIds);
            $pendingEnrollmentRequests = CourseEnrollment::with(['course', 'eleve'])
                ->when(! empty($courseIds), function ($query) use ($courseIds) {
                    $query->whereIn('course_id', $courseIds);
                })
                ->when(CourseEnrollment::usesStatusWorkflow(), function ($query) {
                    $query->where('status', CourseEnrollment::STATUS_PENDING);
                })
                ->orderBy('response_deadline_at')
                ->latest('id')
                ->take(12)
                ->get();
        }

        if (Schema::hasTable('course_comments')) {
            $courseIds = $courses->pluck('id')->all();
            $commentsBase = CourseComment::with(['course', 'eleve'])
                ->when(! empty($courseIds), function ($query) use ($courseIds) {
                    $query->whereIn('course_id', $courseIds);
                })
                ->latest();

            $pendingCourseComments = (clone $commentsBase)
                ->where('status', 'pending')
                ->take(12)
                ->get();

            $recentCourseComments = (clone $commentsBase)
                ->take(12)
                ->get();
        }

        if ($pendingEnrollmentRequests->count() > 0) {
            $notifications->push($pendingEnrollmentRequests->count().' demande(s) d inscription en attente de reponse.');
        }

        return view('dashboards.enseignant', [
            'enseignant' => $enseignant,
            'courses' => $courses,
            'activeCourse' => $activeCourse,
            'stats' => $stats,
            'devoirSubmissions' => $devoirSubmissions,
            'notifications' => $notifications,
            'recentAnnouncements' => $recentAnnouncements,
            'unreadInAppCount' => $unreadInAppCount,
            'pendingCourseComments' => $pendingCourseComments,
            'recentCourseComments' => $recentCourseComments,
            'pendingEnrollmentRequests' => $pendingEnrollmentRequests,
        ]);
    }

    public function superadmin(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $role = trim((string) $request->query('role', ''));
        $status = trim((string) $request->query('status', ''));
        $tab = trim((string) $request->query('tab', 'overview'));
        $allowedTabs = ['overview', 'users', 'enrollments', 'courses', 'communications', 'settings', 'audit'];
        if (! in_array($tab, $allowedTabs, true)) {
            $tab = 'overview';
        }

        $usersQuery = User::query()->latest('id');
        if ($query !== '') {
            $usersQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%')
                    ->orWhere('email', 'like', '%'.$query.'%');

                if (Schema::hasColumn('users', 'matricule')) {
                    $q->orWhere('matricule', 'like', '%'.$query.'%');
                }
            });
        }
        if (in_array($role, [User::ROLE_ELEVE, User::ROLE_ENSEIGNANT, User::ROLE_SUPERADMIN], true)) {
            $usersQuery->where('role', $role);
        }
        if ($status === 'pending') {
            $usersQuery
                ->whereIn('role', [User::ROLE_ELEVE, User::ROLE_ENSEIGNANT])
                ->where('is_active', false);
        } elseif ($status === 'active') {
            $usersQuery->where('is_active', true);
        } elseif ($status === 'inactive') {
            $usersQuery->where('is_active', false);
        }

        $users = $usersQuery->paginate(20)->withQueryString();
        $eleveUsersQuick = User::query()
            ->where('role', User::ROLE_ELEVE)
            ->latest('id')
            ->take(15)
            ->get();
        $enseignantUsersQuick = User::query()
            ->where('role', User::ROLE_ENSEIGNANT)
            ->latest('id')
            ->take(15)
            ->get();
        $communications = PlatformCommunication::query()
            ->latest('published_at')
            ->latest('id')
            ->take(8)
            ->get();
        $auditLogs = ActivityLog::query()
            ->with('user:id,name,email')
            ->where('action', 'like', 'superadmin.%')
            ->latest('id')
            ->take(12)
            ->get();
        $availableCourses = Course::query()
            ->when(Schema::hasColumn('courses', 'is_available'), function ($q) {
                $q->where('is_available', true);
            })
            ->latest()
            ->take(30)
            ->get();
        $pendingEnrollmentRequests = collect();
        if (Schema::hasTable('course_enrollments')) {
            CourseEnrollment::rejectExpiredPending();
            $pendingEnrollmentRequests = CourseEnrollment::with(['course', 'eleve'])
                ->when(CourseEnrollment::usesStatusWorkflow(), function ($query) {
                    $query->where('status', CourseEnrollment::STATUS_PENDING);
                })
                ->orderBy('response_deadline_at')
                ->latest('id')
                ->take(20)
                ->get();
        }

        $stats = Cache::remember('superadmin.dashboard.stats', 60, function () {
            return [
                'users' => User::count(),
                'eleves' => User::where('role', User::ROLE_ELEVE)->count(),
                'enseignants' => User::where('role', User::ROLE_ENSEIGNANT)->count(),
                'pending_validations' => User::whereIn('role', [User::ROLE_ELEVE, User::ROLE_ENSEIGNANT])->where('is_active', false)->count(),
                'courses' => Course::count(),
                'evaluations' => Schema::hasTable('evaluations') ? Evaluation::count() : 0,
                'submissions' => Schema::hasTable('devoir_submissions') ? DevoirSubmission::count() : 0,
            ];
        });

        return view('dashboards.superadmin', [
            'users' => $users,
            'eleveUsersQuick' => $eleveUsersQuick,
            'enseignantUsersQuick' => $enseignantUsersQuick,
            'communications' => $communications,
            'auditLogs' => $auditLogs,
            'availableCourses' => $availableCourses,
            'query' => $query,
            'selectedRole' => $role,
            'selectedStatus' => $status,
            'registrationsOpen' => PlatformSetting::bool('registrations_open', true),
            'maintenanceMode' => PlatformSetting::bool('maintenance_mode', false),
            'stats' => $stats,
            'pendingEnrollmentRequests' => $pendingEnrollmentRequests,
            'selectedTab' => $tab,
        ]);
    }

    public function superadminDestroyUser(Request $request, User $user)
    {
        $authUser = $request->user();
        if (! $authUser || ! $authUser->isSuperadmin()) {
            abort(403);
        }

        if ((int) $authUser->id === (int) $user->id) {
            return back()->withErrors(['superadmin' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        if ($user->isSuperadmin()) {
            $otherSuperadmins = User::where('role', User::ROLE_SUPERADMIN)
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherSuperadmins === 0) {
                return back()->withErrors(['superadmin' => 'Impossible de supprimer le dernier superadmin.']);
            }
        }

        $deletedUser = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
        $user->delete();
        $this->flushSuperadminDashboardCache();
        $this->logSuperadminAction(
            $request,
            'superadmin.user.deleted',
            'user',
            (int) $deletedUser['id'],
            ['deleted_user' => $deletedUser]
        );

        return back()->with('success_superadmin', 'Utilisateur supprime avec succes.');
    }

    public function superadminUpdateUser(Request $request, User $user)
    {
        $authUser = $request->user();
        if (! $authUser || ! $authUser->isSuperadmin()) {
            abort(403);
        }

        if ((int) $authUser->id === (int) $user->id) {
            return back()->withErrors(['superadmin' => 'Vous ne pouvez pas modifier votre propre statut ici.']);
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['nullable', Rule::in([User::ROLE_ELEVE, User::ROLE_ENSEIGNANT, User::ROLE_SUPERADMIN])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $hasAnyUpdateField = collect(['name', 'email', 'role', 'is_active'])
            ->contains(fn ($field) => $request->has($field));

        if (! $hasAnyUpdateField) {
            return back()->withErrors(['superadmin' => 'Aucune modification recue.']);
        }

        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => (bool) $user->is_active,
        ];

        $newRole = $request->has('role') ? (string) ($validated['role'] ?? $user->role) : $user->role;
        $newActive = $request->has('is_active')
            ? (bool) ($validated['is_active'] ?? $user->is_active)
            : (bool) $user->is_active;

        if ($user->isSuperadmin() && ! $newActive) {
            $otherActive = User::where('role', User::ROLE_SUPERADMIN)
                ->where('id', '!=', $user->id)
                ->where('is_active', true)
                ->count();
            if ($otherActive === 0) {
                return back()->withErrors(['superadmin' => 'Impossible de desactiver le dernier superadmin actif.']);
            }
        }

        if ($user->isSuperadmin() && $newRole !== User::ROLE_SUPERADMIN) {
            $otherSuperadmins = User::where('role', User::ROLE_SUPERADMIN)
                ->where('id', '!=', $user->id)
                ->count();
            if ($otherSuperadmins === 0) {
                return back()->withErrors(['superadmin' => 'Impossible de retirer le role admin du dernier admin.']);
            }
        }

        if ($request->has('name')) {
            $user->name = (string) ($validated['name'] ?? $user->name);
        }
        if ($request->has('email')) {
            $user->email = (string) ($validated['email'] ?? $user->email);
        }
        if ($request->has('role')) {
            $user->role = $newRole;
        }
        if ($request->has('is_active')) {
            $user->is_active = $newActive;
        }

        $user->save();
        $this->flushSuperadminDashboardCache();
        $action = 'superadmin.user.updated';
        if (! $oldValues['is_active'] && $user->is_active && in_array($user->role, [User::ROLE_ELEVE, User::ROLE_ENSEIGNANT], true)) {
            $action = 'superadmin.registration.approved';
        }
        $this->logSuperadminAction(
            $request,
            $action,
            'user',
            (int) $user->id,
            [
                'before' => $oldValues,
                'after' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_active' => (bool) $user->is_active,
                ],
            ]
        );

        return back()->with('success_superadmin', 'Utilisateur mis a jour avec succes.');
    }

    public function superadminStoreUser(Request $request)
    {
        $authUser = $request->user();
        if (! $authUser || ! $authUser->isSuperadmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::ROLE_ELEVE, User::ROLE_ENSEIGNANT, User::ROLE_SUPERADMIN])],
            'is_active' => ['nullable', 'boolean'],
            'email_verified' => ['nullable', 'boolean'],
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => $request->boolean('email_verified', true) ? now() : null,
        ]);

        $this->flushSuperadminDashboardCache();
        $this->logSuperadminAction(
            $request,
            'superadmin.user.created',
            'user',
            (int) $newUser->id,
            [
                'name' => $newUser->name,
                'email' => $newUser->email,
                'role' => $newUser->role,
                'is_active' => (bool) $newUser->is_active,
            ]
        );

        return back()->with('success_superadmin', 'Nouveau compte cree avec succes.');
    }

    public function superadminStoreCommunication(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isSuperadmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:4000'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $communication = PlatformCommunication::create([
            'superadmin_user_id' => $user->id,
            'title' => $validated['title'],
            'message' => $validated['message'],
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $request->boolean('is_published', true) ? now() : null,
        ]);
        $this->logSuperadminAction(
            $request,
            'superadmin.communication.created',
            'platform_communication',
            (int) $communication->id,
            ['title' => $communication->title, 'is_published' => (bool) $communication->is_published]
        );

        return back()->with('success_superadmin', 'Communique enregistre.');
    }

    public function superadminStorePromoCourse(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isSuperadmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:100'],
        ]);

        $course = Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'level' => $validated['level'] ?? null,
            'formateur_user_id' => null,
            'is_available' => true,
            'is_promo_only' => true,
            'publication_status' => 'published',
        ]);
        $this->flushSuperadminDashboardCache();
        $this->logSuperadminAction(
            $request,
            'superadmin.course.created_promo',
            'course',
            (int) $course->id,
            ['title' => $course->title]
        );

        return back()->with('success_superadmin', 'Cours vitrine cree et publie.');
    }

    public function superadminUpdateSettings(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isSuperadmin()) {
            abort(403);
        }

        $settings = [
            'registrations_open' => $request->boolean('registrations_open'),
            'maintenance_mode' => $request->boolean('maintenance_mode'),
        ];

        foreach ($settings as $key => $value) {
            PlatformSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ? '1' : '0']
            );
        }
        $this->logSuperadminAction(
            $request,
            'superadmin.settings.updated',
            'platform_setting',
            null,
            $settings
        );

        return back()->with('success_superadmin', 'Configuration plateforme mise a jour.');
    }

    public function superadminUpdateCourse(Request $request, Course $course)
    {
        $user = $request->user();
        if (! $user || ! $user->isSuperadmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:100'],
            'is_available' => ['nullable', 'boolean'],
            'is_promo_only' => ['nullable', 'boolean'],
            'publication_status' => ['nullable', 'in:draft,published'],
        ]);

        $course->title = $validated['title'];
        $course->description = $validated['description'];
        $course->category = $validated['category'];
        $course->level = $validated['level'] ?? null;

        if (Schema::hasColumn('courses', 'is_available')) {
            $course->is_available = $request->boolean('is_available');
        }
        if (Schema::hasColumn('courses', 'is_promo_only')) {
            $course->is_promo_only = $request->boolean('is_promo_only');
        }
        if (Schema::hasColumn('courses', 'publication_status') && isset($validated['publication_status'])) {
            $course->publication_status = $validated['publication_status'];
        }

        $course->save();
        $this->logSuperadminAction(
            $request,
            'superadmin.course.updated',
            'course',
            (int) $course->id,
            ['title' => $course->title]
        );

        return back()->with('success_superadmin', 'Cours mis a jour avec succes.');
    }

    public function superadminDestroyCourse(Request $request, Course $course)
    {
        $user = $request->user();
        if (! $user || ! $user->isSuperadmin()) {
            abort(403);
        }

        $blockingDependencies = [];
        if (Schema::hasTable('course_enrollments') && $course->enrollments()->exists()) {
            $blockingDependencies[] = 'inscriptions apprenants';
        }
        if (Schema::hasTable('devoir_submissions') && $course->devoirSubmissions()->exists()) {
            $blockingDependencies[] = 'soumissions de devoirs';
        }
        if (Schema::hasTable('evaluations') && $course->evaluations()->exists()) {
            $blockingDependencies[] = 'evaluations';
        }
        if (Schema::hasTable('course_chapters') && $course->chapters()->exists()) {
            $blockingDependencies[] = 'chapitres';
        }
        if (Schema::hasTable('course_announcements') && $course->announcements()->exists()) {
            $blockingDependencies[] = 'annonces';
        }
        if (Schema::hasTable('course_comments') && $course->comments()->exists()) {
            $blockingDependencies[] = 'commentaires';
        }

        if (! empty($blockingDependencies)) {
            return back()->withErrors([
                'superadmin' => 'Suppression impossible: ce cours contient deja des '.implode(', ', $blockingDependencies).'.',
            ]);
        }

        $courseId = (int) $course->id;
        $courseTitle = $course->title;
        $course->delete();
        $this->flushSuperadminDashboardCache();
        $this->logSuperadminAction(
            $request,
            'superadmin.course.deleted',
            'course',
            $courseId,
            ['title' => $courseTitle]
        );

        return back()->with('success_superadmin', 'Cours supprime avec succes.');
    }

    private function logSuperadminAction(Request $request, string $action, ?string $entityType = null, ?int $entityId = null, array $meta = []): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        ActivityLog::create([
            'user_id' => optional($request->user())->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'meta' => $meta,
        ]);
    }

    private function flushSuperadminDashboardCache(): void
    {
        Cache::forget('superadmin.dashboard.stats');
    }
}
