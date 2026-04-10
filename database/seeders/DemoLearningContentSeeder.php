<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseChapter;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemoLearningContentSeeder extends Seeder
{
    public function run()
    {
        if (! Schema::hasTable('courses') || ! Schema::hasTable('course_chapters') || ! Schema::hasTable('course_lessons')) {
            return;
        }

        $teacher = User::where('email', 'enseignant@example.test')->first();
        $learner = User::where('email', 'eleve@example.test')->first();

        if (! $teacher) {
            return;
        }

        $blueprints = [
            [
                'course' => [
                    'title' => 'Laravel Bootcamp: Application complete',
                    'description' => 'Construire une application Laravel moderne avec auth, tableaux de bord, modules metier et bonnes pratiques de code.',
                    'category' => 'Developpement Web',
                    'level' => 'Intermediaire',
                    'is_available' => true,
                    'publication_status' => 'published',
                    'is_promo_only' => false,
                ],
                'chapters' => [
                    [
                        'title' => 'Architecture et demarrage',
                        'lessons' => [
                            ['title' => 'Structure du projet Laravel', 'type' => 'text', 'content' => 'Decouvrez les dossiers cles: app, routes, resources, database et leurs responsabilites.'],
                            ['title' => 'Configuration de l environnement', 'type' => 'text', 'content' => 'Parametrez .env, base de donnees, cache et filesystems pour un workflow stable.'],
                            ['title' => 'Presentation video du flux applicatif', 'type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY', 'content' => 'Vision globale d une application Laravel du besoin metier au deploiement.'],
                        ],
                    ],
                    [
                        'title' => 'Fonctionnalites metier',
                        'lessons' => [
                            ['title' => 'CRUD avance et validation', 'type' => 'text', 'content' => 'Creer des formulaires robustes avec Form Request, policies et controle des erreurs.'],
                            ['title' => 'Tableaux de bord role-based', 'type' => 'text', 'content' => 'Construire des espaces eleve, enseignant et admin avec middlewares de roles et permissions.'],
                            ['title' => 'Suivi de progression apprenant', 'type' => 'text', 'content' => 'Modeliser lesson_progress et calculer les indicateurs d avancement dans les vues.'],
                        ],
                    ],
                ],
                'completed_lessons' => 4,
                'announcement' => [
                    'title' => 'Nouvelle cohorte Laravel',
                    'message' => 'Le parcours Laravel Bootcamp est ouvert. Commencez par le chapitre Architecture avant vendredi.',
                ],
            ],
            [
                'course' => [
                    'title' => 'API REST et securite',
                    'description' => 'Concevoir des API performantes avec Laravel, Sanctum, gestion des erreurs et strategie de versioning.',
                    'category' => 'Backend',
                    'level' => 'Avance',
                    'is_available' => true,
                    'publication_status' => 'published',
                    'is_promo_only' => false,
                ],
                'chapters' => [
                    [
                        'title' => 'Conception API',
                        'lessons' => [
                            ['title' => 'Ressources API et pagination', 'type' => 'text', 'content' => 'Retourner des payloads coherents avec API Resource, meta et pagination uniforme.'],
                            ['title' => 'Auth token avec Sanctum', 'type' => 'text', 'content' => 'Mettre en place une authentification par token et securiser les endpoints sensibles.'],
                            ['title' => 'Gestion des erreurs', 'type' => 'text', 'content' => 'Normaliser les messages, status HTTP et tracing pour faciliter le debug cote client.'],
                        ],
                    ],
                    [
                        'title' => 'Industrialisation',
                        'lessons' => [
                            ['title' => 'Tests feature API', 'type' => 'text', 'content' => 'Ecrire des tests feature couvrant les cas heureux, erreurs et autorisations.'],
                            ['title' => 'Rate limiting et monitoring', 'type' => 'text', 'content' => 'Limiter les abus et monitorer les endpoints critiques avec logs metriques.'],
                            ['title' => 'Versioning de l API', 'type' => 'text', 'content' => 'Planifier les evolutions sans casser les integrations existantes.'],
                        ],
                    ],
                ],
                'completed_lessons' => 2,
                'announcement' => [
                    'title' => 'Sprint securite API',
                    'message' => 'Cette semaine nous renforcons les endpoints critiques et les tests de regression.',
                ],
            ],
            [
                'course' => [
                    'title' => 'UX/UI pour plateformes e-learning',
                    'description' => 'Designer un parcours apprenant lisible: onboarding, progression, feedback et pages de cours engageantes.',
                    'category' => 'Design Produit',
                    'level' => 'Debutant',
                    'is_available' => true,
                    'publication_status' => 'published',
                    'is_promo_only' => false,
                ],
                'chapters' => [
                    [
                        'title' => 'Fondamentaux UX',
                        'lessons' => [
                            ['title' => 'Cartographier le parcours apprenant', 'type' => 'text', 'content' => 'Identifier les etapes cle: decouverte, inscription, apprentissage, evaluation, feedback.'],
                            ['title' => 'Concevoir des dashboards utiles', 'type' => 'text', 'content' => 'Prioriser les KPI actionnables: progression, prochaines actions et risques de decrochage.'],
                            ['title' => 'Micro-copie pedagogique', 'type' => 'text', 'content' => 'Ecrire des messages courts qui guident l utilisateur sans ambiguite.'],
                        ],
                    ],
                    [
                        'title' => 'Prototypage et iteration',
                        'lessons' => [
                            ['title' => 'Systeme visuel coherent', 'type' => 'text', 'content' => 'Structurer la hierarchie visuelle avec contrastes, espaces et composants reutilisables.'],
                            ['title' => 'Tests utilisateur rapides', 'type' => 'text', 'content' => 'Valider les parcours par des tests scenario et corriger les frictions majeures.'],
                            ['title' => 'Mesurer l impact UX', 'type' => 'text', 'content' => 'Suivre completion rate, NPS apprenant et feedback qualitatif pour decider des optimisations.'],
                        ],
                    ],
                ],
                'completed_lessons' => 0,
                'announcement' => [
                    'title' => 'Atelier design learning',
                    'message' => 'Un atelier pratique UX est planifie jeudi. Pensez a preparer un cas d usage reel.',
                ],
            ],
            [
                'course' => [
                    'title' => 'Cours videos: Laravel de A a Z',
                    'description' => 'Parcours 100% video pour apprendre Laravel pas a pas: installation, CRUD, auth, API et mise en production.',
                    'category' => 'Developpement Web',
                    'level' => 'Debutant',
                    'is_available' => true,
                    'publication_status' => 'published',
                    'is_promo_only' => false,
                ],
                'chapters' => [
                    [
                        'title' => 'Prise en main',
                        'lessons' => [
                            ['title' => 'Installer Laravel et configurer le projet', 'type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE', 'content' => 'Mise en place complete de l environnement de travail Laravel.'],
                            ['title' => 'Routing et controleurs', 'type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=MFh0Fd7BsjE', 'content' => 'Creer vos premieres routes et connecter des controleurs propres.'],
                        ],
                    ],
                    [
                        'title' => 'Fonctionnalites essentielles',
                        'lessons' => [
                            ['title' => 'CRUD avec Eloquent', 'type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=zx2v5l7M9Jk', 'content' => 'Construire un module CRUD complet avec migrations, modeles et formulaires.'],
                            ['title' => 'Authentification et autorisations', 'type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=0Rjsuw1ScXg', 'content' => 'Mettre en place login, inscription et restrictions d acces selon les roles.'],
                            ['title' => 'Publier une API securisee', 'type' => 'video', 'video_url' => 'https://www.youtube.com/watch?v=Ug-8rA5Q8d0', 'content' => 'Exposer des endpoints API avec validation, ressources et protection.'],
                        ],
                    ],
                ],
                'completed_lessons' => 1,
                'announcement' => [
                    'title' => 'Nouveau parcours video',
                    'message' => 'Le cours videos Laravel est disponible. Suivez les lecons dans l ordre pour progresser rapidement.',
                ],
            ],
            [
                'course' => [
                    'title' => 'Docker pour developpeurs Laravel',
                    'description' => 'Containeriser vos projets Laravel pour uniformiser les environnements local, CI et production.',
                    'category' => 'DevOps',
                    'level' => 'Intermediaire',
                    'is_available' => true,
                    'publication_status' => 'published',
                    'is_promo_only' => false,
                ],
                'chapters' => [
                    [
                        'title' => 'Bases Docker',
                        'lessons' => [
                            ['title' => 'Images, conteneurs et volumes', 'type' => 'text', 'content' => 'Comprendre les primitives Docker et leurs impacts pour un projet PHP moderne.'],
                            ['title' => 'Dockerfile pour Laravel', 'type' => 'text', 'content' => 'Construire une image propre avec extensions PHP, composer et optimisation cache.'],
                        ],
                    ],
                    [
                        'title' => 'Compose et workflow equipe',
                        'lessons' => [
                            ['title' => 'Composer une stack locale', 'type' => 'text', 'content' => 'Assembler php-fpm, nginx, mysql et redis dans un workflow de developpement stable.'],
                            ['title' => 'CI et deploiement', 'type' => 'text', 'content' => 'Reutiliser les images en CI pour fiabiliser tests et deploy.'],
                        ],
                    ],
                ],
                'completed_lessons' => 0,
                'announcement' => [
                    'title' => 'Ouverture module Docker',
                    'message' => 'Ce cours est disponible en parcours libre. Les inscriptions sont traitees en 72h.',
                ],
                'enrollment_status' => 'pending',
            ],
        ];

        foreach ($blueprints as $index => $blueprint) {
            $courseData = $blueprint['course'];
            $courseData['formateur_user_id'] = $teacher->id;

            $course = Course::updateOrCreate(
                ['title' => $courseData['title']],
                $courseData
            );

            $allLessons = collect();

            foreach ($blueprint['chapters'] as $chapterIndex => $chapterData) {
                $chapter = CourseChapter::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'title' => $chapterData['title'],
                    ],
                    [
                        'position' => $chapterIndex + 1,
                        'is_published' => true,
                    ]
                );

                foreach ($chapterData['lessons'] as $lessonIndex => $lessonData) {
                    $lesson = CourseLesson::updateOrCreate(
                        [
                            'course_chapter_id' => $chapter->id,
                            'title' => $lessonData['title'],
                        ],
                        [
                            'lesson_type' => $lessonData['type'] ?? 'text',
                            'content' => $lessonData['content'] ?? null,
                            'video_url' => $lessonData['video_url'] ?? null,
                            'position' => $lessonIndex + 1,
                            'is_published' => true,
                        ]
                    );

                    $allLessons->push($lesson);
                }
            }

            CourseAnnouncement::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $blueprint['announcement']['title'],
                ],
                [
                    'formateur_user_id' => $teacher->id,
                    'message' => $blueprint['announcement']['message'],
                    'is_published' => true,
                ]
            );

            if (! $learner || ! Schema::hasTable('course_enrollments')) {
                continue;
            }

            $enrollmentStatus = $blueprint['enrollment_status'] ?? CourseEnrollment::STATUS_APPROVED;
            $isApproved = $enrollmentStatus === CourseEnrollment::STATUS_APPROVED;

            CourseEnrollment::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'eleve_user_id' => $learner->id,
                ],
                [
                    'status' => $enrollmentStatus,
                    'requested_at' => now()->subDays(2 + $index),
                    'response_deadline_at' => $isApproved ? null : now()->addDays(1),
                    'decision_at' => $isApproved ? now()->subDays(1 + $index) : null,
                    'responded_by_user_id' => $isApproved ? $teacher->id : null,
                    'response_note' => $isApproved ? 'Inscription acceptee pour la simulation de suivi.' : null,
                    'enrolled_at' => $isApproved ? now()->subDays(1 + $index) : null,
                ]
            );

            if (! Schema::hasTable('lesson_progress') || ! $isApproved) {
                continue;
            }

            $completedCount = (int) ($blueprint['completed_lessons'] ?? 0);
            $completedLessons = $allLessons
                ->sortBy(function ($lesson) {
                    return sprintf('%04d-%04d', $lesson->course_chapter_id, $lesson->position);
                })
                ->values()
                ->take($completedCount);

            foreach ($completedLessons as $progressIndex => $lesson) {
                LessonProgress::updateOrCreate(
                    [
                        'course_lesson_id' => $lesson->id,
                        'eleve_user_id' => $learner->id,
                    ],
                    [
                        'is_completed' => true,
                        'completed_at' => now()->subDays(max(1, $index + $progressIndex + 1)),
                    ]
                );
            }
        }
    }
}
