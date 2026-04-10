<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_available' => 'nullable|boolean',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        $payload = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'level' => $request->level,
            'image' => $imagePath,
        ];

        if (Schema::hasColumn('courses', 'formateur_user_id')) {
            $payload['formateur_user_id'] = optional($request->user())->id;
        }

        if (Schema::hasColumn('courses', 'is_available')) {
            $payload['is_available'] = $request->boolean('is_available');
        }

        if (Schema::hasColumn('courses', 'publication_status')) {
            $payload['publication_status'] = $request->boolean('is_available') ? 'published' : 'draft';
        }

        Course::create($payload);

        return redirect()->back()->with('success', 'Cours créé avec succès');
    }
}
