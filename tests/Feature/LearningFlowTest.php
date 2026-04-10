<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_eleve_can_enroll_in_available_course(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => true,
            'publication_status' => 'published',
        ]);

        $response = $this->actingAs($eleve)->post(route('apprenant.enrollments.store'), [
            'course_id' => $course->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('course_enrollments', [
            'course_id' => $course->id,
            'eleve_user_id' => $eleve->id,
        ]);
    }

    public function test_eleve_can_submit_evaluation_attempt(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Eval Course',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => true,
            'publication_status' => 'published',
        ]);

        $evaluation = Evaluation::create([
            'course_id' => $course->id,
            'formateur_user_id' => $formateur->id,
            'title' => 'Quiz 1',
            'type' => 'quiz',
            'total_points' => 10,
            'is_published' => true,
            'pass_score' => 5,
            'randomize_questions' => false,
        ]);

        $question = EvaluationQuestion::create([
            'evaluation_id' => $evaluation->id,
            'type' => 'qcm',
            'question' => '2+2 ?',
            'choices' => ['3', '4', '5'],
            'correct_choice' => '4',
            'points' => 2,
            'position' => 1,
        ]);

        $this->actingAs($eleve)->post(route('apprenant.enrollments.store'), [
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($eleve)->post(route('apprenant.evaluations.submit', $evaluation), [
            'attempt_id' => null,
            'answers' => [
                $question->id => '4',
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('evaluation');
        $this->assertDatabaseCount('evaluation_attempts', 0);

        $this->actingAs($eleve)->post(route('apprenant.evaluations.start', $evaluation));

        $attemptId = \App\Models\EvaluationAttempt::query()->value('id');

        $response = $this->actingAs($eleve)->post(route('apprenant.evaluations.submit', $evaluation), [
            'attempt_id' => $attemptId,
            'answers' => [
                $question->id => '4',
            ],
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('evaluation_attempts', [
            'evaluation_id' => $evaluation->id,
            'eleve_user_id' => $eleve->id,
            'score' => 2,
            'max_score' => 2,
            'status' => 'submitted',
        ]);
    }

    public function test_eleve_cannot_enroll_in_unavailable_course(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Cours brouillon',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => false,
            'publication_status' => 'draft',
        ]);

        $response = $this->actingAs($eleve)->post(route('apprenant.enrollments.store'), [
            'course_id' => $course->id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('course_id');
        $this->assertDatabaseMissing('course_enrollments', [
            'course_id' => $course->id,
            'eleve_user_id' => $eleve->id,
        ]);
    }

    public function test_eleve_cannot_submit_evaluation_if_not_enrolled(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Cours securise',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => true,
            'publication_status' => 'published',
        ]);

        $evaluation = Evaluation::create([
            'course_id' => $course->id,
            'formateur_user_id' => $formateur->id,
            'title' => 'Quiz securise',
            'type' => 'quiz',
            'total_points' => 10,
            'is_published' => true,
            'pass_score' => 5,
            'randomize_questions' => false,
        ]);

        $question = EvaluationQuestion::create([
            'evaluation_id' => $evaluation->id,
            'type' => 'qcm',
            'question' => '2+2 ?',
            'choices' => ['3', '4', '5'],
            'correct_choice' => '4',
            'points' => 2,
            'position' => 1,
        ]);

        $this->actingAs($eleve)->post(route('apprenant.evaluations.start', $evaluation));

        $response = $this->actingAs($eleve)->post(route('apprenant.evaluations.submit', $evaluation), [
            'attempt_id' => 1,
            'answers' => [
                $question->id => '4',
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('evaluation');
        $this->assertDatabaseCount('evaluation_attempts', 0);
    }

    public function test_eleve_cannot_start_before_opening_time(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Cours planifie',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => true,
            'publication_status' => 'published',
        ]);

        $this->actingAs($eleve)->post(route('apprenant.enrollments.store'), [
            'course_id' => $course->id,
        ]);

        $evaluation = Evaluation::create([
            'course_id' => $course->id,
            'formateur_user_id' => $formateur->id,
            'title' => 'Examen planifie',
            'type' => 'examen',
            'total_points' => 20,
            'is_published' => true,
            'opens_at' => now()->addHour(),
            'due_at' => now()->addHours(2),
            'pass_score' => 10,
        ]);

        $response = $this->actingAs($eleve)->post(route('apprenant.evaluations.start', $evaluation));
        $response->assertStatus(302);
        $response->assertSessionHasErrors('evaluation');
        $this->assertDatabaseCount('evaluation_attempts', 0);
    }

    public function test_eleve_cannot_mark_lesson_complete_if_not_enrolled(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Cours progression',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => true,
            'publication_status' => 'published',
        ]);

        $chapter = CourseChapter::create([
            'course_id' => $course->id,
            'title' => 'Chapitre 1',
            'position' => 1,
            'is_published' => true,
        ]);

        $lesson = CourseLesson::create([
            'course_chapter_id' => $chapter->id,
            'title' => 'Lecon 1',
            'content' => 'Contenu',
            'position' => 1,
            'is_published' => true,
        ]);

        $response = $this->actingAs($eleve)->post(route('apprenant.lessons.complete', $lesson));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('lesson');
        $this->assertDatabaseCount('lesson_progress', 0);
    }

    public function test_eleve_can_open_lesson_when_enrolled(): void
    {
        $eleve = User::factory()->create(['role' => User::ROLE_ELEVE]);
        $formateur = User::factory()->create(['role' => User::ROLE_ENSEIGNANT]);

        $course = Course::create([
            'title' => 'Cours lecture',
            'description' => 'Description',
            'category' => 'Informatique',
            'level' => 'Debutant',
            'formateur_user_id' => $formateur->id,
            'is_available' => true,
            'publication_status' => 'published',
        ]);

        $chapter = CourseChapter::create([
            'course_id' => $course->id,
            'title' => 'Chapitre A',
            'position' => 1,
            'is_published' => true,
        ]);

        $lesson = CourseLesson::create([
            'course_chapter_id' => $chapter->id,
            'title' => 'Lecon A',
            'content' => 'Contenu de la lecon',
            'position' => 1,
            'is_published' => true,
        ]);

        $this->actingAs($eleve)->post(route('apprenant.enrollments.store'), [
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($eleve)->get(route('apprenant.lessons.show', $lesson));

        $response->assertStatus(200);
        $response->assertSee('Contenu de la lecon');
    }
}
