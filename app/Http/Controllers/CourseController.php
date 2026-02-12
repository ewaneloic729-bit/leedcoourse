<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    // recuperer la liste des cours
    public function index()
    {
        $courses = Course::with('enseignants')->get();
        return View('courses.index', compact('courses'));
    }
    public function show($id)
    {
        $course = Course::with('enseignants')->findOrFail($id);
        return View('courses.show', compact('course'));
    }
    public function create()
    {

        return View('courses.create');
    }
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'enseignant_id' => 'required|exists:enseignants,id',
        ]);

        Course::create($validated);
        return redirect()->route('courses.index');
    }
}
