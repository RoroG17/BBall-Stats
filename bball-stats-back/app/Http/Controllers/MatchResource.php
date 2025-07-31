<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matchs;
use App\Models\Equipe;
use App\Models\Jouer;
use App\Models\Saison;

class MatchResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matchs = Matchs::getAllMatchs();
        return response()->json($matchs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = validator($request->input('data'), [
            'date_match' => 'required|date',
            'numero' => 'required|integer',
            'equipe_domicile' => 'required|integer',
            'equipe_exterieur' => 'required|integer',
            'Id_Saison' => 'required|integer', 
        ])->validate();
        
        $match = Matchs::createMatch($validated);
        return response()->json($match, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $match = Matchs::getMatch($id);
        $stats = Jouer::getStatsMatch($id);
        if (!$match) {
            return response()->json(['message' => 'Match non trouvé'], 404);
        }
        return response()->json(["match" => $match, "stats" => $stats])->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $match = Matchs::find($id);
        if (!$match) {
            return response()->json(['message' => 'Match non trouvé'], 404);
        }
        $validated = $request->validate([
            'date_match' => 'sometimes|required|date',
            'numero' => 'sometimes|required|integer',
            'domicile' => 'sometimes|required|boolean',
            'score_f' => 'sometimes|required|integer',
            'score_a' => 'sometimes|required|integer',
            'Id_Equipe' => 'sometimes|required|integer',
            'Id_Saison' => 'sometimes|required|integer',
        ]);
        if (isset($validated['Id_Equipe']) && !Equipe::existeEquipe($validated['Id_Equipe'])) {
            return response()->json(['message' => "L'équipe n'existe pas"], 404);
        }
        if (isset($validated['Id_Saison']) && !Saison::existeSaison($validated['Id_Saison'])) {
            return response()->json(['message' => "La saison n'existe pas"], 404);
        }
        $match->updateMatch($validated);
        return response()->json($match);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $match = Matchs::find($id);
        if (!$match) {
            return response()->json(['message' => 'Match non trouvé'], 404);
        }
        $match->deleteMatch();
        return response()->json(['message' => 'Match supprimé']);
    }
}
