<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saison;

class SaisonResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saisons = Saison::getAllSaison();
        return response()->json($saisons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = validator($request->input('data'), [
            'annee_debut' => 'required|integer',
            'annee_fin' => 'required|integer',
            'championnat' => 'required|string',
            'categorie' => 'required|string',
        ])->validate();
        $saison = Saison::createSaison($validated);
        return response()->json($saison, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $saison = Saison::getSaison($id);
        if (!$saison) {
            return response()->json(['message' => 'Saison non trouvée'], 404);
        }
        return response()->json($saison);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $saison = Saison::getSaison($id);
        if (!$saison) {
            return response()->json(['message' => 'Saison non trouvée'], 404);
        }
        $validated = $request->validate([
            'annee_debut' => 'sometimes|required|integer',
            'annee_fin' => 'sometimes|required|integer',
            'championnat' => 'sometimes|required|string',
            'categorie' => 'sometimes|required|string',
        ]);
        $saison->updateSaison($validated);
        return response()->json($saison);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $saison = Saison::getSaison($id);
        if (!$saison) {
            return response()->json(['message' => 'Saison non trouvée'], 404);
        }
        $saison->deleteSaison();
        return response()->json(['message' => 'Saison supprimée']);
    }
}
