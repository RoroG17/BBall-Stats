<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;

class EquipeResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipes = Equipe::getAllEquipe();
        return response()->json($equipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'logo' => 'nullable|string',
        ]);
        $equipe = Equipe::createEquipe($validated);
        return response()->json($equipe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipe = Equipe::getEquipe($id);
        if (!$equipe) {
            return response()->json(['message' => 'Équipe non trouvée'], 404);
        }
        return response()->json($equipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipe = Equipe::getEquipe($id);
        if (!$equipe) {
            return response()->json(['message' => 'Équipe non trouvée'], 404);
        }
        $validated = $request->validate([
            'nom' => 'sometimes|required|string',
            'logo' => 'nullable|string',
        ]);
        $equipe->updateEquipe($validated);
        return response()->json($equipe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipe = Equipe::getEquipe($id);
        if (!$equipe) {
            return response()->json(['message' => 'Équipe non trouvée'], 404);
        }
        $equipe->deleteEquipe();
        return response()->json(['message' => 'Équipe supprimée']);
    }
}
