<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enseignant;

class EnseignantController extends Controller
{
    //liste des enseignants
    public function index()
    {
        $enseignants = Enseignant::all();
        return view('enseignantsIndex', compact('enseignants'));
    }
    public function show($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        return view('enseignants.show', compact('enseignant'));
    }
    // afficher le formulaire de création
    public function create()
    {
        return view('enseignantForm');
    }

    // traiter la soumission du formulaire de création
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:enseignants',
        ]);

        Enseignant::create($validated);
        return redirect()->route('enseignants.index');
    }
   

    // afficher le formulaire d'édition
    public function edit($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        return view('enseignants.edit', compact('enseignant'));
    }

    // traiter la soumission du formulaire d'édition
    public function update(Request $request, $id)
    {
        $enseignant = Enseignant::findOrFail($id);

      // Valider les données du formulaire
      $validated = $request->validate([
          'nom' => 'required|string|max:255',
          'prenom' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:enseignants,email,' . $enseignant->id,
      ]);

      $enseignant->update($validated);
      return redirect()->route('enseignants.index');
  }
}
