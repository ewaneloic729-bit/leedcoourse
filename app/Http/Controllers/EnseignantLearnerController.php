<?php

namespace App\Http\Controllers;

use App\Models\DevoirSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnseignantLearnerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $allEleves = User::where('role', User::ROLE_ELEVE)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $rows = collect();

        if (Schema::hasTable('devoir_submissions')) {
            $submissions = DevoirSubmission::query()
                ->whereHas('course', function ($query) use ($user) {
                    if (Schema::hasColumn('courses', 'formateur_user_id') && $user) {
                        $query->where('formateur_user_id', $user->id);
                    }
                })
                ->latest()
                ->get();

            $statsByEleve = $submissions
                ->whereNotNull('eleve_user_id')
                ->groupBy('eleve_user_id')
                ->map(function ($items) {
                    return [
                        'total' => $items->count(),
                        'pending' => $items->where('status', 'pending')->count(),
                        'in_review' => $items->where('status', 'in_review')->count(),
                        'corrected' => $items->where('status', 'corrected')->count(),
                        'average' => round((float) $items->whereNotNull('score')->avg('score'), 2),
                        'last_submission' => optional($items->first())->created_at,
                    ];
                });

            $rows = $allEleves->map(function ($eleve) use ($statsByEleve) {
                $stat = $statsByEleve->get($eleve->id, [
                    'total' => 0,
                    'pending' => 0,
                    'in_review' => 0,
                    'corrected' => 0,
                    'average' => null,
                    'last_submission' => null,
                ]);

                return [
                    'name' => $eleve->name,
                    'email' => $eleve->email,
                    'total' => $stat['total'],
                    'pending' => $stat['pending'],
                    'in_review' => $stat['in_review'],
                    'corrected' => $stat['corrected'],
                    'average' => $stat['average'],
                    'last_submission' => $stat['last_submission'],
                ];
            });
        } else {
            $rows = $allEleves->map(function ($eleve) {
                return [
                    'name' => $eleve->name,
                    'email' => $eleve->email,
                    'total' => 0,
                    'pending' => 0,
                    'in_review' => 0,
                    'corrected' => 0,
                    'average' => null,
                    'last_submission' => null,
                ];
            });
        }

        $query = trim((string) $request->query('q'));
        if ($query !== '') {
            $rows = $rows->filter(function ($row) use ($query) {
                return stripos($row['name'], $query) !== false
                    || stripos($row['email'], $query) !== false;
            })->values();
        }

        return view('dashboards.enseignant-apprenants', [
            'rows' => $rows,
            'query' => $query,
        ]);
    }
}

