<?php

namespace App\Http\Controllers;

use App\Models\EvaluationAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnseignantGradebookController extends Controller
{
    public function index(Request $request)
    {
        $rows = collect();

        if (Schema::hasTable('evaluation_attempts')) {
            $rows = EvaluationAttempt::with(['eleve', 'evaluation.course', 'evaluation.questions', 'answerDetails'])
                ->whereHas('evaluation', function ($query) use ($request) {
                    $query->where('formateur_user_id', optional($request->user())->id);
                })
                ->latest('submitted_at')
                ->paginate(20);
        } else {
            $rows = collect();
        }

        return view('dashboards.enseignant-gradebook', [
            'rows' => $rows,
            'setupMissing' => ! Schema::hasTable('evaluation_attempts'),
        ]);
    }

    public function exportCsv(Request $request)
    {
        if (! Schema::hasTable('evaluation_attempts')) {
            return back()->withErrors(['csv' => 'Le module de notes n est pas initialise.']);
        }

        $rows = EvaluationAttempt::with(['eleve', 'evaluation.course'])
            ->whereHas('evaluation', function ($query) use ($request) {
                $query->where('formateur_user_id', optional($request->user())->id);
            })
            ->latest('submitted_at')
            ->get();

        $response = new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Apprenant', 'Email', 'Cours', 'Evaluation', 'Score', 'Max', 'Date']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    optional($row->eleve)->name,
                    optional($row->eleve)->email,
                    optional(optional($row->evaluation)->course)->title,
                    optional($row->evaluation)->title,
                    $row->score,
                    $row->max_score,
                    optional($row->submitted_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        });

        $filename = 'carnet-notes-'.now()->format('Ymd-His').'.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');

        return $response;
    }
}
