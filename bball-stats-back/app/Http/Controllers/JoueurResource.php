<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Joueur;

class JoueurResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $joueurs = Joueur::getAllJoueurs();
        return response()->json($joueurs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'licence' => 'required|string|unique:joueurs,licence',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'civilite' => 'required|string',
            'date_naissance' => 'required|date',
            'photo' => 'nullable|string',
        ]);
        $joueur = Joueur::createJoueur($validated);
        return response()->json($joueur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $licence)
    {
        $joueur = Joueur::getJoueur($licence);
        if (!$joueur) {
            return response()->json(['message' => 'Joueur non trouvé'], 404);
        }
        return response()->json($joueur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $licence)
    {
        $joueur = Joueur::getJoueur($licence);
        if (!$joueur) {
            return response()->json(['message' => 'Joueur non trouvé'], 404);
        }
        $validated = $request->validate([
            'nom' => 'sometimes|required|string',
            'prenom' => 'sometimes|required|string',
            'civilite' => 'sometimes|required|string',
            'date_naissance' => 'sometimes|required|date',
            'photo' => 'nullable|string',
        ]);
        $joueur->updateJoueur($validated);
        return response()->json($joueur);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $licence)
    {
        $joueur = Joueur::getJoueur($licence);
        if (!$joueur) {
            return response()->json(['message' => 'Joueur non trouvé'], 404);
        }
        $joueur->deleteJoueur();
        return response()->json(['message' => 'Joueur supprimé']);
    }
}
