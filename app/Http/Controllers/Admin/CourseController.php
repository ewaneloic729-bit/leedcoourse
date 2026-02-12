<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Affiche le formulaire
    public function create()
    {
        return view('admin.courses.create');
    }

    // Enregistre le cours
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category' => 'required',
            'image' => 'nullable|image'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'level' => $request->level,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Cours créé avec succès');
    }
}
